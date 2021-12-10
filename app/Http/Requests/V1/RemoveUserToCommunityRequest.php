<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class RemoveUserToCommunityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin()
            || $this->user()->isPartOfCommunity($this->community)
            || $this->user()->isAdminOfCommunity($this->community)
            || $this->user()->isOwnerOfCommunity($this->community);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'string',
        ];
    }
}
