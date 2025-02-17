<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Service\ClientCreateRequest;
use App\Http\Requests\Service\ClientUpdateRequest;
use App\Http\Responses\Service\ClientCreateResponse;
use App\Http\Responses\Service\ClientUpdateResponse;
use App\Exceptions\Service\ClientExceptionCode as ExceptionCode;
use App\Repositories\Service\ClientRepository;
use App\Validators\Service\ClientValidator;
use TokenAuth;

/**
 * @group
 *
 * Client Service
 *
 * @package namespace App\Http\Controllers\Service;
 */
class ClientController extends Controller
{

    /**
     *
     * @var ClientRepository
     */
    protected $repository;

    /**
     *
     * @var ClientValidator
     */
    protected $validator;

    /**
     * ClientController constructor.
     *
     * @param ClientRepository $repository
     * @param ClientValidator $validator
     */
    public function __construct(ClientRepository $repository, ClientValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Ban Index
     *
     * Get the ban index for the service.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * number | INT | Ban number
     * description | STR | Ban description
     * status | BOOL | Available option status false: Disable true: Enable
     *
     * @response
     * {
     *    "success": true,
     *    "data": [
     *        {
     *            "number": 0,
     *            "description": "Global Service",
     *            "status": true
     *        },
     *        {
     *            "number": 1,
     *            "description": "User Service",
     *            "status": true
     *        },
     *        {
     *            "number": 2,
     *            "description": "Admin Service",
     *            "status": true
     *        }
     *    ]
     * }
     *
     * @param ClientCreateRequest $request
     * @param ClientCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function bans(ClientCreateRequest $request, ClientCreateResponse $response)
    {
        return $response->success($this->repository->bans([
            'number',
            'description',
            'status'
        ]));
    }

    /**
     * Ban Info
     *
     * Get the ban description info for the service.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * number | INT | Ban number
     * description | STR | Ban description
     * status | BOOL | Available option status false: Disable true: Enable
     *
     * @urlParam number required Ban number Example: 1
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "number": 1,
     *        "description": "User Service",
     *        "status": true
     *    }
     * }
     *
     * @param int $number
     * @param ClientCreateRequest $request
     * @param ClientCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ban($number, ClientCreateRequest $request, ClientCreateResponse $response)
    {
        return $response->success($this->repository->bans([
            'number',
            'description',
            'status'
        ], $number));
    }

    /**
     * Client Index
     *
     * Get the client user index for the service.
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
     * status | BOOL | Client status false: Disable true: Enable
     * expired_at | STR | Available service end datetime
     * created_at | STR | Datetime when the client was created
     * updated_at | STR | Client last updated datetime
     *
     * meta.pagination :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * total | INT | Total number of data
     * count | INT | Number of data displayed
     * per_page | INT | Number of displayed data per page
     * current_page | INT | Current page number
     * total_pages | INT | Total pages
     *
     * @queryParam name Client name index fuzzy query Example: a
     * @queryParam creation_sort Sort mode by creation time index ( ASC,DESC ) Example: ASC
     * @queryParam start Start range of query creation date Example: 2018-10-01
     * @queryParam end End range of query creation date Example: 2020-10-30
     * @queryParam page required Page number Example: 1
     * @queryParam rows Per page rows defaults to 15 Example: 15
     *
     * @response
     * {
     *    "success": true,
     *    "data": [
     *        {
     *            "app_id": "6398211294583",
     *            "name": "admin",
     *            "ban": 0,
     *            "description": "Global Service",
     *            "status": true,
     *            "expired_at": "",
     *            "created_at": "2018-11-26 11:41:32",
     *            "updated_at": "2018-11-26 11:41:32"
     *        },
     *        {
     *            "app_id": "6398212215437",
     *            "name": "sns gmae",
     *            "ban": 1,
     *            "description": "User Service",
     *            "status": true,
     *            "expired_at": "",
     *            "created_at": "2018-11-26 11:41:32",
     *            "updated_at": "2018-11-26 11:41:32"
     *        },
     *        {
     *            "app_id": "6398213515611",
     *            "name": "arpg gmae",
     *            "ban": 1,
     *            "description": "User Service",
     *            "status": true,
     *            "expired_at": "",
     *            "created_at": "2018-11-26 11:41:32",
     *            "updated_at": "2018-11-26 11:41:32"
     *        }
     *    ],
     *    "meta": {
     *        "pagination": {
     *            "total": 3,
     *            "count": 3,
     *            "per_page": 15,
     *            "current_page": 1,
     *            "total_pages": 1
     *        }
     *    }
     * }
     *
     * @param ClientCreateRequest $request
     * @param ClientCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ClientCreateRequest $request, ClientCreateResponse $response)
    {
        return $response->success($this->repository->index($request));
    }

    /**
     * Client Info
     *
     * Get the client user info for the service.
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
     * client_id | STR | Client id account
     * client_secret | STR | Client secret password
     * ban | INT | Client ban number
     * description | STR | Client ban description
     * status | BOOL | Client status false: Disable true: Enable
     * expired_at | STR | Available service end datetime
     * created_at | STR | Datetime when the client was created
     * updated_at | STR | Client last updated datetime
     *
     * @urlParam app_id required Client serial id Example: 6398211294583
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "app_id": "6398211294583",
     *        "name": "admin",
     *        "client_id": "2301cb9578e2de254401ccc473928439",
     *        "client_secret": "504b8f12b812b4b8ddeb210f826de044",
     *        "ban": 0,
     *        "description": "Global Service",
     *        "status": true,
     *        "expired_at": "",
     *        "created_at": "2018-11-26 11:41:32",
     *        "updated_at": "2018-11-26 11:41:32"
     *    }
     * }
     *
     * @param int $id
     * @param ClientCreateRequest $request
     * @param ClientCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function read($id, ClientCreateRequest $request, ClientCreateResponse $response)
    {
        return $response->success($this->repository->focusClient($id));
    }

    /**
     * Build Client
     *
     * Build a client user for the service.
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
     * client_id | STR | Client id account
     * client_secret | STR | Client secret password
     * ban | INT | Client ban number
     * description | STR | Client ban description
     * status | BOOL | Client status false: Disable true: Enable
     * expired_at | STR | Available service end datetime
     * created_at | STR | Datetime when the client was created
     * updated_at | STR | Client last updated datetime
     *
     * @bodyParam name STR required Client service name Example: {name}
     * @bodyParam ban INT required Ban number Example: {ban}
     * @bodyParam expire STR Available service end datetime YYYY-MM-DD hh:mm:ss Example: {expire}
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "app_id": "6398214798008",
     *        "name": "mmo game",
     *        "client_id": "c81e8e3225624250829a2139eb8a4d4c",
     *        "client_secret": "d4b25b9f79aa9f489557efeb78671197",
     *        "ban": 1,
     *        "description": "User Service",
     *        "status": true,
     *        "expired_at": "",
     *        "created_at": "2018-11-26 18:06:24",
     *        "updated_at": "2018-11-26 18:06:24"
     *    }
     * }
     *
     * @param ClientCreateRequest $request
     * @param ClientCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function build(ClientCreateRequest $request, ClientCreateResponse $response)
    {
        $ban = $request->input('ban');

        $bans = $this->repository->bans([
            'status'
        ], $ban);

        if (! $bans['status']) {
            throw new ExceptionCode(ExceptionCode::BAN_NUMBER_DISABLED);
        }

        return $response->success($this->repository->build($request->input('name'), $ban, $request->input('expire')));
    }

    /**
     * Reset Client Secret
     *
     * Reset the client's service secret.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * client_secret | STR | Client secret password
     *
     * @urlParam app_id required Client serial id Example: 6398211294583
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "client_secret": "5a38f514ffe7704f8c0094d41fb75bf7"
     *    }
     * }
     *
     * @param int $id
     * @param ClientUpdateRequest $request
     * @param ClientUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetSecret($id, ClientUpdateRequest $request, ClientUpdateResponse $response)
    {
        $source = [
            'client_secret' => $this->repository->focusResetSecret($id)
        ];

        return $response->success($source);
    }

    /**
     * Disable Client
     *
     * Disable the client user for the service.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam app_id required Client serial id Example: 6398212215437
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param int $id
     * @param ClientUpdateRequest $request
     * @param ClientUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable($id, ClientUpdateRequest $request, ClientUpdateResponse $response)
    {
        /* Check operator */
        if (($client = TokenAuth::getClient()) && $client->id == $id) {
            throw new ExceptionCode(ExceptionCode::INOPERABLE_CLIENT);
        }
        
        $this->repository->focusDisable($id);

        return $response->success();
    }

    /**
     * Enable Client
     *
     * Enable the client user for the service.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam app_id required Client serial id Example: 6398212215437
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param int $id
     * @param ClientUpdateRequest $request
     * @param ClientUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable($id, ClientUpdateRequest $request, ClientUpdateResponse $response)
    {
        $this->repository->focusEnable($id);

        return $response->success();
    }

    /**
     * Rename Client
     *
     * Rename the client name of the service.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam app_id required Client serial id Example: 6398212215437
     * @bodyParam name STR required Client service name Example: {name}
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param int $id
     * @param ClientUpdateRequest $request
     * @param ClientUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function rename($id, ClientUpdateRequest $request, ClientUpdateResponse $response)
    {
        $this->repository->focusRename($id, $request->input('name'));

        return $response->success();
    }

    /**
     * Rewrite Client Ban
     *
     * Rewrite the client ban number of the service.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam app_id required Client serial id Example: 6398212215437
     * @bodyParam ban INT required Ban number Example: {ban}
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param int $id
     * @param ClientUpdateRequest $request
     * @param ClientUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function rewriteBan($id, ClientUpdateRequest $request, ClientUpdateResponse $response)
    {
        /* Check operator */
        if (($client = TokenAuth::getClient()) && $client->id == $id) {
            throw new ExceptionCode(ExceptionCode::INOPERABLE_CLIENT);
        }

        $ban = $request->input('ban');

        $bans = $this->repository->bans([
            'status'
        ], $ban);

        if (! $bans['status']) {
            throw new ExceptionCode(ExceptionCode::BAN_NUMBER_DISABLED);
        }

        $this->repository->focusRewriteBan($id, $ban);

        return $response->success();
    }

    /**
     * Reschedule Client Expiration
     *
     * Reschedule the client expiration of the service.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam app_id required Client serial id Example: 6398212215437
     * @bodyParam expire STR Available service end datetime YYYY-MM-DD hh:mm:ss Example: {expire}
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param int $id
     * @param ClientUpdateRequest $request
     * @param ClientUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reschedule($id, ClientUpdateRequest $request, ClientUpdateResponse $response)
    {
        /* Check operator */
        if (($client = TokenAuth::getClient()) && $client->id == $id) {
            throw new ExceptionCode(ExceptionCode::INOPERABLE_CLIENT);
        }

        $expire = $request->input('expire');

        $this->repository->focusReschedule($id, $expire);

        return $response->success();
    }
}
