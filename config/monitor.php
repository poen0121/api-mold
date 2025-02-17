<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Redis Connection
    |--------------------------------------------------------------------------
    |
    | Set redis connection from config database.php.
    | Must match with one of the database's configured "redis".
    |
    | Note: Monitor data uses the redis sorted set.
    |
    */

    'connection' => env('MONITOR_REDIS_CONNECTION', 'default'),
    
    /*
     |--------------------------------------------------------------------------
     | Data Valid Time
     |--------------------------------------------------------------------------
     | Specify the length of time (in minutes) that the mark data will be valid for cache.
     |
     | Defaults to 1 minutes.
     | 'ttl' => 1
     */

    'ttl' => env('MONITOR_TTL', 1),
];
