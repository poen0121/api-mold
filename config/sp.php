<?php
return [
    
    /*
    |--------------------------------------------------------------------------
    | Cache Store
    |--------------------------------------------------------------------------
    |
    | Enabled system parameters to use cache storage.
    | Set storage cache from config cache.php.
    | Must match with one of the application's configured cache "stores".
    |
    | Note: Use cache to improve read response speed.
    | Suggested: "memcached", "redis"
    |
    */

    'cache_store' => env('SYSTEM_PARAMETER_CACHE_STORE', null),

    /*
    |--------------------------------------------------------------------------
    | Parameter Value Validation Rule
    |--------------------------------------------------------------------------
    |
    | Command: $php artisan data:sp-add
    | Set the system parameter value rule.
    |
    | Example :
    |  'rules' => [
    |      Parameter name => [
    |	     Validation rule.
    |      ]
    |  ]
    */
    
    'rules' => [
        'activity_query_limit_months' => [
            'required',
            'integer',
            'between:1,6',
        ],
        'sms_query_limit_months' => [
            'required',
            'integer',
            'between:1,6',
        ],
        //:end-generating:
    ]
];
