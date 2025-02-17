<?php

namespace App\Http\Middleware\Request\System\Parameter;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Slug
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
        $slug = $request->route()->parameter('slug');
        if (isset($slug)) {
            $rules = config('sp.rules');
            if (! isset($rules[$slug])) {
                throw new ModelNotFoundException('Query System Parameter: No query results for index: Unknown slug \'' . $slug . '\'.');
            }
        }
        
        return $next($request);
    }
}
