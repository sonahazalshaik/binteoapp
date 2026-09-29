<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPortfolioImage extends Model
{
    protected $guarded = ['id'];

    public function portfolio()
    {
        return $this->belongsTo(MarketPortfolio::class, 'market_portfolio_id');
    }
}
