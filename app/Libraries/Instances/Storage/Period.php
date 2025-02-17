<?php

namespace App\Libraries\Instances\Storage;

use Illuminate\Validation\ValidationException;
use Validator;
use Carbon;
use Cache;
use Str;
use Exception;

/**
 * Final Class Period.
 *
 * @package namespace App\Libraries\Instances\Storage;
 */
final class Period
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
    private static $suffix = ':p';

    /**
     * The cache key suffix name for guest.
     *
     * @var string
     */
    private static $guestSuffix = ':pg';
    
    /**
     * The signature cache list.
     *
     * @var array
     */
    private static $signatures = [];

    /**
    * Create a code string with 72 bytes.
    *
    * @param string $schedule
    *
    * @return void
    */
    private static function outputCode(string $schedule)
    {
        /* Create auth tag unique code */
        $code = Str::uuid()->getHex(); // 32 bytes
        /* Hash suffix code */
        $code .= hash('crc32b', $code . $schedule); // 8 bytes
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
     * Get the regular attendance signature code.
     *
     * @param string $startTime
     * @param string $endTime
     * @param array $data
     * @param int $ttl
     * @param string|null $tag
     *
     * @return string|null
     */
    public static function build(string $startTime, string $endTime, array $data = [], int $ttl = 3, ?string $tag = null): ?string
    {
        /* Check tag format */
        if (isset($tag) && ! preg_match('/^[A-Z0-9_]+$/', $tag)) {
            throw new Exception('Storage Period: The tag name uses only characters such as ( A ~ Z 0 ~ 9 _ ) .');
        }
        /* Validator */
        try {
            Validator::make([
                'start' => $startTime,
                'end' => $endTime
            ], [
                'start' => 'required|date_format:Y-m-d H:i:s',
                'end' => 'required|date_format:Y-m-d H:i:s'
            ])->validate();
        } catch (\Throwable $th) {
            if ($th instanceof ValidationException) {
                return null;
            }
            throw $th;
        }
        $startedAt = ($startTime > $endTime ? $endTime : $startTime);
        $endedAt = ($startTime > $endTime ? $startTime : $endTime);
        /* Check time */
        if ($startedAt === $endedAt || $startedAt <= Carbon::now()->toDateTimeString()) {
            return null;
        }
        /* Create auth tag unique code */
        $code = self::outputCode($startedAt.$endedAt);
        /* Check code unexists */
        if (! self::get($code)) {
            $data = [$startedAt, $endedAt, $ttl, $data];
            $interimStore = config('signature.interim_store');
            /* Authorization code during creation */
            if ($ttl > 0 && Cache::store($interimStore)->put(self::$prefix . $code . self::$suffix, $data, Carbon::parse($endedAt)->addMinutes($ttl))) {
                /* Cache signature */
                self::$signatures[$code] = $data;
                /* Codebase 72 bytes */
                return (isset($tag) ? $tag . '-' : '' ) . $code;
            }
        }
        return null;
    }

    /**
     * Verify attendance signature count.
     *
     * @param string $guest
     * @param string $code
     *
     * @return int|null
     */
    public static function sign(string $guest, string $code): ?int
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
                $now = Carbon::now()->toDateTimeString();
                if ($storage[0] <= $now) {
                    /* Check done */
                    if ($signCount = Cache::store($interimStore)->get(self::$prefix . $code . ':' . $guest . self::$guestSuffix)) {
                        if ($signCount < 2 && $storage[1] <= $now && Cache::store($interimStore)->put(self::$prefix . $code . ':' . $guest . self::$guestSuffix, 2, Carbon::parse($storage[1])->addMinutes($storage[2] + 1))) {
                            return 2;
                        } else {
                            return ($signCount > 1 ? null : 1);
                        }
                    } elseif (Carbon::parse($storage[0])->addMinutes($storage[2])->toDateTimeString() >= $now && Cache::store($interimStore)->put(self::$prefix . $code . ':' . $guest . self::$guestSuffix, 1, Carbon::parse($storage[1])->addMinutes($storage[2] + 1))) {
                        return 1;
                    }
                }
            }
        }
        return null;
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
                    'started_at' => self::$signatures[$code][0],
                    'ended_at' => self::$signatures[$code][1],
                    'data' => self::$signatures[$code][3]
                ];
            } elseif ($storage = Cache::store($interimStore)->get(self::$prefix . $code . self::$suffix)) {
                /* Cache signature */
                self::$signatures[$code] = $storage;
                return [
                    'started_at' => $storage[0],
                    'ended_at' => $storage[1],
                    'data' => $storage[3]
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
