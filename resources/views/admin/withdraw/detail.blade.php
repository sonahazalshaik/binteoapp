@extends('admin.layouts.app')

@section('panel')
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700" 
         x-data="{ 
            approveModal: false, 
            rejectModal: false 
         }">
        
        <!-- Header Info Card -->
        <div class="relative bg-white dark:bg-[#121212] rounded-[3rem] border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden p-8">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-[100px] -mr-32 -mt-32"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-500 shadow-xl shadow-indigo-500/5">
                        <span class="material-symbols-rounded text-3xl">payments</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter uppercase ">Withdrawal #{{ $withdrawal->trx }}</h2>
                        <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.3em] mt-1 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Method used: {{ __(@$withdrawal->method->name) }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right mr-4 hidden sm:block">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Request Date</p>
                        <p class="text-xs font-bold text-slate-700 dark:text-white mt-0.5">{{ showDateTime($withdrawal->created_at) }}</p>
                    </div>
                    @php echo $withdrawal->statusBadge @endphp
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left: Financial Matrix -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white dark:bg-[#121212] rounded-[3rem] border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
                        <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] ">Payment Summary</h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="flex justify-between items-center p-5 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Withdrawal Amount</span>
                            <span class="text-sm font-black text-slate-900 dark:text-white ">{{ showAmount($withdrawal->amount, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-5 rounded-2xl bg-rose-500/5 border border-rose-500/10">
                            <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Processing Fee</span>
                            <span class="text-sm font-black text-rose-600 ">- {{ showAmount($withdrawal->charge, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-5 rounded-2xl bg-emerald-500/5 border border-emerald-500/10">
                            <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Payable Amount</span>
                            <span class="text-sm font-black text-emerald-600 ">{{ showAmount($withdrawal->after_charge, 0) }}</span>
                        </div>
                        <div class="pt-4 mt-4 border-t border-slate-100 dark:border-white/5">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Conversion Rate</span>
                                <span class="text-[10px] font-bold text-slate-700 dark:text-white">1 {{ gs('cur_text') }} = {{ showAmount($withdrawal->rate, 0, currencyFormat: false) }} {{ $withdrawal->currency }}</span>
                            </div>
                            <div class="p-6 rounded-[2rem] bg-gradient-to-br from-indigo-600 to-violet-700 shadow-xl shadow-indigo-500/20 text-center">
                                <span class="block text-[8px] font-black text-indigo-100 uppercase tracking-[0.4em] mb-2">Total amount to send</span>
                                <span class="text-xl font-black text-white tracking-tighter">{{ showAmount($withdrawal->final_amount, 0, currencyFormat: false) }} {{ $withdrawal->currency }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Quick Access -->
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden group shadow-2xl">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 to-transparent"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                                <span class="material-symbols-rounded text-xl">person</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-black uppercase tracking-tight">{{ @$withdrawal->user->fullname }}</h4>
                                <a href="{{ route('admin.users.detail', $withdrawal->user_id) }}" class="text-[10px] font-bold text-indigo-400 hover:text-white transition-colors">@<span>{{ @$withdrawal->user->username }}</span></a>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.detail', $withdrawal->user_id) }}" class="w-full h-12 rounded-xl bg-white text-slate-900 flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest hover:scale-[1.02] transition-all">
                            View Full User Details
                            <span class="material-symbols-rounded text-sm">open_in_new</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right: Submission Data & Actions -->
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white dark:bg-[#121212] rounded-[3rem] border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
                        <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] ">User Submission Info</h3>
                    </div>
                    <div class="p-10">
                        @if($details != null)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                @foreach(json_decode($details) as $val)
                                    <div class="space-y-2 p-6 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 group hover:border-indigo-500/30 transition-colors">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ __($val->name) }}</p>
                                        @if($val->type == 'checkbox')
                                            <p class="text-xs font-black text-slate-800 dark:text-white uppercase">{{ implode(', ', $val->value) }}</p>
                                        @elseif($val->type == 'file')
                                            @if($val->value)
                                                <a href="{{ route('user.download.attachment', encrypt(getFilePath('verify').'/'.$val->value)) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500/10 text-indigo-500 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition-all">
                                                    <span class="material-symbols-rounded text-sm">download</span>
                                                    Download Attachment
                                                </a>
                                            @else
                                                <p class="text-xs font-bold text-slate-400">No Attachment Provided</p>
                                            @endif
                                        @else
                                            <p class="text-sm font-black text-slate-800 dark:text-white uppercase">{{ __($val->value) }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-12 text-center">
                                <span class="material-symbols-rounded text-5xl text-slate-200 dark:text-white/10 mb-4">folder_off</span>
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest ">No custom metadata submitted with this request.</p>
                            </div>
                        @endif

                        @if($withdrawal->admin_feedback)
                            <div class="mt-10 p-8 rounded-[2rem] bg-indigo-500/5 border border-indigo-500/10 relative overflow-hidden">
                                <span class="material-symbols-rounded absolute -right-4 -bottom-4 text-9xl text-indigo-500/5">rate_review</span>
                                <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-3 ">Reviewer's Note</h4>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-400 leading-relaxed ">"{{ $withdrawal->admin_feedback }}"</p>
                            </div>
                        @endif

                        @if($withdrawal->status == Status::PAYMENT_PENDING)
                            <div class="mt-12 flex flex-col sm:flex-row gap-4 pt-8 border-t border-slate-100 dark:border-white/5">
                                <button type="button" @click="approveModal = true" class="flex-1 h-14 rounded-2xl bg-emerald-500 text-white font-black text-[10px] uppercase tracking-widest shadow-xl shadow-emerald-500/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 group">
                                    <span class="material-symbols-rounded text-lg group-hover:rotate-12 transition-transform">check_circle</span>
                                    Approve
                                </button>
                                <button type="button" @click="rejectModal = true" class="flex-1 h-14 rounded-2xl bg-rose-500 text-white font-black text-[10px] uppercase tracking-widest shadow-xl shadow-rose-500/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 group">
                                    <span class="material-symbols-rounded text-lg group-hover:-rotate-12 transition-transform">cancel</span>
                                    Reject
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Alpine Appove Modal -->
        <div x-show="approveModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center" 
             x-cloak>
            <div x-show="approveModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 backdrop-blur-0"
                 x-transition:enter-end="opacity-100 backdrop-blur-sm"
                 class="absolute inset-0 bg-black/60" 
                 @click="approveModal = false"></div>

            <div x-show="approveModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="relative w-full max-w-md bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] shadow-2xl border border-white/20 dark:border-white/10 overflow-hidden z-10 m-4">
                
                <form action="{{ route('admin.withdraw.data.approve') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $withdrawal->id }}">
                    <div class="h-24 bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center px-8 relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/10 mix-blend-overlay"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-white uppercase tracking-tighter shadow-sm">Approve Payment</h3>
                            <p class="text-[10px] font-bold text-white/80 uppercase tracking-widest mt-1">Reference Number: {{ $withdrawal->trx }}</p>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="p-6 rounded-2xl bg-emerald-50 dark:bg-emerald-500/5 border border-emerald-100 dark:border-emerald-500/10 text-center">
                            <p class="text-[9px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-1">Confirming Payment Of</p>
                            <p class="text-2xl font-black text-emerald-700 dark:text-emerald-500 tracking-tighter">
                                {{ showAmount($withdrawal->final_amount, currencyFormat: false) }} {{ $withdrawal->currency }}
                            </p>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest ml-2">Transaction Proof / Details</label>
                            <textarea name="details" required class="w-full h-32 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-5 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all shadow-inner" placeholder="E.g. Bank Transfer ID: TXN_88291..."></textarea>
                        </div>
                    </div>
                    <div class="p-6 bg-slate-50/50 dark:bg-white/[0.02] border-t border-slate-100 dark:border-white/5 flex gap-3">
                        <button type="button" @click="approveModal = false" class="flex-1 h-12 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/10 transition-all ">Cancel</button>
                        <button type="submit" class="flex-1 h-12 rounded-xl bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20 active:scale-95 flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded text-sm">check_circle</span> APPROVE
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Alpine Reject Modal -->
        <div x-show="rejectModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center" 
             x-cloak>
            <div x-show="rejectModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 backdrop-blur-0"
                 x-transition:enter-end="opacity-100 backdrop-blur-sm"
                 class="absolute inset-0 bg-black/60" 
                 @click="rejectModal = false"></div>

            <div x-show="rejectModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="relative w-full max-w-md bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] shadow-2xl border border-white/20 dark:border-white/10 overflow-hidden z-10 m-4">
                
                <form action="{{ route('admin.withdraw.data.reject') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $withdrawal->id }}">
                    <div class="h-24 bg-gradient-to-r from-rose-500 to-red-600 flex items-center px-8 relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/10 mix-blend-overlay"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-white uppercase tracking-tighter shadow-sm">Reject Withdrawal</h3>
                            <p class="text-[10px] font-bold text-white/80 uppercase tracking-widest mt-1">Reference Number: {{ $withdrawal->trx }}</p>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest ml-2">Reason for Decline</label>
                            <textarea name="details" required class="w-full h-32 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-5 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all shadow-inner" placeholder="E.g. Incorrect bank details, Insufficient activity..."></textarea>
                        </div>
                    </div>
                    <div class="p-6 bg-slate-50/50 dark:bg-white/[0.02] border-t border-slate-100 dark:border-white/5 flex gap-3">
                        <button type="button" @click="rejectModal = false" class="flex-1 h-12 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/10 transition-all ">Cancel</button>
                        <button type="submit" class="flex-1 h-12 rounded-xl bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all shadow-lg shadow-rose-500/20 active:scale-95 flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded text-sm">cancel</span> REJECT
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

