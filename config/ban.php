<?php
return [

    /*
     |--------------------------------------------------------------------------
     | API Resource Version
     |--------------------------------------------------------------------------
     |
     | Example :
     | 'version' => 'v1'
     */
    
    'version' => env('API_VERSION', 'v1'),

    /*
     |--------------------------------------------------------------------------
     | API Throttle Whitelisted IP
     |--------------------------------------------------------------------------
     |
     | Set the APIs throttle whitelisted IP list.
     |
     | Example :
     | 'throttle_whitelisted' => [
     |    Whitelisted IPs
     | ]
     */

    'throttle_whitelisted' => [
        '::1',
        '127.0.0.1',
    ],

    /*
     |--------------------------------------------------------------------------
     | API Throttle Limit
     |--------------------------------------------------------------------------
     |
     | Set the APIs throttle limit fornat : [throttle:request count limit, request interval minute limit, type name].
     | Note: When interval minutes is 0, it is unlimited.
     |
     | Example :
     | 'throttle' => [
     |    Type name => 'throttle:60,1,Type name',
     | ]
     */

    'throttle' => [
        'base' => 'throttle:60,1,base',
        'auth' => 'throttle:30,1,auth',
        'login' => 'throttle:5,3,login',
        'logon' => 'throttle:10,3,logon',
        'forget_password' => 'throttle:5,3,forget_password',
        'modify_password' => 'throttle:5,3,modify_password',
        'verifycode' => 'throttle:5,3,verifycode',
        'logout' => 'throttle:5,3,logout',
    ],

    /*
     |--------------------------------------------------------------------------
     | Ignore Restrict Access
     |--------------------------------------------------------------------------
     |
     | Set the APIs to ignore the restricted access of access guards.
     |
     | Example :
     | 'ignore_restrict_access' => [
     |     Allow API named route to ignore restricted access
     | ]
     */

    'ignore_restrict_access' => [
        'auth.user.types',
        'auth.read.service',
        'auth.token.revoke',
        'auth.token.refresh',
    ],

    /*
     |--------------------------------------------------------------------------
     | Release Service Ban
     |--------------------------------------------------------------------------
     |
     | List of client service bans.
     | Forbidden and restricted use of client services is specified by an API named route.
     | Client service bans are unique serial number.
     | Controlled by middleware 'token.ban' .
     | Description file : (resources/lang/ language dir /ban.php)
     | Command: $php artisan config:add-ban-service
     |
     | Example :
     | 'release' => [
     |    Ban number => [
     |        'description' => Description code project,
     |        'restrict_access_guards' => Auth guards type array to restrict access (Ignored if using routing middleware "token.login" or in the configuration "ban.ignore_restrict_access"),
     |        'unique_auth_ignore_guards' => Auth guards type array to ignore unique auth column in the auth table,
     |        'unique_auth_inherit_login_guards' => Auth guards type array can inherit login from the unique auth column in the auth table,
     |        'status' => Available option status ( TRUE | FALSE ),
     |        'allow_named' => [
     |           Allow API named route or * all are allowed
     |        ],
     |        'unallow_named' => [
     |           Unallow API named route or * all are not allowed
     |        ]
     |    ],
     | ]
     */

    'release' => [
        0 => [
            'description' => 'global',
            'restrict_access_guards' => [],
            'unique_auth_ignore_guards' => [],
            'unique_auth_inherit_login_guards' => [],
            'status' => false,
            'allow_named' => [
                '*'
            ],
            'unallow_named' => []
        ],
        1 => [
            'description' => 'admin',
            'restrict_access_guards' => [],
            'unique_auth_ignore_guards' => [
                'client'
            ],
            'unique_auth_inherit_login_guards' => [],
            'status' => false,
            'allow_named' => [
                '*'
            ],
            'unallow_named' => []
        ],
        2 => [
            'description' => 'member',
            'restrict_access_guards' => [],
            'unique_auth_ignore_guards' => [
                'client'
            ],
            'unique_auth_inherit_login_guards' => [],
            'status' => false,
            'allow_named' => [
                '*'
            ],
            'unallow_named' => [
                'doc.auth',
                'auth.client.*',
                'system.parameter.*',
                'system.interface.*',
                'system.authority.*',
                'system.log.types',
                'system.log.index',
                'notice.bulletin.*',
                'sms.log.*',
            ]
        ],
        //:end-generating:
    ]
];
