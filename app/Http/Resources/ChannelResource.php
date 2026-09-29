<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'avatar' => $this->avatar ? getImage(getFilePath('channelAvatar') . '/' . $this->avatar) : null,
            'cover' => $this->cover ? getImage(getFilePath('channelCover') . '/' . $this->cover) : null,
            'description' => $this->description,
            'subscribers_count' => $this->subscribers_count,
            'is_verified' => $this->is_verified,
            'is_featured' => $this->is_featured,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
