<?php

namespace App\Libraries\Instances\Calculator;

/**
 * Class WGS.
 *
 * @package namespace App\Libraries\Instances\Calculator;
 */
class WGS
{
    /**
     * Approximate radius of earth in meters.
     *
     * @var int
     */
    protected $earthRadius = 6367000;

    /**
     * The origin coordinate.
     *
     * @var array
     */
    protected $originCoordinate;

    /**
     * Constrain degrees to range 0 ~ 360.
     *
     * @param float $degrees
     *
     * @return float
     */
    protected function wrap360(float $degrees): float
    {
        $degrees = round($degrees, 7);
        /* Avoid rounding due to arithmetic ops if within range */
        if (0 <= $degrees && $degrees < 360) {
            return $degrees;
        }
        /* Operation confirmation range */
        return round(fmod(fmod($degrees, 360) + 360, 360), 7);
    }

    /**
     * Constrain degrees to range -180 ~ +180.
     *
     * @param float $degrees
     *
     * @return float
     */
    protected function wrap180(float $degrees): float
    {
        $degrees = round($degrees, 7);
        /* Avoid rounding due to arithmetic ops if within range */
        if (-180 < $degrees && $degrees <= 180) {
            return $degrees;
        }
        /* Operation confirmation range */
        return round(fmod($degrees + 540, 360) - 180, 7);
    }

    /**
     * Constrain degrees to range -90..+90.
     *
     * @param float $degrees
     *
     * @return float
     */
    protected function wrap90(float $degrees): float
    {
        $degrees = round($degrees, 7);
        /* Avoid rounding due to arithmetic ops if within range */
        if (-90 <= $degrees && $degrees <= 90) {
            return $degrees;
        }
        /* Operation confirmation range */
        return round(abs(fmod(fmod($degrees, 360) + 270, 360) - 180) - 90, 7);
    }

    /**
     * Measure distance in meters based on the start and end coordinates by WGS84.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     *
     * @return float
     */
    private function calcDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        /* Convert these degrees to radians to work with the formula */
        $lat1 = deg2rad($this->wrap90($lat1));
        $lon1 = deg2rad($this->wrap180($lon1));
        $lat2 = deg2rad($this->wrap90($lat2));
        $lon2 = deg2rad($this->wrap180($lon2));
        /*
        Using the
        Haversine formula
        http://en.wikipedia.org/wiki/Haversine_formula
        calculate the distance
        */
        $calcLatitude = $lat2 - $lat1;
        $calcLongitude = $lon2 - $lon1;
        $calcFormula = sin($calcLatitude / 2) * sin($calcLatitude / 2) + cos($lat1) * cos($lat2) * sin($calcLongitude / 2) * sin($calcLongitude / 2);
        $calcFormula = 2 * atan2(sqrt($calcFormula), sqrt(1-$calcFormula));
        $distance = $this->earthRadius * $calcFormula;
        return round($distance, 2);
    }

    /**
     * Set origin coordinate.
     *
     * @param float $latitude
     * @param float $longitude
     *
     * @return object
     */
    public function origin(float $latitude, float $longitude): object
    {
        $this->originCoordinate = [$latitude, $longitude];
        return $this;
    }

    /**
     * Measure the azimuth in a clockwise direction with north according to the start and end coordinates by WGS84.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     *
     * @return float
     */
    private function calcAzimuth(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        /* Convert these degrees to radians to work with the formula */
        $lat1 = deg2rad($this->wrap90($lat1));
        $lat2 = deg2rad($this->wrap90($lat2));
        $calcLongitude = deg2rad($this->wrap180($lon2) - $this->wrap180($lon1));
        $y = sin($calcLongitude) * cos($lat2);
        $x = cos($lat1) * sin($lat2) - sin($lat1) * cos($lat2) * cos($calcLongitude);
        $radian = atan2($y, $x);
        return $this->wrap360(rad2deg($radian));
    }

    /**
     * Measure the moving distance in meters based on the moving coordinates.
     *
     * @param float $latitude
     * @param float $longitude
     *
     * @return float|null
     */
    public function distance(float $latitude, float $longitude): ?float
    {
        if (isset($this->originCoordinate)) {
            return $this->calcDistance($this->originCoordinate[0], $this->originCoordinate[1], $latitude, $longitude);
        }
        return null;
    }

    /**
     * Measure the azimuth in a clockwise direction with north according to the start and end coordinates.
     *
     * @param float $latitude
     * @param float $longitude
     *
     * @return float|null
     */
    public function azimuth(float $latitude, float $longitude): ?float
    {
        if (isset($this->originCoordinate)) {
            return $this->calcAzimuth($this->originCoordinate[0], $this->originCoordinate[1], $latitude, $longitude);
        }
        return null;
    }

    /**
     * According to the starting coordinate , azimuth and distance, the destination coordinates are calculated.
     *
     * @param float $azimuth
     * @param float $distance
     *
     * @return array|null
     */
    public function destination(float $azimuth, float $distance): ?array
    {
        if (isset($this->originCoordinate)) {
            $latOrigin = deg2rad($this->wrap90($this->originCoordinate[0]));
            $lonOrigin = deg2rad($this->wrap180($this->originCoordinate[1]));
            /* Angular distance in radians */
            $angular = $distance/$this->earthRadius;
            $radian = deg2rad($azimuth);
            $latitude = sin($latOrigin) * cos($angular) + cos($latOrigin) * sin($angular) * cos($radian);
            $y = sin($radian) * sin($angular) * cos($latOrigin);
            $x = cos($angular) - sin($latOrigin) * $latitude;
            $latitude = asin($latitude);
            $longitude = $lonOrigin + atan2($y, $x);
            /* Coordinate */
            return [
                $this->wrap90(rad2deg($latitude)),
                $this->wrap180(rad2deg($longitude))
            ];
        }
        return null;
    }
}
