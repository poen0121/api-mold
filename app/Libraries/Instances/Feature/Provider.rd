Documentation PHP:

 >> Information

    Title		: Feature Provider
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    02-05-2020		Poen		02-10-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Feature/Provider.php) :
    The functional base class.

    Feature configuration information.

    file > (config/feature.php) :
    The feature provider about config.

    Available fields :
     Column string : class
     Column string : code
     Column string : description
     Column array : arguments
     Column array : responses

 >> Artisan Commands

    Register feature class as a provider to the profile.
    $php artisan config:add-feature-provider <name>

 >> Learn

    Usage 1 :
    Get the feature document for the provider.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Feature\Provider;

    $columns = [
        'code',
        'description',
        'arguments',
        'responses'
    ];

    Provider::getDoc(Provider::getProvider(), $columns);

    Usage 2 :
    Get the feature document for the release.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Feature\Provider;

    use App\Entities\Service\Client;

    $columns = [
        'code',
        'description',
        'arguments',
        'responses'
    ];

    Provider::getDoc(Provider::getRelease(Client::class), $columns);

