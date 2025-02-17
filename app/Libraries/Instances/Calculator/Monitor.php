<?php

namespace App\Libraries\Instances\Calculator;

use Illuminate\Support\Facades\Redis;
use Carbon;

/**
 * Class Monitor.
 *
 * @package namespace App\Libraries\Instances\Calculator;
 */
class Monitor
{
    /**
     * The redis.
     *
     * @var object
     */
    private $redis;

    /**
     * Data pool name.
     *
     * @var string
     */
    private $poolName;

    /**
     * Data valid base time.
     *
     * @var int
     */
    private $baseTime;

    /**
     * Data valid mark time.
     *
     * @var int
     */
    private $markTime;

    /**
     * Monitor constructor.
     * 
     * @param string $poolName
     * 
     * @return void
     */
    public function __construct(string $poolName)
    {
        $now = Carbon::now();
        $this->poolName = 'Monitor:' . $poolName;
        $this->baseTime = $now->format('Ymdhis');
        $this->markTime = $now->copy()->addMinutes(config('monitor.ttl', 1))->format('Ymdhis');
        $this->redis = Redis::connection(config('monitor.connection'));
    }

    /**
     * Sit in the data pool.
     *
     * @param string $mark
     *
     * @return bool
     */
    public function sit(string $mark): bool
    {
        return $this->redis->zadd($this->poolName, $this->markTime, $mark);
    }

    /**
     * Get the valid count.
     *
     * @return int
     */
    public function count(): int
    {
        /* Forget old data */
        $this->redis->zremrangebyscore($this->poolName, 0, $this->baseTime);
        /* Valid count */
        return $this->redis->zcount($this->poolName, $this->baseTime, $this->markTime);
    }
}
