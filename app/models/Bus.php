<?php

class Bus extends Eloquent
{
    protected $table = 'bus';
    protected $guarded = array('id');
    
    use SoftDeletingTrait;

}