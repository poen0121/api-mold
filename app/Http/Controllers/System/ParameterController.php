<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\System\ParameterCreateRequest;
use App\Http\Requests\System\ParameterUpdateRequest;
use App\Http\Responses\System\ParameterCreateResponse;
use App\Http\Responses\System\ParameterUpdateResponse;
use App\Exceptions\System\ParameterExceptionCode as ExceptionCode;
use App\Repositories\System\ParameterRepository;
use App\Validators\System\ParameterValidator;
use SystemParameter;

/**
 * @group
 *
 * System Parameters
 *
 * @package namespace App\Http\Controllers\System;
 */
class ParameterController extends Controller
{
    /**
     * @var ParameterRepository
     */
    protected $repository;

    /**
     * @var ParameterValidator
     */
    protected $validator;

    /**
     * ParameterController constructor.
     *
     * @param ParameterRepository $repository
     * @param ParameterValidator $validator
     */
    public function __construct(ParameterRepository $repository, ParameterValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Parameter Index
     *
     * Get the parameter index.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * id | INT | Parameter serial id
     * slug | STR | Parameter key name
     * value | STR | Parameter value
     * rule.description | STR | Parameter value description
     * rule.type | STR | Parameter value type
     * description | STR | Parameter about description
     * created_at | STR | Datetime when the parameter was created
     * updated_at | STR | Parameter last updated datetime
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
     * @queryParam page required Page number Example: 1
     * @queryParam rows Per page rows defaults to 15 Example: 15
     *
     * @response
     * {
     *    "success": true,
     *    "data": [
     *        {
     *            "id": 1,
     *            "slug": "status",
     *            "value": "1",
     *            "rule": {
     *                "description": "Define values 0 ~ 1",
     *                "type": "integer"
     *            },
     *            "description": "System Status",
     *            "created_at": "2018-10-28 17:53:06",
     *            "updated_at": "2018-11-28 17:53:06"
     *        }
     *    ],
     *    "meta": {
     *        "pagination": {
     *            "total": 1,
     *            "count": 1,
     *            "per_page": 15,
     *            "current_page": 1,
     *            "total_pages": 1
     *        }
     *    }
     * }
     *
     * @param ParameterCreateRequest $request
     * @param ParameterCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ParameterCreateRequest $request, ParameterCreateResponse $response)
    {
        return $response->success($this->repository->index($request));
    }

    /**
     * Parameter Info
     *
     * Get the parameter info.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * id | INT | Parameter serial id
     * slug | STR | Parameter key name
     * value | STR | Parameter value
     * rule.description | STR | Parameter value description
     * rule.type | STR | Parameter value type
     * description | STR | Parameter about description
     * created_at | STR | Datetime when the parameter was created
     * updated_at | STR | Parameter last updated datetime
     *
     * @urlParam slug required Parameter key name Example: status
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "id": 1,
     *        "slug": "status",
     *        "value": "1",
     *        "rule": {
     *            "description": "Define values 0 ~ 1",
     *            "type": "integer"
     *        },
     *        "description": "System Status",
     *        "created_at": "2018-10-28 17:53:06",
     *        "updated_at": "2018-11-28 17:53:06"
     *    }
     * }
     *
     * @param string $slug
     * @param ParameterCreateRequest $request
     * @param ParameterCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function read($slug, ParameterCreateRequest $request, ParameterCreateResponse $response)
    {
        return $response->success($this->repository->focusSlug($slug));
    }

    /**
     * Rewrite Parameter Value
     *
     * Rewrite the parameter value.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam slug required Parameter key name Example: status
     * @bodyParam value STR required Overwrite parameter value Example: {value}
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param string $slug
     * @param ParameterUpdateRequest $request
     * @param ParameterUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function rewriteValue($slug, ParameterUpdateRequest $request, ParameterUpdateResponse $response)
    {
        /* Check slug */
        $this->repository->focusSlug($slug);
        /* Set value */
        if (SystemParameter::setValue($slug, $request->input('value'), false)) {
            return $response->success();
        } else {
            throw new ExceptionCode(ExceptionCode::REWRITE_PARAMETER_VALUE_FAIL);
        }
    }
}
