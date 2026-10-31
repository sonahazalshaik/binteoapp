<?php

namespace App\Exports\Admin;

use App\Models\Deposit;

class DepositsExport extends AdminBaseExport
{
    protected array $columns = ['id', 'user_id', 'amount', 'status', 'created_at'];
    protected array $headers = ['ID', 'User', 'Amount', 'Status', 'Date'];

    protected function query()
    {
        $scope = request()->get('scope');
        $query = ($scope && in_array($scope, ['pending', 'rejected', 'successful'], true)
                && method_exists(Deposit::class, 'scope' . ucfirst($scope)))
            ? Deposit::$scope()->with('user:id,username')
            : Deposit::query()->with('user:id,username');

        if (request()->filled('user_id')) {
            $query->where('user_id', request()->user_id);
        }

        $query->searchable(['trx', 'user:username'])->dateFilter();

        if (request()->filled('method')) {
            if (request()->method != \App\Constants\Status::GOOGLE_PAY) {
                $gateway = \App\Models\Gateway::where('alias', request()->method)->first();
                if ($gateway) {
                    $query->where('method_code', $gateway->code);
                }
            } else {
                $query->where('method_code', \App\Constants\Status::GOOGLE_PAY);
            }
        }

        return $query->latest();
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
