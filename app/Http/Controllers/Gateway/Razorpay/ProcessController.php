<?php

namespace App\Http\Controllers\Gateway\Razorpay;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class ProcessController extends Controller
{
    /*
     * RazorPay Gateway Logic
     */

    public static function process($deposit)
    {
        $gateway = $deposit->gateway;
        $params = $gateway->gateway_parameter;
        
        $apiKey = $params->key_id ?? "";
        $apiSecret = $params->key_secret ?? "";

        try {
            $api = new Api($apiKey, $apiSecret);
            \Log::channel('razorpay')->info('GATEWAY | Order creation requested', [
                'user_id'    => auth()->id(),
                'trx'        => $deposit->trx,
                'amount'     => $deposit->final_amount,
                'key_prefix' => substr((string) $apiKey, 0, 12),
            ]);
            $order = $api->order->create([
                'receipt'         => $deposit->trx,
                'amount'          => round($deposit->final_amount * 100),
                'currency'        => $deposit->method_currency,
                'payment_capture' => '1',
            ]);
            \Log::channel('razorpay')->info('GATEWAY | Order created successfully on Razorpay', [
                'order_id' => $order->id,
                'trx'      => $deposit->trx,
                'status'   => $order->status ?? null,
            ]);
        } catch (\Exception $e) {
            \Log::channel('razorpay')->error('GATEWAY | Order creation FAILED on Razorpay', [
                'trx'   => $deposit->trx ?? null,
                'error' => $e->getMessage(),
            ]);
            $send['error']   = true;
            $send['message'] = $e->getMessage();
            return json_encode($send);
        }

        $deposit->btc_wallet = $order->id;
        $deposit->save();

        $val['key']             = $apiKey;
        $val['amount']          = round($deposit->final_amount * 100);
        $val['currency']        = $deposit->method_currency;
        $val['order_id']        = $order['id'];
        $val['buttontext']      = "Pay with Razorpay";
        $val['name']            = auth()->user()->username ?? "Marketplace Upgrade";
        $val['description']     = "Payment By Razorpay";
        $val['image']           = siteLogo();
        $val['prefill.name']    = auth()->user() ? trim(auth()->user()->firstname . ' ' . auth()->user()->lastname) : "Guest User";
        $val['prefill.email']   = auth()->user()->email ?? "";
        $val['prefill.contact'] = auth()->user()->mobile ?? "";
        $val['theme.color']     = "#2ecc71";
        $send['val']            = $val;

        $send['method'] = 'POST';

        $alias = $deposit->gateway->alias;

        $send['url']         = route('ipn.' . $alias) . '?order_id=' . encrypt($deposit->btc_wallet);
        $send['custom']      = $deposit->trx;
        $send['checkout_js'] = "https://checkout.razorpay.com/v1/checkout.js";
        $send['view']        = 'frontend.payment.' . strtolower($alias);

        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $orderId  = decrypt($request->order_id);
        $deposit  = Deposit::where('btc_wallet', $orderId)->orderBy('id', 'DESC')->first();
        if (!$deposit) {
            $notify[] = ['error', 'Invalid Session Tracking ID.'];
            return back()->withNotify($notify);
        }

        $apiSecret = $deposit->gateway->gateway_parameter->key_secret ?? "";

        $api = new Api($deposit->gateway->gateway_parameter->key_id ?? "", $apiSecret);
        $sig = hash_hmac('sha256', $orderId . "|" . $request->razorpay_payment_id, $apiSecret);
        $deposit->detail = $request->all();
        $deposit->save();

        if ($sig == $request->razorpay_signature && $deposit->status == 0) {
            try {
                \Log::channel('razorpay')->info('GATEWAY | IPN callback, fetching payment', [
                    'trx'        => $deposit->trx,
                    'payment_id' => $request->razorpay_payment_id,
                ]);
                $payment = $api->payment->fetch($request->razorpay_payment_id);
                \Log::channel('razorpay')->info('GATEWAY | Payment fetched from Razorpay API', [
                    'trx'        => $deposit->trx,
                    'status'     => $payment->status,
                    'method'     => $payment->method ?? null,
                    'amount'     => $payment->amount,
                    'error_desc' => $payment->error_description ?? null,
                ]);
                if ($payment->status !== 'captured') {
                    $payment->capture(['amount' => $payment->amount]);
                }
            } catch (\Exception $e) {
                \Log::channel('razorpay')->error('GATEWAY | Capture Failed', [
                    'trx'   => $deposit->trx,
                    'error' => $e->getMessage(),
                ]);
                \Log::error('Razorpay Capture Failed: ' . $e->getMessage());
                $notify[] = ['error', 'Payment capture failed.'];
                return back()->withNotify($notify);
            }
            PaymentController::userDataUpdate($deposit);
            $notify[] = ['success', 'Transaction authenticated successfully.'];
            return redirect($deposit->success_url)->withNotify($notify);
        } else {
            $notify[] = ['error', "Authentication failed or session expired."];
            return back()->withNotify($notify);
        }
    }
}
