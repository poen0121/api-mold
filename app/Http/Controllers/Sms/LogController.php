<?php

namespace App\Http\Controllers\Sms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Sms\LogCreateRequest;
use App\Http\Requests\Sms\LogUpdateRequest;
use App\Http\Responses\Sms\LogCreateResponse;
use App\Http\Responses\Sms\LogUpdateResponse;
use App\Exceptions\Sms\OperateExceptionCode as ExceptionCode;
use App\Repositories\Sms\OperateRepository;
use App\Validators\Sms\OperateValidator;

/**
 * @group
 *
 * SMS Log
 *
 * @package namespace App\Http\Controllers\Sms;
 */
class LogController extends Controller
{
    /**
     * @var OperateRepository
     */
    protected $repository;

    /**
     * @var OperateValidator
     */
    protected $validator;

    /**
     * LogController constructor.
     *
     * @param OperateRepository $repository
     * @param OperateValidator $validator
     */
    public function __construct(OperateRepository $repository, OperateValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * SMS Record Index
     *
     * Get the SMS sending index.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * serial | STR | SMS record serial id
     * source_id | STR | SMS operate source id
     * source | STR | SMS operate source type code
     * source_name | STR | SMS operate source type name
     * telecomer | STR | SMS telecomer type code
     * telecomer_name | STR | SMS telecomer type name
     * phone | STR | SMS to phone number
     * message.message | STR | SMS message
     * message.subject | STR | SMS subject
     * result | OBJ | SMS response result message
     * operate | STR | SMS operate type ( success, failure )
     * created_at | STR | Datetime when the SMS was sent
     * updated_at | STR | Datetime when the log was updated
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
     *            "serial": "S120201118",
     *            "source_id": "6398211294583",
     *            "source": "system",
     *            "source_name": "System",
     *            "telecomer": "mexmo",
     *            "telecomer_name": "Mexmo Telecomer",
     *            "phone": "+886930684635",
     *            "message": {
     *                "message": "Authcode: 58496",
     *                "subject": "Auth Code"
     *            },
     *            "result": {
     *                "message-id": "0C000000217B7F02",
     *                "remaining-balance": "15.53590000",
     *                "message-price": "0.03330000",
     *                "status": "0"
     *            },
     *            "operate": "success",
     *            "created_at": "2020-11-18 10:57:20",
     *            "updated_at": "2020-11-18 10:57:20"
     *        },
     *        {
     *            "serial": "S220201118",
     *            "source_id": "6398211294583",
     *            "source": "system",
     *            "source_name": "System",
     *            "telecomer": "mexmo",
     *            "telecomer_name": "Mexmo Telecomer",
     *            "phone": "+886930877633",
     *            "message": {
     *                "message": "Authcode: 35745",
     *                "subject": "Auth Code"
     *            },
     *            "result": {
     *                "message-id": "0C000000217B7F10",
     *                "remaining-balance": "15.50260000",
     *                "message-price": "0.03330000",
     *                "status": "0"
     *            },
     *            "operate": "success",
     *            "created_at": "2020-11-18 10:57:30",
     *            "updated_at": "2020-11-18 10:57:30"
     *        }
     *    ],
     *    "meta": {
     *        "pagination": {
     *            "total": 2,
     *            "count": 2,
     *            "per_page": 15,
     *            "current_page": 1,
     *            "total_pages": 1
     *        }
     *    }
     * }
     *
     * @param LogCreateRequest $request
     * @param LogCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(LogCreateRequest $request, LogCreateResponse $response)
    {
        return $response->success($this->repository->index($request));
    }

    /**
     * SMS Record Serial Info
     *
     * Get the SMS sending serial info.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * serial | STR | SMS record serial id
     * source_id | STR | SMS operate source id
     * source | STR | SMS operate source type code
     * source_name | STR | SMS operate source type name
     * telecomer | STR | SMS telecomer type code
     * telecomer_name | STR | SMS telecomer type name
     * phone | STR | SMS to phone number
     * message.message | STR | SMS message
     * message.subject | STR | SMS subject
     * result | OBJ | SMS response result message
     * operate | STR | SMS operate type ( success, failure )
     * created_at | STR | Datetime when the SMS was sent
     * updated_at | STR | Datetime when the log was updated
     *
     * @urlParam serial required SMS record serial id Example: S120201118
     * 
     * @response
     * {
     *    "success": true,
     *    "data": {
     *       "serial": "S120201118",
     *       "source_id": "6398211294583",
     *       "source": "system",
     *       "source_name": "System",
     *       "telecomer": "mexmo",
     *       "telecomer_name": "Mexmo Telecomer",
     *       "phone": "+886930684635",
     *       "message": {
     *           "message": "Authcode: 58496",
     *           "subject": "Auth Code"
     *       },
     *       "result": {
     *           "message-id": "0C000000217B7F02",
     *           "remaining-balance": "15.53590000",
     *           "message-price": "0.03330000",
     *           "status": "0"
     *       },
     *       "operate": "success",
     *       "created_at": "2020-11-18 10:57:20",
     *       "updated_at": "2020-11-18 10:57:20"
     *   }
     * }
     *
     * @param string $serial
     * @param LogCreateRequest $request
     * @param LogCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function read($serial, LogCreateRequest $request, LogCreateResponse $response)
    {
        return $response->success($this->repository->focusSerial($serial));
    }
}
