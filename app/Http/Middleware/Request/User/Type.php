<?php

namespace App\Http\Middleware\Request\User;

use Closure;
use TokenAuth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
            $type = TokenAuth::userTypes([
                'class',
                'type'
            ], $type);
            /* Check restrict access guards */
            $restrict = config('ban.release.' . TokenAuth::getClient()->ban . '.restrict_access_guards');
            if (is_array($restrict) && count($restrict) > 0 && ! in_array($type['type'], $restrict)) {
                throw new ModelNotFoundException('Query Auth: No query results for guards: Unavailable type \'' . $type['type'] . '\'.');
            }
            /* Set type info */
            $request->route()->setParameter('type', $type);
        }

        return $next($request);
    }
}
