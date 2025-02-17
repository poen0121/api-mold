Documentation PHP:

 >> Information

    Title		: Exception Code
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    10-22-2018		Poen		03-16-2022	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Abstracts/Base/ExceptionCode.php) :
    The exception code throws the message integrator base class.

    file > (config/exception.php) :
    The exception converter to display error code config.

    Note: The Http status code default to 500 and can also be changed by calling.

 >> Artisan Commands

    Create a object file.
    $php artisan make:ex-code <name>

    Cancel creating converter language file.
    $php artisan make:ex-code --noconverter <name>

 >> Learn

    Step 1 :
    Create ExceptionCode object class.

    $php artisan make:ex-code Service\Auth

    Example : App\Exceptions\Service\AuthExceptionCode object class

    File : app/Exceptions/Service/AuthExceptionCode.php

    Step 2 :
    Edit exception code error.

    File : config / exception.php

    Example :
    --------------------------------------------------------------------------
    return [
        App\Exceptions\Service\AuthExceptionCode::class => [
            0 => 0,
        ],
        //:end-generating:
    ];

    Step 3 :
    Edit language file.

    File : resources/lang/ language dir /exception/App/Exceptions/Service/AuthExceptionCode/converter.php

    Example :
    --------------------------------------------------------------------------
    use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;

    return [
        'default' => [
            'code' => (string) ExceptionCode::NORMAL,
            'status' => 500,
            'message' => 'Something error happens.'
        ],
        'customize' => [
            500 => [
                ExceptionCode::NORMAL => [
                    'code' => (string) ExceptionCode::NORMAL,
                    'status' => 500,
                    'message' => 'Something error happens.'
                ],
            ]
        ]
    ];

    Step 4 :
    Call throw exception.

    Example :
    --------------------------------------------------------------------------
    use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;

    throw new ExceptionCode(ExceptionCode::AUTH_FAIL);

    Step 5 :
    Call throw exception and replace converter message tags.

    Example :
    --------------------------------------------------------------------------
    use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;

    throw new ExceptionCode(ExceptionCode::AUTH_FAIL, ['%user%' => '1294583', '%type%' => 'admin']);

    Step 6 :
    Call throw exception and replace source message tags.

    Example :
    --------------------------------------------------------------------------
    use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;

    throw new ExceptionCode(ExceptionCode::AUTH_FAIL, [], ['%user%' => '1294583', '%type%' => 'admin']);

    Step 7 :
    Call throw exception and custom description.

    Example :
    --------------------------------------------------------------------------
    use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;
    use Illuminate\Support\MessageBag;

    $description = app(MessageBag::class);
    $description->add('about', 'This a error description.');

    throw new ExceptionCode(ExceptionCode::AUTH_FAIL, [], [], $description);

    Step 8 :
    Change the default value of http status code.

    Example :
    --------------------------------------------------------------------------
    use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;

    throw new ExceptionCode(ExceptionCode::AUTH_FAIL, [], [], null, 403);

    Step 9 :
    Get the dislpay code.

    Example :
    --------------------------------------------------------------------------
    use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;

    try {
        throw new ExceptionCode(ExceptionCode::AUTH_FAIL);
    } catch (\Throwable $th) {
        return $th->getDisplayCode();
    }

    Step 10 :
    Get the dislpay http status code.

    Example :
    --------------------------------------------------------------------------
    use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;

    try {
        throw new ExceptionCode(ExceptionCode::AUTH_FAIL);
    } catch (\Throwable $th) {
        return $th->getDisplayStatusCode();
    }

    Step 11 :
    Get the dislpay message.

    Example :
    --------------------------------------------------------------------------
    use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;

    try {
        throw new ExceptionCode(ExceptionCode::AUTH_FAIL);
    } catch (\Throwable $th) {
        return $th->getDisplayMessage();
    }
