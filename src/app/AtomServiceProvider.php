<?php

namespace Emamie\Atom;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Emamie\Atom\ApiGenerator;
use PHPUnit\Test\Extension;
use Encore\Admin\Form;
use Razavi\Atom\Services\RestWebService;
use Emamie\Atom\Services\RestApiService;
use Emamie\Atom\Exceptions;

class AtomServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {

        date_default_timezone_set(env('APP_TIMEZONE', "Asia/Tehran"));

        \Encore\Admin\Form::extend('date_persian', Admin\Extensions\DatePersian::class);
        \Encore\Admin\Form::extend('ckeditor', Admin\Extensions\CKEditor::class);
        \Encore\Admin\Form::extend('select2', Admin\Extensions\Select2::class);
        \Encore\Admin\Form::extend('table2', Admin\Extensions\Table2::class);
        \Encore\Admin\Form::extend('number2', Admin\Extensions\Number2::class);
        \Encore\Admin\Form::extend('phone2', Admin\Extensions\Phone2::class);
        \Encore\Admin\Form::extend('mobile2', Admin\Extensions\Mobile2::class);
        \Encore\Admin\Form::forget('DateMultiple');
        /*
         * Override ApiGenerator command for atom
         */
        if ($this->app->runningInConsole()) {
            $this->commands([
                Command\InstallCommand::class,
                Command\APIGeneratorCommand::class,
            ]);
        }
        $this->loadViewsFrom(__DIR__.'/../views', 'atom');

        /*
         * Publish assets
         */
        if ($this->app->runningInConsole()) {

            /*
             * php artisan vendor:publish --tag=atom
             */

            $this->publishes([
                realpath(__DIR__.'/../config') => config_path(''),
            ], ['atom','configurations','atom-configuration']);

            $this->publishes([
                __DIR__ .'/../migrations' => database_path('migrations'),
            ], ['atom','migrations','atom-migrations']);

            $this->publishes([
                __DIR__ . '/../templates/bootstrap' => public_path('vendor/bootstrap'),
                __DIR__ . '/../templates/AdminLTE-2.3.5-RTL/css' => public_path('vendor/laravel-admin/AdminLTE/dist/css'),
                __DIR__ . '/../templates/AdminLTE-2.3.5-RTL/fonts' => public_path('vendor/laravel-admin/AdminLTE/dist/fonts'),
                __DIR__ . '/../assets/lib' => public_path('vendor/atom'),
                __DIR__ . '/../assets/img' => public_path('vendor/atom/img'),
                __DIR__ . '/../assets/css/bootstrap.rtl.full.min.css' => public_path('vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css'),
            ], ['atom','assets','atom-assets']);

            $this->publishes(
                $this->publishConfigurations(), ['atom','configurations','atom-configuration']);

        }

        /*
         * Register ClearCache middleware for development
         */
        $router->aliasMiddleware('clearcache', 'Emamie\\Atom\\Middleware\\ClearCache');

        /*
         * Register api token middleware
         */
        $router->aliasMiddleware('apiauth', 'Emamie\\Atom\\Middleware\\ApiAuth');

        /*
         * Auth routes
         *
         * Load from vendor/encore/laravel-admin/src/Admin.php@registerAuthRoutes()
         */
        \Route::group([
            'prefix' => config('admin.route.prefix'),
//            'namespace' => 'Encore\Admin\Controllers',
            'namespace' => 'Emamie\Atom\Auth',
            'middleware' => config('admin.route.middleware'),
        ], function (Router $router) {

            $route = __DIR__ . '/../routes/backend-auth.php';
            if (file_exists($route)) $this->loadRoutesFrom($route);

        });

        /*
         * Backend routes
         *
         * Load from vendor/encore/laravel-admin/src/Admin.php@registerAuthRoutes()
         */
        \Route::group([
            'prefix' => config('admin.route.prefix'),
            'namespace' => 'Encore\Admin\Controllers',
            'middleware' => config('admin.route.middleware'),
        ], function (Router $router) {

            $route = __DIR__ . '/../routes/backend.php';
            if (file_exists($route)) $this->loadRoutesFrom($route);

        });

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * change default ExceptionHandler
         */
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            Exceptions\Handler::class
        );

        $this->registerConnectionServices();
    }
    protected function registerConnectionServices()
    {
        // @TODO: remove after class RestApiService
        // $this->app->bind('RestWebService', RestWebService::class);
        
        $this->app->bind(
            'Emamie\Atom\Services\RestApiServiceInterface',
            'Emamie\Atom\Services\RestApiService'
        );
        // $this->app->bind('RestApiService', RestApiService::class);


     
    }

    protected function publishConfigurations()
    {
        $reflector = new \ReflectionClass(get_class());

        $fn = $reflector->getFileName();

        $package_path = dirname($fn);

        return [
            realpath($package_path . '/../../config.php') => config_path( 'app/atom.php'),
        ];
    }

    

}
