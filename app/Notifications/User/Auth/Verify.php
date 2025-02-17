<?php

namespace App\Notifications\User\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\Service\AuthExceptionCode;
use Exception;
use TokenAuth;
use StorageCode;
use Lang;

class Verify extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The letter subject code.
     *
     * @var string
     */
    protected $letter = 'AUTH_VERIFY_LETTER';

    /**
     * The auth time limit (minutes)
     *
     * @var int
     */
    protected $ttl;

    /**
     * The assign send email.
     *
     * @var string
     */
    protected $email;

    /**
     * The markdown blade.
     *
     * @var string
     */
    protected $blade = 'notices.mail.user.auth.verify';

    /**
     * Create a new notification instance.
     *
     * @param int $ttl
     * @return void
     * @throws \Exception
     */
    public function __construct(int $ttl = 5)
    {
        if ($ttl < 1) {
            throw new Exception('Assign TTL: TTL must be greater than or equal to 1.');
        }
        $this->ttl = $ttl;
    }

    /**
     * Set the verify code.
     *
     * @param  mixed  $notifiable
     * @return string
     * @throws \Exception
     */
    protected function setVerifyCode($notifiable): string
    {
        /* Save auth code */
        if ($code = StorageCode::fill(TokenAuth::getAuthGuard($notifiable) . ':' . $notifiable->email, $this->ttl)) {
            return $code;
        }
        throw new AuthExceptionCode(AuthExceptionCode::VERIFYCODE_CREATE_FAIL);
    }

    /**
     * Get the verify code.
     *
     * @param  mixed  $notifiable
     * @param string|null $email
     * @return int|null
     * @throws \Exception
     */
    public function getVerifyCode($notifiable, ?string $email = null): ?int
    {
        /* Set email */
        if (isset($email)) {
            $notifiable->email = $email;
        }
        if (filter_var($notifiable->email, FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception('Access E-Mail: E-Mail format error: Unknown e-mail.');
        }
        /* Model name */
        $model = get_class($notifiable);
        /* Get auth guard user */
        if (TokenAuth::model() !== $model && TokenAuth::getAuthGuard($model) && $notifiable->getJWTIdentifier()) {
            /* Get auth code */
            if ($storage = StorageCode::get(TokenAuth::getAuthGuard($model) . ':' . $notifiable->email)) {
                return $storage;
            }
            return null;
        } else {
            throw new ModelNotFoundException('Query Auth: No query results for guards: Unknown user auth model.');
        }
    }

    /**
     * Forget the verify code.
     *
     * @param  mixed  $notifiable
     * @param string|null $email
     * @return bool
     * @throws \Exception
     */
    public function forgetVerifyCode($notifiable, ?string $email = null): bool
    {
        /* Set email */
        if (isset($email)) {
            $notifiable->email = $email;
        }
        if (filter_var($notifiable->email, FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception('Access E-Mail: E-Mail format error: Unknown e-mail.');
        }
        /* Model name */
        $model = get_class($notifiable);
        /* Get auth guard user */
        if (TokenAuth::model() !== $model && TokenAuth::getAuthGuard($model) && $notifiable->getJWTIdentifier()) {
            /* Forget auth code */
            return StorageCode::forget(TokenAuth::getAuthGuard($model) . ':' . $notifiable->email);
        } else {
            throw new ModelNotFoundException('Query Auth: No query results for guards: Unknown user auth model.');
        }
    }

    /**
     * Assign the blade template.
     *
     * @param string $blade
     * @return object
     */
    public function assignBlade(string $blade)
    {
        if (!isset($blade[0])) {
            throw new Exception('Assign Blade: Blade template is empty: Unknown blade template.');
        }
        $this->blade = $blade;
        return $this;
    }

    /**
     * Assign the send email.
     *
     * @param string $email
     * @return object
     */
    public function assignMail(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception('Assign E-Mail: E-Mail format error: Unknown e-mail.');
        }
        $this->email = $email;
        return $this;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     * @throws \Exception
     */
    public function toMail($notifiable)
    {
        /* Set email */
        if (isset($this->email)) {
            $notifiable->email = $this->email;
        }
        if (filter_var($notifiable->email, FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception('Access E-Mail: E-Mail format error: Unknown e-mail.');
        }
        /* Model name */
        $model = get_class($notifiable);
        /* Get auth guard user */
        if (TokenAuth::model() !== $model && TokenAuth::getAuthGuard($model) && $notifiable->getJWTIdentifier()) {
            // Need notifiable user email colunm to send
            return (new MailMessage)->subject(Lang::dict('mail', 'subject.' . $this->letter, 'Authorization Verification Letter'))
            ->markdown($this->blade, [
                'TITLE' => Lang::dict('mail', 'blade.' . $this->letter . '.TITLE', 'Notification'),
                'TOP_CONTENT' => Lang::dict('mail', 'blade.' . $this->letter . '.TOP_CONTENT', 'This is an authorized verification code letter!'),
                'BODY_CONTENT' => Lang::dict('mail', 'blade.' . $this->letter . '.BODY_CONTENT', 'Verification Code : :code', ['code' => $this->setVerifyCode($notifiable)]),
                'BOTTOM_CONTENT' => Lang::dict('mail', 'blade.' . $this->letter . '.BOTTOM_CONTENT', 'Please use the verification code within :ttl minutes and enter it in the requested service.', ['ttl' => $this->ttl])
            ]);
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
