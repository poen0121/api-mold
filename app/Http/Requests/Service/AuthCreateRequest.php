<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;
use TokenAuth;

class AuthCreateRequest extends FormRequest
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
            case 'auth.token.create':
                return [
                    'client_id' => 'required|size:32',
                    'client_secret' => 'required|size:32'
                ];
            case 'auth.user.login':
                /* Get the guard type info array */
                $type = $this->route()->parameter('type');
                /* Validate rule by login */
                $rules = config('auth.guards.' . $type['type'] . '.login_rule');
                if (isset($rules)) {
                    /* Account */
                    $rules['account'] = (isset($rules['account']) ? $rules['account'] : 'required|between:1,128');
                    /* Check rule type */
                    if (is_string($rules['account']) && strpos($rules['account'], 'required') === false) {
                        $rules['account'] = 'required|' . $rules['account'];
                    } elseif (is_array($rules['account']) && strpos(implode('|', $rules['account']), 'required') === false) {
                        array_unshift($rules['account'], 'required');
                    }
                    /* Password */
                    $rules['password'] = (isset($rules['password']) ? $rules['password'] : 'required|between:8,16');
                    /* Check rule type */
                    if (is_string($rules['password']) && strpos($rules['password'], 'required') === false) {
                        $rules['password'] = 'required|' . $rules['password'];
                    } elseif (is_array($rules['password']) && strpos(implode('|', $rules['password']), 'required') === false) {
                        array_unshift($rules['password'], 'required');
                    }
                    /* Return rule */
                    return [
                        'account' => $rules['account'],
                        'password' => $rules['password'],
                    ];
                } else {
                    throw new ExceptionCode(ExceptionCode::NO_PERMISSION);
                }
                // no break
            case 'auth.user.signature.login':
                return [
                    'signature' => [
                        'required',
                        'regex:/^([A-Z0-9_]+\-)?[A-F0-9]{72}$/'
                    ]
                ];
            default:
                return [];
        }
    }
}
