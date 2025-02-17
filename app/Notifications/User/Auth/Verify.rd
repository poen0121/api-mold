Documentation PHP:

 >> Information

    Title		: Notifications-Mail User Auth Verify Code
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    02-13-2022		Poen		02-13-2022	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/App/Notifications/User/Auth/Verify.php) :
    The functional base class.

    file > (config/auth.php) :
    Auth guards configuration.

    Node : 

    You can use the getVerifyCode function to get the email verification code of the sent email.
    
    You can use the forgetVerifyCode function to forget the email verification code of the sent email.

    Default mail template : notices.mail.user.auth.verify
    You can use a custom mail template through the assignBlade function.

    file > (resources/lang/ language dir /mail.php) :
    Edit language file of the subject code 'AUTH_VERIFY_LETTER'.

    Entities need to use Notifiable trait.
    use Illuminate\Notifications\Notifiable;

 >> Base Usage

    Step 1 :
    Send a letter to the user to authorize the verification code.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Auth\Verify;

    $user = Auth::find(1);

    $user->notify(new Verify());

    Step 2 :
    Send a letter to the user to authorize the verification code and use a custom mail template.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Auth\Verify;

    $user = Auth::find(1);

    $user->notify((new Verify())->assignBlade('notices.mail.user.auth.verify'));

    Step 3 :
    Send a letter to the user to authorize the verification code and specify mail.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Auth\Verify;

    $user = Auth::find(1);

    $user->notify((new Verify())->assignMail('admin@example.com'));

    Step 4 :
    Get the email verification code of the sent email.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Auth\Verify;

    $user = Auth::find(1);

    $user->notify(new Verify());

    $storage = (new Verify())->getVerifyCode($user);

    Step 5 :
    Get the email verification code of the sent email for the specified email.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Auth\Verify;

    $user = Auth::find(1);

    $user->notify((new Verify())->assignMail('admin@example.com'));

    $storage = (new Verify())->getVerifyCode($user, 'admin@example.com');

    Step 6 :
    Forget the email verification code of the sent email.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Auth\Verify;

    $user = Auth::find(1);

    $user->notify(new Verify());

    $storage = (new Verify())->getVerifyCode($user);

    (new Verify())->forgetVerifyCode($user);

    Step 7 :
    Forget the email verification code of the sent email for the specified email.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Auth\Verify;

    $user = Auth::find(1);

    $user->notify((new Verify())->assignMail('admin@example.com'));

    $storage = (new Verify())->getVerifyCode($user, 'admin@example.com');

    (new Verify())->forgetVerifyCode($user, 'admin@example.com');
