<?php

namespace App\Libraries\Instances\Detector;

/**
 * Final Class Lottery.
 *
 * @package namespace App\Libraries\Instances\Detector;
 */
final class Lottery
{
    /**
     * The total balls.
     *
     * @var int
     */
    private $totalBalls = 0;

    /**
     * The weight percentage value of the strengthening mode.
     *
     * @var int
     */
    private $strengthen = 0;

    /**
     * The weight percentage value of the weakening mode.
     *
     * @var int
     */
    private $weaken = 0;

    /**
     * Lottery constructor.
     *
     * @param int $totalBalls
     *
     * @return void
     */
    public function __construct(int $totalBalls)
    {
        $this->totalBalls = $totalBalls;
    }

    /**
     * Create a ball box.
     *
     * @param int $totalBalls
     *
     * @return object
     */
    public static function inBox(int $totalBalls): object
    {
        return new self($totalBalls);
    }

    /**
     * Pick colored balls to successfully count.
     *
     * @param int $colorBalls
     * @param int $rounds
     *
     * @return int
     */
    public function pick(int $colorBalls, int $rounds = 1): int
    {
        $totalBalls = $this->totalBalls;
        if ($colorBalls > 0 && $totalBalls >= 0 && $rounds > 0) {
            /* 100 % */
            if ($colorBalls >= $totalBalls) {
                return $rounds;
            }
            /* Weight mode */
            if (($this->strengthen > 0 || $this->weaken > 0) && $totalBalls < (PHP_INT_MAX / 100)) {
                /* Base unit number of colored balls */
                $unitBalls = $colorBalls;
                /* Available number of total balls */
                $totalBalls = $totalBalls * 100;
                /* Available number of colored balls */
                $colorBalls = $colorBalls * 100;
                /* Weight number of colored balls */
                $weight = $this->strengthen - $this->weaken;
                $colorBalls = $colorBalls + ($unitBalls * $weight);
                $colorBalls = ($colorBalls >= $totalBalls ? ($totalBalls - $unitBalls) : $colorBalls);
                /* Restore mode */
                $this->weaken = 0;
                $this->strengthen = 0;
            }
            /* Expected value */
            $expect = floor($rounds * ($colorBalls / $totalBalls));
            /* Variance */
            $variance = $rounds * ($colorBalls / $totalBalls) * (1 - ($colorBalls / $totalBalls));
            /* Standard deviation */
            $deviation = floor(sqrt($variance));
            /* Ensure accuracy */
            if ($expect == 0 || $deviation == 0) {
                $deviation = 0;
                for($i = 0; $i < $rounds; $i++) {
                    $deviation += (mt_rand(1, $totalBalls) <= $colorBalls ? 1 : 0);
                }
            } else {
                /* Approximation distributed */
                $deviation = mt_rand($expect - $deviation, $expect + $deviation);
            }
            return $deviation;
        }
        return 0;
    }

    /**
     * Get proportional weight.
     *
     * @param int $base
     * 
     * @return int
     */
    private function weight(int $base): int
    {
        /* Curve percentage */ 
        if ($base > 1) {
            $base = floor(log($base, 2)) + floor(log($base, 3));
            return ($base > 99 ? 99 : $base);
        }
        return 0;
    }

    /**
     * Use strengthening mode.
     *
     * @param int $base
     * 
     * @return object
     */
    public function strengthen(int $base): object
    {
        $this->strengthen = $this->weight($base);
        return $this;
    }

    /**
     * Use weakening mode.
     *
     * @param int $base
     * 
     * @return object
     */
    public function weaken(int $base): object
    {
        $this->weaken = $this->weight($base);
        return $this;
    }
}
