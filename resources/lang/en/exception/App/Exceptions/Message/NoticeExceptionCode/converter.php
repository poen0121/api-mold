<?php
use App\Exceptions\Message\NoticeExceptionCode as ExceptionCode;

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
            ExceptionCode::DISALLOWED_SEND_OBJECT => [
                'code' => (string) ExceptionCode::DISALLOWED_SEND_OBJECT,
                'status' => '403',
                'message' => 'Disallowed send object.'
            ],
            ExceptionCode::INVALID_SENDER => [
                'code' => (string) ExceptionCode::INVALID_SENDER,
                'status' => '403',
                'message' => 'Invalid sender.'
            ],       
            ExceptionCode::INVALID_NOTIFICATION_ID => [
                'code' => (string) ExceptionCode::INVALID_NOTIFICATION_ID,
                'status' => '400',
                'message' => 'Invalid notification id in \'%id%\'.'
            ],
        ]
    ]
];
