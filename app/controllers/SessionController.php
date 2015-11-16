<?php

class SessionController extends \BaseController 
{
    /**
     * Display the specified resource.
     *
     * @param  NA
     * @return NA
     */
    public function showLogin()
    {
        // remove all session
        Session::flush();
        
        // call view login form
        return View::make('session.login')->with('data', $this->data);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  NA
     * @return NA
     */
    public function requestLogin()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'user_email' => 'required|email',
            'password' => 'required|min:4'
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::to(URL::previous())
                ->withErrors($validator) // send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
            
        } else {
            // create our user data for the authentication
            $user_array = array(
                'user_email' => $this->getInput('user_email', ''),
                'password' => $this->getInput('password', '')
            );

            // attempt to do the login
            if (Auth::attempt($user_array)) {
                $current_password = $this->getInput('password', '');
                
                // additional validation to make sure that password matched
                $user = Auth::user();
                if (Hash::check($current_password, $user->password)) {
                    return Redirect::intended('check');
                    
                }
            } else {
                Input::flash();
                Session::flash('error_message', 'Invalid email or password, please try again'); 
                $this->data['has_error'] = ERROR;
                return View::make('session.login')->with('data', $this->data);

            }

        }
    }
    
    // logout user to the system
    public function requestLogout()
    {
        Session::flush();
        Auth::logout(); // log the user out of our application
        
        $headers = apache_request_headers();
        // check if remote_user exist
        if (isset($headers["REMOTE_USER"])) {
            $windows_id = str_replace(REMOTE_USER, "", $headers["REMOTE_USER"]);
            $this->data["window_id"] = $windows_id;
        }
        return View::make('session.login')->with('data', $this->data);
    }
    
    // display home page
    public function showHome()
    {
        // header and module properties
        $this->data['page_module'] = HOME_MODULE;
        $this->data['page_title'] = HOME_TITLE;

        return View::make('session.home')->with('data', $this->data);
    }
}