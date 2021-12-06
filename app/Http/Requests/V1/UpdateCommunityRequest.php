<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;

class UpdateCommunityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ($this->user()->isAdmin() || ($this->user()->id == $this->community->owner->first()->id && empty($this->owner)));
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
                'min:3',
                'max:50',
                Rule::unique('communities')->ignore($this->community->name, 'name'),
            ],
            'description' => 'string|min:5',
            'owner' => 'integer|exists:users,id',
            'private' => 'boolean',
            'password' => 'required_if:private,1|required_if:private,true|nullable|string|min:3'
        ];
    }
}
