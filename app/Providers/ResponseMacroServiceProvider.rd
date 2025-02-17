Documentation PHP:

 >> Information

    Title		: Reform Response
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    10-22-2018		Poen		03-19-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Providers/ResponseMacroServiceProvider.php) :
    The format response base function.

 >> Artisan Commands

    Create a object file.
    $php artisan make:response <name>

 >> Learn

    Step 1 :
    Create response object class.

    $php artisan make:response Service\Auth

    Example : App\Http\Responses\Service\AuthResponse object class

    File : app/Http/Responses/Service/AuthResponse.php

    Step 2 :
    Edit response to successful data conversion.

    Example :
    --------------------------------------------------------------------------
    public function transform(array &$data)
    {
         // Adjust the response format data label
    }

    Step 3 :
    Call response success.

    Example :
    --------------------------------------------------------------------------
    use App\Http\Responses\Service\AuthResponse;

    app(AuthResponse::class)->success([]);
