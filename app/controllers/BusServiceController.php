<?php

class BusServiceController extends \BaseController 
{    
    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }
    
    
    /**
     * Display list of bus services on user's nearby area
     *
     * @return list of location
     */
    public function index()
    {
        // search variables here
        $location_id = $this->getInput('location_id', '');
        $stop_id = $this->getInput('stop_id', '');

        // order variables here
        $sort_by = $this->getInput('sort_by', 'location.created_at');
        $order_by = $this->getInput('order_by', DESCENDING);
        
        // search Location status
        $locations = Location::with(array('Stop' => function ($query) use ($stop_id) {
            if (!empty($stop_id)) {
                $query->where('id', $stop_id);
            }
        }))
        ->locationSort($sort_by, $order_by);
        
        // search location filters
        $this->data["s_locations"] = $locations->get();
        
        // additional search conditions
        if (!empty($location_id)) {
            $locations = $locations->where('id', $location_id);
        }
        
        $locations = $locations->paginate(PAGINATION_LIMIT);

        // location records
        $this->data["locations"] = $locations;
        
        // previous search values
        $this->data["location_id"] = $location_id;
        $this->data["stop_id"] = $stop_id;
        
        // current record number
        $this->data["count"] = $locations->getFrom();
        
        // search parameter to the pagination
        $this->data["url_pagination"] = array('sort_by' => $sort_by, 'order_by' => $order_by, 'location_id' => $location_id, 'stop_id' => $stop_id);

        // search parameter to the sorting
        $this->data["url_sort"] = array('sort_by' => $sort_by, 'order_by' => getSortOrder($order_by), 
                                        'location_id' => $location_id, 'stop_id' => $stop_id, 'page' => $locations->getCurrentPage());

        // load the view and pass the check records
        return View::make('setup.check.index')->with('data', $this->data);
    }                                                                               


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        
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
        
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        
    }
}
