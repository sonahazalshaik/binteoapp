<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Traits\SupportTicketManager;
use App\Services\Frontend\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use SupportTicketManager;

    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;

        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });

        $this->userType = 'user';
        $this->column = 'user_id';
        $this->redirectLink = 'user.ticket.index';
    }

    public function ticketIndex()
    {
        $pageTitle = "My Support Tickets";
        $supports = $this->ticketService->userTickets();
        $counts = $this->ticketService->ticketStatusCounts();

        if (request()->ajax() && request()->wantsJson()) {
            return response()->json([
                'html' => view('frontend.client.ticket.partials.ticket_list', compact('supports'))->render(),
                'counts' => $counts,
            ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return view('frontend.client.ticket.index', compact('supports', 'counts', 'pageTitle'));
    }

    public function ticketCreate()
    {
        $pageTitle = "New Support Ticket";
        $user = auth()->user();
        return view('frontend.client.ticket.create', compact('pageTitle', 'user'));
    }

    public function ticketView($id)
    {
        $pageTitle = "View Ticket";
        $myTicket = $this->ticketService->findTicket($id);
        $messages = $this->ticketService->ticketMessages($myTicket->id);
        return view('frontend.client.ticket.view', compact('myTicket', 'messages', 'pageTitle'));
    }

    public function ticketStore(Request $request)
    {
        return $this->storeSupportTicket($request);
    }

    public function ticketReply(Request $request, $id)
    {
        return $this->replyTicket($request, $id);
    }

    public function ticketClose($id)
    {
        return $this->closeTicket($id);
    }

    public function ticketHubData($id)
    {
        $result = $this->ticketService->hubData($id);

        if (isset($result['error'])) {
            return response()->json($result, 404);
        }

        return response()->json($result);
    }
}
