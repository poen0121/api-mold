<?php

namespace App\Http\Middleware\Auth\Token;

use Closure;
use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;
use App\Exceptions\System\AuthorityExceptionCode;
use App\Libraries\Instances\System\InterfaceScope;
use TokenAuth;

class VerifyAuthBan
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* Verify auth client */
        if ($client = TokenAuth::getClient()) {
            /* Check release by client ban number id */
            $release = config('ban.release.' . $client->ban);
            /* Check release */
            if (! isset($release)) {
                throw new ExceptionCode(ExceptionCode::NO_PERMISSION);
            }
            /* Check route scope */
            if ($routeName = $request->route()->getName()) {
                $routeNames = explode('.', $routeName);
                $allowRouteName = false;
                $allowNamed = (isset($release['allow_named']) ? $release['allow_named'] : []);
                if (is_array($allowNamed) && count($allowNamed) > 0) {
                    $allowRouteName = true;
                    if (! in_array($routeName, $allowNamed, true) && ! in_array('*', $allowNamed)) {
                        $allowRouteName = false;
                        $routeNamePart = '';
                        foreach ($routeNames as $namePart) {
                            $routeNamePart .= $namePart . '.';
                            if (in_array($routeNamePart . '*', $allowNamed)) {
                                $allowRouteName = true;
                                break;
                            }
                        }
                    }
                }
                if (! $allowRouteName) {
                    throw new ExceptionCode(ExceptionCode::NO_PERMISSION);
                }
                $unallowRouteName = false;
                $unallowNamed = (isset($release['unallow_named']) ? $release['unallow_named'] : []);
                if (is_array($unallowNamed) && count($unallowNamed) > 0) {
                    $unallowRouteName = true;
                    if (! in_array($routeName, $unallowNamed, true) && ! in_array('*', $unallowNamed)) {
                        $unallowRouteName = false;
                        $routeNamePart = '';
                        foreach ($routeNames as $namePart) {
                            $routeNamePart .= $namePart . '.';
                            if (in_array($routeNamePart . '*', $unallowNamed)) {
                                $unallowRouteName = true;
                                break;
                            }
                        }
                    }
                }
                if ($unallowRouteName) {
                    throw new ExceptionCode(ExceptionCode::NO_PERMISSION);
                }
            } else {
                throw new ExceptionCode(ExceptionCode::NO_PERMISSION);
            }
            /* Verify client authority trait */
            if (in_array('App\Libraries\Traits\Entity\Column\Authority', class_uses($client))) {
                /* Managed source by ban */
                $managedAuthority = array_keys(InterfaceScope::allowedByban($client->ban));
                /* Format source codes */
                $authority = array_intersect($managedAuthority, $client->authority);
                /* Check source */
                if (! in_array($routeName, $authority, true)) {
                    throw new AuthorityExceptionCode(AuthorityExceptionCode::NO_PERMISSION);
                }
            }
            /* Get access guards */
            $accessGuards = (isset($release['restrict_access_guards']) && is_array($release['restrict_access_guards']) ? $release['restrict_access_guards'] : []);
            /* Get correct access guards */
            $accessGuards = (count($accessGuards) > 0 ? array_intersect(array_keys(TokenAuth::getGuardModels()), $accessGuards) : $accessGuards);
            /* Check access guards */
            if (count($accessGuards) > 0 && ! in_array('token.login', $request->route()->middleware())) {
                /* Public api route names */
                $publicRouteNames = config('ban.ignore_restrict_access', []);
                /* Check restrict guards */
                if (! in_array(TokenAuth::getAuthGuard(), $accessGuards, true) && ! in_array($routeName, $publicRouteNames, true)) {
                    throw new ExceptionCode(ExceptionCode::NO_PERMISSION);
                }
            }
            /* Check auth user */
            if ($user = TokenAuth::getUser()) {
                /* Verify user authority trait */
                if (in_array('App\Libraries\Traits\Entity\Column\Authority', class_uses($user))) {
                    /* Managed source by ban */
                    $managedAuthority = (! isset($managedAuthority) ? array_keys(InterfaceScope::allowedByban($client->ban)) : $managedAuthority);
                    /* Format source codes */
                    $authority = array_intersect($managedAuthority, $user->authority);
                    /* Check source */
                    if (! in_array($routeName, $authority, true)) {
                        throw new AuthorityExceptionCode(AuthorityExceptionCode::NO_PERMISSION);
                    }
                }
            }
        } else {
            throw new ExceptionCode(ExceptionCode::AUTH_FAIL);
        }
        
        return $next($request);
    }
}