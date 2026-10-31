<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Services\Admin\GatewayService;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    protected $service;

    public function __construct(GatewayService $service)
    {
        $this->service = $service;
    }

    public function pending($userId = null)
    {
        $pageTitle = 'Pending Withdrawals';
        $withdrawals = $this->withdrawalData('pending',userId:$userId);
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    public function approved($userId = null)
    {
        $pageTitle = 'Approved Withdrawals';
        $withdrawals = $this->withdrawalData('approved',userId:$userId);
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    public function rejected($userId = null)
    {
        $pageTitle = 'Rejected Withdrawals';
        $withdrawals = $this->withdrawalData('rejected',userId:$userId);
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    public function all($userId = null)
    {
        $pageTitle = 'All Withdrawals';
        $withdrawalData = $this->withdrawalData($scope = null, $summary = true,userId:$userId);
        $withdrawals = $withdrawalData['data'];
        $summary = $withdrawalData['summary'];
        $successful = $summary['successful'];
        $pending = $summary['pending'];
        $rejected = $summary['rejected'];


        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals','successful','pending','rejected'));
    }

    public function create()
    {
        $pageTitle = 'Add Manual Withdrawal';
        $users = \App\Models\User::active()->orderBy('username')->get();
        $methods = \App\Models\WithdrawMethod::active()->orderBy('name')->get();
        return view('admin.withdraw.create_manual', compact('pageTitle', 'users', 'methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'method_id' => 'required|exists:withdraw_methods,id',
            'amount' => 'required|numeric|gt:0',
            'details' => 'nullable|string',
        ]);

        try {
            $this->service->createManualWithdrawal($request->all());
        } catch (\RuntimeException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withInput()->withNotify($notify);
        }

        $notify[] = ['success', 'Manual withdrawal created successfully'];
        return to_route('admin.withdraw.data.all')->withNotify($notify);
    }

    protected function withdrawalData($scope = null, $summary = false,$userId = null){
        return $this->service->getWithdrawals($scope, $summary, $userId);
    }

    public function destroy($id)
    {
        $withdraw = Withdrawal::where('id', $id)->first();

        if (!$withdraw) {
            $notify[] = ['error', 'Withdrawal record not found'];
            return back()->withNotify($notify);
        }

        Transaction::where('trx', $withdraw->trx)->where('user_id', $withdraw->user_id)->delete();
        $withdraw->delete();

        $notify[] = ['success', 'Withdrawal record deleted successfully'];
        return back()->withNotify($notify);
    }

    public function details($id)
    {
        $withdrawal = $this->service->getWithdrawalDetails($id);
        $pageTitle = 'Withdrawal Details';
        $details = $withdrawal->withdraw_information ? json_encode($withdrawal->withdraw_information) : null;

        return view('admin.withdraw.detail', compact('pageTitle', 'withdrawal','details'));
    }

    public function approve(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        try {
            $this->service->approveWithdrawal($request->id, $request->details);
        } catch (\RuntimeException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }

        $withdraw = $this->service->getWithdrawalDetails($request->id);

        notify($withdraw->user, 'WITHDRAW_APPROVE', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => showAmount($withdraw->final_amount,currencyFormat:false),
            'amount' => showAmount($withdraw->amount,currencyFormat:false),
            'charge' => showAmount($withdraw->charge,currencyFormat:false),
            'rate' => showAmount($withdraw->rate,currencyFormat:false),
            'trx' => $withdraw->trx,
            'admin_details' => $request->details
        ]);

        $notify[] = ['success', 'Withdrawal approved successfully'];
        return to_route('admin.withdraw.data.pending')->withNotify($notify);
    }


    public function reject(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        $this->service->rejectWithdrawal($request->id, $request->details);
        $withdraw = $this->service->getWithdrawalDetails($request->id);

        notify($withdraw->user, 'WITHDRAW_REJECT', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => showAmount($withdraw->final_amount,currencyFormat:false),
            'amount' => showAmount($withdraw->amount,currencyFormat:false),
            'charge' => showAmount($withdraw->charge,currencyFormat:false),
            'rate' => showAmount($withdraw->rate,currencyFormat:false),
            'trx' => $withdraw->trx,
            'post_balance' => showAmount($withdraw->user->balance,currencyFormat:false),
            'admin_details' => $request->details
        ]);

        $notify[] = ['success', 'Withdrawal rejected successfully'];
        return to_route('admin.withdraw.data.pending')->withNotify($notify);
    }

}
