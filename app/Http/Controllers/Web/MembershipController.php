<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\Membership;
use App\Services\Frontend\MembershipService;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    protected MembershipService $membershipService;

    public function __construct(MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    public function join(Membership $membership)
    {
        $result = $this->membershipService->join($membership);

        if ($result['success']) {
            $notify[] = ['success', $result['message']];
        } else {
            $notify[] = ['error', $result['message']];
        }

        return back()->withNotify($notify);
    }
}


