<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KycSubmission extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'date_of_birth' => 'date',
        'reviewed_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'reviewed_by');
    }

    // Status helpers
    public function isPending()
    {
        return $this->status == 0;
    }

    public function isApproved()
    {
        return $this->status == 1;
    }

    public function isRejected()
    {
        return $this->status == 2;
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            0 => '<span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-600 border border-amber-500/20 italic">Pending</span>',
            1 => '<span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 italic">Verified</span>',
            2 => '<span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-rose-500/10 text-rose-600 border border-rose-500/20 italic">Rejected</span>',
            default => '<span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-500/10 text-slate-600 border border-slate-500/20 italic">Unknown</span>',
        };
    }
}
