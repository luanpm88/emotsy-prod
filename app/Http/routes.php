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
Route::get('assets/images/avatar/user-{uid?}.jpg', 'UserController@avatar');

// Mail list
Route::get('lists/quick-view', 'MailListController@quickView');
Route::get('lists/{uid}/list-growth', 'MailListController@listGrowthChart');
Route::get('lists/sort', 'MailListController@sort');
Route::get('lists/listing/{page?}', 'MailListController@listing');
Route::get('lists/delete', 'MailListController@delete');
Route::get('lists/delete/confirm', 'MailListController@deleteConfirm');
Route::get('lists/{uid}/overview', 'MailListController@overview');
Route::resource('lists', 'MailListController');
Route::get('lists/{uid}/edit', 'MailListController@edit');
Route::patch('lists/{uid}/update', 'MailListController@update');

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
Route::get('lists/{list_uid}/subscribers/import', 'SubscriberController@import');
Route::post('lists/{list_uid}/subscribers/import', 'SubscriberController@import');
Route::get('lists/{list_uid}/subscribers/import/proccess', 'SubscriberController@importProccess');
Route::get('lists/{list_uid}/subscribers/export', 'SubscriberController@export');
Route::post('lists/{list_uid}/subscribers/export', 'SubscriberController@export');
Route::get('lists/{list_uid}/subscribers/export/proccess', 'SubscriberController@exportProccess');
Route::get('lists/{list_uid}/subscribers/export/download', 'SubscriberController@downloadExportedCsv');

// Notification handler
Route::get('delivery/notify', 'DeliveryController@notify');

// Segment
Route::get('segments/select_box', 'SegmentController@selectBox');
Route::get('lists/{list_uid}/segments', 'SegmentController@index');
Route::get('lists/{list_uid}/segments/{uid}/subscribers', 'SegmentController@subscribers');
Route::get('lists/{list_uid}/segments/{uid}/listing_subscribers', 'SegmentController@listing_subscribers');
Route::get('lists/{list_uid}/segments/create', 'SegmentController@create');
Route::get('lists/{list_uid}/segments/listing', 'SegmentController@listing');
Route::post('lists/{list_uid}/segments/store', 'SegmentController@store');
Route::get('lists/{list_uid}/segments/{uid}/edit', 'SegmentController@edit');
Route::patch('lists/{list_uid}/segments/{uid}/update', 'SegmentController@update');
Route::get('lists/{list_uid}/segments/delete', 'SegmentController@delete');
Route::get('lists/{list_uid}/segments/sample_condition', 'SegmentController@sample_condition');

// Page
Route::get('lists/{list_uid}/pages/{alias}/update', 'PageController@update');
Route::post('lists/{list_uid}/pages/{alias}/update', 'PageController@update');
Route::post('lists/{list_uid}/pages/{alias}/preview', 'PageController@preview');
Route::get('lists/{list_uid}/sign-up', 'PageController@sign_up_form');
Route::post('lists/{list_uid}/sign-up', 'PageController@sign_up_form');
Route::get('lists/{list_uid}/sign-up/thank-you', 'PageController@sign_up_thankyou_page');
Route::get('lists/{list_uid}/subscribe-confirm/{uid}/{code}', 'PageController@sign_up_confirmation_thankyou');
Route::get('lists/{list_uid}/unsubscribe/{uid}/{code}', 'PageController@unsubscribe_form');
Route::post('lists/{list_uid}/unsubscribe/{uid}/{code}', 'PageController@unsubscribe_form');
Route::get('lists/{list_uid}/update-profile/{uid}/{code}', 'PageController@profile_update_form');
Route::post('lists/{list_uid}/update-profile/{uid}/{code}', 'PageController@profile_update_form');
Route::get('lists/{list_uid}/update-profile-success/{uid}', 'PageController@profile_update_success_page');
Route::get('lists/{list_uid}/profile-update-email-sent/{uid}', 'PageController@profile_update_email_sent');

// Template
Route::get('templates/{uid}/content', 'TemplateController@content');
Route::get('templates/sort', 'TemplateController@sort');
Route::get('templates/listing/{page?}', 'TemplateController@listing');
Route::get('templates/choosing/{page?}', 'TemplateController@choosing');
Route::get('templates/upload', 'TemplateController@upload');
Route::post('templates/upload', 'TemplateController@upload');
Route::get('templates/{uid}/image', 'TemplateController@image');
Route::post('templates/{uid}/saveImage', 'TemplateController@saveImage');
Route::get('templates/{uid}/preview', 'TemplateController@preview');
Route::get('templates/delete', 'TemplateController@delete');
Route::resource('templates', 'TemplateController');
Route::get('templates/{uid}/edit', 'TemplateController@edit');
Route::patch('templates/{uid}/update', 'TemplateController@update');

// Campaign
Route::get('campaigns/quick-view', 'CampaignController@quickView');
Route::get('campaigns/{uid}/chart24h', 'CampaignController@chart24h');
Route::get('campaigns/{uid}/overview', 'CampaignController@overview');
Route::get('campaigns/{uid}/links', 'CampaignController@links');
Route::get('campaigns/sort', 'CampaignController@sort');
Route::get('campaigns/listing/{page?}', 'CampaignController@listing');
Route::get('campaigns/{uid}/recipients', 'CampaignController@recipients');
Route::post('campaigns/{uid}/recipients', 'CampaignController@recipients');
Route::get('campaigns/{uid}/setup', 'CampaignController@setup');
Route::post('campaigns/{uid}/setup', 'CampaignController@setup');
Route::get('campaigns/{uid}/template', 'CampaignController@template');
Route::post('campaigns/{uid}/template', 'CampaignController@template');
Route::get('campaigns/{uid}/schedule', 'CampaignController@schedule');
Route::post('campaigns/{uid}/schedule', 'CampaignController@schedule');
Route::get('campaigns/{uid}/confirm', 'CampaignController@confirm');
Route::post('campaigns/{uid}/confirm', 'CampaignController@confirm');
Route::get('campaigns/delete', 'CampaignController@delete');
Route::resource('campaigns', 'CampaignController');
Route::get('campaigns/{uid}/edit', 'CampaignController@edit');
Route::patch('campaigns/{uid}/update', 'CampaignController@update');

// ADMIN AREA
Route::group(['namespace' => 'Admin'], function () {
    
    Route::get('admin', 'HomeController@index');
    
    // User Group
    Route::get('admin/user_groups/listing/{page?}', 'UserGroupController@listing');
    Route::get('admin/user_groups/sort', 'UserGroupController@sort');
    Route::resource('admin/user_groups', 'UserGroupController');
    
    // User
    Route::get('admin/users/listing/{page?}', 'UserController@listing');
    Route::get('admin/users/sort', 'UserController@sort');
    Route::resource('admin/users', 'UserController');
    
    // Template
    Route::get('admin/templates/listing/{page?}', 'TemplateController@listing');
    Route::get('admin/templates/upload', 'TemplateController@upload');
    Route::post('admin/templates/upload', 'TemplateController@upload');
    Route::resource('admin/templates', 'TemplateController');
    Route::get('admin/templates/{uid}/edit', 'TemplateController@edit');
    Route::patch('admin/templates/{uid}/update', 'TemplateController@update');
    
    // Layout
    Route::get('admin/layouts/listing/{page?}', 'LayoutController@listing');
    Route::get('admin/layouts/sort', 'LayoutController@sort');
    Route::resource('admin/layouts', 'LayoutController');
    
    // User
    Route::get('admin/sending_servers/listing/{page?}', 'SendingServerController@listing');
    Route::get('admin/sending_servers/sort', 'SendingServerController@sort');
    Route::get('admin/sending_servers/delete', 'SendingServerController@delete');
    Route::resource('admin/sending_servers', 'SendingServerController');
    
});
