<?php

namespace App\Libraries\Instances\Token;

use App\Entities\Service\Auth as Client;
use StorageSign;
use Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lang;
use Exception;
use Cache;
use Carbon;

/**
 * Final Class Auth.
 *
 * @package namespace App\Libraries\Instances\Token;
 */
final class Auth
{
    /**
     * The cache key prefix name.
     *
     * @var string
     */
    private static $prefix = 'jwt:m:';

    /**
     * The guard cache key flags list.
     *
     * @var array
     */
    private static $guardCacheFlags;

    /**
     * JWT-Auth
     *
     * @var \Tymon\JWTAuth\JWT
     */
    private static $jwt;

    /**
     * JWT-Auth Lcobucci
     *
     * @var \Tymon\JWTAuth\Providers\JWT\Lcobucci
     */
    private static $signer;

    /**
     * Auth guard models list
     *
     * @var array
     */
    private static $guardModels;

    /**
     * Auth guard models type number id list
     *
     * @var array
     */
    private static $agtModels;

    /**
     * Auth guard payload
     *
     * @var array
     */
    private static $payload;

    /**
     * The types columns list.
     *
     * @var array
     */
    private static $columns = [
        'class',
        'type',
        'description'
    ];

    /**
     * The user types list.
     *
     * @var array
     */
    private static $userTypes;

    /**
     * The auth token.
     *
     * @var string
     */
    private static $token;

    /**
     * The auth guard.
     *
     * @var string
     */
    private static $guard;

    /**
     * The client service object.
     *
     * @var \Illuminate\Foundation\Auth\User
     */
    private static $client;

    /**
     * The user object.
     *
     * @var \Illuminate\Foundation\Auth\User
     */
    private static $user;

    /**
     * Auth initialization.
     *
     * @return void
     */
    private static function init()
    {
        if (!isset(self::$jwt)) {
            self::$jwt = app('tymon.jwt');
            self::$signer = app('tymon.jwt.provider.jwt.lcobucci');
            /* Get auth guard models */
            $guards = (array) config('auth.guards');
            $models = [];
            self::$guardModels = collect($guards)->map(function ($item, $key) use (&$models) {
                if ($item['driver'] === 'jwt') {
                    $model = config('auth.providers.' . $item['provider'] . '.model');
                    if (get_parent_class($model) === 'Illuminate\Foundation\Auth\User' && in_array('App\Libraries\Traits\Entity\Auth\JWT', class_uses($model)) && ! in_array($model, $models)) {
                        $models[] = $model;
                        self::$guardCacheFlags[$key] = sha1(serialize(app($model)));
                        return $model;
                    }
                }
                return null;
            })->reject(function ($item) {
                return !isset($item);
            })->all();
            /* Agt models */
            self::$agtModels = array_values(self::$guardModels);
        }
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public static function model()
    {
        return Client::class;
    }

    /**
     * Get the cache key
     *
     * @param string $id
     * @param string $guard
     *
     * @return string
     */
    public static function getCacheKey(string $id, string $guard): string
    {
        self::init();
        $flag = (isset(self::$guardCacheFlags[$guard]) ? ':' . self::$guardCacheFlags[$guard] . ':' : ':');
        return self::$prefix . $guard . $flag . $id;
    }

    /**
     * Get the JWT object.
     *
     * @return \Tymon\JWTAuth\JWT
     */
    public static function jwt()
    {
        self::init();
        return self::$jwt;
    }

    /**
     * Get the authentication guard models.
     *
     * @return array
     */
    public static function getGuardModels(): array
    {
        self::init();
        return self::$guardModels;
    }

    /**
     * Get the authentication guard model name.
     *
     * @param string $guard
     *
     * @return string|null
     */
    public static function getGuardModel(string $guard): ?string
    {
        return (isset(self::getGuardModels()[$guard]) ? self::getGuardModels()[$guard] : null);
    }

    /**
     * Get the authentication agt models.
     *
     * @return array
     */
    public static function getAgtModels(): array
    {
        self::init();
        return self::$agtModels;
    }

    /**
     * Get the authentication agt model name.
     *
     * @param int $agt
     *
     * @return string|null
     */
    public static function getAgtModel(int $agt): ?string
    {
        return (isset(self::getAgtModels()[$agt]) ? self::getAgtModels()[$agt] : null);
    }

    /**
     * Get the user guard type list.
     *
     * @param array $column
     * column string : class
     * column string : type
     * column string : description
     * @param string|null $guard
     *
     * @return array
     * @throws \Exception
     */
    public static function userTypes(array $column = [], ?string $guard = null): array
    {
        /* Use column */
        if (count($column) > 0) {
            $diff = array_unique(array_diff($column, self::$columns));
            /* Check column name */
            if (count($diff) > 0) {
                throw new Exception('Query Auth: Column not found: Unknown column ( \'' . implode('\', \'', $diff) . '\' ) in \'field list\'.');
            }
        }
        /* Build cache reset description */
        if (!isset(self::$userTypes)) {
            $guards = self::getGuardModels();

            self::$userTypes = collect($guards)->map(function ($item, $key) {
                if ($item !== self::model()) {
                    return [
                        'class' => $item,
                        'type' => $key,
                        'description' => Lang::dict('auth', 'guards.' . $key, 'Undefined')
                    ];
                }
                return null;
            })->reject(function ($item) {
                return !isset($item);
            })->all();
        }
        /* Return result */
        if (is_null($guard)) {
            $types = self::$userTypes;
            if (count($column) > 0) {
                /* Forget column */
                $forget = array_diff(self::$columns, $column);
                /* Get result */
                $types = collect($types)->map(function ($item) use ($forget) {
                    return collect($item)->forget($forget)->all();
                })->all();
            }
        } else {
            /* Get type */
            if (isset(self::$userTypes[$guard])) {
                $types = self::$userTypes[$guard];
                if (count($column) > 0) {
                    /* Forget column */
                    $forget = array_diff(self::$columns, $column);
                    /* Get result */
                    $types = collect($types)->forget($forget)->all();
                }
            } else {
                throw new ModelNotFoundException('Query Auth: No query results for guards: Unknown type \'' . $guard . '\'.');
            }
        }

        return $types;
    }

    /**
     * Get the authentication guard name of the token or auth model.
     *
     * @param mixed $abstract
     *
     * @return string|null
     */
    public static function getAuthGuard($abstract = null): ?string
    {
        if (!isset($abstract)) {
            /* Get auth token */
            if ($token = self::jwt()->getToken()) {
                /* Token string */
                $token = $token->get();
                if ($token !== self::$token) {
                    /* Cache token */
                    self::$token = $token;
                    /* Get auth payload and check token */
                    self::$payload = self::$signer->decode($token);
                    /* Get auth model name */
                    $model = self::getAgtModel(self::$payload['agt']);
                    /* Get auth guard name */
                    self::$guard = (isset($model) ? array_search($model, self::getGuardModels()) : null);
                    self::$guard = (is_string(self::$guard) ? self::$guard : null);
                    /* Check payload prv */
                    self::$guard = (isset(self::$guard, self::$payload['prv']) && self::$payload['prv'] !== sha1($model) ? null : self::$guard);
                    /* Check the guard jwt ttl */
                    if (isset(self::$guard)) {
                        /* Get the guard jwt ttl */
                        $config = config('auth.guards.' . self::$guard);
                        $ttl = (array_key_exists('jwt_ttl', $config) ? $config['jwt_ttl'] : config('jwt.ttl'));
                        /* Check auth guard exp mode */
                        self::$guard = (($ttl === null && isset(self::$payload['exp'])) || ($ttl !== null && !isset(self::$payload['exp'])) ? null : self::$guard);
                        /* Check auth model target */
                        if (isset(self::$guard)) {
                            /* Get client auth guard name */
                            $guard = self::getAuthGuard(self::model());
                            /* Client auth model target */
                            $expires = Carbon::now()->addMinutes(self::getTTL(self::model()) ?: config('jwt.ttl', 60));
                            self::$client = Cache::remember(self::getCacheKey(self::$payload['shi'], $guard), $expires, function () {
                                return self::model()::find(self::$payload['shi']);
                            });
                            /* Check client unique auth */
                            if (self::$client) {
                                /* Release by client ban number id */
                                $release = config('ban.release.' . self::$client->ban);
                                /* Unique auth ignore guards */
                                $ignoreGuards = (isset($release['unique_auth_ignore_guards']) && is_array($release['unique_auth_ignore_guards']) ? $release['unique_auth_ignore_guards'] : []);
                                /* Check unique auth uuid */
                                if (!in_array($guard, $ignoreGuards, true) && in_array('unique_auth', self::$client->getFillable()) && self::$payload['cuk'] !== self::$client->unique_auth) {
                                    self::$client = null;
                                }
                            }
                            /* User auth init */
                            self::$user = self::$client;
                            /* Check user auth model target */
                            if (self::$client && $model !== self::model()) {
                                $expires = Carbon::now()->addMinutes(self::getTTL($model) ?: config('jwt.ttl', 60));
                                self::$user = Cache::remember(self::getCacheKey(self::$payload['sub'], self::$guard), $expires, function () use ($model) {
                                    return app($model)->find(self::$payload['sub']);
                                });
                                /* Check user unique auth */
                                if (self::$user) {
                                    /* Check unique auth uuid */
                                    if (!in_array(self::$guard, $ignoreGuards, true) && in_array('unique_auth', self::$user->getFillable()) && self::$payload['uuk'] !== self::$user->unique_auth) {
                                        self::$user = null;
                                    }
                                }
                            }
                            /* Check auth target */
                            self::$guard = (isset(self::$client, self::$user) ? self::$guard : null);
                        }
                    }
                    /* Reset */
                    self::$payload = (isset(self::$guard) ? self::$payload : null);
                    self::$client = (isset(self::$guard) ? self::$client : null);
                    self::$user = (isset(self::$guard) ? self::$user : null);
                }
                /* Return auth guard */
                return self::$guard;
            }
        } else {
            /* Get abstract name */
            $abstract = (is_object($abstract) ? get_class($abstract) : (is_string($abstract) ? $abstract : null));
            /* Get auth guard name */
            $guard = (isset($abstract) ? array_search($abstract, self::getGuardModels()) : null);
            /* Return guard name */
            return (is_string($guard) ? $guard : null);
        }

        return null;
    }

    /**
     * Check if authentication guard applies to client services.
     *
     * @return bool
     */
    public static function isClientGuard(): bool
    {
        /* Verify auth token get guard */
        if ($guard = self::getAuthGuard()) {
            /* Check guard */
            return ($guard === self::getAuthGuard(self::model()) ? true : false);
        }

        return false;
    }

    /**
     * Get the authentication payload of the token.
     *
     * @return array|null
     */
    public static function getAuthPayload(): ?array
    {
        /* Verify auth token */
        if (self::getAuthGuard()) {
            /* Return auth payload */
            return self::$payload;
        }

        return null;
    }

    /**
     * Set the jwt ttl by guard target.
     *
     * @param string $guard
     *
     * @return void
     */
    public static function setTTLByGuard(string $guard)
    {
        /* Get the guard jwt ttl */
        $config = config('auth.guards.' . $guard);
        $ttl = (array_key_exists('jwt_ttl', $config) ? $config['jwt_ttl'] : config('jwt.ttl'));
        /* Set jwt ttl */
        if (isset($ttl)) {
            $refreshTTL = (array_key_exists('jwt_refresh_ttl', $config) ? $config['jwt_refresh_ttl'] : config('jwt.refresh_ttl'));
            $refreshTTL = (isset($refreshTTL) && $refreshTTL < $ttl ? $ttl : $refreshTTL);
            self::jwt()->factory()->setTTL($ttl)->emptyClaims();
            self::jwt()->factory()->validator()->setRefreshTTL($refreshTTL);
            self::jwt()->blacklist()->setRefreshTTL($refreshTTL);
        } else {
            self::jwt()->factory()->setTTL(null)->emptyClaims();
            self::jwt()->factory()->validator()->setRefreshTTL(null);
            self::jwt()->blacklist()->setRefreshTTL(null);
        }
    }

    /**
     * Get the signature validity time (in minutes) of the auth object or abstract model.
     *
     * @param mixed $abstract
     *
     * @return int|null
     */
    public static function getUTSTTL($abstract = null): ?int
    {
        $ttl = config('auth.uts_ttl');
        /* Get abstract name */
        $abstract = (isset($abstract) ? (is_object($abstract) ? get_class($abstract) : (is_string($abstract) ? $abstract : false)) : null);
        /* Get the guard uts ttl */
        if ($abstract !== false && ($guard = self::getAuthGuard($abstract))) {
            $config = config('auth.guards.' . $guard);
            $ttl = (array_key_exists('uts_ttl', $config) ? $config['uts_ttl'] : $ttl);
        }

        return $ttl;
    }

    /**
     * Get the jwt token validity time (in minutes) of the auth object or abstract model.
     *
     * @param mixed $abstract
     *
     * @return int|null
     */
    public static function getTTL($abstract = null): ?int
    {
        $ttl = config('jwt.ttl');
        /* Get abstract name */
        $abstract = (isset($abstract) ? (is_object($abstract) ? get_class($abstract) : (is_string($abstract) ? $abstract : false)) : null);
        /* Get the guard jwt ttl */
        if ($abstract !== false && ($guard = self::getAuthGuard($abstract))) {
            $config = config('auth.guards.' . $guard);
            $ttl = (array_key_exists('jwt_ttl', $config) ? $config['jwt_ttl'] : $ttl);
        }

        return $ttl;
    }

    /**
     * Get the auth client model for the authentication token.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function getClient(): ?object
    {
        /* Verify auth token */
        if (self::getAuthGuard()) {
            /* Return client model */
            return self::$client;
        }
        return null;
    }

    /**
     * Get the auth user model for the authentication token.
     *
     * @param mixed $abstract
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public static function getUser($abstract = null): ?object
    {
        /* Verify auth type target */
        if (isset($abstract)) {
            /* Get abstract name */
            $abstract = (is_object($abstract) ? get_class($abstract) : (is_string($abstract) ? $abstract : null));
            if (!isset($abstract)) {
                return null;
            }
        }
        /* Verify auth token get guard */
        if ($guard = self::getAuthGuard()) {
            /* Get auth model name */
            $model = self::getGuardModel($guard);
            /* Check auth type target */
            $model = (!isset($abstract) || $abstract === $model ? $model : null);
            /* Check auth type */
            if (isset($model) && self::model() !== $model) {
                /* Return user model */
                return self::$user;
            }
        }
        return null;
    }

    /**
     * Login the client auth model and return access token.
     *
     * @param \App\Entities\Service\Auth $client
     *
     * @return string|null
     */
    public static function loginClient(object $client): ?string
    {
        /* Model name */
        $model = get_class($client);
        /* Get auth guard name */
        if (self::model() === $model && ($guard = self::getAuthGuard($model))) {
            /* Get client id */
            $id = $client->getJWTIdentifier();
            /* Get client ban number id */
            $ban = $client->ban;
            /* Check client info */
            if (isset($id, $ban)) {
                /* Get auth guard type number id */
                $agt = array_search($model, self::getAgtModels());
                /* Set client unique login uuid */
                $cuk = null;
                /* Release by client ban number id */
                $release = config('ban.release.' . $ban);
                /* Unique auth ignore guards */
                $ignoreGuards = (isset($release['unique_auth_ignore_guards']) && is_array($release['unique_auth_ignore_guards']) ? $release['unique_auth_ignore_guards'] : []);
                /* Check unique auth mode */
                if (!in_array($guard, $ignoreGuards, true) && in_array('unique_auth', $client->getFillable())) {
                    /* Unique auth inherit login guards */
                    $inheritGuards = (isset($release['unique_auth_inherit_login_guards']) && is_array($release['unique_auth_inherit_login_guards']) ? $release['unique_auth_inherit_login_guards'] : []);
                    /* Check unique auth mode */
                    if (in_array($guard, $inheritGuards, true) && isset($client->unique_auth[0])) {
                        $cuk = $client->unique_auth;
                    } else {
                        /* Create client unique auth uuid */
                        $cuk = Str::uuid()->getHex();
                        /* Need to update the unique authcode */
                        $uniqueAuth = true;
                    }
                }
                /* Set claims */
                $client->setJWTCustomClaims([
                    'shi' => $id, // Client service holder id
                    'uuk' => null, // User unique login key
                    'cuk' => $cuk, // Client unique login key
                    'agt' => $agt // Auth guard type number id
                ]);
                /* Set the jwt ttl */
                self::setTTLByGuard($guard);
                /* Auth token */
                $token = auth()->login($client);
                /* Touch client unique login */
                if (isset($uniqueAuth)) {
                    $client->pushLogName('auth')->pushLog('auth')
                    ->pushProperties(['ip' => request()->ip()])
                    ->update([
                        'unique_auth' => $cuk
                    ]);
                } else {
                    /* Client auth log */
                    activity('auth')->performedOn($client)
                    ->causedBy($client)
                    ->withProperties(['ip' => request()->ip()])
                    ->log('auth');
                }
                /* Return auth token */
                return $token;
            }
        }
        return null;
    }

    /**
     * Login the user auth model and return access token.
     *
     * @param \Illuminate\Foundation\Auth\User $user
     *
     * @return string|null
     */
    public static function loginUser(object $user): ?string
    {
        /* Model name */
        $model = get_class($user);
        /* Check auth guard name and id to confirm that the client is authorized */
        if (self::model() !== $model && ($guard = self::getAuthGuard($model)) && ($id = $user->getJWTIdentifier()) && self::isClientGuard()) {
            /* Get auth guard type number id */
            $agt = array_search($model, self::getAgtModels());
            /* Get client ban number id */
            $ban = self::$client->ban;
            /* Set user unique login uuid */
            $uuk = null;
            /* Set client unique login uuid */
            $cuk = self::$payload['cuk'];
            /* Release by client ban number id */
            $release = config('ban.release.' . $ban);
            /* Unique auth ignore guards */
            $ignoreGuards = (isset($release['unique_auth_ignore_guards']) && is_array($release['unique_auth_ignore_guards']) ? $release['unique_auth_ignore_guards'] : []);
            /* Check unique auth mode */
            if (!in_array($guard, $ignoreGuards, true) && in_array('unique_auth', $user->getFillable())) {
                /* Unique auth inherit login guards */
                $inheritGuards = (isset($release['unique_auth_inherit_login_guards']) && is_array($release['unique_auth_inherit_login_guards']) ? $release['unique_auth_inherit_login_guards'] : []);
                /* Check unique auth mode */
                if (in_array($guard, $inheritGuards, true) && isset($user->unique_auth[0])) {
                    $uuk = $user->unique_auth;
                } else {
                    /* Create user unique auth uuid */
                    $uuk = Str::uuid()->getHex();
                    /* Need to update the unique authcode */
                    $uniqueAuth = true;
                }
            }
            /* Set claims */
            $user->setJWTCustomClaims([
                'shi' => self::$payload['shi'], // Client service holder id
                'uuk' => $uuk, // User unique login key
                'cuk' => $cuk, // Client unique login key
                'agt' => $agt // Auth guard type number id
            ]);
            /* Touch user unique login */
            if (isset($uniqueAuth)) {
                $user->pushLogName('login')->pushLog('login')
                ->pushProperties(['ip' => request()->ip()])
                ->update([
                    'unique_auth' => $uuk
                ]);
            } else {
                /* User login log */
                activity('login')->performedOn($user)
                ->causedBy(self::$client)
                ->withProperties(['ip' => request()->ip()])
                ->log('login');
            }
            /* Set the jwt ttl */
            self::setTTLByGuard(self::getAuthGuard(self::model()));
            /* Invalidate the original token and unset user */
            auth()->logout();
            /* Set the jwt ttl */
            self::setTTLByGuard($guard);
            /* Auth token */
            $token = auth()->login($user);
            /* Return user auth token */
            return $token;
        }
        return null;
    }

    /**
     * Logout the user auth model, then log back in to the client auth model and return the access token.
     *
     * @return string|null
     */
    public static function logoutUser(): ?string
    {
        /* Verify auth token get guard */
        if ($guard = self::getAuthGuard()) {
            /* Get auth model name */
            $model = self::getGuardModel($guard);
            if (self::model() !== $model) {
                /* Get auth guard type number id */
                $agt = array_search(self::model(), self::getAgtModels());
                /* Set claims */
                self::$client->setJWTCustomClaims([
                    'shi' => self::$payload['shi'], // Client service holder id
                    'uuk' => null, // User unique login key
                    'cuk' => self::$payload['cuk'], // Client unique login key
                    'agt' => $agt // Auth guard type number id
                ]);
                /* Set the jwt ttl */
                self::setTTLByGuard($guard);
                /* Invalidate the original token and unset user */
                auth()->logout();
                /* Set the jwt ttl */
                self::setTTLByGuard(self::getAuthGuard(self::model()));
                /* Auth token */
                $token = auth()->login(self::$client);
                /* User logout log */
                activity('logout')->performedOn(self::$user)
                ->causedBy(self::$client)
                ->withProperties(['ip' => request()->ip()])
                ->log('logout');
                /* Return client token */
                return $token;
            }
        }
        return null;
    }

    /**
     * Refresh the auth token.
     *
     * @param bool $forceForever
     *
     * @return string|null
     */
    public static function refresh(bool $forceForever = false): ?string
    {
        /* Verify auth token get guard */
        if ($guard = self::getAuthGuard()) {
            /* Set the jwt ttl */
            self::setTTLByGuard($guard);
            /* Refresh token */
            if ($token = auth()->refresh($forceForever)) {
                /* Return token */
                return $token;
            }
        }
        return null;
    }

    /**
     * Revoke the auth token.
     *
     * @param bool $forceForever
     *
     * @return bool
     */
    public static function revoke(bool $forceForever = false): bool
    {
        /* Verify auth token get guard */
        if ($guard = self::getAuthGuard()) {
            /* Set the jwt ttl */
            self::setTTLByGuard($guard);
            /* Invalidate the original token and unset user */
            auth()->logout($forceForever);
            /* User or client revoke log */
            activity('revoke')->performedOn(self::$user)
            ->causedBy(self::$client)
            ->withProperties(['ip' => request()->ip()])
            ->log('revoke');
            return true;
        }
        return false;
    }

    /**
     * Obtain the user signature code used for signature authorization login through the user auth model.
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @param int $ttl
     *
     * @return string|null
     */
    public static function injectUserSignature(object $user, int $ttl = null): ?string
    {
        /* Model name */
        $model = get_class($user);
        /* Check guard auth user */
        if (self::model() !== $model && self::getAuthGuard($model) && ($id = $user->getJWTIdentifier())) {
            /* Auth target */
            $data = [
                'mark' => 'TokenAuth',
                'model' => $model,
                'id' => $id
            ];
            /* Save auth unique code */
            return StorageSign::build($data, (isset($ttl) ? $ttl : $user->getUTSTTL()), 'AUTH');
        }
        return null;
    }

    /**
     * Login the user signature code and return user model.
     *
     * @param string $code
     * @param bool $forceForget
     *
     * @return object|null
     */
    public static function getUserBySignature(string $code, bool $forceForget = true): ?object
    {
        $data = StorageSign::get($code);
        /* Check signature user */
        if (isset($data['mark'], $data['model'], $data['id']) && $data['mark'] === 'TokenAuth' && self::model() !== $data['model'] && ($guard = self::getAuthGuard($data['model']))) {
            $expires = Carbon::now()->addMinutes(self::getTTL($data['model']) ?: config('jwt.ttl', 60));
            $user = Cache::remember(self::getCacheKey($data['id'], $guard), $expires, function () use ($data) {
                return app($data['model'])->find($data['id']);
            });
            /* Cache forget */
            if ($forceForget) {
                StorageSign::forget($code);
            }
            /* Return user model */
            return $user;
        }
        return null;
    }
}
