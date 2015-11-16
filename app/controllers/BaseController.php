<?php

class BaseController extends Controller {

    // holds all information to be submitted in view layer
    protected $data = array();

    /**
     * validate input if exist, or with value
     *
     * @return return the value if not null, else set a default
     */
    protected function getInput($value, $replace_value, $type = '')
    {
        if ($type == DATE_STRING) {
            return (Input::has($value)) ? Input::get($value) : $replace_value; 
        } else {
            return (Input::has($value)) ? Input::get($value) : $replace_value; 
        }
        
    }
}