<?php

namespace App\Services;

class PaymentService
{
    public function availableGateways()
    {
        return \App\Models\GatewayCurrency::whereHas('gateway', function ($gate) {
            $gate->where('status', 1);
        })->with('gateway')->orderby('method_code')->get();
    }

    public function validateGateway($gatewayId, $currency, $amount)
    {
        $gate = \App\Models\GatewayCurrency::whereHas('gateway', function ($gate) {
            $gate->where('status', 1);
        })->where('method_code', $gatewayId)->where('currency', $currency)->first();

        if (!$gate) {
            return ['success' => false, 'message' => 'Invalid Gateway Protocol.'];
        }

        if ($gate->min_amount > $amount || $gate->max_amount < $amount) {
            return ['success' => false, 'message' => 'Amount out of operational bounds.'];
        }

        $charge = $gate->fixed_charge + ($amount * $gate->percent_charge / 100);
        $payable = $amount + $charge;
        $final_amo = $payable * $gate->rate;

        return [
            'success' => true,
            'gate' => $gate,
            'charge' => $charge,
            'payable' => $payable,
            'final_amo' => $final_amo,
        ];
    }
}
