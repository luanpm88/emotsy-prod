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
Route::auth();

Route::get('/', "HomeController@index");

// update current user profile
Route::get('account/profile', 'AccountController@profile');
Route::post('account/profile', 'AccountController@profile');

// translation data
Route::get('/datatable_locale', 'Controller@datatable_locale');
Route::get('/jquery_validate_locale', 'Controller@jquery_validate_locale');