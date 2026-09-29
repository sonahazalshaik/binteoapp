@extends('layouts.app')

@section('content')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<style>
    select {
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        background-image: none !important;
    }
    select::-ms-expand {
        display: none !important;
    }
</style>


<div id="marketplace-index" class="min-h-screen bg-white dark:bg-[#0A0A0A] transition-colors duration-500 pb-10 md:pb-2" x-data="marketplaceIndexPurchase()" x-init="init()">
    <!-- Hero Section -->
    <div class="relative pt-16 md:pt-20 pb-8 md:pb-20 bg-gradient-to-r from-purple-600 via-orange-500 to-red-500 text-white">
        <!-- Premium Gradient Background -->
        <div class="absolute inset-0 opacity-40 mix-blend-overlay">
            <div class="absolute inset-0" style="background: radial-gradient(circle at 0% 0%, rgba(255,255,255,0.2) 0%, transparent 50%), radial-gradient(circle at 100% 100%, rgba(255,255,255,0.2) 0%, transparent 50%);"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 md:px-6 text-center">
            <h1 class="text-2xl md:text-4xl font-black text-white mb-2 md:mb-3 tracking-tight animate-in fade-in slide-in-from-top-10 duration-1000">
                Profile Marketplace
            </h1>
            <p class="text-xs md:text-lg text-white/90 font-medium mb-4 md:mb-8 max-w-4xl mx-auto animate-in fade-in slide-in-from-top-10 duration-1000 delay-150 leading-relaxed">
                Connect with professional profiles, influencers, actors, and investors for your next project.
            </p>
            
            <!-- Responsive Action Center -->
            <div class="relative z-[60] mb-6 md:mb-8 animate-in fade-in zoom-in duration-1000 delay-300">
                <div class="flex flex-wrap items-center justify-center gap-2 md:gap-3">
                    @if($talent)
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="px-4 md:px-6 py-2 md:py-3 bg-white text-[#9333ea] rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-1.5 md:gap-2">
                                <span class="material-symbols-rounded text-base md:text-lg">account_circle</span>
                                {{ $talent->name }}
                                <span class="material-symbols-rounded text-base md:text-lg transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-[calc(100vw-3rem)] max-w-[16rem] bg-white dark:bg-[#151515] border border-slate-100 dark:border-white/5 rounded-3xl shadow-2xl p-3 md:p-4 z-[100] animate-in fade-in zoom-in duration-200">
                                <a href="{{ route('marketplace.dashboard') }}" class="flex items-center gap-3 p-4 hover:bg-slate-50 dark:hover:bg-white/5 rounded-2xl transition-all text-slate-700 dark:text-white">
                                    <span class="material-symbols-rounded text-slate-400">dashboard</span>
                                    <span class="text-xs font-black uppercase tracking-widest">Dashboard</span>
                                </a>
                                <form action="{{ route('marketplace.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 p-4 hover:bg-rose-50 dark:hover:bg-rose-500/10 text-rose-600 rounded-2xl transition-all">
                                        <span class="material-symbols-rounded">logout</span>
                                        <span class="text-xs font-black uppercase tracking-widest">Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('marketplace.register') }}" class="px-4 md:px-6 py-2 md:py-3 bg-white text-[#9333ea] rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-1.5 md:gap-2">
                            <span class="material-symbols-rounded text-base md:text-lg">person_add</span>
                            Create Profile
                        </a>
                        <a href="{{ route('marketplace.login') }}" class="px-4 md:px-6 py-2 md:py-3 bg-white/10 text-white rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest border border-white/20 hover:bg-white/20 active:scale-95 transition-all flex items-center gap-1.5 md:gap-2">
                            <span class="material-symbols-rounded text-base md:text-lg">key</span>
                            Profile Login
                        </a>
                    @endif
                    <a href="#plans" class="h-9 md:h-10 px-5 md:px-6 bg-black text-white rounded-xl font-black text-[10px] md:text-[10px] flex items-center justify-center gap-1.5 md:gap-2 shadow-2xl hover:scale-105 transition-all border border-white/10">
                        <span class="material-symbols-rounded text-yellow-500 text-sm md:text-base">payments</span>
                        View Plans
                        <span class="material-symbols-rounded text-orange-500 text-sm md:text-base">arrow_forward</span>
                    </a>
                </div>
            </div>
            
            <!-- Responsive Stats Bar -->
            <div class="max-w-4xl mx-auto bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-3 md:p-6 shadow-2xl animate-in fade-in slide-in-from-bottom-10 duration-1000 delay-500">
                <div class="grid grid-cols-3 gap-2 md:gap-8 md:divide-x divide-white/10">
                    <div class="px-1 md:px-2">
                        <div class="text-lg md:text-2xl font-black text-white mb-0.5">{{ number_format($stats['profiles']) }}+</div>
                        <div class="text-[7px] md:text-[9px] font-black text-white/60 uppercase tracking-widest">Profiles Active</div>
                    </div>
                    <div class="px-1 md:px-2">
                        <div class="text-lg md:text-2xl font-black text-white mb-0.5">{{ number_format($stats['featured']) }}</div>
                        <div class="text-[7px] md:text-[9px] font-black text-white/60 uppercase tracking-widest">Handpicked Profiles</div>
                    </div>
                    <div class="px-1 md:px-2">
                        <div class="text-lg md:text-2xl font-black text-white mb-0.5">{{ number_format($stats['services']) }}+</div>
                        <div class="text-[7px] md:text-[9px] font-black text-white/60 uppercase tracking-widest">Services Available</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile-First Search & Filter Section -->
    <div class="max-w-7xl mx-auto px-6 -mt-8 relative z-20">
        <form action="{{ route('marketplace.index') }}" method="GET" class="bg-white dark:bg-[#121212] rounded-2xl shadow-xl shadow-indigo-500/10 p-4 md:p-8 space-y-4 border border-indigo-500/30 dark:border-indigo-500/40">
            <!-- Search Bar -->
            <div class="relative">
                <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-white/70">search</span>
                <input type="text" name="search" value="{{ request()->search }}" placeholder="Search profiles..." class="w-full pl-12 pr-6 py-4 bg-gray-50 dark:bg-black/20 rounded-xl border-none text-sm font-bold text-slate-900 dark:text-white placeholder:text-gray-400 focus:ring-2 focus:ring-orange-500/20 transition-all">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 px-4 py-2 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-indigo-700 transition-all">Search</button>
            </div>
 
            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="relative">
                    <select name="type" onchange="this.form.submit()" class="w-full pl-6 pr-10 py-3 bg-gray-50 dark:bg-black/20 rounded-xl border-none text-xs font-black text-slate-700 dark:text-white/70 appearance-none cursor-pointer">
                        <option @selected(!request()->type || request()->type == 'All Roles')>All Roles</option>
                        <option @selected(request()->type == 'Actor')>Actor</option>
                        <option @selected(request()->type == 'Actress')>Actress</option>
                        <option @selected(request()->type == 'Director')>Director</option>
                        <option @selected(request()->type == 'Producer')>Producer</option>
                        <option @selected(request()->type == 'Executive Producer')>Executive Producer</option>
                        <option @selected(request()->type == 'Screenwriter')>Screenwriter</option>
                        <option @selected(request()->type == 'Dialogue Writer')>Dialogue Writer</option>
                        <option @selected(request()->type == 'Cinematographer (DOP)')>Cinematographer (DOP)</option>
                        <option @selected(request()->type == 'Editor')>Editor</option>
                        <option @selected(request()->type == 'Colorist')>Colorist</option>
                        <option @selected(request()->type == 'VFX Artist')>VFX Artist</option>
                        <option @selected(request()->type == 'Motion Graphics Designer')>Motion Graphics Designer</option>
                        <option @selected(request()->type == 'Thumbnail Designer')>Thumbnail Designer</option>
                        <option @selected(request()->type == 'Music Director')>Music Director</option>
                        <option @selected(request()->type == 'Singer')>Singer</option>
                        <option @selected(request()->type == 'Rap Artist')>Rap Artist</option>
                        <option @selected(request()->type == 'Lyricist')>Lyricist</option>
                        <option @selected(request()->type == 'Sound Designer')>Sound Designer</option>
                        <option @selected(request()->type == 'Background Score Composer')>Background Score Composer</option>
                        <option @selected(request()->type == 'Choreographer')>Choreographer</option>
                        <option @selected(request()->type == 'Dance Crew')>Dance Crew</option>
                        <option @selected(request()->type == 'Makeup Artist')>Makeup Artist</option>
                        <option @selected(request()->type == 'Costume Designer')>Costume Designer</option>
                        <option @selected(request()->type == 'Stylist')>Stylist</option>
                        <option @selected(request()->type == 'Photographer')>Photographer</option>
                        <option @selected(request()->type == 'Casting Director')>Casting Director</option>
                        <option @selected(request()->type == 'Assistant Director')>Assistant Director</option>
                        <option @selected(request()->type == 'Production Manager')>Production Manager</option>
                        <option @selected(request()->type == 'Line Producer')>Line Producer</option>
                        <option @selected(request()->type == 'Short Film Creator')>Short Film Creator</option>
                        <option @selected(request()->type == 'Reel Creator')>Reel Creator</option>
                        <option @selected(request()->type == 'YouTuber')>YouTuber</option>
                        <option @selected(request()->type == 'Influencer')>Influencer</option>
                        <option @selected(request()->type == 'Brand Collaborator')>Brand Collaborator</option>
                        <option @selected(request()->type == 'OTT Partner')>OTT Partner</option>
                        <option @selected(request()->type == 'Distributor')>Distributor</option>
                        <option @selected(request()->type == 'Investor')>Investor</option>
                        <option @selected(request()->type == 'Event Organizer')>Event Organizer</option>
                        <option @selected(request()->type == 'Studio Owner')>Studio Owner</option>
                        <option @selected(request()->type == 'Acting Trainer')>Acting Trainer</option>
                        <option @selected(request()->type == 'Film School')>Film School</option>
                        <option @selected(request()->type == 'Voice Over Artist')>Voice Over Artist</option>
                        <option @selected(request()->type == 'Dub Artist')>Dub Artist</option>
                        <option @selected(request()->type == 'Anchor / Host')>Anchor / Host</option>
                        <option @selected(request()->type == 'Meme Creator')>Meme Creator</option>
                        <option @selected(request()->type == 'Marketing Partner')>Marketing Partner</option>
                    </select>
                    <span class="material-symbols-rounded absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-white/70 pointer-events-none text-lg">expand_more</span>
                </div>
                <div class="relative">
                    <select name="location" onchange="this.form.submit()" class="w-full pl-6 pr-10 py-3 bg-gray-50 dark:bg-black/20 rounded-xl border-none text-xs font-black text-slate-700 dark:text-white/70 appearance-none cursor-pointer">
                        <option @selected(!request()->location || request()->location == 'All Locations')>All Locations</option>
                        <option @selected(request()->location == 'Andaman and Nicobar Islands')>Andaman and Nicobar Islands</option>
                        <option @selected(request()->location == 'Andhra Pradesh')>Andhra Pradesh</option>
                        <option @selected(request()->location == 'Arunachal Pradesh')>Arunachal Pradesh</option>
                        <option @selected(request()->location == 'Assam')>Assam</option>
                        <option @selected(request()->location == 'Bihar')>Bihar</option>
                        <option @selected(request()->location == 'Chandigarh')>Chandigarh</option>
                        <option @selected(request()->location == 'Chhattisgarh')>Chhattisgarh</option>
                        <option @selected(request()->location == 'Dadra and Nagar Haveli and Daman and Diu')>Dadra and Nagar Haveli and Daman and Diu</option>
                        <option @selected(request()->location == 'Delhi')>Delhi</option>
                        <option @selected(request()->location == 'Goa')>Goa</option>
                        <option @selected(request()->location == 'Gujarat')>Gujarat</option>
                        <option @selected(request()->location == 'Haryana')>Haryana</option>
                        <option @selected(request()->location == 'Himachal Pradesh')>Himachal Pradesh</option>
                        <option @selected(request()->location == 'Jammu and Kashmir')>Jammu and Kashmir</option>
                        <option @selected(request()->location == 'Jharkhand')>Jharkhand</option>
                        <option @selected(request()->location == 'Karnataka')>Karnataka</option>
                        <option @selected(request()->location == 'Kerala')>Kerala</option>
                        <option @selected(request()->location == 'Ladakh')>Ladakh</option>
                        <option @selected(request()->location == 'Lakshadweep')>Lakshadweep</option>
                        <option @selected(request()->location == 'Madhya Pradesh')>Madhya Pradesh</option>
                        <option @selected(request()->location == 'Maharashtra')>Maharashtra</option>
                        <option @selected(request()->location == 'Manipur')>Manipur</option>
                        <option @selected(request()->location == 'Meghalaya')>Meghalaya</option>
                        <option @selected(request()->location == 'Mizoram')>Mizoram</option>
                        <option @selected(request()->location == 'Nagaland')>Nagaland</option>
                        <option @selected(request()->location == 'Odisha')>Odisha</option>
                        <option @selected(request()->location == 'Puducherry')>Puducherry</option>
                        <option @selected(request()->location == 'Punjab')>Punjab</option>
                        <option @selected(request()->location == 'Rajasthan')>Rajasthan</option>
                        <option @selected(request()->location == 'Sikkim')>Sikkim</option>
                        <option @selected(request()->location == 'Tamil Nadu')>Tamil Nadu</option>
                        <option @selected(request()->location == 'Telangana')>Telangana</option>
                        <option @selected(request()->location == 'Tripura')>Tripura</option>
                        <option @selected(request()->location == 'Uttar Pradesh')>Uttar Pradesh</option>
                        <option @selected(request()->location == 'Uttarakhand')>Uttarakhand</option>
                        <option @selected(request()->location == 'West Bengal')>West Bengal</option>
                    </select>
                    <span class="material-symbols-rounded absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-white/70 pointer-events-none text-lg">expand_more</span>
                </div>
                <div class="relative">
                    <select class="w-full pl-6 pr-10 py-3 bg-gray-50 dark:bg-black/20 rounded-xl border-none text-xs font-black text-slate-700 dark:text-white/70 appearance-none cursor-pointer">
                        <option>All Ratings</option>
                        <option>5 Stars</option>
                        <option>4 Stars & Up</option>
                        <option>3 Stars & Up</option>
                    </select>
                    <span class="material-symbols-rounded absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-white/70 pointer-events-none text-lg">expand_more</span>
                </div>
            </div>
        </form>
    </div>

    <!-- Featured Creators Section -->
    <div class="max-w-7xl mx-auto px-6 pt-0 pb-0 overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center">
                        <span class="material-symbols-rounded">recommend</span>
                    </div>
                    <span class="text-xs font-black text-orange-500 uppercase tracking-[0.25em]">Handpicked Profiles</span>
                </div>
                <h2 class="text-3xl md:text-5xl font-black tracking-tight dark:text-white uppercase">Featured Profiles</h2>
            </div>
            <a href="{{ route('marketplace.featured') }}" class="group relative flex items-center gap-3 px-8 py-3.5 bg-gradient-to-r from-orange-500 to-rose-500 hover:from-orange-600 hover:to-rose-600 rounded-full text-xs font-black uppercase tracking-[0.2em] text-white shadow-xl shadow-orange-500/20 transition-all duration-300 overflow-hidden hover:scale-105 active:scale-95">
                <span class="relative z-10">View All Featured</span>
                <span class="material-symbols-rounded text-lg relative z-10 group-hover:translate-x-1.5 transition-transform duration-500">arrow_forward</span>
            </a>
        </div>
        
        <div class="relative" x-data="{ 
            speed: 0.6,
            paused: false,
            isDown: false,
            dragged: false,
            startX: 0,
            scrollLeft: 0,
            init() {
                const el = this.$refs.slider;
                const loop = () => {
                    if (!this.paused && !this.isDown) {
                        el.scrollLeft += this.speed;
                        if (el.scrollLeft >= el.scrollWidth / 2) {
                            el.scrollLeft = 0;
                        }
                    }
                    requestAnimationFrame(loop);
                };
                loop();
            },
            handleMouseDown(e) {
                this.isDown = true;
                this.paused = true;
                this.dragged = false;
                this.startX = e.pageX - this.$refs.slider.offsetLeft;
                this.scrollLeft = this.$refs.slider.scrollLeft;
            },
            handleMouseMove(e) {
                if (!this.isDown) return;
                e.preventDefault();
                const x = e.pageX - this.$refs.slider.offsetLeft;
                const walk = (x - this.startX) * 1.5;
                if (Math.abs(x - this.startX) > 5) {
                    this.dragged = true;
                }
                this.$refs.slider.scrollLeft = this.scrollLeft - walk;
            },
            handleMouseUp() {
                this.isDown = false;
                this.paused = false;
                setTimeout(() => { this.dragged = false; }, 50);
            },
            handleTouchStart(e) {
                this.isDown = true;
                this.paused = true;
                this.dragged = false;
                this.startX = e.touches[0].pageX - this.$refs.slider.offsetLeft;
                this.scrollLeft = this.$refs.slider.scrollLeft;
            },
            handleTouchMove(e) {
                if (!this.isDown) return;
                const x = e.touches[0].pageX - this.$refs.slider.offsetLeft;
                const walk = (x - this.startX) * 1.5;
                if (Math.abs(x - this.startX) > 5) {
                    this.dragged = true;
                }
                this.$refs.slider.scrollLeft = this.scrollLeft - walk;
            },
            handleTouchEnd() {
                this.isDown = false;
                this.paused = false;
                setTimeout(() => { this.dragged = false; }, 50);
            }
        }">
            <div x-ref="slider" 
                 @mousedown="handleMouseDown($event)"
                 @mousemove="handleMouseMove($event)"
                 @mouseup="handleMouseUp()"
                 @mouseleave="handleMouseUp()"
                 @touchstart="handleTouchStart($event)"
                 @touchmove="handleTouchMove($event)"
                 @touchend="handleTouchEnd()"
                 @mouseenter="paused = true" 
                 @mouseleave="paused = false"
                 @dragstart.prevent
                 @click="if (dragged) { $event.preventDefault(); $event.stopPropagation(); }"
                 class="flex items-stretch gap-3 md:gap-6 overflow-x-auto no-scrollbar py-4 cursor-grab active:cursor-grabbing select-none">
                @php
                    $isFiltered = request()->filled('search') || 
                                  (request()->filled('type') && request()->type !== 'All Roles') || 
                                  (request()->filled('location') && request()->location !== 'All Locations');
                    
                    $displayCreators = $featuredCreators;
                    if (!$isFiltered && $featuredCreators->count() > 3) {
                        $displayCreators = $featuredCreators->concat($featuredCreators);
                    }
                @endphp
                @foreach($displayCreators as $item)
                    @include('frontend.partials.creator_card_small', ['creator' => $item])
                @endforeach
            </div>
        </div>
    </div>

    <!-- All Creators Section -->
    <div class="bg-gray-50/30 dark:bg-white/[0.01] border-y border-gray-100 dark:border-white/5 pt-0 pb-0">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 rounded-[1.5rem] bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                    <span class="material-symbols-rounded">groups</span>
                </div>
                <h2 class="text-3xl md:text-5xl font-black tracking-tight dark:text-white">All Profiles</h2>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 lg:gap-8">
                @forelse($allCreators as $item)
                    @include('frontend.marketplace.partials.creator_card', ['item' => $item])
                @empty
                    <div class="col-span-full py-40 text-center">
                        <div class="w-32 h-32 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-10">
                            <span class="material-symbols-rounded text-6xl text-gray-300">person_off</span>
                        </div>
                        <h3 class="text-2xl font-black dark:text-white uppercase mb-4">No Profiles Found</h3>
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Try adjusting your filters or search terms</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-10 md:mt-16 text-center">
                <a href="{{ route('marketplace.all') }}" class="group inline-flex items-center gap-2 md:gap-3 px-6 md:px-10 py-3.5 md:py-5 bg-indigo-600 rounded-full text-xs md:text-sm font-black uppercase tracking-widest text-white hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/20">
                    Explore All Profiles
                    <span class="material-symbols-rounded text-lg md:text-xl group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Premium Creator Gallery Showcase -->
    <div class="py-12 md:py-16 overflow-hidden bg-white dark:bg-[#050505]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row items-end justify-between gap-6 mb-10 md:mb-12">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-4 md:mb-6">
                        <span class="material-symbols-rounded text-sm">photo_library</span>
                        Creative Portfolios
                    </div>
                    <h2 class="text-3xl md:text-5xl font-black tracking-tight dark:text-white mb-3">Creator Showcase</h2>
                    <p class="text-sm md:text-base text-gray-400 font-medium leading-relaxed">Discover exceptional work and creative excellence from our elite network of professional profiles.</p>
                </div>
                <div class="hidden md:flex gap-3">
                    <button class="w-14 h-14 rounded-2xl border border-gray-100 dark:border-white/10 flex items-center justify-center text-gray-400 hover:border-indigo-500 hover:text-indigo-500 transition-all">
                        <span class="material-symbols-rounded">west</span>
                    </button>
                    <button class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-xl shadow-indigo-500/20 hover:bg-indigo-700 transition-all">
                        <span class="material-symbols-rounded">east</span>
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($galleryItems as $gallery)
                    @php $galleryPhoto = $gallery->photoUrl(); @endphp
                    <div onclick="Swal.fire({imageUrl: '{{ $galleryPhoto }}', imageAlt: '{{ addslashes($gallery->marketplace->name) }}', showCloseButton: true, showConfirmButton: false, width: 'auto', padding: 0, background: 'transparent', backdrop: 'rgba(0,0,0,0.9)', customClass: { image: 'rounded-2xl max-h-[90vh] object-contain', closeButton: 'rounded-full w-10 h-10 text-2xl focus:outline-none shadow-xl border-2 border-transparent' }, didOpen: () => { const btn = Swal.getCloseButton(); btn.style.color = '#F97316'; btn.style.backgroundColor = '#ffffff'; btn.style.top = '10px'; btn.style.right = '10px'; btn.style.zIndex = '9999'; }})" 
                       class="group relative aspect-[3/4] rounded-2xl md:rounded-3xl overflow-hidden bg-gray-100 dark:bg-white/5 shadow-xl border border-gray-100 dark:border-white/5 transition-all duration-700 block cursor-pointer">
                        <!-- High Fidelity Image -->
                        @if($galleryPhoto)
                        <img src="{{ $galleryPhoto }}" 
                             alt="{{ $gallery->marketplace->name }}"
                             loading="lazy" decoding="async"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000 ease-out">
                        @else
                        <div class="w-full h-full flex items-center justify-center" style="background: {{ $gallery->marketplace->getAvatarColor() }}">
                            <span class="text-4xl font-black text-white uppercase">{{ $gallery->marketplace->getInitials() }}</span>
                        </div>
                        @endif

                        <!-- Permanent Information Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-4 md:p-6 pointer-events-none">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2 md:gap-3 mb-3 md:mb-4 pointer-events-auto">
                                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full overflow-hidden border-2 border-white/20 flex-shrink-0 bg-gray-100 dark:bg-white/5">
                                        @php $galleryAvatar = $gallery->marketplace->photoUrl(); @endphp
                                        @if($galleryAvatar)
                                            <img src="{{ $galleryAvatar }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center" style="background: {{ $gallery->marketplace->getAvatarColor() }}">
                                                <span class="text-[8px] md:text-[10px] font-black text-white ">{{ $gallery->marketplace->getInitials() }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest text-indigo-400 truncate">Creator</p>
                                        <p class="text-xs md:text-sm font-bold text-white truncate">{{ $gallery->marketplace->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="h-px bg-white/10 w-full mb-3 md:mb-6 pointer-events-none"></div>
                                
                                <div class="flex items-center justify-between pointer-events-auto">
                                    <div class="flex flex-col">
                                        <span class="text-[7px] md:text-[9px] font-black text-white/40 uppercase tracking-[0.1em] md:tracking-[0.2em] mb-1">Portfolio</span>
                                        <span class="text-[9px] md:text-xs font-bold text-white uppercase">{{ ucfirst($gallery->marketplace->type) }}</span>
                                    </div>
                                    <a href="{{ route('marketplace.portfolio.short', ['slug' => $gallery->marketplace->slug]) }}" 
                                       onclick="event.stopPropagation()"
                                       title="View Portfolio"
                                       class="w-8 h-8 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-white/10 backdrop-blur-xl border border-white/10 flex items-center justify-center text-white group-hover:bg-white group-hover:text-black transition-all hover:scale-110">
                                        <span class="material-symbols-rounded text-base md:text-xl">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12 md:mt-16">
                <a href="{{ route('marketplace.gallery') }}" class="group inline-flex items-center gap-3 px-8 md:px-10 py-4 md:py-5 bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-full text-xs font-black uppercase tracking-[0.2em] dark:text-white hover:bg-indigo-600 hover:text-white transition-all shadow-xl shadow-indigo-500/10">
                    View Master Collection
                    <span class="material-symbols-rounded text-lg group-hover:translate-x-2 transition-transform">trending_flat</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Native App Membership Section (Optimized Scale) -->
    <div id="plans" class="relative py-24 md:py-20 bg-gradient-to-br from-[#0f172a] via-[#1e1b4b] to-[#0f172a] overflow-hidden">
        <!-- Requested Radial Gradient Background -->
        <div class="absolute inset-0 pointer-events-none opacity-60" style="background: radial-gradient(circle at 0% 0%, rgba(255,255,255,0.2) 0%, transparent 50%), radial-gradient(circle at 100% 100%, rgba(255,255,255,0.2) 0%, transparent 50%);"></div>
        
        <!-- Animated Glow Blobs -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-indigo-500/20 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-purple-500/20 rounded-full blur-[100px] animate-pulse" style="animation-delay: 2s"></div>
        
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <!-- Header -->
            <div class="text-center mb-8 md:mb-10">
                <h2 class="text-3xl md:text-3xl font-black text-white tracking-tighter mb-3">Choose Your Plan</h2>
                <p class="text-gray-400 font-medium text-xs md:text-xs">Unlock premium tools and global opportunities.</p>
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
                <div class="mb-8 md:mb-10 max-w-sm md:max-w-md mx-auto relative group">
                    <div class="absolute inset-0 bg-indigo-500/20 rounded-2xl blur-md group-hover:blur-lg transition-all duration-500"></div>
                    <div class="relative bg-[#0f172a]/80 backdrop-blur-xl border border-indigo-500/30 rounded-2xl p-3 pb-4 flex items-center justify-between shadow-xl overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-16 h-16 bg-indigo-500/20 rounded-full blur-xl"></div>
                        
                        <div class="flex items-center gap-3 z-10">
                            <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center flex-shrink-0 relative">
                                <div class="absolute inset-0 border border-indigo-400/30 rounded-full animate-ping"></div>
                                <span class="material-symbols-rounded text-lg">workspace_premium</span>
                            </div>
                            <div class="text-left">
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Active Plan</span>
                                    @if(!$isLifetime && $daysRemaining <= 7)
                                        <span class="px-1.5 py-0.5 rounded text-[6px] font-black bg-red-500/20 text-red-400 uppercase tracking-widest animate-pulse">Expiring Soon</span>
                                    @endif
                                </div>
                                <p class="text-xs font-black text-white uppercase tracking-wider truncate">{{ $activeSubscription->plan_name ?? 'Premium' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center z-10 ml-auto flex-shrink-0">
                            <div class="text-right pr-2 sm:pr-4">
                                <p class="text-[6px] sm:text-[7px] font-bold text-gray-400 uppercase tracking-widest mb-0.5 whitespace-nowrap">Purchased</p>
                                <p class="text-[9px] sm:text-[10px] font-black text-white uppercase tracking-wider whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($activeSubscription->start_date)->format('d M y') }}
                                </p>
                            </div>
                            <div class="text-right pl-2 sm:pl-4 border-l border-white/10">
                                <p class="text-[6px] sm:text-[7px] font-bold text-gray-400 uppercase tracking-widest mb-0.5 whitespace-nowrap">
                                    {{ $isLifetime ? 'Status' : $daysRemaining . ' Days Left' }}
                                </p>
                                <p class="text-[9px] sm:text-[10px] font-black text-indigo-400 uppercase tracking-wider whitespace-nowrap">
                                    {{ $isLifetime ? 'LIFETIME' : \Carbon\Carbon::parse($activeSubscription->end_date)->format('d M y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Dynamic Progress Bar -->
                        <div class="absolute bottom-0 left-0 w-full h-[3px] bg-white/5">
                            <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 relative" style="width: {{ $progressPercentage }}%">
                                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-white rounded-full shadow-[0_0_5px_#818cf8]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Plans Grid (Compact 4-col on desktop) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-3 max-w-4xl mx-auto">
                @foreach($plans as $plan)
                    @php
                        $isPremium = $loop->last;
                        $isSubscribed = isset($activeSubscription) && $activeSubscription && (int)$activeSubscription->plan_id === (int)$plan->id;
                        $durationLabel = match((int)$plan->plan_duration) {
                            1 => '/ Mo',
                            3 => '/ 3 Mo',
                            6 => '/ 6 Mo',
                            12 => '/ Yr',
                            default => '/ ' . $plan->plan_duration . ' Mo',
                        };
                    @endphp
                    <div class="relative bg-white/5 backdrop-blur-xl border border-white/10 rounded-[1.5rem] md:rounded-xl p-4 md:p-4 transition-all duration-500 hover:scale-[1.02] flex flex-col h-full overflow-hidden">
                        @if($isPremium)
                            <div class="absolute top-0 right-0 px-3 md:px-3 py-1 bg-[#ff571a] text-white text-[7px] md:text-[7px] font-black uppercase tracking-widest rounded-bl-xl">Popular</div>
                        @endif

                        <div class="mb-4 md:mb-4">
                            <h3 class="text-[10px] md:text-xs font-black text-white mb-1 uppercase tracking-wide truncate">{{ $plan->plan_name }}</h3>
                            <div class="flex items-baseline gap-0.5">
                                <span class="text-lg md:text-xl font-black text-white">₹{{ number_format($plan->plan_price) }}</span>
                                <span class="text-[6px] md:text-[8px] font-bold text-gray-500 uppercase tracking-widest">{{ $durationLabel }}</span>
                            </div>
                        </div>

                        <div class="space-y-2 md:space-y-2 mb-4 md:mb-5 flex-1">
                            @php
                                $planFeatures = match($plan->plan_name) {
                                    'Silver' => [
                                        'Basic Profile Listing',
                                        'Portfolio Showcase',
                                        'Email Support',
                                    ],
                                    'Gold' => [
                                        'Priority Listing',
                                        'Portfolio Showcase',
                                        'Custom Branding',
                                        'Chat Support',
                                    ],
                                    'Platinum' => [
                                        'Featured Profile',
                                        'Custom Branding',
                                        'Unlimited Media',
                                        'Priority Support',
                                        'Network Access',
                                    ],
                                    'Diamond Pro' => [
                                        'Featured Profile',
                                        'Custom Branding',
                                        'Unlimited Media',
                                        'VIP Support 24/7',
                                        'Network Access',
                                        'Verified Badge',
                                    ],
                                    default => [
                                        'Profile Listing',
                                        'Portfolio Showcase',
                                    ],
                                };
                            @endphp
                            @foreach($planFeatures as $feature)
                                <div class="flex items-center gap-1.5 md:gap-2">
                                    <div class="w-3.5 h-3.5 md:w-4 md:h-4 rounded-full bg-[#ff571a]/20 text-[#ff571a] flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-rounded text-[8px] md:text-[10px]">done</span>
                                    </div>
                                    <span class="text-[8px] md:text-[10px] font-bold text-gray-400 truncate">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>

                        @if($isSubscribed)
                            <button disabled class="w-full h-10 md:h-10 rounded-xl md:rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-black text-[8px] md:text-[9px] uppercase tracking-widest cursor-default flex items-center justify-center gap-1">
                                <span class="material-symbols-rounded text-sm">verified</span> Subscribed
                            </button>
                        @else
                            <button @click="handleSubscribe('{{ $plan->id }}')" :disabled="loading === '{{ $plan->id }}'" class="w-full h-10 md:h-10 rounded-xl md:rounded-lg {{ $isPremium ? 'bg-[#ff571a] text-white shadow-lg shadow-[#ff571a]/20' : 'bg-white text-black' }} font-black text-[8px] md:text-[9px] uppercase tracking-widest transition-all active:scale-95 hover:brightness-110 disabled:opacity-50 flex items-center justify-center gap-1">
                                <span x-show="loading !== '{{ $plan->id }}'">Subscribe</span>
                                <span x-show="loading === '{{ $plan->id }}'" class="flex items-center gap-1"><svg class="animate-spin h-3 w-3" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing</span>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Features Bar (Reduced Scale) -->
    <div class="max-w-5xl mx-auto px-6 pt-12 pb-6 md:pt-16 md:pb-4">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Feature 1 -->
            <div class="group relative p-6 md:p-8 bg-white dark:bg-white/[0.03] rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 dark:border-white/[0.05] shadow-xl shadow-gray-200/20 dark:shadow-none hover:shadow-2xl transition-all duration-500 overflow-hidden">
                <div class="absolute -top-10 -right-10 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl group-hover:bg-indigo-500/10 transition-all"></div>
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl md:rounded-2xl bg-indigo-500/10 dark:bg-indigo-500/20 text-indigo-500 dark:text-indigo-400 flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-inner">
                        <span class="material-symbols-rounded text-2xl md:text-3xl">support_agent</span>
                    </div>
                    <h4 class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] text-gray-900 dark:text-white mb-2 leading-tight">24/7 Expert Support</h4>
                    <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">Dedicated assistance</p>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="group relative p-6 md:p-8 bg-white dark:bg-white/[0.03] rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 dark:border-white/[0.05] shadow-xl shadow-gray-200/20 dark:shadow-none hover:shadow-2xl transition-all duration-500 overflow-hidden">
                <div class="absolute -top-10 -right-10 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-all"></div>
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl md:rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-500 dark:text-emerald-400 flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500 shadow-inner">
                        <span class="material-symbols-rounded text-2xl md:text-3xl">verified_user</span>
                    </div>
                    <h4 class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] text-gray-900 dark:text-white mb-2 leading-tight">Trusted Platform</h4>
                    <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">Secured ecosystem</p>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="group relative p-6 md:p-8 bg-white dark:bg-white/[0.03] rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 dark:border-white/[0.05] shadow-xl shadow-gray-200/20 dark:shadow-none hover:shadow-2xl transition-all duration-500 overflow-hidden">
                <div class="absolute -top-10 -right-10 w-24 h-24 bg-orange-500/5 rounded-full blur-2xl group-hover:bg-orange-500/10 transition-all"></div>
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl md:rounded-2xl bg-orange-500/10 dark:bg-orange-500/20 text-orange-500 dark:text-orange-400 flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-inner">
                        <span class="material-symbols-rounded text-2xl md:text-3xl">payments</span>
                    </div>
                    <h4 class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] text-gray-900 dark:text-white mb-2 leading-tight">Secure Payment</h4>
                    <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">Encrypted transactions</p>
                </div>
            </div>
            
            <!-- Feature 4 -->
            <div class="group relative p-6 md:p-8 bg-white dark:bg-white/[0.03] rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 dark:border-white/[0.05] shadow-xl shadow-gray-200/20 dark:shadow-none hover:shadow-2xl transition-all duration-500 overflow-hidden">
                <div class="absolute -top-10 -right-10 w-24 h-24 bg-purple-500/5 rounded-full blur-2xl group-hover:bg-purple-500/10 transition-all"></div>
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl md:rounded-2xl bg-purple-500/10 dark:bg-purple-500/20 text-purple-500 dark:text-purple-400 flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500 shadow-inner">
                        <span class="material-symbols-rounded text-2xl md:text-3xl">task_alt</span>
                    </div>
                    <h4 class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] text-gray-900 dark:text-white mb-2 leading-tight">Verified Profiles</h4>
                    <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">Authenticity guaranteed</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.8s ease-out forwards; }

    /* Page-scoped icon visibility fix (this page only).
       The layout globally forces `.material-symbols-rounded` to `color: inherit`,
       which beats Tailwind's text-color utilities and leaves icons dark-on-dark
       in dark mode. Light mode restores the original colors; dark mode renders
       the icons white as designed. */
    html:not(.dark) #marketplace-index span.material-symbols-rounded.text-gray-400 { color: #9ca3af !important; }
    html:not(.dark) #marketplace-index span.material-symbols-rounded.text-gray-300 { color: #d1d5db !important; }
    html:not(.dark) #marketplace-index span.material-symbols-rounded.text-slate-400 { color: #94a3b8 !important; }
    html:not(.dark) #marketplace-index span.material-symbols-rounded.text-white { color: #ffffff !important; }
    html:not(.dark) #marketplace-index span.material-symbols-rounded.text-yellow-400 { color: #facc15 !important; }
    html:not(.dark) #marketplace-index span.material-symbols-rounded.text-yellow-500 { color: #eab308 !important; }
    html:not(.dark) #marketplace-index span.material-symbols-rounded.text-orange-500 { color: #f97316 !important; }
    .dark #marketplace-index span.material-symbols-rounded.text-gray-400 { color: rgba(255,255,255,0.7) !important; }
    .dark #marketplace-index span.material-symbols-rounded.text-gray-300 { color: #ffffff !important; }
    .dark #marketplace-index span.material-symbols-rounded.text-yellow-400 { color: #ffffff !important; }
    .dark #marketplace-index span.material-symbols-rounded.text-white { color: #ffffff !important; }
    .dark #marketplace-index span.material-symbols-rounded.dark\:text-white\/70 { color: rgba(255,255,255,0.7) !important; }
</style>

<script>
function marketplaceIndexPurchase(){
    return {
        loading: null,
        isLoggedIn: {{ $talent ? 'true' : 'false' }},
        init(){},
        handleSubscribe(planId){
            if(!this.isLoggedIn){
                // Guest -> must login/register first (as requested)
                window.location.href = '{{ route('marketplace.login') }}';
                return;
            }
            this.initiatePurchase(planId);
        },
        initiatePurchase(planId){
            this.loading = planId;
            fetch('{{ route('marketplace.plan.buy', ['id' => ':id']) }}'.replace(':id', planId), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if(data.error){
                    Swal.fire('Error', data.error, 'error');
                    this.loading = null;
                    return;
                }
                if(data.redirect){
                    Swal.fire('Success', data.message || 'Activated', 'success').then(()=> window.location.href=data.redirect);
                    return;
                }
                if(!data.key){
                    Swal.fire('Configuration Error','Razorpay Key missing','error');
                    this.loading=null; return;
                }
                const options = {
                    key: data.key,
                    amount: data.amount,
                    currency: data.currency,
                    name: "Marketplace Talent Upgrade",
                    description: "Subscribe to " + data.plan_name,
                    order_id: data.order_id,
                    handler: (res) => this.verifyPayment(res, data.trx),
                    prefill: { name: data.name, email: data.email, contact: data.contact },
                    theme: { color: "#ff571a" },
                    modal: { ondismiss: () => { this.loading=null; } }
                };
                const rzp = new Razorpay(options);
                rzp.on('payment.failed', (resp)=>{ this.loading=null; Swal.fire('Payment Failed', resp.error.description || 'Payment failed','error'); });
                rzp.open();
            })
            .catch(e=>{ console.error(e); this.loading=null; Swal.fire('Error','Gateway Initialization Failed','error'); });
        },
        verifyPayment(response, trx){
            fetch('{{ route('marketplace.plan.verify') }}', {
                method: 'POST',
                headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' },
                body: JSON.stringify({ razorpay_payment_id: response.razorpay_payment_id, razorpay_order_id: response.razorpay_order_id, razorpay_signature: response.razorpay_signature, trx: trx })
            })
            .then(r=>r.json())
            .then(data=>{
                if(data.success){ Swal.fire('Success', data.success, 'success').then(()=> window.location.reload()); }
                else { Swal.fire('Error', data.error || 'Verification failed','error'); }
                this.loading=null;
            });
        }
    }
}
</script>
@endsection
