<?php

namespace App\Libraries\Traits\Entity\Swap;

use App\Libraries\Instances\Swap\TimeDisplay;
use Carbon;

trait TimeEquation
{
    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param string  $key
     * @return mixed
     */
    public function __get($key)
    {
        $value = parent::__get($key);
        /* Get Carbon datetime attributes */
        $dates = $this->getDates();
        /* Set local timezone time attribute */
        if (isset($value) && in_array($key, $dates, true)) {
            /* Check local timezone time attribute */
            if (is_object($value) && get_class($value) === 'Illuminate\Support\Carbon') {
                /* Return client timezone time attribute */
                return $value->tz($this->getCTZ())->toDateTimeString();
            } elseif (is_string($value)) {
                /* Get local carbon object */
                $value = Carbon::parse($value, $this->getTZ());
                /* Return client timezone time attribute */
                return $value->tz($this->getCTZ())->toDateTimeString();
            }
        }

        return $value;
    }

    /**
    * Get the application local timezone.
    *
    * @return string
    */
    public function getTZ(): string
    {
        /* Local timezone */
        return TimeDisplay::getTZ();
    }
    
    /**
    * Get the accept client timezone.
    *
    * @return string
    */
    public function getCTZ(): string
    {
        /* Accept client timezone */
        return TimeDisplay::getCTZ();
    }

    /**
     * Swap the client timezone datetime to the local timezone datetime.
     *
     * @param mixed $value
     * @return Carbon|null
     */
    public function asLocalTime($value): ?object
    {
        return TimeDisplay::asLocalTime($value);
    }

    /**
     * Swap the local timezone datetime to the client timezone datetime.
     *
     * @param mixed $value
     * @return Carbon|null
     */
    public function asClientTime($value): ?object
    {
        return TimeDisplay::asClientTime($value);
    }
}
