<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        return [
            'name' => [
                'string',
                'max:255',
                Rule::unique('users')->ignore($this->user->name, 'name'),
            ],
            'email' => [
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user->email, 'email'),
            ],
            'password' => 'required|string|min:6',
        ];
    }
}
