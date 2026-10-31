<?php

namespace App\Http\Controllers;

use App\Models\OnboardingSlide;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    /**
     * Get the onboarding slides for mobile app.
     */
    public function getSlides()
    {
        // Fetch ordered slides, limited to exactly 3 for the dynamic mobile onboarding pages
        $slides = OnboardingSlide::orderBy('sort_order', 'asc')
            ->limit(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $slides
        ], 200);
    }
}
