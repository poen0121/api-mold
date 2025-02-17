<?php

namespace App\Http\Middleware\Request\Client;

use App\Entities\Service\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Closure;

class AppId
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
        /* Check the app id format */
        $tid = $request->route()->parameter('app_id');
        if (isset($tid)) {
            $id = app(Client::class)->asPrimaryId($tid);
            if (! isset($id)) {
                throw new ModelNotFoundException('Query Client: No query results for index: Unknown app id \'' . $tid . '\'.');
            } else {
                /* Set real id */
                $request->route()->setParameter('app_id', $id);
            }
        }

        return $next($request);
    }
}
