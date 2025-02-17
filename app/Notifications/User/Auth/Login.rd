Documentation PHP:

 >> Information

    Title		: Notifications-Mail User Auth Login
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    05-30-2020		Poen		01-15-2021	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/App/Notifications/User/Auth/Login.php) :
    The functional base class.

    file > (config/auth.php) :
    Auth guards configuration.
    
    Config : 
    Set the auth link survival time by auth.muts_ttl .

    Node : 
    The function needs to set redirect url.

    Default mail template : notices.mail.user.auth.login
    You can use a custom mail template through the assignBlade function.

    file > (resources/lang/ language dir /mail.php) :
    Edit language file of the subject code 'AUTH_LOGIN_LETTER'.

    Entities need to use Notifiable trait.
    use Illuminate\Notifications\Notifiable;

 >> Base Usage

    Step 1 :
    Send a letter to the user to authorize the login link and default uri query variable name is auth.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Auth\Login;

    $user = Auth::find(1);

    $user->notify(new Login('http://example.com'));

    Step 2 :
    Send a letter to the user to authorize the login link and specify uri query variable name.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Auth\Login;

    $user = Auth::find(1);

    $user->notify((new Login('http://example.com'))->assignVar('token'));

    Step 3 :
    Send a letter to the user to authorize the login link and use a custom mail template.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Auth\Login;

    $user = Auth::find(1);

    $user->notify((new Login('http://example.com'))->assignBlade('notices.mail.user.auth.login'));

    Step 4 :
    Send a letter to the user to authorize the login link and specify mail.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use App\Notifications\User\Auth\Login;

    $user = Auth::find(1);

    $user->notify((new Login('http://example.com'))->assignMail('admin@example.com'));
