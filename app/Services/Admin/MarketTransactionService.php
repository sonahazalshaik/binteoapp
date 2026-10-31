<?php

namespace App\Services\Admin;

use App\Models\MarketSubscriptionPayment;

class MarketTransactionService
{
    public function list()
    {
        return MarketSubscriptionPayment::with(['marketplace', 'plan'])
            ->searchable(['trx', 'gateway_code', 'marketplace:name', 'marketplace:email'])
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
    }

    public function find($id)
    {
        return MarketSubscriptionPayment::with(['marketplace', 'plan'])->findOrFail($id);
    }
}
