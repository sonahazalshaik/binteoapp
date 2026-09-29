@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#0A0A0A] py-20 px-6 transition-colors duration-500">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $pageTitle }}</h1>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-2">Choose a secure payment provider to complete your transaction</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($gatewayCurrency as $data)
            <div class="bg-white dark:bg-[#151515] border border-slate-100 dark:border-white/5 p-10 rounded-[3rem] shadow-sm hover:shadow-2xl transition-all group">
                <div class="text-center">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-red-50 dark:group-hover:bg-red-600/10 transition-all">
                        <span class="material-symbols-rounded text-3xl text-slate-400 group-hover:text-red-600">account_balance_wallet</span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight mb-2">{{ __($data->name) }}</h3>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-8">Secured Gateway</p>
                    
                    <div class="py-6 px-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5 mb-10">
                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Transaction Limits</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ showAmount($data->min_amount) }} - {{ showAmount($data->max_amount) }} {{ __($data->currency) }}</p>
                    </div>

                    <form action="{{ route('user.deposit.insert') }}" method="POST">
                        @csrf
                        <input type="hidden" name="gateway" value="{{ $data->method_code }}">
                        <input type="hidden" name="currency" value="{{ $data->currency }}">
                        <input type="hidden" name="plan_id" value="{{ request()->plan_id }}">
                        <input type="hidden" name="amount" value="{{ request()->amount }}">
                        
                        <button type="submit" class="w-full py-4 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white rounded-2xl font-bold text-sm hover:bg-red-600 hover:text-white hover:border-transparent transition-all shadow-sm active:scale-95">
                            Select Provider
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-20 text-center opacity-30">
            <div class="flex items-center justify-center gap-6 text-slate-400">
                <span class="material-symbols-rounded text-4xl">verified_user</span>
                <span class="material-symbols-rounded text-4xl">lock</span>
                <span class="material-symbols-rounded text-4xl">shield</span>
            </div>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] mt-6">Secure and Encrypted Payment Standards</p>
        </div>
    </div>
</div>
@endsection

