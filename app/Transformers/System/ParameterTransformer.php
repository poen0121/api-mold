<?php

namespace App\Transformers\System;

use League\Fractal\TransformerAbstract;
use App\Entities\System\Parameter;

/**
 * Class ParameterTransformer.
 *
 * @package namespace App\Transformers\System;
 */
class ParameterTransformer extends TransformerAbstract
{
    /**
     * Transform the Parameter entity.
     *
     * @param \App\Entities\System\Parameter $model
     *
     * @return array
     */
    public function transform(Parameter $model)
    {
        return collect([
            'id' => (int) $model->id,
            'slug' => $model->slug,
            'value' => $model->value,
            'rule' => $model->rule,
            'description' => $model->description,

            /* place your other model properties here */
            
            /* Timezone datetime */
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ])->map(function ($item, $key) {
            return (isset($item) ? $item : '');
        })->all();
    }
}
