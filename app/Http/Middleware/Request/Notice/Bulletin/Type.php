<?php

namespace App\Http\Middleware\Request\Notice\Bulletin;

use Closure;
use App\Libraries\Instances\Notice\Bulletin;

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
            $type = Bulletin::userTypes([
                'class',
                'type'
            ], $type);
            /* Set type info */
            $request->route()->setParameter('type', $type);
        }

        return $next($request);
    }
}
