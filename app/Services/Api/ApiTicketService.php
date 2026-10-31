<?php

namespace App\Services\Api;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Constants\Status;

class ApiTicketService
{
    public function getConversation(int $id): array
    {
        $ticket = SupportTicket::where('id', $id)
            ->when(!auth()->guard('admin')->check(), function ($q) {
                return $q->where('user_id', auth()->id());
            })
            ->first();

        if (!$ticket) {
            return ['error' => 'Ticket not found', 'code' => 404];
        }

        $messages = SupportMessage::where('support_ticket_id', $ticket->id)
            ->with('attachments', 'admin')
            ->orderBy('created_at', 'asc')
            ->get();

        return [
            'ticket' => [
                'id' => $ticket->id,
                'subject' => $ticket->subject,
                'status' => $ticket->status,
                'ticket_number' => $ticket->ticket,
                'status_badge' => (string) $ticket->statusBadge,
                'is_closed' => $ticket->status == Status::TICKET_CLOSE,
            ],
            'messages' => $messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'is_admin' => $msg->admin_id > 0,
                    'sender_name' => $msg->admin_id ? $msg->admin->name : 'You',
                    'created_at' => $msg->created_at->diffForHumans(),
                    'attachments' => $msg->attachments->map(function ($att) {
                        return [
                            'url' => \App\Helpers\ImageHelper::getPhotoUrl($att->attachment),
                            'is_image' => preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $att->attachment),
                        ];
                    }),
                ];
            }),
        ];
    }
}
