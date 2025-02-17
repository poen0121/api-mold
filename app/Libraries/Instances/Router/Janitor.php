<?php

namespace App\Libraries\Instances\Router;

use Lang;
use Str;

/**
 * Final Class Janitor.
 *
 * @package namespace App\Libraries\Instances\Router;
 */
final class Janitor
{
    /**
     * The guests class list.
     *
     * @var array
     */
    private static $janitorGuests;

    /**
     * The guests class belong list.
     *
     * @var array
     */
    private static $janitorBelongGuests;
    
    /**
     * Get the guests class list.
     * 
     * @param string|null $belong
     * 
     * @return array
     */
    public static function getGuests(?string $belong = null): array
    {
        /* Cache */
        if (! isset(self::$janitorGuests)) {
            self::$janitorGuests = config('janitor');
            self::$janitorGuests = (is_array(self::$janitorGuests) ? self::$janitorGuests : []);
            self::$janitorGuests = array_unique(collect(self::$janitorGuests)->map(function ($item, $key) {
                if (isset($key[0], $item['class'][0], $item['status'], $item['data'], $item['belong'][0]) && is_array($item['data'])) {
                    self::$janitorBelongGuests[$item['belong']][$key] = $item['class'];
                    return $item['class'];
                }
                return null;
            })->reject(function ($item) {
                return !isset($item);
            })->all());
        }
        return (isset($belong) ? (isset(self::$janitorBelongGuests[$belong]) ? self::$janitorBelongGuests[$belong] : []) : self::$janitorGuests);
    }

    /**
     * Check the guest class return the guest type code.
     *
     * @param mixed $abstract
     *
     * @return string|null
     */
    public static function getGuestType($abstract): ?string
    {
        /* Get abstract name */
        $abstract = (is_object($abstract) ? get_class($abstract) : (is_string($abstract) ? $abstract : null));
        if (is_string($abstract)) {
            /* Get guest type code */
            $guest = array_search($abstract, self::getGuests());

            return (is_string($guest) ? $guest : null);
        }
        return null;
    }

    /**
     * Check the guest type return the guest data array.
     *
     * @param string $type
     *
     * @return array|null
     */
    public static function getGuestData(string $type): ?array
    {
        /* Get guest data */
        return (isset(self::getGuests()[$type]) ? config('janitor.' . $type . '.data') : null);
    }

    /**
     * Check the guest type return the category to which the guest belongs.
     *
     * @param string $type
     *
     * @return string|null
     */
    public static function getGuestBelong(string $type): ?string
    {
        /* Get guest belongs */
        return (isset(self::getGuests()[$type]) ? config('janitor.' . $type . '.belong') : null);
    }

    /**
     * Check the guest type return the guest type name.
     *
     * @param string $type
     *
     * @return string|null
     */
    public static function getGuestName(string $type): ?string
    {
        /* Get guest type name */
        return (isset(self::getGuests()[$type]) ? Lang::dict('janitor', 'names.' . $type, Str::of($type)->replace('_', ' ')->title()) : null);
    }

    /**
     * Check the guest type return the guest class name.
     *
     * @param string $type
     *
     * @return string|null
     */
    public static function getGuestClass(string $type): ?string
    {
        /* Get guest type class */
        return (isset(self::getGuests()[$type]) ? self::getGuests()[$type] : null);
    }

    /**
     * Check that the class allowed by the janitor.
     *
     * @param mixed $abstract
     * @param string|null $belong
     * 
     * @return bool
     */
    public static function isAllowed($abstract, ?string $belong = null): bool
    {
        /* Check guest type code */
        if ($guest = self::getGuestType($abstract)) {
            /* Check guest belong */
            if (isset($belong) && self::getGuestBelong($guest) !== $belong) {
                return false;
            }
            /* Check guest status */
            return (config('janitor.' . $guest . '.status') ? true : false);
        }
        return false;
    }
}
