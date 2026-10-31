<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'name',
        'duration_type',
        'duration_days',
        'price',
        'description',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'status' => 'boolean',
    ];

    public const DURATION_1_MONTH = '1_month';
    public const DURATION_3_MONTHS = '3_months';
    public const DURATION_6_MONTHS = '6_months';
    public const DURATION_1_YEAR = '1_year';

    public static function standardTiers(): array
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('admin_channel_plans')) {
                $adminPlans = AdminChannelPlan::where('status', 1)->orderBy('sort_order')->orderBy('duration_days')->get();
                if ($adminPlans->isNotEmpty()) {
                    $tiers = [];
                    foreach ($adminPlans as $ap) {
                        $tiers[$ap->duration_type] = [
                            'type' => $ap->duration_type,
                            'days' => $ap->duration_days,
                            'months' => $ap->duration_months,
                            'label' => $ap->name,
                            'default_price' => (float)$ap->default_price,
                            'description' => $ap->description,
                        ];
                    }
                    return $tiers;
                }
            }
        } catch (\Throwable $e) {
            // Fallback to static defaults
        }

        return [
            self::DURATION_1_MONTH => [
                'type' => self::DURATION_1_MONTH,
                'days' => 30,
                'months' => 1,
                'label' => '1 Month Pass',
                'description' => 'Access all premium videos from this channel for 1 month.',
            ],
            self::DURATION_3_MONTHS => [
                'type' => self::DURATION_3_MONTHS,
                'days' => 90,
                'months' => 3,
                'label' => '3 Months Pass',
                'description' => 'Access all premium videos from this channel for 3 months.',
            ],
            self::DURATION_6_MONTHS => [
                'type' => self::DURATION_6_MONTHS,
                'days' => 180,
                'months' => 6,
                'label' => '6 Months Pass',
                'description' => 'Access all premium videos from this channel for 6 months.',
            ],
            self::DURATION_1_YEAR => [
                'type' => self::DURATION_1_YEAR,
                'days' => 365,
                'months' => 12,
                'label' => '1 Year VIP Pass',
                'description' => 'Access all premium videos from this channel for 1 full year.',
            ],
        ];
    }

    public function getDurationLabelAttribute(): string
    {
        return match ($this->duration_type) {
            self::DURATION_1_MONTH => '1 Month',
            self::DURATION_3_MONTHS => '3 Months',
            self::DURATION_6_MONTHS => '6 Months',
            self::DURATION_1_YEAR => '1 Year',
            default => "{$this->duration_days} Days",
        };
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(ChannelSubscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
