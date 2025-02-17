Documentation PHP:

 >> Information

    Title		: JWT
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    12-31-2019		Poen		10-14-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Traits/Entity/Auth/JWT.php) :
    The functional base class.

    According to Tymon\JWTAuth related classes.

 >> Middleware

    token.auth : Verify service authorization
    token.ban : Verify authorization interface authority for auth guards

 >> Learn

    Step 1 :
    In App\Entities Model Class, Use App\Libraries\Traits\Entity\Auth\JWT

    File : app/Entities/Member/Auth.php

    Example :
    --------------------------------------------------------------------------
    namespace App\Entities\Member;

    use Prettus\Repository\Contracts\Transformable;
    use Prettus\Repository\Traits\TransformableTrait;
    use Tymon\JWTAuth\Contracts\JWTSubject;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use App\Libraries\Traits\Entity\Auth\JWT;

    class Auth extends Authenticatable implements Transformable, JWTSubject
    {
        use TransformableTrait;
        use JWT;

    }

    Step 2 :

    Logon the user model return the authentication token.

    File : app/Http/Controllers/Member/AuthController.php

    Example :
    --------------------------------------------------------------------------
    namespace App\Http\Controllers\Member;

    use App\Http\Controllers\Controller;
    use App\Entities\Member\Auth;
    use App\Http\Requests\Member\AuthCreateRequest;
    use App\Http\Responses\Member\AuthCreateResponse;
    use App\Exceptions\Member\AuthExceptionCode as ExceptionCode;
    use App\Exceptions\Service\AuthExceptionCode;
    use TokenAuth;
    use DB;

    class AuthController extends Controller {

        public function logon(AuthCreateRequest $request, AuthCreateResponse $response)
        {
            // Account data
            $credentials = $request->only([
                'account',
                'password'
            ]);
            DB::beginTransaction();
            // Create user model
            $result = Auth::create([
                'account' => $credentials['account'],
                'password' => $credentials['password']
            ]);
            // Get user token and remember to use Middleware 'token.login'
            $token = TokenAuth::loginUser($user);
            if (isset($token)) {
                $source = [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => $user->getTTL() * 60
                ];

                DB::commit();

                return $response->json($source);
            } else {
                throw new AuthExceptionCode(AuthExceptionCode::TOKEN_CREATE_FAIL);
            }
        }
    }

    Step 3 :

    Verify the auth user model for the authentication token.

    File : app/Http/Controllers/Member/AuthController.php

    Example :
    --------------------------------------------------------------------------
    namespace App\Http\Controllers\Member;

    use App\Http\Controllers\Controller;
    use App\Entities\Member\Auth;
    use App\Http\Responses\Member\AuthCreateResponse;
    use App\Exceptions\Service\AuthExceptionCode;
    use TokenAuth;

    class AuthController extends Controller {

        public function me(AuthCreateResponse $response)
        {
            if ($user = TokenAuth::getUser(Auth::class)) {

                return $response->json($user->toArray());
            }

            throw new AuthExceptionCode(AuthExceptionCode::USER_AUTH_FAIL);
        }
    }

    ==========================================================================

    Custom Definition :

    Usage 1 :
    Get the name of the login identifier for the user.
    Note : If an empty string is returned, it will cancel the API interface auth login function.
    Example :
    --------------------------------------------------------------------------
    public static function getLoginIdentifierName(): string
    {
        return 'account';
    }

    Usage 2 :
    Get the auth password for the user.

    Example :
    --------------------------------------------------------------------------
    public function getAuthPassword(): string
    {
        return $this->password;
    }

    Usage 3 :
    Verify if the other status of the user is abnormal.

    Other auth users call automatically at the following locations :
    APIs Route : 'auth.user.login', 'auth.user.signature.login'
    Middleware : 'token.auth', 'token.revoke', 'token.refresh', 'token.logout'
    Auxiliary : You can use the $this->isCaptureRoute($specify = []) function to do some masking.

    Example :
    --------------------------------------------------------------------------
    public function verifyHoldStatusOnFail()
    {
        if ($this->exists) {
            // You can use the $this->isCaptureRoute($specify = []) function to do some masking
            // You can set $this->getDefaultCaptureRoute() to affect $this->isCaptureRoute() function
            // Verify user other status throw exception
        }
    }
