let mix = require('laravel-mix');

require('laravel-mix-merge-manifest');

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

/** in production prevent cache **/
if (mix.inProduction()) {
    mix.version();
}


/** generate map file in development env */
if ( ! mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'source-map'
    })
        .sourceMaps()
}

mix
    .mergeManifest()

	// global library
    .js('packages/atom/src/assets/js/global.js', 'public/vendor/atom/js')
    .copyDirectory('packages/atom/src/assets/lib', 'public/vendor/atom/lib')

