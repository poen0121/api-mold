<?php

namespace App\Libraries\Instances\Feature;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * Final Class Provider.
 *
 * @package namespace App\Libraries\Instances\Feature;
 */
final class Provider
{
    /**
     * The feature columns list.
     *
     * @var array
     */
    private static $columns = [
        'class',
        'code',
        'description',
        'arguments',
        'responses'
    ];

    /**
     * Get a list of class for the existing feature provider.
     *
     * @return array
     */
    public static function getProvider(): array
    {
        return array_unique(config('feature.providers'));
    }

    /**
     * Get the configured feature model tag name.
     *
     * @param mixed $abstract
     *
     * @return string|null
     */
    public static function getModelTag($abstract): ?string
    {
        /* Get abstract name */
        $abstract = (is_object($abstract) ? get_class($abstract) : (is_string($abstract) ? $abstract : null));
        if (isset($abstract)) {
            $models = array_unique(config('feature.models'));
            $tag = array_search($abstract, $models);
            return (is_string($tag) ? $tag : null);
        }
        return null;
    }

    /**
     * Get a list of class for the existing feature release by model.
     *
     * @param mixed $abstract
     *
     * @return array
     */
    public static function getRelease($abstract): array
    {
        /* Check resource */
        if ($modelTag = self::getModelTag($abstract)) {
            $release = config('feature.release');
            /* Check resource */
            if (isset($release[$modelTag])) {
                $features = array_unique($release[$modelTag]);
                return array_intersect_key(self::getProvider(), array_flip($features));
            }
        }
        return [];
    }

    /**
     * Get a list of data for the existing feature document.
     *
     * @param array $features
     * @param array $column
     * column string : class
     * column string : code
     * column string : description
     * column array : arguments
     * column array : responses
     * @param string|null $code
     *
     * @return array
     * @throws \Exception
     */
    public static function getDoc(array $features, array $column = [], ?string $code = null): array
    {
        /* Use column */
        if (count($column) > 0) {
            $diff = array_unique(array_diff($column, self::$columns));
            /* Check column name */
            if (count($diff) > 0) {
                throw new Exception('Query Feature: Column not found: Unknown column ( \'' . implode('\', \'', $diff) . '\' ) in \'field list\'.');
            }
        }
        /* Build documenies */
        $documenies = [];
        foreach ($features as $class) {
            $obj = app($class);
            $key = $obj->getCode();
            if (isset($key) && !isset($documenies[$key])) {
                $documenies[$key] = [];
                $documenies[$key][self::$columns[0]] = $class;
                $documenies[$key][self::$columns[1]] = $key;
                $documenies[$key][self::$columns[2]] = $obj->getDescription();
                $documenies[$key][self::$columns[3]] = $obj->getArgumentsDescription();
                $documenies[$key][self::$columns[4]] = $obj->getResponsesDescription();
            }
        }
        /* Return result */
        if (is_null($code)) {
            if (count($column) > 0) {
                /* Forget column */
                $forget = array_diff(self::$columns, $column);
                /* Get result */
                $documenies = collect($documenies)->map(function ($item) use ($forget) {
                    return collect($item)->forget($forget)->all();
                })
                    ->all();
            }
            return $documenies;
        } else {
            if (isset($documenies[$code])) {
                if (count($column) > 0) {
                    /* Forget column */
                    $forget = array_diff(self::$columns, $column);
                    /* Get result */
                    return collect($documenies[$code])->forget($forget)->all();
                } else {
                    return $documenies[$code];
                }
            } else {
                throw new ModelNotFoundException('Query Feature: No query results for providers: Unknown code \'' . $code . '\'.');
            }
        }
    }
}
