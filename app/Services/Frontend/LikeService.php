<?php

namespace App\Services\Frontend;

use App\Models\Video;

class LikeService
{
    public function toggle($id): array
    {
        $video = Video::where("id", $id)->first() ?? Video::where("slug", $id)->first();
        if (!$video) {
            return ["status" => "error", "message" => "Video not found"];
        }

        $user = auth()->user();

        if ($video->isLikedBy($user)) {
            $video->likes()->where("user_id", $user->id)->delete();
            $liked = false;
        } else {
            $video->likes()->create(["user_id" => $user->id]);
            $liked = true;
        }

        return [
            "liked" => $liked,
            "likes_count" => $video->likes()->count(),
        ];
    }
}

