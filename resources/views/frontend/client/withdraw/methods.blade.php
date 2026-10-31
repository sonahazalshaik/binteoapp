<x-app-layout>
    <div class="min-h-screen bg-[#FDFDFF] dark:bg-[#080808] transition-colors duration-500 pb-20 sm:pb-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 pt-6 sm:pt-8" x-data="withdrawForm()" x-init="$watch('selectedMethod', () => { $el.querySelector('input[name=amount]')?.focus() })">
            
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <a href="{{ route('studio.dashboard') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 text-gray-400 hover:text-red-500 transition-all active:scale-90 shadow-sm">
                        <span class="material-symbols-rounded text-xl">arrow_back_ios_new</span>
                    </a>
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight leading-none">Withdraw</h1>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Wallet Payout</p>
                    </div>
                </div>
                
                <a href="{{ route('user.withdraw.log') }}" class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 text-[10px] font-black text-gray-500 uppercase tracking-widest hover:border-red-500/50 hover:text-red-500 transition-all shadow-sm">
                    <span class="material-symbols-rounded text-base">receipt_long</span>
                    <span class="hidden sm:inline">History</span>
                </a>
            </div>

            <!-- Sleeker Balance Bar -->
            <div class="bg-white dark:bg-[#111111] rounded-3xl p-6 border border-gray-100 dark:border-white/5 shadow-sm mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-500/10 flex items-center justify-center text-red-500">
                        <span class="material-symbols-rounded text-2xl">account_balance_wallet</span>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Available Balance</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ showAmount(auth()->user()->balance) }}</p>
                    </div>
                </div>
                <div class="hidden sm:block h-8 w-[1px] bg-gray-100 dark:bg-white/5 mx-4"></div>
                <div class="flex items-center gap-3 bg-gray-50 dark:bg-white/2 px-4 py-2 rounded-2xl border border-gray-100 dark:border-white/5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Verified Wallet</span>
                </div>
            </div>

            <form action="{{ route('user.withdraw.submit') }}" method="POST" class="withdraw-form space-y-8" @submit.prevent="submitForm">
                @csrf
                
                <!-- Step 1: Methods -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 px-1">
                        <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">1. Select Payout Method</h2>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @forelse($withdrawMethod as $method)
                        <label class="relative cursor-pointer group" @click="selectedMethod = '{{ $method->id }}'">
                            <input type="radio" name="method_code" value="{{ $method->id }}" class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" x-model="selectedMethod">
                            <div class="h-full bg-white dark:bg-[#111111] p-4 rounded-2xl border-2 transition-all shadow-sm hover:shadow-md flex flex-col items-center text-center"
                                 :class="selectedMethod == '{{ $method->id }}' ? 'border-red-500 bg-red-50/20 dark:bg-red-500/5' : 'border-transparent'">
                                <div class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-white/5 flex items-center justify-center mb-3 transition-transform group-hover:scale-105">
                                    @if($method->image)
                                        <img src="{{ getImage($method->image) }}" class="w-8 h-8 object-contain">
                                    @else
                                        <span class="material-symbols-rounded text-xl text-gray-400">payments</span>
                                    @endif
                                </div>
                                <h4 class="font-black text-gray-900 dark:text-white text-[11px] uppercase tracking-tight leading-tight mb-1">{{ $method->name }}</h4>
                                
                                <div class="space-y-1 mt-1">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <span class="text-[7px] font-black text-gray-400 uppercase tracking-widest">Charge:</span>
                                        <span class="text-[8px] font-bold text-red-500">{{ showAmount($method->fixed_charge) }} + {{ getAmount($method->percent_charge) }}%</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-[7px] font-black text-gray-400 uppercase tracking-[0.2em] mb-0.5">Limits</span>
                                        <span class="text-[8px] font-bold text-gray-600 dark:text-gray-300 leading-none">
                                            {{ showAmount($method->min_limit, currencyFormat: false) }} - {{ showAmount($method->max_limit, currencyFormat: false) }} {{ $method->currency }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="absolute top-2 right-2 transition-transform" :class="selectedMethod == '{{ $method->id }}' ? 'scale-100' : 'scale-0'">
                                    <div class="w-4 h-4 rounded-full bg-red-500 flex items-center justify-center text-white shadow-lg">
                                        <span class="material-symbols-rounded text-[10px] font-black">check</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @empty
                        @endforelse
                    </div>
                </div>

                <div class="max-w-md">
                    <!-- Step 2: Amount -->
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center gap-2 px-1">
                            <h2 class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">2. Amount to Payout</h2>
                        </div>

                        <div class="bg-white dark:bg-[#111111] rounded-2xl p-3 border border-gray-100 dark:border-white/5 shadow-sm">
                            <div class="relative flex items-center bg-gray-50 dark:bg-white/5 rounded-xl border border-transparent focus-within:border-red-500/30 transition-colors p-1.5">
                                <span class="pl-3 pr-1 text-base font-black text-gray-500 dark:text-gray-400">{{ $general->cur_sym }}</span>
                                <input type="number" name="amount" step="1" placeholder="0.00" 
                                       class="flex-1 bg-transparent border-none focus:ring-0 text-base font-black text-gray-900 dark:text-white placeholder:text-gray-300 dark:placeholder:text-gray-600 py-1.5 px-0" 
                                       required data-no-hint>
                                <button type="button" 
                                        class="ml-2 px-3 py-1.5 rounded-lg text-[9px] font-bold uppercase tracking-wider transition-colors"
                                        :class="selectedMethod && methods[selectedMethod] && userBalance < methods[selectedMethod].min ? 'bg-gray-200 dark:bg-white/5 text-gray-400 dark:text-gray-600 cursor-not-allowed' : 'bg-gray-900 dark:bg-white text-white dark:text-black hover:bg-red-500 hover:text-white'"
                                        :disabled="selectedMethod && methods[selectedMethod] && userBalance < methods[selectedMethod].min"
                                        @click="$el.form.amount.value = Math.floor(selectedMethod && methods[selectedMethod] ? Math.min(userBalance, methods[selectedMethod].max) : userBalance)">
                                    Max
                                </button>
                            </div>
                            
                            <div class="mt-3 flex items-center gap-1.5 text-gray-400 justify-center">
                                <span class="material-symbols-rounded text-[13px] text-emerald-500">lock</span>
                                <span class="text-[9px] font-bold uppercase tracking-widest">Secure 256-bit Encrypted Transfer</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-2">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('studio.dashboard') }}" class="w-1/3 py-3 bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white rounded-xl font-black text-[11px] uppercase tracking-[0.2em] hover:bg-gray-200 dark:hover:bg-white/10 transition-all active:scale-[0.98] flex items-center justify-center">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="flex-1 py-3 rounded-xl font-black text-[11px] uppercase tracking-[0.2em] flex items-center justify-center gap-2 transition-all active:scale-[0.98]"
                                    :class="selectedMethod && methods[selectedMethod] && userBalance < methods[selectedMethod].min ? 'bg-gray-300 dark:bg-white/10 text-gray-500 dark:text-gray-600 cursor-not-allowed shadow-none' : 'bg-red-500 text-white shadow-md shadow-red-500/20 hover:bg-red-600'"
                                    :disabled="selectedMethod && methods[selectedMethod] && userBalance < methods[selectedMethod].min">
                                Confirm Payout
                                <span class="material-symbols-rounded text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </button>
                        </div>
                        <p class="text-center text-[9px] font-bold text-gray-400 mt-2.5 flex items-center justify-center gap-1">
                            <span class="material-symbols-rounded text-[12px]">schedule</span>
                            Usually processed within 24 hours
                        </p>
                    </div>
                </div>
            </form>

        </div>
    </div>

    @push('script')
    <script>
        function withdrawForm() {
            const userBalance = {{ auth()->user()->balance }};
            const methods = @json($withdrawMethod->mapWithKeys(fn($m) => [$m->id => ['min' => (float) $m->min_limit, 'max' => (float) $m->max_limit]]));
            return {
                selectedMethod: null,
                userBalance: userBalance,
                methods: methods,
                getEffectiveMax() {
                    if (!this.selectedMethod || !this.methods[this.selectedMethod]) return this.userBalance;
                    return Math.min(this.userBalance, this.methods[this.selectedMethod].max);
                },
                getEffectiveMin() {
                    if (!this.selectedMethod || !this.methods[this.selectedMethod]) return 0;
                    return this.methods[this.selectedMethod].min;
                },
                submitForm() {
                    const amount = this.$el.amount.value;
                    const isDark = document.documentElement.classList.contains('dark');
                    const bgColor = isDark ? '#0F0F0F' : '#ffffff';
                    const textColor = isDark ? '#ffffff' : '#000000';

                    if (!amount || amount <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Amount',
                            text: 'Enter a valid amount.',
                            background: bgColor,
                            color: textColor,
                            customClass: { popup: 'rounded-2xl' }
                        });
                        return;
                    }

                    if (!this.selectedMethod) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Selection Required',
                            text: 'Choose a payout method first.',
                            background: bgColor,
                            color: textColor,
                            customClass: { popup: 'rounded-2xl' }
                        });
                        return;
                    }

                    if (this.methods[this.selectedMethod] && amount < this.methods[this.selectedMethod].min) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Below Minimum',
                            text: `Minimum withdrawal for this method is ${this.methods[this.selectedMethod].min.toLocaleString()} {{ $general->cur_text }}.`,
                            background: bgColor,
                            color: textColor,
                            customClass: { popup: 'rounded-2xl' }
                        });
                        return;
                    }

                    if (this.methods[this.selectedMethod] && amount > this.methods[this.selectedMethod].max) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Above Maximum',
                            text: `Maximum withdrawal for this method is ${this.methods[this.selectedMethod].max.toLocaleString()} {{ $general->cur_text }}.`,
                            background: bgColor,
                            color: textColor,
                            customClass: { popup: 'rounded-2xl' }
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Confirm Payout',
                        text: `Withdraw ${amount} {{ $general->cur_text }}?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Yes, Withdraw',
                        background: bgColor,
                        color: textColor,
                        customClass: {
                            popup: 'rounded-3xl border border-gray-100 dark:border-white/5',
                            title: 'font-black tracking-tight'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Processing Request',
                                html: `
                                    <div class="flex flex-col items-center justify-center p-6">
                                        <div class="relative w-24 h-24 mb-6">
                                            <div class="absolute inset-0 border-8 border-red-500/10 rounded-full"></div>
                                            <div class="absolute inset-0 border-8 border-t-red-500 rounded-full animate-spin shadow-[0_0_20px_rgba(239,68,68,0.3)]"></div>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <span class="material-symbols-rounded text-red-500 animate-pulse text-3xl">lock</span>
                                            </div>
                                        </div>
                                        <div class="space-y-2 text-center">
                                            <p class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-[0.2em]">Securing Connection</p>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Encrypting transaction data...</p>
                                        </div>
                                    </div>
                                `,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                background: bgColor,
                                customClass: { popup: 'rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-white/10' }
                            });
                            setTimeout(() => { this.$el.submit(); }, 1200);
                        }
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
