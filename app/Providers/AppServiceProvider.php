<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Notification;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* Set default schema string length */
        Schema::defaultStringLength(191);
        /* Choose URL Scheme */
        if (stripos(config('app.url'), 'https:') !== false) {
            /* Force https */
            URL::forceScheme('https');
        }
        /* Register validator */
        \Validator::extend('taiwan_id_verifier', 'App\Libraries\Verifiers\TaiwanIdVerifier@validate');
        \Validator::extend('amount_verifier', 'App\Libraries\Verifiers\AmountVerifier@validate');
        \Validator::extend('instanceof_verifier', 'App\Libraries\Verifiers\InstanceofVerifier@validate');
        //:end-verifier-generating:
        /* Register observers for models */
        /* Auth observers for models */
        \App\Entities\Service\Auth::observe(\App\Observers\Service\AuthObserver::class);
        \App\Entities\Service\Client::observe(\App\Observers\Service\AuthObserver::class);
        //:end-auth-observer-generating:
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //register l5-repository
        $this->app->register(RepositoryServiceProvider::class);
        //register macro lang
        $this->app->register(LangMacroServiceProvider::class);
        //register macro response
        $this->app->register(ResponseMacroServiceProvider::class);
        //register fix class optimization
        $this->app->register(FixClassServiceProvider::class);
        //register SMS channel
        Notification::extend('sms', function ($app) {
            return $app->make(\App\Channels\SmsChannel::class);
        });
    }
}
