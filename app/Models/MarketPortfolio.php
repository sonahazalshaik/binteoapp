<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPortfolio extends Model
{
    protected $guarded = ['id'];

    public function marketplace()
    {
        return $this->belongsTo(MarketPlace::class);
    }

    public function images()
    {
        return $this->hasMany(MarketPortfolioImage::class, 'market_portfolio_id')->orderBy('sort_order', 'asc');
    }
}
