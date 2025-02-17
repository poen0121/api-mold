<?php

namespace App\Http\Responses\Service;

use Illuminate\Routing\ResponseFactory;

/**
 * Class AuthCreateResponse.
 *
 * @package App\Http\Responses\Service
 */
class AuthCreateResponse extends ResponseFactory
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
            case 'auth.read.service':
                $data = collect($data)->forget([
                    'id',
                    'client_id',
                    'client_secret',
                    'status'
                ])->all();
                break;
        }
    }
}
