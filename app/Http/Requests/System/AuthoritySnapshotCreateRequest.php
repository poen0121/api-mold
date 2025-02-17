<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class AuthoritySnapshotCreateRequest extends FormRequest
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
            case 'system.authority.snapshot.build':
                return [
                    'name' => 'required|between:1,128',
                    'interface' => 'required|array'
                ];
            case 'system.authority.snapshot.index':
                return [
                    'creation_sort' => 'in:ASC,DESC',
                    'start' => 'date_format:Y-m-d',
                    'end' => 'date_format:Y-m-d',
                    'page' => 'required|integer|between:1,' . PHP_INT_MAX,
                    'rows' => 'integer|between:1,1000'
                ];
            default:
                return [];
        }
    }
}
