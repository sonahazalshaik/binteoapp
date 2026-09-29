@extends('admin.layouts.app')

@section('title', 'Add Manual Withdrawal')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Add Withdrawal</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Process a manual payout for a user</p>
        </div>
        <a href="{{ route('admin.withdraw.data.all') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90">
            <span class="material-symbols-rounded text-xl">close</span>
        </a>
    </div>

    <form action="{{ route('admin.withdraw.data.store') }}" method="POST" 
          x-data="{ 
            synching: false, 
            open: false, 
            search: '', 
            selectedId: '', 
            selectedName: 'Select User...',
            selectedBalance: '0.00',
            selectUser(id, name, balance) {
                this.selectedId = id;
                this.selectedName = name;
                this.selectedBalance = balance;
                this.open = false;
                this.search = '';
            }
          }" 
          @submit="synching = true"
          class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Panel: User Information -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <span class="material-symbols-rounded text-lg">person</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">User</h3>
                        </div>

                        <!-- Custom Searchable Select -->
                        <div class="space-y-2" @click.away="open = false">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Assigned User</label>
                            <input type="hidden" name="user_id" :value="selectedId" required>
                            
                            <div class="relative">
                                <button type="button" 
                                        @click="open = !open"
                                        class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-5 flex items-center justify-between text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                    <span x-text="selectedName" class="truncate">Select User...</span>
                                    <span class="material-symbols-rounded text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                                </button>

                                <!-- Dropdown Panel -->
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="absolute z-[100] mt-2 w-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden"
                                     x-cloak>
                                    
                                    <div class="p-3 border-b border-slate-100 dark:border-white/5">
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400 text-sm">search</span>
                                            <input type="text" x-model="search" placeholder="Search user..." 
                                                   class="w-full h-10 bg-slate-50 dark:bg-black/20 border-none rounded-xl pl-10 pr-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-1 focus:ring-blue-500/50 transition-all">
                                        </div>
                                    </div>

                                    <div class="max-h-60 overflow-y-auto p-2 space-y-1">
                                        @foreach($users as $user)
                                            <button type="button"
                                                    x-show="search === '' || '{{ strtolower($user->username) }}'.includes(search.toLowerCase()) || '{{ strtolower($user->email) }}'.includes(search.toLowerCase())"
                                                    @click="selectUser('{{ $user->id }}', '{{ $user->username }}', '{{ showAmount($user->balance) }}')"
                                                    class="w-full text-left p-3 rounded-xl hover:bg-blue-600 group transition-all flex items-center justify-between"
                                                    :class="selectedId == '{{ $user->id }}' ? 'bg-blue-500/10' : ''">
                                                <div class="flex flex-col">
                                                    <span class="text-[11px] font-bold group-hover:text-white transition-colors"
                                                          :class="selectedId == '{{ $user->id }}' ? 'text-blue-500' : 'text-slate-900 dark:text-white'">{{ $user->username }}</span>
                                                    <span class="text-[9px] font-medium text-slate-400 dark:text-white/30 group-hover:text-white/60 transition-colors mt-0.5">{{ $user->email }}</span>
                                                </div>
                                                <span class="material-symbols-rounded text-blue-500 group-hover:text-white transition-all text-sm" x-show="selectedId == '{{ $user->id }}'">check</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Balance Indicator -->
                        <div x-show="selectedId" class="p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/5 space-y-1">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none">Current Balance</p>
                            <p class="text-xl font-black text-slate-900 dark:text-white " x-text="selectedBalance"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Withdrawal Details -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <span class="material-symbols-rounded text-lg">payments</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Withdrawal Logic</h3>
                        </div>

                        <div class="space-y-6">
                            <!-- Method Selection -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Payment Method</label>
                                <select name="method_id" required class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-5 text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition-all appearance-none">
                                    <option value="">Select Method...</option>
                                    @foreach($methods as $method)
                                        <option value="{{ $method->id }}">{{ __($method->name) }} ({{ $method->currency }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <x-input name="amount" label="Withdraw Amount" placeholder="0.00" type="number" step="any" required="true" icon="attach_money" hint="Specify the amount to be deducted from user balance." />
                            
                            <x-textarea name="details" label="Internal Notes" placeholder="Enter transaction reference or internal details for this manual withdrawal..." rows="6" icon="description" hint="These details will be visible in the withdrawal history."></x-textarea>
                        </div>

                        <div class="flex pt-6 border-t border-slate-100 dark:border-white/5">
                            <button type="submit" :disabled="synching || !selectedId" 
                                    class="w-full h-16 rounded-2xl bg-orange-600 text-white text-sm font-bold uppercase tracking-widest hover:bg-orange-700 active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed shadow-lg shadow-orange-500/20">
                                <span x-show="!synching" class="material-symbols-rounded">check_circle</span>
                                <span x-show="synching" class="material-symbols-rounded animate-spin">sync</span>
                                <span x-text="synching ? 'Processing...' : 'Process Withdrawal'">Process Withdrawal</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

