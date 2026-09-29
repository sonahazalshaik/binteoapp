@extends('admin.layouts.app')

@section('panel')
<div class="row">

    <div class="col-lg-12">
        <div class="card bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
            @include('admin.components.header-toolbar', ['title' => $pageTitle])
            <div class="px-6 pt-6">
                @include('admin.components.table-toolbar')
            </div>

            <!-- Streamlined Filter System -->
            <div class="px-6 pb-6" x-data="{ showAdvanced: {{ request()->hasAny(['user_id', 'date', 'min_amount', 'max_amount', 'remark']) ? 'true' : 'false' }} }">
                <form action="" method="GET" class="space-y-4">
                    <!-- Primary Intelligence Bar -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Search Bar with Integrated Action -->
                        <div class="flex-grow min-w-[280px] relative group">
                            <input type="text" name="search" value="{{ request()->search }}" 
                                   class="h-11 w-full pl-11 pr-24 rounded-2xl bg-slate-50 dark:bg-white/[0.03] border border-transparent focus:border-rose-500/50 focus:bg-white dark:focus:bg-[#1a1a1a] text-[11px] font-bold text-slate-700 dark:text-white/80 transition-all outline-none" 
                                   placeholder="Search transaction codes or usernames...">
                            <span class="material-symbols-rounded absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg group-focus-within:text-rose-500 transition-colors">search</span>
                            
                            <!-- Search Trigger -->
                            <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 px-4 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black text-[9px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all">
                                Search
                            </button>
                        </div>

                        <!-- Type Selector -->
                        <div class="w-36">
                            <select name="trx_type" onchange="this.form.submit()" class="h-11 w-full px-4 rounded-2xl bg-slate-50 dark:bg-white/[0.03] border border-transparent focus:border-rose-500/50 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-white/40 outline-none cursor-pointer transition-all">
                                <option value="">Type: All</option>
                                <option value="+" @selected(request()->trx_type == '+')>Type: Credit</option>
                                <option value="-" @selected(request()->trx_type == '-')>Type: Debit</option>
                            </select>
                        </div>

                        <!-- Advanced Toggle & Reset -->
                        <div class="flex items-center gap-2">
                            <button type="button" @click="showAdvanced = !showAdvanced" 
                                    :class="showAdvanced ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/30' : 'bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60'"
                                    class="h-11 px-5 rounded-2xl flex items-center gap-2 text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all">
                                <span class="material-symbols-rounded text-lg" :class="showAdvanced ? 'fill-1' : ''">tune</span>
                                {{ request()->hasAny(['user_id', 'date', 'min_amount', 'max_amount', 'remark']) ? 'Filters Active' : 'Advanced' }}
                            </button>
                            
                            <a href="{{ route('admin.report.transaction') }}" class="h-11 w-11 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-400 flex items-center justify-center hover:text-rose-500 transition-all" title="Clear All Intelligence">
                                <span class="material-symbols-rounded text-xl">restart_alt</span>
                            </a>
                        </div>
                    </div>

                    <!-- Advanced Intelligence Matrix -->
                    <div x-show="showAdvanced" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-6 rounded-[2rem] bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5" x-cloak>
                        
                        <!-- User Node -->
                        <div>
                            <label class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1.5 block px-1">Identity Node</label>
                            <select name="user_id" class="h-10 w-full px-4 rounded-xl bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60 outline-none">
                                <option value="">Select User...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected(request()->user_id == $user->id)>{{ $user->username }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Window -->
                        <div>
                            <label class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1.5 block px-1">Temporal Window</label>
                            <input name="date" type="text" class="date-range h-10 w-full px-4 rounded-xl bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60 outline-none cursor-pointer" placeholder="Date Range..." value="{{ request()->date }}">
                        </div>

                        <!-- Amount Delta -->
                        <div class="lg:col-span-1">
                            <label class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1.5 block px-1">Quantum Range (Min-Max)</label>
                            <div class="flex items-center gap-2">
                                <input type="number" step="any" name="min_amount" value="{{ request()->min_amount }}" class="h-10 w-full px-3 rounded-xl bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60 outline-none" placeholder="Min">
                                <span class="w-2 h-[1px] bg-slate-300 dark:bg-white/10"></span>
                                <input type="number" step="any" name="max_amount" value="{{ request()->max_amount }}" class="h-10 w-full px-3 rounded-xl bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60 outline-none" placeholder="Max">
                            </div>
                        </div>

                        <!-- Remark -->
                        <div>
                            <label class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1.5 block px-1">Operation Remark</label>
                            <select name="remark" class="h-10 w-full px-4 rounded-xl bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 text-[10px] font-bold text-slate-600 dark:text-white/60 outline-none">
                                <option value="">Select Remark...</option>
                                @foreach($remarks as $remark)
                                    <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>{{ __(keyToTitle($remark->remark)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Apply Advanced Button -->
                        <div class="md:col-span-2 lg:col-span-4 pt-4 border-t border-slate-100 dark:border-white/5 flex justify-end">
                            <button type="submit" class="h-10 px-8 rounded-xl bg-rose-500 text-white text-[10px] font-black uppercase tracking-[0.2em] hover:bg-rose-600 hover:shadow-lg hover:shadow-rose-500/30 transition-all flex items-center gap-2">
                                <span class="material-symbols-rounded text-sm">filter_alt</span>
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
                <table class="w-full text-left border-collapse">
                        <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Identity')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Transaction Code')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Temporal Stamp')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Quantum Shift')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('New Equilibrium')</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Protocol Details')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($transactions as $trx)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center text-[10px] font-black text-slate-400 uppercase ">
                                                {{ substr($trx->user->firstname, 0, 1) }}{{ substr($trx->user->lastname, 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="text-[11px] font-black text-slate-900 dark:text-white uppercase leading-none">{{ $trx->user->fullname }}</h4>
                                                <a href="{{ appendQuery('search',$trx->user->username) }}" class="text-[9px] font-black text-rose-500 uppercase tracking-widest mt-1 hover:underline">@ {{ $trx->user->username }}</a>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        <span class="px-2 py-1 rounded-md bg-slate-100 dark:bg-white/5 font-mono text-[10px] tracking-tighter">{{ $trx->trx }}</span>
                                    </td>

                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                        {{ showDateTime($trx->created_at) }}
                                        <div class="text-[9px] text-slate-400 font-medium mt-0.5">{{ diffForHumans($trx->created_at) }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="text-[11px] font-black @if($trx->trx_type == '+') text-emerald-500 @else text-rose-500 @endif">
                                            {{ $trx->trx_type }} {{showAmount($trx->amount)}}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-[11px] font-black text-slate-700 dark:text-white/70 ">
                                        {{ showAmount($trx->post_balance) }}
                                    </td>

                                    <td class="px-6 py-4 text-[10px] font-bold text-slate-500 dark:text-white/40 leading-tight max-w-xs">{{ __($trx->details) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-20 text-center opacity-20 text-[11px] font-black uppercase tracking-widest" colspan="100%">No transactions detected in current segment.</td>
                                </tr>
                            @endforelse

                    </tbody>
                </table>
            </div>

            <!-- App View (Grid) -->
            <div class="px-6 pb-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 mt-6">
                    @forelse($transactions as $trx)
                        <div class="bg-white dark:bg-[#1a1a1a] rounded-[2.5rem] border border-slate-200 dark:border-white/10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden relative group">
                            <!-- Top Status Line -->
                            <div class="absolute top-0 inset-x-0 h-1 @if($trx->trx_type == '+') bg-emerald-500 @else bg-rose-500 @endif opacity-30 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="p-8">
                                <!-- User Info -->
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-xs font-black text-slate-400 uppercase ">
                                        {{ substr($trx->user->firstname, 0, 1) }}{{ substr($trx->user->lastname, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase leading-none truncate">{{ $trx->user->fullname }}</h4>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">@ {{ $trx->user->username }}</p>
                                    </div>
                                </div>

                                <!-- Transaction Value -->
                                <div class="mb-6 p-6 rounded-[1.8rem] @if($trx->trx_type == '+') bg-emerald-500/5 @else bg-rose-500/5 @endif border @if($trx->trx_type == '+') border-emerald-500/10 @else border-rose-500/10 @endif text-center">
                                    <span class="block text-[8px] font-black uppercase tracking-[0.2em] @if($trx->trx_type == '+') text-emerald-500 @else text-rose-500 @endif mb-2">Quantum Shift</span>
                                    <h2 class="text-2xl font-black @if($trx->trx_type == '+') text-emerald-500 @else text-rose-500 @endif">
                                        {{ $trx->trx_type }} {{showAmount($trx->amount)}}
                                    </h2>
                                </div>

                                <!-- Metrics Grid -->
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <span class="block text-[8px] font-black uppercase tracking-widest text-slate-400 mb-1">Equilibrium</span>
                                        <span class="block text-[11px] font-black text-slate-700 dark:text-white/70 ">{{ showAmount($trx->post_balance) }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-[8px] font-black uppercase tracking-widest text-slate-400 mb-1">Temporal Stamp</span>
                                        <span class="block text-[9px] font-bold text-slate-700 dark:text-white/70">{{ $trx->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <!-- TRX Code & Details -->
                                <div class="pt-6 border-t border-slate-100 dark:border-white/5">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Protocol TRX</span>
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-white/5 font-mono text-[9px] text-slate-500 dark:text-white/30">{{ $trx->trx }}</span>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 leading-relaxed line-clamp-2">"{{ __($trx->details) }}"</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-40 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[3.5rem]">
                             <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-6xl">payments</span>
                             <p class="mt-6 text-[11px] font-black text-slate-400 uppercase tracking-widest ">No transactions detected in current segment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @if($transactions->hasPages())
        <div class="mt-8 p-8 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/[0.01] rounded-b-[2rem]">
            {{ paginateLinks($transactions) }}
        </div>
        @endif
    </div><!-- card end -->
</div>
</div>

@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script>
        (function($){
            "use strict"

            const datePicker = $('.date-range').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                },
                showDropdowns: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                },
                maxDate: moment()
            });
            const changeDatePickerText = (event, startDate, endDate) => {
                $(event.target).val(startDate.format('MMMM DD, YYYY') + ' - ' + endDate.format('MMMM DD, YYYY'));
            }


            $('.date-range').on('apply.daterangepicker', (event, picker) => changeDatePickerText(event, picker.startDate, picker.endDate));


            if ($('.date-range').val()) {
                let dateRange = $('.date-range').val().split(' - ');
                $('.date-range').data('daterangepicker').setStartDate(new Date(dateRange[0]));
                $('.date-range').data('daterangepicker').setEndDate(new Date(dateRange[1]));
            }

        })(jQuery)
    </script>
@endpush


