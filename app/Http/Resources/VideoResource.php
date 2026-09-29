<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'thumbnail' => $this->getThumbnailUrl(),
            'duration' => $this->duration,
            'views_count' => $this->views_count,
            'likes_count' => $this->likes_count,
            'comments_count' => $this->comments_count,
            'is_premium' => $this->is_premium,
            'is_featured' => $this->is_featured,
            'is_trending' => $this->is_trending,
            'visibility' => $this->visibility,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'channel' => new ChannelResource($this->whenLoaded('channel')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
        ];
    }
}
