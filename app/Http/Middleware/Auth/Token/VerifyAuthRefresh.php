<?php

namespace App\Http\Middleware\Auth\Token;

use Closure;
use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;
use TokenAuth;

class VerifyAuthRefresh
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* Get auth token guard */
        if (! $guard = TokenAuth::getAuthGuard()) {
            throw new ExceptionCode(ExceptionCode::AUTH_FAIL);
        } else {
            /* Set the guard api as default driver */
            auth()->setDefaultDriver($guard);
        }
        /* Check auth user */
        if (! auth()->check()) {
            if ($payload = TokenAuth::getAuthPayload()) {
                /* Get black info */
                $jwtBlack = app('tymon.jwt.provider.storage')->get(TokenAuth::jwt()->blacklist()->getKey(TokenAuth::jwt()->factory()->setRefreshFlow(true)->customClaims($payload)->make()));
                if (! isset($jwtBlack)) {
                    /* Verify auth token and refresh extended auth token */
                    if ($token = TokenAuth::refresh()) {
                        /* Set response refresh authorization token */
                        define('REFRESH_TOKEN_BY_EXCEPTION', $token);
                    }
                } elseif ($jwtBlack === 'forever' || (isset($jwtBlack['valid_until']) && $jwtBlack['valid_until'] <= time())) {
                    throw new ExceptionCode(ExceptionCode::AUTH_FAIL);
                }
            } else {
                throw new ExceptionCode(ExceptionCode::AUTH_FAIL);
            }
        }
        /* Verify auth client */
        if ($client = TokenAuth::getClient()) {
            /* Check auth client status */
            $client->verifyHoldStatusOnFail();
        } else {
            throw new ExceptionCode(ExceptionCode::AUTH_FAIL);
        }
        /* Verify auth user */
        if (! TokenAuth::isClientGuard()) {
            if ($user = TokenAuth::getUser()) {
                /* Check auth user status */
                $user->verifyHoldStatusOnFail();
            } else {
                throw new ExceptionCode(ExceptionCode::USER_AUTH_FAIL);
            }
        }

        return $next($request);
    }
}
