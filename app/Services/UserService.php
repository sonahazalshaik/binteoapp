<?php

namespace App\Services;

class UserService
{
    public function profileData($user)
    {
        $user->loadCount(['videos', 'subscribers', 'subscriptions']);
        return $user;
    }

    public function updateProfile($user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function changePassword($user, string $newPassword)
    {
        $user->update(['password' => bcrypt($newPassword)]);
        return true;
    }

    public function updateKyc($user, array $data)
    {
        $user->kyc_data = $data;
        $user->kv = 2;
        $user->save();
        return $user;
    }
}
