let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
	// global library
    .js('packages/atom/src/assets/js/global.js', 'public/atom/js')
    .copyDirectory('packages/atom/src/assets/lib', 'public/atom')

    // Set version update
    .version()
