<?php

namespace Emamie\Atom\Middleware;

use Closure;
use Artisan;

class ClearCache
{
    public function handle($request, Closure $next)
    {
        Artisan::call('cache:clear');
        Artisan::call('route:cache');
        Artisan::call('config:cache');
        Artisan::call('view:cache');
        return $next($request);
    }
}
