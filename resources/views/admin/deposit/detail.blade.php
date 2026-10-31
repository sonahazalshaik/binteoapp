@extends('admin.layouts.app')
@section('panel')
    <div class="max-w-6xl mx-auto pb-16 animate-in fade-in slide-in-from-bottom-4 duration-500">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Transaction Details</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-white/40 uppercase tracking-widest mt-1">Reference: <span class="text-orange-500">{{ $deposit->trx }}</span></p>
            </div>
            <div>
                @php echo $deposit->statusBadge @endphp
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Amount & Overview -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Main Receipt Card -->
                <div class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-black dark:from-black dark:via-[#121212] dark:to-black rounded-3xl p-6 overflow-hidden shadow-xl border border-slate-800 dark:border-white/5 text-white group">
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 group-hover:opacity-10 transition-opacity duration-700"></div>
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-500 rounded-full blur-[80px] opacity-20"></div>
                    <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-500 rounded-full blur-[80px] opacity-10"></div>
                    
                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-2xl bg-white/5 backdrop-blur-xl border border-white/10 flex items-center justify-center mb-4 shadow-inner text-emerald-400">
                            <span class="material-symbols-rounded text-2xl">account_balance_wallet</span>
                        </div>
                        <span class="text-[9px] font-black text-white/40 uppercase tracking-[0.3em] mb-1">Total Received</span>
                        <h2 class="text-3xl font-black tracking-tight leading-none mb-6 text-white">{{ showAmount($deposit->amount + $deposit->charge) }}</h2>
                        
                        <div class="w-full h-[1px] bg-white/10 my-1"></div>
                        
                        <div class="w-full flex justify-between items-center py-3">
                            <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest flex items-center gap-1.5"><span class="material-symbols-rounded text-[13px]">person</span> User</span>
                            <span class="text-[11px] font-black text-white bg-white/10 px-2.5 py-1 rounded-md">
                                {!! $deposit->user ? '@'.$deposit->user->username : ($deposit->marketplace ? $deposit->marketplace->name : 'Deleted User') !!}
                            </span>
                        </div>
                        <div class="w-full flex justify-between items-center py-3 border-t border-white/5">
                            <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest flex items-center gap-1.5"><span class="material-symbols-rounded text-[13px]">account_balance</span> Method</span>
                            <span class="text-[11px] font-black text-white">
                                {{ ($deposit->method_code < 5000) ? __($deposit->gateway->name) : 'Google Pay' }}
                            </span>
                        </div>
                        <div class="w-full flex justify-between items-center py-3 border-t border-white/5">
                            <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest flex items-center gap-1.5"><span class="material-symbols-rounded text-[13px]">calendar_today</span> Date</span>
                            <span class="text-[11px] font-black text-white">
                                {{ showDateTime($deposit->created_at, 'd M Y, h:i A') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Admin Feedback -->
                @if($deposit->admin_feedback)
                <div class="bg-blue-50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-500/20 p-5 flex gap-3 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-rounded text-lg">chat_bubble</span>
                    </div>
                    <div>
                        <h4 class="text-[9px] font-black text-blue-500 uppercase tracking-widest mb-1">Admin Feedback</h4>
                        <p class="text-[12px] font-bold text-blue-900 dark:text-blue-100 leading-relaxed">{{ __($deposit->admin_feedback) }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Ledger & Verification -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Financial Breakdown -->
                <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400 dark:text-white/40">
                            <span class="material-symbols-rounded text-xl">receipt_long</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Ledger Breakdown</h3>
                            <p class="text-[9px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mt-0.5">Financial details of the transaction</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="p-5 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 flex flex-col justify-center">
                            <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mb-1.5 flex items-center gap-1.5"><span class="material-symbols-rounded text-[13px]">payments</span> Base Amount</span>
                            <span class="text-xl font-black text-slate-900 dark:text-white tracking-tight">{{ showAmount($deposit->amount) }}</span>
                        </div>
                        <div class="p-5 rounded-2xl bg-rose-50 dark:bg-rose-500/5 border border-rose-100 dark:border-rose-500/10 flex flex-col justify-center">
                            <span class="text-[9px] font-black text-rose-500 dark:text-rose-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5"><span class="material-symbols-rounded text-[13px]">price_change</span> Gateway Charge</span>
                            <span class="text-xl font-black text-rose-600 dark:text-rose-400 tracking-tight">+ {{ showAmount($deposit->charge) }}</span>
                        </div>
                        <div class="sm:col-span-2 p-5 rounded-2xl bg-indigo-50 dark:bg-indigo-500/5 border border-indigo-100 dark:border-indigo-500/10 flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4">
                            <div>
                                <span class="text-[9px] font-black text-indigo-500 dark:text-indigo-400 uppercase tracking-widest mb-1.5 flex items-center justify-center sm:justify-start gap-1.5"><span class="material-symbols-rounded text-[13px]">currency_exchange</span> Conversion ({{ __($deposit->method_currency) }})</span>
                                <span class="text-2xl font-black text-indigo-700 dark:text-indigo-400 tracking-tight">{{ showAmount($deposit->final_amount, currencyFormat: false) }}</span>
                            </div>
                            <div class="h-8 w-[1px] bg-indigo-200 dark:bg-indigo-500/20 hidden sm:block"></div>
                            <div class="text-center sm:text-right">
                                <span class="text-[9px] font-black text-indigo-400 uppercase tracking-widest block mb-1">Exchange Rate</span>
                                <span class="text-[12px] font-bold text-indigo-600 dark:text-indigo-300 bg-indigo-100 dark:bg-indigo-500/20 px-2 py-1 rounded-md">1 {{ gs('cur_text') }} = {{ showAmount($deposit->rate, currencyFormat: false) }} {{ __($deposit->method_currency) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Proof -->
                @if($details != null || $deposit->status == Status::PAYMENT_PENDING)
                <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-500/10 flex items-center justify-center text-orange-500">
                            <span class="material-symbols-rounded text-xl">fact_check</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Verification Evidence</h3>
                            <p class="text-[9px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mt-0.5">Submitted data for administrative review</p>
                        </div>
                    </div>

                    @if($details != null)
                        <div class="space-y-3">
                            @php $decodedDetails = json_decode($details); @endphp
                            @if(is_array($decodedDetails) || is_object($decodedDetails))
                                @foreach($decodedDetails as $key => $val)
                                    @if($deposit->method_code >= 1000)
                                        <div class="group p-4 rounded-xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 hover:border-orange-500/30 transition-all flex items-center justify-between gap-3">
                                            <div class="flex-1">
                                                <span class="block text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-1">{{ __(@$val->name ?? keyToTitle($key)) }}</span>
                                                @if(isset($val->type) && @$val->type == 'checkbox')
                                                    <span class="text-[13px] font-bold text-slate-800 dark:text-white/90">{{ implode(',',@$val->value) }}</span>
                                                @elseif(isset($val->type) && @$val->type == 'file')
                                                    @if(@$val->value)
                                                        <span class="text-[13px] font-bold text-slate-800 dark:text-white/90 flex items-center gap-1.5"><span class="material-symbols-rounded text-emerald-500 text-sm">check_circle</span> File Uploaded</span>
                                                    @else
                                                        <span class="text-[13px] font-bold text-slate-400">No file</span>
                                                    @endif
                                                @else
                                                    <p class="text-[13px] font-bold text-slate-800 dark:text-white/90 leading-relaxed">{{ is_scalar($val) ? $val : __(@$val->value) }}</p>
                                                @endif
                                            </div>
                                            @if(isset($val->type) && @$val->type == 'file' && @$val->value)
                                                <a href="{{ route('admin.download.attachment',encrypt(getFilePath('verify').'/'.@$val->value)) }}" class="flex-shrink-0 w-10 h-10 rounded-lg bg-orange-500 text-white flex items-center justify-center hover:scale-105 active:scale-95 transition-all shadow-md shadow-orange-500/20" title="Download">
                                                    <span class="material-symbols-rounded text-[18px]">download</span>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        @if($deposit->method_code < 1000)
                            @include('admin.deposit.gateway_data',['details'=>json_decode($details)])
                        @endif
                    @else
                        <div class="py-10 text-center flex flex-col items-center bg-slate-50 dark:bg-white/[0.02] rounded-2xl border border-dashed border-slate-200 dark:border-white/10">
                            <span class="material-symbols-rounded text-3xl text-slate-300 dark:text-white/20 mb-2">inventory_2</span>
                            <span class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-[0.2em]">No verification data provided</span>
                        </div>
                    @endif
                </div>
                @endif
                
                <!-- Action Buttons -->
                @if($deposit->status == Status::PAYMENT_PENDING)
                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    <button class="w-full sm:w-auto h-12 px-8 rounded-xl bg-white dark:bg-[#121212] text-rose-500 flex items-center justify-center gap-2 text-[11px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all border border-rose-100 dark:border-rose-500/20 shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <span class="material-symbols-rounded text-base">block</span>
                        Reject
                    </button>
                    <button class="flex-1 h-12 rounded-xl bg-emerald-500 text-white flex items-center justify-center gap-2 text-[12px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:scale-[1.02] active:scale-95 transition-all shadow-md shadow-emerald-500/20 confirmationBtn"
                            data-action="{{ route('admin.deposit.approve', $deposit->id) }}"
                            data-question="@lang('Are you sure to approve this transaction?')">
                        <span class="material-symbols-rounded text-lg">check_circle</span>
                        Authorize Deposit
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- REJECT MODAL --}}
    <div id="rejectModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 rounded-3xl bg-white dark:bg-[#121212] shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                    <h5 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">@lang('Denial Intelligence')</h5>
                    <button type="button" class="w-10 h-10 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all" data-bs-dismiss="modal">
                        <span class="material-symbols-rounded text-[18px]">close</span>
                    </button>
                </div>
                <form action="{{ route('admin.deposit.reject')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $deposit->id }}">
                    <div class="p-6 space-y-5">
                        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/20">
                            <p class="text-[12px] font-bold text-rose-600 dark:text-rose-400 leading-relaxed">
                                @lang('You are about to') <span class="font-black uppercase">@lang('reject')</span> @lang('the deposit of') <span class="font-black text-rose-700 dark:text-rose-300">{{ showAmount($deposit->amount)}}</span> @lang('from') <span class="font-black text-rose-700 dark:text-rose-300">@if($deposit->user) {{'@'.$deposit->user->username}} @else Deleted User @endif</span>. @lang('Please provide a reason below.')
                            </p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-[0.2em]">Reason for Rejection</label>
                            <textarea name="message" class="w-full h-28 px-4 py-3 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[13px] font-bold text-slate-700 dark:text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all resize-none" placeholder="E.g. Insufficient proof of payment..." required></textarea>
                            <p class="text-[10px] font-bold text-slate-400 flex items-center gap-1"><span class="material-symbols-rounded text-sm">visibility</span> This feedback will be visible to the user.</p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <button type="submit" class="w-full h-12 rounded-xl bg-rose-600 text-white flex items-center justify-center gap-2 text-[11px] font-black uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-md shadow-rose-600/20">
                            <span class="material-symbols-rounded text-[18px]">gavel</span>
                            @lang('Confirm Rejection')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

