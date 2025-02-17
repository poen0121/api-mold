<?php

namespace App\Libraries\Instances\System;

use Route;
use Lang;
use Exception;

/**
 * Final Class InterfaceScope.
 *
 * @package namespace App\Libraries\Instances\System;
 */
final class InterfaceScope
{

    /**
     * The interface columns list.
     *
     * @var array
     */
    private static $columns = [
        'code',
        'interface',
        'description',
        'action'
    ];

    /**
     * Reserved item.
     *
     * @var array
     */
    private static $reserved = [];

    /**
     * All item.
     *
     * @var array
     */
    private static $all = [];

    /**
     * InterfaceScope initialization.
     *
     */
    private static function init()
    {
        if (count(self::$all) == 0) {
            /* Route base list */
            $routeCollection = Route::getRoutes();
            $description = Lang::dict('ban', 'interface', []);
            collect($routeCollection)->map(function ($item) use ($description) {
                $routeName = $item->getName();
                $middleware = $item->middleware();
                if (isset($routeName, $middleware) && in_array('api', $middleware)) {
                    self::$all[$routeName] = [
                        'code' => $routeName,
                        'interface' => $item->uri(),
                        'description' => (isset($description[$routeName]) ? $description[$routeName] : 'Undefined'),
                        'action' => collect($item->getAction())->forget(['as'])->all()
                    ];
                    /* Reserved */
                    if (! in_array('token.ban', $middleware)) {
                        self::$reserved[$routeName] = self::$all[$routeName];
                    }
                }
            });
        }
    }

    /**
     * Get all interfaces.
     *
     * @param array $column
     * column string : interface
     * column string : code
     * column string : description
     * column array : action
     *
     * @return array
     * @throws \Exception
     */
    public static function all(array $column = []): array
    {
        /* Use column */
        if (count($column) > 0) {
            $diff = array_unique(array_diff($column, self::$columns));
            /* Check column name */
            if (count($diff) > 0) {
                throw new Exception('Query APIs Interface: Column not found: Unknown column ( \'' . implode('\', \'', $diff) . '\' ) in \'field list\'.');
            }
        }
        /* Scopes initialization */
        self::init();
        /* Scopes */
        $scopes = self::$all;
        /* Return result */
        if (count($column) > 0) {
            /* Forget column */
            $forget = array_diff(self::$columns, $column);
            /* Get result */
            $scopes = collect($scopes)->map(function ($item) use ($forget) {
                return collect($item)->forget($forget)->all();
            })
            ->all();
        }

        return $scopes;
    }

    /**
     * Get the list of reserved interfaces.
     *
     * @param array $column
     * column string : interface
     * column string : code
     * column string : description
     * column array : action
     *
     * @return array
     * @throws \Exception
     */
    public static function reserved(array $column = []): array
    {
        /* Use column */
        if (count($column) > 0) {
            $diff = array_unique(array_diff($column, self::$columns));
            /* Check column name */
            if (count($diff) > 0) {
                throw new Exception('Query APIs Interface: Column not found: Unknown column ( \'' . implode('\', \'', $diff) . '\' ) in \'field list\'.');
            }
        }
        /* Scopes initialization */
        self::init();
        /* Scopes */
        $scopes = self::$reserved;
        /* Return result */
        if (count($column) > 0) {
            /* Forget column */
            $forget = array_diff(self::$columns, $column);
            /* Get result */
            $scopes = collect($scopes)->map(function ($item) use ($forget) {
                return collect($item)->forget($forget)->all();
            })
            ->all();
        }

        return $scopes;
    }
    
    /**
     * Get the list of managed interfaces.
     *
     * @param array $column
     * column string : interface
     * column string : code
     * column string : description
     * column array : action
     *
     * @return array
     * @throws \Exception
     */
    public static function managed(array $column = []): array
    {
        /* Use column */
        if (count($column) > 0) {
            $diff = array_unique(array_diff($column, self::$columns));
            /* Check column name */
            if (count($diff) > 0) {
                throw new Exception('Query APIs Interface: Column not found: Unknown column ( \'' . implode('\', \'', $diff) . '\' ) in \'field list\'.');
            }
        }
        /* Scopes initialization */
        self::init();
        /* Scopes */
        $scopes = array_diff_key(self::$all, self::$reserved);
        /* Return result */
        if (count($column) > 0) {
            /* Forget column */
            $forget = array_diff(self::$columns, $column);
            /* Get result */
            $scopes = collect($scopes)->map(function ($item) use ($forget) {
                return collect($item)->forget($forget)->all();
            })
            ->all();
        }

        return $scopes;
    }

    /**
     * Get the list of allowed interfaces for ban number.
     *
     * @param integer $ban
     * @param array $column
     * column string : interface
     * column string : code
     * column string : description
     * column array : action
     *
     * @return array
     * @throws \Exception
     */
    public static function allowedByban(int $ban, array $column = []): array
    {
        /* Use column */
        if (count($column) > 0) {
            $diff = array_unique(array_diff($column, self::$columns));
            /* Check column name */
            if (count($diff) > 0) {
                throw new Exception('Query APIs Interface: Column not found: Unknown column ( \'' . implode('\', \'', $diff) . '\' ) in \'field list\'.');
            }
        }
        /* Scopes initialization */
        self::init();
        /* Scopes */
        $scopes = self::$reserved;
        $release = config('ban.release.' . $ban);
        /* Check ban */
        if (isset($release)) {
            /* AllowNamed array */
            $allowNamed = (isset($release['allow_named']) ? $release['allow_named'] : []);
            /* UnallowNamed array */
            $unallowNamed = (isset($release['unallow_named']) ? $release['unallow_named'] : []);
            /* Check allowNamed */
            if (count($allowNamed) > 0) {
                /* Check unallowNamed */
                if (! in_array('*', $unallowNamed)) {
                    /* Route all list */
                    $scopes = self::$all;
                    /* Recheck allowNamed */
                    if (! in_array('*', $allowNamed)) {
                        $scopes = collect($scopes)->map(function ($item, $key) use ($allowNamed) {
                            if (! in_array($key, $allowNamed)) {
                                /* Route name array */
                                $routeNames = explode('.', $key);
                                /* Scanning allowNamed range */
                                $routeNamePart = '';
                                foreach ($routeNames as $namePart) {
                                    $routeNamePart .= $namePart . '.';
                                    if (in_array($routeNamePart . '*', $allowNamed)) {
                                        return $item;
                                    }
                                }
                                return null;
                            } else {
                                return $item;
                            }
                        })->reject(function ($item) {
                            return !isset($item);
                        })->all();
                    }
                    /* Recheck unallowNamed */
                    if (count($unallowNamed) > 0) {
                        $scopes = collect($scopes)->map(function ($item, $key) use ($unallowNamed) {
                            if (! in_array($key, $unallowNamed)) {
                                /* Route name array */
                                $routeNames = explode('.', $key);
                                /* Scanning unallowNamed range */
                                $routeNamePart = '';
                                foreach ($routeNames as $namePart) {
                                    $routeNamePart .= $namePart . '.';
                                    if (in_array($routeNamePart . '*', $unallowNamed)) {
                                        return null;
                                    }
                                }
                                return $item;
                            }
                            return null;
                        })->reject(function ($item) {
                            return !isset($item);
                        })->all();
                    }
                    $scopes = array_merge(self::$reserved, $scopes);
                }
            }
        }
        /* Return result */
        if (count($column) > 0) {
            /* Forget column */
            $forget = array_diff(self::$columns, $column);
            /* Get result */
            $scopes = collect($scopes)->map(function ($item) use ($forget) {
                return collect($item)->forget($forget)->all();
            })
            ->all();
        }

        return $scopes;
    }

    /**
     * Get the list of managed interfaces for ban number.
     *
     * @param integer $ban
     * @param array $column
     * column string : interface
     * column string : code
     * column string : description
     * column array : action
     *
     * @return array
     * @throws \Exception
     */
    public static function managedByBan(int $ban, array $column = []): array
    {
        $allowed = self::allowedByban($ban, $column);

        return array_diff_key($allowed, self::$reserved);
    }
}
