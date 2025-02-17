<?php

namespace App\Libraries\Instances\System;

use TokenAuth;
use Lang;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * Final Class Authority.
 *
 * @package namespace App\Libraries\Instances\System;
 */
final class Authority
{
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
     * The types list.
     *
     * @var array
     */
    private static $types;

    /**
     * Get a list of auth guard object types.
     *
     * @param array $column
     * column string : class
     * column string : type
     * column string : description
     * @param string|null $type
     *
     * @return array
     * @throws \Exception
     */
    public static function objectTypes(array $column = [], ?string $type = null): array
    {
        /* Use column */
        if (count($column) > 0) {
            $diff = array_unique(array_diff($column, self::$columns));
            /* Check column name */
            if (count($diff) > 0) {
                throw new Exception('Query Authority: Column not found: Unknown column ( \'' . implode('\', \'', $diff) . '\' ) in \'field list\'.');
            }
        }
        /* Build cache reset description */
        if (!isset(self::$types)) {
            $guards = TokenAuth::getGuardModels();

            self::$types = collect($guards)->map(function ($item, $key) {
                /* Verify authority trait */
                if (in_array('App\Libraries\Traits\Entity\Column\Authority', class_uses($item))) {
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
        if (is_null($type)) {
            $types = self::$types;
            if (count($column) > 0) {
                /* Forget column */
                $forget = array_diff(self::$columns, $column);
                /* Get result */
                $types = collect($types)->map(function ($item) use ($forget) {
                    return collect($item)->forget($forget)->all();
                })
                    ->all();
            }
        } else {
            /* Get type */
            if (isset(self::$types[$type])) {
                $types = self::$types[$type];
                if (count($column) > 0) {
                    /* Forget column */
                    $forget = array_diff(self::$columns, $column);
                    /* Get result */
                    $types = collect($types)->forget($forget)->all();
                }
            } else {
                throw new ModelNotFoundException('Query Authority: No query results for guards: Unknown type \'' . $type . '\'.');
            }
        }

        return $types;
    }

    /**
     * Global the user authority.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return array|null
     */
    public static function global(object $user): ?array
    {
        if (in_array('App\Libraries\Traits\Entity\Column\Authority', class_uses($user))) {
            return ['*'];
        }

        return null;
    }

    /**
     * Grant the user authority.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return array|null
     */
    public static function grant(object $user, array $insertface): ?array
    {
        if (in_array('App\Libraries\Traits\Entity\Column\Authority', class_uses($user))) {
            return array_unique(array_merge(($user->authority ?: []), array_values($insertface)));
        }

        return null;
    }

    /**
     * Remove the user authority.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return array|null
     */
    public static function remove(object $user, array $insertface): ?array
    {
        if (in_array('App\Libraries\Traits\Entity\Column\Authority', class_uses($user))) {
            return array_values(array_diff(($user->authority ?: []), $insertface));
        }

        return null;
    }
}
