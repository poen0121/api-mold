<?php

namespace App\Transformers\System;

use League\Fractal\TransformerAbstract;
use App\Entities\System\DataActivity;
use Lang;

/**
 * Class DataActivityTransformer.
 *
 * @package namespace App\Transformers\System;
 */
class DataActivityTransformer extends TransformerAbstract
{
    /**
     * Transform the DataActivity entity.
     *
     * @param \App\Entities\System\DataActivity $model
     *
     * @return array
     */
    public function transform(DataActivity $model)
    {
        return collect([
            'id' => (int) $model->id,
            'type' => $model->log_name,
            'name' => $model->log_name,
            'description' => $model->description,
            'target_id' => $model->target_id,
            'target_name' => $model->target,
            'trigger_id' => $model->trigger_id,
            'trigger_name' => $model->trigger,
            'properties' => $model->properties,

            /* place your other model properties here */
            
            /* Timezone datetime */
            'created_at' => $model->created_at,
            // 'updated_at' => $model->updated_at
        ])->map(function ($item, $key) {
            switch($key) {
                case 'name':
                    $item = Lang::dict('activitylog', 'names.' . $item, $item);
                break;
                case 'description':
                    $item = Lang::dict('activitylog', 'descriptions.' . $item, $item);
                break;
                case 'target_name':
                    $item = Lang::dict('activitylog', 'subject_types.' . $item, $item);
                break;
                case 'trigger_name':
                    $item = Lang::dict('activitylog', 'causer_types.' . $item, $item);
                break;
            }
            return (isset($item) ? $item : '');
        })->all();
    }
}
