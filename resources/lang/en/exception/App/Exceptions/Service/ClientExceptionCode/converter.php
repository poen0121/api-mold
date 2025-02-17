<?php
use App\Exceptions\Service\ClientExceptionCode as ExceptionCode;

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
            ExceptionCode::BAN_NUMBER_DISABLED => [
                'code' => (string) ExceptionCode::BAN_NUMBER_DISABLED,
                'status' => '403',
                'message' => 'Unauthorized ban number.'
            ],
            ExceptionCode::SERVICE_NAME_EXISTS => [
                'code' => (string) ExceptionCode::SERVICE_NAME_EXISTS,
                'status' => '403',
                'message' => 'Service name is already registered.'
            ],
            ExceptionCode::SERVICE_ID_EXISTS => [
                'code' => (string) ExceptionCode::SERVICE_ID_EXISTS,
                'status' => '507',
                'message' => 'Service creation failed.'
            ],
            ExceptionCode::INOPERABLE_CLIENT => [
                'code' => (string) ExceptionCode::INOPERABLE_CLIENT,
                'status' => '403',
                'message' => 'Inoperable client service object.'
            ],
        ]
    ]
];
