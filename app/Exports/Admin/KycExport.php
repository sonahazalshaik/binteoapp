<?php

namespace App\Exports\Admin;

use App\Models\KycSubmission;

class KycExport extends AdminBaseExport
{
    protected array $columns = ['id', 'user_id', 'status', 'created_at'];
    protected array $headers = ['ID', 'User', 'Status', 'Submitted'];

    protected function query()
    {
        return KycSubmission::query()->with('user:id,username')->latest();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->user?->username ?? 'N/A',
            $row->status ?? 'pending',
            $row->created_at?->format('Y-m-d H:i'),
        ];
    }
}
