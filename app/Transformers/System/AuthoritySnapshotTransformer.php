<?php

namespace App\Transformers\System;

use League\Fractal\TransformerAbstract;
use App\Entities\System\AuthoritySnapshot;

/**
 * Class AuthoritySnapshotTransformer.
 *
 * @package namespace App\Transformers\System;
 */
class AuthoritySnapshotTransformer extends TransformerAbstract
{
    /**
     * Transform the AuthoritySnapshot entity.
     *
     * @param \App\Entities\System\AuthoritySnapshot $model
     *
     * @return array
     */
    public function transform(AuthoritySnapshot $model)
    {
        return collect([
            'id' => $model->id,
            'name' => $model->name,
            'authority' => $model->authority,
            /* place your other model properties here */
            
            /* Timezone datetime */
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ])->map(function ($item, $key) {
            return (isset($item) ? $item : '');
        })->all();
    }
}
