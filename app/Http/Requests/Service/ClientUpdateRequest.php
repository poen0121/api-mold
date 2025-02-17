<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        $named = $this->route()->getName();
        switch ($named) {
            case 'auth.client.rename':
                return [
                    'name' => 'required|between:1,128'
                ];
            case 'auth.client.rewrite.ban':
                return [
                    'ban' => [
                        'required',
                        'integer',
                        'between:0,' . (count(config('ban.release')) - 1)
                    ]
                ];
            case 'auth.client.reschedule':
                return [
                    'expire' => 'date_format:Y-m-d H:i:s'
                ];
            default:
                return [];
        }
    }
}
