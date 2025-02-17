<?php

namespace App\Http\Middleware\Request\Notice\Bulletin;

use Closure;
use App\Libraries\Instances\Notice\Bulletin;

class Label
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
        $label = $request->route()->parameter('label');
        if (isset($label)) {
            /* Check type */
            $label = Bulletin::labelTypes([
                'type'
            ], $label);
            /* Set type info */
            $request->route()->setParameter('label', $label);
        }

        return $next($request);
    }
}
