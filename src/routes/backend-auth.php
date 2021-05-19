<?php

/*
 * laravel admin main route, copy from app/Admin/routes.php
 *
 * admin alias of Encore\\Admin\\Facades\\Admin
 */
// \Admin::registerAuthRoutes();

Route::get('/', function(){
    return redirect()->route(config('admin.custom.dashboard_route_name', 'admin.dashboard'));
});

Route::get('auth/login', 'AuthController@getLogin');
Route::post('auth/login', 'AuthController@postLogin');
Route::get('auth/logout', 'AuthController@getLogout');
Route::get('auth/setting', 'AuthController@getSetting');
Route::put('auth/setting', 'AuthController@putSetting');