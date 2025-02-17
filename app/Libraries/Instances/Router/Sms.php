<?php

namespace App\Libraries\Instances\Router;

use Phone;

/**
 * Final Class Sms.
 *
 * @package namespace App\Libraries\Instances\Router;
 */
final class Sms
{
    /**
     * The telecomer notifications class list.
     *
     * @var array
     */
    private static $telecomerNotifications;

    /**
     * The default telecomer type code.
     *
     * @var array
     */
    private static $telecomerDefault;

    /**
     * The telecomer type code route list.
     *
     * @var array
     */
    private static $telecomerRoutes;

    /**
     * Get the telecomer notifications class list.
     *
     * @return array
     */
    public static function getNotifications(): array
    {
        /* Cache */
        if (! isset(self::$telecomerNotifications)) {
            self::$telecomerNotifications = config('sms.notifications');
            self::$telecomerNotifications = (is_array(self::$telecomerNotifications) ? self::$telecomerNotifications : []);
        }
        return self::$telecomerNotifications;
    }

    /**
     * Get the default telecomer type code.
     *
     * @return string|null
     */
    public static function getDefault(): ?string
    {
        /* Cache */
        if (! isset(self::$telecomerDefault)) {
            self::$telecomerDefault = config('sms.default');
            self::$telecomerDefault = (is_string(self::$telecomerDefault) ? (isset(self::$telecomerDefault[0]) ? self::$telecomerDefault : false) : false);
        }
        return (self::$telecomerDefault === false ? null : self::$telecomerDefault);
    }

    /**
     * Get the telecomer type code route list.
     *
     * @return array
     */
    public static function getRoutes(): array
    {
        /* Cache */
        if (! isset(self::$telecomerRoutes)) {
            self::$telecomerRoutes = config('sms.routes');
            self::$telecomerRoutes = (is_array(self::$telecomerRoutes) ? self::$telecomerRoutes : []);
        }
        return self::$telecomerRoutes;
    }

    /**
     * Get the SMS route notification class.
     *
     * @param string $phone
     * @param array $express
     * 
     * @return string|null
     */
    public static function route(string $phone, array $express = []): ?string
    {
        if ($code = Phone::parse($phone, $express)->getCode()) {
            if (isset(self::getRoutes()[$code], self::getNotifications()[self::getRoutes()[$code]])) {
                return self::getNotifications()[self::getRoutes()[$code]];
            } else {
                return (self::getDefault() !== null && isset(self::getNotifications()[self::getDefault()]) ? self::getNotifications()[self::getDefault()] : null);
            }
        }
        return null;
    }
}
