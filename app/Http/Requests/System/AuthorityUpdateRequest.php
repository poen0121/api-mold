<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class AuthorityUpdateRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $named = $this->route()->getName();
        switch ($named) {
            case 'system.authority.global':
                return [];
            default:
                return [
                    'interface' => 'array',
                    'snapshot' => 'size:32'
                ];
        }
    }
}
