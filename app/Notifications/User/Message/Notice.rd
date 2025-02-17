Documentation PHP:

 >> Information

    Title		: Notifications-Database User Message Notice
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    06-18-2020		Poen		01-19-2021	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/App/Notifications/User/Message/Notice.php) :
    The functional base class.

    file > (resources/lang/ language dir /notice.php) :
    The language file.

    Store :

    Database table name is `notifications` .

    Node : 

    Entities need to use Notifiable trait.
    use Illuminate\Notifications\Notifiable;
    

 >> Base Usage

    Step 1 :
    Send a message to the user.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Message\Notice;

    $user = Auth::find(1);

    $user->notify(new Notice('Test', ['message' => 'Test messgae.']));

    Step 2 :
    Send a message to the user and add type code.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Message\Notice;

    $user = Auth::find(1);

    $user->notify(new Notice('Test', ['message' => 'Test messgae.'], 'test'));
