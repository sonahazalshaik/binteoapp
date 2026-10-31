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

    /**
     * Reject top-level browser navigations so a page load can never receive
     * raw JSON (Chrome renders that as plain text with a "Pretty-print" button).
     * Real fetch() calls send Sec-Fetch-Mode: cors, navigations send "navigate".
     */
    protected function wantsGridJson($request): bool
    {
        if (!$request->hasHeader('X-Ticket-List')) {
            return false;
        }
        if ($request->header('Sec-Fetch-Mode') === 'navigate' || $request->header('Sec-Fetch-Dest') === 'document') {
            return false;
        }
        return $request->ajax() && $request->wantsJson();
    }

    public function ticketIndex()
    {
        // NOTE: this page route ALWAYS returns Blade HTML. It contains no
        // JSON branch by design, so no navigation, reload, or browser Back
        // can ever receive a raw JSON document from this URL.
        $pageTitle = "My Support Tickets";
        $supports = $this->ticketService->userTickets();
        $counts = $this->ticketService->ticketStatusCounts();

        return view('frontend.client.ticket.index', compact('supports', 'counts', 'pageTitle'));
    }

    /**
     * JSON-only grid endpoint for ticket filter/pagination fetch().
     * This URL is never linked or navigated to; non-AJAX access gets 404
     * (never JSON as a page, never the Blade layout either).
     */
    public function ticketGrid()
    {
        if (!$this->wantsGridJson(request())) {
            abort(404);
        }

        $supports = $this->ticketService->userTickets();
        $counts = $this->ticketService->ticketStatusCounts();

        return response()->json([
            'html' => view('frontend.client.ticket.partials.ticket_list', compact('supports'))->render(),
            'counts' => $counts,
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
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
