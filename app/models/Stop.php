<?php

class Stop extends Eloquent
{
    protected $table = 'stop';
    protected $guarded = array('id');
    
    use SoftDeletingTrait;
    
    /**
     * relationship: STOP has many to many BUS
     *
     */
    public function bus() 
    {
        return $this->belongsToMany('Bus')->withPivot('id', 'arrival_time', 'arrival_time2');
    }

    /**
     * location_id as where condition
     *
     */
    public function scopeLocationId($query, $location_id)
    {
        return $query->where('location_id', $location_id);
    }
}