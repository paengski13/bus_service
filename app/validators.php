<?php
Validator::extend('check_password', function($attribute,$value,$parameters)
{
    return (Hash::check($value, Auth::user()->password));
});