<?php

namespace App\Http\Middleware\Record;

use Closure;

class Access
{
    /**
     * The block request body items.
     *
     * @var array
     */
    private $blockBody = [];

    /**
     * Filter replacement blocks the request body.
     *
     * @param  array &$data
     * @return void
     */
    public function filterBlockBody(array &$data)
    {
        $replace = function (&$value, $key) {
            if (in_array($key, $this->blockBody, true)) {
                $value = '******';
            } elseif (is_array($value)) {
                $this->filterBlockBody($value);
            }
        };
        array_walk($data, $replace);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* Check log type */
        if (in_array('access', config('activitylog.available_log_name', [])) && ! in_array('access', config('activitylog.ignore_log_name', []))) {
            /* Set block request body items */
            $this->blockBody = array_unique(config('activitylog.block_request_body', []));
            /* Get request body */
            $body = $request->all();
            /* Filter to replace block request body items */
            $this->filterBlockBody($body);
            /* Save activity log */
            activity('access')->withProperties(['ip' => $request->ip(), 'request_body' => $body])
            ->log($request->method() . ' ' . $request->path());
        }

        return $next($request);
    }
}
