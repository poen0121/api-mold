<?php

namespace App\Libraries\Instances\Storage;

use Illuminate\Validation\ValidationException;
use Validator;
use Carbon;
use Cache;
use Str;
use Exception;

/**
 * Final Class Imprint.
 *
 * @package namespace App\Libraries\Instances\Storage;
 */
final class Imprint
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
    private static $suffix = ':i';

    /**
     * The cache key suffix name for guest.
     *
     * @var string
     */
    private static $guestSuffix = ':ig';
    
    /**
     * The signature cache list.
     *
     * @var array
     */
    private static $signatures = [];

    /**
    * Create a code string with 72 bytes.
    *
    * @param string $datetime
    *
    * @return void
    */
    private static function outputCode(string $datetime)
    {
        /* Create auth tag unique code */
        $code = Str::uuid()->getHex(); // 32 bytes
        /* Hash suffix code */
        $code .= hash('crc32b', $code . $datetime); // 8 bytes
        /* Signature code */
        return Str::upper($code . hash_hmac('md5', $code, get_class() . config('signature.secret')));
    }

    /**
    * Get the clean code string.
    *
    * @param string $code
    *
    * @return string
    */
    private static function cleanCode(string $code): string
    {
        $code = explode('-', $code);
        return end($code);
    }

    /**
    * Check the code string format.
    *
    * @param string $code
    *
    * @return bool
    */
    private static function isCode(string $code): bool
    {
        $code = Str::lower($code);
        if (preg_match('/^[a-f0-9]{72}$/', $code) && substr($code, -32) === hash_hmac('md5', substr($code, 0, 40), get_class() . config('signature.secret'))) {
            return true;
        }
        return false;
    }

    /**
     * Get the datetime signature code for the appointment.
     *
     * @param string $datetime
     * @param array $data
     * @param int $ttl
     * @param string|null $tag
     *
     * @return string|null
     */
    public static function build(string $datetime, array $data = [], int $ttl = 3, ?string $tag = null): ?string
    {
        /* Check tag format */
        if (isset($tag) && ! preg_match('/^[A-Z0-9_]+$/', $tag)) {
            throw new Exception('Storage Imprint: The tag name uses only characters such as ( A ~ Z 0 ~ 9 _ ) .');
        }
        /* Validator */
        try {
            Validator::make([
                'datetime' => $datetime
            ], [
                'datetime' => 'required|date_format:Y-m-d H:i:s'
            ])->validate();
        } catch (\Throwable $th) {
            if ($th instanceof ValidationException) {
                return null;
            }
            throw $th;
        }
        /* Check time */
        if ($datetime <= Carbon::now()->toDateTimeString()) {
            return null;
        }
        /* Create auth tag unique code */
        $code = self::outputCode($datetime);
        /* Check code unexists */
        if (! self::get($code)) {
            $data = [$datetime, $ttl, $data];
            $interimStore = config('signature.interim_store');
            /* Authorization code during creation */
            if ($ttl > 0 && Cache::store($interimStore)->put(self::$prefix . $code . self::$suffix, $data, Carbon::parse($datetime)->addMinutes($ttl))) {
                /* Cache signature */
                self::$signatures[$code] = $data;
                /* Codebase 72 bytes */
                return (isset($tag) ? $tag . '-' : '' ) . $code;
            }
        }
        return null;
    }

    /**
     * Verify sign up signature.
     *
     * @param string $guest
     * @param string $code
     *
     * @return bool
     */
    public static function sign(string $guest, string $code): bool
    {
        $code = self::cleanCode($code);
        /* Check code */
        if (self::isCode($code)) {
            $interimStore = config('signature.interim_store');
            $storage = null;
            /* Check cache */
            if (isset(self::$signatures[$code])) {
                $storage = self::$signatures[$code];
            } else {
                /* Get key value */
                if ($storage = Cache::store($interimStore)->get(self::$prefix . $code . self::$suffix)) {
                    /* Cache signature */
                    self::$signatures[$code] = $storage;
                }
            }
            /* Check storage */
            if ($storage) {
                /* Check time */
                if ($storage[0] <= Carbon::now()->toDateTimeString()) {
                    /* Check done */
                    if (Cache::store($interimStore)->get(self::$prefix . $code . ':' . $guest . self::$guestSuffix)) {
                        return false;
                    } elseif (Cache::store($interimStore)->put(self::$prefix . $code . ':' . $guest . self::$guestSuffix, 1, Carbon::parse($storage[0])->addMinutes($storage[1] + 1))) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Get the data by signature code.
     *
     * @param string $code
     *
     * @return array|null
     */
    public static function get(string $code): ?array
    {
        $code = self::cleanCode($code);
        /* Check code */
        if (self::isCode($code)) {
            $interimStore = config('signature.interim_store');
            /* Check cache */
            if (isset(self::$signatures[$code])) {
                return [
                    'datetime' => self::$signatures[$code][0],
                    'data' => self::$signatures[$code][2]
                ];
            } elseif ($storage = Cache::store($interimStore)->get(self::$prefix . $code . self::$suffix)) {
                /* Cache signature */
                self::$signatures[$code] = $storage;
                return [
                    'datetime' => $storage[0],
                    'data' => $storage[2]
                ];
            }
        }
        return null;
    }

    /**
     * Forget the data by signature code.
     *
     * @param string $code
     *
     * @return bool
     */
    public static function forget(string $code): bool
    {
        $code = self::cleanCode($code);
        $remove = false;
        /* Check code */
        if (self::isCode($code)) {
            /* Remove cache data */
            if (isset(self::$signatures[$code])) {
                unset(self::$signatures[$code]);
            }
            $interimStore = config('signature.interim_store');
            /* Remove key */
            $remove = Cache::store($interimStore)->forget(self::$prefix . $code . self::$suffix);
        }
        return $remove;
    }
}
