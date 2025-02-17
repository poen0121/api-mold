Documentation PHP:

 >> Information

    Title		: Janitor
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    11-23-2020		Poen		12-04-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Router/Janitor.php) :
    The functional base class.

    file > (config/janitor.php) :
    The janitor about config.

    file > (resources/lang/ language dir /janitor.php) :
    Edit language file.

    In addition to the basic data format in the configuration file, you can also expand the data array.

 >> Artisan Commands

    Add a new guest class.
    $php artisan config:add-janitor-guest

 >> Learn

    Usage 1 :
    Get a list of all guests class.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Router\Janitor;

    $item = Janitor::getGuests();

    Usage 2 :
    Get a specific list of belonging guests class.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Router\Janitor;

    $item = Janitor::getGuests('normal');

    Usage 3 :
    Check the guest class return the guest type code.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Router\Janitor;
    use App\Entities\Service\Auth;

    $type = Janitor::getGuestType(Auth::class);

    Usage 4 :
    Check the guest type return the guest data array.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Router\Janitor;
    use App\Entities\Service\Auth;

    $type = Janitor::getGuestType(Auth::class);

    $data = Janitor::getGuestData($type);

    Usage 5 :
    Check the guest type return the category to which the guest belongs.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Router\Janitor;
    use App\Entities\Service\Auth;

    $type = Janitor::getGuestType(Auth::class);

    $belong = Janitor::getGuestBelong($type);

    Usage 6 :
    Check the guest type return the guest type name.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Router\Janitor;
    use App\Entities\Service\Auth;

    $type = Janitor::getGuestType(Auth::class);

    $name = Janitor::getGuestName($type);

    Usage 7 :
    Check the guest type return the guest class name.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Router\Janitor;
    use App\Entities\Service\Auth;

    $type = Janitor::getGuestType(Auth::class);

    $class = Janitor::getGuestClass($type);

    Usage 8 :
    Check all classes allowed by the janitor.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Router\Janitor;
    use App\Entities\Service\Auth;

    $status = Janitor::isAllowed(Auth::class);

    Usage 9 :
    Verify specific belonging and check the classes allowed by the janitor.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Router\Janitor;
    use App\Entities\Service\Auth;

    $status = Janitor::isAllowed(Auth::class, 'normal');
