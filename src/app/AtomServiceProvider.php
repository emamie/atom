<?php

namespace Emamie\Atom;

use Illuminate\Support\ServiceProvider;
use Emamie\Atom\ApiGenerator;
use PHPUnit\Test\Extension;
use Encore\Admin\Form;
use Razavi\Atom\Services\RestWebService;

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

        \Encore\Admin\Form::extend('date_persian', admin\Extensions\DatePersianField::class);
        \Encore\Admin\Form::extend('ckeditor', admin\Extensions\CKEditor::class);
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
         * Publish config file
         *
         * php artisan vendor:publish --provider="Emamie\Atom\AtomServiceProvider"
         */
        $this->publishes([
            realpath(__DIR__.'/../config') => config_path('atom'),
        ]);

        /*
         * Publish assets
         */
        $this->publishes([
            __DIR__.'/../templates/bootstrap' => public_path('vendor/bootstrap'),
            __DIR__.'/../templates/AdminLTE-2.3.5-RTL/css' => public_path('vendor/laravel-admin/AdminLTE/dist/css'),
        ], 'public');

        /*
         * Register ClearCache middleware for development
         */
        $router->aliasMiddleware('clearcache', 'Emamie\\Atom\\Middleware\\ClearCache');

        /*
         * Register api token middleware
         */
        $router->aliasMiddleware('apiauth', 'Emamie\\Atom\\Middleware\\ApiAuth');

        /*
         * Load backend route
         */
        $this->loadRoutesFrom(__DIR__.'/../routes/backend.php');

        /*
         * publish migrations
         */
        $this->publishes([
            __DIR__.'/../migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConnectionServices();
    }
    protected function registerConnectionServices()
    {
        $this->app->bind('RestWebService', RestWebService::class);
    }
}
