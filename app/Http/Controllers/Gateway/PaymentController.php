<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\MarketSubscription;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function deposit()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('gateway', function ($gate) {
            $gate->where('status', 1);
        })->with('gateway')->orderby('method_code')->get();
        $pageTitle = 'Initiate Payment Protocol';
        return view('frontend.payment.deposit', compact('gatewayCurrency', 'pageTitle'));
    }

    public function depositInsert(Request $request)
    {
        $request->validate([
            'amount'   => 'required|numeric|gt:0',
            'gateway'  => 'required',
            'currency' => 'required',
            'plan_id'  => 'required|exists:user_plans,id',
        ]);

        $gate = GatewayCurrency::whereHas('gateway', function ($gate) {
            $gate->where('status', 1);
        })->where('method_code', $request->gateway)->where('currency', $request->currency)->first();

        if (!$gate) {
            $notify[] = ['error', 'Invalid Gateway Protocol.'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Amount out of operational bounds.'];
            return back()->withNotify($notify);
        }

        $charge    = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable   = $request->amount + $charge;
        $final_amo = $payable * $gate->rate;

        $data = new Deposit();
        $data->marketplace_id  = Session::get('marketplace_user_id');
        $data->plan_id         = $request->plan_id;
        $data->method_code     = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount          = $request->amount;
        $data->charge          = $charge;
        $data->rate            = $gate->rate;
        $data->final_amount    = $final_amo;
        $data->btc_wallet      = "";
        $data->trx             = getTrx();
        $data->status          = 0;
        $data->success_url     = route('marketplace.dashboard');
        $data->failed_url      = route('marketplace.dashboard');
        $data->save();

        session()->put('Track', $data->trx);
        return redirect()->route('user.deposit.confirm');
    }

    public function depositConfirm()
    {
        $track = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', 0)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return redirect()->route('user.deposit.manual.confirm');
        }

        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);


        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return back()->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        $pageTitle = 'Secure Checkout Confirmation';
        return view($data->view, compact('data', 'deposit', 'pageTitle'));
    }

    public static function userDataUpdate($deposit)
    {
        if ($deposit->status == 0) {
            $deposit->status = 1;
            $deposit->save();

            // Update Marketplace Subscription
            $marketplace = $deposit->marketplace;
            $plan = $deposit->plan;

            MarketSubscription::create([
                'marketplace_id' => $marketplace->id,
                'plan_id'        => $plan->id,
                'plan_name'      => $plan->plan_name,
                'plan_price'     => $plan->plan_price,
                'plan_duration'  => $plan->plan_duration,
                'start_date'     => now(),
                'end_date'       => now()->addDays($plan->plan_duration),
            ]);

            // Automatically activate Featured Profile if plan permits
            if ($plan->is_featured_plan) {
                $marketplace->update([
                    'is_featured'    => true,
                    'featured_until' => now()->addDays($plan->plan_duration)
                ]);
            }
        }
    }
    public function depositHistory()
    {
        $pageTitle = 'Deposit History';
        $deposits = auth()->user()->deposits()->with('gateway')->orderBy('id','desc')->paginate(getPaginate());
        return view('frontend.client.deposit.history', compact('pageTitle', 'deposits'));
    }
}
