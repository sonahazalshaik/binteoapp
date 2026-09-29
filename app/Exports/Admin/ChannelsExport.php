<?php

namespace App\Exports\Admin;

use App\Models\Channel;

class ChannelsExport extends AdminBaseExport
{
    protected array $columns = ['id', 'name', 'slug', 'subscribers_count', 'is_active', 'created_at'];
    protected array $headers = ['ID', 'Name', 'Slug', 'Subscribers', 'Active', 'Created'];

    protected function query()
    {
        return Channel::query()->with('user')->latest();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->slug,
            $row->subscribers_count ?? 0,
            $row->is_active ? 'Yes' : 'No',
            $row->created_at?->format('Y-m-d H:i'),
        ];
    }
}
