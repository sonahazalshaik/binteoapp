<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'compare_price' => $this->compare_price,
            'image' => $this->image ? getImage(getFilePath('product') . '/' . $this->image) : null,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
