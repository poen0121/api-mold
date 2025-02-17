<?php

namespace App\Http\Middleware\Request\System\Log;

use Closure;
use App\Repositories\System\DataActivityRepository;

class Type
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
        $type = $request->route()->parameter('type');
        if (isset($type)) {
            /* Check type */
            $type = app(DataActivityRepository::class)->types(['type'], $type);
            /* Set type info */
            $request->route()->setParameter('type', $type['type']);
        }

        return $next($request);
    }
}
