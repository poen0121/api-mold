<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Exceptions\Sms\OperateExceptionCode as ExceptionCode;
use App\Entities\Sms\Operate;
use Exception;
use Carbon;

class SmsChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        /* Check sourceable model */
        if (! app(Operate::class)->isSmsSourceableAllowed($notifiable)) {
            throw new ExceptionCode(ExceptionCode::UNAUTHORIZED_OPERATION);
        }
        /* Check data */
        if (! isset($notification->phone, $notification->message, $notification->subject)) {
            throw new ExceptionCode(ExceptionCode::MISS_DATA, [], [
                '%telecomer%' => get_class($notification)
            ]);
        }
        /* Check response trait */
        if (! in_array('App\Libraries\Traits\Info\Format\Response', class_uses($notification))) {
            throw new ExceptionCode(ExceptionCode::UNKNOWN_RESPONSE_TRAIT, [], [
                '%telecomer%' => get_class($notification)
            ]);
        }
        /* Send */
        if (method_exists($notification, 'toSms')) {
            $result = $notification->toSms($notifiable);
            if (isset($result['success'], $result['data']) && count($result) == 2 && is_bool($result['success']) && is_array($result['data'])) {
                /* Check success */
                $operate = ($result['success'] === true ? 'success' : 'failure');
            } else {
                /* Unformatted */
                $result = $notification->success([
                    'error_operate' => 'Unformatted',
                    'message' => $result
                ]);
                $operate = 'unformatted';
            }
        } else {
            throw new ExceptionCode(ExceptionCode::FUNCTION_UNIMPLEMENTED, [], [
                '%telecomer%' => get_class($notification)
            ]);
        }
        /* Save log */
        if (config('sms.logger')) {
            /* Save */
            $now = Carbon::now();
            Operate::create([
                'sourceable_type' => get_class($notifiable),
                'sourceable_id' => $notifiable->id,
                'via' => get_class($notification),
                'notify_phone' => $notification->phone,
                'notify_message' => [
                    'subject' => $notification->subject,
                    'message' => $notification->message
                ],
                'notify_result' => $result,
                'operate' => ($operate === 'unformatted' ? 'success' : $operate),
                'created_at' => $now,
                'updated_at' => $now,
                'month' =>  (int) $now->format('m')
            ]);
        }
        /* Send error */
        if ($operate === 'failure') {
            throw new ExceptionCode(ExceptionCode::SMS_SEND_FAILED, [], [
                '%telecomer%' => get_class($notification)
            ]);
        }
        if (config('queue.default') === 'sync' && $operate === 'unformatted') {
            throw new ExceptionCode(ExceptionCode::DATA_FORMAT_ERROR, [], [
                '%telecomer%' => get_class($notification)
            ]);
        }
    }
}