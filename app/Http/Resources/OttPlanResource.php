<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OttPlanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'duration_days' => $this->duration_days,
            'quality' => $this->quality,
            'devices' => $this->devices,
            'features' => $this->features,
            'is_popular' => $this->is_popular,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
