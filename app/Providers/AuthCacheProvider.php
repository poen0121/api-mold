<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Cache;
use TokenAuth;
use Carbon;

/**
 * Class AuthCacheProvider
 * @package App\Auth
 */
class AuthCacheProvider extends EloquentUserProvider
{

    /**
     * AuthCacheProvider constructor.
     * @param HasherContract $hasher
     */
    public function __construct(HasherContract $hasher)
    {
        if ($guard = TokenAuth::getAuthGuard()) {
            $model = TokenAuth::getGuardModel($guard);
        } else {
            $guard = config('auth.defaults.guard');
            $model = config('auth.providers.' . config('auth.guards.' . $guard . '.provider') . '.model');
        }
        parent::__construct($hasher, $model);
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $model = $this->createModel();
        $guard = TokenAuth::getAuthGuard($model);
        $expires = Carbon::now()->addMinutes($model->getTTL() ?: config('jwt.ttl', 60));
        return Cache::remember(TokenAuth::getCacheKey($identifier, $guard), $expires, function () use ($identifier, $model) {
            return $this->newModelQuery($model)
                    ->where($model->getAuthIdentifierName(), $identifier)
                    ->first();
        });
    }
}
