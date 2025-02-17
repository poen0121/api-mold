<?php

namespace App\Entities\System;

use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Libraries\Traits\Entity\Swap\TimeEquation;
use Spatie\Activitylog\Models\Activity;
use Lang;
use Carbon;

/**
 * Class DataActivity.
 *
 * @package namespace App\Entities\System;
 */
class DataActivity extends Activity implements Transformable
{
    use TransformableTrait;
    use TimeEquation;

    /**
     * Eloquent bootstrap any operation.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $now = Carbon::now();
            $model->created_at = $now;
            $model->updated_at = $now;
            $model->month = (int) $now->format('m');
        });
    }

    /**
     * Get the log target id.
     *
     * @return string|null
     */
    public function getTargetIdAttribute(): ?string
    {
        if (isset($this->subject_type, $this->subject_id)) {
            return (string) (in_array('App\Libraries\Traits\Entity\Swap\Identity', class_uses($this->subject_type)) ? app($this->subject_type)->asPrimaryTid($this->subject_id) : $this->subject_id);
        }
        return null;
    }

    /**
     * Get the log target type class name.
     *
     * @return string|null
     */
    public function getTargetAttribute(): ?string
    {
        if (isset($this->subject_type)) {

            $types = Lang::dict('activitylog', 'subject_types');

            return (isset($types[$this->subject_type]) ? $this->subject_type : 'Undefined');
        }
        return null;
    }

    /**
     * Get the log trigger id.
     *
     * @return string|null
     */
    public function getTriggerIdAttribute(): ?string
    {
        if (isset($this->causer_type, $this->causer_id)) {
            return (string) (in_array('App\Libraries\Traits\Entity\Swap\Identity', class_uses($this->causer_type)) ? app($this->causer_type)->asPrimaryTid($this->causer_id) : $this->causer_id);
        }
        return null;
    }

     /**
     * Get the log trigger type class name.
     *
     * @return string|null
     */
    public function getTriggerAttribute(): ?string
    {
        if (isset($this->causer_type)) {

            $types = Lang::dict('activitylog', 'causer_types');

            return (isset($types[$this->causer_type]) ? $this->causer_type : 'Undefined');
        }
        return null;
    }
}
