<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'image' => getImage(getFilePath('userProfile') . '/' . $this->image),
            'status' => $this->status,
            'created_at' => $this->created_at,
            'channel' => new ChannelResource($this->whenLoaded('channel')),
        ];
    }
}
