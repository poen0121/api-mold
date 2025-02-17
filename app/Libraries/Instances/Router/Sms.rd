Documentation PHP:

 >> Information

    Title		: SMS Notification Router
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    11-17-2020		Poen		11-23-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Router/Sms.php) :
    The functional base class.

    file > (config/sms.php) :
    The SMS notification about config.

    file > (resources/lang/ language dir /sms.php) :
    Edit language file.

 >> Note

    The area code uses the ISO_3166-1 alpha2 area code.

    Reference : https://zh.wikipedia.org/wiki/ISO_3166-1

 >> Learn

    Usage 1 :
    Parse phone baseinfo.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Router\Sms;

    $notification = Sms::route('+886930684635');

    Usage 2 :
    If there is no international code, route can be parsed from area express.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Router\Sms;

    $notification = Sms::route('0930684635', ['TW']);
