<?php

namespace Emamie\Atom\Core;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Container\Container;
use Illuminate\Console\Scheduling\Schedule;

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
         * Add Schedule Task To Run
         */
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $this->schedule($schedule);
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

        /**
         * Load routes only for package domains
         */
        if( in_array(request()->getHttpHost(), (array) config('app.' . $this->package_key . '.domains'))) {

            /*
             * routes load
             */
            $routes_load = $this->routesLoad();

            if(is_array($routes_load)){

                foreach ($routes_load as $route_load){

                    \Route::group([
                        'prefix' => $route_load['prefix'],
                        'namespace' => $route_load['namespace'],
                        'middleware' => $route_load['middleware'],
                    ], function (Router $router) use ($route_load) {

                        if (file_exists($route_load['file'])) $this->loadRoutesFrom($route_load['file']);

                    });

                }

            }

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
            $middlewares['clearcache'] = 'clearcache';
        }

        return $middlewares;
    }

    protected function middlewaresApi()
    {
        $middlewares = $this->middlewaresGlobal();

        // @TODO : replace with passport method
        $middlewares['apiauth'] = 'apiauth:razavi/' . $this->package_key;

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

    protected function routesLoad()
    {
        $route_files = [];

        /** api route */
        $route_files['api-v1'] = [
            'prefix' => config('app.' . $this->package_key . '.api_prefix', 'api'),
            'namespace' => $this->package_namespace . '\\Api',
            'middleware' => $this->middlewaresApi(),
            'file' => realpath($this->package_path . '/../routes/api.php'),
        ];

        /** admin route */
        $route_files['admin'] = [
            'prefix' => config('admin.route.prefix'),
            'namespace' => $this->package_namespace . '\\BackendController',
            'middleware' => config('admin.route.middleware'),
            'file' => realpath($this->package_path . '/../routes/backend.php'),
        ];

        /** front route */
        $route_files['front'] = [
            'prefix'        => '',
            'namespace' => $this->package_namespace . '\\FrontendController',
            'middleware' => ['web'],
            'file' => realpath($this->package_path . '/../routes/frontend.php'),
        ];

        return $route_files;
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
            // @TODO fix error on console
            // throw new \Exception('package key is empty');
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

    protected function schedule($schedule)
    {
        /*
        $schedule->command('sample:command')
            ->withoutOverlapping(10)// lock 10 minutes
            ->onOneServer()
            ->runInBackground()
            ->everyMinute()
            ->appendOutputTo(storage_path('logs/cron-'. $this->package_key .'-'. date("YmdH") .'.log'));
        */
    }
}
