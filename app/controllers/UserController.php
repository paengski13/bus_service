<?php

class UserController extends \BaseController 
{
    // determine if user has access to this page
    private $page_access = '';
    
    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
        
        // check if user has access to this module
        if (Auth::check()) {
            if (!$this->checkAccess(USER_TITLE, Auth::user()->access->toArray()) && Auth::user()->user_admin == NO) {
                $this->page_access = NO_ACCESS;
            }
        }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // redirect to home page if user has no access to this page
        if ($this->page_access) {
            return Redirect::to(strtolower(HOME_TITLE));
        }
        
        // header and module properties
        $this->data['page_module'] = USER_MODULE;
        $this->data['page_title'] = USER_TITLE;
    
        // search variables here
        $s_firstname = $this->getInput('s_firstname', '');
        $s_lastname = $this->getInput('s_lastname', '');
        $s_status = $this->getInput('s_status', ACTIVE);
        
        // order variables here
        $sort_by = $this->getInput('sort_by', 'user.created_at');
        $order_by = $this->getInput('order_by', DESCENDING);
        
        // search user status
        $users = User::userStatus($s_status)
            ->userFirstname($s_firstname)
            ->userLastname($s_lastname)
            ->userSort($sort_by, $order_by)
            ->paginate(PAGINATION_LIMIT);
        // user records
        $this->data["users"] = $users;
        
        // previous search values
        $this->data["s_firstname"] = $s_firstname;
        $this->data["s_lastname"] = $s_lastname;
        $this->data["s_status"] = $s_status;
        
        // list of status
        $this->data["status"] = getOptionStatus(USER_TITLE);
        
        // current record number
        $this->data["count"] = $users->getFrom();
        
        // search parameter to the pagination
        $this->data["url_pagination"] = array('sort_by' => $sort_by, 'order_by' => $order_by, 's_firstname' => $s_firstname, 's_lastname' => $s_lastname, 's_status' => $s_status);

        // search parameter to the sorting
        $this->data["url_sort"] = array('sort_by' => $sort_by, 'order_by' => getSortOrder($order_by), 
                                        's_firstname' => $s_firstname, 's_lastname' => $s_lastname, 's_status' => $s_status,
                                        'page' => $users->getCurrentPage());

        // load the view and pass the user records
        return View::make('setup.user.index')->with('data', $this->data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // header and module properties
        $this->data['page_module'] = USER_MODULE;
        $this->data['page_title'] = USER_TITLE;
        
        // to preserve previous url, check if the validation failed
        if (!Session::has('danger')) {
            $this->setPreviousListURL(strtolower(USER_TITLE));
            
            // also remove the user_photo session
            Session::forget('user_photo');
        }

        // get countries
        $this->data["countries"] = Country::countryStatus(ACTIVE)->countrySort('country_name', ASCENDING)->get();
        
        // get gender
        $this->data["gender"] = getOptionGender();
        
        // get civil status
        $this->data["civil_status"] = getOptionCivilStatus();
        
        // get civil status
        $this->data["relationship"] = getOptionRelationship();
        
        // get page access
        $this->data["access"] = Access::accessStatus(ACTIVE)
            ->accessSort('access_sort', ASCENDING)->get();
        
        // get status
        $this->data["status"] = getOptionStatus(USER_TITLE);
        
        // get previously selected picture
        if (Session::has('user_photo')) {
            $this->data['user_photo'] = Session::get('user_photo');
        } else {
            $this->data['user_photo'] = "";
        }
        
        // load the create form
        return View::make('setup.user.create')->with('data', $this->data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'user_firstname' => 'required',
            'user_gender' => 'required',
            'user_joined_date' => 'required|date_format:"'.DATE_FORMAT_2,
            'user_left_date' => 'date_format:"'.DATE_FORMAT_2,
            'user_email' => 'required|email|unique:user,user_email,NULL,id,deleted_at,NULL',
            'country_key1' => 'required',
            'user_contact_phone_number1' => 'required',
            'user_status' => 'required',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // redirect to list page
            Session::flash('danger', UNABLE_TO_SAVE);
            return Redirect::to(URL::previous())
                ->withErrors($validator)
                ->withInput();

        } else {
            
            // compose the fields to be updated
            $data = array();
            $data['user_key'] = generateRandomID();
            $data['user_firstname'] = $this->getInput('user_firstname', '');
            $data['user_middlename'] = $this->getInput('user_middlename', '');
            $data['user_lastname'] = $this->getInput('user_lastname', '');
            $data['user_alias'] = $this->getInput('user_alias', '');
            $data['user_gender'] = $this->getInput('user_gender', '');
            $data['user_civil_status'] = $this->getInput('user_civil_status', '');
            $data['user_birth_date'] = \Carbon\Carbon::createFromFormat(DATE_FORMAT_1, $this->getInput('user_birth_date', DEFAULT_DATE))->format(DB_DATE_FORMAT);
            $data['user_joined_date'] = $this->getInput('user_joined_date', '');
            $data['user_left_date'] = $this->getInput('user_left_date', '');
            $data['user_email'] = $this->getInput('user_email', '');
            $data['user_hometown_address'] = $this->getInput('user_hometown_address', '');
            $data['user_overseas_address'] = $this->getInput('user_overseas_address', '');
            if (Session::has('user_photo')) {
                $data['user_photo'] = Session::get('user_photo');
                Session::forget('user_photo');
            } else {
                $data['user_photo'] = "";
            }
            $data['user_admin'] = NO;
            $data['user_status'] = $this->getInput('user_status', '');
            $data['created_by'] = Auth::user()->id;
                
            // create record
            $user = User::create($data);
            
            // create record
            for ($cnt = 1; $cnt <= $this->getInput('hdn_increment', ''); $cnt++) {
                if ($this->getInput('hdn_index' . $cnt, '') == YES && 
                    $this->getInput('country_key' . $cnt, '') != EMPTY_STRING && 
                    $this->getInput('user_contact_phone_number' . $cnt, '') != EMPTY_STRING) {
                    $data = array();
                    $data['user_contact_key'] = generateRandomID();
                    $data['user_id'] = $user->id;
                    $data['country_id'] = Country::countryKey($this->getInput('country_key' . $cnt, ''))->pluck('id');
                    $data['user_contact_phone_number'] = $this->getInput('user_contact_phone_number' . $cnt, '');
                    $data['created_by'] = Auth::user()->id;
                    
                    // create record
                    UserContact::create($data);
                }
            }
            
            $data = array();
            $data['user_emergency_key'] = generateRandomID();
            $data['user_id'] = $user->id;
            $data['country_id'] = Country::countryKey($this->getInput('emergency_country_key', ''))->pluck('id');
            $data['user_emergency_name'] = $this->getInput('user_emergency_name', '');
            $data['user_emergency_relation'] = $this->getInput('user_emergency_relation', '');
            $data['user_emergency_address'] = $this->getInput('user_emergency_address', '');
            $data['user_emergency_phone'] = $this->getInput('user_emergency_phone', '');
            $data['created_by'] = Auth::user()->id;
            
            // create record
            UserEmergency::create($data);
            
            // create access record
            if (is_array($this->getInput('access_user', array()))) {
                foreach($this->getInput('access_user', array()) as $access_id) {
                    $data = array();
                    $data['user_id'] = $user->id;
                    $data['access_id'] = $access_id;
                    $data['created_by'] = Auth::user()->id;
                    
                    // create record
                    AccessUser::create($data);
                }
            }
            
            // redirect to list page
            Session::flash('success', SUCCESS_CREATE);
            return Redirect::to($this->getPreviousListURL());
        }
    }


    /**
     * Display user invite form
     *
     * @return Response
     */
    public function createInvite()
    {
        // header and module properties
        $this->data['page_module'] = USER_MODULE;
        $this->data['page_title'] = USER_TITLE;
        
        // to preserve previous url, check if the validation failed
        if (!Session::has('danger')) {
            $this->setPreviousListURL(strtolower(USER_TITLE));
        }
        
        // load the create form
        return View::make('setup.user.create_invite')->with('data', $this->data);
    }


    /**
     * Save and send email to the invited user
     *
     * @return Response
     */
    public function sendInvite()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'user_firstname' => 'required',
            'user_email' => 'required|email|unique:user,user_email,NULL,id,deleted_at,NULL',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // redirect to list page
            Session::flash('danger', UNABLE_TO_SAVE);
            return Redirect::to(URL::previous())
                ->withErrors($validator)
                ->withInput();

        } else {            
            
            // compose the fields to be updated
            $data = array();
            $data['user_key'] = generateRandomID();
            $this->data['user_key'] = $data['user_key'];
            $data['user_firstname'] = $this->getInput('user_firstname', '');
            $data['user_lastname'] = $this->getInput('user_lastname', '');
            $data['user_email'] = $this->getInput('user_email', '');
            $data['user_status'] = LOCKED;
            $data['user_admin'] = NO;
            $data['created_by'] = Auth::user()->id;
            // create record
            $user = User::create($data);
            
            // make sure that the user was successfully created before proceeding to the next process
            if (isset($user->id)) {
                // create blank contact record
                $data = array();
                $data['user_contact_key'] = generateRandomID();
                $data['user_id'] = $user->id;
                $data['created_by'] = Auth::user()->id;
                UserContact::create($data);
                
                // create blank emergency contact record
                $data = array();
                $data['user_emergency_key'] = generateRandomID();
                $data['user_id'] = $user->id;
                $data['created_by'] = Auth::user()->id;
                UserEmergency::create($data);
                
                /*// create email content
                $data = array();
                $data['email_key'] = generateRandomID();
                $data['email_subject'] = USER_CREATION_INVITE_SUBJECT_EMAIL;
                $data['email_content'] = View::make('email_template.invite_email')->with('data', $this->data);
                $data['email_status'] = PENDING;
                $data['created_by'] = Auth::user()->id;
                $email = Email::create($data);
                
                // create email recipients
                $data = array();
                $data['email_id'] = $email->id;
                $data['email_recipient_key'] = generateRandomID();
                $data['email_recipient_address'] = $user->user_email;
                $data['email_recipient_type'] = RECIPIENT_TO;
                $data['created_by'] = Auth::user()->id;
                $email_recipient = EmailRecipient::create($data);*/
                
                // send mail
                Mail::send(['html' => 'email_template.invite_email'], array('user_key' => $user->user_key), function($message) use ($user)
                {
                    $message->to($user->user_email);
                    $message->subject(USER_CREATION_INVITE_SUBJECT_EMAIL);
                });
            }
            
            // redirect to list page
            Session::flash('success', SUCCESS_CREATE);
            return Redirect::to($this->getPreviousListURL());
        }
    }


    /**
     * validate the invite of the user
     *
     * @return Response
     */
    public function processInvite($id)
    {
        // header and module properties
        $this->data['page_module'] = USER_MODULE;
        $this->data['page_title'] = USER_TITLE;
        
        // to preserve previous url, check if the validation failed
        if (!Session::has('danger')) {
            $this->setPreviousListURL(strtolower(USER_TITLE)); 
            
            // also remove the user_photo session
            Session::forget('user_photo');
        }
        
        // get the selected record
        $user = User::userKey($id)->first();
        
        // check if the record really exist
        if (is_null($user)) {
            // redirect to list page
            Session::flash('danger', SOMETHING_WENT_WRONG);
            return Redirect::to(strtolower(USER_TITLE));
        }
        
        // get record
        $this->data["user"] = $user;
        
        // get record
        $this->data["user_contact"] = UserContact::userId($user->id)->get();

        // get countries
        $this->data["countries"] = Country::countryStatus(ACTIVE)->countrySort('country_name', ASCENDING)->get();
        
        // get gender
        $this->data["gender"] = getOptionGender();
        
        // get civil status
        $this->data["civil_status"] = getOptionCivilStatus();
        
        // get civil status
        $this->data["relationship"] = getOptionRelationship();
        
        // get page access
        $this->data["access"] = Access::with(array('user' => function($query) use ($user)
            {
                $query->where('user_id', $user->id);
            }))
            ->accessStatus(ACTIVE)
            ->accessSort('access_sort', ASCENDING)->get();
        
        // get status
        $this->data["status"] = getOptionStatus(USER_TITLE);
        
        // get previously selected picture
        if (Session::has('user_photo')) {
            $this->data['user_photo'] = Session::get('user_photo');
        } else {
            $this->data['user_photo'] = "";
        }

        // load the show form
        return View::make('setup.user.confirm_invite')->with('data', $this->data);
    }
    
    
    /**
     * Confirm user as member
     *
     * @param  int  $id
     * @return Response
     */
    public function confirmInvite($id)
    {   
        // validate the info, create rules for the inputs
        $rules = array(
            'user_firstname' => 'required',
            'user_gender' => 'required',
            'user_joined_date' => 'required|date_format:"'.DATE_FORMAT_2,
            'user_left_date' => 'date_format:"'.DATE_FORMAT_2,
            'user_email' => 'required|email|unique:user,user_email,' . $id . ',user_key,deleted_at,NULL',
            'password' => 'required',
            'country_key1' => 'required',
            'user_contact_phone_number1' => 'required',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // redirect to list page
            Session::flash('danger', UNABLE_TO_SAVE);
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();

        } else {
            // where condition
            $user = User::userKey($id)->first();
            
            // check if the record can be updated
            if (empty($user->id)) {
                // redirect to list page
                Session::flash('danger', SOMETHING_WENT_WRONG);
                return Redirect::to(strtolower(USER_TITLE));
            }
            
            // fields to be updated
            $user->user_firstname = $this->getInput('user_firstname', '');
            $user->user_middlename = $this->getInput('user_middlename', '');
            $user->user_lastname = $this->getInput('user_lastname', '');
            $user->user_alias = $this->getInput('user_alias', '');
            $user->user_gender = $this->getInput('user_gender', '');
            $user->user_civil_status = $this->getInput('user_civil_status', '');
            $user->user_birth_date = \Carbon\Carbon::createFromFormat(DATE_FORMAT_1, $this->getInput('user_birth_date', DEFAULT_DATE))->format(DB_DATE_FORMAT);
            $user->user_joined_date = $this->getInput('user_joined_date', '');
            $user->user_left_date = $this->getInput('user_left_date', '');
            $user->user_email = $this->getInput('user_email', '');
            $user->password = Hash::make($this->getInput('password', ''));
            $user->user_hometown_address = $this->getInput('user_hometown_address', '');
            $user->user_overseas_address = $this->getInput('user_overseas_address', '');
            if (Session::has('user_photo')) {
                $user->user_photo = Session::get('user_photo');
                Session::forget('user_photo');
            }
            $user->user_status = ACTIVE;
            $user->updated_by = $user->id;
            
            // update record
            $user->save();
            
            for ($cnt = 1; $cnt <= $this->getInput('hdn_increment', ''); $cnt++) {
                if ($this->getInput('hdn_index' . $cnt, '') == YES && $this->getInput('country_key' . $cnt, '') != EMPTY_STRING && $this->getInput('user_contact_phone_number' . $cnt, '') != EMPTY_STRING) {
                    if ($this->getInput('user_contact_key' . $cnt, '') == EMPTY_STRING) {
                        $data = array();
                        $data['user_contact_key'] = generateRandomID();
                        $data['user_id'] = $user->id;
                        $data['country_id'] = Country::countryKey($this->getInput('country_key' . $cnt, ''))->pluck('id');
                        $data['user_contact_phone_number'] = $this->getInput('user_contact_phone_number' . $cnt, '');
                        $data['created_by'] = $user->id;
                        
                        // create record
                        UserContact::create($data);
                    } else {
                        // where condition
                        $user_contact = UserContact::UserContactKey($this->getInput('user_contact_key' . $cnt, ''))->first();
                        
                        // check if the record can be updated
                        if (isset($user_contact->id)) {
                            $user_contact->country_id = Country::countryKey($this->getInput('country_key' . $cnt, ''))->pluck('id');
                            $user_contact->user_contact_phone_number = $this->getInput('user_contact_phone_number' . $cnt, '');
                            $user_contact->updated_by = $user->id;
                    
                            // update record
                            $user_contact->save();
                        }
                    }
                }
            }
            
            // where condition
            $user_emergency = UserEmergency::userId($user->id)->first();
            
            // check if the record can be updated
            if (!empty($user_emergency->id)) {
                // fields to be updated
                $user_emergency->user_emergency_name = $this->getInput('user_emergency_name', '');
                $user_emergency->user_emergency_relation = $this->getInput('user_emergency_relation', '');
                $user_emergency->user_emergency_address = $this->getInput('user_emergency_address', '');
                $user_emergency->country_id = Country::countryKey($this->getInput('emergency_country_key', ''))->pluck('id');
                $user_emergency->user_emergency_phone = $this->getInput('user_emergency_phone', '');
                $user_emergency->updated_by = $user->id;
                    
                // update record
                $user_emergency->save();
            }
            
            $this->data['user_fullname'] = $user->user_fullname;
            
            /*// send notification email to all active members
            // create email content
            $data = array();
            $data['email_key'] = generateRandomID();
            $data['email_subject'] = USER_CREATION_WELCOME_SUBJECT_EMAIL;
            $data['email_content'] = View::make('email_template.new_member_email')->with('data', $this->data);
            $data['email_status'] = PENDING;
            $data['created_by'] = $user->id;
            $email = Email::create($data);
            
            // get list of active users
            $users = User::userStatus(ACTIVE)->where('id', '!=', $user->id)->get();
            foreach ($users as $record) {
                // create email recipients
                $data = array();
                $data['email_id'] = $email->id;
                $data['email_recipient_key'] = generateRandomID();
                $data['email_recipient_address'] = $record->user_email;
                $data['email_recipient_type'] = RECIPIENT_TO;
                $data['created_by'] = $user->id;
                EmailRecipient::create($data);
            }*/
            
            // send mail
            $user_info = array('user_fullname' => $user->user_fullname,
                               'user_photo' => $user->user_photo, );
            Mail::send(['html' => 'email_template.new_member_email'], $user_info, function($message) use ($user)
            {
                // get list of active users
                $users = User::userStatus(ACTIVE)->where('id', '!=', $user->id)->get();
            
                foreach ($users as $record) {
                    $message->to($record->user_email);
                }
                
                $message->cc($user->user_email);
                $message->subject(USER_CREATION_WELCOME_SUBJECT_EMAIL);
            });
            
            // redirect to list page
            Session::flash('success', SUCCESS_ACTIVATE);
            return View::make('session.login')->with('data', $this->data);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  string unique $id
     * @return Response
     */
    public function show($id)
    {
    
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  string unique $id
     * @return Response
     */
    public function edit($id)
    {
        // header and module properties
        $this->data['page_module'] = USER_MODULE;
        $this->data['page_title'] = USER_TITLE;
        
        // to preserve previous url, check if the validation failed
        if (!Session::has('danger')) {
            $this->setPreviousListURL(strtolower(USER_TITLE)); 
            
            // also remove the user_photo session
            Session::forget('user_photo');
        }
        
        // get the selected record
        $user = User::userKey($id)->first();
        
        // check if the record really exist
        if (is_null($user)) {
            // redirect to list page
            Session::flash('danger', SOMETHING_WENT_WRONG);
            return Redirect::to(strtolower(USER_TITLE));
        }
        
        // get record
        $this->data["user"] = $user;
        
        // get record
        $this->data["user_contact"] = UserContact::userId($user->id)->get();

        // get countries
        $this->data["countries"] = Country::countryStatus(ACTIVE)->countrySort('country_name', ASCENDING)->get();
        
        // get gender
        $this->data["gender"] = getOptionGender();
        
        // get civil status
        $this->data["civil_status"] = getOptionCivilStatus();
        
        // get civil status
        $this->data["relationship"] = getOptionRelationship();
        
        // get page access
        $this->data["access"] = Access::with(array('user' => function($query) use ($user)
            {
                $query->where('user_id', $user->id);
            }))
            ->accessStatus(ACTIVE)
            ->accessSort('access_sort', ASCENDING)->get();
        
        // get status
        $this->data["status"] = getOptionStatus(USER_TITLE);
        
        // get previously selected picture
        if (Session::has('user_photo')) {
            $this->data['user_photo'] = Session::get('user_photo');
        } else {
            $this->data['user_photo'] = "";
        }

        // load the show form
        return View::make('setup.user.show')->with('data', $this->data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'user_firstname' => 'required',
            'user_gender' => 'required',
            'user_joined_date' => 'required|date_format:"'.DATE_FORMAT_2,
            'user_left_date' => 'date_format:"'.DATE_FORMAT_2,
            'user_email' => 'required|email|unique:user,user_email,' . $id . ',user_key,deleted_at,NULL',
            'country_key1' => 'required',
            'user_contact_phone_number1' => 'required',
            'user_status' => 'required',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            // redirect to list page
            Session::flash('danger', UNABLE_TO_SAVE);
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();

        } else {
            // where condition
            $user = User::userKey($id)->first();
            
            // check if the record can be updated
            if (empty($user->id)) {
                // redirect to list page
                Session::flash('danger', SOMETHING_WENT_WRONG);
                return Redirect::to(strtolower(USER_TITLE));
            }
            
            // fields to be updated
            $user->user_firstname = $this->getInput('user_firstname', '');
            $user->user_middlename = $this->getInput('user_middlename', '');
            $user->user_lastname = $this->getInput('user_lastname', '');
            $user->user_alias = $this->getInput('user_alias', '');
            $user->user_gender = $this->getInput('user_gender', '');
            $user->user_civil_status = $this->getInput('user_civil_status', '');
            $user->user_birth_date = \Carbon\Carbon::createFromFormat(DATE_FORMAT_1, $this->getInput('user_birth_date', DEFAULT_DATE))->format(DB_DATE_FORMAT);
            $user->user_joined_date = $this->getInput('user_joined_date', '');
            $user->user_left_date = $this->getInput('user_left_date', '');
            $user->user_email = $this->getInput('user_email', '');
            $user->user_hometown_address = $this->getInput('user_hometown_address', '');
            $user->user_overseas_address = $this->getInput('user_overseas_address', '');
            if (Session::has('user_photo')) {
                $user->user_photo = Session::get('user_photo');
                Session::forget('user_photo');
            }
            $user->user_status = $this->getInput('user_status', '');
            $user->updated_by = Auth::user()->id;
            
            // update record
            $user->save();
            
            for ($cnt = 1; $cnt <= $this->getInput('hdn_increment', ''); $cnt++) {
                if ($this->getInput('hdn_index' . $cnt, '') == YES && $this->getInput('country_key' . $cnt, '') != EMPTY_STRING && $this->getInput('user_contact_phone_number' . $cnt, '') != EMPTY_STRING) {
                    if ($this->getInput('user_contact_key' . $cnt, '') == EMPTY_STRING) {
                        $data = array();
                        $data['user_contact_key'] = generateRandomID();
                        $data['user_id'] = $user->id;
                        $data['country_id'] = Country::countryKey($this->getInput('country_key' . $cnt, ''))->pluck('id');
                        $data['user_contact_phone_number'] = $this->getInput('user_contact_phone_number' . $cnt, '');
                        $data['created_by'] = Auth::user()->id;
                        
                        // create record
                        UserContact::create($data);
                    } else {
                        // where condition
                        $user_contact = UserContact::UserContactKey($this->getInput('user_contact_key' . $cnt, ''))->first();
                        
                        // check if the record can be updated
                        if (isset($user_contact->id)) {
                            $user_contact->country_id = Country::countryKey($this->getInput('country_key' . $cnt, ''))->pluck('id');
                            $user_contact->user_contact_phone_number = $this->getInput('user_contact_phone_number' . $cnt, '');
                            $user_contact->updated_by = Auth::user()->id;
                    
                            // update record
                            $user_contact->save();
                        }
                    }
                }
            }
            
            // where condition
            $user_emergency = UserEmergency::userId($user->id)->first();
            
            // check if the record can be updated
            if (!empty($user_emergency->id)) {
                // fields to be updated
                $user_emergency->user_emergency_name = $this->getInput('user_emergency_name', '');
                $user_emergency->user_emergency_relation = $this->getInput('user_emergency_relation', '');
                $user_emergency->user_emergency_address = $this->getInput('user_emergency_address', '');
                $user_emergency->country_id = Country::countryKey($this->getInput('emergency_country_key', ''))->pluck('id');
                $user_emergency->user_emergency_phone = $this->getInput('user_emergency_phone', '');
                $user_emergency->updated_by = Auth::user()->id;
                    
                // update record
                $user_emergency->save();
            }
            
            // flag all approver template records
            AccessUser::userId($user->id)->update(array('access_user_flag' => YES));
            // create access record
            if (is_array($this->getInput('access_user', array()))) {
                foreach($this->getInput('access_user', array()) as $access_id) {
                    $access_user = AccessUser::accessId($access_id)
                        ->userId($user->id)->first();
                    
                    if (isset($access_user->id) && !empty($access_user->id)) {
                        // update record
                        $access_user->user_id = $user->id;
                        $access_user->access_id = $access_id;
                        $access_user->access_user_flag = NO;
                        $access_user->updated_by = Auth::user()->id;
                        $access_user->save();
                        
                    } else {
                        // create record
                        $data = array();
                        $data['user_id'] = $user->id;
                        $data['access_id'] = $access_id;
                        $data['access_user_flag'] = NO;
                        $data['created_by'] = Auth::user()->id;
                        AccessUser::create($data);
                        
                    }
                }
                // delete records set to yes
                AccessUser::AccessUserFlag(YES)
                    ->UserId($user->id)->delete();
            }
            
            // redirect to list page
            Session::flash('success', SUCCESS_UPDATE);
            return Redirect::to($this->getPreviousListURL());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // where condition
        $user = User::userKey($id)->first();
        
        // check if the record really exist
        if (is_null($user)) {
            // redirect to list page
            Session::flash('danger', SOMETHING_WENT_WRONG);
            return Redirect::to(strtolower(USER_TITLE));
        }
        
        // store user who deleted the record
        $user->deleted_by = Auth::user()->id;
        $user->save();
        
        // delete record
        $user->delete();

        // redirect to list page
        Session::flash('success', SUCCESS_DELETE);
        return Redirect::to(strtolower(USER_TITLE));
    }
    
    
    /**
     * upload photo
     *
     * @param  
     * @return photo
     */
    public function uploadPhoto()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'user_photo' => 'mimes:jpeg,bmp,png'
        );
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return "error";
        } else {
            if (!is_null(Input::file('user_photo'))) {
                $upload_file = Input::file('user_photo');
                $new_file = str_random(10) . '_' . date("Ymdhis") . "." . $upload_file->getClientOriginalExtension();
                $upload_success = $upload_file->move(UPLOAD_USER_PHOTO_PATH, $new_file);
                
                Session::put('user_photo', $new_file);
                return UPLOAD_USER_PHOTO_PATH . '/' . $new_file;
            }
        }
    }
    
}
