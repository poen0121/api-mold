Documentation PHP:

 >> Information

    Title		: Bulletins
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    09-09-2020		Poen		03-29-2022	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Notice/Bulletin.php) :
    The functional base class.

    file > (config/notice.php) :
    The driver about config.

    file > (resources/lang/ language dir /notice.php) :
    The language file.

 >> Note

    User Model :
    Entities need to use Notifiable trait.
    use Illuminate\Notifications\Notifiable;
    The entity must be of Auth Guard type.

 >> Learn

    Usage 1 :
    Build a bulletin notification message.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Notice\Bulletin;

    Bulletin::build('member', 'Test', ['message' => 'Test message.'], '2020-10-01 08:00:00', '2020-10-10 08:00:00', 'system',  true);

    Usage 2 :
    Capture the bulletin notifications.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Notice\Bulletin;
    use App\App\Entities\Member\Auth;

    $user = Auth::find(1);

    Bulletin::capture($user);

