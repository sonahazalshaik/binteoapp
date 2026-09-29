@extends('layouts.app')

@section('content')
<div id="marketplace-portfolio" class="min-h-screen bg-slate-50/50 dark:bg-[#0A0A0A] pb-20" x-data="{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'about', messageModal: false, coverModal: false, profileModal: false, galleryModal: false, activeGalleryImage: '' }" x-init="if(!['about', 'gallery', 'services', 'contact'].includes(activeTab)) activeTab = 'about'; $watch('activeTab', value => { window.location.hash = value; }); $watch('messageModal', value => { if(value) setTimeout(() => { let c = document.getElementById('messages-container'); if(c) c.scrollTop = c.scrollHeight; }, 100); });">
    @php
        $hasSubscription = false;
        $hasContactAccess = false;
        if (auth()->check()) {
            if (auth()->user()->role === 'user') {
                $activePlans = auth()->user()->purchasedPlans()
                    ->with('plan')
                    ->where(function($q) {
                        $q->where('expired_date', '>', now())->orWhereNull('expired_date');
                    })->get();
                if ($activePlans->isNotEmpty()) {
                    $hasSubscription = true;
                }
                // Check if any active plan has contact_access (Premium Access) enabled
                if ($activePlans->filter(function($p) { return $p->plan && $p->plan->contact_access == 1; })->isNotEmpty()) {
                    $hasContactAccess = true;
                }
            } else {
                $hasSubscription = true; // Non-standard users (like admin) bypass this check
                $hasContactAccess = true;
            }
        }
        
        if (session()->has('marketplace_user_id')) {
            $viewerMarketplaceId = session('marketplace_user_id');
            $viewerMarketplace = \App\Models\MarketPlace::find($viewerMarketplaceId);
            if ($viewerMarketplace) {
                $activeSubs = $viewerMarketplace->subscriptions()
                    ->with('plan')
                    ->where(function($q) {
                        $q->where('end_date', '>', now())->orWhereNull('end_date');
                    })->get();
                if ($activeSubs->isNotEmpty()) {
                    $hasSubscription = true;
                    if ($activeSubs->filter(function($p) { return $p->plan && $p->plan->contact_access == 1; })->isNotEmpty()) {
                        $hasContactAccess = true;
                    }
                }
            }
        }
        
        $isOwner = session('marketplace_user_id') == $marketplace->id;
        $canView = $hasSubscription || $isOwner;
        $canContact = $hasContactAccess || $isOwner;
    @endphp

    <!-- Header / Cover Photo -->
    <div class="relative w-full h-48 lg:h-64 bg-slate-200 dark:bg-[#151515] cursor-pointer group" @click="coverModal = true">
        <img src="{{ $marketplace->coverUrl() ?? 'https://placehold.co/1200x800?text=Cover' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-10">
            
            <!-- Main Content Column -->
            <div class="flex-1 min-w-0 pb-10">
                <!-- Profile & Ratings Wrapper -->
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6 lg:gap-10">
                    
                    <!-- Left: Profile Info -->
                    <div class="flex-1 min-w-0">
                        <!-- Avatar & Profile Header -->
                        <div class="flex flex-col sm:flex-row items-center sm:items-end text-center sm:text-left gap-4 md:gap-5 relative z-10">
                            <div class="w-40 h-40 lg:w-56 lg:h-56 rounded-full border-[6px] border-white dark:border-[#0A0A0A] bg-slate-100 dark:bg-neutral-800 overflow-hidden shrink-0 shadow-md -mt-[80px] lg:-mt-28 mx-auto sm:mx-0 cursor-pointer hover:opacity-90 transition-opacity" @click="profileModal = true">
                                @if($marketplace->image)
                                    <img src="{{ $marketplace->photoUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-blue-500 text-white text-6xl lg:text-8xl font-bold">{{ $marketplace->getInitials() }}</div>
                                @endif
                            </div>
                            <div class="pb-1 lg:pb-2 pt-2 lg:pt-0 flex flex-col items-center sm:items-start justify-end flex-1 min-w-0 w-full">
                                <h1 class="text-2xl lg:text-[26px] font-bold text-slate-900 dark:text-white leading-tight line-clamp-2 break-all lg:break-words">{{ $marketplace->name }}</h1>
                                <div class="flex items-center justify-center sm:justify-start mt-1.5 mb-2 w-full">
                                    <span class="bg-blue-100 text-blue-600 text-[10px] font-bold px-3 py-1 rounded uppercase shrink-0">{{ $marketplace->type ?? 'ACTOR' }}</span>
                                </div>
                                <p class="text-[13px] lg:text-[15px] text-slate-500 dark:text-slate-400 line-clamp-3 break-words">{{ $marketplace->business_name ?? 'Professional' }}</p>
                            </div>
                        </div>

                        <!-- Location & Info -->
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 lg:gap-6 mt-5 text-xs lg:text-sm text-slate-500 dark:text-slate-400 font-medium">
                            <div class="flex items-center gap-1.5" title="{{ $marketplace->location ?? 'Mumbai, Maharashtra' }}">
                                <span class="material-symbols-rounded text-[18px]">location_on</span> 
                                {{ \Illuminate\Support\Str::limit($marketplace->location ?? 'Mumbai, Maharashtra', 16) }}
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-rounded text-[18px]">calendar_today</span> 
                                @php
                                    $expRaw = is_array($marketplace->years_of_experience) ? implode(', ', $marketplace->years_of_experience) : ($marketplace->years_of_experience ?? '0');
                                    $expClean = trim(str_ireplace([' years', ' year', ' yrs', ' yr', 'years', 'year', 'yrs', 'yr'], '', $expRaw));
                                @endphp
                                {{ $expClean }} years
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap items-center gap-2 lg:gap-3 mt-6 lg:mt-8">
                            @if($marketplace->hasContactAccess() || $isOwner)
                                @if($canContact)
                                    <button @click="messageModal = true" class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-red-500 to-orange-500 shadow-md hover:shadow-lg transition-all border-none">
                                        <span class="material-symbols-rounded text-[18px]">chat</span> Message
                                    </button>
                                @else
                                    @if(auth()->check() || session()->has('marketplace_user_id'))
                                        <button onclick="Swal.fire({ title: 'Premium Feature', text: 'You need an active subscription with contact access to message.', icon: 'lock', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Upgrade Plan' }).then((r) => { if(r.isConfirmed) window.location.href='{{ route('marketplace.index') }}#plans'; })" class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-2.5 border border-slate-200 dark:border-white/10 rounded-xl font-bold text-sm text-slate-700 dark:text-white bg-slate-100 dark:bg-[#151515] transition-colors shadow-sm lg:shadow-none">
                                            <span class="material-symbols-rounded text-[18px]">lock</span> Message
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-red-500 to-orange-500 shadow-md hover:shadow-lg transition-all border-none">
                                            <span class="material-symbols-rounded text-[18px]">chat</span> Message
                                        </a>
                                    @endif
                                @endif
                            @endif
                            <button onclick="sharePortfolio('{{ url()->current() }}')" class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-5 py-2.5 border border-red-200 dark:border-red-500/30 rounded-xl font-bold text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors bg-white dark:bg-transparent shadow-sm lg:shadow-none shrink-0">
                                <span class="material-symbols-rounded text-[18px]">share</span> <span class="hidden sm:inline">Share</span>
                            </button>

                            @if($marketplace->facebook_link || $marketplace->instagram_link || $marketplace->twitter_link)
                                <div class="flex items-center gap-2 ml-auto lg:ml-2">
                                    @if($marketplace->facebook_link)
                                        <a href="{{ Str::startsWith($marketplace->facebook_link, ['http://', 'https://']) ? $marketplace->facebook_link : 'https://'.$marketplace->facebook_link }}" target="_blank" class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm shrink-0">
                                            <i class="fab fa-facebook-f text-sm"></i>
                                        </a>
                                    @endif
                                    @if($marketplace->instagram_link)
                                        <a href="{{ Str::startsWith($marketplace->instagram_link, ['http://', 'https://']) ? $marketplace->instagram_link : 'https://'.$marketplace->instagram_link }}" target="_blank" class="w-10 h-10 rounded-xl bg-pink-50 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400 flex items-center justify-center hover:bg-pink-600 hover:text-white transition-all shadow-sm shrink-0">
                                            <i class="fab fa-instagram text-[15px]"></i>
                                        </a>
                                    @endif
                                    @if($marketplace->twitter_link)
                                        <a href="{{ Str::startsWith($marketplace->twitter_link, ['http://', 'https://']) ? $marketplace->twitter_link : 'https://'.$marketplace->twitter_link }}" target="_blank" class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-900/20 text-sky-500 dark:text-sky-400 flex items-center justify-center hover:bg-sky-500 hover:text-white transition-all shadow-sm shrink-0">
                                            <i class="fab fa-twitter text-sm"></i>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Ratings Component -->
                    <div class="w-full lg:w-[340px] shrink-0 mt-6 lg:mt-0 lg:self-center relative z-10">
                        <div class="flex flex-col md:flex-row md:items-center justify-between bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-500/5 dark:to-red-500/5 border border-orange-100 dark:border-orange-500/10 rounded-xl p-4 shadow-sm gap-4"
                             x-data="{ 
                                rating: {{ auth()->check() ? ($marketplace->ratings()->where('user_id', auth()->id())->first()->rating ?? 0) : 0 }}, 
                                hoverRating: 0,
                                isSubmitting: false,
                                avgRating: {{ $marketplace->averageRating }},
                                totalRatings: {{ $marketplace->totalRatings }},
                                breakdown: {{ json_encode($marketplace->ratingBreakdown) }},
                                submitRating(val) {
                                    @if(!auth()->check())
                                        window.location.href = '{{ route('login') }}';
                                        return;
                                    @endif
                                    if (this.isSubmitting) return;
                                    this.rating = val;
                                    this.isSubmitting = true;
                                    fetch('{{ route('marketplace.rating.submit', $marketplace->id) }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({ rating: val })
                                    }).then(res => res.json()).then(data => {
                                        this.isSubmitting = false;
                                        if(data.status === 'success') {
                                            this.avgRating = data.average_rating;
                                            this.totalRatings = data.total_ratings;
                                            this.breakdown = data.breakdown;
                                            if (typeof Swal !== 'undefined') {
                                                Swal.fire({
                                                    toast: true, position: 'top-end', icon: 'success', title: 'Rating saved!',
                                                    showConfirmButton: false, timer: 2000, background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff', color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a'
                                                });
                                            }
                                        }
                                    }).catch(() => this.isSubmitting = false);
                                }
                             }">
                            
                            <div class="flex flex-row items-start gap-4 w-full">
                                <!-- Left: Stars + Score -->
                                <div class="shrink-0">
                                    <div class="text-[13px] font-bold text-slate-900 dark:text-white mb-1.5">Rate this Profile</div>
                                    <div class="flex items-center gap-1 cursor-pointer mb-2">
                                        <template x-for="i in 5">
                                            <button @click="submitRating(i)" @mouseenter="hoverRating = i" @mouseleave="hoverRating = 0" class="focus:outline-none transition-transform active:scale-75 disabled:opacity-50" :disabled="isSubmitting">
                                                <i class="fa-star text-[20px] lg:text-[22px] transition-all duration-200"
                                                   :class="(hoverRating >= i || (!hoverRating && rating >= i)) ? 'fas text-yellow-400 drop-shadow-md' : 'far text-slate-300 dark:text-white/20 hover:text-yellow-400/70'">
                                                </i>
                                            </button>
                                        </template>
                                    </div>
                                    <div class="flex items-baseline gap-2">
                                        <div class="text-2xl font-black text-slate-900 dark:text-white" x-text="avgRating.toFixed(1)"></div>
                                        <span class="text-slate-400 font-bold text-xs">/ 5</span>
                                        <span class="text-[10px] text-slate-500 font-medium ml-1.5 bg-white dark:bg-black/20 px-1.5 py-0.5 rounded shadow-sm border border-slate-100 dark:border-white/5"><span x-text="totalRatings"></span> total</span>
                                    </div>
                                </div>

                                <!-- Right: Breakdown bars -->
                                <div class="flex-1 border-l border-slate-200 dark:border-white/10 pl-4 space-y-1.5 pt-1">
                                    <template x-for="star in [5,4,3,2,1]">
                                        <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-600 dark:text-slate-400">
                                            <div class="w-3 text-right" x-text="star"></div>
                                            <i class="fas fa-star text-[9px] text-yellow-400"></i>
                                            <div class="w-full h-[6px] bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden" style="min-width: 50px;">
                                                <div class="h-full bg-yellow-400 rounded-full transition-all duration-500" 
                                                     :style="'width: ' + (totalRatings > 0 ? (breakdown[star] / totalRatings * 100) : 0) + '%'"></div>
                                            </div>
                                            <div class="w-3 text-left text-[9px] text-slate-500" x-text="breakdown[star]"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3 Stats Cards -->
                <div class="grid grid-cols-3 gap-3 lg:gap-4 mt-8">
                    <div class="p-3 lg:p-5 border border-red-100 dark:border-red-500/20 rounded-2xl text-center bg-gradient-to-br from-red-50 to-orange-50 dark:from-red-500/10 dark:to-orange-500/10 shadow-sm lg:shadow-none flex flex-col justify-center items-center">
                        @php
                            $expRaw = is_array($marketplace->years_of_experience) ? implode(', ', $marketplace->years_of_experience) : ($marketplace->years_of_experience ?? '0');
                            $expClean = trim(str_ireplace([' years', ' year', ' yrs', ' yr', 'years', 'year', 'yrs', 'yr'], '', $expRaw));
                        @endphp
                        <div class="text-base lg:text-xl font-black text-red-600">{{ $expClean }}</div>
                        <div class="text-[9px] lg:text-[10px] text-slate-600 dark:text-slate-400 mt-0.5 lg:mt-1 capitalize font-medium">Years</div>
                    </div>
                    <div class="p-3 lg:p-5 border border-blue-100 dark:border-blue-500/20 rounded-2xl text-center bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-500/10 dark:to-indigo-500/10 shadow-sm lg:shadow-none flex flex-col justify-center items-center">
                        <div class="text-base lg:text-xl font-black text-blue-600">{{ $marketplace->galleries->count() }}</div>
                        <div class="text-[9px] lg:text-[10px] text-slate-600 dark:text-slate-400 mt-0.5 lg:mt-1 capitalize font-medium">Portfolio</div>
                    </div>
                    <div class="p-3 lg:p-5 border border-emerald-100 dark:border-emerald-500/20 rounded-2xl text-center bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-500/10 dark:to-teal-500/10 shadow-sm lg:shadow-none flex flex-col justify-center items-center">
                        @php 
                            $skills = is_array($marketplace->skills) ? $marketplace->skills : explode(',', $marketplace->skills ?? ''); 
                            $skillsCount = count(array_filter($skills));
                        @endphp
                        <div class="text-base lg:text-xl font-black text-emerald-600">{{ $skillsCount }}</div>
                        <div class="text-[9px] lg:text-[10px] text-slate-600 dark:text-slate-400 mt-0.5 lg:mt-1 capitalize font-medium">Skills</div>
                    </div>
                </div>

                <!-- Mobile Availability Block -->
                @if($marketplace->hasContactAccess() || $isOwner)
                <div class="block lg:hidden mt-6 border border-slate-100 dark:border-white/5 bg-white dark:bg-[#151515] rounded-2xl p-5 shadow-sm">
                    <div class="font-bold text-sm text-slate-900 dark:text-white mb-3">Availability</div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-full text-xs font-medium mb-5">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Available
                    </div>
                    @if($canView)
                        <a href="tel:{{ $marketplace->number }}" class="block w-full py-3 bg-[#e04f3e] text-white rounded-lg font-bold text-sm text-center shadow-sm hover:bg-red-600 transition-colors">
                            Contact Now
                        </a>
                    @else
                        <button onclick="Swal.fire({ title: 'Premium Feature', text: 'You need an active subscription to access direct contact details.', icon: 'lock', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Upgrade Plan' }).then((r) => { if(r.isConfirmed) window.location.href='{{ route('marketplace.index') }}#plans'; })" class="w-full py-3 bg-slate-200 dark:bg-white/10 text-slate-500 dark:text-slate-400 rounded-lg font-bold text-sm text-center shadow-sm transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded text-[18px]">lock</span> Contact Now
                        </button>
                    @endif

                    @if($marketplace->facebook_link || $marketplace->instagram_link || $marketplace->twitter_link)
                        <div class="flex justify-center gap-4 mt-5 pt-5 border-t border-slate-100 dark:border-white/5">
                            @if($marketplace->facebook_link)
                                <a href="{{ Str::startsWith($marketplace->facebook_link, ['http://', 'https://']) ? $marketplace->facebook_link : 'https://'.$marketplace->facebook_link }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if($marketplace->instagram_link)
                                <a href="{{ Str::startsWith($marketplace->instagram_link, ['http://', 'https://']) ? $marketplace->instagram_link : 'https://'.$marketplace->instagram_link }}" target="_blank" class="w-10 h-10 rounded-full bg-pink-50 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400 flex items-center justify-center hover:bg-pink-600 hover:text-white transition-all shadow-sm">
                                    <i class="fab fa-instagram text-lg"></i>
                                </a>
                            @endif
                            @if($marketplace->twitter_link)
                                <a href="{{ Str::startsWith($marketplace->twitter_link, ['http://', 'https://']) ? $marketplace->twitter_link : 'https://'.$marketplace->twitter_link }}" target="_blank" class="w-10 h-10 rounded-full bg-sky-50 dark:bg-sky-900/20 text-sky-500 dark:text-sky-400 flex items-center justify-center hover:bg-sky-500 hover:text-white transition-all shadow-sm">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                @endif

                <!-- Tabs Navigation -->
                <div class="flex items-center gap-6 mt-8 lg:mt-10 border-b border-slate-200 dark:border-white/10 overflow-x-auto no-scrollbar">
                    <button @click="activeTab = 'about'" class="pb-3 text-sm font-medium whitespace-nowrap transition-colors border-b-2" :class="activeTab === 'about' ? 'text-red-600 border-red-600' : 'border-transparent text-slate-500 hover:text-slate-900 dark:hover:text-white'">About</button>
                    <button @click="activeTab = 'gallery'" class="pb-3 text-sm font-medium whitespace-nowrap transition-colors border-b-2" :class="activeTab === 'gallery' ? 'text-red-600 border-red-600' : 'border-transparent text-slate-500 hover:text-slate-900 dark:hover:text-white'">Gallery</button>
                    <button @click="activeTab = 'portfolio'" class="pb-3 text-sm font-medium whitespace-nowrap transition-colors border-b-2" :class="activeTab === 'portfolio' ? 'text-red-600 border-red-600' : 'border-transparent text-slate-500 hover:text-slate-900 dark:hover:text-white'">Portfolio</button>
                    <button @click="activeTab = 'services'" class="pb-3 text-sm font-medium whitespace-nowrap transition-colors border-b-2" :class="activeTab === 'services' ? 'text-red-600 border-red-600' : 'border-transparent text-slate-500 hover:text-slate-900 dark:hover:text-white'">Services</button>
                    @if($marketplace->hasContactAccess() || $isOwner)
                        <button @click="activeTab = 'contact'" class="pb-3 text-sm font-medium whitespace-nowrap transition-colors border-b-2" :class="activeTab === 'contact' ? 'text-red-600 border-red-600' : 'border-transparent text-slate-500 hover:text-slate-900 dark:hover:text-white'">Contact</button>
                    @endif
                </div>

                <!-- Tab Contents -->
                <div class="mt-6">

                    <!-- ABOUT TAB -->
                    <div x-show="activeTab === 'about'" x-transition>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 lg:hidden">About</h2>
                        <div class="bg-white dark:bg-[#151515] p-6 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm lg:shadow-none">
                            <p class="text-sm md:text-base text-slate-600 dark:text-slate-400 leading-relaxed">
                                {!! nl2br(e($marketplace->more_info ?? 'Professional talent ready for new opportunities and collaborations.')) !!}
                            </p>
                            <div class="mt-8 flex flex-wrap gap-4">
                                @if($marketplace->facebook_link) 
                                    <a href="{{ Str::startsWith($marketplace->facebook_link, ['http://', 'https://']) ? $marketplace->facebook_link : 'https://'.$marketplace->facebook_link }}" target="_blank" class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800/30 rounded-xl flex items-center justify-center hover:scale-110 hover:bg-blue-600 hover:text-white hover:border-blue-600 hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-300">
                                        <i class="fab fa-facebook-f"></i>
                                    </a> 
                                @endif
                                @if($marketplace->instagram_link) 
                                    <a href="{{ Str::startsWith($marketplace->instagram_link, ['http://', 'https://']) ? $marketplace->instagram_link : 'https://'.$marketplace->instagram_link }}" target="_blank" class="w-10 h-10 bg-pink-50 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400 border border-pink-100 dark:border-pink-800/30 rounded-xl flex items-center justify-center hover:scale-110 hover:bg-pink-600 hover:text-white hover:border-pink-600 hover:shadow-lg hover:shadow-pink-500/30 transition-all duration-300">
                                        <i class="fab fa-instagram text-lg"></i>
                                    </a> 
                                @endif
                                @if($marketplace->twitter_link) 
                                    <a href="{{ Str::startsWith($marketplace->twitter_link, ['http://', 'https://']) ? $marketplace->twitter_link : 'https://'.$marketplace->twitter_link }}" target="_blank" class="w-10 h-10 bg-sky-50 dark:bg-sky-900/20 text-sky-500 dark:text-sky-400 border border-sky-100 dark:border-sky-800/30 rounded-xl flex items-center justify-center hover:scale-110 hover:bg-sky-500 hover:text-white hover:border-sky-500 hover:shadow-lg hover:shadow-sky-500/30 transition-all duration-300">
                                        <i class="fab fa-twitter"></i>
                                    </a> 
                                @endif
                                @if($marketplace->website_url || $marketplace->portfolio_url)
                                    <a href="{{ Str::startsWith($marketplace->website_url ?? $marketplace->portfolio_url, ['http://', 'https://']) ? ($marketplace->website_url ?? $marketplace->portfolio_url) : 'https://'.($marketplace->website_url ?? $marketplace->portfolio_url) }}" target="_blank" class="w-10 h-10 bg-slate-100 dark:bg-white/10 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/20 rounded-xl flex items-center justify-center hover:scale-110 hover:bg-slate-800 hover:text-white hover:border-slate-800 hover:shadow-lg transition-all duration-300" title="Website / Portfolio">
                                        <span class="material-symbols-rounded text-[20px]">language</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- GALLERY TAB -->
                    <div x-show="activeTab === 'gallery'" x-transition style="display: none;">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 lg:hidden">Gallery</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @forelse($marketplace->galleries as $gallery)
                                <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 border border-slate-100 dark:border-white/5 shadow-sm lg:shadow-none cursor-pointer group"
                                     @click="activeGalleryImage = '{{ $gallery->photoUrl() }}'; galleryModal = true">
                                    <img src="{{ $gallery->photoUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @empty
                                <div class="col-span-full py-12 text-center text-slate-400 bg-white dark:bg-[#151515] rounded-2xl">No gallery images available.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- PORTFOLIO TAB -->
                    <div x-show="activeTab === 'portfolio'" x-transition style="display: none;" x-data="{ portfolioModal: false, pTitle: '', pDesc: '', pImages: [], currentSlide: 0 }">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 lg:hidden">Portfolio</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse(\App\Models\MarketPortfolio::with('images')->where('marketplace_id', $marketplace->id)->orderBy('sort_order', 'asc')->get() as $portfolioItem)
                                <div class="bg-white dark:bg-[#111] border border-slate-200 dark:border-white/5 rounded-2xl overflow-hidden shadow-sm group cursor-pointer hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
                                     @click='pTitle = @json($portfolioItem->title); pDesc = @json($portfolioItem->description); pImages = @json($portfolioItem->images->map(function($img) { return getImage($img->image); })->toArray()); currentSlide = 0; portfolioModal = true'>
                                    <div class="h-48 bg-slate-100 dark:bg-white/5 relative overflow-hidden">
                                        @if($portfolioItem->images->count() > 0)
                                            <img src="{{ getImage($portfolioItem->images->first()->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-white/20">
                                                <span class="material-symbols-rounded text-5xl">image</span>
                                            </div>
                                        @endif
                                        <div class="absolute top-4 right-4 bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest text-white shadow-sm">
                                            {{ $portfolioItem->images->count() }} Photos
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <h4 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2 line-clamp-1">{{ $portfolioItem->title }}</h4>
                                        <div class="text-xs text-slate-500 dark:text-white/60 line-clamp-3">
                                            {!! strip_tags($portfolioItem->description) !!}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-12 text-center text-slate-400 bg-white dark:bg-[#151515] rounded-2xl border border-slate-100 dark:border-white/5">No portfolio items available.</div>
                            @endforelse
                        </div>

                        <!-- Native App Style Modal -->
                        <template x-teleport="body">
                            <div x-show="portfolioModal" class="fixed inset-0 z-[99999] flex items-end sm:items-center justify-center sm:p-6" x-cloak>
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="portfolioModal = false" x-transition.opacity></div>
                            
                            <div class="relative w-full max-w-[95%] sm:max-w-xl bg-white dark:bg-[#151515] sm:rounded-2xl rounded-t-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] sm:max-h-[85vh]" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-12 sm:scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-12 sm:scale-95">
                                 
                                <div class="w-12 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full mx-auto mt-3 mb-1 sm:hidden"></div>
                                
                                <div class="flex items-start justify-between p-4 sm:p-5 border-b border-slate-100 dark:border-white/5">
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight line-clamp-2 flex-1" x-text="pTitle"></h3>
                                    <button @click="portfolioModal = false" class="w-8 h-8 shrink-0 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500 hover:bg-red-500 hover:text-white transition-all shadow-sm ml-4">
                                        <span class="material-symbols-rounded text-sm">close</span>
                                    </button>
                                </div>
                                
                                <div class="p-4 sm:p-5 pb-8 sm:pb-6 overflow-y-auto no-scrollbar flex-1 bg-white dark:bg-transparent">
                                    <div class="prose prose-sm prose-slate dark:prose-invert max-w-none mb-6 text-slate-600 dark:text-slate-400 leading-relaxed" x-html="pDesc"></div>
                                    
                                    <div class="relative w-full rounded-2xl overflow-hidden border border-slate-200 dark:border-white/5 shadow-sm group" x-show="pImages.length > 0">
                                        <!-- Images Slider -->
                                        <div class="flex transition-transform duration-500 ease-in-out h-full" :style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
                                            <template x-for="(img, index) in pImages" :key="index">
                                                <div class="w-full flex-shrink-0 aspect-video bg-slate-100 dark:bg-[#111] relative rounded-xl overflow-hidden">
                                                    <img :src="img" class="absolute inset-0 w-full h-full object-cover">
                                                </div>
                                            </template>
                                        </div>
                                        
                                        <!-- Navigation Buttons -->
                                        <button @click="currentSlide = (currentSlide > 0) ? currentSlide - 1 : pImages.length - 1" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 backdrop-blur-md shadow-sm flex items-center justify-center text-slate-800 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-all hover:bg-orange-500 hover:text-white hover:scale-110 active:scale-95" x-show="pImages.length > 1">
                                            <span class="material-symbols-rounded">chevron_left</span>
                                        </button>
                                        <button @click="currentSlide = (currentSlide < pImages.length - 1) ? currentSlide + 1 : 0" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 backdrop-blur-md shadow-sm flex items-center justify-center text-slate-800 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-all hover:bg-orange-500 hover:text-white hover:scale-110 active:scale-95" x-show="pImages.length > 1">
                                            <span class="material-symbols-rounded">chevron_right</span>
                                        </button>
                                        
                                        <!-- Progress Bar (Orange) -->
                                        <div class="absolute bottom-0 left-0 w-full h-1.5 bg-black/30 backdrop-blur-sm" x-show="pImages.length > 1">
                                            <div class="h-full bg-orange-500 transition-all duration-300" :style="'width: ' + ((currentSlide + 1) / pImages.length * 100) + '%'"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-12 text-center" x-show="pImages.length === 0">
                                        <span class="material-symbols-rounded text-6xl text-slate-200 dark:text-white/10 mb-4 block">imagesmode</span>
                                        <p class="text-xs font-black text-slate-500 uppercase tracking-widest">No Gallery Images</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </template>
                    </div>

                    <!-- SERVICES TAB -->
                    <div x-show="activeTab === 'services'" x-transition style="display: none;">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 lg:hidden">Services</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($marketplace->services as $service)
                                @if($canView)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $marketplace->number) }}?text={{ urlencode('Hi ' . $marketplace->name . ', I am interested in your service: ' . $service->service_name) }}" target="_blank" class="block bg-white dark:bg-[#151515] p-5 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm lg:shadow-none hover:border-green-500 dark:hover:border-green-500 transition-all flex gap-4 group cursor-pointer relative overflow-hidden">
                                @else
                                    <button onclick="Swal.fire({ title: 'Premium Feature', text: 'You need an active subscription to contact this creator for their services.', icon: 'lock', confirmButtonColor: '#dc2626' })" class="w-full text-left bg-white dark:bg-[#151515] p-5 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm lg:shadow-none hover:border-red-500 dark:hover:border-red-500 transition-all flex gap-4 group relative overflow-hidden">
                                @endif
                                
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-black overflow-hidden shrink-0 group-hover:scale-105 transition-transform duration-300">
                                        <img src="{{ $service->photoUrl() }}" class="w-full h-full object-cover" onerror="this.style.display='none'">
                                    </div>
                                    <div class="flex-1 pr-6">
                                        <h3 class="font-bold text-slate-900 dark:text-white mb-1 group-hover:text-[#e04f3e] transition-colors">{{ $service->service_name }}</h3>
                                        <p class="text-sm text-slate-500 leading-relaxed">{{ $service->service_brief }}</p>
                                    </div>

                                    <div class="absolute top-5 right-5 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="material-symbols-rounded {{ $canView ? 'text-green-500' : 'text-slate-300 dark:text-slate-600' }} text-xl">
                                            {{ $canView ? 'chat' : 'lock' }}
                                        </span>
                                    </div>

                                @if($canView)
                                    </a>
                                @else
                                    </button>
                                @endif
                            @empty
                                <div class="col-span-full py-12 text-center text-slate-400 bg-white dark:bg-[#151515] rounded-2xl border border-slate-100 dark:border-white/5">No services listed.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- CONTACT TAB -->
                    @if($marketplace->hasContactAccess() || $isOwner)
                    <div x-show="activeTab === 'contact'" x-transition style="display: none;">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 lg:hidden">Contact Inquiry</h2>
                        
                        @if($canContact)
                            <div class="bg-white dark:bg-[#151515] p-6 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm">
                                <form id="contact-inquiry" action="{{ route('market.contact.store', $marketplace->id) }}" method="POST" class="space-y-4" x-data="{
                                    name: '',
                                    email: '',
                                    phone: '',
                                    subject: '',
                                    message: '',
                                    errors: {
                                        name: '',
                                        email: '',
                                        phone: '',
                                        subject: '',
                                        message: ''
                                    },
                                    validateName() {
                                        const v = String(this.name || '').trim();
                                        if (!v) {
                                            this.errors.name = 'Name is required.';
                                        } else if (v.length < 2 || !/^(?=.*[a-zA-Z])[a-zA-Z\s\.\'\-]+$/.test(v)) {
                                            this.errors.name = 'Enter a valid name (letters only, min 2 chars).';
                                        } else {
                                            this.errors.name = '';
                                        }
                                    },
                                    validateEmail() {
                                        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                        if (!this.email.trim()) {
                                            this.errors.email = 'Email is required.';
                                        } else if (!re.test(this.email)) {
                                            this.errors.email = 'Please enter a valid email address.';
                                        } else {
                                            this.errors.email = '';
                                        }
                                    },
                                    validatePhone() {
                                        if (!this.phone.trim()) {
                                            this.errors.phone = 'Phone number is required.';
                                        } else if (!/^(0\d{10}|[6-9]\d{9})$/.test(this.phone.trim())) {
                                            this.errors.phone = 'Enter 10 digits starting with 6-9 or 11 digits starting with 0.';
                                        } else {
                                            this.errors.phone = '';
                                        }
                                    },
                                    get phoneDigitCount() {
                                        return String(this.phone || '').replace(/\D/g, '').length;
                                    },
                                    validateMessage() {
                                        if (!this.message.trim()) {
                                            this.errors.message = 'Message is required.';
                                        } else if (this.message.trim().length < 5) {
                                            this.errors.message = 'Message must be at least 5 characters.';
                                        } else {
                                            this.errors.message = '';
                                        }
                                    },
                                    validateSubject() {
                                        const v = String(this.subject || '').trim();
                                        if (v && v.length < 3) {
                                            this.errors.subject = 'Subject must be at least 3 characters.';
                                        } else {
                                            this.errors.subject = '';
                                        }
                                    },
                                    get isContactValid() {
                                        if (!this.name.trim() || !this.email.trim() || !this.phone.trim() || !this.message.trim()) return false;
                                        if (this.name.trim().length < 2 || !/^(?=.*[a-zA-Z])[a-zA-Z\s\.\'\-]+$/.test(this.name.trim())) return false;
                                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) return false;
                                        if (!/^(0\d{10}|[6-9]\d{9})$/.test(this.phone.trim())) return false;
                                        if (this.subject.trim() && this.subject.trim().length < 3) return false;
                                        if (this.message.trim().length < 5) return false;
                                        return true;
                                    },
                                    submitForm(e) {
                                        this.validateName();
                                        this.validateEmail();
                                        this.validatePhone();
                                        this.validateSubject();
                                        this.validateMessage();
                                        if (this.errors.name || this.errors.email || this.errors.phone || this.errors.subject || this.errors.message) {
                                            e.preventDefault();
                                        }
                                    }
                                }" @submit="submitForm($event)">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Your Name <span class="text-red-500">*</span></label>
                                            <div class="group h-11" style="position: relative;">
                                                <i class="fas fa-user text-slate-400 dark:text-slate-500 group-focus-within:text-[#e04f3e] transition-colors text-sm" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); pointer-events: none; z-index: 10; display: flex; align-items: center; justify-content: center; width: 16px; height: 16px;"></i>
                                                <input type="text" name="name" x-model="name" @blur="validateName" @input="validateName" required class="w-full h-11 pr-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#e04f3e]/20 focus:border-[#e04f3e] transition-all" style="padding-left: 48px;" :class="errors.name ? 'border-red-500 ring-4 ring-red-500/10' : ''">
                                            </div>
                                            <p x-show="errors.name" x-text="errors.name" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1 mt-1.5" style="display: none;"></p>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Your Email <span class="text-red-500">*</span></label>
                                            <div class="group h-11" style="position: relative;">
                                                <i class="fas fa-envelope text-slate-400 dark:text-slate-500 group-focus-within:text-[#e04f3e] transition-colors text-sm" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); pointer-events: none; z-index: 10; display: flex; align-items: center; justify-content: center; width: 16px; height: 16px;"></i>
                                                <input type="email" name="email" x-model="email" @blur="validateEmail" @input="validateEmail" required class="w-full h-11 pr-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#e04f3e]/20 focus:border-[#e04f3e] transition-all" style="padding-left: 48px;" :class="errors.email ? 'border-red-500 ring-4 ring-red-500/10' : ''">
                                            </div>
                                            <p x-show="errors.email" x-text="errors.email" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1 mt-1.5" style="display: none;"></p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Your Phone <span class="text-red-500">*</span></label>
                                            <div class="group h-11" style="position: relative;">
                                                <i class="fas fa-phone text-slate-400 dark:text-slate-500 group-focus-within:text-[#e04f3e] transition-colors text-sm" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); pointer-events: none; z-index: 10; display: flex; align-items: center; justify-content: center; width: 16px; height: 16px;"></i>
                                                <input type="text" name="phone" x-model="phone" @blur="validatePhone" @input="phone = String(phone).replace(/\D/g, '').substring(0, 11); validatePhone()" required class="w-full h-11 pr-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#e04f3e]/20 focus:border-[#e04f3e] transition-all" style="padding-left: 48px;" :class="errors.phone ? 'border-red-500 ring-4 ring-red-500/10' : ''">
                                            </div>
                                            <p x-show="errors.phone" x-text="errors.phone" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1 mt-1.5" style="display: none;"></p>
                                            <p x-show="phone" class="px-1 text-[9px] font-bold uppercase tracking-widest" :class="/^(0\d{10}|[6-9]\d{9})$/.test(phone.trim()) ? 'text-emerald-500' : 'text-rose-500'">
                                                <span x-text="phoneDigitCount"></span> / 11 digits
                                                • <span x-text="11 - phoneDigitCount"></span> left
                                            </p>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Subject</label>
                                            <div class="group h-11" style="position: relative;">
                                                <i class="fas fa-heading text-slate-400 dark:text-slate-500 group-focus-within:text-[#e04f3e] transition-colors text-sm" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); pointer-events: none; z-index: 10; display: flex; align-items: center; justify-content: center; width: 16px; height: 16px;"></i>
                                                <input type="text" name="subject" x-model="subject" @blur="validateSubject" @input="validateSubject" class="w-full h-11 pr-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#e04f3e]/20 focus:border-[#e04f3e] transition-all" style="padding-left: 48px;" :class="errors.subject ? 'border-red-500 ring-4 ring-red-500/10' : ''">
                                            </div>
                                            <p x-show="errors.subject" x-text="errors.subject" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1 mt-1.5" style="display: none;"></p>
                                        </div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Message <span class="text-red-500">*</span></label>
                                        <div class="group" style="position: relative;">
                                            <i class="fas fa-comment-dots text-slate-400 dark:text-slate-500 group-focus-within:text-[#e04f3e] transition-colors text-sm" style="position: absolute; left: 18px; top: 16px; pointer-events: none; z-index: 10; display: flex; align-items: center; justify-content: center; width: 16px; height: 16px;"></i>
                                            <textarea name="message" x-model="message" @blur="validateMessage" @input="validateMessage" required rows="4" class="w-full pr-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#e04f3e]/20 focus:border-[#e04f3e] transition-all resize-none" style="padding-left: 48px; padding-top: 14px;" :class="errors.message ? 'border-red-500 ring-4 ring-red-500/10' : ''"></textarea>
                                        </div>
                                        <p x-show="errors.message" x-text="errors.message" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-1 mt-1.5" style="display: none;"></p>
                                    </div>
                                    <div class="flex justify-end pt-3">
                                        <button type="submit" :disabled="!isContactValid" class="w-full sm:w-auto px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest text-white bg-gradient-to-r from-red-500 to-orange-500 hover:scale-[1.02] active:scale-95 shadow-md hover:shadow-lg shadow-orange-500/10 hover:shadow-orange-500/20 transition-all border-none flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                                            <i class="fas fa-paper-plane text-xs"></i> Send Inquiry
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                             <div class="bg-white dark:bg-[#151515] p-8 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm text-center">
                                 <span class="material-symbols-rounded text-5xl text-slate-300 dark:text-slate-600 mb-3 block">lock</span>
                                 <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">Premium Contact Feature</h3>
                                 <p class="text-xs text-slate-500 max-w-md mx-auto mb-6">You need an active subscription with contact access to send inquiries to this creator.</p>
                                 <button onclick="Swal.fire({ title: 'Premium Feature', text: 'You need an active subscription with contact access to send inquiries.', icon: 'lock', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Upgrade Plan' }).then((r) => { if(r.isConfirmed) window.location.href='{{ session()->has('marketplace_user_id') ? route('marketplace.index').'#plans' : route('user.plans.index') }}'; })" class="px-6 py-2.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-red-500 to-orange-500 shadow-md hover:shadow-lg transition-all border-none">
                                     Upgrade Plan
                                 </button>
                             </div>
                        @endif
                    </div>
                    @endif

                </div>
            </div>

            <!-- Desktop Availability Sidebar -->
            <div class="hidden lg:block w-[300px] lg:w-[320px] shrink-0">
                @if($marketplace->hasContactAccess() || $isOwner)
                <div class="bg-white dark:bg-[#151515] border border-slate-100 dark:border-white/5 shadow-[0_10px_30px_-10px_rgba(0,0,0,0.05)] rounded-2xl p-6 lg:-mt-24 relative z-30">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-rounded text-green-500 text-[18px]">check_circle</span>
                        <span class="font-bold text-sm text-slate-900 dark:text-white">Available for Contact</span>
                    </div>
                    
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-full text-xs font-medium mb-6">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Available
                    </div>

                    <div class="space-y-3">
                        @if($canView)
                            <a href="tel:{{ $marketplace->number }}" class="block text-center w-full py-3 bg-[#e04f3e] hover:bg-red-600 text-white rounded-xl font-bold text-sm shadow-sm transition-colors">
                                Contact Now
                            </a>
                            @if(auth()->check() || session()->has('marketplace_user_id'))
                                <button @click="messageModal = true" class="block w-full text-center py-3 bg-white dark:bg-transparent border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white rounded-xl font-bold text-sm hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <span class="material-symbols-rounded text-[16px] align-middle mr-1">chat</span> Send Message
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="block text-center w-full py-3 bg-white dark:bg-transparent border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white rounded-xl font-bold text-sm hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <span class="material-symbols-rounded text-[16px] align-middle mr-1">chat</span> Send Message
                                </a>
                            @endif
                        @else
                            <button onclick="Swal.fire({ title: 'Premium Feature', text: 'You need an active subscription to access direct contact details.', icon: 'lock', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Upgrade Plan' }).then((r) => { if(r.isConfirmed) window.location.href='{{ route('marketplace.index') }}#plans'; })" class="w-full py-3 bg-slate-200 dark:bg-white/10 text-slate-500 dark:text-slate-400 rounded-xl font-bold text-sm text-center shadow-sm transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-rounded text-[18px]">lock</span> Contact Now
                            </button>
                            <button onclick="Swal.fire({ title: 'Premium Feature', text: 'You need an active subscription to access direct contact details.', icon: 'lock', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Upgrade Plan' }).then((r) => { if(r.isConfirmed) window.location.href='{{ route('marketplace.index') }}#plans'; })" class="w-full py-3 bg-transparent border border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 rounded-xl font-bold text-sm hover:bg-slate-50 dark:hover:bg-white/5 transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-rounded text-[16px]">lock</span> Send Message
                            </button>
                        @endif
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-white/5 space-y-4">
                        @if($canView)
                            <a href="mailto:{{ $marketplace->email }}" class="flex items-center gap-3 text-slate-500 dark:text-slate-400 text-sm hover:text-red-500 transition-colors">
                                <span class="material-symbols-rounded text-[18px]">mail</span>
                                <span class="truncate">{{ $marketplace->email ?? 'contact@example.com' }}</span>
                            </a>
                            <a href="tel:{{ $marketplace->number }}" class="flex items-center gap-3 text-slate-500 dark:text-slate-400 text-sm hover:text-red-500 transition-colors">
                                <span class="material-symbols-rounded text-[18px]">call</span>
                                <span class="truncate">{{ $marketplace->number ?? '+91 (555) 123-4567' }}</span>
                            </a>
                        @else
                            <div class="flex items-center gap-3 text-slate-400 dark:text-slate-600 text-sm">
                                <span class="material-symbols-rounded text-[18px]">mail</span>
                                <span class="truncate">Hidden (Premium Only)</span>
                            </div>
                            <div class="flex items-center gap-3 text-slate-400 dark:text-slate-600 text-sm">
                                <span class="material-symbols-rounded text-[18px]">call</span>
                                <span class="truncate">Hidden (Premium Only)</span>
                            </div>
                        @endif
                        
                        @if($marketplace->facebook_link || $marketplace->instagram_link || $marketplace->twitter_link)
                            <div class="flex items-center gap-3 pt-4">
                                @if($marketplace->facebook_link)
                                    <a href="{{ Str::startsWith($marketplace->facebook_link, ['http://', 'https://']) ? $marketplace->facebook_link : 'https://'.$marketplace->facebook_link }}" target="_blank" class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        <i class="fab fa-facebook-f text-xs"></i>
                                    </a>
                                @endif
                                @if($marketplace->instagram_link)
                                    <a href="{{ Str::startsWith($marketplace->instagram_link, ['http://', 'https://']) ? $marketplace->instagram_link : 'https://'.$marketplace->instagram_link }}" target="_blank" class="w-8 h-8 rounded-full bg-pink-50 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400 flex items-center justify-center hover:bg-pink-600 hover:text-white transition-all shadow-sm">
                                        <i class="fab fa-instagram text-xs"></i>
                                    </a>
                                @endif
                                @if($marketplace->twitter_link)
                                    <a href="{{ Str::startsWith($marketplace->twitter_link, ['http://', 'https://']) ? $marketplace->twitter_link : 'https://'.$marketplace->twitter_link }}" target="_blank" class="w-8 h-8 rounded-full bg-sky-50 dark:bg-sky-900/20 text-sky-500 dark:text-sky-400 flex items-center justify-center hover:bg-sky-500 hover:text-white transition-all shadow-sm">
                                        <i class="fab fa-twitter text-xs"></i>
                                    </a>
                                @endif
                            </div>
                        @endif

                        @if($marketplace->portfolio_url || $marketplace->website_url)
                        <div class="flex items-center gap-3 text-sm">
                            <span class="material-symbols-rounded text-[18px] text-slate-500 dark:text-slate-400">language</span>
                            <a href="{{ $marketplace->portfolio_url ?? $marketplace->website_url }}" target="_blank" class="text-[#e04f3e] hover:underline truncate">{{ $marketplace->portfolio_url ?? 'Website Link' }}</a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Message Modal -->
    <template x-teleport="body">
        <div x-show="messageModal" style="display: none;" class="fixed inset-0 z-[999999] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="messageModal = false"></div>
            <div class="relative w-[95%] max-w-md mx-auto bg-white dark:bg-[#111] rounded-[2rem] lg:rounded-3xl shadow-2xl flex flex-col overflow-hidden h-[75vh] lg:h-[500px]">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-black/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-white/10 overflow-hidden flex items-center justify-center shrink-0">
                        @if($marketplace->image)
                            <img src="{{ $marketplace->photoUrl() }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-[#e04f3e] font-bold text-sm bg-[#e04f3e]/10 w-full h-full flex items-center justify-center">
                                {{ strtoupper(substr($marketplace->name, 0, 2)) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white">{{ $marketplace->name }}</h3>
                            @if($marketplace->type)
                                <span class="text-[10px] font-bold px-2 py-0.5 bg-[#e04f3e]/10 text-[#e04f3e] rounded-md tracking-wide">{{ $marketplace->type }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <button @click="messageModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-[#e04f3e] hover:bg-red-600 text-white transition-colors">
                    <span class="material-symbols-rounded text-lg">close</span>
                </button>
            </div>

            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-5 space-y-4" id="messages-container">
                @if(isset($messages) && $messages->count() > 0)
                    @foreach($messages as $msg)
                        @php
                            $isOwn = false;
                            if (auth()->check() && $msg->user_id == auth()->id() && $msg->sender_type === 'user') $isOwn = true;
                            elseif (session('marketplace_user_id') && $msg->sender_marketplace_id == session('marketplace_user_id')) $isOwn = true;
                        @endphp
                        @if($isOwn)
                            <div class="flex justify-end gap-2 items-center" id="msg-container-{{ $msg->id }}">
                                <div class="bg-[#e04f3e] text-white p-3 rounded-2xl rounded-tr-sm max-w-[80%] shadow-sm">
                                    <p class="text-sm" id="msg-text-{{ $msg->id }}">{!! $msg->message !!}</p>
                                    <span class="text-[9px] opacity-70 mt-1 block text-right">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <!-- Options menu: dropdown is created by JS on click -->
                                <div class="relative shrink-0">
                                    <button onclick="showMsgMenu(this, {{ $msg->id }}, '{{ addslashes(htmlspecialchars_decode($msg->message, ENT_QUOTES)) }}')" class="msg-dots-btn p-1 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 dark:text-slate-500 transition-colors">
                                        <span class="material-symbols-rounded text-[18px]">more_vert</span>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="flex justify-start gap-2" id="msg-container-{{ $msg->id }}">
                                <div class="w-6 h-6 rounded-full bg-slate-200 shrink-0 overflow-hidden flex items-center justify-center">
                                    @if($marketplace->image)
                                        <img src="{{ $marketplace->photoUrl() }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[#e04f3e] font-bold text-[9px] bg-[#e04f3e]/10 w-full h-full flex items-center justify-center">
                                            {{ strtoupper(substr($marketplace->name, 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="bg-slate-100 dark:bg-white/5 text-slate-800 dark:text-slate-200 p-3 rounded-2xl rounded-tl-sm max-w-[80%] shadow-sm">
                                    <p class="text-sm" id="msg-text-{{ $msg->id }}">{!! $msg->message !!}</p>
                                    <span class="text-[9px] opacity-50 mt-1 block">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="h-full flex flex-col items-center justify-center text-center opacity-50 text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-rounded text-4xl mb-2">forum</span>
                        <p class="text-xs font-bold uppercase tracking-widest">Start a conversation</p>
                        <p class="text-[10px] mt-1">Send a message to {{ $marketplace->name }}</p>
                    </div>
                @endif
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white dark:bg-[#111] border-t border-slate-100 dark:border-white/5" x-data="portfolioChat()">
                <form @submit.prevent="submitMessage" class="flex flex-col gap-2">
                    <div class="flex gap-2 items-center">
                        <div class="flex-1 relative">
                            <textarea x-model="msg" @keydown.enter.prevent="submitMessage" name="message" rows="2" placeholder="Type your message..." data-no-hint="true" class="w-full px-4 py-3 bg-slate-100 dark:bg-white/5 rounded-xl text-sm focus:outline-none focus:ring-2 ring-red-500/50 text-slate-900 dark:text-white resize-none custom-scrollbar"></textarea>
                        </div>
                        <button type="submit" :disabled="isSending" class="w-12 h-[52px] flex items-center justify-center bg-[#e04f3e] hover:bg-red-600 disabled:opacity-50 text-white rounded-xl shadow-md transition-colors shrink-0">
                            <span class="material-symbols-rounded text-xl" x-show="!isSending">send</span>
                            <i class="fas fa-spinner fa-spin" x-show="isSending" style="display: none;"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </template>
    <!-- Profile Image Modal -->
    <template x-teleport="body">
        <div x-show="profileModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="profileModal" x-transition.opacity class="absolute inset-0 bg-black/90 backdrop-blur-sm" @click="profileModal = false"></div>
            <div x-show="profileModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-90 translate-y-4"
                 class="relative max-w-[280px] md:max-w-[400px] w-full mx-auto z-10 flex flex-col items-center">
                 
                <button @click="profileModal = false" class="absolute -top-12 right-0 w-10 h-10 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-colors">
                    <span class="material-symbols-rounded">close</span>
                </button>

                @if($marketplace->image)
                    <img src="{{ $marketplace->photoUrl() }}" class="w-full h-auto object-cover rounded-2xl shadow-2xl">
                @else
                    <div class="w-full aspect-square flex items-center justify-center bg-blue-500 text-white text-8xl md:text-9xl font-bold rounded-2xl shadow-2xl">{{ $marketplace->getInitials() }}</div>
                @endif
            </div>
        </div>
    </template>

    <!-- Cover Image Modal -->
    <template x-teleport="body">
        <div x-show="coverModal" style="display: none;" class="fixed inset-0 z-[40] flex items-center justify-center">
            <div x-show="coverModal" x-transition.opacity class="absolute inset-0 bg-black/95 backdrop-blur-sm" @click="coverModal = false"></div>
            
            <div x-show="coverModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-90 translate-y-4"
                 class="relative z-10 flex items-center justify-center w-full h-full p-4 md:p-8 pointer-events-none">
                 
                <div class="relative inline-block pointer-events-auto">
                    <!-- Close Button overlaying the image -->
                    <button @click="coverModal = false" class="absolute top-2 right-2 md:top-4 md:right-4 z-50 w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full bg-black/60 text-white hover:bg-black/90 transition-colors backdrop-blur-md shadow-lg cursor-pointer">
                        <span class="material-symbols-rounded text-base md:text-lg">close</span>
                    </button>
                    
                    <img src="{{ $marketplace->coverUrl() ?? 'https://placehold.co/1200x800?text=Cover' }}" class="w-auto h-auto max-w-[90vw] md:max-w-[80vw] max-h-[70vh] md:max-h-[75vh] object-contain rounded-xl shadow-2xl">
                </div>
            </div>
        </div>
    </template>

    <!-- Gallery Lightbox Modal -->
    <template x-teleport="body">
        <div x-show="galleryModal" style="display: none;" class="fixed inset-0 z-[40] flex items-center justify-center">
            <div x-show="galleryModal" x-transition.opacity class="absolute inset-0 bg-black/95 backdrop-blur-sm" @click="galleryModal = false"></div>
            
            <div x-show="galleryModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-90 translate-y-4"
                 class="relative z-10 flex items-center justify-center w-full h-full p-4 md:p-8 pointer-events-none">
                 
                <div class="relative inline-block pointer-events-auto">
                    <!-- Close Button overlaying the image -->
                    <button @click="galleryModal = false" class="absolute top-2 right-2 md:top-4 md:right-4 z-50 w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full bg-black/60 text-white hover:bg-black/90 transition-colors backdrop-blur-md shadow-lg cursor-pointer">
                        <span class="material-symbols-rounded text-base md:text-lg">close</span>
                    </button>
                    
                    <img :src="activeGalleryImage" class="w-auto h-auto max-w-[90vw] md:max-w-[80vw] max-h-[70vh] md:max-h-[75vh] object-contain rounded-xl shadow-2xl">
                </div>
            </div>
        </div>
    </template>
</div>


<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Page-scoped icon visibility fix (this page only).
       The layout globally forces `.material-symbols-rounded` to `color: inherit`,
       which beats Tailwind's text-color utilities and leaves icons dark-on-dark
       in dark mode. */
    html:not(.dark) #marketplace-portfolio span.material-symbols-rounded.text-slate-200 { color: #e2e8f0 !important; }
    html:not(.dark) #marketplace-portfolio span.material-symbols-rounded.text-slate-300 { color: #d1d5db !important; }
    html:not(.dark) #marketplace-portfolio span.material-symbols-rounded.text-slate-500 { color: #64748b !important; }
    html:not(.dark) #marketplace-portfolio span.material-symbols-rounded.text-green-500 { color: #22c55e !important; }
    .dark #marketplace-portfolio span.material-symbols-rounded.text-slate-200 { color: rgba(255,255,255,0.1) !important; }
    .dark #marketplace-portfolio span.material-symbols-rounded.text-slate-300 { color: #475569 !important; }
    .dark #marketplace-portfolio span.material-symbols-rounded.text-slate-500 { color: #94a3b8 !important; }
    .dark #marketplace-portfolio span.material-symbols-rounded.text-green-500 { color: #22c55e !important; }

    /* Contact form auto-hints (injected by validation.js on focus):
       let the fixed-height field wrapper grow so the hint flows below the
       input instead of overflowing onto the next label, keep the field icon
       pinned to the input's center, and style the hint as a native helper chip. */
    #contact-inquiry .group.h-11:has(.auto-hint-msg) { height: auto; }
    #contact-inquiry .group.h-11 > i.fas { top: 22px !important; }
    #contact-inquiry .auto-hint-msg {
        margin: 8px 4px 4px;
        padding: 8px 12px;
        border-radius: 12px;
        background: rgba(249,115,22,0.07);
        font-size: 9px;
        line-height: 1.6;
    }
    .dark #contact-inquiry .auto-hint-msg { background: rgba(249,115,22,0.10); }
</style>
@endsection

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush

@push('script')
<script>
function sharePortfolio(url) {
    @if(!$hasSubscription && !$isOwner)
        Swal.fire({
            html: `
            <div class="relative bg-slate-50 dark:bg-[#151515] rounded-[2rem] p-6 text-center overflow-hidden">
                <!-- Decorative Background Glows -->
                <div class="absolute top-0 right-0 w-24 h-24 bg-red-500/10 dark:bg-red-500/20 blur-3xl rounded-full"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-purple-500/10 dark:bg-purple-500/20 blur-3xl rounded-full"></div>
                
                <div class="relative w-20 h-20 mx-auto mb-4">
                    <!-- Glowing Rings -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-red-500 to-purple-500 rounded-full blur-xl opacity-40 animate-pulse"></div>
                    <div class="absolute inset-2 bg-gradient-to-tr from-red-500 to-purple-500 rounded-full opacity-20 animate-ping" style="animation-duration: 3s;"></div>
                    
                    <div class="relative w-full h-full bg-white dark:bg-[#1A1A1A] rounded-full flex items-center justify-center border-[3px] border-slate-50 dark:border-[#151515] shadow-inner z-10">
                        <div class="w-12 h-12 bg-gradient-to-tr from-red-500 to-purple-500 rounded-full flex items-center justify-center shadow-lg">
                            <span class="material-symbols-rounded text-2xl text-white">share</span>
                        </div>
                    </div>
                </div>
                
                <h2 class="text-xl font-black text-slate-900 dark:text-white mb-2 tracking-tight relative z-10" style="font-family: inherit;">Premium Feature</h2>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-6 leading-relaxed px-1 relative z-10">
                    Sharing professional profiles is an exclusive feature for our <strong class="text-slate-900 dark:text-white">Premium Subscribers</strong>. Upgrade your plan to unlock this feature.
                </p>
                
                <a href="{{ url('client/plans') }}" class="relative w-full group overflow-hidden flex items-center justify-center gap-2 p-3 bg-gradient-to-r from-red-600 via-purple-600 to-red-600 hover:bg-right text-white rounded-xl border-2 border-slate-900 dark:border-white transition-all duration-500 active:scale-95 shadow-[0_10px_20px_-10px_rgba(220,38,38,0.5)] z-10" style="background-size: 200% auto; text-decoration: none;">
                    <span class="font-black uppercase tracking-[0.2em] text-[10px] text-white drop-shadow-md">Unlock Premium</span>
                    <span class="material-symbols-rounded text-[16px] text-white">bolt</span>
                </a>
                
                <p class="text-[8px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-4 relative z-10">Secure & Verified Platform</p>
            </div>
            `,
            showConfirmButton: false,
            background: 'transparent',
            padding: 0,
            customClass: {
                popup: 'rounded-[2rem] bg-transparent p-0'
            }
        });
    @else
        if (navigator.share) {
            navigator.share({
                title: '{{ addslashes($marketplace->name) }} - Portfolio',
                text: 'Check out this professional portfolio on {{ gs('site_name') }}!',
                url: url
            }).catch((error) => console.log('Error sharing:', error));
        } else {
            // Fallback for desktop/unsupported browsers
            navigator.clipboard.writeText(url).then(() => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'bottom-end',
                        icon: 'success',
                        title: 'Portfolio link copied!',
                        showConfirmButton: false,
                        timer: 2500,
                        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a'
                    });
                } else {
                    alert('Portfolio link copied to clipboard!');
                }
            });
        }
    @endif
}

document.addEventListener('alpine:init', () => {
    Alpine.data('portfolioChat', () => ({
        msg: '',
        isSending: false,
        submitMessage() {
            if (!this.msg.trim() || this.isSending) return;
            this.isSending = true;
            fetch('{{ route("marketplace.message.send", $marketplace->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: this.msg })
            }).then(res => res.json()).then(data => {
                this.isSending = false;
                if (data.status === 'success') {
                    this.msg = '';
                    var container = document.getElementById('messages-container');
                    var msgId = data.message.id;
                    var safeMsg = data.message.message.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
                    var msgEl = document.createElement('div');
                    msgEl.className = 'flex justify-end gap-2 items-center';
                    msgEl.id = 'msg-container-' + msgId;
                    msgEl.innerHTML = '<div class="bg-[#e04f3e] text-white p-3 rounded-2xl rounded-tr-sm max-w-[80%] shadow-sm">'
                        + '<p class="text-sm" id="msg-text-' + msgId + '">' + data.message.message + '</p>'
                        + '<span class="text-[9px] opacity-70 mt-1 block text-right">Just now</span>'
                        + '</div>'
                        + '<div class="relative shrink-0">'
                        + '<button onclick="showMsgMenu(this, ' + msgId + ', \'' + safeMsg + '\')" class="msg-dots-btn p-1 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 dark:text-slate-500 transition-colors">'
                        + '<span class="material-symbols-rounded text-[18px]">more_vert</span>'
                        + '</button>'
                        + '</div>';
                    // Remove empty state
                    var emptyState = container.querySelector('.opacity-50.h-full');
                    if (emptyState) emptyState.remove();
                    container.appendChild(msgEl);
                    container.scrollTop = container.scrollHeight;
                }
            }).catch(() => { this.isSending = false; });
        }
    }));
});
var _activeMsgMenu = null;

function showMsgMenu(btn, msgId, msgText) {
    // Close any existing menu first
    closeMsgMenus();

    // Create dropdown element dynamically
    var dd = document.createElement('div');
    dd.className = 'msg-dropdown-live';
    dd.style.cssText = 'position:fixed;z-index:9999999;min-width:120px;background:#fff;border-radius:10px;box-shadow:0 4px 24px rgba(0,0,0,0.15);border:1px solid #e2e8f0;padding:4px 0;';
    if (document.documentElement.classList.contains('dark')) {
        dd.style.background = '#1a1d24';
        dd.style.borderColor = 'rgba(255,255,255,0.1)';
    }

    dd.innerHTML = '<button style="display:flex;align-items:center;gap:8px;width:100%;padding:6px 14px;font-size:12px;font-weight:600;color:#475569;border:none;background:none;cursor:pointer;text-align:left;" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'none\'" class="msg-menu-edit"><span class="material-symbols-rounded" style="font-size:15px;color:#6366f1;">edit</span> Edit</button>'
        + '<button style="display:flex;align-items:center;gap:8px;width:100%;padding:6px 14px;font-size:12px;font-weight:600;color:#ef4444;border:none;background:none;cursor:pointer;text-align:left;" onmouseover="this.style.background=\'#fef2f2\'" onmouseout="this.style.background=\'none\'" class="msg-menu-delete"><span class="material-symbols-rounded" style="font-size:15px;">delete</span> Delete</button>';

    document.body.appendChild(dd);

    // Position near button
    var rect = btn.getBoundingClientRect();
    dd.style.top = rect.top + 'px';
    dd.style.left = (rect.left - dd.offsetWidth - 4) + 'px';

    // If it goes off-screen left, show on right
    if (parseInt(dd.style.left) < 4) {
        dd.style.left = (rect.right + 4) + 'px';
    }

    // Bind actions
    dd.querySelector('.msg-menu-edit').onclick = function() {
        closeMsgMenus();
        editMessage(msgId, msgText);
    };
    dd.querySelector('.msg-menu-delete').onclick = function() {
        closeMsgMenus();
        deleteMessage(msgId);
    };

    _activeMsgMenu = dd;
}

function closeMsgMenus() {
    if (_activeMsgMenu) {
        _activeMsgMenu.remove();
        _activeMsgMenu = null;
    }
}

// Close menus when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.msg-dots-btn') && _activeMsgMenu) {
        closeMsgMenus();
    }
});

function editMessage(id, text) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Edit Message',
            input: 'textarea',
            inputValue: text,
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Save',
            confirmButtonColor: '#e04f3e',
            showLoaderOnConfirm: true,
            preConfirm: (newMessage) => {
                return fetch('{{ url("marketplace/user-message/update") }}/' + id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: newMessage })
                })
                .then(async response => {
                    const data = await response.json().catch(() => null);
                    if (!response.ok) {
                        throw new Error((data && data.message) || response.statusText)
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`)
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const el = document.getElementById('msg-text-' + id);
                if (el) el.innerHTML = result.value.data.message;
            }
        })
    } else {
        var newMessage = prompt("Edit your message:", text);
        if (newMessage !== null && newMessage !== text) {
            fetch('{{ url("marketplace/user-message/update") }}/' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: newMessage })
            }).then(res => res.json()).then(data => {
                if(data.status === 'success') {
                    const el = document.getElementById('msg-text-' + id);
                    if (el) el.innerHTML = data.data.message;
                }
            });
        }
    }
}

function deleteMessage(id) {
    const doDelete = () => {
        fetch('{{ url("marketplace/user-message/delete") }}/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(res => res.json()).then(data => {
            if(data.status === 'success') {
                const el = document.getElementById('msg-container-' + id);
                if (el) el.remove();
            }
        }).catch(err => {
            alert("Delete failed. Please try again.");
        });
    };

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Message?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e04f3e',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Delete',
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a'
        }).then((result) => {
            if (result.isConfirmed) doDelete();
        });
    } else {
        if (confirm('Are you sure you want to delete this message?')) doDelete();
    }
}
</script>
@endpush

