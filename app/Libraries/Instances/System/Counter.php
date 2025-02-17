<?php

namespace App\Libraries\Instances\System;

use App\Entities\System\Parameter as SP;
use DB;
use Cache;
use Exception;

/**
 * Final Class Counter.
 *
 * @package namespace App\Libraries\Instances\System;
 */
final class Counter
{

    /**
     * The system counter parameter prefix name.
     *
     * @var string
     */
    private static $prefix = 'sys:count:';

    /**
     * The key list.
     *
     * @var array
     */
    private static $keys = [];

    /**
     * Increment the value.
     *
     * @param string $key
     * @param int $value
     *
     * @return int
     * @throws \Exception
     */
    public static function increment(string $key, int $value = 1): int
    {
        if (strlen($key) > 128) {
            throw new Exception('System Counter: The key name entered must not exceed 128 bytes!');
        }
        if ($value < 0 || $value > PHP_INT_MAX) {
            throw new Exception('System Counter: The value entered must be between 0 ~ PHP_INT_MAX!');
        }
        /* Check key */
        if (isset(self::$keys[$key]) && self::$keys[$key] === PHP_INT_MAX) {
            return PHP_INT_MAX;
        }
        /* Get system counter parameter name */
        $slug = self::$prefix . $key;
        /* Check slug */
        DB::beginTransaction();
        $result = SP::where('slug', $slug)->lockForUpdate()->first();
        if ($result) {
            if ($result->value < PHP_INT_MAX) {
                $value = bcadd($result->value, $value);
                /* Check max value */
                $value = (bccomp($value, PHP_INT_MAX) < 1 ? $value : PHP_INT_MAX);
                /* Rewrite */
                $result->update([
                    'value' => $value
                ]);
            } else {
                $value = PHP_INT_MAX;
            }
        } else {
            SP::create([
                'slug' => $slug,
                'value' => $value
            ]);
        }
        /* Save cache */
        if ($store = config('sp.cache_store')) {
            Cache::store($store)->forever($slug, $value);
        }
        self::$keys[$key] = $value;
        DB::commit();
        return $value;
    }

    /**
     * Decrement the value.
     *
     * @param string $key
     * @param int $value
     *
     * @return int
     * @throws \Exception
     */
    public static function decrement(string $key, int $value = 1): int
    {
        if (strlen($key) > 128) {
            throw new Exception('System Counter: The key name entered must not exceed 128 bytes!');
        }
        if ($value < 0 || $value > PHP_INT_MAX) {
            throw new Exception('System Counter: The value entered must be between 0 ~ PHP_INT_MAX!');
        }
        /* Check key */
        if (isset(self::$keys[$key]) && self::$keys[$key] === 0) {
            return 0;
        }
        /* Get system counter parameter name */
        $slug = self::$prefix . $key;
        /* Check slug */
        DB::beginTransaction();
        $result = SP::where('slug', $slug)->lockForUpdate()->first();
        if ($result) {
            if ($result->value > 0) {
                $value = bcsub($result->value, $value);
                /* Check min value */
                $value = (bccomp($value, 0) > 0 ? $value : 0);
                /* Rewrite */
                $result->update([
                    'value' => $value
                ]);
            } else {
                $value = 0;
            }
        } else {
            $value = 0;
            SP::create([
                'slug' => $slug,
                'value' => $value
            ]);
        }
        /* Save cache */
        if ($store = config('sp.cache_store')) {
            Cache::store($store)->forever($slug, $value);
        }
        self::$keys[$key] = $value;
        DB::commit();
        return $value;
    }

    /**
     * Get the total.
     *
     * @param string $key
     * 
     * @return int
     * @throws \Exception
     */
    public static function total(string $key): int
    {
        if (strlen($key) > 128) {
            throw new Exception('System Counter: The key name entered must not exceed 128 bytes!');
        }
        /* Check key */
        if (isset(self::$keys[$key])) {
            return self::$keys[$key];
        }
        /* Get system counter parameter name */
        $slug = self::$prefix . $key;
        /* Cache search */
        if ($store = config('sp.cache_store')) {
            $value = Cache::store($store)->get($slug);
            if (! isset($value)) {
                $result = SP::where('slug', $slug)->first();
                $value = ($result ? $result->value : 0);
                /* Save cache */
                Cache::store($store)->forever($slug, $value);
            }
        } else {
            $result = SP::where('slug', $slug)->first();
            $value = ($result ? $result->value : 0);
        }
        return self::$keys[$key] = $value;
    }
}
