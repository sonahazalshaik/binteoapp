<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Traits\SupportTicketManager;

class SupportTicketController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('admin')->user();
            return $next($request);
        });

        $this->userType = 'admin';
        $this->column = 'admin_id';
    }

    public function tickets()
    {
        $pageTitle = 'Support Tickets';
        $items = SupportTicket::searchable(['name','subject','ticket'])->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle'));
    }

    public function pendingTicket()
    {
        $pageTitle = 'Pending Tickets';
        $items = SupportTicket::searchable(['name','subject','ticket'])->pending()->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle'));
    }

    public function closedTicket()
    {
        $pageTitle = 'Closed Tickets';
        $items = SupportTicket::searchable(['name','subject','ticket'])->closed()->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle'));
    }

    public function answeredTicket()
    {
        $pageTitle = 'Answered Tickets';
        $items = SupportTicket::searchable(['name','subject','ticket'])->orderBy('id','desc')->with('user')->answered()->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle'));
    }

    public function ticketReply($id)
    {
        $ticket = SupportTicket::with('user')->where('id', $id)->firstOrFail();
        $pageTitle = 'Reply Ticket';
        $messages = SupportMessage::with('ticket','admin','attachments')->where('support_ticket_id', $ticket->id)->orderBy('id','desc')->get();
        return view('admin.support.reply', compact('ticket', 'messages', 'pageTitle'));
    }

    public function ticketDelete($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $messages = SupportMessage::where('support_ticket_id', $ticket->id)->get();
        foreach ($messages as $message) {
            if ($message->attachments()->count() > 0) {
                foreach ($message->attachments as $attachment) {
                    \App\Helpers\ImageHelper::deleteImage($attachment->attachment);
                    $attachment->delete();
                }
            }
            $message->delete();
        }
        $ticket->delete();
        $notify[] = ['success', "Support ticket deleted successfully"];
        return back()->withNotify($notify);
    }

    public function ticketMessageDelete($id)
    {
        $message = SupportMessage::findOrFail($id);
        if ($message->attachments()->count() > 0) {
            foreach ($message->attachments as $attachment) {
                \App\Helpers\ImageHelper::deleteImage($attachment->attachment);
                $attachment->delete();
            }
        }
        $message->delete();
        $notify[] = ['success', "Support message deleted successfully"];
        return back()->withNotify($notify);
    }

    /**
     * AJAX endpoint: Return ticket messages as JSON for the admin Hub modal.
     */
    public function ticketHubData($id)
    {
        $ticket = SupportTicket::with('user')->where('id', $id)->first();

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        $messages = SupportMessage::where('support_ticket_id', $ticket->id)
            ->with('attachments', 'admin')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($msg) use ($ticket) {
                return [
                    'id'         => $msg->id,
                    'message'    => $msg->message,
                    'is_admin'   => (bool) $msg->admin_id,
                    'sender'     => $msg->admin_id ? ($msg->admin->name ?? 'Admin') : ($ticket->user->fullname ?? $ticket->name),
                    'created_at' => $msg->created_at->diffForHumans(),
                    'attachments' => $msg->attachments->map(fn($a) => [
                        'id'        => $a->encrypted_id,
                        'url'       => route('admin.ticket.download', $a->encrypted_id),
                        'is_image'  => $a->is_image,
                        'image_url' => $a->is_image ? getImage($a->attachment) : null,
                    ]),
                ];
            });

        return response()->json([
            'ticket' => [
                'id'       => $ticket->id,
                'ticket'   => $ticket->ticket,
                'subject'  => $ticket->subject,
                'status'   => $ticket->status,
                'priority' => $ticket->priority,
                'user'     => $ticket->user->fullname ?? $ticket->name,
            ],
            'messages' => $messages,
        ]);
    }

    /**
     * AJAX endpoint: Update ticket status from the admin Hub modal.
     */
    public function ticketUpdateStatus(\Illuminate\Http\Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $status = (int) $request->input('status');

        if (!in_array($status, [0, 1, 2, 3, 4])) {
            return response()->json(['error' => 'Invalid status'], 422);
        }

        $ticket->status = $status;
        $ticket->save();

        return response()->json(['success' => true, 'status' => $status]);
    }

}
