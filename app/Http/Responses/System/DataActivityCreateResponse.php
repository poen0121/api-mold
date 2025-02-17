<?php

namespace App\Http\Responses\System;

use Illuminate\Routing\ResponseFactory;

/**
 * Class DataActivityCreateResponse.
 *
 * @package App\Http\Responses\System
 */
class DataActivityCreateResponse extends ResponseFactory
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

        $data['data'] = collect($data['data'])->map(function ($info) {
            return collect($info)->forget([
                'id'
            ])->all();
        })->all();
    }
}
