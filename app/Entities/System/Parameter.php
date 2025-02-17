<?php

namespace App\Entities\System;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Libraries\Traits\Entity\Log\DataActivity;
use App\Libraries\Traits\Entity\Swap\TimeEquation;
use Lang;

/**
 * Class Parameter.
 *
 * @package namespace App\Entities\System;
 */
class Parameter extends Model implements Transformable
{
    use TransformableTrait;
    use DataActivity;
    use TimeEquation;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_parameters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'value'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    
    /**
    * Get the slug description
    *
    * @return string|null
    */
    public function getDescriptionAttribute(): ?string
    {
        if (isset($this->slug)) {
            return Lang::dict('system.parameters.' . $this->slug . '.document', 'description', 'Undefined');
        }
        return null;
    }

    /**
    * Get the value rule description
    *
    * @return array|null
    */
    public function getRuleAttribute(): ?array
    {
        if (isset($this->slug)) {
            return Lang::dict('system.parameters.' . $this->slug . '.document', 'value', []);
        }
        return null;
    }
}
