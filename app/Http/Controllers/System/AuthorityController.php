<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\System\AuthorityCreateRequest;
use App\Http\Requests\System\AuthorityUpdateRequest;
use App\Http\Responses\System\AuthorityCreateResponse;
use App\Http\Responses\System\AuthorityUpdateResponse;
use App\Exceptions\System\AuthorityExceptionCode as ExceptionCode;
use App\Libraries\Instances\System\Authority;
use App\Libraries\Instances\System\InterfaceScope;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Entities\System\AuthoritySnapshot;
use TokenAuth;

/**
 * @group
 *
 * Authority Operation
 *
 * @package namespace App\Http\Controllers\System;
 */
class AuthorityController extends Controller
{

    /**
     * AuthorityController constructor.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Authority License Types
     *
     * Get a list of authority object types.
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
     * @param AuthorityCreateRequest $request
     * @param AuthorityCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function objectTypes(AuthorityCreateRequest $request, AuthorityCreateResponse $response)
    {
        return $response->success([
            'data' => array_values(Authority::objectTypes([
                'type',
                'description'
            ]))
        ]);
    }

    /**
    * Global Authority
    *
    * Grant the global permissions to type object.
    *
    * ### Response Body
    *
    * success : true
    *
    * @urlParam type required License object type code Example: admin
    * @urlParam uid required Identify the object serial id Example: 6398211294583
    *
    * @response
    * {
    *    "success": true
    * }
    *
    * @param array $type
    * @param int $id
    * @param AuthorityUpdateRequest $request
    * @param AuthorityUpdateResponse $response
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function global($type, $id, AuthorityUpdateRequest $request, AuthorityUpdateResponse $response)
    {
        /* Check operator */
        if (($user = TokenAuth::getUser()) && $type['class'] === get_class($user) && $user->id == $id) {
            throw new ExceptionCode(ExceptionCode::INOPERABLE_USER);
        }
        /* Select and save */
        if ($object = $type['class']::find($id)) {
            /* Save */
            $object->authority = Authority::global($object);
            $object->save();
        } else {
            throw new ModelNotFoundException('Query Authority: No query results for objects: Unknown object id \'' . $id . '\'.');
        }
        
        return $response->success();
    }
    
    /**
     * Grant Authority
     *
     * Grant the permissions to type object.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam type required License object type code Example: admin
     * @urlParam uid required Identify the object serial id Example: 6398211294583
     * @bodyParam interface ARR Managed APIs interface code Example: {interface}
     * @bodyParam snapshot ARR Authority snapshot id Example: {snapshot}
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param array $type
     * @param int $id
     * @param AuthorityUpdateRequest $request
     * @param AuthorityUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function grant($type, $id, AuthorityUpdateRequest $request, AuthorityUpdateResponse $response)
    {
        /* Check operator */
        if (($user = TokenAuth::getUser()) && $type['class'] === get_class($user) && $user->id == $id) {
            throw new ExceptionCode(ExceptionCode::INOPERABLE_USER);
        }
        /* Select and save */
        if ($object = $type['class']::find($id)) {
            $interface = $request->input('interface');
            $snapshot = $request->input('snapshot');
            if (!isset($interface) && !isset($snapshot)) {
                throw new ExceptionCode(ExceptionCode::UNSPECIFIED_AUTHORITY);
            }
            $authority = [];
            if (isset($interface)) {
                $managed = InterfaceScope::managed();
                foreach ($interface as $code) {
                    if (! is_string($code) || ! isset($managed[$code])) {
                        throw new ExceptionCode(ExceptionCode::INVALID_INTERFACE_CODE, [
                            '%code%' => (is_array($code) ? 'Array' : $code)
                        ], [
                            '%code%' => (is_array($code) ? 'Array' : $code)
                        ]);
                    }
                }
                $authority = $interface;
            }
            /* Check snapshot */
            if (isset($snapshot)) {
                $snapshot = AuthoritySnapshot::find($snapshot);
                if (! $snapshot) {
                    throw new ExceptionCode(ExceptionCode::INVALID_SNAPSHOT);
                }
                $authority = array_merge($authority, $snapshot->authority);
            }
            /* Save */
            $object->authority = Authority::grant($object, $authority);
            $object->save();
        } else {
            throw new ModelNotFoundException('Query Authority: No query results for objects: Unknown object id \'' . $id . '\'.');
        }
        
        return $response->success();
    }

    /**
     * Remove Authority
     *
     * Remove the permissions to type object.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam type required License object type code Example: admin
     * @urlParam uid required Identify the object serial id Example: 6398211294583
     * @bodyParam interface ARR Managed APIs interface code Example: {interface}
     * @bodyParam snapshot ARR Authority snapshot id Example: {snapshot}
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param array $type
     * @param int $id
     * @param AuthorityUpdateRequest $request
     * @param AuthorityUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($type, $id, AuthorityUpdateRequest $request, AuthorityUpdateResponse $response)
    {
        /* Check operator */
        if (($user = TokenAuth::getUser()) && $type['class'] === get_class($user) && $user->id == $id) {
            throw new ExceptionCode(ExceptionCode::INOPERABLE_USER);
        }
        /* Select and save */
        if ($object = $type['class']::find($id)) {
            $interface = $request->input('interface');
            $snapshot = $request->input('snapshot');
            if (!isset($interface) && !isset($snapshot)) {
                throw new ExceptionCode(ExceptionCode::UNSPECIFIED_AUTHORITY);
            }
            $authority = [];
            if (isset($interface)) {
                $managed = InterfaceScope::managed();
                foreach ($interface as $code) {
                    if (! is_string($code) || ! isset($managed[$code])) {
                        throw new ExceptionCode(ExceptionCode::INVALID_INTERFACE_CODE, [
                            '%code%' => (is_array($code) ? 'Array' : $code)
                        ], [
                            '%code%' => (is_array($code) ? 'Array' : $code)
                        ]);
                    }
                }
                $authority = $interface;
            }
            /* Check snapshot */
            if (isset($snapshot)) {
                $snapshot = AuthoritySnapshot::find($snapshot);
                if (! $snapshot) {
                    throw new ExceptionCode(ExceptionCode::INVALID_SNAPSHOT);
                }
                $authority = array_merge($authority, $snapshot->authority);
            }
            /* Save */
            $object->authority = Authority::remove($object, $authority);
            $object->save();
        } else {
            throw new ModelNotFoundException('Query Authority: No query results for objects: Unknown object id \'' . $id . '\'.');
        }

        return $response->success();
    }
}