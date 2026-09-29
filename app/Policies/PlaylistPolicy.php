<?php

namespace App\Policies;

use App\Models\Playlist;
use App\Models\User;
use App\Constants\Status;

class PlaylistPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Playlist $playlist): bool
    {
        if ($playlist->visibility == Status::PUBLIC) {
            return true;
        }

        return $user && $user->id == $playlist->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Playlist $playlist): bool
    {
        return $user->id == $playlist->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Playlist $playlist): bool
    {
        return $user->id == $playlist->user_id;
    }
}
