<?php

namespace App\Services\Admin;

use App\Models\MarketSubscription;
use App\Models\MarketSubscriptionPayment;

class MarketSubscriptionService
{
    public function list()
    {
        return MarketSubscription::with(['marketplace', 'plan'])
            ->searchable(['marketplace:name', 'marketplace:email'])
            ->latest()
            ->paginate(getPaginate());
    }

    public function find($id)
    {
        return MarketSubscription::with(['marketplace', 'plan'])->findOrFail($id);
    }

    public function getActiveSubscriptions()
    {
        return MarketSubscription::with(['marketplace', 'plan'])
            ->where('end_date', '>=', now())
            ->latest()
            ->paginate(getPaginate());
    }

    public function getExpiredSubscriptions()
    {
        return MarketSubscription::with(['marketplace', 'plan'])
            ->where('end_date', '<', now())
            ->latest()
            ->paginate(getPaginate());
    }

    public function getPayments()
    {
        return MarketSubscriptionPayment::with(['marketplace', 'plan'])
            ->latest()
            ->paginate(getPaginate());
    }

    public function cancelSubscription($id)
    {
        $subscription = MarketSubscription::findOrFail($id);
        $subscription->end_date = now();
        $subscription->save();

        return $subscription;
    }
}
