@extends('layouts.app')

@section('content')
<div class="relative min-h-screen bg-slate-50 dark:bg-[#0B0F19] overflow-hidden transition-colors duration-500 py-12 md:py-20">
    
    <div class="relative z-10 px-4 max-w-4xl mx-auto">
        
        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ route('user.plans.purchased') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                <span class="material-symbols-rounded text-[18px]">arrow_back</span>
                Back to Plans
            </a>
        </div>

        <div class="bg-white dark:bg-[#151C2C] rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-2xl shadow-slate-200/50 dark:shadow-none overflow-hidden" id="invoice-content">
            
            <!-- Invoice Header -->
            <div class="p-8 md:p-12 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-orange-500 to-rose-500 flex items-center justify-center shadow-lg shadow-orange-500/30">
                            <span class="material-symbols-rounded text-3xl text-white">receipt_long</span>
                        </div>
                        <div>
                            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">INVOICE</h1>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1">#INV-{{ str_pad($purchasedPlan->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                    
                    <div class="text-left md:text-right">
                        <h2 class="text-lg font-black text-slate-900 dark:text-white">{{ $general->site_name }}</h2>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">{{ url('/') }}</p>
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-2">Date: {{ \Carbon\Carbon::parse($purchasedPlan->created_at)->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="p-8 md:p-12 grid grid-cols-1 md:grid-cols-2 gap-12">
                
                <!-- Billed To -->
                <div>
                    <h3 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Billed To</h3>
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ auth()->user()->fullname }}</h4>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->mobile)
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">{{ auth()->user()->mobile }}</p>
                    @endif
                </div>

                <!-- Transaction Details -->
                <div class="md:text-right">
                    <h3 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Transaction Details</h3>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Status: <span class="text-emerald-500 font-bold">Paid</span></p>
                    @if($purchasedPlan->trx)
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">TRX ID: <span class="font-bold text-slate-700 dark:text-slate-300">{{ $purchasedPlan->trx }}</span></p>
                    @endif
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Payment Method: <span class="font-bold text-slate-700 dark:text-slate-300">Gateway</span></p>
                </div>

            </div>

            <!-- Plan Table -->
            <div class="px-8 md:px-12 pb-8 md:pb-12">
                <div class="bg-slate-50 dark:bg-black/20 rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
                    <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-slate-100 dark:bg-white/5 border-b border-slate-200 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        <div class="col-span-8 md:col-span-9">Description</div>
                        <div class="col-span-4 md:col-span-3 text-right">Amount</div>
                    </div>
                    <div class="grid grid-cols-12 gap-4 px-6 py-6 items-center">
                        <div class="col-span-8 md:col-span-9">
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $purchasedPlan->plan->name ?? 'Premium Plan' }}</h4>
                            @if($purchasedPlan->plan && $purchasedPlan->plan->duration)
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Duration: {{ $purchasedPlan->plan->duration }} Months</p>
                            @else
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Duration: Lifetime Access</p>
                            @endif
                        </div>
                        <div class="col-span-4 md:col-span-3 text-right">
                            <span class="text-xl font-black text-slate-900 dark:text-white">{{ showAmount($purchasedPlan->price) }}</span>
                        </div>
                    </div>
                    
                    <!-- Total -->
                    <div class="px-6 py-6 bg-slate-100 dark:bg-white/5 border-t border-slate-200 dark:border-white/5 flex justify-between items-center">
                        <span class="text-sm font-black text-slate-500 uppercase tracking-widest">Total Paid</span>
                        <span class="text-3xl font-black text-orange-500">{{ showAmount($purchasedPlan->price) }}</span>
                    </div>
                </div>
            </div>

            <!-- Print/Download Actions -->
            <div class="p-8 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex justify-center md:justify-end">
                <button onclick="window.print()" class="px-8 py-3.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl text-xs font-black tracking-widest uppercase shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                    <span class="material-symbols-rounded text-[18px]">print</span>
                    Print Invoice
                </button>
            </div>
            
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #invoice-content, #invoice-content * { visibility: visible; }
    #invoice-content { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; border: none; }
    button { display: none !important; }
}
</style>
@endsection
