Documentation PHP:

 >> Information

    Title		: Token Auth
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    12-16-2019		Poen		01-11-2021	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Token/Auth.php) :
    The functional base class.

    file > (config/auth.php) :
    Auth guards configuration.

    file > (config/ban.php) :
    Guards ban configuration.

    file > (config/jwt.php) :
    Token auth configuration.

    You can configure data cache storage store.

 >> Artisan Commands

    Add the new unique_auth column to the database auth table and the auth eloquent fillable additional attribute 'unique_auth' to enable this feature.
    $php artisan mg-column:append-unique-auth

 >> Aliases

    use TokenAuth;

  >> Base Usage

    Usage 1 :
    Get the auth user model for the authentication token.

    Example :
    --------------------------------------------------------------------------
    use TokenAuth;

    TokenAuth::getUser();

    Usage 2 :
    Verify the auth user model for the authentication token.

    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use TokenAuth;

    TokenAuth::getUser(Auth::class);

    Usage 3 :
    Login the user auth model and return access token.
    Remember to use Middleware 'token.login' .
    Example :
    --------------------------------------------------------------------------
    use App\Entities\Member\Auth;
    use TokenAuth;

    $user = Auth::find(1);
    TokenAuth::loginUser($user);

    Usage 4 :
    Check if authentication guard applies to client services.

    Example :
    --------------------------------------------------------------------------
    use TokenAuth;

    TokenAuth::isClientGuard();
