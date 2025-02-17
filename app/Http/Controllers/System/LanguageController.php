<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\System\LanguageCreateRequest;
use App\Http\Requests\System\LanguageUpdateRequest;
use App\Http\Responses\System\LanguageCreateResponse;
use App\Http\Responses\System\LanguageUpdateResponse;
use App\Exceptions\System\LanguageExceptionCode as ExceptionCode;
use Lang;

/**
 * @group
 *
 * Support Language
 *
 * @package namespace App\Http\Controllers\System;
 */
class LanguageController extends Controller
{

    /**
     * LanguageController constructor.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Default Language
     *
     * Get the default language.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * language | STR | Language code
     * description | STR | Language about description
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "language": "en",
     *        "description": "English"
     *    }
     * }
     *
     * @param LanguageCreateRequest $request
     * @param LanguageCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function default(LanguageCreateRequest $request, LanguageCreateResponse $response)
    {
        return $response->success(Lang::default());
    }

    /**
     * Language Index
     *
     * Get the language index.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * language | STR | Language code
     * description | STR | Language about description
     *
     * @response
     * {
     *    "success": true,
     *    "data": [
     *        {
     *            "language": "en",
     *            "description": "English"
     *        },
     *        {
     *            "language": "zh-TW",
     *            "description": "繁體中文"
     *        }
     *    ]
     * }
     *
     * @param LanguageCreateRequest $request
     * @param LanguageCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(LanguageCreateRequest $request, LanguageCreateResponse $response)
    {
        return $response->success(array_values(Lang::locales()));
    }
}
