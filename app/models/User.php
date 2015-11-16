<?php
 
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
 
class User extends Eloquent implements UserInterface, RemindableInterface 
{
    protected $table  = "user";
    protected $guarded = array('id');
    
    protected $hidden = ["password"];
    
    use SoftDeletingTrait;
    
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    } 

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return "remember_token";
    }

    public function getReminderEmail()
    {
        return $this->email;
    }
	
	// concat first, middle and last name
    public function getUserFullnameAttribute()
    {
        return $this->attributes['user_firstname'] . ' ' . $this->attributes['user_lastname'];
    }
    
    public function access()
    {
        return $this->belongsToMany('Access');
    }
    
	public function userContact() 
    {
        return $this->hasMany('UserContact');
    }
    
	public function userEmergency() 
    {
        return $this->hasOne('UserEmergency');
    }
    
    // search for user key
    public function scopeUserKey($query, $user_key)
    {
        return $query->where('user_key', 'LIKE', $user_key);
    }
	
    // search for user name
    public function scopeUserFirstname($query, $user_firstname)
    {
        return $query->where('user_firstname', 'LIKE', "$user_firstname%");
    }
	
    // search for user name
    public function scopeUserLastname($query, $user_lastname)
    {
        return $query->where('user_lastname', 'LIKE', "$user_lastname%");
    }
    
    // search for user status
    public function scopeUserStatus($query, $user_status)
    {
        if (!empty($user_status)) {
            return $query->where('user_status', 'LIKE', $user_status);
        
        }
    }
    
    // sort for user record
    public function scopeUserSort($query, $sort_by = 'created_at', $order_by)
    {
        return $query->orderBy($sort_by, $order_by);
        
    }
}