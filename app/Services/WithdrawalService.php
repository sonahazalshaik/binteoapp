<?php

namespace App\Services;

class WithdrawalService
{
    public function userHistory($user)
    {
        return \App\Models\Withdrawal::where('user_id', $user->id)->where('status', '!=', 0)->with('method')->latest()->paginate(getPaginate());
    }

    public function availableMethods()
    {
        return \App\Models\WithdrawMethod::where('status', 1)->get();
    }
}
