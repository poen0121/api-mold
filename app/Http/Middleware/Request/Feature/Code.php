<?php

namespace App\Http\Middleware\Request\Feature;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Libraries\Instances\Feature\Provider;

class Code
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
        $code = $request->route()->parameter('code');
        if (isset($code)) {
            $providers = Provider::getProvider();
            if (! isset($providers[$code]) || (isset($providers[$code]) && app($providers[$code])->getCode() === null)) {
                throw new ModelNotFoundException('Query Feature: No query results for providers: Unknown code \'' . $code . '\'.');
            }
        }
        
        return $next($request);
    }
}
