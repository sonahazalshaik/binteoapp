<?php

namespace App\Exports\Admin;

use App\Models\Plan;

class PlansExport extends AdminBaseExport
{
    protected array $columns = ['id', 'name', 'price', 'status', 'created_at'];
    protected array $headers = ['ID', 'Name', 'Price', 'Status', 'Created'];

    protected function query()
    {
        return Plan::query()->latest();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->price ?? 0,
            $row->status ?? 'active',
            $row->created_at?->format('Y-m-d H:i'),
        ];
    }
}
