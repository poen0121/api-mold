Documentation PHP:

 >> Information

    Title		: Authority
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    02-28-2020		Poen		02-28-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/System/Authority.php) :
    The functional base class.

    API Authority process.

    file > (config/auth.php) :
    The auth guards config.

    file > (resources/lang/ language dir /auth.php) :
    The guards description content.

 >> Learn

    Usage 1 :
    Get a list of auth guard object types.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\Authority;

    $columns = [
        'class',
        'type',
        'description'
    ];

    Authority::objectTypes($columns);

    Usage 2 :
    Grant the user authority and update authority.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\Authority;
    use App\Entities\Service\Client;

    $item = Client::find(1);

    if ($authority = Authority::grant($item, ['auth.client.index'])) {
        $item->update(['authority' => $authority]);
    }

    Usage 3 :
    Remove the user authority and update authority.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\Authority;
    use App\Entities\Service\Client;

    $item = Client::find(1);

    if ($authority = Authority::remove($item, ['auth.client.index'])) {
        $item->update(['authority' => $authority]);
    }

    Usage 4 :
    Global the user authority and update authority.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\Authority;
    use App\Entities\Service\Client;

    $item = Client::find(1);

    if ($authority = Authority::global($item)) {
      $item->update(['authority' => $authority]);
    }
