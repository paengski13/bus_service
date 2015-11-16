<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
// regex format when passing parameters
Route::pattern('uuid', '[0-9]{6}[0-9a-f]{13}');


/*** Session ****/
// index URL
Route::get('', array('uses' => 'SessionController@showLogin'));
Route::get('/', array('uses' => 'SessionController@showLogin'));

// route to show the login form
Route::get('login', array('uses' => 'SessionController@showLogin'));

// route to login request
Route::post('login', array('uses' => 'SessionController@requestLogin'));

// route to process the logout
Route::get('logout', array('uses' => 'SessionController@requestLogout'));

Route::resource('check', 'BusServiceController');
Route::resource('stop', 'StopController');
Route::resource('bus_stop', 'BusStopController');
// ajax function for calling list of bus stops based on location id
Route::get('stop/location/{id}', array('uses' => 'StopController@getByLocation'));

// logout when there is not valid session detected
Route::group(array('before' => 'auth', 'after' => 'auth'), function()
{
    Route::resource('check', 'BusServiceController');
    Route::resource('stop', 'StopController');
    Route::resource('bus_stop', 'BusStopController');
    Route::get('stop/location/{id}', array('uses' => 'StopController@getByLocation'));
});




/*** View Composer ****/
View::composer('*', function($view)
{
    
});
/*** End View Composer ****/