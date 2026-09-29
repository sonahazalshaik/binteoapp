<?php

namespace App\Services\Frontend;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;

class TicketService
{
    public function index()
    {
        return SupportTicket::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
    }

    public function view($id): array
    {
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('messages.attachments', 'messages.admin')
            ->firstOrFail();

        return compact('ticket');
    }

    public function store(Request $request): SupportTicket
    {
        return SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority ?? 'medium',
        ]);
    }

    public function reply(Request $request, $id): SupportMessage
    {
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return $ticket->messages()->create([
            'message' => $request->message,
        ]);
    }

    public function close($id): void
    {
        SupportTicket::where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['status' => \App\Constants\Status::TICKET_CLOSE]);
    }

    public function userTickets()
    {
        $query = SupportTicket::where('user_id', auth()->id());

        if (request()->has('status') && request()->status !== '' && request()->status !== null) {
            $query->where('status', request()->status);
        }

        return $query->orderBy('priority', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate())
            ->appends(request()->query());
    }

    public function ticketStatusCounts(): array
    {
        $all = SupportTicket::where('user_id', auth()->id());
        return [
            'all'         => (clone $all)->count(),
            'pending'     => (clone $all)->where('status', 0)->count(),
            'answered'    => (clone $all)->where('status', 1)->count(),
            'in_progress' => (clone $all)->where('status', 2)->count(),
            'closed'      => (clone $all)->where('status', 3)->count(),
            'rejected'    => (clone $all)->where('status', 4)->count(),
        ];
    }

    public function findTicket($id)
    {
        return SupportTicket::where(function($q) use ($id) {
            $q->where('id', $id)->orWhere('ticket', $id);
        })->where('user_id', auth()->id())->firstOrFail();
    }

    public function ticketMessages($ticketId)
    {
        return SupportMessage::where('support_ticket_id', $ticketId)
            ->with('attachments')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function hubData($id): array
    {
        $ticket = SupportTicket::where(function($q) use ($id) {
            $q->where('id', $id)->orWhere('ticket', $id);
        })->where('user_id', auth()->id())->first();

        if (!$ticket) {
            return ['error' => 'Ticket not found'];
        }

        $messages = SupportMessage::where('support_ticket_id', $ticket->id)
            ->with('attachments')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($msg) use ($ticket) {
                return [
                    'id'         => $msg->id,
                    'message'    => $msg->message,
                    'is_admin'   => (bool) $msg->admin_id,
                    'sender'     => $msg->admin_id ? 'Support Analyst' : auth()->user()->fullname,
                    'created_at' => $msg->created_at->diffForHumans(),
                    'attachments' => $msg->attachments->map(fn($a) => [
                        'id'        => $a->encrypted_id,
                        'url'       => route('user.ticket.download', $a->encrypted_id),
                        'is_image'  => $a->is_image,
                        'image_url' => $a->is_image ? getImage($a->attachment) : null,
                    ]),
                ];
            });

        return [
            'ticket' => [
                'id'       => $ticket->id,
                'ticket'   => $ticket->ticket,
                'subject'  => $ticket->subject,
                'status'   => $ticket->status,
                'priority' => $ticket->priority,
            ],
            'messages' => $messages,
        ];
    }
}
