<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\Admin\PlanService;
use Illuminate\Http\Request;

class ManagePlanController extends Controller {
    protected $service;

    public function __construct(PlanService $service) {
        $this->service = $service;
    }

    public function index() {

        $pageTitle = "All Monthly Plan";
        $plans     = $this->service->getManagePlans(getPaginate());
        return view('admin.plan.index', compact('pageTitle', 'plans'));
    }

    public function update(Request $request, $id = 0) {
        $request->validate([
            'name'  => 'required|string|max:40',
            'price' => 'required|numeric|gt:0',
        ]);

        $this->service->updatePlanBasic($id, [
            'name'  => $request->name,
            'slug'  => createUniqueSlug($request->name, Plan::class, $id),
            'price' => $request->price,
        ]);

        $notify[] = ["success", "Plan updated successfully"];
        return back()->withNotify($notify);
    }

    public function videosList($id) {
        $plan      = Plan::findOrFail($id);
        $pageTitle = "Videos in " . $plan->name;
        $videos    = $this->service->getPlanVideos($plan->id, getPaginate());
        return view('admin.videos.index', compact('pageTitle', 'videos'));
    }

    public function playlistList($id) {
        $plan      = Plan::findOrFail($id);
        $pageTitle = "Playlists in " . $plan->name;
        $playlists = $this->service->getPlanPlaylists($plan->id, getPaginate());
        return view('admin.playlist.index', compact('pageTitle', 'playlists'));
    }

    public function status($id) {
        return $this->service->changePlanStatus($id);
    }
}
