Documentation PHP:

 >> Information

    Title		: API Interface Scope
    Revision	: 1.0.0
    Notes		: Managed APIs interface relies on using middleware token.ban

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    02-10-2020		Poen		02-21-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/System/InterfaceScope.php) :
    The functional base class.

    API interface scope information.

    file > (routes/api.php) :
    The route name config.

    file > (resources/lang/ language dir /ban.php) :
    The interface description content.

    Available fields :
     Column string : code
     Column string : interface
     Column string : description
     Column array : action

 >> Learn

    Usage 1 :
    Get all interfaces.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\InterfaceScope;

    $columns = [
        'code',
        'interface',
        'description'
    ];

    InterfaceScope::all($columns);

    Usage 2 :
    Get the list of managed interfaces.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\InterfaceScope;

    $columns = [
        'code',
        'interface',
        'description'
    ];

    InterfaceScope::managed($columns);

    Usage 3 :
    Get the list of allowed interfaces for ban number.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\InterfaceScope;

    $columns = [
        'code',
        'interface',
        'description'
    ];

    InterfaceScope::allowedByban(0, $columns);

    Usage 4 :
    Get the list of managed interfaces for ban number.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\InterfaceScope;

    $columns = [
        'code',
        'interface',
        'description'
    ];

    InterfaceScope::managedByban(0, $columns);

    Usage 5 :
    Get the list of reserved interfaces.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\InterfaceScope;

    $columns = [
        'code',
        'interface',
        'description'
    ];

    InterfaceScope::reserved($columns);
