<?php
/*
 * Copy file from /vendor/encore/laravel-admin/src/Console/stubs/bootstrap.stub
 *
 * Change  config: admin.directory = base_path('vendor/emamie/atom/src/admin')
 */

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

// Encore\Admin\Form::forget(['map', 'editor']);


//Admin::css('/vendor/atom/css/bootstrap.rtl.full.min.css');
/*
 * not need asset: in publish vendor replace by main admin-lte.css
 */
// Admin::css('/vendor/adminlte/css/AdminLTE-rtl.min.css');

/* add ckeditor : http://laravel-admin.org/docs/#/en/model-form-field-management?id=integrate-ckeditor */
