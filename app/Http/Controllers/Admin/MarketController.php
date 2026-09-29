<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Services\Admin\MarketTalentService;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    protected $service;

    public function __construct(MarketTalentService $service)
    {
        $this->service = $service;
    }

    /**
     * Show all marketplace records
     */
    public function index()
    {
        $filters = [];
        if (request()->has('type')) {
            $filters['type'] = request()->input('type');
        }
        $categories = $this->service->list($filters);
        $plans      = \App\Models\Plan::active()->get();
        $pageTitle  = "All Marketplace Talents";
        $emptyMessage = "No marketplace records found.";
        return view('admin.marketplace.index', compact('categories', 'emptyMessage', 'pageTitle', 'plans'));
    }

    public function actors()
    {
        $categories = $this->service->list(['type' => 'actor']);
        $pageTitle  = "Actor Profiles";
        $emptyMessage = "No actor profiles found.";
        return view('admin.marketplace.index', compact('categories', 'emptyMessage', 'pageTitle'));
    }

    public function influencers()
    {
        $categories = $this->service->list(['type' => 'influencer']);
        $pageTitle  = "Influencer Profiles";
        $emptyMessage = "No influencer profiles found.";
        return view('admin.marketplace.index', compact('categories', 'emptyMessage', 'pageTitle'));
    }

    public function investors()
    {
        $categories = $this->service->list(['type' => 'investor']);
        $pageTitle  = "Investor Profiles";
        $emptyMessage = "No investor profiles found.";
        return view('admin.marketplace.index', compact('categories', 'emptyMessage', 'pageTitle'));
    }

    public function loginAsTalent($id)
    {
        $user = $this->service->loginAsTalent($id);
        session()->put('marketplace_user_id', $user->id);
        
        $notify[] = ['success', 'Logged in as ' . $user->name];
        return redirect()->route('marketplace.dashboard')->withNotify($notify);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'      => 'required|in:actor,influencer,investor',
            'name'      => 'required|string|max:255',
            'image'     => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:2048',
            'number'    => 'nullable|string|max:20',
            'email'     => 'required|email|unique:market_places,email',
            'location'  => 'nullable|string|max:255',
            'rating'    => 'nullable|numeric|min:0|max:5',
            'projects_count' => 'nullable|integer|min:0',
            'satisfaction_rate' => 'nullable|integer|min:0|max:100',
            'more_info' => 'nullable|string',
            'years_of_experience' => 'nullable|string',
            'skills'    => 'nullable|string',
            'portfolio_url' => 'nullable|string',
            'website_url' => 'nullable|string',
            'business_name' => 'nullable|string',
            'business_type' => 'nullable|string',
            'password'      => 'nullable|string|min:8',
            'facebook_link' => 'nullable|url',
            'instagram_link'=> 'nullable|url',
            'twitter_link'  => 'nullable|url',
            'is_featured'   => 'nullable',
        ]);

        try {
            $this->service->create($validated, $request->file('image'), $request->file('cover_image'));
        } catch (\Exception $exp) {
            $notify[] = ['error', 'Image upload failed.'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'Talent profile created successfully.'];
        return redirect()->route('admin.marketplace.index')->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $marketplace = $this->service->find($id);

        $validated = $request->validate([
            'type'      => 'required|in:actor,influencer,investor',
            'name'      => 'required|string|max:255',
            'image'     => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:2048',
            'number'    => 'nullable|string|max:20',
            'email'     => 'required|email|unique:market_places,email,' . $marketplace->id,
            'location'  => 'nullable|string|max:255',
            'rating'    => 'nullable|numeric|min:0|max:5',
            'projects_count' => 'nullable|integer|min:0',
            'satisfaction_rate' => 'nullable|integer|min:0|max:100',
            'more_info' => 'nullable|string',
            'years_of_experience' => 'nullable|string',
            'skills'    => 'nullable|string',
            'portfolio_url' => 'nullable|string',
            'website_url' => 'nullable|string',
            'business_name' => 'nullable|string',
            'business_type' => 'nullable|string',
            'facebook_link' => 'nullable|url',
            'instagram_link'=> 'nullable|url',
            'twitter_link'  => 'nullable|url',
            'is_featured'   => 'nullable',
            'password'      => 'nullable|string|min:8',
        ]);

        $this->service->update($id, $validated, $request->file('image'), $request->file('cover_image'));

        $notify[] = ['success', 'Talent profile updated successfully.'];
        return redirect()->route('admin.marketplace.index')->withNotify($notify);
    }

    public function status($id)
    {
        $marketplace = $this->service->toggleStatus($id);

        return response()->json([
            'message' => 'Status changed successfully.',
            'status' => $marketplace->status
        ]);
    }

    public function toggleFeatured($id)
    {
        $marketplace = $this->service->toggleFeatured($id);

        $notify[] = ['success', $marketplace->is_featured ? 'Promoted to Featured status.' : 'Removed from Featured status.'];
        return back()->withNotify($notify);
    }

    public function create()
    {
        $pageTitle = 'Initialize Talent Profile';
        return view('admin.marketplace.create', compact('pageTitle'));
    }

    public function edit($id)
    {
        $marketplace = $this->service->find($id);
        $pageTitle = 'Refine Talent Identity: ' . $marketplace->name;
        return view('admin.marketplace.edit', compact('marketplace', 'pageTitle'));
    }

    public function show($id)
    {
        $marketplace = $this->service->find($id);
        $pageTitle = 'Talent Intel: ' . $marketplace->name;
        return view('admin.marketplace.show', compact('marketplace', 'pageTitle'));
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        $notify[] = ['success', 'Talent profile deleted successfully.'];
        return back()->withNotify($notify);
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'ids'    => 'required|array',
            'ids.*'  => 'required',
        ]);

        $ids    = $request->ids;
        $action = $request->action;

        if (!$ids || count($ids) == 0) {
            $notify[] = ['error', 'No talents selected'];
            return back()->withNotify($notify);
        }

        $this->service->bulkAction($ids, $action);

        $notify[] = ['success', 'Bulk action executed successfully'];
        return back()->withNotify($notify);
    }

    public function ratings()
    {
        $pageTitle = 'Marketplace Ratings Management';
        $data = $this->service->getRatings();
        $emptyMessage = "No ratings found.";

        $ratings = $data['ratings'];
        $totalRatingsCount = $data['totalRatingsCount'];
        $avgRating = $data['avgRating'];
        $breakdown = $data['breakdown'];

        if (!view()->exists('admin.marketplace.ratings')) {
            return response()->json(['message' => 'Ratings View under construction', 'ratings' => $ratings]);
        }
        
        return view('admin.marketplace.ratings', compact('pageTitle', 'ratings', 'emptyMessage', 'totalRatingsCount', 'avgRating', 'breakdown'));
    }

    public function createRating()
    {
        $pageTitle = 'Add New Rating';
        $talents = $this->service->list();
        return view('admin.marketplace.ratings_create', compact('pageTitle', 'talents'));
    }

    public function storeRating(Request $request)
    {
        $request->validate([
            'marketplace_id' => 'required|exists:market_places,id',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        $this->service->createRating($request->all());

        $notify[] = ['success', 'Rating added successfully.'];
        return redirect()->route('admin.marketplace.ratings.index')->withNotify($notify);
    }

    public function editRating($id)
    {
        $pageTitle = 'Edit Rating';
        $rating = \App\Models\MarketplaceRating::findOrFail($id);
        $talents = \App\Models\MarketPlace::select('id', 'name', 'type')->get();
        return view('admin.marketplace.ratings_edit', compact('pageTitle', 'rating', 'talents'));
    }

    public function updateRating(Request $request, $id)
    {
        $request->validate([
            'marketplace_id' => 'required|exists:market_places,id',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        $this->service->updateRating($id, $request->all());

        $notify[] = ['success', 'Rating updated successfully.'];
        return redirect()->route('admin.marketplace.ratings.index')->withNotify($notify);
    }

    public function deleteRating($id)
    {
        $this->service->deleteRating($id);
        
        $notify[] = ['success', 'Rating deleted successfully.'];
        return back()->withNotify($notify);
    }
}
