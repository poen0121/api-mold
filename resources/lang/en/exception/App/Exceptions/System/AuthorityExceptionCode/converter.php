<?php
use App\Exceptions\System\AuthorityExceptionCode as ExceptionCode;

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
            ExceptionCode::NO_PERMISSION => [
                'code' => (string) ExceptionCode::NO_PERMISSION,
                'status' => '403',
                'message' => 'Unauthorized permission.'
            ],
            ExceptionCode::UNSPECIFIED_AUTHORITY => [
                'code' => (string) ExceptionCode::UNSPECIFIED_AUTHORITY,
                'status' => '400',
                'message' => 'Unspecified authority.'
            ],
            ExceptionCode::INVALID_SNAPSHOT => [
                'code' => (string) ExceptionCode::INVALID_SNAPSHOT,
                'status' => '400',
                'message' => 'Invalid authority snapshot id.'
            ],
            ExceptionCode::INVALID_INTERFACE_CODE => [
                'code' => (string) ExceptionCode::INVALID_INTERFACE_CODE,
                'status' => '400',
                'message' => 'Invalid interface code in \'%code%\'.'
            ],
            ExceptionCode::INOPERABLE_USER => [
                'code' => (string) ExceptionCode::INOPERABLE_USER,
                'status' => '403',
                'message' => 'Inoperable user object.'
            ],
        ]
    ]
];