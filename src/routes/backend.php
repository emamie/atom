<?php

/*
 * laravel admin main route, copy from app/Admin/routes.php
 *
 * admin alias of Encore\\Admin\\Facades\\Admin
 */
// \Admin::registerAuthRoutes();

Route::resource('auth/users', 'UserController');
Route::resource('auth/roles', 'RoleController');
Route::resource('auth/permissions', 'PermissionController');
Route::resource('auth/menu', 'MenuController', ['except' => ['create']]);
Route::resource('auth/logs', 'LogController', ['only' => ['index', 'destroy']]);


Route::post('_handle_form_', 'HandleController@handleForm')->name('admin.handle-form');
Route::post('_handle_action_', 'HandleController@handleAction')->name('admin.handle-action');
Route::get('_handle_selectable_', 'HandleController@handleSelectable')->name('admin.handle-selectable');
Route::get('_handle_renderable_', 'HandleController@handleRenderable')->name('admin.handle-renderable');
