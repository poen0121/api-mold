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
        'message' => 'The given encode data is invalid.'
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
                'message' => 'The given encode data is invalid.'
            ],
        ]
    ]
];
