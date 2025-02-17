<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Service\AuthCreateRequest;
use App\Http\Requests\Service\AuthUpdateRequest;
use App\Http\Responses\Service\AuthCreateResponse;
use App\Http\Responses\Service\AuthUpdateResponse;
use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Service\AuthRepository;
use App\Validators\Service\AuthValidator;
use App\Libraries\Instances\Calculator\Monitor;
use TokenAuth;
use Hash;
use StorageSign;
use QrCode;

/**
 * @group
 *
 * Auth Service
 *
 * @package namespace App\Http\Controllers\Service;
 */
class AuthController extends Controller
{
    /**
     *
     * @var AuthRepository
     */
    protected $repository;
    
    /**
     *
     * @var AuthValidator
     */
    protected $validator;

    /**
     * AuthController constructor.
     *
     * @param AuthValidator $validator
     */
    public function __construct(AuthRepository $repository, AuthValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Get Access Token
     *
     * Login with client service to get the access token.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * access_token | STR | API access token
     * token_type | STR | API access token type
     * expires_in | INT | API access token valid seconds
     *
     * @bodyParam client_id STR required Client id Example: {client_id}
     * @bodyParam client_secret STR required Client secret Example: {client_secret}
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2FwaVwvdjFcL2F1dGhcL3Rva2VuIiwiaWF0IjoxNTQzMjI2NzgzLCJleHAiOjE1NDMyMjY4NDMsIm5iZiI6MTU0MzIyNjc4MywianRpIjoiWnZYVk9Ib2JRRzhKSnZqUCIsInN1YiI6MX0.9ZwtS9G2FyEPypmYczvZWuqUykEtEX2foDpYEXuTurc",
     *        "token_type": "bearer",
     *        "expires_in": 3660
     *    }
     * }
     *
     * @param AuthCreateRequest $request
     * @param AuthCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function token(AuthCreateRequest $request, AuthCreateResponse $response)
    {
        /* Check account mode */
        $accountName = $this->repository->model()::getLoginIdentifierName();
        if (! isset($accountName[0])) {
            throw new ExceptionCode(ExceptionCode::NO_PERMISSION);
        }
        /* Get service credentials */
        $credentials = $request->only([
            'client_id',
            'client_secret'
        ]);
        /* Check service */
        if ($client = $this->repository->model()::where($accountName, $credentials['client_id'])->first()) {
            if (!Hash::check($credentials['client_secret'], $client->getAuthPassword())) {
                throw new ExceptionCode(ExceptionCode::CLIENT_AUTH_FAIL);
            } else {
                /* Check auth client status */
                $client->verifyHoldStatusOnFail();
                /* Get client token */
                if ($token = TokenAuth::loginClient($client)) {
                    $source = [
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => $client->getTTL() * 60
                    ];

                    return $response->success($source);
                }
                throw new ExceptionCode(ExceptionCode::TOKEN_CREATE_FAIL);
            }
        }
        throw new ExceptionCode(ExceptionCode::CLIENT_NON_EXIST);
    }

    /**
     * Show Service Profile
     *
     * Show the client service profile for the current access token.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * app_id | STR | Client serial id
     * name | STR | Client name
     * ban | INT | Client ban number
     * description | STR | Client ban description
     * expired_at | STR | Available service end datetime
     * created_at | STR | Datetime when the client was created
     * updated_at | STR | Client last updated datetime
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "app_id": "6398211294583",
     *        "name": "admin",
     *        "ban": 0,
     *        "description": "Global Service",
     *        "expired_at": "",
     *        "created_at": "2018-11-26 11:41:32",
     *        "updated_at": "2018-11-26 11:41:32"
     *    }
     * }
     *
     * @param AuthCreateRequest $request
     * @param AuthCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function service(AuthCreateRequest $request, AuthCreateResponse $response)
    {
        /* Transformer */
        $transformer = app($this->repository->presenter())->getTransformer();
        /* Array Info */
        $info = $transformer->transform(TokenAuth::getClient());

        return $response->success($info);
    }

    /**
     * Refresh Access Token
     *
     * Refresh the current access token.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * access_token | STR | API access token
     * token_type | STR | API access token type
     * expires_in | INT | API access token valid seconds
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2FwaVwvdjFcL2F1dGhcL3Rva2VuXC9yZWZyZXNoIiwiaWF0IjoxNTQzMjI2NzgzLCJleHAiOjE1NDMyMjY4NDMsIm5iZiI6MTU0MzIyNjc4MywianRpIjoiMzRMbUZ5a3hQUDR3eWg0SSIsInN1YiI6MX0.GcZ8vExcbjWRTPQ_kOlBUg3h32ph-4viXIugApqjsTA",
     *        "token_type": "bearer",
     *        "expires_in": 3660
     *    }
     * }
     *
     * @param AuthUpdateRequest $request
     * @param AuthUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(AuthUpdateRequest $request, AuthUpdateResponse $response)
    {
        /* Refresh token */
        if ($token = TokenAuth::refresh()) {
            $source = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => TokenAuth::getTTL() * 60
            ];

            return $response->success($source);
        }
        throw new ExceptionCode(ExceptionCode::TOKEN_CREATE_FAIL);
    }

    /**
     * Revoke Access Token
     *
     * Revoke the current access token.
     *
     * ### Response Body
     *
     * success : true
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param AuthUpdateRequest $request
     * @param AuthUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function revoke(AuthUpdateRequest $request, AuthUpdateResponse $response)
    {
        /* Invalidate token */
        if (TokenAuth::revoke()) {
            return $response->success();
        }
        throw new ExceptionCode(ExceptionCode::AUTH_FAIL);
    }

    /**
     * Access User Types
     *
     * Get a list of user types for access.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * type | STR | Type code
     * description | STR | Type about description
     *
     * @response
     * {
     *    "success": true,
     *    "data": [
     *        {
     *            "type": "member",
     *            "description": "Member User"
     *        },
     *        {
     *            "type": "admin",
     *            "description": "Admin User"
     *        }
     *    ]
     * }
     *
     * @param AuthCreateRequest $request
     * @param AuthCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userTypes(AuthCreateRequest $request, AuthCreateResponse $response)
    {
        $types = TokenAuth::userTypes([
            'type',
            'description'
        ]);
        /* Check ban */
        $restrict = config('ban.release.' . TokenAuth::getClient()->ban . '.restrict_access_guards');
        if (is_array($restrict) && count($restrict) > 0) {
            $types = array_intersect_key($types, array_flip($restrict));
        }
        return $response->success([
            'data' => array_values($types)
        ]);
    }

    /**
     * User Online
     *
     * User online statistics.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * count | INT | User online count
     *
     * @urlParam type required User type code Example: member
     * 
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "count": 0
     *    }
     * }
     *
     * @param array $type
     * @param AuthCreateRequest $request
     * @param AuthCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function online($type, AuthCreateRequest $request, AuthCreateResponse $response)
    {
        $source = [
            'count' => (new Monitor($type['type']))->count()
        ];

        return $response->success($source);
    }

    /**
     * Login Identity
     *
     * Login with user credentials and return the user's identity access token.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * access_token | STR | API access token
     * token_type | STR | API access token type
     * expires_in | INT | API access token valid seconds
     *
     * @urlParam type required User type code Example: admin
     * @bodyParam account STR required User account Example: {account}
     * @bodyParam password STR required User password Example: {password}
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ8.eyJpc6MiOiJodHRwOlwvXC8sb2NhbGhvc3RcL2FwaVwvdjFcL2F1dGhcL3Rva2VuXC9yZWZyZXNoIiwiaWF0IjoxNTQzMjI2NzgzLCJleHAiOjE1NDMyMjY4NDMsIm5iZiI6MTU0MzIyNjc4MywianRpIjoiMzRMbUZ5a3hQUDR3eWg0SSIsInN1YiI6MX0.GcZ8vExcbjWRTPQ_kOlBUg3h32ph-4viXIugApqjsTA",
     *        "token_type": "bearer",
     *        "expires_in": 3660
     *    }
     * }
     *
     * @param array $type
     * @param AuthCreateRequest $request
     * @param AuthCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login($type, AuthCreateRequest $request, AuthCreateResponse $response)
    {
        /* Check account mode */
        $accountName = $type['class']::getLoginIdentifierName();
        if (! isset($accountName[0])) {
            throw new ExceptionCode(ExceptionCode::NO_PERMISSION);
        }
        /* Get user credentials */
        $credentials = $request->only([
            'account',
            'password'
        ]);
        /* Check user */
        if ($user = $type['class']::where($accountName, $credentials['account'])->first()) {
            if (!Hash::check($credentials['password'], $user->getAuthPassword())) {
                throw new ExceptionCode(ExceptionCode::USER_AUTH_FAIL);
            } else {
                /* Check auth user status */
                $user->verifyHoldStatusOnFail();
                /* Get user token */
                if ($token = TokenAuth::loginUser($user)) {
                    $source = [
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => $user->getTTL() * 60
                    ];

                    return $response->success($source);
                }
                throw new ExceptionCode(ExceptionCode::TOKEN_CREATE_FAIL);
            }
        }
        throw new ExceptionCode(ExceptionCode::USER_NON_EXIST);
    }

    /**
     * Logout Identity
     *
     * Revoke the current user's identity access token and return client access token.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * access_token | STR | API access token
     * token_type | STR | API access token type
     * expires_in | INT | API access token valid seconds
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ8.eyJpc3MiOiJodHRwOlwvXC8sb2NhbGhvc3RcL2FwaVwvdjFcL2F1dGhcL3Rva2VuXC9yZWZyZXNoIiwiaWF0IjoxNTQzMjI2NzgzLCJleHAiOjE1NDMyMjY4NDMsIm5iZiI6MTU0MzIyNjc4MywianRpIjoiMzRMbUZ5a3hQUDR3eWg0SSIsInN1YiI6MX0.GcZ8vExcbjWRTPQ_kOlBUg3h32ph-4viXIugApqjsTA",
     *        "token_type": "bearer",
     *        "expires_in": 3660
     *    }
     * }
     *
     * @param AuthUpdateRequest $request
     * @param AuthUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(AuthUpdateRequest $request, AuthUpdateResponse $response)
    {
        /* User Logout */
        if ($token = TokenAuth::logoutUser()) {
            /* Return authorization client access token */
            $source = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => TokenAuth::getTTL() * 60
            ];

            return $response->success($source);
        }
        throw new ExceptionCode(ExceptionCode::USER_AUTH_FAIL);
    }

    /**
     * Authorization Signature
     *
     * Get the user code used for signature authorization login.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * type | STR | Signature type
     * signature | STR | Authorized signature code
     * expires_in | INT | Authorized signature code valid seconds
     * qrcode | STR | Authorized signature QR code image link
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "type": "auth",
     *        "signature": "AUTH-8466B336802941AC8DF1BD3173BDEB8DE1FABCEC5FBB036F0C08C550A738B182ABAB2D07",
     *        "expires_in": 180,
     *        "qrcode": "http://example.com/qrcode/100/signature/AUTH-8466B336802941AC8DF1BD3173BDEB8DE1FABCEC5FBB036F0C08C550A738B182ABAB2D07"
     *    }
     * }
     *
     * @param AuthCreateRequest $request
     * @param AuthCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userSignature(AuthCreateRequest $request, AuthCreateResponse $response)
    {
        /* Check user auth */
        if ($user = TokenAuth::getUser()) {
            /* Get auth signature code */
            if ($code = TokenAuth::injectUserSignature($user)) {
                /* Return authorization signature code */
                $source = [
                    'type' => 'auth',
                    'signature' => $code,
                    'expires_in' => $user->getUTSTTL() * 60,
                    'qrcode' => url('/qrcode/signature/sign/' . $code)
                ];

                return $response->success($source);
            }
            throw new ExceptionCode(ExceptionCode::SIGNATURE_CREATE_FAIL);
        }
        throw new ExceptionCode(ExceptionCode::USER_AUTH_FAIL);
    }

    /**
     * Login Signature
     *
     * Login with user authorized signature code and return the user's identity access token.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * access_token | STR | API access token
     * token_type | STR | API access token type
     * expires_in | INT | API access token valid seconds
     *
     * @bodyParam signature STR required User authorized signature code Example: {signature}
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ8.eyJpc6MiOiJodHRwOlwvXC8sb2NhbGhvc3RcL2FwaVwvdjFcL2F1dGhcL3Rva2VuXC9yZWZyZXNoIiwiaWF0IjoxNTQzMjI2NzgzLCJleHAiOjE1NDMyMjY4NDMsIm5iZiI6MTU0MzIyNjc4MywianRpIjoiMzRMbUZ5a3hQUDR3eWg0SSIsInN1YiI6MX0.GcZ8vExcbjWRTPQ_kOlBUg3h32ph-4viXIugApqjsTA",
     *        "token_type": "bearer",
     *        "expires_in": 3660
     *    }
     * }
     *
     * @param AuthCreateRequest $request
     * @param AuthCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginSignature(AuthCreateRequest $request, AuthCreateResponse $response)
    {
        /* Get user model */
        if ($user = TokenAuth::getUserBySignature($request->input('signature'))) {
            /* Check auth user status */
            $user->verifyHoldStatusOnFail();
            /* Get user token */
            if ($token = TokenAuth::loginUser($user)) {
                $source = [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => $user->getTTL() * 60
                ];

                return $response->success($source);
            }
            throw new ExceptionCode(ExceptionCode::TOKEN_CREATE_FAIL);
        }
        throw new ExceptionCode(ExceptionCode::USER_AUTH_FAIL);
    }

    /**
     * APIs Doc Authorization Link
     *
     * Get the APIs documentation authorization link.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * link | STR | Authorized APIs documentation Link
     * expires_in | INT | Authorized signature code valid seconds
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "link": "http://example.com/doc?auth=6B336846802941AC8DF1BD3173BDEB8DE1FABCEC5FBB036F0C08C550A738B182ABAB3D12",
     *        "expires_in": 2592000
     *    }
     * }
     *
     * @param AuthCreateRequest $request
     * @param AuthCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authDoc(AuthCreateRequest $request, AuthCreateResponse $response)
    {
        if (config('apidoc.laravel.auth_mode')) {
            $ttl = config('apidoc.laravel.ttl', 43200);
            $ttl = ($ttl > 0 ? $ttl : 43200);
            if ($code = StorageSign::build(['apidoc'], $ttl)) {
                /* Return authorization link */
                $source = [
                    'link' => url(config('apidoc.laravel.docs_url') . '?auth=' . $code),
                    'expires_in' => $ttl * 60
                ];
                return $response->success($source);
            }
            throw new ExceptionCode(ExceptionCode::SIGNATURE_CREATE_FAIL);
        }
        throw new ExceptionCode(ExceptionCode::OPERATION_DISABLED);
    }

    /**
     * Signature QR Code
     *
     * View signature QR code image.
     *
     * @param string $type
     * @param string $code
     * @param int|null $size
     *
     * @return \Illuminate\Http\Response
     */
    public function qrcodeSignature($type, $code, $size = null)
    {
        /* Check provider */
        $providers = config('signature.qrcode_providers', []);
        if (! isset($providers[$type])) {
            throw new ModelNotFoundException('Signature QR Code: No query results for storage: Unknown type \'' . $type . '\'.');
        }
        /* Check code */
        if ($providers[$type]::get($code)) {
            $size = (isset($size) ? $size : config('signature.qrcode_size', 100));
            return response(QrCode::format('png')->size($size)->generate($code))->header('Content-type','image/png');
        }
        throw new ModelNotFoundException('Signature QR Code: No query results for storage: Unknown signature \'' . $code . '\'.');
    }
}
