<?php

namespace App\Services\Admin;

use App\Models\Channel;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ChannelService
{
    public function authorize(string $ability): void
    {
        abort_if(Gate::denies($ability), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function channelQuery(?string $scope = null)
    {
        $query = $scope ? Channel::$scope() : Channel::query();
        return $query->with('user')
            ->searchable(['name', 'user:username'])
            ->filter(['is_active'])
            ->sortFilter('id', 'desc');
    }

    public function createChannel(array $data, ?array $files = []): Channel
    {
        $channel = new Channel();
        $channel->user_id = $data['user_id'];
        $channel->name = $data['name'];
        $channel->description = $data['description'] ?? null;
        $channel->social_links = $data['social_links'] ?? null;
        $channel->is_active = $data['is_active'] ?? true;

        if (isset($files['avatar'])) {
            $channel->avatar = fileUploader($files['avatar'], getFilePath('channelAvatar'));
        }
        if (isset($files['banner'])) {
            $channel->banner = fileUploader($files['banner'], getFilePath('channelBanner'));
        }

        $channel->save();
        return $channel;
    }

    public function updateChannel(Channel $channel, array $data, ?array $files = []): Channel
    {
        $channel->name = $data['name'] ?? $channel->name;
        $channel->description = $data['description'] ?? $channel->description;
        $channel->social_links = $data['social_links'] ?? $channel->social_links;

        if (isset($files['avatar'])) {
            $channel->avatar = fileUploader($files['avatar'], getFilePath('channelAvatar'), null, $channel->avatar);
        }
        if (isset($files['banner'])) {
            $channel->banner = fileUploader($files['banner'], getFilePath('channelBanner'), null, $channel->banner);
        }

        $channel->save();

        if (isset($data['country']) || isset($data['country_code'])) {
            $user = $channel->user;
            $user->country_name = $data['country'] ?? $user->country_name;
            $user->country_code = $data['country_code'] ?? $user->country_code;
            $user->dial_code = $data['country_code'] ?? $user->dial_code;
            $user->save();
        }

        return $channel;
    }

    public function toggleFeatured(Channel $channel): string
    {
        $channel->is_featured = !$channel->is_featured;
        $channel->save();

        return $channel->is_featured ? 'featured' : 'unfeatured';
    }

    public function toggleTrending(Channel $channel): string
    {
        $channel->is_trending = !$channel->is_trending;
        $channel->save();

        return $channel->is_trending ? 'trending' : 'normal';
    }

    public function toggleStatus(Channel $channel): void
    {
        $channel->is_active = !$channel->is_active;
        $channel->save();
    }

    public function deleteChannel(Channel $channel): void
    {
        $channel->deleteWithAssets();
    }

    public function bulkAction(array $ids, string $action): void
    {
        $channels = Channel::whereIn('id', $ids)->get();

        foreach ($channels as $channel) {
            match ($action) {
                'delete' => $channel->deleteWithAssets(),
                'featured' => $channel->update(['is_featured' => 1]),
                'unfeatured' => $channel->update(['is_featured' => 0]),
                'trending' => $channel->update(['is_trending' => 1]),
                'untrending' => $channel->update(['is_trending' => 0]),
                'approve' => $channel->update(['is_active' => 1]),
                'unapprove' => $channel->update(['is_active' => 0]),
                default => null,
            };
        }
    }

    public function getChannelDetail(Channel $channel): Channel
    {
        $channel->load([
            'videos' => fn($q) => $q->orderBy('created_at', 'desc')->take(10),
            'reels' => fn($q) => $q->orderBy('created_at', 'desc')->take(10),
        ]);

        return $channel;
    }

    public function getSubscriberChannels(?string $search = null, ?int $channelId = null, int $perPage = 15)
    {
        $query = Channel::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('username', 'LIKE', "%{$search}%")
                      ->orWhere('firstname', 'LIKE', "%{$search}%")
                      ->orWhere('lastname', 'LIKE', "%{$search}%"));
            });
        }

        return $query->when($channelId, fn($q) => $q->where('id', $channelId))
            ->with(['user', 'subscribers'])
            ->withCount(['subscribers', 'videos', 'reels'])
            ->orderBy('subscribers_count', 'desc')
            ->paginate($perPage);
    }
}
