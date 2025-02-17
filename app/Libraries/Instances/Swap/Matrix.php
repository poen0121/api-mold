<?php

namespace App\Libraries\Instances\Swap;

/**
 * Final Class Matrix.
 *
 * @package namespace App\Libraries\Instances\Swap;
 */
final class Matrix
{
    /**
     * Replace null with an empty string recursively.
     *
     * @param  array $data
     * @return array
     */
    public static function null2empty(array $data): array
    {
        self::replace($data, null, '');
        return $data;
    }

    /**
     * Replace empty strings with null recursively.
     *
     * @param  array $data
     * @return array
     */
    public static function empty2null(array $data): array
    {
        self::replace($data, '', null);
        return $data;
    }

    /**
     * Replace custom with recursion.
     *
     * @param  array &$data
     * @param  mixed $search
     * @param  mixed $custom
     *
     * @return void
     */
    public static function replace(array &$data, $search, $custom)
    {
        $replace = function (&$value) use ($search, $custom) {
            if ($value === $search) {
                $value = $custom;
            } elseif (is_array($value)) {
                self::replace($value, $search, $custom);
            }
        };
        array_walk($data, $replace);
    }

    /**
     * Replace rules with recursive indexes.
     *
     * @param  array &$data
     * @param  array $rules
     *
     * @return void
     */
    public static function indexReplace(array &$data, array $rules)
    {
        if (count($rules) > 0) {
            $replace = function (&$value, $key) use ($rules) {
                if (is_array($value) && is_int($key)) {
                    self::indexReplace($value, $rules);
                } elseif (isset($rules[$key]) && is_array($rules[$key]) && count($rules[$key]) > 0) {
                    if (is_array($value)) {
                        self::indexReplace($value, $rules[$key]);
                    } elseif (isset($rules[$key][$value])) {
                        $value = $rules[$key][$value];
                    }
                }
            };
            array_walk($data, $replace);
        }
    }

    /**
     * Replace rules with recursive values.
     *
     * @param  array &$data
     * @param  array $rules
     *
     * @return void
     */
    public static function allReplace(array &$data, array $rules)
    {
        if (count($rules) > 0) {
            $replace = function (&$value) use ($rules) {
                if (is_array($value)) {
                    self::allReplace($value, $rules);
                } elseif (isset($rules[$value])) {
                    $value = $rules[$value];
                }
            };
            array_walk($data, $replace);
        }
    }
}
