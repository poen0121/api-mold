<?php

namespace App\Http\Middleware\Request\System\Authority;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TypeUid
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
        $tid = $request->route()->parameter('uid');
        if (isset($type['class'], $tid)) {
            /* Check the object id format */
            $id = app($type['class'])->asPrimaryId($tid);
            if (! isset($id)) {
                throw new ModelNotFoundException('Query Authority: No query results for types: Unknown type uid \'' . $tid . '\'.');
            } else {
                /* Set real id */
                $request->route()->setParameter('uid', $id);
            }
        }

        return $next($request);
    }
}
