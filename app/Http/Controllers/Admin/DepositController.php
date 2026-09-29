<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Services\Admin\GatewayService;
use Illuminate\Http\Request;

class DepositController extends Controller {
    protected $service;

    public function __construct(GatewayService $service)
    {
        $this->service = $service;
    }
    public function pending($userId = null) {
        $pageTitle = 'Pending Payments';
        $deposits  = $this->depositData('pending', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function approved($userId = null) {
        $pageTitle = 'Approved Payments';
        $deposits  = $this->depositData('approved', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function successful($userId = null) {
        $pageTitle = 'Successful Payments';
        $deposits  = $this->depositData('successful', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function rejected($userId = null) {
        $pageTitle = 'Rejected Payments';
        $deposits  = $this->depositData('rejected', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function initiated($userId = null) {
        $pageTitle = 'Initiated Payments';
        $deposits  = $this->depositData('initiated', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function deposit($userId = null) {
        $pageTitle   = 'Payment History';
        $depositData = $this->depositData($scope = null, $summary = true, userId: $userId);
        $deposits    = $depositData['data'];
        $summary     = $depositData['summary'];
        $successful  = $summary['successful'];
        $pending     = $summary['pending'];
        $rejected    = $summary['rejected'];
        $initiated   = $summary['initiated'];
        return view('admin.deposit.log', compact('pageTitle', 'deposits', 'successful', 'pending', 'rejected', 'initiated'));
    }

    protected function depositData($scope = null, $summary = false, $userId = null) {
        return $this->service->getDeposits($scope, $summary, $userId);
    }

    public function details($id) {
        $deposit   = $this->service->getDepositDetails($id);
        $username  = $deposit->user ? $deposit->user->username : ($deposit->marketplace ? $deposit->marketplace->name : 'Deleted User');
        $pageTitle = $username . ' requested ' . showAmount($deposit->amount);
        $detailData = $deposit->detail;
        if (is_string($detailData)) {
            $detailData = json_decode($detailData);
        }
        $details   = ($detailData != null) ? json_encode($detailData) : null;
        return view('admin.deposit.detail', compact('pageTitle', 'deposit', 'details'));
    }

    public function approve($id) {
        $this->service->approveDeposit($id);

        $notify[] = ['success', 'Payment request approved successfully'];

        return to_route('admin.deposit.pending')->withNotify($notify);
    }

    public function reject(Request $request) {
        $request->validate([
            'id'      => 'required|integer',
            'message' => 'required|string|max:255',
        ]);

        $deposit = $this->service->getDepositDetails($request->id);
        $this->service->rejectDeposit($request->id, $request->message);

        if ($deposit->user) {
            notify($deposit->user, 'DEPOSIT_REJECT', [
                'method_name'       => $deposit->methodName(),
                'method_currency'   => $deposit->method_currency,
                'method_amount'     => showAmount($deposit->final_amount, currencyFormat: false),
                'amount'            => showAmount($deposit->amount, currencyFormat: false),
                'charge'            => showAmount($deposit->charge, currencyFormat: false),
                'rate'              => showAmount($deposit->rate, currencyFormat: false),
                'trx'               => $deposit->trx,
                'rejection_message' => $request->message,
            ]);
        }

        $notify[] = ['success', 'Payment request rejected successfully'];
        return to_route('admin.deposit.pending')->withNotify($notify);
    }

    public function destroy($id)
    {
        $this->service->deleteDeposit($id);

        $notify[] = ['success', 'Payment record deleted successfully'];
        return back()->withNotify($notify);
    }
}
