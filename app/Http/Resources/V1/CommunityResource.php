<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CommunityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'owner' => [
                'id' => $this->owner->count() ? $this->owner->first()->id : '',
                'name' => $this->owner->count() ? $this->owner->first()->name : '',
            ],
            'created_at' => $this->created_at,
            'private' => $this->private ? 'yes' : 'no',
            'num_users' => $this->users->count(),
        ];
    }
}
