<?php

namespace Emamie\Atom\Core;

use Emamie\Atom\Exceptions;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Container\Container;

trait ServiceProviderTrait
{
    protected $package_key;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->setPkgKey();
        /**
         * change default ExceptionHandler
         */
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            Exceptions\Handler::class
        );

        $this->registerRouteMiddleware();

        $this->registerConnectionServices();

        $this->registerExtend();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Override ApiGenerator command for atom
         */
        if ($this->app->runningInConsole()) {
            $this->commands([

            ]);
        }

        /** clear cache in local env */
        \Route::group(['middleware'=>['clearcache']], function(Router $router) {

            /*
             * load api routes
             */

            \Route::group([
                'prefix' => 'api',
                'namespace' => 'Razavi\\Webinar\\ApiController',
                'middleware' => ['apiauth:razavi/webinar'],
            ], function (Router $router) {

                $route = __DIR__ . '/../routes/api.php';
                if (file_exists($route)) $this->loadRoutesFrom($route);

            });

            /*
             * Auth routes
             *
             * Load from vendor/encore/laravel-admin/src/Admin.php@registerAuthRoutes()
             */
            \Route::group([
                'prefix' => config('admin.route.prefix'),
                'namespace' => 'Encore\Admin\Controllers',
                'middleware' => config('admin.route.middleware'),
            ], function (Router $router) {

                $route = __DIR__ . '/../routes/backend-auth.php';
                if (file_exists($route)) $this->loadRoutesFrom($route);

            });

            /*
             * Backend routes
             */
            \Route::group([
                'prefix' => config('admin.route.prefix'),
                'namespace' => 'Razavi\\Webinar\\BackendController',
                'middleware' => config('admin.route.middleware'),
            ], function (Router $router) {

                $route = __DIR__ . '/../routes/backend.php';
                if (file_exists($route)) $this->loadRoutesFrom($route);

            });

            /*
             * Frontend routes
             */
            \Route::group([
                // 'prefix'        => '',
                'namespace' => 'Razavi\\Webinar\\FrontendController',
                'middleware' => ['web'],
            ], function (Router $router) {

                $route = __DIR__ . '/../routes/frontend.php';
                if (file_exists($route)) $this->loadRoutesFrom($route);

            });

        });

        /*
         * Load views
         */
        $this->loadViewsFrom(__DIR__.'/../views', $this->package_key);

        /*
         * Load translations
         */
        $this->loadTranslationsFrom(__DIR__.'/../translations', $this->package_key);


        /*
         * publish migrations
         */
        $this->publishes([
            $this->publishMigrations()
        ], 'migrations');

        /*
         * publish configurations
         */
        $this->publishes([
            __DIR__.'/../../webinar.yml' => config_path('webinar.yml'),
        ], 'configuration');

        \Yaml::loadToConfig(config_path('webinar.yml'), 'webinar');


        $this->bootExtend();

    }

    protected function registerExtend()
    {
        //
    }

    protected function bootExtend()
    {
        //
    }

    /**
     *
     *
     * @return array of migrations
     */
    protected function publishMigrations()
    {
        return [
            __DIR__.'/../migrations/' => database_path('migrations'),
        ];
    }

    /**
     * @return array of configuration for publish
     */
    protected function publishConfigurations()
    {
        return [
            __DIR__.'/../../config.php' => config_path('atom/' . $this->package_key . '.php'),
        ];
    }

    /**
     * @throws \Exception
     */
    protected function setPkgKey()
    {
        $classname = get_class($this);

        $classname_array = explode('\\', $classname);

        if(!empty($classname_array[1])) {
            $this->package_key = $classname_array[1];
        } else {
            throw new \Exception('package key not found');
        }
    }
}