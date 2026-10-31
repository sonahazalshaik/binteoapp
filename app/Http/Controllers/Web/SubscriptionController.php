<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\Channel;
use App\Services\Frontend\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function toggle(Channel $channel)
    {
        $result = $this->subscriptionService->toggle($channel);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 403);
        }

        return response()->json($result);
    }

    public function updatePreference(Request $request, Channel $channel)
    {
        $result = $this->subscriptionService->updatePreference($request, $channel);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 404);
        }

        return response()->json($result);
    }
}


