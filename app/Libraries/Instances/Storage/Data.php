<?php

namespace App\Libraries\Instances\Storage;

use Carbon;
use Cache;

/**
 * Final Class Data.
 *
 * @package namespace App\Libraries\Instances\Storage;
 */
final class Data
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
    private static $suffix = ':d';

    /**
     * The data cache list.
     *
     * @var array
     */
    private static $data = [];

    /**
     * Put in the holder’s data.
     *
     * @param string $holder
     * @param array $data
     * @param int $ttl
     *
     * @return bool
     */
    public static function put(string $holder, array $data, int $ttl = 3): bool
    {
        /* Save */
        if ($ttl > 0 && Cache::put(self::$prefix . $holder . self::$suffix, $data, Carbon::now()->addMinutes($ttl))) {
            /* Cache data */
            self::$data[$holder] = $data;
            return true;
        }
        return false;
    }

    /**
     * Get the the holder's data.
     *
     * @param string $holder
     *
     * @return array|null
     */
    public static function get(string $holder): ?array
    {
        /* Check cache */
        if (isset(self::$data[$holder])) {
            return self::$data[$holder];
        } elseif ($storage = Cache::get(self::$prefix . $holder . self::$suffix)) {
            /* Cache data */
            return self::$data[$holder] = $storage;
        }
        return null;
    }

    /**
     * Forget the the holder's data.
     *
     * @param string $holder
     *
     * @return bool
     */
    public static function forget(string $holder): bool
    {
        /* Remove cache data */
        if (isset(self::$data[$holder])) {  
            unset(self::$data[$holder]);
        }
        /* Remove key */
        return Cache::forget(self::$prefix . $holder . self::$suffix);
    }
}
