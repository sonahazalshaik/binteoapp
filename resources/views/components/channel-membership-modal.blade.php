@props(['channel' => null, 'plans' => null])

@php
    $plansList = $plans;
    if ($plansList === null && $channel && \Illuminate\Support\Facades\Schema::hasTable('channel_plans')) {
        $plansList = $channel->plans()->where('status', true)->where('price', '>', 0)->orderBy('duration_days', 'asc')->get();
    }
    $plansList = $plansList ?? collect();

    // Determine popular plan: prioritize 1_month or 30 days, otherwise the first plan
    $hasOneMonth = $plansList->contains(fn($p) => $p->duration_type === '1_month' || $p->duration_days == 30);
    $channelName = $channel->name ?? 'Creator';
@endphp

<!-- Caveat Font for Hand-drawn Script Text -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet">

<style>
    @media (max-width: 767px) {
        .exclusive-header-graphic {
            display: none !important;
        }
    }

    /* Primary Orange Slim Scrollbar */
    .modal-orange-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #f97316 transparent;
    }
    .modal-orange-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    .modal-orange-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .modal-orange-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #ff5200 0%, #f97316 100%);
        border-radius: 9999px;
    }
    .modal-orange-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #ea580c;
    }
</style>

<div x-data="{ openMembershipModal: false }" 
     @open-channel-membership-modal.window="openMembershipModal = true"
     @close-channel-membership-modal.window="openMembershipModal = false"
     x-cloak>
    
    <template x-teleport="body">
        <!-- Modal Backdrop & Container -->
        <div x-show="openMembershipModal"
             x-effect="openMembershipModal ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')"
             class="fixed inset-0 z-[999999] flex items-end sm:items-center sm:justify-center p-0 sm:p-4">

        <!-- Backdrop -->
        <div x-show="openMembershipModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="openMembershipModal = false"
             class="absolute inset-0 bg-black/65 dark:bg-black/80 backdrop-blur-sm"></div>

        <!-- Panel: Native Mobile Sheet on Mobile, Clean Centered Card on Desktop -->
        <div x-show="openMembershipModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
             x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 sm:scale-100 sm:opacity-100"
             x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
             class="relative w-full sm:max-w-xl md:max-w-2xl lg:max-w-[760px] bg-white dark:bg-[#18181b] rounded-t-[28px] rounded-b-none sm:rounded-[24px] shadow-2xl border-t sm:border border-slate-100 dark:border-white/10 flex flex-col max-h-[92dvh] sm:max-h-[82vh] overflow-hidden z-10 mt-auto mb-0 sm:my-auto">

            <!-- Mobile Drag Handle -->
            <div class="sm:hidden flex justify-center pt-2.5 pb-0.5 shrink-0">
                <div class="w-10 h-1 rounded-full bg-slate-300 dark:bg-white/20"></div>
            </div>

            <!-- Header Section -->
            <div class="relative shrink-0 px-5 sm:px-6 pt-2 sm:pt-4 pb-3 sm:pb-3.5 border-b border-slate-100 dark:border-white/5 bg-gradient-to-r from-orange-50/70 via-amber-50/30 to-orange-50/70 dark:from-orange-950/20 dark:via-transparent dark:to-orange-950/10">
                
                <!-- Close Button -->
                <button @click="openMembershipModal = false"
                        type="button"
                        class="absolute top-2.5 sm:top-3.5 right-4 sm:right-5 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gradient-to-tr from-[#ff5200] to-orange-500 hover:from-orange-600 hover:to-amber-500 text-white shadow-sm shadow-orange-500/30 flex items-center justify-center transition-all z-20 active:scale-95">
                    <svg class="w-3.5 h-3.5 stroke-white stroke-[2.5]" fill="none" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <div class="flex items-center justify-between gap-3">
                    <!-- Left: Creator Avatar + Title & Description -->
                    <div class="flex-1 min-w-0 pr-6 sm:pr-0">
                        <!-- Top Row: Avatar & Title + Badge -->
                        <div class="flex items-center gap-3">
                            <!-- Creator Avatar with Glowing Orange Ring -->
                            <div class="relative shrink-0 w-11 h-11 sm:w-12 sm:h-12">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full p-[2px] bg-gradient-to-tr from-amber-500 via-orange-500 to-amber-400 shadow-sm shadow-orange-500/20 shrink-0">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-slate-100 dark:bg-[#1a1a1e] flex items-center justify-center">
                                        @if($channel && $channel->avatar)
                                            <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $channel->avatar) }}" 
                                                 alt="{{ $channelName }}" 
                                                 class="w-full h-full object-cover rounded-full">
                                        @else
                                            <span class="text-lg font-black text-orange-500">{{ substr($channelName, 0, 1) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="min-w-0">
                                <!-- Crown Badge -->
                                <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100/80 dark:bg-orange-500/15 text-orange-600 dark:text-orange-400 border border-orange-200/50 dark:border-orange-500/20 text-[9px] font-black uppercase tracking-wider">
                                    <span class="text-[9px]">👑</span>
                                    <span>CHANNEL MEMBERSHIP</span>
                                </div>

                                <!-- Channel Title -->
                                <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight mt-0.5 truncate">
                                    Join {{ $channelName }}
                                </h2>
                            </div>
                        </div>

                        <!-- Description: Sits cleanly under the row -->
                        <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1 sm:mt-1.5 max-w-lg leading-normal">
                            Subscribe directly to this channel. You’ll get unlimited access to all exclusive premium videos published by this creator for your chosen duration.
                        </p>
                    </div>

                    <!-- Right: Decorative 3D Play Triangle & Hand-drawn Script Text (Strictly Desktop Only) -->
                    <div class="exclusive-header-graphic hidden md:flex items-center gap-2.5 shrink-0 select-none pr-4">
                        <div class="relative w-10 h-10 shrink-0">
                            <div class="absolute inset-0 bg-orange-500/20 rounded-full blur-sm"></div>
                            <svg width="40" height="40" class="text-orange-500 drop-shadow-sm transform -rotate-6" viewBox="0 0 100 100" fill="none">
                                <defs>
                                    <linearGradient id="headerPlayGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#fdba74" />
                                        <stop offset="45%" stop-color="#f97316" />
                                        <stop offset="100%" stop-color="#ea580c" />
                                    </linearGradient>
                                </defs>
                                <path d="M22 18C22 13 27 10 31.5 12.6L72 35.8C76.2 38.2 76.2 44.4 72 46.8L31.5 70C27 72.6 22 69.6 22 64.6V18Z" 
                                      fill="url(#headerPlayGrad)" />
                                <path d="M10 24L14 28L10 32L6 28Z" fill="#fb923c" opacity="0.7"/>
                            </svg>
                        </div>

                        <div class="flex flex-col -rotate-6 tracking-wide leading-none">
                            <span class="text-amber-500 text-[10px] ml-2 font-bold">✦</span>
                            <span style="font-family: 'Caveat', 'Playball', cursive;" class="text-orange-500 font-bold text-base leading-tight">
                                Unlock
                            </span>
                            <span style="font-family: 'Caveat', 'Playball', cursive;" class="text-orange-500 font-bold text-base leading-tight flex items-center gap-1">
                                Exclusive Content <span class="text-amber-500 text-xs">✦</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body Area: Clean Vertical Stack on Mobile, Grid on Desktop -->
            <div class="flex-1 overflow-y-auto overscroll-contain modal-orange-scrollbar px-4 sm:px-6 py-3 sm:py-3.5">
                @if($plansList->count() > 0)
                    @php
                        $colsClass = match($plansList->count()) {
                            1 => 'sm:grid-cols-1 max-w-sm mx-auto',
                            2 => 'sm:grid-cols-2 max-w-xl mx-auto',
                            3 => 'sm:grid-cols-3',
                            default => 'sm:grid-cols-2 lg:grid-cols-4',
                        };
                    @endphp

                    <!-- Vertical Stack on mobile with reduced card width, Side-by-side Grid on desktop -->
                    <div class="flex flex-col items-center sm:items-stretch sm:grid {{ $colsClass }} gap-3 sm:gap-3 lg:gap-3.5 pt-3.5 sm:pt-4 pb-1">
                        @foreach($plansList as $index => $plan)
                            @php
                                $isPopular = ($hasOneMonth && ($plan->duration_type === '1_month' || $plan->duration_days == 30))
                                             || (!$hasOneMonth && $loop->first);

                                $pillLabel = match($plan->duration_type) {
                                    '1_month' => '1 MONTH',
                                    '3_months' => '3 MONTHS',
                                    '6_months' => '6 MONTHS',
                                    '1_year' => '12 MONTHS',
                                    default => ($plan->duration_days >= 360 ? '12 MONTHS' : strtoupper($plan->duration_label)),
                                };
                            @endphp

                            <!-- Plan Card: Compact native mobile card styling, compact desktop column -->
                            <div class="w-full max-w-[295px] sm:max-w-none relative rounded-2xl sm:rounded-xl px-3.5 sm:px-4 pb-3 sm:pb-3.5 flex flex-col justify-between transition-all duration-200
                                        @if($isPopular)
                                            border-2 border-orange-500 bg-orange-50/15 dark:bg-orange-950/10 shadow-md shadow-orange-500/5
                                        @else
                                            border border-slate-200/90 dark:border-white/10 bg-white dark:bg-[#1c1c20] hover:border-orange-500/40 hover:shadow-md transition-colors
                                        @endif"
                                 style="padding-top: {{ $isPopular ? '22px' : '16px' }};">

                                @if($isPopular)
                                    <!-- MOST POPULAR Top Badge: left-5 on mobile, centered on desktop -->
                                    <div class="absolute -top-2.5 left-5 sm:left-1/2 sm:-translate-x-1/2 z-10 pointer-events-none">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-gradient-to-r from-[#ff4500] to-orange-500 text-white font-black text-[9px] uppercase tracking-wider shadow-sm shadow-orange-500/30 whitespace-nowrap">
                                            <span>👑</span>
                                            <span>MOST POPULAR</span>
                                        </span>
                                    </div>
                                @endif

                                <div>
                                    <!-- Plan Duration Header Row -->
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-white/10 text-slate-800 dark:text-slate-200 font-extrabold text-[10px] uppercase tracking-wider">
                                            {{ $pillLabel }}
                                        </span>
                                        <span class="text-[10px] sm:text-[11px] font-semibold text-slate-400 dark:text-slate-500">
                                            {{ $plan->duration_days }} Days
                                        </span>
                                    </div>

                                    <!-- Price Display -->
                                    <div class="my-1 sm:my-1.5">
                                        <span class="font-black text-xl sm:text-2xl tracking-tight @if($isPopular) text-[#ff5200] dark:text-orange-500 @else text-slate-900 dark:text-white @endif">
                                            ₹{{ number_format($plan->price, 2) }}
                                        </span>
                                    </div>

                                    <!-- Standard Channel Perks List with Orange Checkmarks -->
                                    <ul class="space-y-1 sm:space-y-1.5 my-2 sm:my-2.5">
                                        <li class="flex items-center gap-2 text-[10px] sm:text-[11px] text-slate-600 dark:text-slate-300 font-medium">
                                            <span class="w-3.5 h-3.5 rounded-full bg-[#ff5200] text-white flex items-center justify-center shrink-0 text-[8px]">
                                                <svg class="w-2.5 h-2.5 stroke-white stroke-[3.5] fill-none" viewBox="0 0 24 24">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </span>
                                            <span>All premium videos</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-[10px] sm:text-[11px] text-slate-600 dark:text-slate-300 font-medium">
                                            <span class="w-3.5 h-3.5 rounded-full bg-[#ff5200] text-white flex items-center justify-center shrink-0 text-[8px]">
                                                <svg class="w-2.5 h-2.5 stroke-white stroke-[3.5] fill-none" viewBox="0 0 24 24">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </span>
                                            <span>Full HD playback</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-[10px] sm:text-[11px] text-slate-600 dark:text-slate-300 font-medium">
                                            <span class="w-3.5 h-3.5 rounded-full bg-[#ff5200] text-white flex items-center justify-center shrink-0 text-[8px]">
                                                <svg class="w-2.5 h-2.5 stroke-white stroke-[3.5] fill-none" viewBox="0 0 24 24">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </span>
                                            <span>Ad-free experience</span>
                                        </li>
                                        <li class="flex items-center gap-2 text-[10px] sm:text-[11px] text-slate-600 dark:text-slate-300 font-medium">
                                            <span class="w-3.5 h-3.5 rounded-full bg-[#ff5200] text-white flex items-center justify-center shrink-0 text-[8px]">
                                                <svg class="w-2.5 h-2.5 stroke-white stroke-[3.5] fill-none" viewBox="0 0 24 24">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </span>
                                            <span>Support the creator</span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Action Button: Compact CTA button -->
                                @if($isPopular)
                                    <button type="button"
                                            onclick="joinChannelPlan({{ $plan->id }}, '{{ addslashes($plan->name ?: $plan->duration_label) }}', {{ $plan->price }})"
                                            class="w-full max-w-[200px] sm:max-w-none mx-auto mt-2 sm:mt-2.5 py-1.5 sm:py-2 px-3 sm:px-3.5 rounded-full bg-gradient-to-r from-[#ff5200] via-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold text-[11px] sm:text-xs tracking-wide shadow-sm shadow-orange-500/25 active:scale-[0.98] transition-all flex items-center justify-center gap-1.5">
                                        <span>👑</span>
                                        <span>Join for ₹{{ number_format($plan->price, 2) }}</span>
                                        <span>→</span>
                                    </button>
                                @else
                                    <button type="button"
                                            onclick="joinChannelPlan({{ $plan->id }}, '{{ addslashes($plan->name ?: $plan->duration_label) }}', {{ $plan->price }})"
                                            class="w-full max-w-[200px] sm:max-w-none mx-auto mt-2 sm:mt-2.5 py-1.5 sm:py-2 px-3 sm:px-3.5 rounded-full border border-orange-500 text-orange-600 dark:text-orange-400 hover:bg-orange-500 hover:text-white dark:hover:bg-orange-500 dark:hover:text-white font-bold text-[11px] sm:text-xs tracking-wide active:scale-[0.98] transition-all flex items-center justify-center gap-1.5">
                                        <span>Join for ₹{{ number_format($plan->price, 2) }}</span>
                                        <span>→</span>
                                    </button>
                                @endif

                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-14 h-14 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center mx-auto mb-3">
                            <span class="text-2xl">👑</span>
                        </div>
                        <h4 class="text-base font-black text-slate-900 dark:text-white mb-1">No Active Membership Plans</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                            This creator hasn't published any active membership plans at this moment. Please check back soon!
                        </p>
                    </div>
                @endif
            </div>

            <!-- Footer: Trust Badges (Exact Mockup Layout) -->
            <div class="shrink-0 px-5 sm:px-6 py-2.5 pb-[calc(env(safe-area-inset-bottom,0px)+10px)] sm:py-2.5 bg-slate-50 dark:bg-white/[0.02] border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-slate-500 dark:text-slate-400">
                <!-- 100% Secure Checkout with Lock Icon -->
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a5 5 0 00-5 5v3H6a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2v-8a2 2 0 00-2-2h-1V7a5 5 0 00-5-5zm-3 5a3 3 0 016 0v3H9V7zm3 6a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                    </svg>
                    <span class="font-medium text-[10px] sm:text-[11px] text-slate-600 dark:text-slate-300">100% Secure Checkout</span>
                </div>

                <!-- Only Applies to Channel with Shield Check Icon -->
                <div class="flex items-center gap-1.5 text-right">
                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="font-medium text-[10px] sm:text-[11px] text-slate-600 dark:text-slate-300">Only applies to {{ $channelName }}</span>
                </div>
            </div>

        </div>
    </div>
    </template>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function joinChannelPlan(planId, planName, price) {
        @auth
            Swal.fire({
                title: 'Please Wait',
                text: 'Initializing payment for ' + planName + '...',
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch("{{ url('/channel-plans/purchase') }}/" + planId, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                Swal.close();
                if (data.error) {
                    Swal.fire({
                        title: 'Notice',
                        text: data.error,
                        icon: 'warning',
                        confirmButtonColor: '#f97316'
                    });
                    return;
                }

                if (data.free_activated) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message || 'Free membership activated!',
                        icon: 'success',
                        confirmButtonColor: '#f97316'
                    }).then(() => {
                        window.location.reload();
                    });
                    return;
                }

                // Open Razorpay Checkout modal
                var options = {
                    "key": data.key,
                    "amount": data.amount,
                    "currency": data.currency,
                    "name": data.site_name || "Binteo",
                    "description": "Membership: " + (data.plan_name || planName),
                    "order_id": data.order_id,
                    "handler": function (response) {
                        Swal.fire({
                            title: 'Activating Membership',
                            text: 'Verifying payment and activating your subscription...',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        fetch("{{ route('channel-plans.callback') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature,
                                trx: data.trx
                            })
                        })
                        .then(res => res.json())
                        .then(callbackData => {
                            Swal.close();
                            if (callbackData.success) {
                                Swal.fire({
                                    title: 'Membership Activated!',
                                    text: callbackData.message || 'You now have access to all premium videos on this channel.',
                                    icon: 'success',
                                    confirmButtonColor: '#f97316'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Verification Failed',
                                    text: callbackData.error || 'Payment could not be verified.',
                                    icon: 'error',
                                    confirmButtonColor: '#f97316'
                                });
                            }
                        })
                        .catch(err => {
                            Swal.close();
                            Swal.fire({ title: 'Error', text: 'Error verifying payment.', icon: 'error' });
                        });
                    },
                    "prefill": {
                        "name": data.name,
                        "email": data.email,
                        "contact": data.contact
                    },
                    "theme": {
                        "color": "#f97316"
                    }
                };
                var rzp = new Razorpay(options);
                rzp.open();
            })
            .catch(err => {
                Swal.close();
                Swal.fire({ title: 'Error', text: 'Could not communicate with payment server.', icon: 'error' });
            });
        @else
            if (typeof window.showLoginAlert === 'function') {
                window.showLoginAlert('subscribe to this channel');
            } else {
                window.location.href = "{{ route('login') }}";
            }
        @endauth
    }
</script>
