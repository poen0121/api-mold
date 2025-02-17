<?php

namespace App\Http\Middleware\Apidoc;

use Closure;
use StorageSign;

class Watchdog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $named = $request->route()->getName();
        /* Check apidoc.json */
        if (($named === 'apidoc.json' && !config('apidoc.postman.enabled', false))) {
            abort(404, 'Resource not found.');
        }
        /* Check auth mode */
        if (config('apidoc.laravel.auth_mode')) {
            /* Check auth signature */
            $code = $request->input('auth');
            if (isset($code)) {
                if (!($data = StorageSign::get($code)) || !isset($data[0]) || $data[0] !== 'apidoc') {
                    abort(401, 'Unauthorized.');
                }
            }
            /* Check whitelisted */
            $whitelisted = config('apidoc.laravel.whitelisted', []);
            if (!isset($code) && !in_array($request->ip(), $whitelisted)) {
                abort(401, 'Unauthorized.');
            }
        }
        return $next($request);
    }
}
