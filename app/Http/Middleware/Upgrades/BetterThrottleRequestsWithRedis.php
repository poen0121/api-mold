<?php

namespace App\Http\Middleware\Upgrades;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequestsWithRedis;
use RuntimeException;
use Arr;
use Str;

class BetterThrottleRequestsWithRedis extends ThrottleRequestsWithRedis
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|string  $maxAttempts
     * @param  float|int  $decayMinutes
     * @param  string  $prefix
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        /* Check throttle whitelisted IPs */
        if (in_array($request->ip(), config('ban.throttle_whitelisted', []))) {
            return $next($request);
        }
        /* Check internal middleware adopts the last throttle */
        if ($route = $request->route()) {
            $middleware = $route->middleware();
            $middleware = Arr::where($middleware, function ($value, $key) {
                return (Str::startsWith($value, 'throttle:') && Str::substrCount($value, ',') === 2 && ! Str::endsWith($value, ',') ? true : false);
            });
            $middleware = end($middleware);
            if ($middleware !== false) {
                /* Preset trigger */
                if ($prefix === '') {
                    $middleware = strtr($middleware, ['throttle:' => '']);
                    $middleware = explode(',', $middleware);
                    $maxAttempts = $middleware[0];
                    $decayMinutes = $middleware[1];
                    $prefix = $middleware[2];
                } else {
                    return $next($request);
                }
            }
        }
        /* Signature key */
        $throttles = array_values(config('ban.throttle', []));

        $sort = array_search(config('ban.throttle.'.$prefix), $throttles, true);

        $prefix = ($sort === false ? '?'.$prefix : ':'.$sort);

        $key = $prefix.$this->resolveRequestSignature($request);

        $maxAttempts = $this->resolveMaxAttempts($request, $maxAttempts);

        if ($this->tooManyAttempts($key, $maxAttempts, $decayMinutes)) {
            throw $this->buildException($key, $maxAttempts);
        }

        $response = $next($request);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }

    /**
     * Resolve request signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function resolveRequestSignature($request)
    {
        $signature = '';
        /* User */
        if ($user = $request->user()) {
            $signature .= get_class($user).'|'.$user->getAuthIdentifier().'|';
        }
        /* Route */
        if ($route = $request->route()) {
            $signature .= $route->getDomain().'|'.$request->ip();
            return sha1($signature);
        }

        throw new RuntimeException('Unable to generate the request signature. Route unavailable.');
    }
}