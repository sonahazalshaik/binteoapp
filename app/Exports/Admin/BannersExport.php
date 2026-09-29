<?php

namespace App\Exports\Admin;

use App\Models\BannerAd;

class BannersExport extends AdminBaseExport
{
    protected array $columns = ['id', 'slot', 'status', 'created_at'];
    protected array $headers = ['ID', 'Slot', 'Status', 'Created'];

    protected function query()
    {
        return BannerAd::query()->latest();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->slot ?? 'N/A',
            $row->status ? 'Active' : 'Inactive',
            $row->created_at?->format('Y-m-d H:i'),
        ];
    }
}
