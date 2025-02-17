<?php

namespace App\Libraries\Instances\Notice;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use TokenAuth;
use Lang;
use Exception;

/**
 * Final Class LetterType.
 *
 * @package namespace App\Libraries\Instances\Notice;
 */
final class LetterType
{

    /**
     * The user types list.
     *
     * @var array
     */
    private static $userTypes;

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
     * Get a list of user types.
     *
     * @param array $column
     * column string : class
     * column string : type
     * column string : description
     * @param string|null $guard
     *
     * @return array
     * @throws \Exception
     */
    public static function userTypes(array $column = [], ?string $guard = null): array
    {
        /* Use column */
        if (count($column) > 0) {
            $diff = array_unique(array_diff($column, self::$columns));
            /* Check column name */
            if (count($diff) > 0) {
                throw new Exception('Query Notifiable: Column not found: Unknown column ( \'' . implode('\', \'', $diff) . '\' ) in \'field list\'.');
            }
        }
        /* Build cache reset description */
        if (!isset(self::$userTypes)) {
            $guards = TokenAuth::getGuardModels();

            self::$userTypes = collect($guards)->map(function ($item, $key) {
                if (array_key_exists($item, config('notice.letter_recipients', [])) && TokenAuth::model() !== $item && in_array('Illuminate\Notifications\Notifiable', class_uses($item))) {
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
        if (is_null($guard)) {
            $types = self::$userTypes;
            if (count($column) > 0) {
                /* Forget column */
                $forget = array_diff(self::$columns, $column);
                /* Get result */
                $types = collect($types)->map(function ($item) use ($forget) {
                    return collect($item)->forget($forget)->all();
                })->all();
            }
        } else {
            /* Get type */
            if (isset(self::$userTypes[$guard])) {
                $types = self::$userTypes[$guard];
                if (count($column) > 0) {
                    /* Forget column */
                    $forget = array_diff(self::$columns, $column);
                    /* Get result */
                    $types = collect($types)->forget($forget)->all();
                }
            } else {
                throw new ModelNotFoundException('Query Notifiable: No query results for types: Unknown type \'' . $guard . '\'.');
            }
        }

        return $types;
    }

    /**
     * Get a list of user types held by the sender.
     *
     * @param mixed $sender
     * @param array $column
     * column string : class
     * column string : type
     * column string : description
     * @param string|null $guard
     *
     * @return array
     * @throws \Exception
     */
    public static function heldUserTypes($sender, array $column = [], ?string $guard = null): array
    {
        /* Use column */
        if (count($column) > 0) {
            $diff = array_unique(array_diff($column, self::$columns));
            /* Check column name */
            if (count($diff) > 0) {
                throw new Exception('Query Notifiable: Column not found: Unknown column ( \'' . implode('\', \'', $diff) . '\' ) in \'field list\'.');
            }
        }
        /* Sender class name */
        $sender = (is_object($sender) ? get_class($sender) : (is_string($sender) ? $sender : null));
        /* Check sender */
        if (is_string($sender) && TokenAuth::getAuthGuard($sender) && TokenAuth::model() !== $sender) {
            /* Build cache reset description */
            if (!isset(self::$userTypes)) {
                $guards = TokenAuth::getGuardModels();

                self::$userTypes = collect($guards)->map(function ($item, $key) {
                    if (array_key_exists($item, config('notice.letter_recipients', [])) && TokenAuth::model() !== $item && in_array('Illuminate\Notifications\Notifiable', class_uses($item))) {
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
            /* Available user types */
            $types = collect(self::$userTypes)->map(function ($item, $key) use ($sender) {
                if (in_array($sender, config('notice.letter_recipients', [])[$item['class']])) {
                    return $item;
                }
                return null;
            })->reject(function ($item) {
                return !isset($item);
            })->all();
        } else {
            $types = [];
        }
        /* Return result */
        if (is_null($guard)) {
            if (count($column) > 0) {
                /* Forget column */
                $forget = array_diff(self::$columns, $column);
                /* Get result */
                $types = collect($types)->map(function ($item) use ($forget) {
                    return collect($item)->forget($forget)->all();
                })->all();
            }
        } else {
            /* Get type */
            if (isset($types[$guard])) {
                $types = $types[$guard];
                if (count($column) > 0) {
                    /* Forget column */
                    $forget = array_diff(self::$columns, $column);
                    /* Get result */
                    $types = collect($types)->forget($forget)->all();
                }
            } else {
                throw new ModelNotFoundException('Query Notifiable: No query results for types: Unknown type \'' . $guard . '\'.');
            }
        }

        return $types;
    }
}
