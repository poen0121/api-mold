<?php

namespace App\Libraries\Instances\Swap;

use Carbon;

/**
 * Final Class TimeDisplay.
 *
 * @package namespace App\Libraries\Instances\Swap;
 */
final class TimeDisplay
{
    /**
    * Get the application local timezone.
    *
    * @return string
    */
    public static function getTZ(): string
    {
        /* Local timezone */
        return config('app.timezone', 'UTC');
    }
    
    /**
    * Get the accept client timezone.
    *
    * @return string
    */
    public static function getCTZ(): string
    {
        /* Accept client timezone */
        return (defined('CLIENT_TIMEZONE') ? CLIENT_TIMEZONE : self::getTZ());
    }

    /**
     * Swap the client timezone datetime to the local timezone datetime.
     *
     * @param mixed $value
     * @return Carbon|null
     */
    public static function asLocalTime($value): ?object
    {
        if (is_object($value) && get_class($value) === 'Illuminate\Support\Carbon') {
            /* Check timezone diff */
            if ($value->timezone !== self::getTZ()) {
                /* Return local timezone time */
                $value->tz(self::getTZ());
            }
            return $value;
        } elseif (is_string($value)) {
            /* Get local carbon object */
            $value = Carbon::parse($value, self::getCTZ());
            /* Check timezone diff */
            if (self::getCTZ() !== self::getTZ()) {
                /* Set local timezone time */
                $value->tz(self::getTZ());
            }
            return $value;
        }

        return null;
    }

    /**
     * Swap the local timezone datetime to the client timezone datetime.
     *
     * @param mixed $value
     * @return Carbon|null
     */
    public static function asClientTime($value): ?object
    {
        if (is_object($value) && get_class($value) === 'Illuminate\Support\Carbon') {
            /* Check timezone diff */
            if ($value->timezone !== self::getCTZ()) {
                /* Return local timezone time */
                $value->tz(self::getCTZ());
            }
            return $value;
        } elseif (is_string($value)) {
            /* Get local carbon object */
            $value = Carbon::parse($value, self::getTZ());
            /* Check timezone diff */
            if (self::getCTZ() !== self::getTZ()) {
                /* Set local timezone time */
                $value->tz(self::getCTZ());
            }
            return $value;
        }

        return null;
    }
}