<?php

namespace App\Libraries\Instances\Detector;

use Illuminate\Validation\ValidationException;
use Validator;
use Carbon;

/**
 * Final Class Period.
 *
 * @package namespace App\Libraries\Instances\Detector;
 */
final class Period
{
    /**
     * The check mode.
     *
     * @var string
     */
    private $mode;

    /**
     * The start datetime.
     *
     * @var string
     */
    private $start;
    
    /**
     * The end datetime.
     *
     * @var string
     */
    private $end;
    
    /**
     * Period constructor.
     *
     * @param string $mode
     * @param string $start
     * @param string $end
     *
     * @return void
     */
    public function __construct(string $mode, string $start, string $end)
    {
        if ($mode === 'onClock' || $mode === 'onSchedule') {
            $this->mode = $mode;
            $this->start = $start;
            $this->end = $end;
        }
    }

    /**
     * Confirmation time is within range.
     *
     * @param string $time
     * @param bool $include
     *
     * @return bool
     */
    public function within(string $time, bool $include = true): bool
    {
        switch ($this->mode) {
            case 'onClock':
                try {
                    Validator::make([
                        'start' => $this->start,
                        'end' => $this->end,
                        'time' => $time
                    ], [
                        'start' => 'required|date_format:H:i:s',
                        'end' => 'required|date_format:H:i:s',
                        'time' => 'required|date_format:H:i:s'
                    ])->validate();
                    if ($include) {
                        if (($this->end >= $this->start && $time >= $this->start && $time <= $this->end) || ($this->end < $this->start && (($time >= $this->start && $time <= '23:59:59') || ($time >= '00:00:00' && $time <= $this->end)))) {
                            return true;
                        }
                    } else {
                        if (($this->end >= $this->start && $time > $this->start && $time < $this->end) || ($this->end < $this->start && (($time > $this->start && $time <= '23:59:59') || ($time >= '00:00:00' && $time < $this->end)))) {
                            return true;
                        }
                    }
                } catch (\Throwable $th) {
                    if ($th instanceof ValidationException) {
                        return false;
                    }
                    throw $th;
                }
            break;
            case 'onSchedule':
                try {
                    Validator::make([
                        'start' => $this->start,
                        'end' => $this->end,
                        'time' => $time
                    ], [
                        'start' => 'required|date_format:Y-m-d H:i:s',
                        'end' => 'required|date_format:Y-m-d H:i:s',
                        'time' => 'required|date_format:Y-m-d H:i:s'
                    ])->validate();
                    if ($include) {
                        if (($this->end >= $this->start && $time >= $this->start && $time <= $this->end) || ($this->end < $this->start && $time <= $this->start && $time >= $this->end)) {
                            return true;
                        }
                    } else {
                        if (($this->end >= $this->start && $time > $this->start && $time < $this->end) || ($this->end < $this->start && $time < $this->start && $time > $this->end)) {
                            return true;
                        }
                    }
                } catch (\Throwable $th) {
                    if ($th instanceof ValidationException) {
                        return false;
                    }
                    throw $th;
                }
            break;
        }
        return false;
    }

    /**
     * Get the time difference in seconds.
     *
     * @return int|null
     */
    public function diffSec(): ?int
    {
        switch ($this->mode) {
            case 'onClock':
                try {
                    Validator::make([
                        'start' => $this->start,
                        'end' => $this->end
                    ], [
                        'start' => 'required|date_format:H:i:s',
                        'end' => 'required|date_format:H:i:s'
                    ])->validate();
                    $start = Carbon::parse($this->start);
                    $end = Carbon::parse($start->toDateString() . ' ' . $this->end);
                    if ($this->end >= $this->start) {
                        return $start->diffInSeconds($end);
                    } else {
                        return $end->addDay(1)->diffInSeconds($start);
                    }
                } catch (\Throwable $th) {
                    if ($th instanceof ValidationException) {
                        return null;
                    }
                    throw $th;
                }
            break;
            case 'onSchedule':
                try {
                    Validator::make([
                        'start' => $this->start,
                        'end' => $this->end
                    ], [
                        'start' => 'required|date_format:Y-m-d H:i:s',
                        'end' => 'required|date_format:Y-m-d H:i:s'
                    ])->validate();
                    return Carbon::parse($this->start)->diffInSeconds(Carbon::parse($this->end));
                } catch (\Throwable $th) {
                    if ($th instanceof ValidationException) {
                        return null;
                    }
                    throw $th;
                }
            break;
        }
        return null;
    }

    /**
     * Get the cycle count of the time difference.
     *
     * @param int $cycleSec
     * @param mixed &$remainder
     *
     * @return int|null
     */
    public function cycleCount(int $cycleSec, &$remainder = null): ?int
    {
        $remainder = null;
        if ($cycleSec > 0) {
            $seconds = $this->diffSec();
            if (isset($seconds)) {
                $remainder = (int) bcmod($seconds, $cycleSec);
                return bcdiv($seconds, $cycleSec);
            }
        }
        return null;
    }

    /**
     * Create a clock time range mode.
     *
     * @param string $startMoment
     * @param string $endMoment
     *
     * @return object
     */
    public static function onClock(string $startMoment, string $endMoment): object
    {
        return new self('onClock', $startMoment, $endMoment);
    }

    /**
     * Create a date time range mode.
     *
     * @param string $startTime
     * @param string $endTime
     *
     * @return object
     */
    public static function onSchedule(string $startTime, string $endTime): object
    {
        return new self('onSchedule', $startTime, $endTime);
    }

    /**
     * Verify a clock time format.
     *
     * @param string $time
     *
     * @return bool
     */
    public static function verifyMoment(string $time): bool
    {
        try {
            Validator::make([
                'time' => $time
            ], [
                'time' => 'required|date_format:H:i:s'
            ])->validate();
            return true;
        } catch (\Throwable $th) {
            if ($th instanceof ValidationException) {
                return false;
            }
            throw $th;
        }
    }

    /**
     * Verify a date time format.
     *
     * @param string $time
     *
     * @return bool
     */
    public static function verifyDatetime(string $time): bool
    {
        try {
            Validator::make([
                'time' => $time
            ], [
                'time' => 'required|date_format:Y-m-d H:i:s'
            ])->validate();
            return true;
        } catch (\Throwable $th) {
            if ($th instanceof ValidationException) {
                return false;
            }
            throw $th;
        }
    }
}
