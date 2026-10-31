@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-6 py-20 bg-slate-50 dark:bg-[#0A0A0A] transition-colors duration-500">
    <div class="w-full max-w-[480px] animate-in zoom-in duration-700">
        <!-- M3 Surface Card -->
        <div class="bg-white dark:bg-[#151515] p-10 md:p-14 rounded-[3.5rem] border border-slate-100 dark:border-white/5 shadow-2xl relative overflow-hidden text-center">
            <div class="absolute -top-16 -left-16 w-32 h-32 bg-red-600/5 dark:bg-red-600/10 rounded-full blur-[60px]"></div>
            
            <div class="w-16 h-16 bg-red-50 dark:bg-red-600/10 rounded-2xl flex items-center justify-center mx-auto mb-8">
                <span class="material-symbols-rounded text-3xl text-red-600">payments</span>
            </div>

            <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $pageTitle }}</h1>
            <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.1em] mt-2 mb-10">Subscription Details</p>
            
            <!-- Pricing Surface -->
            <div class="p-10 bg-slate-50 dark:bg-white/5 rounded-[2.5rem] border border-slate-200 dark:border-white/10 mb-10">
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Selected Plan</p>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $plan->plan_name }}</h2>
                
                <div class="mt-8 pt-8 border-t border-slate-200 dark:border-white/10">
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Total Amount</p>
                    <div class="flex flex-col items-center">
                        <div class="flex items-baseline gap-2">
                            <span class="text-5xl font-black text-slate-900 dark:text-white">₹{{ number_format($plan->plan_price, 2) }}</span>
                            <span class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase">INR</span>
                        </div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-2">Duration: {{ $plan->plan_duration }} Days</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('marketplace.payment.confirm') }}" method="POST">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <button type="submit" class="w-full py-6 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-full font-bold text-sm tracking-tight shadow-3xl shadow-slate-200 dark:shadow-none hover:opacity-90 active:scale-95 transition-all">
                    Proceed to Payment
                </button>
            </form>
            
            <p class="mt-10 text-[10px] font-bold text-slate-400 dark:text-slate-500 leading-relaxed px-6 uppercase tracking-tight">
                Secure payment processing. By proceeding, you agree to our terms of service and subscription policy.
            </p>
        </div>
    </div>
</div>
@endsection

