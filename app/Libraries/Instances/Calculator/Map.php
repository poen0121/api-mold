<?php

namespace App\Libraries\Instances\Calculator;

use App\Libraries\Instances\Calculator\WGS;
use Illuminate\Support\Facades\Redis;

/**
 * Final Class Map.
 *
 * @package namespace App\Libraries\Instances\Calculator;
 */
final class Map extends WGS
{
    /**
     * The redis.
     *
     * @var object
     */
    private $redis;

    /**
     * Put save landmarks.
     *
     * @var array
     */
    private $putLandmarks = [];

    /**
     * Put save landmarks data.
     *
     * @var array
     */
    private $putLandmarksData = [];

    /**
     * Map constructor.
     *
     * @param string $mapName
     * @return void
     */
    public function __construct(string $mapName)
    {
        $this->mapIndex = 'MapI:' . $mapName;
        $this->mapData = 'MapD:' . $mapName;
        $this->redis = Redis::connection(config('map.connection'));
    }

    /**
     * Put the landmark of the map landmarks.
     *
     * @param string $markName
     * @param float $latitude
     * @param float $longitude
     * @param array $data
     * @return bool
     */
    public function put(string $markName, float $latitude, float $longitude, $data = []): bool
    {
        if (isset($markName[0]) && ($data = json_encode($data))) {
            /* Redis geo format */
            $this->putLandmarks[] = $this->wrap180($longitude);
            $this->putLandmarks[] = $this->wrap90($latitude);
            $this->putLandmarks[] = $markName;
            /* Map data */
            $this->putLandmarksData[$markName] = $data;
            return true;
        }
        return false;
    }

    /**
     * Save the location of the map landmarks.
     *
     * @return bool
     */
    public function save(): bool
    {
        $count = count($this->putLandmarks);
        if ($count > 0 && $count % 3 === 0) {
            /* Index */
            array_unshift($this->putLandmarks, $this->mapIndex);
            call_user_func_array([$this->redis, 'geoAdd'], $this->putLandmarks);
            /* Data */
            $this->redis->hmSet($this->mapData, $this->putLandmarksData);
            $this->putLandmarks = [];
            $this->putLandmarksData = [];
            return true;
        }
        return false;
    }

    /**
     * Get the landmark latitude and longitude of the map landmarks.
     *
     * @param array $landmarks
     * @return array
     */
    public function get(array $landmarks): array
    {
        $landmarks = array_values(array_unique($landmarks));
        array_unshift($landmarks, $this->mapIndex);
        $result = call_user_func_array([$this->redis, 'geoPos'], $landmarks);
        if (is_array($result)) {
            array_shift($landmarks);
            $result = collect($result)->map(function ($landmark, $key) use ($landmarks) {
                if (count($landmark) > 0) {
                    return [
                        'lat' =>  $this->wrap90($landmark[1]),
                        'lon' =>  $this->wrap180($landmark[0]),
                        'mark' => $landmarks[$key]
                    ];
                }
                return null;
            })->reject(function ($landmark) {
                return !isset($landmark);
            })->keyBy('mark')->all();
            /* Get data */
            if (count($result) > 0) {
                $landmarks = array_keys($result);
                $data = $this->redis->hmGet($this->mapData, $landmarks);
                $data = array_combine($landmarks, $data);
                return collect($result)->map(function ($landmark, $mark) use ($data) {
                    $landmark['data'] = (json_decode($data[$mark], true) ?? []);
                    return $landmark;
                })->all();
            }
        }
        return [];
    }

    /**
     * Remove the landmark of the map landmarks.
     *
     * @param array $landmarks
     * @return void
     */
    public function remove(array $landmarks)
    {
        $landmarks = array_values(array_unique($landmarks));
        array_unshift($landmarks, $this->mapIndex);
        call_user_func_array([$this->redis, 'zrem'], $landmarks);
        array_shift($landmarks);
        array_unshift($landmarks, $this->mapData);
        call_user_func_array([$this->redis, 'hdel'], $landmarks);
    }

    /**
     * Measure the moving distance in meters based on the landmarks.
     *
     * @param string $markName1
     * @param string $markName2
     *
     * @return float|null
     */
    public function distanceByLandmark(string $markName1, string $markName2): ?float
    {
        $distance = $this->redis->geoDist($this->mapIndex, $markName1, $markName2, 'm');
        return (isset($distance) && $distance !== false ? round($distance, 2) : null);
    }

    /**
     * Use the landmark radar to search for landmark information on the map through the radius distance unit meter by landmark.
     *
     * @param string $markName
     * @param int $radius
     * @return array
     */
    public function radarByLandmark(string $markName, int $radius): array
    {
        $result = $this->redis->geoRadiusByMember($this->mapIndex, $markName, $radius, 'm', ['WITHDIST','WITHCOORD']);
        if (is_array($result)) {
            $result = collect($result)->map(function ($landmark) use ($markName) {
                if (isset($landmark[0]) && $markName !== $landmark[0]) {
                    return [
                        'lat' =>  $this->wrap90($landmark[2][1]),
                        'lon' =>  $this->wrap180($landmark[2][0]),
                        'mark' => $landmark[0],
                        'dist' => round($landmark[1], 2)
                    ];
                }
                return null;
            })->reject(function ($landmark) {
                return !isset($landmark);
            })->keyBy('mark')->all();
            /* Get data */
            if (count($result) > 0) {
                $landmarks = array_keys($result);
                $data = $this->redis->hmGet($this->mapData, $landmarks);
                $data = array_combine($landmarks, $data);
                return collect($result)->map(function ($landmark, $mark) use ($data) {
                    $landmark['data'] = (json_decode($data[$mark], true) ?? []);
                    return $landmark;
                })->all();
            }
        }
        return [];
    }

    /**
     * Use the coordinate radar to search for landmark information on the map through the radius distance unit meter.
     *
     * @param float $latitude
     * @param float $longitude
     * @param int $radius
     * @return array
     */
    public function radarByCoordinate(float $latitude, float $longitude, int $radius): array
    {
        $result = $this->redis->geoRadius($this->mapIndex, $this->wrap180($longitude), $this->wrap90($latitude), $radius, 'm', ['WITHDIST','WITHCOORD']);
        if (is_array($result)) {
            $result = collect($result)->map(function ($landmark) {
                return [
                    'lat' =>  $this->wrap90($landmark[2][1]),
                    'lon' =>  $this->wrap180($landmark[2][0]),
                    'mark' => $landmark[0],
                    'dist' => round($landmark[1], 2)
                ];
            })->keyBy('mark')->all();
            /* Get data */
            if (count($result) > 0) {
                $landmarks = array_keys($result);
                $data = $this->redis->hmGet($this->mapData, $landmarks);
                $data = array_combine($landmarks, $data);
                return collect($result)->map(function ($landmark, $mark) use ($data) {
                    $landmark['data'] = (json_decode($data[$mark], true) ?? []);
                    return $landmark;
                })->all();
            }
        }
        return [];
    }
}
