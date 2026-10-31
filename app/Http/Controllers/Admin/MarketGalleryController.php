<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketGallery;
use App\Models\MarketPlace;
use App\Services\Admin\MarketTalentService;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;

class MarketGalleryController extends Controller
{
    protected $service;

    public function __construct(MarketTalentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'Market Gallery';
        $galleries = $this->service->listGallery();
        return view('admin.marketplace.gallery.index', compact('pageTitle', 'galleries'));
    }

    public function create()
    {
        $pageTitle = 'Add Gallery Image';
        $talents = $this->service->list();
        return view('admin.marketplace.gallery.create', compact('pageTitle', 'talents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'marketplace_id' => 'required|exists:market_places,id',
            'images' => 'required|array',
            'images.*' => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png', 'webp'])],
        ]);

        $this->service->createGallery($request->all(), $request->file('images'));

        $notify[] = ['success', 'Gallery images added successfully'];
        return to_route('admin.marketplace.gallery.index')->withNotify($notify);
    }

    public function edit($id)
    {
        // $id is the marketplace_id.
        $marketplace = MarketPlace::findOrFail($id);
        $gallery = new MarketGallery();
        $gallery->marketplace_id = $marketplace->id;
        $pageTitle = 'Edit Gallery Photos';
        $talents = MarketPlace::orderBy('id', 'desc')->get();
        $existingImages = MarketGallery::where('marketplace_id', $gallery->marketplace_id)->get();
        $existingCount = $existingImages->count();
        return view('admin.marketplace.gallery.edit', compact('pageTitle', 'gallery', 'talents', 'existingImages', 'existingCount'));
    }

    public function update(Request $request, $id)
    {
        $existingCount = MarketGallery::where('marketplace_id', $id)->count();

        $request->validate([
            'marketplace_id' => 'required|exists:market_places,id',
            'images' => $existingCount == 0 ? 'required|array' : 'nullable|array',
            'images.*' => [$existingCount == 0 ? 'required' : 'nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png', 'webp'])],
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'integer|exists:market_galleries,id',
        ]);

        // Process deleted images
        if ($request->has('deleted_images')) {
            foreach ($request->deleted_images as $deleted_id) {
                $img = MarketGallery::where('id', $deleted_id)->where('marketplace_id', $id)->first();
                if ($img) {
                    $this->service->deleteGallery($img->id);
                }
            }
        }

        // $id is the marketplace_id
        MarketGallery::where('marketplace_id', $id)
            ->update(['marketplace_id' => $request->marketplace_id]);

        // Upload new images
        if ($request->hasFile('images')) {
            $this->service->createGallery($request->all(), $request->file('images'));
        }

        $notify[] = ['success', 'Gallery photos updated successfully'];
        return to_route('admin.marketplace.gallery.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        $gallery = MarketGallery::findOrFail($id);
        $marketplace_id = $gallery->marketplace_id;
        
        $this->service->deleteGallery($id);

        $notify[] = ['success', 'Gallery image deleted successfully'];
        
        return back()->withNotify($notify);
    }
}
