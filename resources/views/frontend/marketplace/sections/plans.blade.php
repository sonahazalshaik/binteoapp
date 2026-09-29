<!-- Tab: Plans -->
<div x-show="tab === 'plans'" x-cloak x-transition class="space-y-12 pb-12">
    
    @php $activeSub = $activeSubscription ?? $subscriptions->first(); @endphp

    <div class="text-center md:text-left mb-6">
        <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-1">Upgrade Your Status</h2>
        <p class="text-slate-500 dark:text-slate-400 font-bold text-[10px] uppercase tracking-widest">Select a plan to unlock premium features and higher visibility.</p>
    </div>

    <!-- Active Subscription Badge -->
    @if(isset($activeSubscription) && $activeSubscription)
        @php
            $isLifetime = empty($activeSubscription->end_date);
            $daysRemaining = 0;
            $progressPercentage = 100;
            
            if (!$isLifetime) {
                $endDate = \Carbon\Carbon::parse($activeSubscription->end_date);
                $startDate = \Carbon\Carbon::parse($activeSubscription->start_date);
                $totalDays = max(1, $startDate->diffInDays($endDate));
                $daysRemaining = max(0, round(now()->diffInDays($endDate, false)));
                $progressPercentage = max(0, min(100, (($totalDays - $daysRemaining) / $totalDays) * 100));
            }
        @endphp
        <div class="mb-8 max-w-md md:max-w-lg mx-auto relative group">
            <div class="absolute inset-0 bg-indigo-500/20 rounded-2xl blur-md group-hover:blur-lg transition-all duration-500"></div>
            <div class="relative bg-[#0f172a]/90 backdrop-blur-xl border border-indigo-500/30 rounded-2xl p-3 md:p-4 flex items-center justify-between shadow-xl overflow-hidden">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-indigo-500/20 rounded-full blur-xl"></div>
                
                <div class="flex items-center gap-3 z-10">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center flex-shrink-0 relative">
                        <div class="absolute inset-0 border border-indigo-400/30 rounded-full animate-ping"></div>
                        <span class="material-symbols-rounded text-lg md:text-xl">workspace_premium</span>
                    </div>
                    <div class="text-left">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest">Active Plan</span>
                            @if(!$isLifetime && $daysRemaining <= 7)
                                <span class="px-2 py-0.5 rounded text-[7px] md:text-[8px] font-black bg-red-500/20 text-red-400 uppercase tracking-widest animate-pulse">Expiring Soon</span>
                            @endif
                        </div>
                        <p class="text-sm md:text-base font-black text-white uppercase tracking-wider truncate">{{ $activeSubscription->plan_name ?? 'Premium' }}</p>
                    </div>
                </div>
                
                <div class="flex items-center z-10 ml-auto flex-shrink-0">
                    <div class="text-right pr-3 md:pr-5">
                        <p class="text-[7px] md:text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1 whitespace-nowrap">Purchased</p>
                        <p class="text-[10px] md:text-[11px] font-black text-white uppercase tracking-wider whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($activeSubscription->start_date)->format('d M y') }}
                        </p>
                    </div>
                    <div class="text-right pl-3 md:pl-5 border-l border-white/10">
                        <p class="text-[7px] md:text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1 whitespace-nowrap">
                            {{ $isLifetime ? 'Status' : $daysRemaining . ' Days Left' }}
                        </p>
                        <p class="text-[10px] md:text-[11px] font-black text-indigo-400 uppercase tracking-wider whitespace-nowrap">
                            {{ $isLifetime ? 'LIFETIME' : \Carbon\Carbon::parse($activeSubscription->end_date)->format('d M y') }}
                        </p>
                    </div>
                </div>

                <!-- Dynamic Progress Bar -->
                <div class="absolute bottom-0 left-0 w-full h-[3px] bg-white/5">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 relative transition-all duration-1000" style="width: {{ $progressPercentage }}%">
                        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-white rounded-full shadow-[0_0_5px_#818cf8]"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Available Plans (Clean & Professional) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 max-w-6xl mx-auto">
        @foreach($plans as $plan)
        @php 
            $isCurrent = isset($activeSubscription) && $activeSubscription && (int)$activeSubscription->plan_id === (int)$plan->id;
            // Fallback: also check any active subscription in collection
            if(!$isCurrent && isset($subscriptions)){
                $isCurrent = $subscriptions->contains(fn($s) => (int)$s->plan_id === (int)$plan->id && (empty($s->end_date) || \Carbon\Carbon::parse($s->end_date)->isFuture()));
            }
            // showAmount adds currency symbol by default, so we pass false for the last param
            $priceText = showAmount($plan->plan_price, 0, true, false, true); 
        @endphp
        <div class="bg-white dark:bg-[#111] rounded-3xl border border-slate-200 dark:border-white/5 shadow-sm p-3.5 md:p-6 flex flex-col h-full group transition-all hover:shadow-xl hover:border-slate-300 dark:hover:border-white/10">
            
            <div class="mb-3 md:mb-5">
                <div class="flex items-center justify-between mb-1">
                    <h4 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{ $plan->plan_name }}</h4>
                    @if($plan->is_featured_plan)
                        <span class="px-2.5 py-1 bg-orange-500/10 text-orange-600 text-[8px] font-black uppercase tracking-widest rounded-lg border border-orange-500/20">Featured</span>
                    @endif
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $plan->plan_duration }} Day Access</p>
            </div>

            <div class="mb-3 md:mb-6">
                <div class="flex items-baseline gap-1">
                    <span class="text-xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tighter ">{{ $priceText }}</span>
                </div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">One-time payment</p>
            </div>

            <div class="space-y-1.5 md:space-y-2.5 mb-4 md:mb-6 flex-grow">
                @if($plan->is_featured_plan)
                <div class="flex items-start gap-2 text-orange-600 dark:text-orange-400">
                    <div class="mt-0.5 w-3 h-3 rounded-full bg-orange-500/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-[8px] font-black">star</span>
                    </div>
                    <span class="text-[10px] md:text-[11px] font-bold leading-tight">Featured Profile Placement</span>
                </div>
                @endif
                
                @if($plan->contact_access)
                <div class="flex items-start gap-2 text-indigo-600 dark:text-indigo-400">
                    <div class="mt-0.5 w-3 h-3 rounded-full bg-indigo-500/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-[8px] font-black">mail</span>
                    </div>
                    <span class="text-[10px] md:text-[11px] font-bold leading-tight">Direct Contact & Live Messaging</span>
                </div>
                @endif

                @foreach(explode(',', $plan->plan_content) as $feature)
                @if(trim($feature))
                <div class="flex items-start gap-2">
                    <div class="mt-0.5 w-3 h-3 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-[8px] font-black text-slate-900 dark:text-white">check</span>
                    </div>
                    <span class="text-[10px] md:text-[11px] font-bold text-slate-600 dark:text-slate-400 leading-tight">{{ trim($feature) }}</span>
                </div>
                @endif
                @endforeach
            </div>

            <button @click="console.log('Initiating purchase for plan:', '{{ $plan->id }}'); initiatePurchase('{{ $plan->id }}')" 
                    :disabled="loading === '{{ $plan->id }}' || {{ $isCurrent ? 'true' : 'false' }}"
                    class="w-full py-2.5 md:py-3 rounded-2xl font-black text-[9px] uppercase tracking-widest transition-all
                        {{ $isCurrent 
                            ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 cursor-default' 
                            : 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:scale-[1.02] active:scale-95 shadow-lg' 
                        }}">
                <span x-show="loading !== '{{ $plan->id }}'" class="flex items-center justify-center gap-2">
                    @if($isCurrent)
                        Subscribed
                        <span class="material-symbols-rounded text-sm">verified</span>
                    @else
                        Subscribe
                        <span class="material-symbols-rounded text-sm">arrow_forward</span>
                    @endif
                </span>
                <span x-show="loading === '{{ $plan->id }}'" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Processing...
                </span>
            </button>
        </div>
        @endforeach
    </div>
</div>

