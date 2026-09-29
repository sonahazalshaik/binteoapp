<?php

namespace App\Services\Frontend;

use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileService
{
    public function editData(Request $request): array
    {
        $user = $request->user()->load(['channel'])->loadCount(['subscribers', 'videos']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return compact('user', 'countries');
    }

    public function update(Request $request, $user): array
    {
        $user->fill($request->safe()->except(['image', 'cover', 'remove_image', 'remove_cover']));

        if ($request->hasFile('image')) {
            try {
                $old = $user->image;
                $user->image = ImageHelper::uploadToR2($request->image, 'userProfile');

                if ($user->channel) {
                    $user->channel->avatar = $user->image;
                    $user->channel->save();
                }

                if ($old) {
                    ImageHelper::deleteImage($old);
                }
            } catch (\Exception $exp) {
                return ['success' => false, 'message' => 'Couldn\'t upload your avatar image'];
            }
        } elseif ($request->remove_image) {
            if ($user->image) {
                ImageHelper::deleteImage($user->image);
                $user->image = null;
                if ($user->channel) {
                    $user->channel->avatar = null;
                    $user->channel->save();
                }
                return ['success' => true, 'message' => 'Avatar image removed successfully'];
            }
        }

        if ($request->hasFile('cover')) {
            try {
                $channel = $user->channel ?? $user->channel()->create([
                    'name' => $user->channel_name ?? $user->fullname,
                    'description' => "Welcome to " . ($user->channel_name ?? $user->fullname) . "'s channel!"
                ]);

                $oldCover = $channel->banner;
                $channel->banner = ImageHelper::uploadToR2($request->cover, 'cover');
                $channel->save();

                if ($oldCover) {
                    ImageHelper::deleteImage($oldCover);
                }
            } catch (\Exception $exp) {
                return ['success' => false, 'message' => 'Couldn\'t upload your cover photo'];
            }
        } elseif ($request->remove_cover) {
            if ($user->channel && $user->channel->banner) {
                ImageHelper::deleteImage($user->channel->banner);
                $user->channel->banner = null;
                $user->channel->save();
                return ['success' => true, 'message' => 'Channel banner removed successfully'];
            }
        }

        $user->name = $user->firstname . ' ' . $user->lastname;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return ['success' => true, 'message' => 'Profile updated successfully!'];
    }

    public function destroy(Request $request): void
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
            'reason' => ['required', 'string'],
            'custom_reason' => ['required_if:reason,Other', 'nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        \App\Models\DeletionRequest::updateOrCreate(
            ['user_id' => $user->id],
            [
                'reason' => $request->reason,
                'custom_reason' => $request->custom_reason,
                'status' => 'pending'
            ]
        );

        // Admin Notification
        try {
            \App\Models\AdminNotification::create([
                'user_id' => $user->id,
                'title' => 'New account deletion request from ' . $user->username,
                'click_url' => route('admin.users.deletion.requests'),
            ]);
        } catch (\Exception $e) {
            \Log::error("Failed creating admin notification: " . $e->getMessage());
        }

        // In-app User Notification
        try {
            \App\Models\UserNotification::create([
                'user_id' => $user->id,
                'sender_id' => $user->id,
                'title' => 'Your account deletion request is pending administrator approval.',
                'click_url' => route('profile.edit'),
            ]);
        } catch (\Exception $e) {
            \Log::error("Failed creating user notification: " . $e->getMessage());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}


