<?php

namespace App\Services\Admin;

use App\Helpers\ImageHelper;
use App\Models\MarketGallery;
use App\Models\MarketplaceRating;
use App\Models\MarketPlace;
use App\Models\MarketPortfolio;
use App\Models\MarketPortfolioImage;
use Illuminate\Support\Facades\Hash;
use App\Models\MarketService;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class MarketTalentService
{
    public function list(array $filters = [])
    {
        $query = MarketPlace::latest();

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        $query->searchable(['name', 'email', 'business_name', 'location', 'number']);

        return $query->paginate(10);
    }

    public function create(array $data, $image = null, $coverImage = null)
    {
        $data['is_featured'] = !empty($data['is_featured']) ? 1 : 0;

        if ($image) {
            try {
                $data['image'] = ImageHelper::uploadToR2($image, 'marketplace/avatars');
            } catch (\Exception $exp) {
                throw $exp;
            }
        }

        if ($coverImage) {
            try {
                $data['cover_image'] = ImageHelper::uploadToR2($coverImage, 'marketplace/covers');
            } catch (\Exception $exp) {
                throw $exp;
            }
        }

        if (!empty($data['skills'])) {
            $data['skills'] = array_map('trim', explode(',', $data['skills']));
        }

        if (!empty($data['years_of_experience'])) {
            $data['years_of_experience'] = (array)$data['years_of_experience'];
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return MarketPlace::create($data);
    }

    public function update($id, array $data, $image = null, $coverImage = null)
    {
        $marketplace = MarketPlace::findOrFail($id);

        if ($image) {
            if ($marketplace->image) {
                ImageHelper::deleteImage($marketplace->image);
            }
            try {
                $marketplace->image = ImageHelper::uploadToR2($image, 'marketplace/avatars');
            } catch (\Exception $exp) {
                throw $exp;
            }
        }

        if ($coverImage) {
            if ($marketplace->cover_image) {
                ImageHelper::deleteImage($marketplace->cover_image);
            }
            try {
                $marketplace->cover_image = ImageHelper::uploadToR2($coverImage, 'marketplace/covers');
            } catch (\Exception $exp) {
                throw $exp;
            }
        }

        $data['is_featured'] = $data['is_featured'] ?? false ? 1 : 0;
        $fillData = $data;

        unset($fillData['image'], $fillData['cover_image']);

        if (!empty($fillData['skills'])) {
            $fillData['skills'] = array_map('trim', explode(',', $fillData['skills']));
        }

        if (!empty($fillData['years_of_experience'])) {
            $fillData['years_of_experience'] = (array)$fillData['years_of_experience'];
        }

        if (!empty($fillData['password'])) {
            $fillData['password'] = Hash::make($fillData['password']);
        } else {
            unset($fillData['password']);
        }

        $marketplace->fill($fillData);

        if ($marketplace->isDirty('is_featured')) {
            if ($marketplace->is_featured) {
                $marketplace->featured_until = now()->addDays(30);
            } else {
                $marketplace->featured_until = null;
            }
        }

        $marketplace->save();

        return $marketplace;
    }

    public function find($id)
    {
        return MarketPlace::findOrFail($id);
    }

    public function delete($id)
    {
        $marketplace = MarketPlace::findOrFail($id);

        if ($marketplace->image) {
            ImageHelper::deleteImage($marketplace->image);
        }

        if ($marketplace->cover_image) {
            ImageHelper::deleteImage($marketplace->cover_image);
        }

        $marketplace->delete();

        return $marketplace;
    }

    public function toggleStatus($id)
    {
        $marketplace = MarketPlace::findOrFail($id);
        $marketplace->status = !$marketplace->status;
        $marketplace->save();

        return $marketplace;
    }

    public function toggleFeatured($id)
    {
        $marketplace = MarketPlace::findOrFail($id);
        $marketplace->is_featured = !$marketplace->is_featured;

        if ($marketplace->is_featured) {
            $marketplace->featured_until = now()->addDays(30);
        } else {
            $marketplace->featured_until = null;
        }

        $marketplace->save();

        return $marketplace;
    }

    public function loginAsTalent($id)
    {
        $user = MarketPlace::findOrFail($id);
        session()->put('marketplace_user_id', $user->id);

        return $user;
    }

    public function bulkAction(array $ids, string $action)
    {
        $talents = MarketPlace::whereIn('id', $ids)->get();

        foreach ($talents as $talent) {
            if ($action == 'delete') {
                $talent->deleteWithAssets();
            } elseif ($action == 'featured') {
                $talent->is_featured = 1;
                $talent->featured_until = now()->addDays(30);
                $talent->save();
            } elseif ($action == 'unfeatured') {
                $talent->is_featured = 0;
                $talent->featured_until = null;
                $talent->save();
            } elseif ($action == 'approve') {
                $talent->status = 1;
                $talent->save();
            } elseif ($action == 'unapprove') {
                $talent->status = 0;
                $talent->save();
            }
        }
    }

    // -------------------------------------------------------- //
    //  RATINGS
    // -------------------------------------------------------- //

    public function getRatings()
    {
        $ratings = MarketplaceRating::with(['marketplace', 'user'])->latest()->paginate(10);
        $totalRatingsCount = MarketplaceRating::count();
        $avgRating = MarketplaceRating::avg('rating') ?? 0;
        $breakdown = [
            5 => MarketplaceRating::where('rating', 5)->count(),
            4 => MarketplaceRating::where('rating', 4)->count(),
            3 => MarketplaceRating::where('rating', 3)->count(),
            2 => MarketplaceRating::where('rating', 2)->count(),
            1 => MarketplaceRating::where('rating', 1)->count(),
        ];

        return compact('ratings', 'totalRatingsCount', 'avgRating', 'breakdown');
    }

    public function createRating(array $data)
    {
        return MarketplaceRating::create([
            'user_id' => 1,
            'marketplace_id' => $data['marketplace_id'],
            'rating' => $data['rating'],
            'review' => '',
        ]);
    }

    public function updateRating($id, array $data)
    {
        $ratingEntry = MarketplaceRating::findOrFail($id);
        $ratingEntry->update([
            'marketplace_id' => $data['marketplace_id'],
            'rating' => $data['rating'],
        ]);

        return $ratingEntry;
    }

    public function deleteRating($id)
    {
        $rating = MarketplaceRating::findOrFail($id);
        $rating->delete();

        return $rating;
    }

    // -------------------------------------------------------- //
    //  GALLERY (MarketGalleryController)
    // -------------------------------------------------------- //

    public function listGallery()
    {
        return MarketGallery::with('marketplace')
            ->searchable(['marketplace:name', 'marketplace:email'])
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
    }

    public function createGallery(array $data, $images = null)
    {
        $created = [];

        if ($images) {
            $images = is_array($images) ? $images : [$images];
            foreach ($images as $image) {
                try {
                    $gallery = new MarketGallery();
                    $gallery->marketplace_id = $data['marketplace_id'];
                    $gallery->market_gallery = ImageHelper::uploadToR2($image, 'marketplace/gallery');
                    $gallery->save();
                    $created[] = $gallery;
                } catch (\Exception $exp) {
                    throw $exp;
                }
            }
        }

        return $created;
    }

    public function findGallery($id)
    {
        return MarketGallery::findOrFail($id);
    }

    public function updateGallery($id, array $data, $images = null)
    {
        $gallery = MarketGallery::findOrFail($id);
        $gallery->marketplace_id = $data['marketplace_id'];
        $gallery->save();

        if ($images) {
            $images = is_array($images) ? $images : [$images];
            foreach ($images as $image) {
                try {
                    $newGallery = new MarketGallery();
                    $newGallery->marketplace_id = $data['marketplace_id'];
                    $newGallery->market_gallery = ImageHelper::uploadToR2($image, 'marketplace/gallery');
                    $newGallery->save();
                } catch (\Exception $exp) {
                    throw $exp;
                }
            }
        }

        return $gallery;
    }

    public function deleteGallery($id)
    {
        $gallery = MarketGallery::findOrFail($id);
        if ($gallery->market_gallery) {
            ImageHelper::deleteImage($gallery->market_gallery);
        }
        $gallery->delete();

        return $gallery;
    }

    // -------------------------------------------------------- //
    //  PORTFOLIO (MarketPortfolioController)
    // -------------------------------------------------------- //

    public function listPortfolios()
    {
        return MarketPortfolio::with(['marketplace', 'images'])
            ->searchable(['title', 'marketplace:name'])
            ->latest()
            ->paginate(15);
    }

    public function createPortfolio(array $data, $images = null)
    {
        $portfolio = MarketPortfolio::create([
            'marketplace_id' => $data['marketplace_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => !empty($data['status']) ? 1 : 0,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        if ($images) {
            foreach ($images as $image) {
                try {
                    $imageUrl = ImageHelper::uploadToR2($image, 'marketplace/portfolios');
                    if ($imageUrl) {
                        MarketPortfolioImage::create([
                            'market_portfolio_id' => $portfolio->id,
                            'image' => $imageUrl,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Portfolio Image Upload Failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                }
            }
        }

        return $portfolio;
    }

    public function findPortfolio($id)
    {
        return MarketPortfolio::with('images')->findOrFail($id);
    }

    public function updatePortfolio($id, array $data, $images = null)
    {
        $portfolio = MarketPortfolio::findOrFail($id);

        $portfolio->update([
            'marketplace_id' => $data['marketplace_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => !empty($data['status']) ? 1 : 0,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        if ($images) {
            foreach ($images as $image) {
                try {
                    $imageUrl = ImageHelper::uploadToR2($image, 'marketplace/portfolios');
                    if ($imageUrl) {
                        MarketPortfolioImage::create([
                            'market_portfolio_id' => $portfolio->id,
                            'image' => $imageUrl,
                        ]);
                    }
                } catch (\Exception $e) {
                    // Skip if fails
                }
            }
        }

        return $portfolio;
    }

    public function togglePortfolioStatus($id)
    {
        $portfolio = MarketPortfolio::findOrFail($id);
        $portfolio->status = !$portfolio->status;
        $portfolio->save();

        return $portfolio;
    }

    public function deletePortfolio($id)
    {
        $portfolio = MarketPortfolio::findOrFail($id);

        foreach ($portfolio->images as $image) {
            if ($image->image) {
                ImageHelper::deleteImage($image->image);
            }
            $image->delete();
        }

        $portfolio->delete();

        return $portfolio;
    }

    public function deletePortfolioImage($id)
    {
        $image = MarketPortfolioImage::findOrFail($id);
        if ($image->image) {
            ImageHelper::deleteImage($image->image);
        }
        $image->delete();

        return $image;
    }

    // -------------------------------------------------------- //
    //  SERVICES (MarketServiceController)
    // -------------------------------------------------------- //

    public function listServices()
    {
        return MarketService::with('marketplace')
            ->searchable(['service_name', 'service_brief', 'marketplace:name'])
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
    }

    public function createService(array $data, $images = null)
    {
        $count = 0;

        if ($images) {
            $images = is_array($images) ? $images : [$images];
            foreach ($images as $image) {
                try {
                    $service = new MarketService();
                    $service->marketplace_id = $data['marketplace_id'];
                    $service->service_name = $data['service_name'];
                    $service->service_brief = $data['service_brief'];
                    $service->service_img = ImageHelper::uploadToR2($image, 'marketplace/services');
                    $service->save();
                    $count++;
                } catch (\Exception $exp) {
                    throw $exp;
                }
            }
        }

        return $count;
    }

    public function findService($id)
    {
        return MarketService::findOrFail($id);
    }

    public function updateService($id, array $data, $images = null)
    {
        $service = MarketService::findOrFail($id);
        $service->marketplace_id = $data['marketplace_id'];
        $service->service_name = $data['service_name'];
        $service->service_brief = $data['service_brief'];
        $service->save();

        if ($images) {
            $images = is_array($images) ? $images : [$images];
            foreach ($images as $image) {
                try {
                    $newService = new MarketService();
                    $newService->marketplace_id = $data['marketplace_id'];
                    $newService->service_name = $data['service_name'];
                    $newService->service_brief = $data['service_brief'];
                    $newService->service_img = ImageHelper::uploadToR2($image, 'marketplace/services');
                    $newService->save();
                } catch (\Exception $exp) {
                    throw $exp;
                }
            }
        }

        return $service;
    }

    public function deleteService($id)
    {
        $service = MarketService::findOrFail($id);
        if ($service->service_img) {
            ImageHelper::deleteImage($service->service_img);
        }
        $service->delete();

        return $service;
    }
}
