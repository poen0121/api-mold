<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Type Name
    |--------------------------------------------------------------------------
    |
    | Set the type code relative name list.
    |
    | Example :
    | 'names' => [
    |   Type code => name,
    | ]
    */

    'names' => [
        'default' => 'Default',
        'model' => 'Model',
        'access' => 'Access',
        'auth' => 'Auth',
        'login' => 'Login',
        'logout' => 'Logout',
        'revoke' => 'Revoke',
    ],

    /*
    |--------------------------------------------------------------------------
    | Description
    |--------------------------------------------------------------------------
    |
    | Set the description relative content list.
    |
    | Example :
    | 'descriptions' => [
    |   Content type description => Display content,
    | ]
    */

    'descriptions' => [
        'created' => 'Created',
        'updated' => 'Updated',
        'deleted' => 'Deleted',
        'auth' => 'Login Client',
        'login' => 'Login User',
        'logout' => 'Logout User',
        'revoke' => 'Revoke Token',
    ],

    /*
    |--------------------------------------------------------------------------
    | Subject Type
    |--------------------------------------------------------------------------
    |
    | Set the subject type class relative name list.
    |
    | Example :
    | 'subject_types' => [
    |   Subject type class => Type name,
    | ]
    */

    'subject_types' => [
        App\Entities\Service\Auth::class => 'Client',
        App\Entities\System\Parameter::class => 'System Parameter',
    ],

    /*
    |--------------------------------------------------------------------------
    | Causer Type
    |--------------------------------------------------------------------------
    |
    | Set the causer type class relative name list.
    |
    | Example :
    | 'causer_types' => [
    |   Causer type class => Type name,
    | ]
    */

    'causer_types' => [
        App\Entities\Service\Auth::class => 'Client',
    ],

];