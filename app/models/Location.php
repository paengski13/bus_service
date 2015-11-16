<?php

class Location extends Eloquent
{
    protected $table = 'location';
    protected $guarded = array('id');
    
    use SoftDeletingTrait;
    
    /**
     * relationship: location has zero to many stop
     *
     */
    public function stop() 
    {
        return $this->hasMany('Stop');
    }
    
    /**
     * Sort record based on the parameter
     *
     */
    public function scopeLocationSort($query, $sort_by = 'created_at', $order_by)
    {
        return $query->orderBy($sort_by, $order_by);
        
    }

}