<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Message\NoticeCreateRequest;
use App\Http\Requests\Message\NoticeUpdateRequest;
use App\Http\Responses\Message\NoticeCreateResponse;
use App\Http\Responses\Message\NoticeUpdateResponse;
use App\Exceptions\Message\NoticeExceptionCode as ExceptionCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\Service\AuthExceptionCode;
use App\Transformers\Message\NoticeTransformer;
use App\Libraries\Instances\Notice\Bulletin;
use App\Libraries\Instances\Notice\LetterType;
use App\Notifications\User\Message\Letter;
use TokenAuth;

/**
 * @group
 *
 * User Notify
 *
 * @package namespace App\Http\Controllers\Message;
 */
class NoticeController extends Controller
{
    /**
     * NoticeController constructor.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Letters Available User Types
     *
     * Get a list of user types available for notification letters.
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
     * @param NoticeCreateRequest $request
     * @param NoticeCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userTypes(NoticeCreateRequest $request, NoticeCreateResponse $response)
    {
        /* Check token guard */
        if ($user = TokenAuth::getUser()) {
            $types = LetterType::heldUserTypes($user, [
                'type',
                'description'
            ]);
            return $response->success([
                'data' => array_values($types)
            ]);
        }
        throw new AuthExceptionCode(AuthExceptionCode::USER_AUTH_FAIL);
    }

    /**
     * Send Message Letter
     *
     * Send a message to the specified object.
     *
     * ### Response Body
     *
     * success : true
     *
     * @urlParam type required User type code Example: member
     * @urlParam uid required User serial id Example: 1294583
     * @bodyParam subject STR required Letter subject Example: {subject}
     * @bodyParam message STR required Letter message Example: {message}
     * @bodyParam note ARR,OBJ Letter note Example: {note}
     *
     * @response
     * {
     *    "success": true
     * }
     *
     * @param array $type
     * @param int $id
     * @param NoticeCreateRequest $request
     * @param NoticeCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage($type, $id, NoticeCreateRequest $request, NoticeCreateResponse $response)
    {
        /* Check token guard */
        if ($sender = TokenAuth::getUser()) {
            $subject = $request->input('subject');
            $message = $request->input('message');
            $note = $request->input('note');
            /* Check user */
            if ($user = app($type['class'])->find($id)) {
                /* Check object type id */
                if (get_class($sender) === get_class($user) && $sender->id === $user->id) {
                    throw new ExceptionCode(ExceptionCode::DISALLOWED_SEND_OBJECT);
                }
                /* Check auth user status */
                try {
                    $user->verifyHoldStatusOnFail();
                } catch (\Throwable $th) {
                    if (in_array('App\\Libraries\\Abstracts\\Base\\ExceptionCode', class_parents($th))) {
                        throw new ExceptionCode(ExceptionCode::DISALLOWED_SEND_OBJECT);
                    } else {
                        throw $th;
                    }
                }
                /* Send message */
                $user->notify(new Letter($sender, $subject, $message, (isset($note) ? $note : [])));
                return $response->success();
            }
            throw new ModelNotFoundException('No query results for object model [' . $type['class'] . '] ');
        }
        throw new AuthExceptionCode(AuthExceptionCode::USER_AUTH_FAIL);
    }

    /**
    * My Notify Messages
    *
    * Get the messages of the user notification.
    *
    * ### Response Body
    *
    * success : true
    *
    * data :
    *
    * Parameter | Type | Description
    * --------- | ------- | ------- | -----------
    * id | STR | Notice serial id
    * notice.subject | OBJ | Notice message subject
    * notice.content | OBJ | Notice message content object
    * notice.type | STR | Notice message type code
    * notice.type_name | STR | Notice message type name
    * read_at | STR | Datetime when the notice was read
    * created_at | STR | Datetime when the notice was created
    *
    * @urlParam mark Mark read status value is ( read ) Example: read
    *
    * @response
    * {
    *    "success": true,
    *    "data": [
    *        {
    *            "id": "ddc09c60-8385-4e64-8516-6ba3f6fd6a64",
    *            "notice": {
    *                  "subject": "Test",
    *                  "content": {
    *                        "message": "Test message"
    *                  },
    *                  "type": "none",
    *                  "type_name": "Notice"
    *            },
    *            "read_at": "2020-04-16 11:04:19",
    *            "created_at": "2020-04-15 14:02:47"
    *        }
    *    ]
    * }
    *
    * @param string|null $mark
    * @param NoticeCreateRequest $request
    * @param NoticeCreateResponse $response
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function messages($mark = null, NoticeCreateRequest $request, NoticeCreateResponse $response)
    {
        /* Check token guard */
        if ($user = TokenAuth::getUser()) {
            $source = [];
            if (in_array('Illuminate\Notifications\Notifiable', class_uses($user))) {
                Bulletin::capture($user);
                /* Self notifications */
                $all = $user->notifications;
                /* Only unread set read_at */
                if ($mark === 'read') {
                    $all->markAsRead();
                }
                /* Get Source */
                $transformer = app(NoticeTransformer::class);
                $source = collect($all)->map(function ($notification) use ($user, $transformer) {
                    return $transformer->transform($notification);
                })->all();
            }
            /* Return notification messages */
            return $response->success([
                'data' => $source
            ]);
        }
        throw new AuthExceptionCode(AuthExceptionCode::USER_AUTH_FAIL);
    }

    /**
    * My Unread Notify Messages
    *
    * Get the messages of the user unread notification.
    *
    * ### Response Body
    *
    * success : true
    *
    * data :
    *
    * Parameter | Type | Description
    * --------- | ------- | ------- | -----------
    * id | STR | Notice serial id
    * notice.subject | OBJ | Notice message subject
    * notice.content | OBJ | Notice message content object
    * notice.type | STR | Notice message type code
    * notice.type_name | STR | Notice message type name
    * read_at | STR | Datetime when the notice was read
    * created_at | STR | Datetime when the notice was created
    *
    * @urlParam mark Mark read status value is ( read ) Example: read
    *
    * @response
    * {
    *    "success": true,
    *    "data": [
    *        {
    *            "id": "ddc09c60-8385-4e64-8516-6ba3f6fd6a64",
    *            "notice": {
    *                  "subject": "Test",
    *                  "content": {
    *                        "message": "Test message"
    *                  },
    *                  "type": "none",
    *                  "type_name": "Notice"
    *            },
    *            "read_at": "2020-04-16 11:04:19",
    *            "created_at": "2020-04-15 14:02:47"
    *        }
    *    ]
    * }
    *
    * @param string|null $mark
    * @param NoticeCreateRequest $request
    * @param NoticeCreateResponse $response
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function unreadMessages($mark = null, NoticeCreateRequest $request, NoticeCreateResponse $response)
    {
        /* Check token guard */
        if ($user = TokenAuth::getUser()) {
            $source = [];
            if (in_array('Illuminate\Notifications\Notifiable', class_uses($user))) {
                Bulletin::capture($user);
                $unread = $user->unreadNotifications;
                /* Only unread set read_at */
                if ($mark === 'read') {
                    $unread->markAsRead();
                }
                /* Get Source */
                $transformer = app(NoticeTransformer::class);
                $source = collect($unread)->map(function ($notification) use ($user, $transformer) {
                    return $transformer->transform($notification);
                })->all();
            }
            /* Return notification messages */
            return $response->success([
                'data' => $source
            ]);
        }
        throw new AuthExceptionCode(AuthExceptionCode::USER_AUTH_FAIL);
    }

    /**
     * My Unread Notify Counts
     *
     * Get the counts of the user unread notification.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * count | INT | Unread count
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *       "count": 1
     *    }
     * }
     *
     * @param NoticeCreateRequest $request
     * @param NoticeCreateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unreadCounts(NoticeCreateRequest $request, NoticeCreateResponse $response)
    {
        /* Check token guard */
        if ($user = TokenAuth::getUser()) {
            $count = 0;
            if (in_array('Illuminate\Notifications\Notifiable', class_uses($user))) {
                Bulletin::capture($user);
                /* Count unread */
                $count = count($user->unreadNotifications);
            }
            /* Return notification unread count */
            return $response->success([
                'count' => $count
            ]);
        }
        throw new AuthExceptionCode(AuthExceptionCode::USER_AUTH_FAIL);
    }

    /**
     * Mark Read
     *
     * Mark the notification messages as read by the user.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * count | INT | Mark success count
     *
     * @bodyParam id ARR required Notice serial id Example: {id}
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *       "count": 1
     *    }
     * }
     *
     * @param NoticeUpdateRequest $request
     * @param NoticeUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markRead(NoticeUpdateRequest $request, NoticeUpdateResponse $response)
    {
        /* Check token guard */
        if ($user = TokenAuth::getUser()) {
            $count = 0;
            if (in_array('Illuminate\Notifications\Notifiable', class_uses($user))) {
                /* Check id format */
                $ids = $request->input('id');
                foreach ($ids as $id) {
                    if (! is_string($id) || ! preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $id)) {
                        throw new ExceptionCode(ExceptionCode::INVALID_NOTIFICATION_ID, [
                            '%id%' => (is_array($id) ? 'Array' : $id)
                        ], [
                            '%id%' => (is_array($id) ? 'Array' : $id)
                        ]);
                    }
                }
                /* Get the unread notification */
                if ($unread = $user->notifications()->whereNull('read_at')->whereIn('id', array_unique($ids))->get()) {
                    /* Count unread */
                    $count = count($unread);
                    /* Only unread set read_at */
                    $unread->markAsRead();
                }
            }
            /* Return notification unread count */
            return $response->success([
                'count' => $count
            ]);
        }
        throw new AuthExceptionCode(AuthExceptionCode::USER_AUTH_FAIL);
    }

    /**
     * Remove Messages
     *
     * Remove the messages of the user notification.
     *
     * ### Response Body
     *
     * success : true
     *
     * data :
     *
     * Parameter | Type | Description
     * --------- | ------- | ------- | -----------
     * count | INT | Remove success count
     *
     * @bodyParam id ARR required Notice serial id Example: {id}
     *
     * @response
     * {
     *    "success": true,
     *    "data": {
     *       "count": 1
     *    }
     * }
     *
     * @param NoticeUpdateRequest $request
     * @param NoticeUpdateResponse $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(NoticeUpdateRequest $request, NoticeUpdateResponse $response)
    {
        /* Check token guard */
        if ($user = TokenAuth::getUser()) {
            $count = 0;
            if (in_array('Illuminate\Notifications\Notifiable', class_uses($user))) {
                /* Check id format */
                $ids = $request->input('id');
                foreach ($ids as $id) {
                    if (! is_string($id) || ! preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $id)) {
                        throw new ExceptionCode(ExceptionCode::INVALID_NOTIFICATION_ID, [
                            '%id%' => (is_array($id) ? 'Array' : $id)
                        ], [
                            '%id%' => (is_array($id) ? 'Array' : $id)
                        ]);
                    }
                }
                /* Delete notifications */
                $count = $user->notifications()->whereIn('id', array_unique($ids))->delete();
            }
            /* Return notification deletion count */
            return $response->success([
                'count' => $count
            ]);
        }
        throw new AuthExceptionCode(AuthExceptionCode::USER_AUTH_FAIL);
    }
}
