<x-app-layout>
    <div x-data="transactionManager()" 
         x-init="init()"
         @open-trx-detail.window="selectedTrx = $event.detail; showDetailModal = true;"
         class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6">
            
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">All Transactions</h1>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Ledger of account activity
                    </p>
                </div>

                <!-- Sleek & Compact Filter -->
                <div class="relative self-start sm:self-auto" @click.outside="openFilter = false">
                    <button @click="openFilter = !openFilter" 
                            type="button"
                            class="px-4 py-2 sm:px-5 sm:py-2.5 bg-white dark:bg-[#181818] rounded-xl border border-gray-200 dark:border-white/10 shadow-sm text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2 hover:border-red-500/40 active:scale-95 transition-all">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Filter</span>
                        <template x-if="filterLabel !== 'All'">
                            <span class="px-1.5 py-0.5 rounded-md text-[9px] bg-red-500 text-white font-black uppercase" x-text="filterLabel"></span>
                        </template>
                        <svg class="w-3.5 h-3.5 opacity-50 transition-transform duration-200" :class="openFilter ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <!-- Compact Filter Dropdown Menu -->
                    <div x-show="openFilter" 
                         x-cloak 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                         class="absolute left-0 sm:left-auto sm:right-0 mt-2 w-64 bg-white dark:bg-[#181818] rounded-2xl border border-gray-200/80 dark:border-white/10 shadow-xl p-2 z-[100] space-y-1">
                        
                        <!-- Header -->
                        <div class="flex items-center justify-between px-2 py-1 border-b border-gray-100 dark:border-white/5">
                            <span class="text-[10px] font-black uppercase tracking-wider text-gray-400">Filter By</span>
                            <button @click="resetFilters()" 
                                    type="button" 
                                    x-show="currentTrxType !== '' || currentRemark !== '' || search !== ''"
                                    class="text-[10px] font-bold text-red-500 hover:underline">
                                Reset
                            </button>
                        </div>

                        <!-- Options List -->
                        <div class="space-y-0.5">
                            <!-- All Operations -->
                            <button type="button" 
                                    @click="selectFilter('', '', 'All')"
                                    class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs transition-colors"
                                    :class="currentTrxType === '' && currentRemark === '' ? 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 font-semibold'">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-300 flex items-center justify-center">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                    </span>
                                    <span>All Transactions</span>
                                </div>
                                <template x-if="currentTrxType === '' && currentRemark === ''">
                                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </template>
                            </button>

                            <!-- Inbound Credits (+) -->
                            <button type="button" 
                                    @click="selectFilter('+', '', 'Credits (+)')"
                                    class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs transition-colors"
                                    :class="currentTrxType === '+' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 font-semibold'">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-black">+</span>
                                    <span>Credits (+)</span>
                                </div>
                                <template x-if="currentTrxType === '+'">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </template>
                            </button>

                            <!-- Outbound Debits (-) -->
                            <button type="button" 
                                    @click="selectFilter('-', '', 'Debits (-)')"
                                    class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs transition-colors"
                                    :class="currentTrxType === '-' ? 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 font-bold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 font-semibold'">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-rose-500/15 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xs font-black">-</span>
                                    <span>Debits (-)</span>
                                </div>
                                <template x-if="currentTrxType === '-'">
                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </template>
                            </button>
                        </div>

                        @if(isset($remarks) && count($remarks) > 0)
                        <!-- Categories Section -->
                        <div class="pt-1 border-t border-gray-100 dark:border-white/5">
                            <p class="px-2 py-0.5 text-[9px] font-black text-gray-400 uppercase tracking-wider">Categories</p>
                            <div class="max-h-28 overflow-y-auto space-y-0.5 pr-0.5">
                                @foreach($remarks as $remarkItem)
                                <button type="button" 
                                        @click="selectFilter('', '{{ $remarkItem }}', '{{ __(keyToTitle($remarkItem)) }}')"
                                        class="w-full flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs transition-colors"
                                        :class="currentRemark === '{{ $remarkItem }}' ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 font-medium'">
                                    <span class="truncate">{{ __(keyToTitle($remarkItem)) }}</span>
                                    <template x-if="currentRemark === '{{ $remarkItem }}'">
                                        <svg class="w-3.5 h-3.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Compact Search -->
                        <div class="pt-1 border-t border-gray-100 dark:border-white/5">
                            <div class="relative">
                                <input type="text" 
                                       x-model="search" 
                                       @keydown.enter.prevent="submitSearch()"
                                       placeholder="Search ID / note..." 
                                       class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg pl-7 pr-7 py-1.5 text-xs text-gray-900 dark:text-white outline-none focus:border-red-500">
                                <svg class="w-3.5 h-3.5 absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <button type="button" @click="submitSearch()" class="absolute right-1.5 top-1/2 -translate-y-1/2 p-0.5 text-gray-400 hover:text-red-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Transactions Ledger Core Container -->
            <div class="relative">
                <!-- Loading State Overlay -->
                <div x-show="loading" 
                     x-cloak 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 bg-white/70 dark:bg-[#0F0F0F]/70 backdrop-blur-[2px] z-20 flex items-center justify-center rounded-[2.5rem]">
                    <div class="flex items-center gap-3 px-6 py-3 rounded-full bg-white dark:bg-[#181818] shadow-xl border border-gray-100 dark:border-white/10">
                        <svg class="animate-spin h-5 w-5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest text-gray-700 dark:text-gray-300">Loading Entries...</span>
                    </div>
                </div>

                <!-- Async Rendered Table & Cards -->
                <div id="transactions-table-container">
                    @include('frontend.client.partials.transactions_table')
                </div>
            </div>

            <!-- Transaction Detail Popup Modal (Alpine Teleport to Body) -->
            <template x-teleport="body">
                <div x-show="showDetailModal" 
                     x-cloak
                     class="fixed inset-0 z-[999999] flex items-center justify-center p-4">
                    
                    <!-- Backdrop -->
                    <div x-show="showDetailModal"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="showDetailModal = false"
                         class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>

                    <!-- Panel Card -->
                    <div x-show="showDetailModal"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                         class="relative w-full max-w-lg bg-white dark:bg-[#18181b] rounded-[24px] sm:rounded-[28px] shadow-2xl border border-slate-100 dark:border-white/10 overflow-hidden z-10 p-6 sm:p-7">

                        <!-- Header -->
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white shadow-md shrink-0"
                                     :class="selectedTrx?.trx_type === '+' ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-rose-500 shadow-rose-500/20'">
                                    <template x-if="selectedTrx?.trx_type === '+'">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                        </svg>
                                    </template>
                                    <template x-if="selectedTrx?.trx_type !== '+'">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                        </svg>
                                    </template>
                                </div>
                                <div>
                                    <h3 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight">Transaction Detail</h3>
                                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest" x-text="selectedTrx?.time_ago"></p>
                                </div>
                            </div>

                            <button @click="showDetailModal = false" 
                                    type="button"
                                    class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#ff5200] to-orange-500 hover:from-orange-600 hover:to-amber-500 text-white shadow-sm shadow-orange-500/30 flex items-center justify-center transition-all active:scale-95">
                                <svg class="w-3.5 h-3.5 stroke-white stroke-[2.5]" fill="none" viewBox="0 0 24 24">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>

                        <!-- Amount Banner -->
                        <div class="my-5 p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/5 flex items-center justify-between">
                            <div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 block mb-0.5">Amount</span>
                                <span class="text-2xl font-black" 
                                      :class="selectedTrx?.trx_type === '+' ? 'text-emerald-500' : 'text-rose-500'" 
                                      x-text="(selectedTrx?.trx_type || '') + ' ' + (selectedTrx?.amount || '0')">
                                </span>
                            </div>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider"
                                  :class="selectedTrx?.trx_type === '+' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-500 border border-rose-500/20'">
                                Settled
                            </span>
                        </div>

                        <!-- Key-Value Breakdown Grid -->
                        <div class="space-y-3 text-xs">
                            <!-- TRX Hash -->
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-white/[0.02]">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Transaction ID</span>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-slate-900 dark:text-white text-xs select-all" x-text="selectedTrx?.trx"></span>
                                    <button @click="copyTrx(selectedTrx?.trx)" class="p-1 rounded text-slate-400 hover:text-orange-500 transition-colors" title="Copy Transaction ID">
                                        <span class="material-symbols-rounded text-sm" x-text="copied ? 'check' : 'content_copy'"></span>
                                    </button>
                                </div>
                            </div>

                            <!-- Date & Time -->
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-white/[0.02]">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Date & Time</span>
                                <span class="font-bold text-slate-700 dark:text-slate-300" x-text="selectedTrx?.date"></span>
                            </div>

                            <!-- Post Balance -->
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-white/[0.02]">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Post Balance</span>
                                <span class="font-black text-slate-900 dark:text-white" x-text="selectedTrx?.post_balance"></span>
                            </div>

                            <!-- Fee / Charge -->
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-white/[0.02]">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Fee / Charge</span>
                                <span class="font-bold text-slate-600 dark:text-slate-400" x-text="selectedTrx?.charge || '0'"></span>
                            </div>

                            <!-- Category / Remark -->
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-white/[0.02]">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Category</span>
                                <span class="font-bold text-slate-700 dark:text-slate-300" x-text="selectedTrx?.remark"></span>
                            </div>

                            <!-- Full Description / Signature -->
                            <div class="p-3.5 rounded-xl bg-orange-50/60 dark:bg-orange-950/20 border border-orange-200/50 dark:border-orange-500/10">
                                <span class="text-[9px] font-black uppercase tracking-widest text-orange-600 dark:text-orange-400 block mb-1">Details & Remarks</span>
                                <p class="font-bold text-xs text-slate-800 dark:text-slate-200 leading-relaxed" x-text="selectedTrx?.details"></p>
                            </div>
                        </div>

                        <!-- Footer CTA -->
                        <div class="mt-6 flex justify-end">
                            <button @click="showDetailModal = false"
                                    type="button"
                                    class="px-6 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-white/10 dark:hover:bg-white/20 text-slate-700 dark:text-white font-black text-xs uppercase tracking-wider transition-all active:scale-95">
                                Close
                            </button>
                        </div>

                    </div>
                </div>
            </template>

        </div>
    </div>

    <!-- Manager Script -->
    <script>
        function transactionManager() {
            return {
                openFilter: false,
                currentTrxType: '{{ request()->trx_type ?? '' }}',
                currentRemark: '{{ request()->remark ?? '' }}',
                search: '{{ request()->search ?? '' }}',
                filterLabel: '{{ request()->trx_type == '+' ? 'Credits (+)' : (request()->trx_type == '-' ? 'Debits (-)' : (request()->remark ? __(keyToTitle(request()->remark)) : 'All')) }}',
                loading: false,
                showDetailModal: false,
                selectedTrx: null,
                copied: false,

                init() {
                    const container = document.getElementById('transactions-table-container');
                    if (container) {
                        // Event delegation for pagination links inside the container
                        container.addEventListener('click', (e) => {
                            const link = e.target.closest('.ajax-pagination a, .pagination a');
                            if (link && link.href) {
                                e.preventDefault();
                                this.fetchTransactions(link.href);
                            }
                        });
                    }
                },

                selectFilter(type, remark, label) {
                    this.currentTrxType = type;
                    this.currentRemark = remark;
                    this.filterLabel = label;
                    this.openFilter = false;
                    this.fetchTransactions();
                },

                submitSearch() {
                    this.openFilter = false;
                    this.fetchTransactions();
                },

                resetFilters() {
                    this.currentTrxType = '';
                    this.currentRemark = '';
                    this.search = '';
                    this.filterLabel = 'All';
                    this.openFilter = false;
                    this.fetchTransactions();
                },

                copyTrx(text) {
                    if (!text) return;
                    navigator.clipboard.writeText(text);
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                },

                fetchTransactions(customUrl = null) {
                    this.loading = true;
                    let url = customUrl;
                    if (!url) {
                        const params = new URLSearchParams();
                        if (this.currentTrxType) params.append('trx_type', this.currentTrxType);
                        if (this.currentRemark) params.append('remark', this.currentRemark);
                        if (this.search) params.append('search', this.search);
                        params.append('ajax', '1');
                        url = '{{ route('user.transactions') }}?' + params.toString();
                    } else {
                        if (!url.includes('ajax=1')) {
                            url += (url.includes('?') ? '&' : '?') + 'ajax=1';
                        }
                    }

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html, application/xhtml+xml'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Network response was not ok');
                        return res.text();
                    })
                    .then(html => {
                        const container = document.getElementById('transactions-table-container');
                        if (container) {
                            container.innerHTML = html;
                            if (window.Alpine) {
                                Alpine.initTree(container);
                            }
                        }
                        this.loading = false;
                    })
                    .catch(err => {
                        console.error('Failed to load transactions:', err);
                        this.loading = false;
                    });
                }
            };
        }
    </script>
</x-app-layout>
