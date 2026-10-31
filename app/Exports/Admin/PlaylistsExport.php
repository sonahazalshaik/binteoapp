<?php

namespace App\Exports\Admin;

use App\Models\Playlist;

class PlaylistsExport extends AdminBaseExport
{
    protected array $columns = ['id', 'name', 'created_at'];
    protected array $headers = ['ID', 'Name', 'Created'];

    protected function query()
    {
        return Playlist::query()->with('user:id,username')->latest();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->created_at?->format('Y-m-d H:i'),
        ];
    }
}
