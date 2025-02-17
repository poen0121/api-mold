<?php
use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;

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
            ExceptionCode::CLIENT_AUTH_FAIL => [
                'code' => (string) ExceptionCode::CLIENT_AUTH_FAIL,
                'status' => '401',
                'message' => 'Unauthorized client credentials.'
            ],
            ExceptionCode::AUTH_FAIL => [
                'code' => (string) ExceptionCode::AUTH_FAIL,
                'status' => '401',
                'message' => 'Unauthorized identity.'
            ],
            ExceptionCode::TOKEN_CREATE_FAIL => [
                'code' => (string) ExceptionCode::TOKEN_CREATE_FAIL,
                'status' => '401',
                'message' => 'Authorization failed.'
            ],
            ExceptionCode::CLIENT_NON_EXIST => [
                'code' => (string) ExceptionCode::CLIENT_NON_EXIST,
                'status' => '401',
                'message' => 'Unauthorized client credentials.'
            ],
            ExceptionCode::SERVICE_REJECTED => [
                'code' => (string) ExceptionCode::SERVICE_REJECTED,
                'status' => '401',
                'message' => 'Unauthorized identity.'
            ],
            ExceptionCode::NO_PERMISSION => [
                'code' => (string) ExceptionCode::NO_PERMISSION,
                'status' => '403',
                'message' => 'Unauthorized permission.'
            ],
            ExceptionCode::USER_AUTH_FAIL => [
                'code' => (string) ExceptionCode::USER_AUTH_FAIL,
                'status' => '401',
                'message' => 'Unauthorized user credentials.'
            ],
            ExceptionCode::TOKEN_OTHER_GUARD_AUTHORIZED => [
                'code' => (string) ExceptionCode::TOKEN_OTHER_GUARD_AUTHORIZED,
                'status' => '401',
                'message' => 'Token has been authorized for other identity.'
            ],
            ExceptionCode::USER_NON_EXIST => [
                'code' => (string) ExceptionCode::USER_NON_EXIST,
                'status' => '401',
                'message' => 'Unauthorized user credentials.'
            ],
            ExceptionCode::SIGNATURE_CREATE_FAIL => [
                'code' => (string) ExceptionCode::SIGNATURE_CREATE_FAIL,
                'status' => '401',
                'message' => 'Authorization failed.'
            ],
            ExceptionCode::SERVICE_EXPIRED => [
                'code' => (string) ExceptionCode::SERVICE_EXPIRED,
                'status' => '401',
                'message' => 'Unauthorized identity.'
            ],
            ExceptionCode::VERIFYCODE_CREATE_FAIL => [
                'code' => (string) ExceptionCode::VERIFYCODE_CREATE_FAIL,
                'status' => '401',
                'message' => 'Authorization failed.'
            ],
            ExceptionCode::OPERATION_DISABLED => [
                'code' => (string) ExceptionCode::OPERATION_DISABLED,
                'status' => '403',
                'message' => 'Operation has been disabled.'
            ],
        ]
    ]
];
