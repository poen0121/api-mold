<?php
return [

    /*
    |--------------------------------------------------------------------------
    | SMS Activity Record
    |--------------------------------------------------------------------------
    |
    | About: App\Channels\SmsChannel class
    | If set to false, no SMS activities will be saved to the database.
    |
    | Example :
    | 'logger' => true
    |
    */
    
    'logger' => env('SMS_ACTIVITY_LOGGER_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | SMS Notification Telecomer
    |--------------------------------------------------------------------------
    |
    | Set up a list of SMS notification class.
    | Command: $php artisan make:notification-sms
    |
    | Example :
    | 'notifications' =>
    |    SMS telecomer type code => Notification class name,
    | ]
    |
    */

    'notifications' => [
        //:end-notification-generating:
    ],

     /*
     |--------------------------------------------------------------------------
     | Target Able Entities Model
     |--------------------------------------------------------------------------
     |
     | Set the list of available SMS operation source models.
     | Command: $php artisan config:add-sms-source
     |
     | Example :
     | 'sourceables' => [
     |    Target type code => [
     |        'status' => Enabled state true or false,
     |        'model' => Unique source model class
     |    ],
     | ]
     */

    'sourceables' => [
        'system' => [
            'status' => true,
            'model' => App\Entities\Service\Auth::class
        ],
        //:end-source-generating:
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Notification For Router
    |--------------------------------------------------------------------------
    |
    | Usage: App\Libraries\Instances\Router\Sms class
    | Set the SMS default class.
    |
    | Example :
    | 'default' => SMS telecomer type code
    |
    */
    
    'default' => env('SMS_ROUTER_DEFAULT', ''),

    /*
    |--------------------------------------------------------------------------
    | Route Notifications For Router
    |--------------------------------------------------------------------------
    |
    | Usage: App\Libraries\Instances\Router\Sms class
    | Set the list of available SMS routing items.
    |
    | Example :
    | 'routes' =>
    |    Phone area code => SMS telecomer type code,
    | ]
    |
    | Phone area code eg: +886
    |
    */

    'routes' => [
        //
    ]
];