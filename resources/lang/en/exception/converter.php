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
        100 => [
            0 => [
                'code' => '0',
                'status' => '100',
                'message' => 'Continue.'
            ],
        ],
        101 => [
            0 => [
                'code' => '0',
                'status' => '101',
                'message' => 'Switching protocols.'
            ],
        ],
        102 => [
            0 => [
                'code' => '0',
                'status' => '102',
                'message' => 'Processing.'
            ],
        ], // RFC2518
        200 => [
            0 => [
                'code' => '0',
                'status' => '200',
                'message' => 'OK.'
            ],
        ],
        201 => [
            0 => [
                'code' => '0',
                'status' => '201',
                'message' => 'Created.'
            ],
        ],
        202 => [
            0 => [
                'code' => '0',
                'status' => '202',
                'message' => 'Accepted.'
            ],
        ],
        203 => [
            0 => [
                'code' => '0',
                'status' => '203',
                'message' => 'Non-authoritative information.'
            ],
        ],
        204 => [
            0 => [
                'code' => '0',
                'status' => '204',
                'message' => 'No content.'
            ],
        ],
        205 => [
            0 => [
                'code' => '0',
                'status' => '205',
                'message' => 'Reset content.'
            ],
        ],
        206 => [
            0 => [
                'code' => '0',
                'status' => '206',
                'message' => 'Partial content.'
            ],
        ],
        207 => [
            0 => [
                'code' => '0',
                'status' => '207',
                'message' => 'Multi-status.'
            ],
        ], // RFC4918
        208 => [
            0 => [
                'code' => '0',
                'status' => '208',
                'message' => 'Already reported.'
            ],
        ], // RFC5842
        226 => [
            0 => [
                'code' => '0',
                'status' => '226',
                'message' => 'IM used.'
            ],
        ], // RFC3229
        300 => [
            0 => [
                'code' => '0',
                'status' => '300',
                'message' => 'Multiple choices.'
            ],
        ],
        301 => [
            0 => [
                'code' => '0',
                'status' => '301',
                'message' => 'Moved permanently.'
            ],
        ],
        302 => [
            0 => [
                'code' => '0',
                'status' => '302',
                'message' => 'Found'
            ],
        ],
        303 => [
            0 => [
                'code' => '0',
                'status' => '303',
                'message' => 'See other.'
            ],
        ],
        304 => [
            0 => [
                'code' => '0',
                'status' => '304',
                'message' => 'Not modified.'
            ],
        ],
        305 => [
            0 => [
                'code' => '0',
                'status' => '305',
                'message' => 'Use proxy.'
            ],
        ],
        307 => [
            0 => [
                'code' => '0',
                'status' => '307',
                'message' => 'Temporary redirect.'
            ],
        ],
        308 => [
            0 => [
                'code' => '0',
                'status' => '308',
                'message' => 'Permanent redirect.'
            ],
        ], // RFC7238
        400 => [
            0 => [
                'code' => '0',
                'status' => '400',
                'message' => 'Bad request.'
            ],
        ],
        401 => [
            0 => [
                'code' => '0',
                'status' => '401',
                'message' => 'Unauthorized.'
            ],
        ],
        402 => [
            0 => [
                'code' => '0',
                'status' => '402',
                'message' => 'Payment required.'
            ],
        ],
        403 => [
            0 => [
                'code' => '0',
                'status' => '403',
                'message' => 'Forbidden.'
            ],
        ],
        404 => [
            0 => [
                'code' => '0',
                'status' => '404',
                'message' => 'Not found.'
            ],
        ],
        405 => [
            0 => [
                'code' => '0',
                'status' => '405',
                'message' => 'Method not allowed.'
            ],
        ],
        406 => [
            0 => [
                'code' => '0',
                'status' => '406',
                'message' => 'Not acceptable.'
            ],
        ],
        407 => [
            0 => [
                'code' => '0',
                'status' => '407',
                'message' => 'Proxy authentication required.'
            ],
        ],
        408 => [
            0 => [
                'code' => '0',
                'status' => '408',
                'message' => 'Request timeout.'
            ],
        ],
        409 => [
            0 => [
                'code' => '0',
                'status' => '409',
                'message' => 'Conflict.'
            ],
        ],
        410 => [
            0 => [
                'code' => '0',
                'status' => '410',
                'message' => 'Gone.'
            ],
        ],
        411 => [
            0 => [
                'code' => '0',
                'status' => '411',
                'message' => 'Length required.'
            ],
        ],
        412 => [
            0 => [
                'code' => '0',
                'status' => '412',
                'message' => 'Precondition failed.'
            ],
        ],
        413 => [
            0 => [
                'code' => '0',
                'status' => '413',
                'message' => 'Payload too large.'
            ],
        ],
        414 => [
            0 => [
                'code' => '0',
                'status' => '414',
                'message' => 'URI too long.'
            ],
        ],
        415 => [
            0 => [
                'code' => '0',
                'status' => '415',
                'message' => 'Unsupported media type.'
            ],
        ],
        416 => [
            0 => [
                'code' => '0',
                'status' => '416',
                'message' => 'Range not satisfiable.'
            ],
        ],
        417 => [
            0 => [
                'code' => '0',
                'status' => '417',
                'message' => 'Expectation failed.'
            ],
        ],
        418 => [
            0 => [
                'code' => '0',
                'status' => '418',
                'message' => 'I\'m a teapot.'
            ],
        ], // RFC2324
        421 => [
            0 => [
                'code' => '0',
                'status' => '421',
                'message' => 'Misdirected request.'
            ],
        ], // RFC7540
        422 => [
            0 => [
                'code' => '0',
                'status' => '422',
                'message' => 'Unprocessable entity.'
            ],
        ], // RFC4918
        423 => [
            0 => [
                'code' => '0',
                'status' => '423',
                'message' => 'Locked.'
            ],
        ], // RFC4918
        424 => [
            0 => [
                'code' => '0',
                'status' => '424',
                'message' => 'Failed dependency.'
            ],
        ], // RFC4918
        425 => [
            0 => [
                'code' => '0',
                'status' => '425',
                'message' => 'Reserved for WebDAV advanced collections expired proposal.'
            ],
        ], // RFC2817
        426 => [
            0 => [
                'code' => '0',
                'status' => '426',
                'message' => 'Upgrade required.'
            ],
        ], // RFC2817
        428 => [
            0 => [
                'code' => '0',
                'status' => '428',
                'message' => 'Precondition required.'
            ],
        ], // RFC6585
        429 => [
            0 => [
                'code' => '0',
                'status' => '429',
                'message' => 'Too many requests.'
            ],
        ], // RFC6585
        431 => [
            0 => [
                'code' => '0',
                'status' => '431',
                'message' => 'Request header fields too large.'
            ],
        ], // RFC6585
        451 => [
            0 => [
                'code' => '0',
                'status' => '451',
                'message' => 'Unavailable for legal reasons.'
            ],
        ], // RFC7725
        500 => [
            0 => [
                'code' => '0',
                'status' => '500',
                'message' => 'Internal server error.'
            ],
        ],
        501 => [
            0 => [
                'code' => '0',
                'status' => '501',
                'message' => 'Not implemented.'
            ],
        ],
        502 => [
            0 => [
                'code' => '0',
                'status' => '502',
                'message' => 'Bad gateway.'
            ],
        ],
        503 => [
            0 => [
                'code' => '0',
                'status' => '503',
                'message' => 'Service unavailable.'
            ],
        ],
        504 => [
            0 => [
                'code' => '0',
                'status' => '504',
                'message' => 'Gateway timeout.'
            ],
        ],
        505 => [
            0 => [
                'code' => '0',
                'status' => '505',
                'message' => 'HTTP version not supported.'
            ],
        ],
        506 => [
            0 => [
                'code' => '0',
                'status' => '506',
                'message' => 'Variant also negotiates.'
            ],
        ], // RFC2295
        507 => [
            0 => [
                'code' => '0',
                'status' => '507',
                'message' => 'Insufficient storage.'
            ],
        ], // RFC4918
        508 => [
            0 => [
                'code' => '0',
                'status' => '508',
                'message' => 'Loop detected.'
            ],
        ], // RFC5842
        510 => [
            0 => [
                'code' => '0',
                'status' => '510',
                'message' => 'Not extended.'
            ],
        ], // RFC2774
        511 => [
            0 => [
                'code' => '0',
                'status' => '511',
                'message' => 'Network authentication required.'
            ],
        ], // RFC6585
    ]
];
