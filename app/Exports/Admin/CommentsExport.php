<?php

namespace App\Exports\Admin;

use App\Models\Comment;

class CommentsExport extends AdminBaseExport
{
    protected array $columns = ['id', 'content', 'created_at'];
    protected array $headers = ['ID', 'Content', 'Created'];

    protected function query()
    {
        return Comment::query()->with('user:id,username', 'video:id,title')->latest();
    }

    public function map($row): array
    {
        return [
            $row->id,
            \Str::limit($row->content, 100),
            $row->created_at?->format('Y-m-d H:i'),
        ];
    }
}
