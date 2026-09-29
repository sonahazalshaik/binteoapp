<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketPlace;
use App\Services\Admin\MarketTalentService;
use Illuminate\Http\Request;

class MarketPortfolioController extends Controller
{
    protected $service;

    public function __construct(MarketTalentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'Marketplace Portfolios';
        $portfolios = $this->service->listPortfolios();
        $emptyMessage = 'No portfolios found';
        
        return view('admin.marketplace.portfolio.index', compact('pageTitle', 'portfolios', 'emptyMessage'));
    }

    public function create()
    {
        $pageTitle = 'Create Portfolio';
        $talents = MarketPlace::select('id', 'name', 'type')->get();
        return view('admin.marketplace.portfolio.create', compact('pageTitle', 'talents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'marketplace_id' => 'required|exists:market_places,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $portfolio = $this->service->createPortfolio($request->all(), null);

        $images = $request->file('images');
        if ($images) {
            foreach ($images as $image) {
                $imageUrl = $this->uploadPortfolioImage($image);
                if ($imageUrl) {
                    \App\Models\MarketPortfolioImage::create([
                        'market_portfolio_id' => $portfolio->id,
                        'image' => $imageUrl,
                    ]);
                }
            }
        }

        $notify[] = ['success', 'Portfolio created successfully'];
        return redirect()->route('admin.marketplace.portfolio.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $portfolio = $this->service->findPortfolio($id);
        $pageTitle = 'Edit Portfolio';
        $talents = MarketPlace::select('id', 'name', 'type')->get();
        
        return view('admin.marketplace.portfolio.edit', compact('pageTitle', 'portfolio', 'talents'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'marketplace_id' => 'required|exists:market_places,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $portfolio = $this->service->updatePortfolio($id, $request->all(), null);

        $images = $request->file('images');
        if ($images) {
            foreach ($images as $image) {
                $imageUrl = $this->uploadPortfolioImage($image);
                if ($imageUrl) {
                    \App\Models\MarketPortfolioImage::create([
                        'market_portfolio_id' => $portfolio->id,
                        'image' => $imageUrl,
                    ]);
                }
            }
        }

        $notify[] = ['success', 'Portfolio updated successfully'];
        return back()->withNotify($notify);
    }

    private function uploadPortfolioImage($image)
    {
        $r2 = gs('cloudflare_config');
        $key = $r2->access_key ?? config('filesystems.disks.r2.key');
        $secret = $r2->secret_key ?? config('filesystems.disks.r2.secret');
        $bucket = $r2->bucket ?? config('filesystems.disks.r2.bucket');

        if (!empty($key) && !empty($secret) && !empty($bucket)) {
            try {
                return \App\Helpers\ImageHelper::uploadToR2($image, 'marketplace/portfolios');
            } catch (\Exception $e) {
                \Log::error("R2 Upload Failed, falling back to local: " . $e->getMessage());
            }
        }

        // Fallback to local storage (public disk)
        $ext = method_exists($image, 'getClientOriginalExtension') ? $image->getClientOriginalExtension() : $image->extension();
        $ext = $ext ?: 'jpg';
        $imageName = time() . '_' . uniqid() . '.' . $ext;
        $localPath = \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('marketplace/portfolios', $image, $imageName);
        return $localPath;
    }

    public function status($id)
    {
        $portfolio = $this->service->togglePortfolioStatus($id);

        $notify[] = ['success', 'Status changed successfully.'];
        return back()->withNotify($notify);
    }

    public function destroy($id)
    {
        $this->service->deletePortfolio($id);

        $notify[] = ['success', 'Portfolio deleted successfully.'];
        return back()->withNotify($notify);
    }

    public function deleteImage($id)
    {
        $this->service->deletePortfolioImage($id);

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }
}
