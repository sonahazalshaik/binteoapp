<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SecurityService;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function __construct(private SecurityService $securityService) {}

    public function index()
    {
        $data = $this->securityService->getSecurityDashboard();

        return view('admin.security.index', $data);
    }

    public function blockIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:blocked_ips,ip_address',
            'reason' => 'nullable|string|max:255'
        ]);

        $this->securityService->blockIp($request->ip_address, $request->reason);

        $notify[] = ['success', 'IP address blocked successfully.'];
        return back()->withNotify($notify);
    }

    public function unblockIp($id)
    {
        $this->securityService->unblockIp($id);

        $notify[] = ['success', 'IP address unblocked successfully.'];
        return back()->withNotify($notify);
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $this->securityService->createAnnouncement($request->title, $request->message);

        $notify[] = ['success', 'Announcement broadcasted successfully.'];
        return back()->withNotify($notify);
    }

    public function toggleAnnouncement($id)
    {
        $this->securityService->toggleAnnouncement($id);
        $notify[] = ['success', 'Announcement status updated.'];
        return back()->withNotify($notify);
    }

    public function destroyAnnouncement($id)
    {
        $this->securityService->deleteAnnouncement($id);
        $notify[] = ['success', 'Announcement deleted.'];
        return back()->withNotify($notify);
    }

    public function strikes()
    {
        $strikes = $this->securityService->getStrikes();
        return view('admin.security.strikes', compact('strikes'));
    }

    public function issueStrike(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'reason' => 'required|string',
        ]);

        $this->securityService->issueStrike($request->video_id, $request->reason);

        $notify[] = ['success', 'Copyright strike issued successfully.'];
        return back()->withNotify($notify);
    }

    public function resolveStrike($id)
    {
        $this->securityService->resolveStrike($id);

        $notify[] = ['success', 'Copyright strike resolved.'];
        return back()->withNotify($notify);
    }

    public function blacklist()
    {
        $keywords = $this->securityService->getBlacklistedKeywords();
        return view('admin.security.blacklist', compact('keywords'));
    }

    public function storeBlacklist(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:50|unique:blacklisted_keywords,keyword',
        ]);

        $this->securityService->addBlacklistedKeyword($request->keyword);

        $notify[] = ['success', 'Keyword added to blacklist.'];
        return back()->withNotify($notify);
    }

    public function destroyBlacklist($id)
    {
        $this->securityService->removeBlacklistedKeyword($id);

        $notify[] = ['success', 'Keyword removed from blacklist.'];
        return back()->withNotify($notify);
    }
}
