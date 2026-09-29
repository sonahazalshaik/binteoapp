<?php

namespace App\Exports\Admin;

use App\Models\Video;

class VideosExport extends AdminBaseExport
{
    protected array $columns = ['id', 'title', 'views_count', 'is_featured', 'is_trending', 'status', 'created_at'];
    protected array $headers = ['ID', 'Title', 'Views', 'Featured', 'Trending', 'Status', 'Created'];

    protected function query()
    {
        return Video::query()->with('user:id,username')->latest();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->title,
            $row->views_count ?? 0,
            $row->is_featured ? 'Yes' : 'No',
            $row->is_trending ? 'Yes' : 'No',
            $row->status ?? 'unknown',
            $row->created_at?->format('Y-m-d H:i'),
        ];
    }
}
