<?php

namespace App\Entities\System;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Libraries\Traits\Entity\Swap\TimeEquation;
use App\Libraries\Traits\Entity\Column\Authority;
use Str;

/**
 * Class AuthoritySnapshot.
 *
 * @package namespace App\Entities\System;
 */
class AuthoritySnapshot extends Model implements Transformable
{
    use TransformableTrait;
    use TimeEquation;
    use Authority;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'authority_snapshots';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'authority'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The other attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Eloquent bootstrap any operation.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            /* Create id unique code */
            $model->{$model->getKeyName()} = Str::uuid()->getHex();
        });
    }

    /**
     * The key incrementing mode.
     *
     * @var bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * The key type.
     *
     * @var string
     */
    public function getKeyType()
    {
        return 'string';
    }
}
