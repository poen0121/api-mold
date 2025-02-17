<?php

namespace App\Http\Middleware\Accept;

use App\Exceptions\System\AcceptExceptionCode as ExceptionCode;
use Closure;

class ResponseType
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $paths = config('app.accept_response_paths', ['api/*' => 'application/json']);
        /* Check response limit type */
        foreach ($paths as $path => $format) {
            if ($request->is($path) && ! $request->accepts($format)) {
                throw new ExceptionCode(ExceptionCode::UNACCEPTABLE_RESPONSE_TYPE);
            }
        }

        return $next($request);
    }
}