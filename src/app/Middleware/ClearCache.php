<?php

namespace Emamie\Atom\Middleware;

use Closure;
use Artisan;

/**
 * Class ClearCache
 *
 * clear laravel cache in development
 *
 * @package Emamie\Atom\Middleware
 */
class ClearCache
{
    public function handle($request, Closure $next)
    {

        if( \App::isLocal() ) {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
        }

        return $next($request);
    }
}