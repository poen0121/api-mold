<?php

namespace App\Http\Middleware\Accept;

use Closure;
use App\Exceptions\System\AcceptExceptionCode as ExceptionCode;

class BanToken
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
        /* Check auth token guard */
        if (app('tymon.jwt')->getToken()) {
            throw new ExceptionCode(ExceptionCode::BAN_TOKEN);
        }

        return $next($request);
    }
}
