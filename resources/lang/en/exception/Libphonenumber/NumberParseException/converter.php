<?php
return [
    /*
     |--------------------------------------------------------------------------
     | Default exception error message
     |--------------------------------------------------------------------------
     |
     | The default message that responds to an exception error.
     |
     | Example :
     | 'default' => [
     |   'code' => (string) thrown error code,
     |   'status' => (string) thrown status code,
     |   'message' => (string) thrown error message
     | ]
     */

    'default' => [
        'code' => '0',
        'status' => '400',
        'message' => 'Unrecognized phone number.'
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Exception information conversion language lines
    |--------------------------------------------------------------------------
    |
    | The status code is bound to the list of information thrown by the corresponding exception error code conversion.
    |
    | Example :
    |   'customize' => [
    |    (int) source http status code => [
    |           (mixed) source error code => [
    |           'code' => (string) thrown error code,
    |           'status' => (string) thrown status code,
    |           'message' => (string) thrown error message
    |           ],
    |       ],
    |    ]
    */
    
    'customize' => [
        500 => [
            0 => [
                'code' => '0',
                'status' => '400',
                'message' => 'Unrecognized phone number.'
            ],
            1 => [
                'code' => '1',
                'status' => '400',
                'message' => 'Unrecognized phone number.'
            ],
            2 => [
                'code' => '2',
                'status' => '400',
                'message' => 'Unrecognized phone number.'
            ],
            3 => [
                'code' => '3',
                'status' => '400',
                'message' => 'Unrecognized phone number.'
            ],
            4 => [
                'code' => '4',
                'status' => '400',
                'message' => 'Unrecognized phone number.'
            ],
        ]
    ]
];
