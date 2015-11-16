<?php

class BusStopController extends \BaseController 
{    
    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }
    
    
    /**
     * list the resources.
     *
     * @return Response
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
     * Retrieve a single record
     *
     * @param  string unique $id
     * @return Response
     */
    public function show($id)
    {
        $bus_stop = BusStop::find($id);
        echo json_encode($bus_stop);
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
