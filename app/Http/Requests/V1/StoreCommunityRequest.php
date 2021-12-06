<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommunityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ($this->user()->isAdmin() || $this->user()->id == $this->owner);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:50|unique:communities',
            'description' => 'required|string|min:5',
            'owner' => 'required|integer|exists:users,id',
            'private' => 'boolean',
            'password' => 'required_if:private,1|required_if:private,true|nullable|string|min:3'
        ];
    }
}
