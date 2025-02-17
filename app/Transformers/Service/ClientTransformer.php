<?php

namespace App\Transformers\Service;

use League\Fractal\TransformerAbstract;
use App\Entities\Service\Client;

/**
 * Class ClientTransformer.
 *
 * @package namespace App\Transformers\Service;
 */
class ClientTransformer extends TransformerAbstract
{

    /**
     * Transform the Client entity.
     *
     * @param \App\Entities\Service\Client $model
     *
     * @return array
     */
    public function transform(Client $model)
    {
        return collect([
            'id' => (int) $model->id,
            'app_id' => $model->app_id,
            'name' => $model->name,
            'client_id' => $model->client_id,
            'client_secret' => $model->real_client_secret,
            'ban' => $model->ban,
            'description' => $model->description,
            'status' => $model->status,

            /* place your other model properties here */
            
            /* Timezone datetime */
            'expired_at' => $model->expired_at,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ])->map(function ($item, $key) {
            return (isset($item) ? $item : '');
        })->all();
    }
}
