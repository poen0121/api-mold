Documentation PHP:

 >> Information

    Title		: SMS Channel
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    11-17-2020		Poen		02-02-2021	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Router/Sms.php) :
    The SMS notifications router functional base class.

    file > (config/sms.php) :
    The SMS notification about config.

    file > (resources/lang/ language dir /sms.php) :
    Edit language file.

 >> Note

    The area code uses the ISO_3166-1 alpha2 area code.

    Reference : https://zh.wikipedia.org/wiki/ISO_3166-1

 >> Artisan Commands
   
    Generate SMS notification component.
    $php artisan make:notification-sms

 >> Notification Return Format

    Return array format.
    [bool] success : Success status true or false
    [array] data : Data array

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

    Usage 3 :
    Send SMS notification.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Router\Sms;
    use Phone;
    use TokenAuth;
    use Lang;

    $phone = '+886930684635';
    if ($phone = Phone::parse($phone)->getFormat()) {
        if (($sms = Sms::route($phone)) && TokenAuth::getClient()) {
            // Use SMS subject lang dict
            $subject = Lang::dict('sms', 'subject.' . 'authcode', 'Authcode');
            // Use SMS message lang dict
            $message = Lang::dict('sms', 'message.' . 'authcode', 'Authcode : :code', ['code' => 123456]);
            // Send SMS
            TokenAuth::getClient()->notify(new $sms($phone, $subject, $message));
        }
    }
