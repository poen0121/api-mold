<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\System\AuthoritySnapshotCreateRequest;
use App\Http\Requests\System\AuthoritySnapshotUpdateRequest;
use App\Http\Responses\System\AuthoritySnapshotCreateResponse;
use App\Http\Responses\System\AuthoritySnapshotUpdateResponse;
use App\Exceptions\System\AuthoritySnapshotExceptionCode as ExceptionCode;
use App\Repositories\System\AuthoritySnapshotRepository;
use App\Validators\System\AuthoritySnapshotValidator;
use App\Exceptions\System\AuthorityExceptionCode;
use App\Libraries\Instances\System\Authority;
use App\Libraries\Instances\System\InterfaceScope;

/**
 * @group
 *
 * Authority Snapshot
 *
 * @package namespace App\Http\Controllers\System;
 */
class AuthoritySnapshotController extends Controller
{
    /**
     * @var AuthoritySnapshotRepository
     */
    protected $repository;

    /**
     * @var AuthoritySnapshotValidator
     */
    protected $validator;

    /**
     * AuthoritySnapshotController constructor.
     *
     * @param AuthoritySnapshotRepository $repository
     * @param AuthoritySnapshotValidator $validator
     */
    public function __construct(AuthoritySnapshotRepository $repository, AuthoritySnapshotValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Authority Snapshot Index
     *
     * Get the authority snapshot index.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * id | STR | Snapshot id
     * name | STR | Snapshot name
     * created_at | STR | Datetime when the snapshot was created
     * updated_at | STR | Snapshot last updated datetime
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
     *            "id": "4f9f99e6d29046439a438b05f9607da1",
     *            "name": "View Client",
     *            "created_at": "2020-01-10 09:45:19",
     *            "updated_at": "2020-01-10 09:45:19"
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
     * @param AuthoritySnapshotCreateRequest $request
     * @param AuthoritySnapshotCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(AuthoritySnapshotCreateRequest $request, AuthoritySnapshotCreateResponse $response)
    {
        return $response->success($this->repository->index($request));
    }

    /**
     * Build Authority Snapshot
     *
     * Build a authority snapshot.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * id | STR | Snapshot id
     * name | STR | Snapshot name
     *
     * @bodyParam name STR required Authority snapshot name Example: {name}
     * @bodyParam interface ARR required Managed APIs interface code Example: {interface}
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *         "id": "4f9f99e6d29046439a438b05f9607da1",
     *         "name": "View Client"
     *     }
     * }
     *
     * @param  AuthoritySnapshotCreateRequest $request
     * @param  AuthoritySnapshotCreateResponse $response
     *
     * @return \Illuminate\Http\Response
     */
    public function build(AuthoritySnapshotCreateRequest $request, AuthoritySnapshotCreateResponse $response)
    {
        $managed = InterfaceScope::managed();
        $name = $request->input('name');
        $interface = $request->input('interface');
        foreach ($interface as $code) {
            if (! is_string($code) || ! isset($managed[$code])) {
                throw new AuthorityExceptionCode(AuthorityExceptionCode::INVALID_INTERFACE_CODE, [
                    '%code%' => (is_array($code) ? 'Array' : $code)
                ], [
                    '%code%' => (is_array($code) ? 'Array' : $code)
                ]);
            }
        }
        /* Create data */
        return $response->success($this->repository->build($name, $interface));
    }

    /**
     * Authority Snapshot Info
     *
     * Get the specified snapshot info from authority.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * id | STR | Snapshot id
     * name | STR | Snapshot name
     * authority | ARR | Snapshot APIs authority
     * created_at | STR | Datetime when the snapshot was created
     * updated_at | STR | Snapshot last updated datetime
     *
     * @urlParam id required Authority snapshot id Example: 4f9f99e6d29046439a438b05f9607da1
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *         "id": "4f9f99e6d29046439a438b05f9607da1",
     *         "name": "View Client",
     *         "authority": [
     *              "auth.client.index",
     *              "auth.token.create"
     *         ],
     *         "created_at": "2020-01-10 09:45:19",
     *         "updated_at": "2020-01-10 09:45:19"
     *     }
     * }
     *
     * @param  int $id
     * @param  AuthoritySnapshotCreateRequest $request
     * @param  AuthoritySnapshotCreateResponse $response
     *
     * @return \Illuminate\Http\Response
     */
    public function read($id, AuthoritySnapshotCreateRequest $request, AuthoritySnapshotCreateResponse $response)
    {
        $source = $this->repository->find($id);

        return $response->success($source);
    }

    /**
     * Rename Authority Snapshot
     *
     * Rename the snapshot name of the authority snapshot.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam id required Authority snapshot id Example: 4f9f99e6d29046439a438b05f9607da1
     * @bodyParam name STR required Authority snapshot name Example: {name}
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param int $id
     * @param AuthoritySnapshotUpdateRequest $request
     * @param AuthoritySnapshotUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function rename($id, AuthoritySnapshotUpdateRequest $request, AuthoritySnapshotUpdateResponse $response)
    {
        $this->repository->focusRename($id, $request->input('name'));

        return $response->success();
    }

    /**
     * Delete Authority Snapshot
     *
     * Delete the specified snapshot from authority.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam id required Authority snapshot id Example: 4f9f99e6d29046439a438b05f9607da1
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param  int $id
     * @param  AuthoritySnapshotUpdateRequest $request
     * @param  AuthoritySnapshotUpdateResponse $response
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id, AuthoritySnapshotUpdateRequest $request, AuthoritySnapshotUpdateResponse $response)
    {
        $this->repository->delete($id);

        return $response->success();
    }
}
