@extends('admin.layouts.app')

@section('content')
    <div class="row justify-content-center">
        @if (request()->routeIs('admin.withdraw.data.all') || request()->routeIs('admin.withdraw.method') || request()->routeIs('admin.users.withdrawals') || request()->routeIs('admin.users.withdrawals.method'))
            <div class="col-12">
                @include('admin.withdraw.widget')
            </div>
        @endif
        <div class="col-lg-12">
            <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                @include('admin.components.header-toolbar', [
                    'title' => 'Withdrawals',
                    'createRoute' => route('admin.withdraw.data.create'),
                    'createLabel' => 'Add Withdrawal'
                ])
                <div class="px-6 pt-6">
                    @include('admin.components.table-toolbar', ['module' => 'withdrawals', 'exportTotal' => count($withdrawals)])
                </div>
                <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
                    <table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Method | Transaction')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Initiated')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('User')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Amount')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Conversion')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Status')</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Action')</th>

                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @forelse($withdrawals as $withdraw)
                                    @php
                                        $details = $withdraw->withdraw_information != null ? json_encode($withdraw->withdraw_information) : null;
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <span class="fw-bold"><a href="{{ appendQuery('method', @$withdraw->method->id) }}"> {{ __(@$withdraw->method->name) }}</a></span>
                                            <br>
                                            <small>{{ $withdraw->trx }}</small>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ showDateTime($withdraw->created_at) }} <br> {{ diffForHumans($withdraw->created_at) }}
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <span class="fw-bold">{{ $withdraw->user->fullname }}</span>
                                            <br>
                                            <span class="small"> <a href="{{ appendQuery('search', @$withdraw->user->username) }}"><span>@</span>{{ $withdraw->user->username }}</a> </span>
                                        </td>


                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ showAmount($withdraw->amount, 0) }} - <span class="text--danger" title="@lang('charge')">{{ showAmount($withdraw->charge, 0) }} </span>
                                            <br>
                                            <strong title="@lang('Amount after charge')">
                                                {{ showAmount($withdraw->amount - $withdraw->charge, 0) }}
                                            </strong>
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            {{ showAmount(1) }} = {{ showAmount($withdraw->rate, currencyFormat: false) }} {{ __($withdraw->currency) }}
                                            <br>
                                            <strong>{{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ __($withdraw->currency) }}</strong>
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            @php echo $withdraw->statusBadge @endphp
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="grid grid-cols-2 gap-2 w-max">
                                                @if($withdraw->status == Status::PAYMENT_PENDING)
                                                    <button class="px-3 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center gap-2 transition-all hover:scale-105 shadow-sm" 
                                                            @click="$dispatch('open-withdraw-approve-modal', { id: {{ $withdraw->id }}, amount: '{{ showAmount($withdraw->final_amount, currencyFormat: false) }}', currency: '{{ $withdraw->currency }}' })">
                                                        <span class="material-symbols-rounded text-sm">check_circle</span>
                                                        <span class="text-[9px] font-black uppercase tracking-widest">@lang('Approve')</span>
                                                    </button>
                                                    <button class="px-3 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center gap-2 transition-all hover:scale-105 shadow-sm" 
                                                            @click="$dispatch('open-withdraw-reject-modal', { id: {{ $withdraw->id }}, trx: '{{ $withdraw->trx }}' })">
                                                        <span class="material-symbols-rounded text-sm">cancel</span>
                                                        <span class="text-[9px] font-black uppercase tracking-widest">@lang('Reject')</span>
                                                    </button>
                                                @endif
                                                <a href="{{ route('admin.withdraw.data.details', $withdraw->id) }}" class="px-3 h-8 rounded-lg bg-indigo-500 text-white flex items-center justify-center gap-2 transition-all hover:scale-105 shadow-sm {{ $withdraw->status != Status::PAYMENT_PENDING ? 'col-span-2' : '' }}">
                                                    <span class="material-symbols-rounded text-sm">visibility</span>
                                                    <span class="text-[9px] font-black uppercase tracking-widest">@lang('Details')</span>
                                                </a>
                                                <button class="px-3 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center gap-2 transition-all hover:scale-105 shadow-sm confirmationBtn {{ $withdraw->status != Status::PAYMENT_PENDING ? 'col-span-2' : '' }}" data-action="{{ Route::has('admin.withdraw.destroy') ? route('admin.withdraw.destroy', $withdraw->id ?? 0) : (Route::has('admin.withdraw.delete') ? route('admin.withdraw.delete', $withdraw->id ?? 0) : 'javascript:void(0)') }}" data-question="@lang('Are you sure you want to delete this record?')">
                                                    <span class="material-symbols-rounded text-sm">delete</span>
                                                    <span class="text-[9px] font-black uppercase tracking-widest">@lang('Delete')</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>

                    <!-- App View -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" style="display: none;">
                        @forelse($withdrawals as $withdraw)
                            <div class="group bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-3xl p-6 hover:shadow-xl hover:border-primary-500/30 transition-all duration-300">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <span class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest block mb-1">TRX: {{ $withdraw->trx }}</span>
                                        <div class="text-[14px] font-black text-slate-800 dark:text-white leading-tight">
                                            {{ __(@$withdraw->method->name) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        @php echo $withdraw->statusBadge; @endphp
                                    </div>
                                </div>

                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500">
                                            <i class="las la-user"></i>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest block">User</span>
                                            <a href="{{ route('admin.users.detail', $withdraw->user->id)}}" class="text-[11px] font-bold text-slate-700 dark:text-white/70">{{@$withdraw->user->fullname}}</a>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center bg-slate-50 dark:bg-white/[0.02] rounded-2xl p-3">
                                        <div>
                                            <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest block">Amount</span>
                                            <span class="text-[11px] font-bold text-slate-700 dark:text-white/70">{{ showAmount($withdraw->amount - $withdraw->charge) }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest block">Initiated</span>
                                            <span class="text-[11px] font-bold text-slate-700 dark:text-white/70">{{ diffForHumans($withdraw->created_at) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-slate-100 dark:border-white/5">
                                    @if($withdraw->status == Status::PAYMENT_PENDING)
                                        <button class="flex-1 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all shadow-sm group/btn" 
                                                @click="$dispatch('open-withdraw-approve-modal', { id: {{ $withdraw->id }}, amount: '{{ showAmount($withdraw->final_amount, currencyFormat: false) }}', currency: '{{ $withdraw->currency }}' })">
                                            <span class="material-symbols-rounded text-sm group-hover/btn:scale-110 transition-transform">check_circle</span>
                                            Approve
                                        </button>
                                        <button class="flex-1 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-sm group/btn" 
                                                @click="$dispatch('open-withdraw-reject-modal', { id: {{ $withdraw->id }}, trx: '{{ $withdraw->trx }}' })">
                                            <span class="material-symbols-rounded text-sm group-hover/btn:scale-110 transition-transform">cancel</span>
                                            Reject
                                        </button>
                                    @endif
                                    
                                    <div class="flex items-center gap-2 w-full">
                                        <a href="{{ route('admin.withdraw.data.details', $withdraw->id) }}" class="flex-grow h-10 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-white text-[9px] font-black uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all flex items-center justify-center gap-2 group/btn">
                                            <span class="material-symbols-rounded text-sm group-hover/btn:translate-x-1 transition-transform">visibility</span>
                                            View Details
                                        </a>
                                        <button class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all confirmationBtn" data-action="{{ Route::has('admin.withdraw.destroy') ? route('admin.withdraw.destroy', $withdraw->id ?? 0) : (Route::has('admin.withdraw.delete') ? route('admin.withdraw.delete', $withdraw->id ?? 0) : 'javascript:void(0)') }}" data-question="@lang('Are you sure you want to delete this record?')" title="@lang('Delete')">
                                            <span class="material-symbols-rounded text-sm">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full flex flex-col items-center justify-center p-12 text-center">
                                <div class="w-24 h-24 bg-slate-100 dark:bg-white/5 rounded-full flex items-center justify-center mb-4">
                                    <i class="las la-hand-holding-usd text-4xl text-slate-400 dark:text-white/20"></i>
                                </div>
                                <h4 class="text-lg font-black text-slate-700 dark:text-white uppercase tracking-widest mb-1">{{ __($emptyMessage) }}</h4>
                                <p class="text-[11px] font-bold text-slate-400 dark:text-white/40">No records found for this query.</p>
                            </div>
                        @endforelse
                    </div>

                </div>
                @if ($withdrawals->hasPages())
                    <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                        {{ paginateLinks($withdrawals) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
    <x-confirmation-modal />
    <!-- Withdraw Approve Modal -->
    <div x-data="{ 
            show: false, 
            withdrawId: '', 
            amount: '',
            currency: '',
            init() {
                window.addEventListener('open-withdraw-approve-modal', (e) => {
                    this.withdrawId = e.detail.id;
                    this.amount = e.detail.amount;
                    this.currency = e.detail.currency;
                    this.show = true;
                });
            }
        }" 
        x-show="show" 
        class="fixed inset-0 z-[100] flex items-center justify-center" 
        x-cloak>
        
        <!-- Backdrop -->
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-0"
             x-transition:enter-end="opacity-100 backdrop-blur-sm"
             class="absolute inset-0 bg-black/60" 
             @click="show = false"></div>

        <!-- Modal Content -->
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-md bg-white dark:bg-[#1a1a1a] rounded-[2rem] shadow-2xl border border-white/20 dark:border-white/10 overflow-hidden z-10 m-4">
            
            <form action="{{ route('admin.withdraw.data.approve') }}" method="POST">
                @csrf
                <input type="hidden" name="id" :value="withdrawId">
                <!-- Header -->
                <div class="h-24 bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center px-8 relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/10 mix-blend-overlay"></div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-black text-white uppercase tracking-tighter shadow-sm">Approve Withdrawal</h3>
                        <p class="text-[10px] font-bold text-white/80 uppercase tracking-widest mt-1">Amount: <span x-text="`${amount} ${currency}`"></span></p>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8 space-y-6">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest ml-2">Transaction Details</label>
                        <textarea name="details" required class="w-full h-32 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl p-4 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" placeholder="Provide transaction number or payment details..."></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex gap-3">
                    <button type="button" @click="show = false" class="flex-1 h-11 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/10 transition-all ">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 h-11 rounded-xl bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20 active:scale-95 flex items-center justify-center gap-2">
                        <span class="material-symbols-rounded text-sm">check_circle</span> Approve
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdraw Reject Modal -->
    <div x-data="{ 
            show: false, 
            withdrawId: '', 
            trx: '',
            init() {
                window.addEventListener('open-withdraw-reject-modal', (e) => {
                    this.withdrawId = e.detail.id;
                    this.trx = e.detail.trx;
                    this.show = true;
                });
            }
        }" 
        x-show="show" 
        class="fixed inset-0 z-[100] flex items-center justify-center" 
        x-cloak>
        
        <!-- Backdrop -->
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-0"
             x-transition:enter-end="opacity-100 backdrop-blur-sm"
             class="absolute inset-0 bg-black/60" 
             @click="show = false"></div>

        <!-- Modal Content -->
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-md bg-white dark:bg-[#1a1a1a] rounded-[2rem] shadow-2xl border border-white/20 dark:border-white/10 overflow-hidden z-10 m-4">
            
            <form action="{{ route('admin.withdraw.data.reject') }}" method="POST">
                @csrf
                <input type="hidden" name="id" :value="withdrawId">
                <!-- Header -->
                <div class="h-24 bg-gradient-to-r from-rose-500 to-red-600 flex items-center px-8 relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/10 mix-blend-overlay"></div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-black text-white uppercase tracking-tighter shadow-sm">Reject Withdrawal</h3>
                        <p class="text-[10px] font-bold text-white/80 uppercase tracking-widest mt-1">TRX: <span x-text="trx"></span></p>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8 space-y-6">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest ml-2">Rejection Reason</label>
                        <textarea name="details" required class="w-full h-32 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl p-4 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="Provide the reason for rejection..."></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex gap-3">
                    <button type="button" @click="show = false" class="flex-1 h-11 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/10 transition-all ">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 h-11 rounded-xl bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all shadow-lg shadow-rose-500/20 active:scale-95 flex items-center justify-center gap-2">
                        <span class="material-symbols-rounded text-sm">cancel</span> Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection




@push('breadcrumb-plugins')
    <x-search-form dateSearch='yes' placeholder='Username / Email' />
@endpush


@push('script-lib')
    <script src="{{ asset('assets/global/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/css/daterangepicker.css') }}">
@endpush


