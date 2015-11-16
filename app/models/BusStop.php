<?php

class BusStop extends Eloquent
{
    protected $table = 'bus_stop';
    protected $guarded = array('id');
    
    use SoftDeletingTrait;

}