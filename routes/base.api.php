<?php

use Illuminate\Support\Facades\Route;

/*
 |--------------------------------------------------------------------------
 | Base API Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register base API routes for your application. These
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

/* Base API */
Route::group([
    'prefix' => config('ban.version')
], function ($router) {
    /* Auth Token API */
    Route::group([
        'as' => 'auth.',
        'prefix' => 'auth'
    ], function ($router) {
        /* Get access token */
        Route::post('/token', 'Service\AuthController@token')->middleware([config('ban.throttle.auth'), 'accept.bantoken'])->name('token.create');
        /* Refresh access token */
        Route::patch('/token/refresh', 'Service\AuthController@refresh')->middleware([config('ban.throttle.base'), 'token.refresh', 'token.ban'])->name('token.refresh');
        /* Revoke access token */
        Route::delete('/token', 'Service\AuthController@revoke')->middleware([config('ban.throttle.base'), 'token.revoke', 'token.ban'])->name('token.revoke');
        /* User login */
        Route::post('/user/login/{type}', 'Service\AuthController@login')->middleware([config('ban.throttle.login'), 'token.login', 'token.ban', 'request.user.type'])->name('user.login')->where('type', '[a-zA-Z0-9_]+');
        /* User signature login */
        Route::post('/user/signature/login', 'Service\AuthController@loginSignature')->middleware([config('ban.throttle.login'), 'token.login', 'token.ban'])->name('user.signature.login');
        /* User logout access token */
        Route::delete('/user/logout', 'Service\AuthController@logout')->middleware([config('ban.throttle.logout'), 'token.logout', 'token.ban'])->name('user.logout');
    });
    /* Auth Service API */
    Route::group([
        'middleware' => [
            config('ban.throttle.base'),
            'token.auth',
            'token.ban'
        ],
        'as' => 'auth.',
        'prefix' => 'auth'
    ], function ($router) {
        /* Access token show service profile */
        Route::get('/service', 'Service\AuthController@service')->name('read.service');
        /* User login types */
        Route::get('/user/type', 'Service\AuthController@userTypes')->name('user.types');
        /* User online */
        Route::get('/user/online/{type}', 'Service\AuthController@online')->middleware(['request.user.type'])->name('user.online')->where('type', '[a-zA-Z0-9_]+');
        /* Create user auth signature code */
        Route::post('/user/signature', 'Service\AuthController@userSignature')->name('user.signature');
        /* Client Service API */
        Route::group([
            'as' => 'client.',
            'prefix' => 'client'
        ], function ($router) {
            /* Get service bans */
            Route::get('/ban', 'Service\ClientController@bans')->name('bans');
            /* Get service ban info */
            Route::get('/ban/{number}', 'Service\ClientController@ban')->name('ban')->where('number', '[0-9]+');
            /* Get client service index */
            Route::get('/', 'Service\ClientController@index')->name('index');
            /* Get client service info */
            Route::get('/{app_id}', 'Service\ClientController@read')->middleware(['request.client.appid'])->name('read')->where('app_id', '[1-9]{1}[0-9]*');
            /* Create client service */
            Route::post('/', 'Service\ClientController@build')->name('build');
            /* Reset client service secret */
            Route::patch('/reset/{app_id}/secret', 'Service\ClientController@resetSecret')->middleware(['request.client.appid'])->name('reset.secret')->where('app_id', '[1-9]{1}[0-9]*');
            /* Rename client service */
            Route::patch('/{app_id}/name', 'Service\ClientController@rename')->middleware(['request.client.appid'])->name('rename')->where('app_id', '[1-9]{1}[0-9]*');
            /* Rewrite client service ban number */
            Route::patch('/{app_id}/ban', 'Service\ClientController@rewriteBan')->middleware(['request.client.appid'])->name('rewrite.ban')->where('app_id', '[1-9]{1}[0-9]*');
            /* Reschedule client service expiration */
            Route::patch('/reschedule/{app_id}', 'Service\ClientController@reschedule')->middleware(['request.client.appid'])->name('reschedule')->where('app_id', '[1-9]{1}[0-9]*');
            /* Disable client service */
            Route::patch('/disable/{app_id}', 'Service\ClientController@disable')->middleware(['request.client.appid'])->name('disable')->where('app_id', '[1-9]{1}[0-9]*');
            /* Enable client service */
            Route::patch('/enable/{app_id}', 'Service\ClientController@enable')->middleware(['request.client.appid'])->name('enable')->where('app_id', '[1-9]{1}[0-9]*');
        });
    });
    /* System Base API */
    Route::group([
        'middleware' => [
            config('ban.throttle.base'),
            'token.auth',
            'token.ban'
        ],
        'as' => 'system.',
        'prefix' => 'system'
    ], function ($router) {
        /* System Language API */
        Route::group([
            'as' => 'language.',
            'prefix' => 'language'
        ], function ($router) {
            /* Get support language index */
            Route::get('/', 'System\LanguageController@index')->name('index');
            /* Get default language */
            Route::get('/default', 'System\LanguageController@default')->name('default');
        });
        /* System Interface API */
        Route::group([
            'as' => 'interface.',
            'prefix' => 'interface'
        ], function ($router) {
            /* Get APIs */
            Route::get('/', 'System\InterfaceController@index')->name('index');
            /* Get managed APIs */
            Route::get('/managed', 'System\InterfaceController@managed')->name('managed');
            /* Get managed APIs by ban number */
            Route::get('/managed/ban/{number}', 'System\InterfaceController@managedByBan')->name('managed.ban')->where('number', '[0-9]+');
        });
    });
    /* System Log API */
    Route::group([
        'middleware' => [
            config('ban.throttle.base'),
            'token.auth',
            'token.ban'
        ],
        'as' => 'system.log.',
        'prefix' => 'system'
    ], function ($router) {
        /* Get data activity log type */
        Route::get('/log/type', 'System\DataActivityController@types')->name('types');
        /* Get data activity log index */
        Route::get('/logs/{type?}', 'System\DataActivityController@index')->middleware(['request.system.log.type'])->name('index')->where('type', '[a-zA-Z0-9_]+');
    });
    /* System Parameter API */
    Route::group([
        'middleware' => [
            config('ban.throttle.base'),
            'token.auth',
            'token.ban'
        ],
        'as' => 'system.parameter.',
        'prefix' => 'system/parameter'
    ], function ($router) {
        /* Get parameter index */
        Route::get('/', 'System\ParameterController@index')->name('index');
        /* Get parameter */
        Route::get('/{slug}', 'System\ParameterController@read')->middleware(['request.system.parameter.slug'])->name('read')->where('slug', '[a-zA-Z0-9_]+');
        /* Set parameter */
        Route::patch('/{slug}', 'System\ParameterController@rewriteValue')->middleware(['request.system.parameter.slug'])->name('rewrite.value')->where('slug', '[a-zA-Z0-9_]+');
    });
    /* System Authority API */
    Route::group([
        'middleware' => [
            config('ban.throttle.base'),
            'token.auth',
            'token.ban'
        ],
        'as' => 'system.authority.',
        'prefix' => 'system/authority'
    ], function ($router) {
        /* Get authority object types  */
        Route::get('/type', 'System\AuthorityController@objectTypes')->name('types');
        /* Global authority */
        Route::put('/global/{type}/{uid}', 'System\AuthorityController@global')->middleware(['request.system.authority.type', 'request.system.authority.typeuid'])->name('global')->where(['type' => '[a-zA-Z0-9_]+', 'uid' => '[1-9]{1}[0-9]*']);
        /* Grant authority */
        Route::patch('/grant/{type}/{uid}', 'System\AuthorityController@grant')->middleware(['request.system.authority.type', 'request.system.authority.typeuid'])->name('grant')->where(['type' => '[a-zA-Z0-9_]+', 'uid' => '[1-9]{1}[0-9]*']);
        /* Remove authority */
        Route::patch('/remove/{type}/{uid}', 'System\AuthorityController@remove')->middleware(['request.system.authority.type', 'request.system.authority.typeuid'])->name('remove')->where(['type' => '[a-zA-Z0-9_]+', 'uid' => '[1-9]{1}[0-9]*']);
        /* System Authority Snapshot API */
        Route::group([
            'as' => 'snapshot.',
            'prefix' => 'snapshot'
        ], function ($router) {
            /* Build authority snapshot */
            Route::post('/', 'System\AuthoritySnapshotController@build')->name('build');
            /* Get authority snapshot index */
            Route::get('/', 'System\AuthoritySnapshotController@index')->name('index');
            /* Get authority snapshot */
            Route::get('/{id}', 'System\AuthoritySnapshotController@read')->name('read')->where('id', '[a-f0-9]{32}');
            /* Rename authority snapshot */
            Route::patch('/{id}/name', 'System\AuthoritySnapshotController@rename')->name('rename')->where('id', '[a-f0-9]{32}');
            /* Delete authority snapshot */
            Route::delete('/{id}', 'System\AuthoritySnapshotController@delete')->name('delete')->where('id', '[a-f0-9]{32}');
        });
    });
    /* Message Notice API */
    Route::group([
        'middleware' => [
            config('ban.throttle.base'),
            'token.auth',
            'token.ban'
        ],
        'as' => 'notice.',
        'prefix' => 'notice'
    ], function ($router) {
        /* Bulletin API */
        Route::group([
            'as' => 'bulletin.',
            'prefix' => 'bulletin'
            ], function ($router) {
            /* Get bulletin index */
            Route::get('/', 'Message\BulletinController@index')->name('index');
            /* Get bulletin serial info */
            Route::get('/{id}', 'Message\BulletinController@read')->name('read')->where('id', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
            /* Label types */
            Route::get('/label/type', 'Message\BulletinController@labelTypes')->name('label.types');
            /* User types */
            Route::get('/user/type', 'Message\BulletinController@userTypes')->name('user.types');
            /* Build bulletin */
            Route::post('/build/{label}/{type}', 'Message\BulletinController@build')->middleware(['request.notice.bulletin.label', 'request.notice.bulletin.type'])->name('build')->where(['label' => '[a-zA-Z0-9_]+', 'type' => '[a-zA-Z0-9_]+']);
            /* Disable bulletin */
            Route::patch('/disable/{id}', 'Message\BulletinController@disable')->name('disable')->where('id', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
            /* Enable bulletin */
            Route::patch('/enable/{id}', 'Message\BulletinController@enable')->name('enable')->where('id', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
        });
        /* Letter API */
        Route::group([
            'as' => 'letter.',
            'prefix' => 'letter'
            ], function ($router) {
            /* User types */
            Route::get('/user/type', 'Message\NoticeController@userTypes')->name('user.types');
            /* Send message */
            Route::post('/send/{type}/{uid}', 'Message\NoticeController@sendMessage')->middleware(['request.notice.letter.target'])->name('send')->where(['type' => '[a-zA-Z0-9_]+', 'uid' => '[1-9]{1}[0-9]*']);
        });
        /* Get notice messages */
        Route::get('/{mark?}', 'Message\NoticeController@messages')->name('messages')->where('mark', 'read');
        /* Get unread notice messages */
        Route::get('/unread/{mark?}', 'Message\NoticeController@unreadMessages')->name('unread')->where('mark', 'read');
        /* Get unread notice count */
        Route::get('/count', 'Message\NoticeController@unreadCounts')->name('count');
        /* Mark read notice messages */
        Route::patch('/mark', 'Message\NoticeController@markRead')->name('mark');
        /* Remove notice messages */
        Route::patch('/remove', 'Message\NoticeController@remove')->name('remove');
    });
    /* SMS Log API */
    Route::group([
        'middleware' => [
            config('ban.throttle.base'),
            'token.auth',
            'token.ban'
        ],
        'as' => 'sms.log.',
        'prefix' => 'sms/log'
    ], function ($router) {
        /* Get SMS log index */
        Route::get('/', 'Sms\LogController@index')->name('index');
        /* Get SMS serial info */
        Route::get('/{serial}', 'Sms\LogController@read')->name('read')->where('serial', 'S[1-9]{1}[0-9]*');
    });
    /* Feature API */
    Route::group([
        'middleware' => [
            config('ban.throttle.base'),
            'token.auth',
            'token.ban'
        ],
        'as' => 'feature.',
        'prefix' => 'feature'
    ], function ($router) {
        /* Get support provider index */
        Route::get('/', 'Feature\ProviderController@index')->name('index');
        /* Get feature code info */
        Route::get('/{code}', 'Feature\ProviderController@read')->middleware(['request.feature.code'])->name('read')->where('code', '[a-zA-Z0-9_]+');
    });
    /* APIs Doc API */
    Route::group([
        'middleware' => [
            config('ban.throttle.base'),
            'token.auth',
            'token.ban'
        ],
        'as' => 'doc.',
        'prefix' => 'doc'
    ], function ($router) {
        /* Get APIs document auth link */
        Route::post('/auth', 'Service\AuthController@authDoc')->name('auth');
    });
});
