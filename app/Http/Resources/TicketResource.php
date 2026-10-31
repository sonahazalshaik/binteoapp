<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket,
            'subject' => $this->subject,
            'status' => $this->status,
            'priority' => $this->priority,
            'status_badge' => (string) $this->statusBadge,
            'is_closed' => $this->status == \App\Constants\Status::TICKET_CLOSE,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
