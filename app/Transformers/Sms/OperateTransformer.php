<?php

namespace App\Transformers\Sms;

use League\Fractal\TransformerAbstract;
use App\Entities\Sms\Operate;
use Lang;

/**
 * Class OperateTransformer.
 *
 * @package namespace App\Transformers\Sms;
 */
class OperateTransformer extends TransformerAbstract
{
    /**
     * Transform the Operate entity.
     *
     * @param \App\Entities\Sms\Operate $model
     *
     * @return array
     */
    public function transform(Operate $model)
    {
        return collect([
            'serial' => $model->serial,
            'source_id' => $model->source_id,
            'source' => $model->source,
            'source_name' => $model->source,
            'telecomer' => $model->telecomer,
            'telecomer_name' => $model->telecomer,
            'phone' => $model->notify_phone,
            'message' => $model->notify_message,
            'result' => $model->notify_result,
            'operate' => $model->operate,

            /* place your other model properties here */
            
            /* Timezone datetime */
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ])->map(function ($item, $key) {
            if ($key == 'source_name') {
                return Lang::dict('sms', 'sourceables.' . $item, $item);
            } elseif ($key == 'telecomer_name') {
                return Lang::dict('sms', 'notifications.' . $item, $item);
            } else {
                return (isset($item) ? $item : '');
            }
        })->all();
    }
}
