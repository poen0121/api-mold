<?php

namespace App\Http\Middleware\Accept;

use App\Exceptions\System\AcceptExceptionCode as ExceptionCode;
use Closure;
use DateTimeZone;

class Timezone
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
        /* Accept Timezone */
        $acceptTimezone = $request->header('x-timezone');
        if (isset($acceptTimezone)) {
            try {
                /* Verify timezone */
                $timezone = new DateTimeZone($acceptTimezone);
                /* Set Client timezone */
                define('CLIENT_TIMEZONE', $timezone->getName());
            } catch (\Throwable $th) {
                throw new ExceptionCode(ExceptionCode::TIMEZONE_BAD);
            }
        }
        /* Local timezone */
        $localTimezone = config('app.timezone');
        /* Accept client timezone */
        $timezone = (defined('CLIENT_TIMEZONE') ? CLIENT_TIMEZONE : $localTimezone);
        /* Return header timezone */
        $response = $next($request);
        $response->header('X-Timezone', $timezone);
        return $response;
    }
}
