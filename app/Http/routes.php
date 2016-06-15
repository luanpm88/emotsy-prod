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

// Update current user profile
Route::get('account/profile', 'AccountController@profile');
Route::post('account/profile', 'AccountController@profile');
Route::get('account/contact', 'AccountController@contact');
Route::post('account/contact', 'AccountController@contact');

// Translation data
Route::get('/datatable_locale', 'Controller@datatable_locale');
Route::get('/jquery_validate_locale', 'Controller@jquery_validate_locale');

// User avatar
Route::get('assets/images/avatar/user-{id}.jpg', 'UserController@avatar');

// Mail list
Route::get('lists/sort', 'MailListController@sort');
Route::get('lists/listing/{page?}', 'MailListController@listing');
Route::resource('lists', 'MailListController');
Route::get('lists/{uid}/edit', 'MailListController@edit');

// Field
Route::get('lists/{list_uid}/fields', 'FieldController@index');
Route::get('lists/{list_uid}/fields/sort', 'FieldController@sort');
Route::post('lists/{list_uid}/fields/store', 'FieldController@store');
Route::get('lists/{list_uid}/fields/sample/{type}', 'FieldController@sample');
Route::get('lists/{list_uid}/fields/{uid}/delete', 'FieldController@delete');

// Subscriber
Route::get('lists/{list_uid}/subscribers', 'SubscriberController@index');
Route::get('lists/{list_uid}/subscribers/create', 'SubscriberController@create');
Route::get('lists/{list_uid}/subscribers/listing', 'SubscriberController@listing');
Route::post('lists/{list_uid}/subscribers/store', 'SubscriberController@store');
Route::get('lists/{list_uid}/subscribers/{uid}/edit', 'SubscriberController@edit');
Route::patch('lists/{list_uid}/subscribers/{uid}/update', 'SubscriberController@update');
Route::get('lists/{list_uid}/subscribers/delete', 'SubscriberController@delete');
Route::get('lists/{list_uid}/subscribers/subscribe', 'SubscriberController@subscribe');
Route::get('lists/{list_uid}/subscribers/unsubscribe', 'SubscriberController@unsubscribe');
