<?php

namespace App\Services\Frontend;

use App\Models\Membership;
use App\Models\UserMembership;

class MembershipService
{
    public function join(Membership $membership): array
    {
        $user = auth()->user();

        if ($user->id === $membership->channel->user_id) {
            return ['success' => false, 'message' => 'You cannot join your own channel.'];
        }

        $existing = UserMembership::where('user_id', $user->id)
            ->where('membership_id', $membership->id)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return ['success' => false, 'message' => 'You are already a member of this tier.'];
        }

        UserMembership::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'expires_at' => now()->addMonth(),
            'status' => 'active',
        ]);

        return ['success' => true, 'message' => 'Welcome to the ' . $membership->name . ' family!'];
    }
}


