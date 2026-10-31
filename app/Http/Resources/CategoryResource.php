<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->image ? getImage(getFilePath('category') . '/' . $this->image) : null,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
