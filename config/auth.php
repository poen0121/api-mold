<?php

return [

    /*
     |--------------------------------------------------------------------------
     | User Temporary Signature Valid Time Default
     |--------------------------------------------------------------------------
     | Specify the length of time (in minutes) that the authcode will be valid for cache.
     | You can also set this to null, to yield a never expiring signature.
     |
     | Defaults to 3 minutes.
     | 'uts_ttl' => 3
     */

    'uts_ttl' => env('UTS_TTL', 3),

    /*
     |--------------------------------------------------------------------------
     | User Auth Mail Temporary Signature Valid Time
     |--------------------------------------------------------------------------
     | Specify the length of time (in minutes) that the authcode will be valid for cache.
     | You can also set this to null, to yield a never expiring signature.
     |
     | Defaults to 60 minutes.
     | 'muts_ttl' => 60
     */

    'muts_ttl' => env('MUTS_TTL', 60),

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'client',
        // 'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    | 'guards' => [
    |    Guard type code => [
    |        'driver' => Guard driver,
    |        'provider' => Guard provider,
    |        'jwt_ttl' => JWT time to live,
    |        'jwt_refresh_ttl' => The refresh lifetime of JWT is only valid when jwt_ttl is not null,
    |        'uts_ttl' => User signature time to live,
    |        'login_rule' => [
    |           'account' => 'required|between:1,128', // Customize user login request account rules
    |           'password' => 'required|between:8,16' // Customize user login request password rules
    |        ]
    |    ]
    | ]
    |
    | Note : If login_rule returns null, it will cancel the API interface user login function.
    |
    */

    'guards' => [
        /* Client Auth */
        'client' => [
            'driver' => 'jwt',
            'provider' => 'service_auth',
            'jwt_ttl' => env('JWT_TTL', 60),
            'jwt_refresh_ttl' => env('JWT_REFRESH_TTL', 20160),
            'uts_ttl' => env('UTS_TTL', 3),
            'login_rule' => null
        ],
        /* Other auth user type */
        //:end-guard-generating:
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        /* Client auth model */
        'service_auth' => [
            'driver' => 'eloquent',
            'model' => App\Entities\Service\Auth::class
        ],
        /* Other auth user type model */
        //:end-provider-generating:

    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    // 'passwords' => [
    //     'users' => [
    //         'provider' => 'users',
    //         'table' => 'password_resets',
    //         'expire' => 60,
    //         'throttle' => 60,
    //     ],
    // ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    // 'password_timeout' => 10800,
];
