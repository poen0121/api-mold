<?php

namespace App\Libraries\Upgrades;

use Tymon\JWTAuth\Providers\JWT\Lcobucci;
use Lcobucci\JWT\Builder;

class BetterJWTLcobucci extends Lcobucci
{
    /**
     * Create a JSON Web Token.
     *
     * @param  array  $payload
     *
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     *
     * @return string
     */
    public function encode(array $payload)
    {
        // Initialization builder
        $this->builder = new Builder;
        // Encode payload
        return parent::encode($payload);
    }
}
