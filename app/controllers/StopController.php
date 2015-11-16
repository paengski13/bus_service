<?php

class StopController extends \BaseController 
{    
    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return NA
     */
    public function index()
    {
        
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


    /**
     * get list of stops based on location
     *
     * @param  int  $location_id
     * @return Response
     */
    public function getByLocation($location_id)
    {
        if ($location_id){
            // get all stops based on the condition
            $stops = Stop::LocationId($location_id)->get();
            
        } else {
            // get all
            $stops = Stop::get();
        }
        
        if ($stops->count()) {
            echo json_encode($stops);
        } else {
            echo json_encode(array());
        }
    }
}
