<?php

namespace App\Http\Responses\System;

use Illuminate\Routing\ResponseFactory;

/**
 * Class AuthoritySnapshotCreateResponse.
 *
 * @package App\Http\Responses\System
 */
class AuthoritySnapshotCreateResponse extends ResponseFactory
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
            case 'system.authority.snapshot.build':
                $data = collect($data)->forget([
                    'authority',
                    'created_at',
                    'updated_at'
                ])->all();
                break;
            case 'system.authority.snapshot.index':
                $data['data'] = collect($data['data'])->map(function ($info) {
                    return collect($info)->forget([
                        'authority'
                    ])->all();
                })->all();
                break;
        }
    }
}
