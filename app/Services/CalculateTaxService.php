<?php

namespace App\Services;

use App\Models\TaxRate;
use Illuminate\Support\Facades\Cache;

class CalculateTaxService
{
    public function calculateTax(float $subtotal, array $location): array
    {
        $taxRate = $this->getTaxRate(
            $location['country'] ?? 'US',
            $location['state'] ?? ''
        );

        $taxAmount = round($subtotal * $taxRate, 2);

        return [
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'total_tax' => $taxAmount,
            'breakdown' => [
                [
                    'name' => $this->getTaxName($location['country'] ?? 'US'),
                    'rate' => $taxRate,
                    'amount' => $taxAmount,
                ],
            ],
            'currency' => gs('cur_text') ?? 'USD',
            'country' => $location['country'] ?? 'US',
            'state' => $location['state'] ?? '',
        ];
    }

    public function getTaxRate(string $country, string $state = ''): float
    {
        $rate = TaxRate::where('country', $country)
            ->when($state, function ($q) use ($state) {
                return $q->where('state', $state);
            })
            ->value('rate');

        if ($rate !== null) {
            return (float) $rate;
        }

        $rate = TaxRate::where('country', $country)
            ->whereNull('state')
            ->value('rate');

        if ($rate !== null) {
            return (float) $rate;
        }

        return (float) (gs('tax_percentage') ?? 0) / 100;
    }

    public function isTaxExempt(array $productCategoryIds): bool
    {
        $exemptCategories = Cache::remember('tax_exempt_categories', 3600, function () {
            return \App\Models\Category::where('is_tax_exempt', true)
                ->pluck('id')
                ->toArray();
        });

        foreach ($productCategoryIds as $catId) {
            if (in_array($catId, $exemptCategories)) {
                return true;
            }
        }

        return false;
    }

    public function getTaxName(string $country): string
    {
        $names = [
            'US' => 'Sales Tax',
            'IN' => 'GST',
            'GB' => 'VAT',
            'AU' => 'GST',
            'CA' => 'HST',
            'AE' => 'VAT',
            'SG' => 'GST',
            'EU' => 'VAT',
        ];

        return $names[$country] ?? 'Tax';
    }

    public function getAvailableTaxRates(): array
    {
        return TaxRate::orderBy('country')->orderBy('state')->get()->toArray();
    }
}
