<?php

namespace Emamie\Atom\Middleware;

use Closure;

class ApiAuth
{
    const CODE = 401;
    const MESSAGE = 'Unauthorized';
    
    /**
     * Handle an incoming request.
     *      
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $service
     * @return mixed
     */
    public function handle($request, Closure $next, $service)
    {
        if (! $this->authorized($request, $service)) {
            return response()->json(['message' => self::MESSAGE], self::CODE);
        }

        return $next($request);
    }

    /**
     * Checks an incoming token against one in configs
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function authorized($request, $service)
    {
        $cfg = $this->getConfigs($service);

        if ($cfg['allow_json_token'] && $cfg['token'] === $request->input($cfg['request_token_name'])) {
            return true;
        }
        
        if ($cfg['allow_request_token'] && $cfg['token'] === $request->get($cfg['request_token_name'])) {
            return true;
        }

        return false;
    }

    /**
     * Returns config array for a given service
     *
     * @param $service
     * @return mixed
     */
    public function getConfigs($service)
    {
        return config('atom.api.auth.' . $service);
    }
}
