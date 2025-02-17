<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class BulletinCreateRequest extends FormRequest
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
            case 'notice.bulletin.build':
                return [
                    'subject' => 'required|between:1,128',
                    'message' => 'required|between:1,256',
                    'note' => 'array',
                    'start' => 'required|date_format:Y-m-d H:i:s',
                    'end' => 'required|date_format:Y-m-d H:i:s'
                ];
            case 'notice.bulletin.index':
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
