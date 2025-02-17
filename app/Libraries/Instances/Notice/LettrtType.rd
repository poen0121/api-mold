Documentation PHP:

 >> Information

    Title		: LetterType
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    01-09-2021		Poen		02-14-2022	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Notice/LetterType.php) :
    The functional base class.

    file > (app/App/Notifications/User/Message/Letter.php) :
    The letter notification base class.

    file > (config/notice.php) :
    The driver about config.

    file > (resources/lang/ language dir /auth.php) :
    The language file.

 >> Note

    User Model :
    Entities need to use Notifiable trait.
    use Illuminate\Notifications\Notifiable;
    The entity must be of Auth Guard type.

 >> Base Usage

    Step 1 :
    Get a list of user types.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Notice\LetterType;

    $types = LetterType::userTypes();

    Step 2 :
    Get a list of user types held by the sender.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Notice\LetterType;
    use App\Entities\Admin\Auth as Admin;

    $sender = Admin::find(1);

    $types = LetterType::heldUserTypes($sender);

