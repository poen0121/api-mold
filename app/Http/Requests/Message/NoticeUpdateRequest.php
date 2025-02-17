<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class NoticeUpdateRequest extends FormRequest
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
            case 'notice.mark':
                return [
                    'id' => 'required|array'
                ];
            case 'notice.remove':
                return [
                    'id' => 'required|array'
                ];
            default:
                return [];
        }
    }
}
