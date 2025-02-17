<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Providers\JWT\Lcobucci;
use App\Libraries\Upgrades\BetterJWTLcobucci;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;

class FixClassServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /* Fix builder token claims */
        $this->app->extend(Lcobucci::class, function () {
            return new BetterJWTLcobucci(
                new Builder(),
                new Parser(),
                config('jwt.secret'),
                config('jwt.algo'),
                config('jwt.keys')
            );
        });
        /* Use better data activity logger */
        $this->app->bind(\Spatie\Activitylog\ActivityLogger::class, \App\Libraries\Upgrades\BetterActivityLogger::class);
    }
}
