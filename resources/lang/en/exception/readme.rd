Documentation PHP:

 >> Information

     Title		: Exception Information Conversion
     Revision	: 1.0.0
     Notes		: The HTTP status code for the exception object class defaults to 500.

     Revision History:
     When			Create		When		Edit		Description
     ---------------------------------------------------------------------------
     10-08-2018		Poen		02-05-2020	Poen		Code maintenance.
     ---------------------------------------------------------------------------

 >> Main File

    (app/Exceptions/Handler.php) :
    About render an exception into an HTTP response.

 >> About

    Exception information conversion language lines.

    file > (config/exception.php) :
    The exception converter to display error code config.

 >> Execution Mode

    Priority retrieval of exception object class converter customizations or default item,
    if there is no pointing to the basic exception\converter.php custom project or default item.

 >> Artisan Commands

    Create a language file.
    $php artisan make:ex-converter <name>

    Indicates the source of the ExceptionCode object.
    $php artisan make:ex-converter --rely <name>

    Code is not restricted by exception configuration control.
    $php artisan make:ex-converter --unconf <name>

 >> Learn

    Step 1 :
    Create a converter language file for the exception object class.

    $php artisan make:ex-converter Illuminate\Validation\ValidationException

    Example : Illuminate\Validation\ValidationException object class

    File : resources/lang/ language dir /exception/Illuminate/Validation/ValidationException/converter.php

    Step 2 :
    Edit exception code error.

    File : config / exception.php

    Example :
    --------------------------------------------------------------------------
    return [
        Illuminate\Validation\ValidationException::class => [
            0 => 0,
        ],
        //:end-generating:
    ];

    Step 3 :
    Define the converter language content.

    Example File :
    --------------------------------------------------------------------------
    <?php
    return [
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
        |

        'default' => [
            'code' => '0',
            'status' => '500',
            'message' => 'Something error happens.'
        ],

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
        |

        'customize' => [
            500 => [
                0 => [
                    'code' => '0',
                    'status' => '400',
                    'message' => 'The given data is invalid.'
                ],
            ],
        ]
    ];
    --------------------------------------------------------------------------
 