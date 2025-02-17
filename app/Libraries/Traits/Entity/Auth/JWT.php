<?php

namespace App\Libraries\Traits\Entity\Auth;

use TokenAuth;

trait JWT
{
    /**
     * Log ignore record attributes.
     *
     * @var array
     */
    protected static $logAttributesToIgnore = [
        'unique_auth'
    ];

    /**
     * Custom declarations are added to the properties of the JWT.
     *
     * @var array
     */
    protected $claims = [];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return $this->claims;
    }

    /**
     * Set a key value array, containing any custom claims to be added to the JWT.
     *
     * @param array $claims
     * @return void
     */
    public function setJWTCustomClaims(array $claims)
    {
        $this->claims = $claims;
    }

    /**
     * Get the name of the login identifier for the user.
     *
     * @return string
     */
    public static function getLoginIdentifierName(): string
    {
        return 'account';
    }

    /**
     * Get the auth password for the user.
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->password;
    }

    /**
     * Verify if the other status of the user is abnormal.
     *
     * @return void
     * @throws \Exception
     */
    public function verifyHoldStatusOnFail()
    {
        if ($this->exists) {
            // You can use the $this->isCaptureRoute($specify = []) function to do some masking
            // You can set $this->getDefaultCaptureRoute() to affect $this->isCaptureRoute() function
            // Verify user other status throw exception
        }
    }

    /**
     * Get the default capture routes.
     *
     * @return array
     */
    public function getDefaultCaptureRoute(): array
    {
        /* Capture routes */
        return  [
            'auth.user.login',
            'auth.user.signature.login',
            // Add another route name
        ];
    }

    /**
     * Check capture routes.
     * 
     * @param array $specify
     * 
     * @return bool
     */
    public function isCaptureRoute(array $specify = null): bool
    {
        /* Specify route */
        $specify = (isset($specify) ? $specify : $this->getDefaultCaptureRoute());
        /* Check route name */
        if ($route = request()->route()) {
            if ($routeName = $route->getName()) {
                return in_array($routeName, $specify, true);
            }
        }

        return false;
    }

    /**
     * Get the signature validity time (in minutes).
     *
     * @return int|null
     */
    public function getUTSTTL(): ?int
    {
        /* Get the guard uts ttl */
        return TokenAuth::getUTSTTL($this);
    }

    /**
     * Get the jwt token validity time (in minutes).
     *
     * @return int|null
     */
    public function getTTL(): ?int
    {
        /* Get the guard jwt ttl */
        return TokenAuth::getTTL($this);
    }

    /**
     * Get unique_auth.
     *
     * @param mixed $value
     * @return string|null
     */
    public function getUniqueAuthAttribute($value): ?string
    {
        return ($this->exists || isset($value) ? (isset($value) ? $value : (in_array('unique_auth', $this->getFillable()) ? '' : null)) : null);
    }
}
