<?php

namespace Emamie\Atom\Middleware;

use Closure;
use Artisan;

class ClearCache
{
    public function handle($request, Closure $next)
    {
        Artisan::call('view:clear');
        return $next($request);
    }
}
