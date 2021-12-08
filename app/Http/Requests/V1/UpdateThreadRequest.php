<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateThreadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $admin = false;
        foreach ($this->thread->community->users as $user) {
            if ($this->user()->id == $user->id || $user->pivot->admin == 1) {
                $admin = true;
            }
        }

        return (
            $this->user()->isAdmin() 
            || ($this->user()->id == $this->thread->community->owner->first()->id)
            || ($this->user()->id == $this->thread->user_id)
            || $admin
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|min:3',
            'thread_id' => 'required|integer|exists:threads,id',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'thread_id' => $this->route('thread')->id,
        ]);
    }
}
