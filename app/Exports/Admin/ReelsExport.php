<?php

namespace App\Exports\Admin;

use App\Models\Reel;

class ReelsExport extends AdminBaseExport
{
    protected array $columns = ['id', 'title', 'views_count', 'likes_count', 'is_trending', 'status', 'created_at'];
    protected array $headers = ['ID', 'Title', 'Views', 'Likes', 'Trending', 'Status', 'Created'];

    protected function query()
    {
        return Reel::query()->with('user:id,username')->latest();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->title,
            $row->views_count ?? 0,
            $row->likes_count ?? 0,
            $row->is_trending ? 'Yes' : 'No',
            $row->status ?? 'unknown',
            $row->created_at?->format('Y-m-d H:i'),
        ];
    }
}
