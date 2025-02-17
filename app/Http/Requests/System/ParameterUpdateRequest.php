<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class ParameterUpdateRequest extends FormRequest
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
        $slug = $this->route()->parameter('slug');
        
        $rule = 'required';

        if (isset($slug)) {
            /* Validate rule */
            $rules = config('sp.rules');
            if (isset($rules[$slug])) {
                $rule = $rules[$slug];
                /* Check rule type */
                if (is_string($rule) && strpos($rule, 'required') === false) {
                    $rule = 'required|' . $rule;
                } elseif (is_array($rule) && strpos(implode('|', $rule), 'required') === false) {
                    array_unshift($rule, 'required');
                }
            }
        }

        return [
            'value' => $rule
        ];
    }
}
