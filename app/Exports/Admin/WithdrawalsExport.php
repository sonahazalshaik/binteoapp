<?php

namespace App\Exports\Admin;

use App\Models\Withdrawal;

class WithdrawalsExport extends AdminBaseExport
{
    protected array $columns = ['id', 'user_id', 'amount', 'status', 'created_at'];
    protected array $headers = ['ID', 'User', 'Amount', 'Status', 'Date'];

    protected function query()
    {
        return Withdrawal::query()->with('user:id,username')->latest();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->user?->username ?? 'N/A',
            $row->amount ?? 0,
            $row->status ?? 'pending',
            $row->created_at?->format('Y-m-d H:i'),
        ];
    }
}
