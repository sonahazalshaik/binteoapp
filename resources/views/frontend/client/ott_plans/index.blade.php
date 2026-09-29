@extends('layouts.app')

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-[#0f172a] via-[#1e1b4b] to-[#0f172a] py-16 overflow-hidden" 
     x-data="ottPurchase()">
    
    <!-- Requested Radial Gradient Background -->
    <div class="absolute inset-0 pointer-events-none opacity-60" style="background: radial-gradient(circle at 0% 0%, rgba(255,255,255,0.2) 0%, transparent 50%), radial-gradient(circle at 100% 100%, rgba(255,255,255,0.2) 0%, transparent 50%);"></div>
    
    <!-- Animated Glow Blobs -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-indigo-500/20 rounded-full blur-[100px] animate-pulse"></div>
    <div class="absolute bottom-0 right-0 w-72 h-72 bg-purple-500/20 rounded-full blur-[100px] animate-pulse" style="animation-delay: 2s"></div>
    
    <div class="max-w-4xl mx-auto px-6 relative z-10">
        
        <!-- Page Heading (Always Visible) -->
        <div class="text-center mb-8 md:mb-12">
            <h2 class="text-[9px] md:text-xs font-black uppercase tracking-[0.3em] text-emerald-500 mb-2 md:mb-3">Upgrade Path</h2>
            <h1 class="text-2xl md:text-4xl font-black text-white tracking-tight mb-2 md:mb-4">Choose Your Plan</h1>
            <p class="text-[10px] md:text-sm text-gray-400 font-medium">Unlock premium tools and global opportunities.</p>
        </div>

        @if($activeSubscription)
            @php
                $isLifetime = empty($activeSubscription->end_date);
                $daysRemaining = 0;
                $progressPercentage = 100;
                
                if (!$isLifetime) {
                    $endDate = \Carbon\Carbon::parse($activeSubscription->end_date);
                    $startDate = \Carbon\Carbon::parse($activeSubscription->start_date ?? $activeSubscription->created_at);
                    $totalDays = max(1, $startDate->diffInDays($endDate));
                    $daysRemaining = max(0, round(now()->diffInDays($endDate, false)));
                    $progressPercentage = max(0, min(100, (($totalDays - $daysRemaining) / $totalDays) * 100));
                }
            @endphp
            <div class="mb-6 max-w-sm md:max-w-md mx-auto relative group">
                <div class="absolute inset-0 bg-emerald-500/10 rounded-2xl blur-md group-hover:blur-lg transition-all duration-500"></div>
                <div class="relative bg-black/40 backdrop-blur-md border border-emerald-500/30 rounded-2xl p-3 pb-4 flex items-center justify-between shadow-lg overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-500/10 rounded-full blur-xl"></div>
                    
                    <div class="flex items-center gap-3 z-10">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0 relative">
                            <div class="absolute inset-0 border border-emerald-400/30 rounded-full animate-ping"></div>
                            <span class="material-symbols-rounded text-lg">workspace_premium</span>
                        </div>
                        <div class="text-left">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Active Plan</span>
                                @if(!$isLifetime && $daysRemaining <= 7)
                                    <span class="px-1.5 py-0.5 rounded text-[6px] font-black bg-red-500/20 text-red-500 uppercase tracking-widest animate-pulse">Expiring Soon</span>
                                @endif
                            </div>
                            <p class="text-xs font-black text-white uppercase tracking-wider truncate">{{ $activeSubscription->plan_name ?? 'Premium' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center z-10 ml-auto flex-shrink-0">
                        <div class="text-right pr-2 sm:pr-4">
                            <p class="text-[6px] sm:text-[7px] font-bold text-gray-400 uppercase tracking-widest mb-0.5 whitespace-nowrap">Purchased</p>
                            <p class="text-[9px] sm:text-[10px] font-black text-white uppercase tracking-wider whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($activeSubscription->start_date ?? $activeSubscription->created_at)->format('d M y') }}
                            </p>
                        </div>
                        <div class="text-right pl-2 sm:pl-4 border-l border-white/10">
                            <p class="text-[6px] sm:text-[7px] font-bold text-gray-400 uppercase tracking-widest mb-0.5 whitespace-nowrap">
                                {{ $isLifetime ? 'Status' : $daysRemaining . ' Days Left' }}
                            </p>
                            <p class="text-[9px] sm:text-[10px] font-black text-emerald-400 uppercase tracking-wider whitespace-nowrap">
                                {{ $isLifetime ? 'LIFETIME' : \Carbon\Carbon::parse($activeSubscription->end_date)->format('d M y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Dynamic Progress Bar -->
                    <div class="absolute bottom-0 left-0 w-full h-[3px] bg-white/5">
                        <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 relative" style="width: {{ $progressPercentage }}%">
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-white rounded-full shadow-[0_0_5px_#10b981]"></div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center mb-12 md:mb-16">
                <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter mb-4">
                    Mini OTT <span class="text-[#ff571a]">Plans</span>
                </h1>
                <p class="max-w-2xl mx-auto text-gray-400 font-bold text-xs md:text-sm uppercase tracking-widest leading-relaxed">
                    Unlock unlimited access to the entire premium library in high definition.
                </p>
            </div>
        @endif

        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6 max-w-5xl mx-auto">
            @foreach($plans as $plan)
                @php
                    $isPremium = $loop->last;
                    $isCurrentPlan = $activeSubscription && $activeSubscription->ott_plan_id == $plan->id;
                    $ottFeatures = array_filter(array_map('trim', explode("\n", $plan->description)));
                    if(empty($ottFeatures)) $ottFeatures = ['Custom Branding', 'Priority Listing', 'Unlimited Media', 'Network Access', 'VIP Support'];
                @endphp
                <div class="relative bg-white/5 backdrop-blur-xl border border-white/10 rounded-[1.5rem] md:rounded-[2rem] p-4 md:p-8 transition-all duration-500 hover:scale-[1.02] flex flex-col h-full overflow-hidden">
                    @if($isPremium)
                        <div class="absolute top-0 right-0 px-3 md:px-5 py-1 md:py-1.5 bg-[#ff571a] text-white text-[7px] md:text-[9px] font-black uppercase tracking-widest rounded-bl-xl md:rounded-bl-2xl">Popular</div>
                    @endif

                    <div class="mb-4 md:mb-8">
                        <h3 class="text-[11px] md:text-base xl:text-lg font-black text-white mb-1 md:mb-1.5 uppercase tracking-wide truncate">{{ $plan->name }}</h3>
                        <div class="flex items-baseline gap-1 md:gap-1.5 flex-wrap md:flex-nowrap">
                            <span class="text-base md:text-lg xl:text-xl font-black text-white whitespace-nowrap">{{ str_replace('.00', '', showAmount($plan->price)) }}</span>
                            <span class="text-[7px] md:text-[9px] font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">/ 
                                @if($plan->duration == 0) LIFETIME 
                                @elseif($plan->duration == 1 || $plan->duration == 30) 1 MONTH 
                                @elseif($plan->duration == 12 || $plan->duration == 365) 1 YEAR 
                                @elseif($plan->duration > 12 && $plan->duration % 30 == 0) {{ $plan->duration / 30 }} MONTHS 
                                @else {{ $plan->duration }} MONTHS 
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2 md:space-y-4 mb-6 md:mb-12 flex-1">
                        <!-- OTT Streaming Access (Dynamic indicator) -->
                        <div class="flex items-center gap-1.5 md:gap-3 p-2 rounded-xl bg-white/5 border border-white/5">
                            <div class="w-3.5 h-3.5 md:w-5 md:h-5 rounded-full {{ $plan->ott_access ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }} flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-rounded text-[8px] md:text-sm">{{ $plan->ott_access ? 'done' : 'close' }}</span>
                            </div>
                            <span class="text-[8px] md:text-sm font-bold uppercase tracking-tighter leading-snug {{ $plan->ott_access ? 'text-gray-200' : 'text-gray-500 line-through' }}">OTT Media Streaming</span>
                        </div>

                        @foreach(array_slice($ottFeatures, 0, 5) as $feature)
                            <div class="flex items-center gap-1.5 md:gap-3">
                                <div class="w-3.5 h-3.5 md:w-5 md:h-5 rounded-full bg-[#ff571a]/20 text-[#ff571a] flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-rounded text-[8px] md:text-sm">done</span>
                                </div>
                                <span class="text-[8px] md:text-sm font-bold text-gray-400 uppercase tracking-tighter leading-snug">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>

                    <button @auth @if(!$isCurrentPlan) @click="initiatePurchase('{{ $plan->id }}')" @endif @else @click="window.showLoginAlert('purchase this plan')" @endauth
                            x-bind:disabled="loading === '{{ $plan->id }}' || {{ $isCurrentPlan ? 'true' : 'false' }}"
                            x-bind:class="loading === '{{ $plan->id }}' ? 'bg-gradient-to-r from-[#ff571a] to-[#ff8c1a] text-white shadow-lg shadow-[#ff571a]/20 opacity-90 cursor-wait' : ({{ $isCurrentPlan ? "'bg-emerald-500/10 text-emerald-500 cursor-not-allowed border border-emerald-500/20'" : ($isPremium ? "'bg-[#ff571a] text-white shadow-lg shadow-[#ff571a]/20 hover:brightness-110'" : "'bg-white text-black hover:bg-gray-100'") }})"
                            class="w-full h-10 md:h-14 rounded-xl md:rounded-2xl font-black text-[8px] md:text-[11px] uppercase tracking-widest transition-all active:scale-95 flex items-center justify-center gap-2">
                        
                        @if($isCurrentPlan)
                            <span class="flex items-center justify-center gap-1 md:gap-2">
                                SUBSCRIBED <span class="material-symbols-rounded text-sm md:text-lg">check_circle</span>
                            </span>
                        @else
                            <span x-show="loading !== '{{ $plan->id }}'" class="flex items-center justify-center gap-1 md:gap-2">
                                SUBSCRIBE <span class="material-symbols-rounded text-sm md:text-lg">arrow_forward</span>
                            </span>
                            <span x-show="loading === '{{ $plan->id }}'" class="flex items-center justify-center gap-1 md:gap-2">
                                <svg class="animate-spin h-3 w-3 md:h-4 md:w-4 text-current" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Processing
                            </span>
                        @endif
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function ottPurchase() {
        return {
            loading: null,
            initiatePurchase(planId) {
                console.log('OTT Purchase: Starting for', planId);
                this.loading = planId;
                fetch('{{ route('user.ott-plans.buy', ['id' => ':id']) }}'.replace(':id', planId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                        this.loading = null;
                        return;
                    }

                    if (data.redirect) {
                        Swal.fire('Success', data.message, 'success').then(() => {
                            window.location.href = data.redirect;
                        });
                        return;
                    }

                    const options = {
                        key: data.key,
                        amount: data.amount,
                        currency: data.currency,
                        name: "Mini OTT Membership",
                        description: `Unlock access with ${data.plan_name}`,
                        order_id: data.order_id,
                        handler: (response) => {
                            this.verifyPayment(response, data.trx);
                        },
                        prefill: {
                            name: data.name,
                            email: data.email,
                            contact: data.contact
                        },
                        theme: { color: "#4F46E5" },
                        modal: {
                            ondismiss: () => { this.loading = null; }
                        }
                    };
                    const rzp = new Razorpay(options);
                    rzp.open();
                })
                .catch(err => {
                    console.error(err);
                    this.loading = null;
                    Swal.fire('Error', 'Gateway Initialization Failed', 'error');
                });
            },
            verifyPayment(response, trx) {
                fetch('{{ route('user.ott-plans.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_signature: response.razorpay_signature,
                        trx: trx
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success', data.success, 'success').then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.error, 'error');
                    }
                    this.loading = null;
                });
            }
        }
    }
</script>
@endsection
