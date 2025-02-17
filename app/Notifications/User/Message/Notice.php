<?php

namespace App\Notifications\User\Message;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use TokenAuth;

class Notice extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The message.
     *
     * @var array
     */
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @param string $subject
     * @param array $content
     * @param string $type
     * @return void
     */
    public function __construct(string $subject, array $content, string $type = 'none')
    {
        $this->message = [
            'subject' => $subject,
            'content' => $content,
            'type' => $type
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
      * Get the database representation of the notification.
      *
      * @param  mixed  $notifiable
      * @return array
      * @throws \Exception
      */
    public function toDatabase($notifiable)
    {
        /* Model name */
        $model = get_class($notifiable);
        /* Get auth guard user */
        if (TokenAuth::model() !== $model && TokenAuth::getAuthGuard($model) && $notifiable->getJWTIdentifier()) {
            return $this->message;
        } else {
            throw new ModelNotFoundException('Query Auth: No query results for guards: Unknown user auth model.');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
