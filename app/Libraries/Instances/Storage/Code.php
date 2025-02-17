<?php

namespace App\Libraries\Instances\Storage;

use Carbon;
use Cache;

/**
 * Final Class Code.
 *
 * @package namespace App\Libraries\Instances\Storage;
 */
final class Code
{
    /**
     * The cache key prefix name.
     *
     * @var string
     */
    private static $prefix = 'storage:';

    /**
     * The cache key suffix name.
     *
     * @var string
     */
    private static $suffix = ':c';

    /**
     * The codes cache list.
     *
     * @var array
     */
    private static $codes = [];

    /**
     * Fill in the holder’s authcode.
     *
     * @param string $holder
     * @param int $ttl
     *
     * @return int|null
     */
    public static function fill(string $holder, int $ttl = 3): ?int
    {
        /* Create authcode 6 bytes */
        $code = mt_rand(100000, 999999);
        /* Save */
        if ($ttl > 0 && Cache::put(self::$prefix . $holder . self::$suffix, $code, Carbon::now()->addMinutes($ttl))) {
            /* Cache code */
            return self::$codes[$holder] = $code;
        }
        return null;
    }

    /**
     * Get the the holder's authcode.
     *
     * @param string $holder
     *
     * @return int|null
     */
    public static function get(string $holder): ?int
    {
        /* Check cache */
        if (isset(self::$codes[$holder])) {
            return self::$codes[$holder];
        } elseif ($storage = Cache::get(self::$prefix . $holder . self::$suffix)) {
            /* Cache code */
            return self::$codes[$holder] = $storage;
        }
        return null;
    }

    /**
     * Forget the the holder's authcode.
     *
     * @param string $holder
     *
     * @return bool
     */
    public static function forget(string $holder): bool
    {
        /* Remove cache data */
        if (isset(self::$codes[$holder])) {
            unset(self::$codes[$holder]);
        }
        /* Remove key */
        return Cache::forget(self::$prefix . $holder . self::$suffix);
    }
}
