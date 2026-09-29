<?php

namespace App\Exports\Admin;

use App\Models\User;

class UsersExport extends AdminBaseExport
{
    protected array $columns = ['id', 'username', 'email', 'status', 'created_at'];
    protected array $headers = ['ID', 'Username', 'Email', 'Status', 'Registered'];

    protected function query()
    {
        return User::query()->latest();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->username,
            $row->email,
            $row->status ?? 'active',
            $row->created_at?->format('Y-m-d H:i'),
        ];
    }
}
