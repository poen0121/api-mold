<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Message\BulletinCreateRequest;
use App\Http\Requests\Message\BulletinUpdateRequest;
use App\Http\Responses\Message\BulletinCreateResponse;
use App\Http\Responses\Message\BulletinUpdateResponse;
use App\Exceptions\Message\BulletinExceptionCode as ExceptionCode;
use App\Repositories\Message\BulletinRepository;
use App\Validators\Message\BulletinValidator;
use App\Libraries\Instances\Notice\Bulletin;
use App\Libraries\Instances\Swap\TimeDisplay;

/**
 * @group
 *
 * Notify Bulletin
 *
 * @package namespace App\Http\Controllers\Message;
 */
class BulletinController extends Controller
{
    /**
     * @var BulletinRepository
     */
    protected $repository;

    /**
     * @var BulletinValidator
     */
    protected $validator;

    /**
     * BulletinController constructor.
     *
     * @param BulletinRepository $repository
     * @param BulletinValidator $validator
     */
    public function __construct(BulletinRepository $repository, BulletinValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Bulletin Index
     *
     * Get the bulletin notification index.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * id | STR | Bulletin serial id
     * subject | STR | Subject name
     * content | OBJ | Notify content
     * type | STR | User type code
     * type_name | STR | User type name
     * released_at | STR | Schedule the bulletin release datetime
     * expired_at | STR | Schedule the bulletin end datetime
     * status | BOOL | Bulletin status false: Disable true: Enable
     * created_at | STR | Datetime when the bulletin was created
     * updated_at | STR | Bulletin last updated datetime
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
     * @queryParam start Start range of query creation date Example: 2020-10-07
     * @queryParam end End range of query creation date Example: 2020-10-10
     * @queryParam page required Page number Example: 1
     * @queryParam rows Per page rows defaults to 15 Example: 15
     *
     * @response
     * {
     *     "success": true,
     *     "data": [
     *         {
     *             "id": "ddc09c60-8385-4e64-8516-6ba3f6fd6a84",
     *             "subject": "Maintenance notice",
     *             "content": {
     *                 "message": "Maintenance notice : 2020-10-10 10:00:00 ~ 2020-10-10 12:00:00."
     *             },
     *             "type": "member",
     *             "type_name": "Member User",
     *             "released_at": "2020-10-10 00:00:00",
     *             "expired_at": "2020-10-10 11:00:00",
     *             "status": true,
     *             "created_at": "2020-10-08 10:00:00",
     *             "updated_at": "2020-10-09 10:00:00"
     *         },
     *         {
     *             "id": "ddc09c60-8385-4e64-8516-6ba3f6fd6a82",
     *             "subject": "Activity notice",
     *             "content": {
     *                 "message": "Activity notice.",
     *                 "note": {
     *                      "link": "http://example.com/activity/1001"
     *                 }
     *             },
     *             "type": "member",
     *             "type_name": "Member User",
     *             "released_at": "2020-10-10 12:00:00",
     *             "expired_at": "2020-10-12 10:00:00",
     *             "status": true,
     *             "created_at": "2020-10-08 11:00:00",
     *             "updated_at": "2020-10-08 12:00:00"
     *         },
     *         {
     *             "id": "ddc09c60-8385-4e64-8516-6ba3f6fd6a83",
     *             "subject": "Maintenance notice",
     *             "content": {
     *                 "message": "Maintenance notice : 2020-10-12 10:00:00 ~ 2020-10-12 12:00:00."
     *             },
     *             "type": "member",
     *             "type_name": "Member User",
     *             "released_at": "2020-10-12 00:00:00",
     *             "expired_at": "2020-10-12 11:00:00",
     *             "status": false,
     *             "created_at": "2020-10-09 10:00:00",
     *             "updated_at": "2020-10-09 11:00:00"
     *         }
     *     ],
     *     "meta": {
     *         "pagination": {
     *             "total": 3,
     *             "count": 3,
     *             "per_page": 15,
     *             "current_page": 1,
     *             "total_pages": 1
     *         }
     *     }
     * }
     *
     * @param BulletinCreateRequest $request
     * @param BulletinCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(BulletinCreateRequest $request, BulletinCreateResponse $response)
    {
        return $response->success($this->repository->index($request));
    }

    /**
     * Bulletin Info
     *
     * Get the bulletin notification info.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * id | STR | Bulletin serial id
     * subject | STR | Subject name
     * content | OBJ | Notify content
     * type | STR | User type code
     * type_name | STR | User type name
     * released_at | STR | Schedule the bulletin release datetime
     * expired_at | STR | Schedule the bulletin end datetime
     * status | BOOL | Bulletin status false: Disable true: Enable
     * created_at | STR | Datetime when the bulletin was created
     * updated_at | STR | Bulletin last updated datetime
     *
     * @urlParam id required Bulletin serial id Example: ddc09c60-8385-4e64-8516-6ba3f6fd6a84
     *
     * @response
     * {
     *     "success": true,
     *     "data": {
     *         "id": "ddc09c60-8385-4e64-8516-6ba3f6fd6a84",
     *         "subject": "Maintenance notice",
     *         "content": {
     *             "message": "Maintenance notice : 2020-10-10 10:00:00 ~ 2020-10-10 12:00:00."
     *         },
     *         "type": "member",
     *         "type_name": "Member User",
     *         "released_at": "2020-10-10 00:00:00",
     *         "expired_at": "2020-10-10 11:00:00",
     *         "status": true,
     *         "created_at": "2020-10-08 10:00:00",
     *         "updated_at": "2020-10-09 10:00:00"
     *     }
     * }
     *
     * @param string $id
     * @param BulletinCreateRequest $request
     * @param BulletinCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function read($id, BulletinCreateRequest $request, BulletinCreateResponse $response)
    {
        return $response->success($this->repository->focusBulletin($id));
    }

    /**
     * Notifiable Label Types
     *
     * Get a list of label types for notification.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * type | STR | Label type code
     * description | STR | Label type about description
     *
     * @response
     * {
     *    "success": true,
     *    "data": [
     *        {
     *            "type": "normal",
     *            "description": "Normal"
     *        },
     *        {
     *            "type": "system",
     *            "description": "System"
     *        }
     *    ]
     * }
     *
     * @param BulletinCreateRequest $request
     * @param BulletinCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function labelTypes(BulletinCreateRequest $request, BulletinCreateResponse $response)
    {
        $types = Bulletin::labelTypes([
            'type',
            'description'
        ]);
        return $response->success([
            'data' => array_values($types)
        ]);
    }

    /**
     * Notifiable User Types
     *
     * Get a list of user types for notification.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * type | STR | User type code
     * description | STR | User type about description
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
     * @param BulletinCreateRequest $request
     * @param BulletinCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userTypes(BulletinCreateRequest $request, BulletinCreateResponse $response)
    {
        $types = Bulletin::userTypes([
            'type',
            'description'
        ]);
        return $response->success([
            'data' => array_values($types)
        ]);
    }

    /**
     * Build Bulletin
     *
     * Build a bulletin notification message.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam label required Label type code Example: normal
     * @urlParam type required User type code Example: member
     * @bodyParam subject STR required Subject name Example: {subject}
     * @bodyParam message STR required Push message Example: {message}
     * @bodyParam note ARR,OBJ Additional note Example: {note}
     * @bodyParam start STR required Bulletin start range time yyyy-mm-dd hh:ii:ss Example: {start}
     * @bodyParam end STR required Bulletin end range time yyyy-mm-dd hh:ii:ss Example: {end}
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param array $label
     * @param array $type
     * @param  BulletinCreateRequest $request
     * @param  BulletinCreateResponse $response
     *
     * @return \Illuminate\Http\Response
     */
    public function build($label, $type, BulletinCreateRequest $request, BulletinCreateResponse $response)
    {
        $subject = $request->input('subject');
        $message = $request->input('message');
        $note = $request->input('note');
        $start = TimeDisplay::asLocalTime($request->input('start'))->toDateTimeString();
        $end = TimeDisplay::asLocalTime($request->input('end'))->toDateTimeString();
        if (Bulletin::build($type['type'], $subject, ['message' => $message, 'note' => (isset($note) ? $note : [])], $start, $end, $label['type'])) {
            return $response->success();
        }
        throw new ExceptionCode(ExceptionCode::CREATE_FAIL);
    }

    /**
     * Disable Bulletin
     *
     * Disable the bulletin notification.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam id required Bulletin serial id Example: ddc09c60-8385-4e64-8516-6ba3f6fd6a84
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param string $id
     * @param BulletinUpdateRequest $request
     * @param BulletinUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable($id, BulletinUpdateRequest $request, BulletinUpdateResponse $response)
    {
        $this->repository->focusDisable($id);

        return $response->success();
    }

    /**
     * Enable Bulletin
     *
     * Enable the bulletin notification.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam id required Bulletin serial id Example: ddc09c60-8385-4e64-8516-6ba3f6fd6a84
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param string $id
     * @param BulletinUpdateRequest $request
     * @param BulletinUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable($id, BulletinUpdateRequest $request, BulletinUpdateResponse $response)
    {
        $this->repository->focusEnable($id);

        return $response->success();
    }
}
