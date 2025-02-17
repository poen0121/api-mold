<?php

use Illuminate\Support\Facades\Route;

/*
 |--------------------------------------------------------------------------
 | API Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register API routes for your application. These
 | routes are loaded by the RouteServiceProvider within a group which
 | is assigned the "api" middleware group. Enjoy building your API!
 |
 |--------------------------------------------------------------------------
 | API Note
 |--------------------------------------------------------------------------
 | 1.The specified throttle middleware must have a type name and cannot be empty.
 |
 | 2.Only the route name definition can be a valid description interface.
 |
 | 3.Auth API Operation : Used middleware "token.auth".
 |
 | 4.Make good use of request protection middleware "throttle:?,?" by ban.php config.
 |
 | 5.Open API Operation : No middleware "token.ban".
 |
 | 6.Through ban.php configuration, make the API version use "ban.version".
 |
 */

/* Customize API Operation */
Route::group([
    'middleware' => [
        config('ban.throttle.base'),
        'token.auth',
        'token.ban'
    ],
    'prefix' => config('ban.version')
], function ($router) {

    // Make some resource API interface routing
});

/* Other API Operation Customize Middleware */
Route::group([
    'prefix' => config('ban.version')
], function ($router) {

    // Make some resource API interface routing
});