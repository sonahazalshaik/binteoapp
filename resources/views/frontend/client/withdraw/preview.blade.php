<x-app-layout>
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[750px] mx-auto px-4 sm:px-6">
            <!-- Header -->
            <div class="mb-12">
                <a href="{{ route('user.withdraw.methods') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 hover:text-red-500 transition-all">
                    <span class="material-symbols-rounded text-lg">arrow_back</span>
                    Back to Methods
                </a>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter">Final Verification</h1>
                <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Payout processing via {{ $withdraw->method->name }}
                </p>
            </div>

            <div class="bg-white dark:bg-[#181818] rounded-[3.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden transition-all duration-500">
                <div class="p-8 sm:p-12">
                    <!-- Summary Card -->
                    <div class="bg-gray-900 dark:bg-white rounded-[2.5rem] p-10 text-white dark:text-black mb-12 relative overflow-hidden shadow-2xl">
                        <!-- Decorative Glow -->
                        <div class="absolute top-0 right-0 p-10 opacity-10">
                            <span class="material-symbols-rounded text-8xl">verified_user</span>
                        </div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-10 pb-8 border-b border-white/10 dark:border-black/5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40">Request Balance</span>
                                    <span class="text-2xl font-black tracking-tighter">{{ showAmount($withdraw->amount) }}</span>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <span class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40">Service Charge</span>
                                    <span class="text-sm font-black text-red-500 dark:text-red-600">-{{ showAmount($withdraw->charge) }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[11px] font-black uppercase tracking-[0.3em] opacity-40">Final Disbursement</span>
                                    <p class="text-[9px] font-bold opacity-30 uppercase tracking-widest">Rate: 1 {{ __($general->cur_text) }} = {{ showAmount($withdraw->rate, currencyFormat: false) }} {{ $withdraw->currency }}</p>
                                </div>
                                <span class="text-4xl font-black text-emerald-500 dark:text-emerald-600 tracking-tighter">
                                    {{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ $withdraw->currency }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('user.withdraw.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
                        @csrf
                        
                        @if($withdraw->method->description)
                        <div class="p-8 bg-red-600/5 rounded-[2.5rem] border border-red-500/10 relative overflow-hidden group">
                             <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                                 <span class="material-symbols-rounded text-8xl text-red-600">contact_support</span>
                             </div>
                             <div class="relative z-10">
                                <h5 class="text-[10px] font-black text-red-600 uppercase tracking-[0.3em] mb-4 flex items-center gap-3">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Transfer Instructions
                                </h5>
                                <div class="text-[11px] font-bold text-gray-600 dark:text-gray-400 leading-loose uppercase tracking-[0.1em]">
                                    @php echo $withdraw->method->description; @endphp
                                </div>
                             </div>
                        </div>
                        @endif

                        <div class="space-y-10">
                            <div class="premium-form-container">
                                <x-viser-form identifier="id" identifierValue="{{ $withdraw->method->form_id }}" />
                            </div>

                            @if (auth()->user()->ts)
                                <div class="space-y-3">
                                    <label class="px-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] block">2FA Security Protocol</label>
                                    <div class="relative group">
                                        <span class="material-symbols-rounded absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-red-500 transition-colors">security</span>
                                        <input type="text" name="authenticator_code" placeholder="Enter 6-digit Code" required 
                                               class="w-full bg-gray-50 dark:bg-white/2 border border-gray-100 dark:border-white/5 rounded-[1.5rem] pl-14 pr-8 py-5 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all">
                                    </div>
                                    <p class="px-5 text-[9px] font-black text-amber-500 uppercase tracking-widest flex items-center gap-2">
                                        <span class="material-symbols-rounded text-sm">lock_person</span>
                                        Google Authenticator Verification Required
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-6 bg-red-600 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.4em] shadow-2xl shadow-red-500/40 hover:bg-red-700 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-4">
                                Confirm & Dispatch
                                <span class="material-symbols-rounded">cloud_done</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] max-w-sm mx-auto leading-loose ">
                    By confirming this disbursement, you verify that all provided payout credentials are accurate and authorized.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>

