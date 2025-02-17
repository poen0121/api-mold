<?php

namespace App\Http\Controllers\Feature;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Feature\ProviderCreateRequest;
use App\Http\Requests\Feature\ProviderUpdateRequest;
use App\Http\Responses\Feature\ProviderCreateResponse;
use App\Http\Responses\Feature\ProviderUpdateResponse;
use App\Exceptions\Feature\ProviderExceptionCode as ExceptionCode;
use App\Libraries\Instances\Feature\Provider;

/**
 * @group
 *
 * Feature Provider
 *
 * @package namespace App\Http\Controllers\Feature;
 */
class ProviderController extends Controller
{

    /**
     * ProviderController constructor.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Feature Index
     *
     * Get the feature index for the provider.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * code | STR | Feature code
     * description | STR | Feature description
     * arguments.deploy.{*}.type | STR | Feature deployment arguments type
     * arguments.deploy.{*}.status | STR | Feature deployment arguments request status 'required' or 'optional'
     * arguments.deploy.{*}.description | STR | Feature deployment arguments description
     * arguments.handle.{*}.type | STR | Feature handle arguments type
     * arguments.handle.{*}.status | STR | Feature handle arguments request status 'required' or 'optional'
     * arguments.handle.{*}.description | STR | Feature handle arguments description
     * responses.deploy.{*}.type | STR | Feature deployment responses type
     * responses.deploy.{*}.description | STR | Feature deployment responses description
     * responses.handle.{*}.type | STR | Feature handle responses type
     * responses.handle.{*}.description | STR | Feature handle responses description
     *
     * @response
     * {
     *    "success": true,
     *    "data": [
     *        {
     *            "code": "add_gold",
     *            "description": "Increase Gold Coins",
     *            "arguments": {
     *                "deploy": {
     *                    "quantity": {
     *                        "type": "integer",
     *                        "status": "required",
     *                        "description": "Quantity increase ( 1 ~ 2147483647 )"
     *                    }
     *                },
     *                "handle": []
     *            },
     *            "responses": {
     *                "deploy": {
     *                    "quantity": {
     *                        "type": "integer",
     *                        "description": "Increase quantity"
     *                    }
     *                },
     *                "handle": {
     *                    "name": {
     *                        "type": "string",
     *                        "description": "Currency name"
     *                    },
     *                    "quantity": {
     *                        "type": "integer",
     *                        "description": "Increase quantity"
     *                    }
     *                }
     *            }
     *        },
     *        {
     *            "code": "item_bale",
     *            "description": "Package Item",
     *            "arguments": {
     *                "deploy": {
     *                    "id": {
     *                        "type": "integer",
     *                        "status": "required",
     *                        "description": "Item serial id"
     *                    },
     *                    "quantity": {
     *                        "type": "integer",
     *                        "status": "required",
     *                        "description": "Quantity increase ( 1 ~ 2147483647 )"
     *                    }
     *                },
     *                "handle": []
     *            },
     *            "responses": {
     *                "deploy": {
     *                    "id": {
     *                        "type": "integer",
     *                        "description": "Item serial id"
     *                    },
     *                    "quantity": {
     *                        "type": "integer",
     *                        "description": "Increase quantity"
     *                    }
     *                },
     *                "handle": {
     *                    "id": {
     *                        "type": "integer",
     *                        "description": "Item serial id"
     *                    },
     *                    "type": {
     *                        "type": "integer",
     *                        "description": "Item type number"
     *                    },
     *                    "name": {
     *                        "type": "string",
     *                        "description": "Item name"
     *                    },
     *                    "quantity": {
     *                        "type": "integer",
     *                        "description": "Increase quantity"
     *                    }
     *                }
     *            }
     *        }
     *    ]
     * }
     *
     * @param ProviderCreateRequest $request
     * @param ProviderCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ProviderCreateRequest $request, ProviderCreateResponse $response)
    {
        return $response->success([
            'data' => array_values(Provider::getDoc(Provider::getProvider(), [
            'code',
            'description',
            'arguments',
            'responses'
        ]))]);
    }

    /**
     * Feature Code Info
     *
     * Get the feature code information for the provider.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * code | STR | Feature code
     * description | STR | Feature description
     * arguments.deploy.{*}.type | STR | Feature deployment arguments type
     * arguments.deploy.{*}.status | STR | Feature deployment arguments request status 'required' or 'optional'
     * arguments.deploy.{*}.description | STR | Feature deployment arguments description
     * arguments.handle.{*}.type | STR | Feature handle arguments type
     * arguments.handle.{*}.status | STR | Feature handle arguments request status 'required' or 'optional'
     * arguments.handle.{*}.description | STR | Feature handle arguments description
     * responses.deploy.{*}.type | STR | Feature deployment responses type
     * responses.deploy.{*}.description | STR | Feature deployment responses description
     * responses.handle.{*}.type | STR | Feature handle responses type
     * responses.handle.{*}.description | STR | Feature handle responses description
     *
     * @urlParam code required Feature code Example: add_gold
     * 
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "code": "add_gold",
     *        "description": "Increase Gold Coins",
     *        "arguments": {
     *            "deploy": {
     *                "quantity": {
     *                    "type": "integer",
     *                    "status": "required",
     *                    "description": "Quantity increase ( 1 ~ 2147483647 )"
     *                }
     *            },
     *            "handle": []
     *        },
     *        "responses": {
     *            "deploy": {
     *                "quantity": {
     *                    "type": "integer",
     *                    "description": "Increase quantity"
     *                }
     *            },
     *            "handle": {
     *                "name": {
     *                    "type": "string",
     *                    "description": "Currency name"
     *                },
     *                "quantity": {
     *                    "type": "integer",
     *                    "description": "Increase quantity"
     *                }
     *            }
     *        }
     *    }
     * }
     *
     * @param string $code
     * @param ProviderCreateRequest $request
     * @param ProviderCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function read($code, ProviderCreateRequest $request, ProviderCreateResponse $response)
    {
        return $response->success(Provider::getDoc(Provider::getProvider(), [
            'code',
            'description',
            'arguments',
            'responses'
        ], $code));
    }
}
