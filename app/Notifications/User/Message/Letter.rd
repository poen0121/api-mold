Documentation PHP:

 >> Information

    Title		: Notifications-Database User Message Letter
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    01-09-2021		Poen		02-14-2022	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/App/Notifications/User/Message/Letter.php) :
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
    Send a letter message to the user.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Admin\Auth as Admin;
    use App\Entities\Member\Auth as Member;
    use App\Notifications\User\Message\Letter;

    $sender = Admin::find(1);
    $user = Member::find(1);

    $user->notify(new Letter($sender, 'Test', 'Test messgae.');

    Step 2 :
    Send a letter message and additional notes to the user.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Admin\Auth as Admin;
    use App\Entities\Member\Auth as Member;
    use App\Notifications\User\Message\Letter;

    $sender = Admin::find(1);
    $user = Member::find(1);

    $user->notify(new Letter($sender, 'Test', 'Test messgae.', ['custom note']);
