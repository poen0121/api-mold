<?php

namespace App\Http\Middleware\Request\Notice\Letter;

use Closure;
use App\Libraries\Instances\Notice\LetterType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use TokenAuth;
use Str;

class AuthTarget
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
            $type = LetterType::heldUserTypes(TokenAuth::getUser(), [
                'class',
                'type'
            ], $type);
            /* Check the user id format */
            $tid = $request->route()->parameter('uid');
            if (isset($type['class'], $tid)) {
                $id = app($type['class'])->asPrimaryId($tid);
                if (! isset($id)) {
                    throw new ModelNotFoundException('Query '. Str::studly($type['type']) .': No query results for users: Unknown user uid \'' . $tid . '\'.');
                } else {
                    /* Set real id */
                    $request->route()->setParameter('uid', $id);
                }
            }
            /* Set type info */
            $request->route()->setParameter('type', $type);
        }

        return $next($request);
    }
}
