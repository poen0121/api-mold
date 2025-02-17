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
    | Note: Map data uses the redis hashes.
    |
    */

    'connection' => env('MAP_REDIS_CONNECTION', 'default'),
    
];
