<?php

namespace Emamie\Atom\Core;

use Emamie\Atom\Exceptions;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Container\Container;

trait ServiceProviderTrait
{
    protected $package_path;

    protected $package_key;

    protected $package_namespace;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->load();

        /**
         * change default ExceptionHandler
         */
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            Exceptions\Handler::class
        );

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

        /**
         * Load routes only for specific domains
         */
        if( in_array(request()->getHttpHost(), (array) config('app.' . $this->package_key . '.domains'))) {

            /*
             * load api routes
             */
            \Route::group([
                'prefix' => config('app.' . $this->package_key . '.api_prefix', 'api'),
                'namespace' => $this->package_namespace . '\\Api',
                'middleware' => $this->middlewaresApi(),
            ], function (Router $router) {
                $route = realpath($this->package_path . '/../routes/api.php');
                if (file_exists($route)) $this->loadRoutesFrom($route);
            });

            /*
             * Backend routes
             */
            \Route::group([
                'prefix' => config('admin.route.prefix'),
                'namespace' => 'Razavi\\Webinar\\Backend',
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
        }

        /*
         * Load views
         */
        $this->loadViewsFrom(realpath($this->package_path . '/../views'), $this->package_key);

        /*
         * Load translations
         */
        $this->loadTranslationsFrom(realpath($this->package_path . '/../translations'), $this->package_key);

        /*
         * publish migrations
         */
        $this->publishes(
            $this->publishMigrations(), 'migrations');

        /*
         * publish configurations
         */
        $this->publishes(
            $this->publishConfigurations(), 'configurations');

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

    protected function middlewaresGlobal()
    {
        $middlewares = [];

        if(\App::isLocal()){
            $middlewares[] = 'clearcache';
        }

        return $middlewares;
    }

    protected function middlewaresApi()
    {
        $middlewares = $this->middlewaresGlobal();

        // @TODO : replace with passport method
        $middlewares[] = 'apiauth:razavi/' . $this->package_key;

        return $middlewares;
    }

    protected function load()
    {
        $this->setPkgPath();

        $this->setPkgKey();

        $this->setPkgNamespace();
    }

    /**
     *
     *
     * @return array of migrations
     */
    protected function publishMigrations()
    {
        return [
            realpath($this->package_path . '/../migrations/') => database_path('migrations'),
        ];
    }

    /**
     * @return array of configuration for publish
     */
    protected function publishConfigurations()
    {
        return [
            realpath($this->package_path . '/../../config.php') => config_path( 'app/' . $this->package_key . '.php'),
        ];
    }

    /**
     * @throws \Exception
     */
    protected function setPkgKey()
    {
        $config = include_once realpath($this->package_path . '/../../config.php');

        if(!empty($config['key'])) {
            $this->package_key = $config['key'];
        } else {
            throw new \Exception('package key is empty');
        }
    }

    protected function setPkgPath()
    {
        $reflector = new \ReflectionClass(get_class());

        $fn = $reflector->getFileName();

        $this->package_path = dirname($fn);
    }

    protected function setPkgNamespace()
    {
        $reflector = new \ReflectionClass(get_class());

        $namespace = $reflector->getNamespaceName();

        $this->package_namespace = $namespace;
    }
}
