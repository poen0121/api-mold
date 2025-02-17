<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\System\InterfaceCreateRequest;
use App\Http\Requests\System\InterfaceUpdateRequest;
use App\Http\Responses\System\InterfaceCreateResponse;
use App\Http\Responses\System\InterfaceUpdateResponse;
use App\Exceptions\System\InterfaceExceptionCode as ExceptionCode;
use App\Libraries\Instances\System\InterfaceScope;
use App\Repositories\Service\ClientRepository;

/**
 * @group
 *
 * Support API Interface
 *
 * @package namespace App\Http\Controllers\System;
 */
class InterfaceController extends Controller
{

    /**
     * Client repository
     *
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * InterfaceController constructor.
     *
     * @param ClientRepository $client
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * APIs
     *
     * Get a list of all API interfaces.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * code | STR | Interface code
     * interface | STR | Interface uri
     * description | STR | Interface about description
     *
     * @response
     * {
     *    "success": true,
     *    "data": [
     *        {
     *            "code": "auth.token.create",
     *            "interface": "api/v1/auth/token",
     *            "description": "Login Client Service"
     *        },
     *        {
     *            "code": "auth.token.refresh",
     *            "interface": "api/v1/auth/token/refresh",
     *            "description": "Refresh Access Token"
     *        },
     *        {
     *            "code": "auth.token.revoke",
     *            "interface": "api/v1/auth/token/revoke",
     *            "description": "Revoke Access Token"
     *        },
     *        {
     *            "code": "auth.user.logout",
     *            "interface": "api/v1/auth/logout",
     *            "description": "User Logout"
     *        },
     *        {
     *            "code": "auth.read.service",
     *            "interface": "api/v1/auth/service",
     *            "description": "Read Own Service Info"
     *        },
     *        {
     *            "code": "auth.client.index",
     *            "interface": "api/v1/auth/client",
     *            "description": "Client Service Index"
     *        },
     *        {
     *            "code": "auth.client.read",
     *            "interface": "api/v1/auth/client/{app_id}",
     *            "description": "Search Client Service Info"
     *        }
     *    ]
     * }
     *
     * @param InterfaceCreateRequest $request
     * @param InterfaceCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(InterfaceCreateRequest $request, InterfaceCreateResponse $response)
    {
        $scopes = InterfaceScope::all([
            'code',
            'interface',
            'description'
        ]);

        return $response->success([
            'data' => array_values($scopes)
        ]);
    }

    /**
     * Managed APIs
     *
     * Get a list of managed API interfaces.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * code | STR | Interface code
     * interface | STR | Interface uri
     * description | STR | Interface about description
     *
     * @response
     * {
     *    "success": true,
     *    "data": [
     *        {
     *            "code": "auth.client.index",
     *            "interface": "api/v1/auth/client",
     *            "description": "Client Service Index"
     *        },
     *        {
     *            "code": "auth.client.read",
     *            "interface": "api/v1/auth/client/{app_id}",
     *            "description": "Search Client Service Info"
     *        }
     *    ]
     * }
     *
     * @param InterfaceCreateRequest $request
     * @param InterfaceCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function managed(InterfaceCreateRequest $request, InterfaceCreateResponse $response)
    {
        $scopes = InterfaceScope::managed([
            'code',
            'interface',
            'description'
        ]);

        return $response->success([
            'data' => array_values($scopes)
        ]);
    }

    /**
     * Managed APIs By Ban
     *
     * Get a list of managed API interfaces by ban number.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * code | STR | Interface code
     * interface | STR | Interface uri
     * description | STR | Interface about description
     *
     * @urlParam number required Ban number Example: 1
     *
     * @response
     * {
     *    "success": true,
     *    "data": [
     *        {
     *            "code": "auth.client.index",
     *            "interface": "api/v1/auth/client",
     *            "description": "Client Service Index"
     *        }
     *    ]
     * }
     *
     * @param int $number
     * @param InterfaceCreateRequest $request
     * @param InterfaceCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function managedByBan($number, InterfaceCreateRequest $request, InterfaceCreateResponse $response)
    {
        $ban = $this->clientRepository->bans([
            'number'
        ], $number);

        $scopes = InterfaceScope::managedByBan($ban['number'], [
            'code',
            'interface',
            'description'
        ]);

        return $response->success([
            'data' => array_values($scopes)
        ]);
    }
}
