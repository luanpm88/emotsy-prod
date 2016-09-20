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

Route::group(['middleware' => ['installed']], function () {
    // Installation
    Route::get('install', 'InstallController@starting');
    Route::get('install/site-info', 'InstallController@siteInfo');
    Route::post('install/site-info', 'InstallController@siteInfo');
    Route::get('install/system-compatibility', 'InstallController@systemCompatibility');
    Route::get('install/database', 'InstallController@database');
    Route::post('install/database', 'InstallController@database');
    Route::get('install/database_import', 'InstallController@databaseImport');
    Route::get('install/cron-jobs', 'InstallController@cronJobs');
    Route::get('install/finish', 'InstallController@finish');
});

Route::group(['namespace' => 'Api', 'prefix' => 'api/v1', 'middleware' => 'auth:api'], function () {
    // Route::group(['namespace' => 'Api', 'prefix' => 'api/v1'], function () {
    Route::get('', 'HomeController@index');
    
    
    Route::resource('lists', 'MailListController');
    Route::resource('campaigns', 'CampaignController');
    Route::get('lists/{list_uid}/subscribers', 'SubscriberController@index');
    Route::get('lists/{list_uid}/subscribers/{uid}', 'SubscriberController@show');
    Route::patch('lists/{list_uid}/subscribers/{uid}/subscribe', 'SubscriberController@subscribe');
    Route::patch('lists/{list_uid}/subscribers/{uid}/unsubscribe', 'SubscriberController@unsubscribe');
    Route::delete('lists/{list_uid}/subscribers/{uid}/delete', 'SubscriberController@delete');
    Route::post('lists/{list_uid}/subscribers/store', 'SubscriberController@store');
});

Route::auth();

Route::get('/offline', 'Controller@offline');
Route::get('/not-authorized', 'Controller@notAuthorized');
Route::get('/demo', 'Controller@demo');


// Customer avatar
Route::get('assets/images/avatar/customer-{uid?}.jpg', 'CustomerController@avatar');

// Admin avatar
Route::get('assets/images/avatar/admin-{uid?}.jpg', 'AdminController@avatar');


Route::group(['middleware' => ['not_installed', 'frontend']], function () {
    Route::get('/', 'HomeController@index');
    Route::get('/docs/api/v1', 'Controller@docsApiV1');
    
    Route::get('/current_user_uid', 'UserController@showUid');

    // Update current user profile
    Route::get('account/api/renew', 'AccountController@renewToken');
    Route::get('account/api', 'AccountController@api');
    Route::get('account/profile', 'AccountController@profile');
    Route::post('account/profile', 'AccountController@profile');
    Route::get('account/contact', 'AccountController@contact');
    Route::post('account/contact', 'AccountController@contact');
    Route::get('account/logs', 'AccountController@logs');
    Route::get('account/logs/listing', 'AccountController@logsListing');
    Route::get('account/quota_log', 'AccountController@quotaLog');

    // Translation data
    Route::get('/datatable_locale', 'Controller@datatable_locale');
    Route::get('/jquery_validate_locale', 'Controller@jquery_validate_locale');

    // User avatar
    Route::get('assets/images/avatar/user-{uid?}.jpg', 'UserController@avatar');

    // Mail list
    Route::post('lists/copy', 'MailListController@copy');
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
    Route::get('lists/{uid}/embedded-form', 'MailListController@embeddedForm');
    Route::post('lists/{uid}/embedded-form-subscribe', 'MailListController@embeddedFormSubscribe');
    Route::post('lists/{uid}/embedded-form-subscribe-captcha', 'MailListController@embeddedFormCaptcha');
    Route::get('lists/{uid}/embedded-form-frame', 'MailListController@embeddedFormFrame');

    // Field
    Route::get('lists/{list_uid}/fields', 'FieldController@index');
    Route::get('lists/{list_uid}/fields/sort', 'FieldController@sort');
    Route::post('lists/{list_uid}/fields/store', 'FieldController@store');
    Route::get('lists/{list_uid}/fields/sample/{type}', 'FieldController@sample');
    Route::get('lists/{list_uid}/fields/{uid}/delete', 'FieldController@delete');

    // Subscriber
    Route::get('subscribers/move/{list_uid}/{type}', 'SubscriberController@move');
    Route::get('subscribers/copy/{list_uid}/{type}', 'SubscriberController@copy');
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

    Route::post('delivery/notify/{stype}', 'DeliveryController@notify');
    //Route::get('delivery/notify/{stype}', 'DeliveryController@notify');

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
    Route::get('lists/{list_uid}/sign-up', 'PageController@signUpForm');
    Route::post('lists/{list_uid}/sign-up', 'PageController@signUpForm');
    Route::get('lists/{list_uid}/sign-up/thank-you', 'PageController@signUpThankyouPage');
    Route::get('lists/{list_uid}/subscribe-confirm/{uid}/{code}', 'PageController@signUpConfirmationThankyou');
    Route::get('lists/{list_uid}/unsubscribe/{uid}/{code}', 'PageController@unsubscribeForm');
    Route::post('lists/{list_uid}/unsubscribe/{uid}/{code}', 'PageController@unsubscribeForm');
    Route::get('lists/{list_uid}/update-profile/{uid}/{code}', 'PageController@profileUpdateForm');
    Route::post('lists/{list_uid}/update-profile/{uid}/{code}', 'PageController@profileUpdateForm');
    Route::get('lists/{list_uid}/update-profile-success/{uid}', 'PageController@profileUpdateSuccessPage');
    Route::get('lists/{list_uid}/profile-update-email-sent/{uid}', 'PageController@profileUpdateEmailSent');
    Route::get('lists/{list_uid}/unsubscribe-success/{uid}', 'PageController@unsubscribeSuccessPage');

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
    Route::get('campaigns/delete/confirm', 'CampaignController@deleteConfirm');
    Route::get('campaigns/{message_id}/open', 'CampaignController@open');
    Route::get('campaigns/{message_id}/click/{url}', 'CampaignController@click');
    Route::get('campaigns/{message_id}/unsubscribe', 'CampaignController@unsubscribe');

    Route::get('campaigns/{uid}/subscribers', 'CampaignController@subscribers');
    Route::get('campaigns/{uid}/subscribers/listing', 'CampaignController@subscribersListing');
    Route::get('campaigns/{uid}/open-map', 'CampaignController@openMap');
    Route::get('campaigns/{uid}/tracking-log', 'CampaignController@trackingLog');
    Route::get('campaigns/{uid}/tracking-log/listing', 'CampaignController@trackingLogListing');
    Route::get('campaigns/{uid}/bounce-log', 'CampaignController@bounceLog');
    Route::get('campaigns/{uid}/bounce-log/listing', 'CampaignController@bounceLogListing');
    Route::get('campaigns/{uid}/feedback-log', 'CampaignController@feedbackLog');
    Route::get('campaigns/{uid}/feedback-log/listing', 'CampaignController@feedbackLogListing');
    Route::get('campaigns/{uid}/open-log', 'CampaignController@openLog');
    Route::get('campaigns/{uid}/open-log/listing', 'CampaignController@openLogListing');
    Route::get('campaigns/{uid}/click-log', 'CampaignController@clickLog');
    Route::get('campaigns/{uid}/click-log/listing', 'CampaignController@clickLogListing');
    Route::get('campaigns/{uid}/unsubscribe-log', 'CampaignController@unsubscribeLog');
    Route::get('campaigns/{uid}/unsubscribe-log/listing', 'CampaignController@unsubscribeLogListing');

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
    Route::get('campaigns/select2', 'CampaignController@select2');
    Route::get('campaigns/pause', 'CampaignController@pause');
    Route::get('campaigns/restart', 'CampaignController@restart');
    Route::resource('campaigns', 'CampaignController');
    Route::get('campaigns/{uid}/edit', 'CampaignController@edit');
    Route::patch('campaigns/{uid}/update', 'CampaignController@update');    
    
    Route::get('users/login-back', 'UserController@loginBack');
});

// ADMIN AREA
Route::group(['namespace' => 'Admin', 'middleware' => ['not_installed', 'backend']], function () {
    Route::get('admin', 'HomeController@index');

    // User    
    Route::get('admin/users/switch/{uid}', 'UserController@switch_user');
    Route::get('admin/users/listing/{page?}', 'UserController@listing');
    Route::get('admin/users/sort', 'UserController@sort');
    Route::get('admin/users/delete', 'UserController@delete');
    Route::resource('admin/users', 'UserController');

    // Template
    Route::get('admin/templates/listing/{page?}', 'TemplateController@listing');
    Route::get('admin/templates/upload', 'TemplateController@upload');
    Route::post('admin/templates/upload', 'TemplateController@upload');
    Route::get('admin/templates/delete', 'TemplateController@delete');
    Route::resource('admin/templates', 'TemplateController');
    Route::get('admin/templates/{uid}/edit', 'TemplateController@edit');
    Route::patch('admin/templates/{uid}/update', 'TemplateController@update');

    // Layout
    Route::get('admin/layouts/listing/{page?}', 'LayoutController@listing');
    Route::get('admin/layouts/sort', 'LayoutController@sort');
    Route::resource('admin/layouts', 'LayoutController');

    // Sending servers
    Route::get('admin/sending_servers/listing/{page?}', 'SendingServerController@listing');
    Route::get('admin/sending_servers/sort', 'SendingServerController@sort');
    Route::get('admin/sending_servers/delete', 'SendingServerController@delete');
    Route::get('admin/sending_servers/disable', 'SendingServerController@disable');
    Route::get('admin/sending_servers/enable', 'SendingServerController@enable');
    Route::resource('admin/sending_servers', 'SendingServerController');
    Route::get('admin/sending_servers/create/{type}', 'SendingServerController@create');
    Route::post('admin/sending_servers/create/{type}', 'SendingServerController@store');
    Route::get('admin/sending_servers/{id}/edit/{type}', 'SendingServerController@edit');
    Route::patch('admin/sending_servers/{id}/update/{type}', 'SendingServerController@update');
    

    // Bounce handlers
    Route::get('admin/bounce_handlers/listing/{page?}', 'BounceHandlerController@listing');
    Route::get('admin/bounce_handlers/sort', 'BounceHandlerController@sort');
    Route::get('admin/bounce_handlers/delete', 'BounceHandlerController@delete');
    Route::resource('admin/bounce_handlers', 'BounceHandlerController');

    // Feedback Loop handlers
    Route::get('admin/feedback_loop_handlers/listing/{page?}', 'FeedbackLoopHandlerController@listing');
    Route::get('admin/feedback_loop_handlers/sort', 'FeedbackLoopHandlerController@sort');
    Route::get('admin/feedback_loop_handlers/delete', 'FeedbackLoopHandlerController@delete');
    Route::resource('admin/feedback_loop_handlers', 'FeedbackLoopHandlerController');

    // Sending domain
    Route::get('admin/sending_domains/listing/{page?}', 'SendingDomainController@listing');
    Route::get('admin/sending_domains/sort', 'SendingDomainController@sort');
    Route::get('admin/sending_domains/delete', 'SendingDomainController@delete');
    Route::resource('admin/sending_domains', 'SendingDomainController');

    // Language
    Route::get('admin/languages/listing/{page?}', 'LanguageController@listing');
    Route::get('admin/languages/delete', 'LanguageController@delete');
    Route::resource('admin/languages', 'LanguageController');

    // Setting
    Route::get('admin/settings/{tab?}', 'SettingController@index');
    Route::post('admin/settings', 'SettingController@index');
    Route::get('admin/update-urls', 'SettingController@updateUrls');

    // Tracking log
    Route::get('admin/tracking_log', 'TrackingLogController@index');
    Route::get('admin/tracking_log/listing', 'TrackingLogController@listing');

    // Feedback log
    Route::get('admin/bounce_log', 'BounceLogController@index');
    Route::get('admin/bounce_log/listing', 'BounceLogController@listing');

    // Open log
    Route::get('admin/open_log', 'OpenLogController@index');
    Route::get('admin/open_log/listing', 'OpenLogController@listing');

    // Click log
    Route::get('admin/click_log', 'ClickLogController@index');
    Route::get('admin/click_log/listing', 'ClickLogController@listing');

    // Feedback log
    Route::get('admin/feedback_log', 'FeedbackLogController@index');
    Route::get('admin/feedback_log/listing', 'FeedbackLogController@listing');

    // Unsubscribe log
    Route::get('admin/unsubscribe_log', 'UnsubscribeLogController@index');
    Route::get('admin/unsubscribe_log/listing', 'UnsubscribeLogController@listing');

    // Sending domain
    Route::get('admin/blacklist', 'BlacklistController@index');
    Route::get('admin/blacklist/listing', 'BlacklistController@listing');
    Route::get('admin/blacklist/delete', 'BlacklistController@delete');
    
    // Customer Group
    Route::get('admin/customer_groups/listing/{page?}', 'CustomerGroupController@listing');
    Route::get('admin/customer_groups/sort', 'CustomerGroupController@sort');
    Route::get('admin/customer_groups/delete', 'CustomerGroupController@delete');
    Route::resource('admin/customer_groups', 'CustomerGroupController');
    
    // Customer    
    Route::get('admin/customers/login-as/{uid}', 'CustomerController@loginAs');
    Route::get('admin/customers/listing/{page?}', 'CustomerController@listing');
    Route::get('admin/customers/sort', 'CustomerController@sort');
    Route::get('admin/customers/delete', 'CustomerController@delete');
    Route::get('admin/customers/disable', 'CustomerController@disable');
    Route::get('admin/customers/enable', 'CustomerController@enable');
    Route::resource('admin/customers', 'CustomerController');
    
    // Admin Group
    Route::get('admin/admin_groups/listing/{page?}', 'AdminGroupController@listing');
    Route::get('admin/admin_groups/sort', 'AdminGroupController@sort');
    Route::get('admin/admin_groups/delete', 'AdminGroupController@delete');
    Route::resource('admin/admin_groups', 'AdminGroupController');
    
    // Admin    
    Route::get('admin/admins/login-as/{uid}', 'AdminController@loginAs');
    Route::get('admin/admins/listing/{page?}', 'AdminController@listing');
    Route::get('admin/admins/sort', 'AdminController@sort');
    Route::get('admin/admins/delete', 'AdminController@delete');
    Route::get('admin/admins/disable', 'AdminController@disable');
    Route::get('admin/admins/enable', 'AdminController@enable');
    Route::resource('admin/admins', 'AdminController');
    
    // Account
    Route::get('admin/account/profile', 'AccountController@profile');
    Route::post('admin/account/profile', 'AccountController@profile');
    Route::get('admin/account/contact', 'AccountController@contact');
    Route::post('admin/account/contact', 'AccountController@contact');
});
