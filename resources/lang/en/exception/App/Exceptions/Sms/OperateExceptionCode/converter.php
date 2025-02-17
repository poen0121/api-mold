<?php
use App\Exceptions\Sms\OperateExceptionCode as ExceptionCode;
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
            ExceptionCode::UNAUTHORIZED_OPERATION => [
                'code' => (string) ExceptionCode::UNAUTHORIZED_OPERATION,
                'status' => '403',
                'message' => 'Unauthorized operation.'
            ],
            ExceptionCode::SMS_SEND_FAILED => [
                'code' => (string) ExceptionCode::SMS_SEND_FAILED,
                'status' => '500',
                'message' => 'SMS sending failed.'
            ],
            ExceptionCode::MISS_DATA => [
                'code' => (string) ExceptionCode::MISS_DATA,
                'status' => '500',
                'message' => 'Missing data.'
            ],
            ExceptionCode::UNKNOWN_RESPONSE_TRAIT => [
                'code' => (string) ExceptionCode::UNKNOWN_RESPONSE_TRAIT,
                'status' => '500',
                'message' => 'Unknown response trait.'
            ],
            ExceptionCode::DATA_FORMAT_ERROR => [
                'code' => (string) ExceptionCode::DATA_FORMAT_ERROR,
                'status' => '500',
                'message' => 'Response data format is incorrect.'
            ],
            ExceptionCode::FUNCTION_UNIMPLEMENTED => [
                'code' => (string) ExceptionCode::FUNCTION_UNIMPLEMENTED,
                'status' => '500',
                'message' => 'Function unimplemented.'
            ],
        ]
    ]
];