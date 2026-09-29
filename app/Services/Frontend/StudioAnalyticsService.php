<?php

namespace App\Services\Frontend;

use Illuminate\Http\Request;

class StudioAnalyticsService
{
    public function overview(Request $request): array
    {
        $user = auth()->user();
        $period = $request->period ?? '7_days';

        $views = $user->viewLogs()
            ->when($period === '7_days', fn($q) => $q->where('created_at', '>=', now()->subDays(7)))
            ->when($period === '30_days', fn($q) => $q->where('created_at', '>=', now()->subDays(30)))
            ->when($period === '90_days', fn($q) => $q->where('created_at', '>=', now()->subDays(90)))
            ->count();

        $totalViews = $user->videos()->sum('views_count');
        $totalVideos = $user->videos()->count();

        return compact('views', 'totalViews', 'totalVideos');
    }

    public function reach(Request $request): array
    {
        $user = auth()->user();
        $subscribers = $user->channel?->subscribers()->count() ?? 0;
        $impressions = $user->videos()->sum('views_count');

        return compact('subscribers', 'impressions');
    }

    public function realtime(Request $request): array
    {
        $user = auth()->user();
        $activeNow = $user->viewLogs()
            ->where('created_at', '>=', now()->subMinutes(5))
            ->distinct('ip')
            ->count('ip');

        return compact('activeNow');
    }

    public function audience(Request $request): array
    {
        $user = auth()->user();
        $topCountries = $user->viewLogs()
            ->selectRaw('country, COUNT(*) as views')
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('views')
            ->take(5)
            ->get();

        return compact('topCountries');
    }
}


