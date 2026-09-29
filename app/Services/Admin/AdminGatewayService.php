<?php

namespace App\Services\Admin;

class AdminGatewayService
{
    public function toggleGateway($gateway): void
    {
        $gateway->update(['status' => !$gateway->status]);
    }

    public function updateCredentials($gateway, array $data): void
    {
        $gateway->update($data);
    }

    public function allGateways()
    {
        return \App\Models\Gateway::with('currencies')->paginate(getPaginate());
    }

    public function manageManualGateway($gateway, array $data): void
    {
        $gateway->update($data);
    }
}
