<?php

namespace App\Exports\Admin;

use App\Models\MarketPlace;

class MarketplaceExport extends AdminBaseExport
{
    protected array $columns = ['id', 'name', 'type', 'rating', 'status', 'created_at'];
    protected array $headers = ['ID', 'Name', 'Type', 'Rating', 'Status', 'Created'];

    protected function query()
    {
        return MarketPlace::query()->latest();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name ?? 'N/A',
            $row->type ?? 'N/A',
            $row->rating ?? 0,
            $row->status ?? 'pending',
            $row->created_at?->format('Y-m-d H:i'),
        ];
    }
}
