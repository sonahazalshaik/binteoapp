<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type', // pre-roll, mid-roll, banner
        'media_path',
        'click_url',
        'duration',
        'skip_after',
        'cpc',
        'cpm',
        'is_active',
    ];

    public function videos()
    {
        return $this->belongsToMany(Video::class);
    }

    protected $casts = [
        'is_active' => 'boolean',
        'cpc' => 'decimal:4',
        'cpm' => 'decimal:4',
    ];
}
