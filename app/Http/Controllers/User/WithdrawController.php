<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use App\Models\Transaction;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;

class WithdrawController extends Controller {

    protected WithdrawalService $withdrawalService;

    public function __construct(WithdrawalService $withdrawalService)
    {
        $this->withdrawalService = $withdrawalService;
    }


    public function withdrawMethod() {
        $user           = auth()->user();
        
        if ($user->kv == 0) {
            $notify[] = ['error', 'You are not KYC verified. For being KYC verified, please provide these information'];
            return to_route('user.kyc.form')->withNotify($notify);
        } elseif ($user->kv == 2) {
            $notify[] = ['warning', 'Your KYC is under review'];
            return to_route('user.kyc.data')->withNotify($notify);
        }

        $withdrawMethod = WithdrawMethod::where('status', Status::ENABLE)->get();
        $pageTitle      = 'Withdrawal Methods';
        return view('frontend.client.withdraw.methods', compact('pageTitle', 'withdrawMethod', 'user'));
    }

    public function withdrawMethodSubmit(Request $request) {
        $request->validate([
            'method_code' => 'required',
            'amount'      => 'required|numeric|gt:0',
        ]);

        $method = WithdrawMethod::where('id', $request->method_code)->where('status', Status::ENABLE)->firstOrFail();
        $user   = auth()->user();

        if ($user->kv == 0) {
            $notify[] = ['error', 'You are not KYC verified. For being KYC verified, please provide these information'];
            return to_route('user.kyc.form')->withNotify($notify);
        } elseif ($user->kv == 2) {
            $notify[] = ['warning', 'Your KYC is under review'];
            return to_route('user.kyc.data')->withNotify($notify);
        }

        if ($request->amount < $method->min_limit) {
            $notify[] = ['error', 'Your requested amount is smaller than minimum amount.'];
            return back()->withNotify($notify);
        }
        if ($request->amount > $method->max_limit) {
            $notify[] = ['error', 'Your requested amount is larger than maximum amount.'];
            return back()->withNotify($notify);
        }

        if ($request->amount > $user->balance) {
            $notify[] = ['error', 'You do not have sufficient balance for withdrawal.'];
            return back()->withNotify($notify);
        }

        $withdraw               = new Withdrawal();
        $withdraw->method_id    = $method->id;
        $withdraw->user_id      = $user->id;
        $withdraw->amount       = $request->amount;
        $withdraw->currency     = $method->currency;
        $withdraw->rate         = $method->rate;
        $withdraw->charge       = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $withdraw->final_amount = ($withdraw->amount - $withdraw->charge) * $withdraw->rate;
        $withdraw->trx          = getTrx();
        $withdraw->status       = Status::PAYMENT_PENDING;
        $withdraw->save();

        $adminNotification            = new \App\Models\AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New withdrawal request from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.data.details', $withdraw->id);
        $adminNotification->save();

        $notify[] = ['success', 'Withdrawal request submitted successfully'];
        return to_route('user.withdraw.log')->withNotify($notify);
    }

    public function withdrawLog(Request $request) {
        $pageTitle = "Withdrawal History";
        $withdraws = Withdrawal::where('user_id', auth()->id())->where('status', '!=', Status::PAYMENT_INITIATE);
        
        if ($request->search) {
            $withdraws = $withdraws->where('trx', $request->search);
        }
        
        $withdraws = $withdraws->with('method')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('frontend.client.withdraw.log', compact('pageTitle', 'withdraws'));
    }
}
