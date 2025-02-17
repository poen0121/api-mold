<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Subject
    |--------------------------------------------------------------------------
    |
    | Set the subject code relative name list.
    |
    | Example :
    | 'subject' => [
    |   Letter subject code => Letter subject name,
    | ]
    */

    'subject' => [
        'AUTH_LOGIN_LETTER' => 'Authorization Login Letter',
        'AUTH_VERIFY_LETTER' => 'Authorization Verify Letter',
    ],

    /*
    |--------------------------------------------------------------------------
    | Blade Template
    |--------------------------------------------------------------------------
    |
    | Set the relative replacement list of the blade template content.
    |
    | Example :
    | 'blade' => [
    |   Letter subject code => [ 
    |     Replacement object code => Replace content,
    |    ],
    | ]
    */

    'blade' => [
        'AUTH_LOGIN_LETTER' => [
            'TITLE' => 'Notification',
            'BUTTON_NAME' => 'Login Service',
            'TOP_CONTENT' => 'This is an authorized login service letter!',
            'BOTTOM_CONTENT' => 'Please click the login button to enter the service within :ttl minutes.',
        ],
        'AUTH_VERIFY_LETTER' => [
            'TITLE' => 'Notification',
            'TOP_CONTENT' => 'This is an authorized verification code letter!',
            'BODY_CONTENT' => 'Verification Code : :code',
            'BOTTOM_CONTENT' => 'Please use the verification code within :ttl minutes and enter it in the requested service.',
        ],
    ],
];
