<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class NoticeCreateRequest extends FormRequest
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
            case 'notice.letter.send':
                return [
                    'subject' => 'required|between:1,128',
                    'message' => 'required|between:1,256',
                    'note' => 'array'
                ];
            default:
                return [];
        }
    }
}
