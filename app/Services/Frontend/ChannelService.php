<?php

namespace App\Services\Frontend;

use App\Models\Channel;
use App\Models\Video;
use App\Models\Reel;
use App\Models\Playlist;
use App\Models\User;
use App\Models\Report;
use App\Models\AdminNotification;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ChannelService
{
    public function show($slug): array
    {
        $channel = Channel::where('slug', $slug)->first();

        if (!$channel && is_numeric($slug)) {
            $channel = Channel::find($slug);
            if ($channel && $channel->slug) {
                return ['redirect' => route('channels.show', $channel->slug)];
            }
        }

        if (!$channel) {
            abort(404);
        }

        $channel->load(['user.videos']);
        $pageTitle = $channel->name;

        $isOwner = auth()->check() && (string)auth()->id() === (string)$channel->user_id;

        if ($isOwner) {
            $baseQuery = $channel->user->videos()->with('reports');
        } else {
            $baseQuery = $channel->user->videos()->forChannelFeed()->with('reports');
        }

        $popularVideos = (clone $baseQuery)->orderBy('views_count', 'desc')->take(20)->get();
        $recentVideos  = (clone $baseQuery)->latest()->take(20)->get();
        $oldestVideos  = (clone $baseQuery)->oldest()->take(20)->get();
        $premiumVideos = (clone $baseQuery)->where('is_premium', 1)->latest()->take(20)->get();
        $playlists     = $channel->user->playlists()->withCount(['videos', 'reels'])->latest()->get();

        if ($isOwner) {
            $reels = $channel->user->reels()->published()->with('reports')->latest()->get();
        } else {
            $reels = $channel->user->reels()->forUser()->with('reports')->latest()->get();
        }

        return compact('channel', 'pageTitle', 'popularVideos', 'recentVideos', 'oldestVideos', 'premiumVideos', 'playlists', 'reels');
    }

    public function showByUsername($username): array
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            return ['error' => 'User not found.'];
        }

        $channel = $user->channel;
        if (!$channel) {
            return ['error' => 'This user does not have a channel page.'];
        }

        return ['slug' => $channel->slug];
    }

    public function checkAvailability(Request $request): array
    {
        $field = $request->field;
        $value = $request->value;

        if ($field === 'username') {
            if (strlen($value) < 6) {
                return ['error' => 'Username must be at least 6 characters.'];
            }
            if (preg_match("/\s/", $value)) {
                return ['error' => 'Username cannot contain spaces.'];
            }
            if (preg_match("/[^a-z0-9_]/", $value)) {
                return ['error' => 'Small letters, numbers, and underscore only.'];
            }
            if (User::where('username', $value)->where('id', '!=', auth()->id())->exists()) {
                return ['error' => 'This username is already taken.'];
            }
        }

        if ($field === 'channel_name') {
            if (empty(trim($value))) {
                return ['error' => 'Channel name is required.'];
            }
            $channelId = auth()->user()->channel?->id;
            $query = Channel::where('name', $value);
            if ($channelId) {
                $query->where('id', '!=', $channelId);
            }
            if ($query->exists()) {
                return ['error' => 'This channel name is already taken.'];
            }
        }

        if ($field === 'mobile') {
            $cleanMobile = preg_replace('/\D/', '', $value);
            if (strlen($cleanMobile) !== 10) {
                return ['error' => 'Mobile number must be exactly 10 digits.'];
            }
            if (User::where('mobile', $cleanMobile)->where('id', '!=', auth()->id())->exists()) {
                return ['error' => 'This mobile number is already registered.'];
            }
        }

        return ['success' => true];
    }

    public function create(): array
    {
        $user = auth()->user();

        if ($user->channel) {
            if ($user->creator_status == 0) {
                $user->creator_status = 1;
                $user->save();
            }
            return ['redirect' => route('studio.dashboard')];
        }

        $pageTitle = "Create Channel";
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $mobileCode = '1';

        return compact('pageTitle', 'countries', 'mobileCode');
    }

    public function store(Request $request): array
    {
        $user = auth()->user();

        Log::info('Channel Creation Attempt - Start', [
            'user_id' => $user->id,
            'is_mobile' => preg_match('/Mobile|Android|BlackBerry|iPhone|Windows Phone/', $request->userAgent()),
            'user_agent' => $request->userAgent(),
            'request_data' => $request->except(['image', '_token'])
        ]);

        if ($user->profile_complete == 1 && $user->channel) {
            Log::warning('Channel Creation - User already has a channel', ['user_id' => $user->id]);
            return ['error' => 'You already have a channel.'];
        }

        $channelId = $user->channel?->id;

        try {
            $request->validate([
                'username'     => ['required', 'min:6', Rule::unique('users', 'username')->ignore($user->id)],
                'mobile'       => ['required', 'digits:10'],
                'channel_name' => ['required', 'string', 'max:255', Rule::unique('channels', 'name')->ignore($channelId)],
                'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            ], [
                'mobile.digits' => 'Mobile number must be exactly 10 digits.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Channel Creation - Validation Failed', [
                'user_id' => $user->id,
                'errors' => $e->errors()
            ]);
            throw $e;
        }

        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            Log::error('Channel Creation - Invalid Username Format', [
                'user_id' => $user->id,
                'username' => $request->username
            ]);
            return ['validation_error' => 'Username can contain only small letters, numbers and underscore.'];
        }

        try {
            $channel = $user->channel()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $request->channel_name,
                    'description' => "Welcome to " . $request->channel_name,
                    'is_active' => true
                ]
            );

            if (!$channel) {
                throw new \Exception('Failed to create channel record.');
            }

            if ($request->hasFile('image')) {
                try {
                    $old = $user->image;
                    $user->image = ImageHelper::uploadToR2($request->image, 'userProfile');

                    $channel->avatar = $user->image;
                    $channel->save();

                    if ($old) {
                        ImageHelper::deleteImage($old);
                    }
                } catch (\Exception $exp) {
                    Log::error('Channel Creation Image Upload Failed: ' . $exp->getMessage());
                }
            }

            $user->country_code = $request->country_code;
            $user->channel_name = $request->channel_name;
            $user->slug = \Illuminate\Support\Str::slug($request->channel_name) . "-" . $user->id;
            $user->mobile = $request->mobile;
            $user->username = $request->username;
            $user->address = $request->address;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->zip = $request->zip;
            $user->country_name = $request->country;
            $user->dial_code = $request->country_code;
            $user->profile_complete = 1;
            $user->creator_status = 1;
            $user->save();

        } catch (\Exception $e) {
            Log::error('Channel Creation CRITICAL Error for user ' . $user->id . ': ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return ['error' => 'Could not finalize channel creation. ' . $e->getMessage()];
        }

        Log::info('Channel Creation - Success', [
            'user_id' => $user->id,
            'channel_name' => $user->channel_name
        ]);

        return ['success' => true];
    }

    public function report(Request $request, Channel $channel): array
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        Report::create([
            'user_id' => auth()->id(),
            'reported_user_id' => $channel->user_id,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        AdminNotification::create([
            'user_id' => auth()->id(),
            'title' => 'New channel report: ' . ($channel->user->channel_name ?? $channel->name),
            'click_url' => route('admin.reports.index'),
        ]);

        return ['success' => true, 'message' => 'Report submitted successfully'];
    }
}
