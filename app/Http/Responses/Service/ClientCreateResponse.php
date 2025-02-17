<?php

namespace App\Http\Responses\Service;

use Illuminate\Routing\ResponseFactory;

/**
 * Class ClientCreateResponse.
 *
 * @package App\Http\Responses\Service
 */
class ClientCreateResponse extends ResponseFactory
{

    /**
     * Transform the data response.
     *
     * @param array &$data
     *
     * @return void
     */
    public function transform(array &$data)
    {
        // Adjust the response format data label

        $named = request()->route()->getName();
        switch ($named) {
            case 'auth.client.build':
                $data = collect($data)->forget([
                    'id'
                ])->all();
                break;
            case 'auth.client.index':
                $data['data'] = collect($data['data'])->map(function ($info) {
                    return collect($info)->forget([
                        'id',
                        'client_id',
                        'client_secret'
                    ])->all();
                })->all();
                break;
            case 'auth.client.read':
                $data = collect($data)->forget([
                    'id'
                ])->all();
                break;
        }
    }
}
