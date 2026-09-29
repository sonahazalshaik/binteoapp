<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    public function fullname(): Attribute
    {
        return new Attribute(
            get:fn () => $this->user ? $this->user->fullname : $this->name,
        );
    }

    public function username(): Attribute
    {
        return new Attribute(
            get:fn () => $this->email,
        );
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function(){
            $html = '';
            if($this->status == Status::TICKET_OPEN){
                $html = '<span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase tracking-widest border border-emerald-500/10">'.trans("Open").'</span>';
            }
            elseif($this->status == Status::TICKET_ANSWER){
                $html = '<span class="px-3 py-1 rounded-lg bg-blue-500/10 text-blue-500 text-[9px] font-black uppercase tracking-widest border border-blue-500/10">'.trans("Answered").'</span>';
            }
            elseif($this->status == Status::TICKET_REPLY){
                $html = '<span class="px-3 py-1 rounded-lg bg-amber-500/10 text-amber-500 text-[9px] font-black uppercase tracking-widest border border-amber-500/10">'.trans("Customer Reply").'</span>';
            }
            elseif($this->status == Status::TICKET_CLOSE){
                $html = '<span class="px-3 py-1 rounded-lg bg-gray-500/10 text-gray-500 text-[9px] font-black uppercase tracking-widest border border-gray-500/10">'.trans("Closed").'</span>';
            }
            elseif($this->status == Status::TICKET_REJECT){
                $html = '<span class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-widest border border-rose-500/10">'.trans("Rejected").'</span>';
            }
            return $html;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supportMessage(){
        return $this->hasMany(SupportMessage::class);
    }


    public function scopePending($query){
        return $query->whereIn('status', [Status::TICKET_OPEN,Status::TICKET_REPLY]);
    }

    public function scopeClosed($query){
        return $query->where('status',Status::TICKET_CLOSE);
    }

    public function scopeAnswered($query){
        return $query->where('status',Status::TICKET_ANSWER);
    }

}
