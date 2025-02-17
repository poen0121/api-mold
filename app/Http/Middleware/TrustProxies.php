<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     * Trust *all* "X-Forwarded-*" headers , please use Request::HEADER_X_FORWARDED_ALL
     * If your proxy instead uses the "Forwarded" header , please use Request::HEADER_FORWARDED
     * If you're using AWS ELB , please use Request::HEADER_X_FORWARDED_AWS_ELB
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
