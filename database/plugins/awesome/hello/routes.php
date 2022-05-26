<?php

// Client View Groups
Route::group(['middleware' => ['web'], 'namespace' => '\Awesome\Hello\Controllers'], function () {

    Route::get('awesome/hello', 'DashboardController@index');

});
