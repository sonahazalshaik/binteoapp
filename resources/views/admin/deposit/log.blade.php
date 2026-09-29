@extends('admin.layouts.app')
@section('content')
    <div class="row justify-content-center">
        @if (request()->routeIs('admin.deposit.list') || request()->routeIs('admin.deposit.method') || request()->routeIs('admin.users.deposits') || request()->routeIs('admin.users.deposits.method'))
            <div class="col-12 mb-8">
                @include('admin.deposit.widget')
            </div>
        @endif

        <div class="col-md-12">
            <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[3rem] overflow-hidden shadow-2xl transition-all duration-300">
                <div class="px-6 py-8 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Financial Influx</h3>
                        <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3 leading-relaxed">Audit trail for all inbound payment gateway transactions</p>
                    </div>
                </div>
                <div class="px-6 pt-6">
                    @php
                        $exportScope = request()->routeIs('admin.deposit.pending') ? 'pending'
                            : (request()->routeIs('admin.deposit.rejected') ? 'rejected'
                            : (request()->routeIs('admin.deposit.successful') ? 'successful' : null));
                    @endphp
                    @include('admin.components.table-toolbar', ['module' => 'deposits', 'exportTotal' => $deposits->total(), 'exportScope' => $exportScope])
                </div>
                
                <div class="overflow-x-auto scrollbar-hide">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Gateway / TRX')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Originator')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Amount Matrix')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap text-center">@lang('Status')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap text-right">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($deposits as $deposit)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-tight">
                                                @if ($deposit->method_code < 5000)
                                                    {{ __(@$deposit->gateway->name) }}
                                                @else
                                                    @lang('Google Pay')
                                                @endif
                                            </span>
                                            <code class="text-[9px] text-orange-500 font-bold uppercase mt-1">{{ $deposit->trx }}</code>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 font-black text-[12px] group-hover:scale-110 transition-transform">
                                                @if($deposit->user)
                                                    {{ substr($deposit->user->fullname, 0, 1) }}
                                                @elseif($deposit->marketplace)
                                                    {{ substr($deposit->marketplace->name, 0, 1) }}
                                                @else
                                                    ?
                                                @endif
                                            </div>
                                            <div class="flex flex-col">
                                                @if($deposit->user)
                                                    <span class="text-[11px] font-black text-slate-900 dark:text-white leading-none mb-1">{{ $deposit->user->fullname }}</span>
                                                    <a href="{{ appendQuery('search', @$deposit->user->username) }}" class="text-[9px] font-bold text-blue-500 uppercase tracking-widest hover:underline">@<span>{{ $deposit->user->username }}</span></a>
                                                @elseif($deposit->marketplace)
                                                    <span class="text-[11px] font-black text-slate-900 dark:text-white leading-none mb-1">{{ $deposit->marketplace->name }}</span>
                                                    <span class="text-[9px] font-bold text-orange-500 uppercase tracking-widest">[Marketplace]</span>
                                                @else
                                                    <span class="text-[11px] font-black text-slate-900 dark:text-white leading-none mb-1">Deleted User</span>
                                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">@<span>N/A</span></span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-0.5">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[12px] font-black text-slate-900 dark:text-white">{{ showAmount($deposit->amount, 0) }}</span>
                                                <span class="text-[9px] font-bold text-red-500 uppercase tracking-tighter" title="Charge">+{{ showAmount($deposit->charge, 0) }}</span>
                                            </div>
                                            <span class="text-[10px] font-bold text-slate-400 ">Total: {{ showAmount($deposit->amount + $deposit->charge, 0) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php echo $deposit->statusBadge @endphp
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="grid grid-cols-2 gap-2 w-max ml-auto">
                                            @if($deposit->status == Status::PAYMENT_PENDING)
                                                <form action="{{ route('admin.deposit.approve', $deposit->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="h-10 px-3 rounded-xl bg-emerald-500 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm">
                                                        <span class="material-symbols-rounded text-sm">check_circle</span>
                                                        <span class="text-[9px] font-black uppercase tracking-widest">@lang('Approve')</span>
                                                    </button>
                                                </form>
                                                <button class="h-10 px-3 rounded-xl bg-rose-500 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm" 
                                                        @click="$dispatch('open-deposit-reject-modal', { id: {{ $deposit->id }}, trx: '{{ $deposit->trx }}' })">
                                                    <span class="material-symbols-rounded text-sm">cancel</span>
                                                    <span class="text-[9px] font-black uppercase tracking-widest">@lang('Reject')</span>
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.deposit.details', $deposit->id) }}" class="h-10 px-3 rounded-xl bg-blue-500 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all shadow-sm {{ $deposit->status != Status::PAYMENT_PENDING ? 'col-span-2' : '' }}">
                                                <span class="material-symbols-rounded text-sm">visibility</span>
                                                <span class="text-[9px] font-black uppercase tracking-widest">@lang('Details')</span>
                                            </a>
                                            <button class="h-10 px-3 rounded-xl bg-red-500 text-white flex items-center justify-center gap-2 hover:scale-105 transition-all confirmationBtn {{ $deposit->status != Status::PAYMENT_PENDING ? 'col-span-2' : '' }}" 
                                                    data-action="{{ Route::has('admin.deposit.delete') ? route('admin.deposit.delete', $deposit->id) : route('admin.deposit.destroy', $deposit->id) }}" 
                                                    data-method="DELETE"
                                                    data-question="@lang('Are you sure you want to delete this record?')">
                                                <span class="material-symbols-rounded text-sm">delete</span>
                                                <span class="text-[9px] font-black uppercase tracking-widest">@lang('Delete')</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-10 py-24 text-center">
                                        <div class="w-20 h-20 mx-auto mb-6 rounded-[1.75rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center">
                                            <span class="material-symbols-rounded text-4xl text-slate-300 dark:text-white/20">receipt_long</span>
                                        </div>
                                        <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2">No Entries Found</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.25em]">Nothing to display for this filter</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if ($deposits->hasPages())
                    <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                        @php echo paginateLinks($deposits) @endphp
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
    <!-- Deposit Reject Modal -->
    <div x-data="{ 
            show: false, 
            depositId: '', 
            trx: '',
            init() {
                window.addEventListener('open-deposit-reject-modal', (e) => {
                    this.depositId = e.detail.id;
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
            
            <form action="{{ route('admin.deposit.reject') }}" method="POST">
                @csrf
                <input type="hidden" name="id" :value="depositId">
                <!-- Header -->
                <div class="h-24 bg-gradient-to-r from-rose-500 to-red-600 flex items-center px-8 relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/10 mix-blend-overlay"></div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-black text-white uppercase tracking-tighter shadow-sm">Reject Payment</h3>
                        <p class="text-[10px] font-bold text-white/80 uppercase tracking-widest mt-1">TRX: <span x-text="trx"></span></p>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8 space-y-6">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest ml-2">Rejection Reason</label>
                        <textarea name="message" required class="w-full h-32 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl p-4 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="Provide the reason for rejection..."></textarea>
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
    @if (!request()->routeIs('admin.users.deposits') && !request()->routeIs('admin.users.deposits.method'))
        <x-search-form dateSearch='yes' placeholder='Username / Email' />
    @endif
@endpush

