<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class AuthoritySnapshotUpdateRequest extends FormRequest
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
            case 'system.authority.snapshot.rename':
                return [
                    'name' => 'required|between:1,128'
                ];
            default:
                return [];
        }
    }
}
