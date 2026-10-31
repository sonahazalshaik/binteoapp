<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    public function userTransactions($userId, $paginate = 20)
    {
        return Transaction::where('user_id', $userId)
            ->with('user')
            ->latest()
            ->paginate($paginate);
    }

    public function getByTrx($trx)
    {
        return Transaction::where('trx', $trx)->first();
    }
}
