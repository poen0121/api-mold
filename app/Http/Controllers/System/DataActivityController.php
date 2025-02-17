<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\System\DataActivityCreateRequest;
use App\Http\Requests\System\DataActivityUpdateRequest;
use App\Http\Responses\System\DataActivityCreateResponse;
use App\Http\Responses\System\DataActivityUpdateResponse;
use App\Exceptions\System\DataActivityExceptionCode as ExceptionCode;
use App\Repositories\System\DataActivityRepository;
use App\Validators\System\DataActivityValidator;

/**
 * @group
 *
 * Activity Log
 *
 * @package namespace App\Http\Controllers\System;
 */
class DataActivityController extends Controller
{
    /**
     * @var DataActivityRepository
     */
    protected $repository;

    /**
     * @var DataActivityValidator
     */
    protected $validator;

    /**
     * DataActivityController constructor.
     *
     * @param DataActivityRepository $repository
     * @param DataActivityValidator $validator
     */
    public function __construct(DataActivityRepository $repository, DataActivityValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Activity Log Types
     *
     * Get a list of log types for system activity.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * type | STR | Log type code
     * name | STR | Log type name
     *
     * @response
     * {
     *    "success": true,
     *    "data": [
     *        {
     *            "type": "default",
     *            "name": "Default"
     *        },
     *        {
     *            "type": "model",
     *            "name": "Model"
     *        },
     *        {
     *            "type": "access",
     *            "name": "Access"
     *        },
     *        {
     *            "type": "auth",
     *            "name": "Auth"
     *        },
     *        {
     *            "type": "login",
     *            "name": "Login"
     *        },
     *        {
     *            "type": "logout",
     *            "name": "Logout"
     *        },
     *        {
     *            "type": "revoke",
     *            "name": "Revoke"
     *        }
     *    ]
     * }
     *
     * @param DataActivityCreateRequest $request
     * @param DataActivityCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function types(DataActivityCreateRequest $request, DataActivityCreateResponse $response)
    {
        return $response->success(['data' => array_values($this->repository->types())]);
    }

    /**
     * Activity Log Index
     *
     * Get the system activity log index.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * type | STR | Log type code
     * name | STR | Log type name
     * description | STR | Log operation description
     * target_id | STR | Target indicator id
     * target_name | STR | Target type name of model
     * trigger_id | STR | Trigger indicator id
     * trigger_name | STR | Trigger type name of model 
     * properties | OBJ | Property content
     * created_at | STR | Datetime when the log was created
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
     * @urlParam type Log type code Example: model
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
     *            "type": "model",
     *            "name": "Model",
     *            "description": "Updated",
     *            "target_id": "1",
     *            "target_name": "System Parameter",
     *            "trigger_id": "6398211294583",
     *            "trigger_name": "Client",
     *            "properties": {
     *                 "old": {
     *                      "value": "20",
     *                      "updated_at": "2020-01-10 09:45:15"
     *                  },
     *                  "attributes": {
     *                      "value": "2",
     *                      "updated_at": "2020-01-10 09:45:19"
     *                  }
     *            },
     *            "created_at": "2020-01-10 09:45:19"
     *        }
     *    ],
     *    "meta": {
     *        "pagination": {
     *            "total": 1,
     *            "count": 1,
     *            "per_page": 15,
     *            "current_page": 1,
     *            "total_pages": 1
     *       }
     *    }
     * }
     *
     * @param string|null $type
     * @param DataActivityCreateRequest $request
     * @param DataActivityCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($type = null, DataActivityCreateRequest $request, DataActivityCreateResponse $response)
    {
        return $response->success($this->repository->index($request, $type));
    }
}
