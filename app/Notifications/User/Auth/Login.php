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
use Lang;

class Login extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The letter subject code.
     *
     * @var string
     */
    protected $letter = 'AUTH_LOGIN_LETTER';

    /**
     * The assign send email.
     *
     * @var string
     */
    protected $email;

    /**
     * The auth redirect url.
     *
     * @var string
     */
    protected $redirect;

    /**
     * The redirect auth query variable.
     *
     * @var string
     */
    protected $queryVar = 'auth';

    /**
     * The markdown blade.
     *
     * @var string
     */
    protected $blade = 'notices.mail.user.auth.login';

    /**
     * Create a new notification instance.
     *
     * @param string $redirect
     * @return void
     * @throws \Exception
     */
    public function __construct(string $redirect)
    {
        if (filter_var($redirect, FILTER_VALIDATE_URL) === false) {
            throw new Exception('Assign Redirect: Redirect URL format error: Unknown redirect URL.');
        }
        $this->redirect = $redirect;
    }

    /**
     * Get the redirect URL.
     *
     * @param  mixed  $notifiable
     * @return string
     * @throws \Exception
     */
    protected function getRedirectUrl($notifiable): string
    {
        /* Save auth unique code */
        if ($code = TokenAuth::injectUserSignature($notifiable, config('auth.muts_ttl', 60))) {
            $query = parse_url($this->redirect, PHP_URL_QUERY);
            $fragment = parse_url($this->redirect, PHP_URL_FRAGMENT);
            /* Get the clear item */
            $clear = [];
            if (isset($fragment)) {
                $clear['#' . $fragment] = '';
            }
            if (isset($query)) {
                $clear['?' . $query] = '';
            }
            /* Get the base uri */
            $baseUri = strtr($this->redirect, $clear);
            if (isset($query)) {
                parse_str(html_entity_decode($query), $qs);
                $query = array_merge($qs, [
                    $this->queryVar => $code
                ]);
            } else {
                $query = [
                    $this->queryVar => $code
                ];
            }
            return $baseUri . '?' . http_build_query($query, '', '&') . (isset($fragment) ? '#' . $fragment : '');
        }
        throw new AuthExceptionCode(AuthExceptionCode::SIGNATURE_CREATE_FAIL);
    }

    /**
     * Assign the uri query var name.
     *
     * @param string $queryVar
     * @return object
     */
    public function assignVar(string $queryVar)
    {
        if (!isset($queryVar[0])) {
            throw new Exception('Assign Variable: Variable name is empty: Unknown variable name.');
        }
        $this->queryVar = $queryVar;
        return $this;
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
            return (new MailMessage)->subject(Lang::dict('mail', 'subject.' . $this->letter, 'Authorization Login Letter'))
            ->markdown($this->blade, [
                'URL' => $this->getRedirectUrl($notifiable),
                'TITLE' => Lang::dict('mail', 'blade.' . $this->letter . '.TITLE', 'Notification'),
                'TOP_CONTENT' => Lang::dict('mail', 'blade.' . $this->letter . '.TOP_CONTENT', 'This is an authorized login service letter!'),
                'BUTTON_NAME' => Lang::dict('mail', 'blade.' . $this->letter . '.BUTTON_NAME', 'Login Service'),
                'BOTTOM_CONTENT' => Lang::dict('mail', 'blade.' . $this->letter . '.BOTTOM_CONTENT', 'Please click the login button to enter the service within :ttl minutes.', ['ttl' => config('auth.muts_ttl', 60)])
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
