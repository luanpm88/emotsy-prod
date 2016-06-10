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
    
    Route::get('/lists', function () {
        return view('lists/index');
    });
    
    Route::get('/lists/create', function () {
        return view('lists/create');
    });

});

Route::auth();

Route::get('/home', 'HomeController@index');
