<?php
use App\Exceptions\System\AcceptExceptionCode as ExceptionCode;

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
            ExceptionCode::UNSUPPORTED_MEDIA_TYPE => [
                'code' => (string) ExceptionCode::UNSUPPORTED_MEDIA_TYPE,
                'status' => '415',
                'message' => 'Unsupported media type.'
            ],
            ExceptionCode::UNACCEPTABLE_RESPONSE_TYPE => [
                'code' => (string) ExceptionCode::UNACCEPTABLE_RESPONSE_TYPE,
                'status' => '406',
                'message' => 'Unacceptable response type.'
            ],
            ExceptionCode::TIMEZONE_BAD => [
                'code' => (string) ExceptionCode::TIMEZONE_BAD,
                'status' => '406',
                'message' => 'Unacceptable timezone.'
            ],
            ExceptionCode::BAN_TOKEN => [
                'code' => (string) ExceptionCode::BAN_TOKEN,
                'status' => '406',
                'message' => 'Unacceptable authorization.'
            ],
        ]
    ]
];