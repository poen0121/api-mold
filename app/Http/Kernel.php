<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            'bindings',
            'accept.language',
            'accept.timezone',
            'accept.mime',
            'accept.response',
            'record.access',
        ],

        'api' => [
            'bindings',
            'accept.language',
            'accept.timezone',
            'accept.mime',
            'accept.response',
            'record.access',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'request.feature.code' => \App\Http\Middleware\Request\Feature\Code::class,
        'request.system.log.type' => \App\Http\Middleware\Request\System\Log\Type::class,
        'request.system.authority.type' => \App\Http\Middleware\Request\System\Authority\Type::class,
        'request.system.authority.typeuid' => \App\Http\Middleware\Request\System\Authority\TypeUid::class,
        'request.system.parameter.slug' => \App\Http\Middleware\Request\System\Parameter\Slug::class,
        'request.notice.bulletin.label' => \App\Http\Middleware\Request\Notice\Bulletin\Label::class,
        'request.notice.bulletin.type' => \App\Http\Middleware\Request\Notice\Bulletin\Type::class,
        'request.notice.letter.target' => \App\Http\Middleware\Request\Notice\Letter\AuthTarget::class,
        'request.client.appid' => \App\Http\Middleware\Request\Client\AppId::class,
        'request.user.type' => \App\Http\Middleware\Request\User\Type::class,
        'record.access' => \App\Http\Middleware\Record\Access::class,
        'accept.mime' => \App\Http\Middleware\Accept\MimeType::class,
        'accept.response' => \App\Http\Middleware\Accept\ResponseType::class,
        'accept.timezone' => \App\Http\Middleware\Accept\Timezone::class,
        'accept.language' => \App\Http\Middleware\Accept\Language::class,
        'accept.bantoken' =>  \App\Http\Middleware\Accept\BanToken::class,
        'token.auth' =>  \App\Http\Middleware\Auth\Token\VerifyAuth::class,
        'token.revoke' => \App\Http\Middleware\Auth\Token\VerifyAuthRevoke::class,
        'token.refresh' => \App\Http\Middleware\Auth\Token\VerifyAuthRefresh::class,
        'token.ban' =>  \App\Http\Middleware\Auth\Token\VerifyAuthBan::class,
        'token.login' =>  \App\Http\Middleware\Auth\Token\VerifyAuthLogin::class,
        'token.logout' =>  \App\Http\Middleware\Auth\Token\VerifyAuthLogout::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        // 'throttle' => \App\Http\Middleware\Upgrades\BetterThrottleRequests::class,
        'throttle' => \App\Http\Middleware\Upgrades\BetterThrottleRequestsWithRedis::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces the listed middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
