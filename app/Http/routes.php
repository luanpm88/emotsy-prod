<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'web'], function () {
    
    Route::get('/', "HomeController@index");
    
    // Update current user profile
    Route::get('account/profile', 'AccountController@profile');
    Route::post('account/profile', 'AccountController@profile');

});

Route::auth();

Route::get('/home', 'HomeController@index');
