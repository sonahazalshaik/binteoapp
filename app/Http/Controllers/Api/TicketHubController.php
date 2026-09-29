<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\ApiTicketService;

class TicketHubController extends Controller
{
    protected ApiTicketService $ticketService;

    public function __construct(ApiTicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function getConversation($id)
    {
        $result = $this->ticketService->getConversation((int) $id);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['code']);
        }

        return response()->json($result);
    }
}
