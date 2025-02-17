<?php
use App\Exceptions\System\CellExceptionCode as ExceptionCode;
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
        'code' => (string) ExceptionCode::NORMAL,
        'status' => '500',
        'message' => 'Something error happens.'
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
            ExceptionCode::NORMAL => [
                'code' => (string) ExceptionCode::NORMAL,
                'status' => '500',
                'message' => 'Something error happens.'
            ], 
            ExceptionCode::VALIDATION => [
                'code' => (string) ExceptionCode::VALIDATION,
                'status' => '500',
                'message' => 'Component execution failed.'
            ],
            ExceptionCode::FUNCTION_UNIMPLEMENTED => [
                'code' => (string) ExceptionCode::FUNCTION_UNIMPLEMENTED,
                'status' => '500',
                'message' => 'Component function unimplemented.'
            ],
            ExceptionCode::DATA_FORMAT_ERROR => [
                'code' => (string) ExceptionCode::DATA_FORMAT_ERROR,
                'status' => '500',
                'message' => 'Component response data format is incorrect.'
            ],
        ]
    ]
];
