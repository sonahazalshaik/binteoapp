<?php

namespace App\Services\Admin;

use App\Constants\Status;
use App\Http\Controllers\Gateway\PaymentController;
use App\Lib\FormProcessor;
use App\Lib\RequiredConfig;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GatewayService
{
    public function authorize(string $ability): void
    {
        abort_if(Gate::denies($ability), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function getAutomaticGateways()
    {
        $allowedAliases = ['StripeV3', 'Razorpay', 'PaypalSdk'];

        return Gateway::automatic()
            ->whereIn('alias', $allowedAliases)
            ->with('currencies')
            ->get();
    }

    public function getAutomaticGatewayForEdit(string $alias): Gateway
    {
        return Gateway::automatic()
            ->with('currencies', 'currencies.method')
            ->where('alias', $alias)
            ->firstOrFail();
    }

    public function updateAutomaticGateway(Request $request, int $code): Gateway
    {
        $gateway = Gateway::where('code', $code)->firstOrFail();
        $this->validateAutomaticGateway($request);
        $this->validateAutomaticGatewayCurrency($request, $gateway);

        $parameters = collect(json_decode($gateway->gateway_parameters));

        foreach ($parameters->where('global', true) as $key => $pram) {
            $parameters[$key]->value = $request->global[$key];
        }

        $filename = $gateway->image;
        if ($request->hasFile('image')) {
            $filename = fileUploader($request->image, getFilePath('gateway'), old: $filename);
        }

        $gateway->alias = $request->alias;
        $gateway->gateway_parameters = json_encode($parameters);
        $gateway->image = $filename;
        $gateway->save();

        if ($request->has('currency')) {
            $gateway->currencies()->delete();

            foreach ($request->currency as $key => $currency) {
                $param = [];
                foreach ($parameters->where('global', true) as $pkey => $pram) {
                    $param[$pkey] = $pram->value;
                }

                foreach ($parameters->where('global', false) as $paramKey => $paramValue) {
                    $param[$paramKey] = $currency['param'][$paramKey];
                }

                $gatewayCurrency = new GatewayCurrency();
                $gatewayCurrency->name = $currency['name'];
                $gatewayCurrency->gateway_alias = $gateway->alias;
                $gatewayCurrency->currency = $currency['currency'];
                $gatewayCurrency->min_amount = $currency['min_amount'];
                $gatewayCurrency->max_amount = $currency['max_amount'];
                $gatewayCurrency->fixed_charge = $currency['fixed_charge'];
                $gatewayCurrency->percent_charge = $currency['percent_charge'];
                $gatewayCurrency->rate = $currency['rate'];
                $gatewayCurrency->symbol = $currency['symbol'];
                $gatewayCurrency->method_code = $code;
                $gatewayCurrency->gateway_parameter = json_encode($param);
                $gatewayCurrency->save();
            }
        }

        RequiredConfig::configured('deposit_method');

        return $gateway;
    }

    public function removeGatewayCurrency(int $currencyId): void
    {
        $gatewayCurrency = GatewayCurrency::findOrFail($currencyId);
        fileManager()->removeFile(getFilePath('gateway') . '/' . $gatewayCurrency->image);
        $gatewayCurrency->delete();
    }

    public function toggleGatewayStatus(int $id): mixed
    {
        return Gateway::changeStatus($id);
    }

    public function getManualGateways()
    {
        return Gateway::manual()->orderBy('id', 'desc')->get();
    }

    public function createManualGateway(Request $request): Gateway
    {
        $formProcessor = new FormProcessor();
        $this->validateManualGateway($request, $formProcessor);

        $lastMethod = Gateway::manual()->orderBy('id', 'desc')->first();
        $methodCode = $lastMethod ? $lastMethod->code + 1 : 1000;

        $generate = $formProcessor->generate('manual_deposit');

        $filename = null;
        if ($request->hasFile('image')) {
            $filename = fileUploader($request->image, getFilePath('gateway'));
        }

        $method = new Gateway();
        $method->code = $methodCode;
        $method->form_id = @$generate->id ?? 0;
        $method->name = $request->name;
        $method->image = $filename;
        $method->alias = strtolower(trim(str_replace(' ', '_', $request->name)));
        $method->status = Status::ENABLE;
        $method->gateway_parameters = json_encode([]);
        $method->supported_currencies = [];
        $method->crypto = Status::DISABLE;
        $method->description = $request->instruction;
        $method->save();

        $gatewayCurrency = new GatewayCurrency();
        $gatewayCurrency->name = $request->name;
        $gatewayCurrency->gateway_alias = strtolower(trim(str_replace(' ', '_', $request->name)));
        $gatewayCurrency->currency = $request->currency;
        $gatewayCurrency->symbol = '';
        $gatewayCurrency->method_code = $methodCode;
        $gatewayCurrency->min_amount = $request->min_limit;
        $gatewayCurrency->max_amount = $request->max_limit;
        $gatewayCurrency->fixed_charge = $request->fixed_charge;
        $gatewayCurrency->percent_charge = $request->percent_charge;
        $gatewayCurrency->rate = $request->rate;
        $gatewayCurrency->save();

        RequiredConfig::configured('deposit_method');

        return $method;
    }

    public function getManualGatewayForEdit(string $alias): Gateway
    {
        return Gateway::manual()->with('singleCurrency')->where('alias', $alias)->firstOrFail();
    }

    public function updateManualGateway(Request $request, int $code): Gateway
    {
        $formProcessor = new FormProcessor();
        $this->validateManualGateway($request, $formProcessor, true);

        $method = Gateway::manual()->where('code', $code)->firstOrFail();

        $filename = $method->image;
        if ($request->hasFile('image')) {
            $filename = fileUploader($request->image, getFilePath('gateway'), old: $filename);
        }

        $generate = $formProcessor->generate('manual_deposit', true, 'id', $method->form_id);
        $method->name = $request->name;
        $method->image = $filename;
        $method->alias = strtolower(trim(str_replace(' ', '_', $request->name)));
        $method->gateway_parameters = json_encode([]);
        $method->supported_currencies = [];
        $method->crypto = Status::DISABLE;
        $method->description = $request->instruction;
        $method->form_id = @$generate->id ?? 0;
        $method->save();

        $singleCurrency = $method->singleCurrency;
        if ($singleCurrency) {
            $singleCurrency->name = $request->name;
            $singleCurrency->gateway_alias = strtolower(trim(str_replace(' ', '_', $method->name)));
            $singleCurrency->currency = $request->currency;
            $singleCurrency->symbol = '';
            $singleCurrency->min_amount = $request->min_limit;
            $singleCurrency->max_amount = $request->max_limit;
            $singleCurrency->fixed_charge = $request->fixed_charge;
            $singleCurrency->percent_charge = $request->percent_charge;
            $singleCurrency->rate = $request->rate;
            $singleCurrency->save();
        }

        return $method;
    }

    public function getDeposits(?string $scope = null, bool $withSummary = false, ?int $userId = null): mixed
    {
        $deposits = $scope ? Deposit::$scope()->with(['user', 'gateway', 'marketplace']) : Deposit::with(['user', 'gateway', 'marketplace']);

        if ($userId) {
            $deposits = $deposits->where('user_id', $userId);
        }

        $deposits = $deposits->searchable(['trx', 'user:username'])->dateFilter();

        $request = request();
        if ($request->method) {
            if ($request->method != Status::GOOGLE_PAY) {
                $method = Gateway::where('alias', $request->method)->firstOrFail();
                $deposits = $deposits->where('method_code', $method->code);
            } else {
                $deposits = $deposits->where('method_code', Status::GOOGLE_PAY);
            }
        }

        if (!$withSummary) {
            return $deposits->orderBy('id', 'desc')->paginate(getPaginate());
        }

        $successful = clone $deposits;
        $pending = clone $deposits;
        $rejected = clone $deposits;
        $initiated = clone $deposits;

        return [
            'data' => $deposits->orderBy('id', 'desc')->paginate(getPaginate()),
            'summary' => [
                'successful' => $successful->where('status', Status::PAYMENT_SUCCESS)->sum('amount'),
                'pending' => $pending->where('status', Status::PAYMENT_PENDING)->sum('amount'),
                'rejected' => $rejected->where('status', Status::PAYMENT_REJECT)->sum('amount'),
                'initiated' => $initiated->where('status', Status::PAYMENT_INITIATE)->sum('amount'),
            ],
        ];
    }

    public function getDepositDetails(int $id): Deposit
    {
        return Deposit::where('id', $id)->with(['user', 'gateway', 'marketplace'])->firstOrFail();
    }

    public function approveDeposit(int $id): void
    {
        $deposit = Deposit::where('id', $id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();
        PaymentController::userDataUpdate($deposit, true);
    }

    public function rejectDeposit(int $id, string $message): void
    {
        $deposit = Deposit::where('id', $id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();
        $deposit->admin_feedback = $message;
        $deposit->status = Status::PAYMENT_REJECT;
        $deposit->save();

        if ($deposit->advertisement_id) {
            $advertisement = $deposit->advertisement;
            $advertisement->status = Status::PAUSE;
            $advertisement->payment_status = Status::PAYMENT_REJECT;
            $advertisement->save();
        } elseif ($deposit->campaign_id) {
            $campaign = $deposit->campaign;
            $campaign->status = Status::REJECTED;
            $campaign->payment_status = Status::PAYMENT_REJECT;
            $campaign->save();
        }
    }

    public function deleteDeposit(int $id): void
    {
        Deposit::findOrFail($id)->delete();
    }

    public function getWithdrawals(?string $scope = null, bool $withSummary = false, ?int $userId = null): mixed
    {
        $withdrawals = $scope
            ? Withdrawal::$scope()
            : Withdrawal::where('status', '!=', Status::PAYMENT_INITIATE);

        if ($userId) {
            $withdrawals = $withdrawals->where('user_id', $userId);
        }

        $withdrawals = $withdrawals->searchable(['trx', 'user:username'])->dateFilter();

        $request = request();
        if ($request->method) {
            $withdrawals = $withdrawals->where('method_id', $request->method);
        }

        if (!$withSummary) {
            return $withdrawals->with(['user', 'method'])->orderBy('id', 'desc')->paginate(getPaginate());
        }

        $successful = clone $withdrawals;
        $pending = clone $withdrawals;
        $rejected = clone $withdrawals;

        return [
            'data' => $withdrawals->with(['user', 'method'])->orderBy('id', 'desc')->paginate(getPaginate()),
            'summary' => [
                'successful' => $successful->where('status', Status::PAYMENT_SUCCESS)->sum('amount'),
                'pending' => $pending->where('status', Status::PAYMENT_PENDING)->sum('amount'),
                'rejected' => $rejected->where('status', Status::PAYMENT_REJECT)->sum('amount'),
            ],
        ];
    }

    public function createManualWithdrawal(array $data): Withdrawal
    {
        $user = User::findOrFail($data['user_id']);
        $method = WithdrawMethod::findOrFail($data['method_id']);

        if ($user->balance < $data['amount']) {
            throw new \RuntimeException('User does not have sufficient balance.');
        }

        $charge = $method->fixed_charge + ($data['amount'] * $method->percent_charge / 100);

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id;
        $withdraw->user_id = $user->id;
        $withdraw->amount = $data['amount'];
        $withdraw->currency = $method->currency;
        $withdraw->rate = $method->rate;
        $withdraw->charge = $charge;
        $withdraw->final_amount = ($data['amount'] - $charge) * $method->rate;
        $withdraw->after_charge = $data['amount'] - $charge;
        $withdraw->trx = getTrx();
        $withdraw->status = Status::PAYMENT_SUCCESS;
        $withdraw->admin_feedback = $data['details'] ?? null;
        $withdraw->save();

        $user->balance -= $data['amount'];
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'amount' => $data['amount'],
            'post_balance' => $user->balance,
            'charge' => $charge,
            'trx_type' => '-',
            'remark' => 'withdrawal',
            'details' => 'Manual Withdrawal via ' . $method->name,
            'trx' => $withdraw->trx,
        ]);

        return $withdraw;
    }

    public function getWithdrawalDetails(int $id): Withdrawal
    {
        return Withdrawal::where('id', $id)->where('status', '!=', Status::PAYMENT_INITIATE)
            ->with(['user', 'method'])->firstOrFail();
    }

    public function approveWithdrawal(int $id, ?string $feedback = null): void
    {
        $withdraw = Withdrawal::where('id', $id)->where('status', Status::PAYMENT_PENDING)
            ->with('user')->firstOrFail();

        $user = $withdraw->user;
        if ($user->balance < $withdraw->amount) {
            throw new \RuntimeException('User does not have sufficient balance.');
        }

        $withdraw->status = Status::PAYMENT_SUCCESS;
        $withdraw->admin_feedback = $feedback;
        $withdraw->save();

        $user->balance -= $withdraw->amount;
        $user->save();

        Transaction::create([
            'user_id' => $withdraw->user_id,
            'amount' => $withdraw->amount,
            'post_balance' => $user->balance,
            'charge' => $withdraw->charge,
            'trx_type' => '-',
            'remark' => 'withdrawal',
            'details' => 'Withdrawal via ' . $withdraw->method->name,
            'trx' => $withdraw->trx,
        ]);
    }

    public function rejectWithdrawal(int $id, ?string $feedback = null): void
    {
        $withdraw = Withdrawal::where('id', $id)->where('status', Status::PAYMENT_PENDING)
            ->with('user')->firstOrFail();

        $withdraw->status = Status::PAYMENT_REJECT;
        $withdraw->admin_feedback = $feedback;
        $withdraw->save();
    }

    public function getWithdrawMethods()
    {
        return WithdrawMethod::orderBy('name')->orderBy('id')->get();
    }

    public function createWithdrawMethod(Request $request): WithdrawMethod
    {
        $formProcessor = new FormProcessor();
        $this->validateWithdrawMethod($request, $formProcessor);

        $generate = $formProcessor->generate('withdraw_method');

        $filename = null;
        if ($request->hasFile('image')) {
            $filename = \App\Helpers\ImageHelper::uploadToR2($request->image, 'withdraw_methods');
        }

        $method = new WithdrawMethod();
        $method->name = $request->name;
        $method->image = $filename;
        $method->form_id = @$generate->id ?? 0;
        $method->rate = $request->rate;
        $method->min_limit = $request->min_limit;
        $method->max_limit = $request->max_limit;
        $method->fixed_charge = $request->fixed_charge;
        $method->percent_charge = $request->percent_charge;
        $method->currency = $request->currency;
        $method->description = $request->instruction;
        $method->schedule_type = $request->schedule_type;
        $method->schedule = $request->schedule;
        $method->save();

        RequiredConfig::configured('withdrawal_method');

        return $method;
    }

    public function getWithdrawMethodForEdit(int $id): WithdrawMethod
    {
        return WithdrawMethod::with('form')->findOrFail($id);
    }

    public function updateWithdrawMethod(Request $request, int $id): WithdrawMethod
    {
        $formProcessor = new FormProcessor();
        $validation = [
            'name' => 'required',
            'rate' => 'required|numeric|gt:0',
            'image' => ['nullable', 'image', new \App\Rules\FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'fixed_charge' => 'required|numeric|gte:0',
            'min_limit' => 'required|numeric|gt:fixed_charge',
            'max_limit' => 'required|numeric|gt:min_limit',
            'percent_charge' => 'required|numeric|between:0,100',
            'currency' => 'required',
            'instruction' => 'required',
            'schedule_type' => 'required|in:daily,weekly,monthly',
            'schedule' => $request->schedule_type != 'daily' ? 'required' : 'nullable',
        ];

        $generatorValidation = $formProcessor->generatorValidation();
        $validation = array_merge($validation, $generatorValidation['rules']);
        $request->validate($validation, $generatorValidation['messages']);

        $method = WithdrawMethod::findOrFail($id);

        $filename = $method->image;

        if ($request->boolean('should_remove_image') && $filename) {
            try {
                \App\Helpers\ImageHelper::deleteImage($filename);
            } catch (\Exception $e) {
            }
            $filename = null;
        }

        if ($request->hasFile('image')) {
            if ($filename) {
                try {
                    \App\Helpers\ImageHelper::deleteImage($filename);
                } catch (\Exception $e) {
                }
            }
            $filename = \App\Helpers\ImageHelper::uploadToR2($request->image, 'withdraw_methods');
        }

        $generate = $formProcessor->generate('withdraw_method', true, 'id', $method->form_id);
        $method->form_id = @$generate->id ?? 0;
        $method->name = $request->name;
        $method->image = $filename;
        $method->rate = $request->rate;
        $method->min_limit = $request->min_limit;
        $method->max_limit = $request->max_limit;
        $method->fixed_charge = $request->fixed_charge;
        $method->percent_charge = $request->percent_charge;
        $method->description = $request->instruction;
        $method->currency = $request->currency;
        $method->schedule_type = $request->schedule_type;
        $method->schedule = $request->schedule;
        $method->save();

        return $method;
    }

    public function toggleWithdrawMethodStatus(int $id): mixed
    {
        return WithdrawMethod::changeStatus($id);
    }

    public function deleteWithdrawMethod(int $id): void
    {
        $method = WithdrawMethod::findOrFail($id);
        if ($method->image) {
            try {
                \App\Helpers\ImageHelper::deleteImage($method->image);
            } catch (\Exception $e) {
            }
        }
        $method->delete();
    }

    protected function validateAutomaticGateway(Request $request): void
    {
        $validationRule = [
            'alias' => 'required',
            'image' => ['nullable', 'image', new \App\Rules\FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ];
        Validator::make($request->all(), $validationRule)->validate();
    }

    protected function validateAutomaticGatewayCurrency(Request $request, Gateway $gateway): void
    {
        $customAttributes = [];
        $validationRule = [];

        $paramList = collect(json_decode($gateway->gateway_parameters));
        $supportedCurrencies = collect($gateway->supported_currencies)->flip()->implode(',');

        foreach ($paramList->where('global', true) as $key => $pram) {
            $validationRule['global.' . $key] = 'required';
            $customAttributes['global.' . $key] = keyToTitle($key);
        }

        if ($request->has('currency')) {
            foreach ($request->currency as $key => $currency) {
                $validationRule['currency.' . $key . '.currency'] = 'required|string|in:' . $supportedCurrencies;
                $validationRule['currency.' . $key . '.symbol'] = 'required|string';
                $validationRule['currency.' . $key . '.name'] = 'required';
                $validationRule['currency.' . $key . '.min_amount'] = 'required|numeric|gt:0|lte:currency.' . $key . '.max_amount';
                $validationRule['currency.' . $key . '.max_amount'] = 'required|numeric|gt:0|gte:currency.' . $key . '.min_amount';
                $validationRule['currency.' . $key . '.fixed_charge'] = 'required|numeric|gte:0';
                $validationRule['currency.' . $key . '.percent_charge'] = 'required|numeric|gte:0|max:100';
                $validationRule['currency.' . $key . '.rate'] = 'required|numeric|gt:0';

                $supportedCurrencies = explode(',', $supportedCurrencies);
                $supportedCurrencies = collect(removeElement($supportedCurrencies, $currency['currency']))->implode(',');

                $currencyIdentifier = $this->currencyIdentifier($currency['name'], $gateway->name . ' ' . $currency['currency']);
                $customAttributes['currency.' . $key . '.name'] = $currencyIdentifier . ' name';
                $customAttributes['currency.' . $key . '.min_amount'] = $currencyIdentifier . ' ' . keyToTitle('min_amount');
                $customAttributes['currency.' . $key . '.max_amount'] = $currencyIdentifier . ' ' . keyToTitle('max_amount');
                $customAttributes['currency.' . $key . '.fixed_charge'] = $currencyIdentifier . ' ' . keyToTitle('fixed_charge');
                $customAttributes['currency.' . $key . '.percent_charge'] = $currencyIdentifier . ' ' . keyToTitle('percent_charge');
                $customAttributes['currency.' . $key . '.rate'] = $currencyIdentifier . ' ' . keyToTitle('rate');
                $customAttributes['currency.' . $key . '.currency'] = $currencyIdentifier . ' ' . keyToTitle('currency');
                $customAttributes['currency.' . $key . '.symbol'] = $currencyIdentifier . ' ' . keyToTitle('symbol');

                foreach ($paramList->where('global', false) as $param_key => $param_value) {
                    $validationRule['currency.' . $key . '.param.' . $param_key] = 'required';
                    $customAttributes['currency.' . $key . '.param.' . $param_key] = $currencyIdentifier . ' ' . keyToTitle($param_value->title);
                }
            }
        }

        Validator::make($request->all(), $validationRule, $customAttributes)->validate();
    }

    protected function validateManualGateway(Request $request, FormProcessor $formProcessor, bool $isUpdate = false): void
    {
        $validation = [
            'name' => 'required',
            'rate' => 'required|numeric|gt:0',
            'currency' => 'required',
            'min_limit' => 'required|numeric|gt:0',
            'max_limit' => 'required|numeric|gt:min_limit',
            'fixed_charge' => 'required|numeric|gte:0',
            'percent_charge' => 'required|numeric|between:0,100',
            'image' => [$isUpdate ? 'nullable' : 'required', 'image', new \App\Rules\FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'instruction' => 'required',
        ];

        $generatorValidation = $formProcessor->generatorValidation();
        $validation = array_merge($validation, $generatorValidation['rules']);
        $request->validate($validation, $generatorValidation['messages']);
    }

    protected function validateWithdrawMethod(Request $request, FormProcessor $formProcessor): void
    {
        $validation = [
            'name' => 'required',
            'rate' => 'required|numeric|gt:0',
            'currency' => 'required',
            'fixed_charge' => 'required|numeric|gte:0',
            'percent_charge' => 'required|numeric|between:0,100',
            'min_limit' => 'required|numeric|gt:fixed_charge',
            'max_limit' => 'required|numeric|gt:min_limit',
            'image' => ['required', 'image', new \App\Rules\FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'instruction' => 'required',
            'schedule_type' => 'required|in:daily,weekly,monthly',
            'schedule' => $request->schedule_type != 'daily' ? 'required' : 'nullable',
        ];

        $generatorValidation = $formProcessor->generatorValidation();
        $validation = array_merge($validation, $generatorValidation['rules']);
        $request->validate($validation, $generatorValidation['messages']);
    }

    private function currencyIdentifier($name, $default = ''): string
    {
        return $name ?? $default;
    }
}
