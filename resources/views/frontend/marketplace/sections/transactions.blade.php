<!-- Tab: Transactions -->
<div x-show="tab === 'transactions'" x-cloak x-transition>
    <div class="bg-white dark:bg-[#111] p-6 md:p-12 rounded-[2rem] md:rounded-[3rem] border border-slate-200 dark:border-white/5 shadow-sm overflow-hidden">
        <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white tracking-tighter mb-8 md:mb-10">Transaction History</h2>
        
        <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-white/5">
            <table class="w-full text-left min-w-[800px]">
                <thead class="bg-slate-50 dark:bg-white/5">
                    <tr>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">#</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Transaction ID</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Plan</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Payment</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-5 text-sm font-bold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="px-6 py-5 text-sm font-black text-slate-700 dark:text-white">{{ $trx->trx }}</td>
                        <td class="px-6 py-5 text-sm font-bold text-slate-600 dark:text-slate-300">{{ $trx->plan ? $trx->plan->plan_name : 'N/A' }}</td>
                        <td class="px-6 py-5 text-sm font-bold text-slate-900 dark:text-white">{{ showAmount($trx->amount, 0) }}</td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-full text-[9px] font-black uppercase tracking-widest">
                                {{ $trx->method_code == 507 ? 'Razorpay' : 'Gateway' }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            @if($trx->status == 1)
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest">Success</span>
                            @elseif($trx->status == 0)
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-widest">Pending</span>
                            @else
                                <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-black uppercase tracking-widest">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-sm font-medium text-slate-400">{{ $trx->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-5 text-center">
                            <button @click="openInvoice({{ $trx->id }}, '{{ $trx->trx }}', '{{ addslashes($trx->plan?->plan_name ?? 'N/A') }}', '{{ showAmount($trx->amount, 0) }}', '{{ $trx->method_code == 507 ? 'Razorpay' : 'Gateway' }}', '{{ $trx->status }}', '{{ $trx->created_at->format('M d, Y') }}', '{{ addslashes($client->name) }}', '{{ addslashes($client->email) }}', '{{ addslashes($client->business_name ?? '') }}')" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 hover:text-indigo-600 transition-all active:scale-90 text-[10px] font-black uppercase tracking-widest">
                                <span class="material-symbols-rounded text-base">receipt_long</span>
                                Invoice
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <span class="material-symbols-rounded text-5xl opacity-30">account_balance_wallet</span>
                                <p class="text-sm font-bold uppercase tracking-widest">No transactions yet</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Invoice Modal -->
    <template x-teleport="body">
        <div x-show="invoiceOpen" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="invoiceOpen = false"></div>
            <div class="relative w-full max-w-2xl bg-white dark:bg-[#151515] rounded-[2rem] shadow-2xl border border-slate-100 dark:border-white/5 overflow-y-auto max-h-[90vh]" x-transition>
                <div class="p-6 md:p-10">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Invoice</h2>
                        <button @click="invoiceOpen = false" class="w-9 h-9 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                            <span class="material-symbols-rounded">close</span>
                        </button>
                    </div>

                    <div class="border border-slate-100 dark:border-white/5 rounded-2xl divide-y divide-slate-100 dark:divide-white/5">
                        {{-- Invoice Header --}}
                        <div class="p-5 md:p-6 flex items-center justify-between bg-slate-50 dark:bg-white/[0.02] rounded-t-2xl">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ __($general->site_name ?? 'Site') }}</p>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-400">Payment Receipt</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Invoice #</p>
                                <p class="text-sm font-black text-slate-900 dark:text-white" x-text="inv.trx"></p>
                            </div>
                        </div>

                        {{-- Vendor Details --}}
                        <div class="p-5 md:p-6">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Vendor Details</p>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400">Name</p>
                                    <p class="text-sm font-black text-slate-900 dark:text-white" x-text="inv.vendorName"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400">Email</p>
                                    <p class="text-sm font-black text-slate-900 dark:text-white" x-text="inv.vendorEmail"></p>
                                </div>
                                <div x-show="inv.vendorBusiness" class="col-span-2">
                                    <p class="text-[10px] font-bold text-slate-400">Business</p>
                                    <p class="text-sm font-black text-slate-900 dark:text-white" x-text="inv.vendorBusiness"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Transaction Details --}}
                        <div class="p-5 md:p-6">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Transaction Details</p>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400">Transaction ID</p>
                                    <p class="text-sm font-black text-slate-900 dark:text-white" x-text="inv.trx"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400">Date</p>
                                    <p class="text-sm font-black text-slate-900 dark:text-white" x-text="inv.date"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400">Payment Method</p>
                                    <p class="text-sm font-black text-slate-900 dark:text-white" x-text="inv.payment"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400">Status</p>
                                    <p class="text-sm font-black" x-text="inv.status === '1' ? 'Success' : (inv.status === '0' ? 'Pending' : 'Rejected')" 
                                       :class="inv.status === '1' ? 'text-emerald-600' : (inv.status === '0' ? 'text-amber-600' : 'text-red-600')"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Plan Details & Amount --}}
                        <div class="p-5 md:p-6">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Plan & Billing</p>
                            <div class="flex items-center justify-between py-3">
                                <div>
                                    <p class="text-sm font-black text-slate-900 dark:text-white" x-text="inv.plan"></p>
                                    <p class="text-[10px] font-bold text-slate-400">Plan Subscription</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-slate-900 dark:text-white" x-text="inv.amount"></p>
                                    <p class="text-[10px] font-bold text-slate-400">Total Paid</p>
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="p-5 md:p-6 bg-slate-50 dark:bg-white/[0.02] rounded-b-2xl flex items-center justify-between">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Thank you for your business</p>
                            <button @click="window.print()" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all active:scale-95 shadow-lg shadow-indigo-500/20 flex items-center gap-2">
                                <span class="material-symbols-rounded text-base">print</span>
                                Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function transactionInvoice() {
        return {
            invoiceOpen: false,
            inv: {
                id: null,
                trx: '',
                plan: '',
                amount: '',
                payment: '',
                status: '',
                date: '',
                vendorName: '',
                vendorEmail: '',
                vendorBusiness: ''
            },
            openInvoice(id, trx, plan, amount, payment, status, date, vendorName, vendorEmail, vendorBusiness) {
                this.inv.id = id;
                this.inv.trx = trx;
                this.inv.plan = plan;
                this.inv.amount = amount;
                this.inv.payment = payment;
                this.inv.status = status;
                this.inv.date = date;
                this.inv.vendorName = vendorName;
                this.inv.vendorEmail = vendorEmail;
                this.inv.vendorBusiness = vendorBusiness;
                this.invoiceOpen = true;
            }
        }
    }
</script>