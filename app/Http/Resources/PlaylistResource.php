<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaylistResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail ? getImage(getFilePath('playlistThumbnail') . '/' . $this->thumbnail) : null,
            'videos_count' => $this->videos_count,
            'is_premium' => $this->is_premium,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'videos' => VideoResource::collection($this->whenLoaded('videos')),
        ];
    }
}
