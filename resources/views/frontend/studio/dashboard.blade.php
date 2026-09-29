@extends('layouts.app')

@section('content')
<style>
    @media (min-width: 1024px) {
        .custom-action-card {
            width: 100% !important;
            max-width: 600px !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
        .action-icon-upload { background: linear-gradient(135deg, #fb923c 0%, #ea580c 100%) !important; color: white !important; box-shadow: 0 10px 15px -3px rgba(251, 146, 60, 0.3) !important; }
        .action-icon-content { background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%) !important; color: white !important; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3) !important; }
        .action-icon-earn { background: linear-gradient(135deg, #34d399 0%, #059669 100%) !important; color: white !important; box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.3) !important; }
        .action-icon-studio { background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%) !important; color: white !important; box-shadow: 0 10px 15px -3px rgba(124, 58, 237, 0.3) !important; }
    }
</style>
<div class="min-h-screen bg-[#F9F9F9] dark:bg-[#0F0F0F] transition-colors duration-500" x-data="planPurchase()">
    <!-- Integrated Profile UI (Responsive for all screens) -->
    <div class="max-w-[1600px] mx-auto 2xl:px-0 pb-0 lg:pb-8">
        <!-- Profile Greeting Hero -->
        <div class="px-6 pt-6 pb-24 lg:pt-8 lg:pb-12 lg:max-w-[1100px] lg:mx-auto gradient-orange relative overflow-hidden lg:rounded-[3rem] rounded-b-[4rem] shadow-2xl shadow-orange-500/20 lg:mt-6 transition-colors duration-500">
            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-4 lg:gap-6">
                    <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-[2rem] lg:rounded-[2.5rem] border-4 border-white/20 overflow-hidden shadow-2xl bg-white/10 backdrop-blur-md">
                        @if(Auth::user()->image)
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . Auth::user()->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white text-2xl lg:text-3xl font-black">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div class="flex flex-col lg:flex-row lg:items-center lg:gap-12">
                        <div>
                            <h2 class="text-2xl lg:text-4xl font-black text-white tracking-tighter">Hi, {{ explode(' ', Auth::user()->name)[0] }}!</h2>
                            <p class="text-[10px] lg:text-xs font-bold text-white/70 uppercase tracking-[0.2em] flex items-center gap-2 mt-1">
                                @if(Auth::user()->isCreator())
                                     <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                     Channel is active
                                @else
                                     <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                     No channel created yet
                                @endif
                            </p>
                        </div>
                        
                        <!-- Desktop Only Stats (Horizontal) -->
                        @if(Auth::user()->isCreator())
                        <div class="hidden lg:flex items-center gap-12 pl-12 border-l border-white/20">
                            <div class="text-white">
                                <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Subscribers</p>
                                <p class="text-xl lg:text-3xl font-black text-white tracking-tighter">{{ number_format($subscribersCount) }}</p>
                            </div>
                            <div class="text-white">
                                <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Reach</p>
                                <p class="text-xl lg:text-3xl font-black text-white tracking-tighter">{{ number_format($totalViews) }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <!-- <button onclick="window.dispatchEvent(new CustomEvent('toggle-sidebar'))" class="group relative w-10 h-10 lg:w-12 lg:h-12 rounded-xl lg:rounded-2xl bg-white/5 hover:bg-white/20 backdrop-blur-xl border border-white/10 flex flex-col items-center justify-center gap-1.5 transition-all shadow-xl overflow-hidden active:scale-95">
                    <div class="absolute inset-0 bg-gradient-to-tr from-white/0 via-white/5 to-white/0 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <span class="w-4 lg:w-5 h-0.5 bg-white rounded-full transform transition-all duration-300 group-hover:w-5 lg:group-hover:w-6 group-hover:translate-x-0.5"></span>
                    <span class="w-5 lg:w-6 h-0.5 bg-white rounded-full transform transition-all duration-300"></span>
                    <span class="w-3 lg:w-4 h-0.5 bg-white rounded-full transform transition-all duration-300 group-hover:w-5 lg:group-hover:w-6 group-hover:-translate-x-0.5"></span>
                </button> -->
            </div>
            
            <!-- Floating Quick Statistics (Original Mobile/Tablet Look - Hidden on Desktop) -->
            @if(Auth::user()->isCreator())
            <div class="absolute bottom-8 left-6 right-6 flex items-center justify-between lg:hidden z-20">
                <div class="text-white">
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Total Subscribers</p>
                    <p class="text-xl font-black text-white tracking-tighter">{{ number_format($subscribersCount) }}</p>
                </div>
                <div class="text-white text-right">
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Reach</p>
                    <p class="text-xl font-black text-white tracking-tighter">{{ number_format($totalViews) }}</p>
                </div>
            </div>
            @endif

            <!-- Decorative background blobs -->
            <div class="absolute -bottom-10 -right-10 w-40 h-40 lg:w-64 lg:h-64 bg-white/10 rounded-full blur-2xl lg:blur-3xl"></div>
            <div class="absolute -top-10 -left-10 w-40 h-40 lg:w-64 lg:h-64 bg-black/10 rounded-full blur-2xl lg:blur-3xl"></div>
        </div>


        @if(Auth::user()->isCreator())
        <div class="px-4 lg:px-12 -mt-10 mb-12 lg:mb-16 relative z-[5]">
            <div class="bg-white dark:bg-[#181818] p-6 lg:py-4 lg:px-8 rounded-[3rem] lg:rounded-3xl shadow-2xl border border-gray-100 dark:border-white/5 flex items-center justify-around custom-action-card">
                <a href="{{ route('videos.create') }}" class="flex flex-col items-center gap-1.5 lg:gap-2 group shrink-0">
                    <div class="w-14 h-14 lg:w-12 lg:h-12 rounded-2xl lg:rounded-[1rem] bg-orange-50 dark:bg-orange-500/10 flex items-center justify-center text-orange-600 group-active:scale-90 lg:group-hover:scale-110 transition-all action-icon-upload">
                        <span class="material-symbols-rounded text-2xl lg:text-xl font-bold">cloud_upload</span>
                    </div>
                    <span class="text-[9px] lg:text-[9px] font-black uppercase tracking-widest text-gray-500 lg:text-gray-900 dark:lg:text-white/80 lg:group-hover:text-orange-500">Upload</span>
                </a>
                <a href="{{ route('studio.videos') }}" class="flex flex-col items-center gap-1.5 lg:gap-2 group shrink-0">
                    <div class="w-14 h-14 lg:w-12 lg:h-12 rounded-2xl lg:rounded-[1rem] bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 group-active:scale-90 lg:group-hover:scale-110 transition-all action-icon-content">
                        <span class="material-symbols-rounded text-2xl lg:text-xl font-bold">analytics</span>
                    </div>
                    <span class="text-[9px] lg:text-[9px] font-black uppercase tracking-widest text-gray-500 lg:text-gray-900 dark:lg:text-white/80 lg:group-hover:text-blue-500">Content</span>
                </a>
                <a href="{{ route('studio.monetization') }}" class="flex flex-col items-center gap-1.5 lg:gap-2 group shrink-0">
                    <div class="w-14 h-14 lg:w-12 lg:h-12 rounded-2xl lg:rounded-[1rem] bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 group-active:scale-90 lg:group-hover:scale-110 transition-all action-icon-earn">
                        <span class="material-symbols-rounded text-2xl lg:text-xl font-bold">payments</span>
                    </div>
                    <span class="text-[9px] lg:text-[9px] font-black uppercase tracking-widest text-gray-500 lg:text-gray-900 dark:lg:text-white/80 lg:group-hover:text-emerald-500">Earn</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1.5 lg:gap-2 group shrink-0">
                    <div class="w-14 h-14 lg:w-12 lg:h-12 rounded-2xl lg:rounded-[1rem] bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-600 group-active:scale-90 lg:group-hover:scale-110 transition-all action-icon-studio">
                        <span class="material-symbols-rounded text-2xl lg:text-xl font-bold">person</span>
                    </div>
                    <span class="text-[9px] lg:text-[9px] font-black uppercase tracking-widest text-gray-500 lg:text-gray-900 dark:lg:text-white/80 lg:group-hover:text-purple-500">Profile</span>
                </a>
            </div>
        </div>
        @else
        <div class="px-4 lg:px-12 -mt-10 mb-12 lg:mb-16 relative z-[5]">
            <div class="bg-white dark:bg-[#181818] p-6 lg:p-6 rounded-[3rem] lg:rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5 flex flex-col lg:flex-row items-center justify-between gap-6 lg:gap-8 custom-action-card">
                <div class="flex items-center gap-4 lg:gap-4 flex-1">
                    <div class="w-14 h-14 lg:w-12 lg:h-12 rounded-2xl lg:rounded-[1rem] bg-gradient-to-tr from-[#fb923c] to-[#ea580c] flex items-center justify-center text-white shrink-0 shadow-lg shadow-orange-500/20">
                        <span class="material-symbols-rounded text-2xl lg:text-xl font-bold">rocket_launch</span>
                    </div>
                    <div class="space-y-0.5">
                        <h3 class="text-lg lg:text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight ">Ready to share your voice?</h3>
                        <p class="text-[11px] lg:text-[9px] font-bold text-gray-500 dark:text-gray-400 max-w-[12rem] leading-tight">
                            Create your channel to unlock content creation.
                        </p>
                    </div>
                </div>
                <a href="{{ route('channels.create') }}" class="w-full lg:w-auto px-5 py-2.5 bg-gradient-to-r from-[#fb923c] to-[#ea580c] text-white rounded-xl font-black text-[9px] uppercase tracking-[0.2em] shadow-md hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-1.5 shrink-0">
                    <span class="material-symbols-rounded text-sm">add_circle</span>
                    Create Channel
                </a>
            </div>
        </div>

        <!-- Onboarding Creator Feature Preview (Native App Style) -->
        <div class="px-6 lg:px-12 mb-20 relative z-[2]">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h3 class="text-xs font-black text-orange-500 uppercase tracking-[0.3em] mb-2">Creator Suite Features</h3>
                <h2 class="text-2xl lg:text-3xl font-black text-gray-900 dark:text-white tracking-tight uppercase ">Everything you need to succeed</h2>
                <p class="text-xs font-bold text-gray-500 dark:text-white/30 uppercase tracking-widest mt-2">Activate your channel to unlock these features</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1: Studio Uploader -->
                <div class="relative bg-white dark:bg-[#121212] p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden group">
                    <div class="absolute top-6 right-6 text-gray-300 dark:text-white/10">
                        <span class="material-symbols-rounded text-lg">lock</span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center mb-6">
                        <span class="material-symbols-rounded text-2xl font-bold">movie</span>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider mb-2">Upload Videos & Reels</h3>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-white/30 leading-relaxed uppercase">
                        Publish high-quality videos and short vertical reels with custom thumbnails, tags, and descriptions.
                    </p>
                </div>

                <!-- Feature 2: Deep Analytics -->
                <div class="relative bg-white dark:bg-[#121212] p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden group">
                    <div class="absolute top-6 right-6 text-gray-300 dark:text-white/10">
                        <span class="material-symbols-rounded text-lg">lock</span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center mb-6">
                        <span class="material-symbols-rounded text-2xl font-bold">bar_chart</span>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider mb-2">Advanced Analytics</h3>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-white/30 leading-relaxed uppercase">
                        Monitor subscriber growth, view trends, watch duration, and engagement details in real-time.
                    </p>
                </div>

                <!-- Feature 3: Monetization Tools -->
                <div class="relative bg-white dark:bg-[#121212] p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden group">
                    <div class="absolute top-6 right-6 text-gray-300 dark:text-white/10">
                        <span class="material-symbols-rounded text-lg">lock</span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center mb-6">
                        <span class="material-symbols-rounded text-2xl font-bold">payments</span>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider mb-2">Monetize Content</h3>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-white/30 leading-relaxed uppercase">
                        Sell premium video access, secure direct payments, and track wallet earnings dynamically.
                    </p>
                </div>

                <!-- Feature 4: Safety & Security -->
                <div class="relative bg-white dark:bg-[#121212] p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden group">
                    <div class="absolute top-6 right-6 text-gray-300 dark:text-white/10">
                        <span class="material-symbols-rounded text-lg">lock</span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-500 flex items-center justify-center mb-6">
                        <span class="material-symbols-rounded text-2xl font-bold">verified_user</span>
                    </div>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider mb-2">Safety Hub</h3>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-white/30 leading-relaxed uppercase">
                        Track strikes, policy reviews, community standard notices, and resolve copyright inquiries.
                    </p>
                </div>
            </div>
        </div>
        @endif

        @if(Auth::user()->isCreator())

        <!-- Unified Security & Safety Hub -->
        @php
            $activeStrikes = method_exists(auth()->user(), 'copyrightStrikes') ? auth()->user()->copyrightStrikes()->where('status', 'active')->with('video')->latest()->get() : collect();
            $hasNotices = $moderationReports->count() > 0 || $activeStrikes->count() > 0;
        @endphp

        <div class="px-4 lg:px-12 mb-10" x-data="{ 
            showStrikeModal: false, 
            currentStrike: null,
            strikeStep: 1,
            openStrike(strike) {
                this.currentStrike = strike;
                this.strikeStep = 1;
                this.showStrikeModal = true;
            }
        }">
            <div class="bg-white dark:bg-[#121212] rounded-[2rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden">
                <!-- Header: System Status -->
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-white/[0.02] border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-900 dark:bg-white flex items-center justify-center text-white dark:text-slate-900">
                            <span class="material-symbols-rounded text-lg">shield_with_heart</span>
                        </div>
                        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Safety & Policy Hub</h3>
                    </div>
                    <span class="text-[10px] font-black {{ $hasNotices ? 'text-rose-500' : 'text-emerald-500' }} uppercase tracking-widest">{{ $hasNotices ? 'System Check: Issues Found' : 'System Check: All Clear' }}</span>
                </div>

                @if($hasNotices)
                <div class="p-2 lg:p-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Copyright Strikes Section -->
                    @if($activeStrikes->count() > 0)
                    <div class="p-4 rounded-3xl bg-rose-500/[0.03] dark:bg-rose-500/[0.05] border border-rose-500/10 h-full">
                        <div class="flex items-center justify-between mb-4 px-1">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-rounded text-rose-500 text-lg">gavel</span>
                                <span class="text-[11px] font-black text-rose-500 uppercase tracking-widest">Copyright Strikes</span>
                            </div>
                            <span class="text-[10px] font-bold text-rose-500/60">{{ $activeStrikes->count() }}/3 Active</span>
                        </div>
                        
                        <div class="space-y-2">
                            @foreach($activeStrikes as $strike)
                            <div @click="openStrike({{ $strike->toJson() }})" class="cursor-pointer bg-white dark:bg-[#1A1A1A] rounded-2xl p-4 flex items-center gap-4 border border-gray-100 dark:border-white/5 hover:border-rose-500/30 transition-all group/strike">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-400 shrink-0 group-hover/strike:text-rose-500 transition-colors">
                                    <span class="material-symbols-rounded text-2xl">videocam</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-[12px] font-black text-gray-900 dark:text-white truncate uppercase tracking-tight">{{ $strike->video?->title ?? 'Removed Content' }}</h4>
                                    <p class="text-[10px] font-bold text-gray-400 truncate mt-1">{{ $strike->reason }}</p>
                                </div>
                                <div class="flex flex-col items-end gap-2 shrink-0">
                                    <span class="text-[9px] font-black text-rose-500 bg-rose-500/10 px-2.5 py-1 rounded-md uppercase tracking-widest">Strike</span>
                                    <div class="px-4 py-1.5 bg-rose-500 text-white rounded-xl flex items-center gap-2 shadow-lg shadow-rose-500/20 active:scale-95 transition-all">
                                        <span class="text-[10px] font-black uppercase tracking-widest">Review</span>
                                        <span class="material-symbols-rounded text-sm">arrow_forward</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="p-4 rounded-3xl bg-emerald-500/[0.03] dark:bg-emerald-500/[0.05] border border-emerald-500/10 h-full flex flex-col items-center justify-center text-center min-h-[160px]">
                        <div class="w-12 h-12 rounded-[1rem] bg-emerald-500/10 flex items-center justify-center text-emerald-500 mb-3">
                            <span class="material-symbols-rounded text-2xl">check_circle</span>
                        </div>
                        <h4 class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest mb-1">0 Active Strikes</h4>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest max-w-[12rem]">Your channel is free of copyright strikes.</p>
                    </div>
                    @endif

                    <!-- Community Notices Section -->
                    @if($moderationReports->count() > 0)
                    <div class="p-4 rounded-3xl bg-amber-500/[0.03] dark:bg-amber-500/[0.05] border border-amber-500/10 h-full">
                        <div class="flex items-center justify-between mb-4 px-1">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-rounded text-amber-500 text-lg">error_outline</span>
                                <span class="text-[11px] font-black text-amber-500 uppercase tracking-widest">Community Notices</span>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            @foreach($moderationReports as $report)
                            @php
                                $isReel = isset($report->reel) || ($report->reel_id ?? 0) > 0;
                                $content = $isReel ? ($report->reel ?? null) : ($report->video ?? null);
                                $isAge = $content?->is_age_restricted ?? false;
                                $targetUrl = ($content && $content->slug) ? ($isReel ? route('reels.show', $content->slug) : route('videos.show', $content->slug)) : '#';
                                $themeColor = $isAge ? 'amber' : 'blue';
                            @endphp
                            <a href="{{ $targetUrl }}" class="block bg-white dark:bg-[#1A1A1A] rounded-2xl p-4 border border-gray-100 dark:border-white/5 group/notice hover:border-{{ $themeColor }}-500/30 transition-all">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-400 shrink-0 group-hover/notice:text-{{ $themeColor }}-500 transition-colors">
                                        <span class="material-symbols-rounded text-xl">{{ $isReel ? 'movie' : 'videocam' }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-[11px] font-black text-gray-900 dark:text-white truncate uppercase tracking-tight leading-tight">{{ $content?->title ?? 'N/A' }}</h4>
                                    </div>
                                    <div class="flex flex-col items-end gap-2 shrink-0">
                                        <span class="text-[9px] font-black {{ $isAge ? 'text-amber-600 bg-amber-500/10' : 'text-blue-600 bg-blue-500/10' }} px-2.5 py-1 rounded-md uppercase tracking-widest leading-none">
                                            {{ $isAge ? 'Age Restricted' : 'Warning' }}
                                        </span>
                                        <div class="px-4 py-1.5 {{ $isAge ? 'bg-amber-500 shadow-amber-500/20' : 'bg-blue-600 shadow-blue-500/20' }} text-white rounded-xl flex items-center gap-2 shadow-lg active:scale-95 transition-all">
                                            <span class="text-[10px] font-black uppercase tracking-widest">View Content</span>
                                            <span class="material-symbols-rounded text-sm">open_in_new</span>
                                        </div>
                                    </div>
                                </div>
                                @if($report->admin_feedback)
                                <div class="mt-3 p-3 bg-gray-50 dark:bg-black/20 rounded-xl border border-gray-100 dark:border-white/5">
                                    <p class="text-[10px] font-bold text-gray-500 dark:text-white/40 leading-tight">"{{ $report->admin_feedback }}"</p>
                                </div>
                                @endif
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="p-4 rounded-3xl bg-emerald-500/[0.03] dark:bg-emerald-500/[0.05] border border-emerald-500/10 h-full flex flex-col items-center justify-center text-center min-h-[160px]">
                        <div class="w-12 h-12 rounded-[1rem] bg-emerald-500/10 flex items-center justify-center text-emerald-500 mb-3">
                            <span class="material-symbols-rounded text-2xl">thumb_up</span>
                        </div>
                        <h4 class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-widest mb-1">No Community Notices</h4>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest max-w-[12rem]">Your content complies with our community guidelines.</p>
                    </div>
                    @endif
                </div>

                <!-- Warning Footer -->
                <div class="px-6 py-3 bg-rose-500/5 border-t border-rose-500/10 flex items-center gap-3">
                    <span class="material-symbols-rounded text-rose-500 text-sm">warning</span>
                    <p class="text-[9px] font-bold text-rose-500 uppercase tracking-widest">Review guidelines carefully. Repeated violations lead to channel termination.</p>
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-16 px-4">
                    <div class="w-20 h-20 rounded-[2rem] bg-emerald-500/10 flex items-center justify-center text-emerald-500 mb-6">
                        <span class="material-symbols-rounded text-4xl">check_circle</span>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest mb-2">No Issues Found</h3>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em] text-center max-w-md">Your account is in good standing. No copyright strikes or community notices.</p>
                </div>
                @endif
            </div>

            {{-- Step-by-Step Strike Modal --}}
            <div x-show="showStrikeModal" x-cloak class="fixed inset-0 z-[10000] flex items-center justify-center p-4">
                <div x-on:click="showStrikeModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-md"></div>
                
                <div x-show="showStrikeModal" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="relative w-full max-w-lg bg-white dark:bg-[#121212] rounded-[2.5rem] overflow-hidden shadow-2xl border border-gray-100 dark:border-white/10">
                    
                    {{-- Progress Bar --}}
                    <div class="h-1.5 w-full bg-gray-100 dark:bg-white/5 flex">
                        <div class="h-full bg-rose-500 transition-all duration-500" :style="'width: ' + (strikeStep * 33.33) + '%'"></div>
                    </div>

                    <div class="p-8 lg:p-12">
                        {{-- Step 1: Violation Overview --}}
                        <div x-show="strikeStep === 1" class="space-y-8">
                            <div class="w-20 h-20 rounded-[2rem] bg-rose-500/10 flex items-center justify-center text-rose-500 mx-auto">
                                <span class="material-symbols-rounded text-4xl">gavel</span>
                            </div>
                            <div class="text-center">
                                <h4 class="text-[10px] font-black text-rose-500 uppercase tracking-[0.2em] mb-2">Step 01 / Policy Check</h4>
                                <h2 class="text-2xl font-black dark:text-white uppercase tracking-tight ">Strike Violation</h2>
                            </div>
                            <div class="bg-gray-50 dark:bg-white/5 p-6 rounded-3xl space-y-4 border border-gray-100 dark:border-white/5">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Affected Content</p>
                                    <p class="text-sm font-black dark:text-white" x-text="currentStrike?.video?.title || 'Removed Content'"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Reason for Strike</p>
                                    <p class="text-xs font-bold text-gray-600 dark:text-gray-400" x-text="currentStrike?.reason"></p>
                                </div>
                            </div>
                            <button @click="strikeStep = 2" class="w-full py-5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-3xl font-black text-[11px] uppercase tracking-[0.3em] shadow-xl active:scale-95 transition-all">
                                View Impact
                            </button>
                        </div>

                        {{-- Step 2: Channel Impact --}}
                        <div x-show="strikeStep === 2" class="space-y-8" x-cloak>
                            <div class="w-20 h-20 rounded-[2rem] bg-amber-500/10 flex items-center justify-center text-amber-500 mx-auto">
                                <span class="material-symbols-rounded text-4xl">monitor_heart</span>
                            </div>
                            <div class="text-center">
                                <h4 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] mb-2">Step 02 / Account Health</h4>
                                <h2 class="text-2xl font-black dark:text-white uppercase tracking-tight ">Channel Status</h2>
                            </div>
                            
                            <div class="flex justify-center gap-4">
                                <template x-for="i in 3">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center border-2" 
                                         :class="i <= {{ $activeStrikes->count() }} ? 'bg-rose-500 border-rose-500 text-white' : 'border-gray-100 dark:border-white/10 text-gray-300'">
                                        <span class="text-lg font-black" x-text="i"></span>
                                    </div>
                                </template>
                            </div>

                            <div class="text-center px-4">
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 leading-relaxed uppercase">
                                    You have <span class="text-rose-500" x-text="{{ $activeStrikes->count() }}"></span> active strike(s). If you reach <span class="text-rose-500">3 strikes</span>, your channel and all videos will be permanently removed.
                                </p>
                            </div>

                            <button @click="strikeStep = 3" class="w-full py-5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-3xl font-black text-[11px] uppercase tracking-[0.3em] shadow-xl active:scale-95 transition-all">
                                How to Resolve
                            </button>
                        </div>

                        {{-- Step 3: Resolution (Compact) --}}
                        <div x-show="strikeStep === 3" class="space-y-6" x-cloak>
                            <div class="text-center">
                                <div class="w-16 h-16 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 mx-auto mb-4">
                                    <span class="material-symbols-rounded text-3xl">fact_check</span>
                                </div>
                                <h4 class="text-[9px] font-black text-blue-500 uppercase tracking-[0.2em] mb-1">Final Step / Resolution</h4>
                                <h2 class="text-xl font-black dark:text-white uppercase tracking-tight ">Action Required</h2>
                            </div>
                            
                            <div class="p-5 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5 space-y-3">
                                <div class="flex gap-3">
                                    <span class="material-symbols-rounded text-sm text-blue-500 shrink-0">history</span>
                                    <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-relaxed">
                                        @php
                                            $guidelinesPolicy = getContent('policy_pages.element')->filter(fn($p) => str_contains(strtolower($p->data_values->title), 'guidelines') || str_contains(strtolower($p->data_values->title), 'community'))->first();
                                        @endphp
                                        Strikes usually expire after <span class="text-blue-500">90 days</span>. 
                                        @if($guidelinesPolicy)
                                            <a href="{{ route('policy.pages', [$guidelinesPolicy->id, slug($guidelinesPolicy->data_values->title)]) }}" class="text-blue-500 underline decoration-blue-500/30 underline-offset-4 hover:decoration-blue-500 transition-all">Review guidelines</a> carefully.
                                        @else
                                            Review guidelines carefully.
                                        @endif
                                    </p>
                                </div>
                                <div class="h-px bg-gray-100 dark:bg-white/5 w-full"></div>
                                <div class="flex gap-3">
                                    <span class="material-symbols-rounded text-sm text-rose-500 shrink-0">warning</span>
                                    <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest leading-relaxed">
                                        Deleting content <span class="underline">will not</span> remove the strike.
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 pt-2">
                                <a :href="'/videos/' + currentStrike?.video?.slug" class="w-full py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-center font-black text-[10px] uppercase tracking-[0.2em] shadow-lg active:scale-95 transition-all">
                                    Review Content Details
                                </a>
                                <button @click="showStrikeModal = false" class="w-full py-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] hover:text-gray-600 dark:hover:text-white transition-colors">
                                    Done
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Unified Professional Performance Suite -->
        <div class="px-6 lg:px-12 mb-12 relative z-[2]">
            <!-- Section Header -->
            <div class="flex items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-orange-500 rounded-full shadow-sm"></div>
                    <div>
                        <h3 class="text-lg lg:text-xl font-black text-gray-900 dark:text-white tracking-tight uppercase">Performance Overview</h3>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-3 py-1.5 bg-emerald-500/5 border border-emerald-500/10 rounded-full">
                    <div class="relative w-1.5 h-1.5">
                        <span class="absolute inset-0 rounded-full bg-emerald-500 animate-ping opacity-75"></span>
                        <span class="relative block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    </div>
                    <span class="text-[9px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-[0.2em]">Live</span>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 lg:gap-6">
                @php
                    $main_stats = [
                        ['Wallet Balance', showAmount(auth()->user()->balance), 'account_balance_wallet', 'emerald', null],
                        ['Subscribers', $subscribersCount, 'groups', 'blue', $dashboardStats['subscribers']],
                        ['Total Views', $totalViews, 'visibility', 'purple', $dashboardStats['views']],
                        ['Engagement', $totalLikes, 'favorite', 'pink', $dashboardStats['likes']],
                        ['Interactions', $totalComments, 'comment', 'amber', $dashboardStats['comments']]
                    ];
                @endphp
                @foreach($main_stats as $stat)
                    <div class="group relative bg-white dark:bg-[#121212] p-5 rounded-[2rem] border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-{{ $stat[3] }}-500/10 flex items-center justify-center text-{{ $stat[3] }}-500 transition-transform group-hover:scale-110">
                                <span class="material-symbols-rounded text-xl font-bold">{{ $stat[2] }}</span>
                            </div>
                            @if(isset($stat[4]))
                                @php
                                    $isNeg = str_contains($stat[4], '-');
                                    $isNeu = in_array($stat[4], ['0%', '+0%', '-0%', 'N/A']);
                                    $trendCol = $isNeg ? 'text-rose-600 dark:text-rose-400' : ($isNeu ? 'text-gray-400' : 'text-emerald-600 dark:text-emerald-400');
                                @endphp
                                <div class="text-[9px] font-black {{ $trendCol }} flex items-center gap-0.5">
                                    @php
                                        $trendIcon = $isNeg ? 'trending_down' : ($isNeu ? 'trending_flat' : 'trending_up');
                                    @endphp
                                    <span class="material-symbols-rounded text-[14px]">{{ $trendIcon }}</span>
                                    {{ $stat[4] }}
                                </div>
                            @endif
                        </div>
                        
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 dark:text-white/30 uppercase tracking-[0.2em] mb-0.5">{{ $stat[0] }}</p>
                            <h4 class="text-xl lg:text-2xl font-black text-gray-900 dark:text-white tracking-tighter">
                                {{ is_numeric($stat[1]) ? number_format($stat[1]) : $stat[1] }}
                            </h4>
                            @if(isset($stat[4]))
                                <p class="text-[7px] font-black text-gray-400 dark:text-white/20 uppercase tracking-tighter mt-1">Gained in last 30 days</p>
                            @endif
                        </div>






                    </div>
                @endforeach
            </div>
        </div>

    </div>

        <!-- Latest Video & Deep Analysis Grid -->
        <div class="grid grid-cols-1 gap-12 mb-20 px-6 lg:px-12">

        <div class="grid grid-cols-1 gap-8">
            <!-- Latest Video Performance -->
            <div class="space-y-8">
                  <div class="bg-white dark:bg-[#181818] border border-slate-100 dark:border-white/10 rounded-[2.5rem] p-6 sm:p-8 shadow-sm overflow-hidden" x-data="{ tab: 'videos' }">
                      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                          <h3 class="text-xl font-black text-slate-900 dark:text-white">Recent Uploads</h3>
                          
                          <div class="flex items-center gap-3">
                              <div class="flex bg-gray-100 dark:bg-black/20 p-1 rounded-full">
                                  <button @click="tab = 'videos'" :class="tab === 'videos' ? 'bg-white dark:bg-white/10 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-1.5 rounded-full text-[10px] sm:text-xs font-black tracking-widest uppercase transition-all">Videos</button>
                                  <button @click="tab = 'reels'" :class="tab === 'reels' ? 'bg-white dark:bg-white/10 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-1.5 rounded-full text-[10px] sm:text-xs font-black tracking-widest uppercase transition-all">Reels</button>
                              </div>
                              <a x-show="tab === 'videos'" href="{{ route('studio.videos') }}" class="text-blue-600 font-black text-xs uppercase tracking-widest hover:underline hidden sm:block">View all</a>
                              <a x-show="tab === 'reels'" x-cloak href="{{ route('studio.reels') }}" class="text-rose-500 font-black text-xs uppercase tracking-widest hover:underline hidden sm:block">View all</a>
                          </div>
                      </div>

                    <!-- Performance-Optimized Recent Uploads -->



                    <div class="space-y-4" x-show="tab === 'videos'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        @foreach($recentVideos as $video)
                        @php
                            $isOptimizing = ($video->isBunnyVideo() && !in_array($video->bunny_status, ['ready', 'error', 'failed'])) || (!$video->isBunnyVideo() && $video->status === 'processing');
                        @endphp
                        
                        <!-- Mobile & Tablet Optimized Card (Hidden on Desktop) -->
                        <div class="lg:hidden bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 p-3 sm:p-4 rounded-[1.5rem] shadow-sm mb-4 flex gap-3 sm:gap-4"
                             x-data="{ 
                                progress: 0, 
                                isOptimizing: {{ $isOptimizing ? 'true' : 'false' }},
                                init() { if(this.isOptimizing) this.pollStatus(); },
                                async pollStatus() {
                                    try {
                                        @if($video->slug)
                                        const res = await fetch('{{ route("videos.check_status", $video->slug) }}');
                                        @else
                                        const res = { json: () => ({ success: false }) };
                                        @endif
                                        const data = await res.json();
                                        if(data.success) {
                                            this.progress = data.encode_progress || 0;
                                            if(data.encode_progress >= 100) window.location.reload();
                                            else setTimeout(() => this.pollStatus(), 5000);
                                        }
                                    } catch(e) { setTimeout(() => this.pollStatus(), 10000); }
                                }
                             }">
                            <div class="block w-28 sm:w-40 shrink-0 aspect-video rounded-xl overflow-hidden bg-gray-100 dark:bg-black/20 relative border border-slate-200 dark:border-slate-800">
                                @if($video->getThumbnailUrl())
                                    <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover">
                                @endif
                                
                                @if($video->status == \App\Constants\Status::DRAFT && $video->bunny_status !== 'ready')
                                    <!-- Overlay for draft videos (Tap to Edit) -->
                                    <a href="{{ route('videos.create', ['draft_id' => $video->id]) }}" class="absolute inset-0 bg-white/95 dark:bg-slate-950/95 backdrop-blur-sm flex flex-col items-center justify-center p-2 text-center z-10 cursor-pointer hover:opacity-95 transition-opacity rounded-xl border border-slate-200 dark:border-slate-800">
                                        <div class="relative w-7 h-7 mb-1 flex items-center justify-center">
                                            <svg viewBox="0 0 28 28" class="w-full h-full transform -rotate-90">
                                                <circle cx="14" cy="14" r="11" stroke="#ff571a" stroke-opacity="0.15" stroke-width="2.5" fill="transparent" />
                                                <circle cx="14" cy="14" r="11" stroke="#ff571a" stroke-width="2.5" stroke-linecap="round" fill="transparent" class="transition-all duration-300" stroke-dasharray="69.1" :stroke-dashoffset="69.1 - (69.1 * (progress || 5) / 100)" />
                                            </svg>
                                        </div>
                                        <span class="text-[9px] font-black text-slate-900 dark:text-white uppercase tracking-wider">DRAFT UPLOADING</span>
                                        <span class="text-[7px] font-bold text-slate-800 dark:text-slate-200 mt-0.5">Tap Edit to Continue</span>
                                    </a>
                                @else
                                    <a href="{{ $video->slug ? route('videos.show', $video->slug) : '#' }}" class="absolute inset-0 z-0"></a>
                                    @if($video->bunny_status === 'processing')
                                        <div class="absolute top-1 left-1 px-1.5 py-0.5 bg-blue-500 rounded text-[7px] font-black text-white uppercase tracking-widest shadow-lg flex items-center gap-0.5 z-10 animate-pulse">
                                            Processing...
                                        </div>
                                    @elseif($video->is_premium)
                                        <div class="absolute top-1 left-1 px-1 py-0.5 bg-gradient-to-r from-amber-400 to-orange-500 rounded text-[6px] font-black text-white uppercase tracking-widest shadow-lg flex items-center gap-0.5 z-10">
                                            <span class="material-symbols-rounded text-[8px]">workspace_premium</span>
                                        </div>
                                    @elseif($video->scheduled_at && $video->scheduled_at->isFuture())
                                        <div class="absolute top-1 left-1 px-1 py-0.5 bg-blue-500 rounded text-[6px] font-black text-white uppercase tracking-widest shadow-lg flex items-center gap-0.5 z-10" title="{{ $video->scheduled_at->format('M d, Y h:i A') }}">
                                            <span class="material-symbols-rounded text-[8px]">schedule</span>
                                        </div>
                                    @endif
                                @endif
                                <div class="absolute bottom-1 right-1 px-1 py-0.5 bg-black/80 rounded text-[8px] font-black text-white pointer-events-none z-10">
                                    {{ $video->duration ?? 'HD' }}
                                </div>
                            </div>

                            <!-- Right: Details & Actions -->
                            <div class="flex-1 min-w-0 flex flex-col py-0.5">
                                <a href="{{ $video->slug ? route('videos.show', $video->slug) : '#' }}" class="hover:text-orange-500 transition-colors">
                                    <h4 class="font-black text-xs sm:text-sm text-gray-900 dark:text-white line-clamp-2 leading-snug mb-1.5">{{ $video->title }}</h4>
                                </a>
                                
                                <div class="flex items-center gap-3 mb-auto">
                                    <div class="flex items-center gap-1 text-gray-400">
                                        <span class="material-symbols-rounded text-[11px]">visibility</span>
                                        <span class="text-[9px] font-bold">{{ number_format($video->views_count) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-gray-400">
                                        <span class="material-symbols-rounded text-[11px]">comment</span>
                                        <span class="text-[9px] font-bold">{{ $video->comments()->count() }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons (Compact Row) -->
                                <div class="flex items-center gap-2 mt-2">
                                    <a href="{{ $video->status == \App\Constants\Status::DRAFT ? route('videos.create', ['draft_id' => $video->id]) : route('studio.videos.edit', $video) }}" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-500 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-colors" title="Edit">
                                        <span class="material-symbols-rounded text-[14px]">edit</span>
                                    </a>
                                    <a href="{{ $video->slug ? route('videos.show', $video->slug) : '#' }}" target="_blank" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-colors" title="View Live">
                                        <span class="material-symbols-rounded text-[14px]">open_in_new</span>
                                    </a>
                                    <button @click="confirmDelete('{{ route('studio.videos.destroy', $video) }}')" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-rose-50 dark:bg-rose-500/10 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-colors" title="Delete">
                                        <span class="material-symbols-rounded text-[14px]">delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Original Desktop List View (Hidden on Mobile) -->
                        <div class="hidden lg:flex items-center gap-6 p-4 rounded-3xl hover:bg-slate-50 dark:hover:bg-white/[0.03] transition-colors border border-transparent hover:border-slate-100 dark:hover:border-white/5 group"
                             x-data="{ 
                                progress: 0, 
                                isOptimizing: {{ $isOptimizing ? 'true' : 'false' }},
                                init() { if(this.isOptimizing) this.pollStatus(); },
                                async pollStatus() {
                                    try {
                                        @if($video->slug)
                                        const res = await fetch('{{ route("videos.check_status", $video->slug) }}');
                                        @else
                                        const res = { json: () => ({ success: false }) };
                                        @endif
                                        const data = await res.json();
                                        if(data.success) {
                                            this.progress = data.encode_progress || 0;
                                            if(data.encode_progress >= 100) window.location.reload();
                                            else setTimeout(() => this.pollStatus(), 5000);
                                        }
                                    } catch(e) { setTimeout(() => this.pollStatus(), 10000); }
                                }
                             }">
                            <div class="w-48 aspect-video rounded-2xl overflow-hidden bg-slate-100 dark:bg-white/5 relative shrink-0 border border-slate-200 dark:border-slate-800">
                                @if($video->getThumbnailUrl())
                                    <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @endif

                                 @if($video->status == \App\Constants\Status::DRAFT && $video->bunny_status !== 'ready')
                                     <!-- Overlay for draft videos (Tap to Edit) -->
                                     <a href="{{ route('videos.create', ['draft_id' => $video->id]) }}" class="absolute inset-0 bg-white/95 dark:bg-slate-950/95 backdrop-blur-sm flex flex-col items-center justify-center p-2 text-center z-10 cursor-pointer hover:opacity-95 transition-opacity rounded-2xl border border-slate-200 dark:border-slate-800">
                                         <div class="relative w-8 h-8 mb-1 flex items-center justify-center">
                                             <svg viewBox="0 0 32 32" class="w-full h-full transform -rotate-90">
                                                 <circle cx="16" cy="16" r="13" stroke="#ff571a" stroke-opacity="0.15" stroke-width="2.5" fill="transparent" />
                                                 <circle cx="16" cy="16" r="13" stroke="#ff571a" stroke-width="2.5" stroke-linecap="round" fill="transparent" class="transition-all duration-300" stroke-dasharray="81.6" :stroke-dashoffset="81.6 - (81.6 * (progress || 5) / 100)" />
                                             </svg>
                                         </div>
                                         <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-wider">DRAFT UPLOADING</span>
                                         <span class="text-[8px] font-bold text-slate-800 dark:text-slate-200 mt-0.5">Tap Edit to Continue</span>
                                     </a>
                                 @else
                                     <template x-if="isOptimizing">
                                         <div class="absolute inset-0 bg-white/95 dark:bg-slate-950/95 backdrop-blur-sm flex flex-col items-center justify-center p-2 text-center z-10 rounded-2xl border border-slate-200 dark:border-slate-800">
                                             <div class="relative w-8 h-8 mb-1 flex items-center justify-center">
                                                 <svg viewBox="0 0 32 32" class="w-full h-full transform -rotate-90">
                                                     <circle cx="16" cy="16" r="13" stroke="#ff571a" stroke-opacity="0.15" stroke-width="2.5" fill="transparent" />
                                                     <circle cx="16" cy="16" r="13" stroke="#ff571a" stroke-width="2.5" stroke-linecap="round" fill="transparent" class="transition-all duration-300" stroke-dasharray="81.6" :stroke-dashoffset="81.6 - (81.6 * (progress || 5) / 100)" />
                                                 </svg>
                                             </div>
                                             <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-wider">PROCESSING VIDEO</span>
                                             <span class="text-[8px] font-bold text-slate-800 dark:text-slate-200 mt-0.5">Available within minutes</span>
                                         </div>
                                     </template>
                                     <a href="{{ $video->slug ? route('videos.show', $video) : '#' }}" class="absolute inset-0 z-0">
                                         <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                             <span class="material-symbols-rounded text-white">play_arrow</span>
                                         </div>
                                     </a>
                                     @if($video->is_premium)
                                         <div class="absolute top-3 left-3 px-2 py-1 bg-gradient-to-r from-amber-400 to-orange-500 rounded-lg text-[8px] font-black text-white uppercase tracking-widest shadow-lg flex items-center gap-1 z-10">
                                             <span class="material-symbols-rounded text-[10px]">workspace_premium</span>
                                             Premium
                                         </div>
                                     @elseif($video->scheduled_at && $video->scheduled_at->isFuture())
                                         <div class="absolute top-3 left-3 px-2 py-1 bg-blue-500 rounded-lg text-[8px] font-black text-white uppercase tracking-widest shadow-lg flex items-center gap-1 z-10" title="{{ $video->scheduled_at->format('M d, Y h:i A') }}">
                                             <span class="material-symbols-rounded text-[10px]">schedule</span>
                                             Scheduled
                                         </div>
                                     @endif
                                 @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ $video->slug ? route('videos.show', $video->slug) : '#' }}" class="hover:text-orange-500 transition-colors">
                                    <h4 class="font-black text-slate-900 dark:text-white truncate mb-1">{{ $video->title }}</h4>
                                </a>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1.5 text-slate-400">
                                        <span class="material-symbols-rounded text-sm">visibility</span>
                                        <span class="text-xs font-bold">{{ number_format($video->views_count) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-slate-400">
                                        <span class="material-symbols-rounded text-sm">comment</span>
                                        <span class="text-xs font-bold">{{ $video->comments()->count() }}</span>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ $video->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($video->status == \App\Constants\Status::DRAFT)
                                    <a href="{{ route('videos.create', ['draft_id' => $video->id]) }}"
                                       class="px-3 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-black text-[10px] uppercase tracking-wider flex items-center gap-1.5 shadow-md transition-all"
                                       title="Edit Draft Video">
                                        <span class="material-symbols-rounded text-sm">edit</span>
                                        Edit Draft
                                    </a>
                                @endif
                                <a href="{{ $video->slug ? route('videos.show', $video->slug) : '#' }}" target="_blank"
                                   class="w-10 h-10 rounded-full flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-all"
                                   title="View Live Video">
                                    <span class="material-symbols-rounded text-xl">open_in_new</span>
                                </a>
                                <a href="{{ route('studio.videos.edit', $video) }}" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-all">
                                    <span class="material-symbols-rounded text-xl">edit</span>
                                </a>
                                <button type="button" @click="confirmDelete('{{ route('studio.videos.destroy', $video) }}')" 
                                        class="w-10 h-10 rounded-full flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all">
                                    <span class="material-symbols-rounded text-xl">delete</span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Performance-Optimized Recent Reels -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4" x-show="tab === 'reels'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <a href="{{ route('studio.reels') }}" class="col-span-full sm:hidden mb-2 text-rose-500 font-black text-[10px] uppercase tracking-widest hover:underline flex items-center gap-1 justify-end">
                            View All <span class="material-symbols-rounded text-[12px]">arrow_forward</span>
                        </a>
                        @forelse($recentReels as $reel)
                            @if(!$reel->slug) @continue @endif
                        @php
                            $isReelOptimizing = ($reel->bunny_status !== 'ready' && $reel->compression_status !== 3);
                            $isMixingReel = ($reel->is_duet || ($reel->music_source && $reel->music_source !== 'none'));
                            
                            $initialStatusText = 'Processing...';
                            if ($isMixingReel) {
                                $cStatus = (int)$reel->compression_status;
                                if ($cStatus === 0) $initialStatusText = 'Waiting for processing...';
                                elseif ($cStatus === 1) $initialStatusText = 'Mixing audio...';
                                elseif ($cStatus === 4) $initialStatusText = 'Uploading final video...';
                                elseif ($cStatus === 5) $initialStatusText = 'Processing video...';
                            }
                        @endphp
                        <div class="group relative"
                             x-data="{ 
                                 progress: 0, 
                                 isOptimizing: {{ $isReelOptimizing ? 'true' : 'false' }},
                                 isMixing: {{ ($isMixingReel && $isReelOptimizing && $reel->compression_status === 1 && !$reel->processing_bunny_id) ? 'true' : 'false' }},
                                 statusText: '{{ $initialStatusText }}',
                                 init() { 
                                     console.log('[REEL-POLL] init run (dashboard)', {reel_id: {{ $reel->id }}, isOptimizing: this.isOptimizing, isMixing: this.isMixing, bunny_status: '{{ $reel->bunny_status }}', compression_status: {{ (int)$reel->compression_status }} });
                                     if(this.isOptimizing) this.pollStatus(); 
                                 },
                                 async pollStatus() {
                                     console.log('[REEL-POLL] pollStatus called (dashboard) for Reel #{{ $reel->id }}');
                                     try {
                                         @if($reel->slug)
                                         const res = await fetch('{{ route("reels.check_status", $reel->slug) }}');
                                         const data = await res.json();
                                         console.log('[REEL-POLL] response (dashboard) for Reel #{{ $reel->id }}', data);
                                         if(data.success) {
                                             this.progress = data.encode_progress || 0;
                                             if(data.bunny_status === 'ready' && data.encode_progress >= 100 && (!data.is_mixing_reel || data.compression_status === 2)) {
                                                 console.log('[REEL-POLL] Reel #{{ $reel->id }} is ready (dashboard)! Reloading page.');
                                                 window.location.reload();
                                             } else if (data.bunny_status === 'error' || data.bunny_status === 'failed' || data.stage === 'failed') {
                                                 console.log('[REEL-POLL] Reel #{{ $reel->id }} failed. Stopping poll.');
                                                 this.statusText = 'Failed';
                                             } else {
                                                 this.isMixing = data.is_mixing_reel && (data.compression_status === 0 || data.compression_status === 1);
                                                 
                                                 if (data.stage === 'queued') this.statusText = 'Waiting...';
                                                 else if (data.stage === 'mixing') this.statusText = 'Mixing audio...';
                                                 else if (data.stage === 'uploading_to_bunny') this.statusText = 'Uploading...';
                                                 else if (data.stage === 'bunny_processing') this.statusText = 'Processing...';
                                                 else this.statusText = 'Processing...';

                                                 console.log('[REEL-POLL] scheduling next check (dashboard) for Reel #{{ $reel->id }} in 5s');
                                                 setTimeout(() => this.pollStatus(), 5000);
                                             }
                                         } else {
                                             console.warn('[REEL-POLL] check Status (dashboard) unsuccessful, scheduling retry in 5s');
                                             setTimeout(() => this.pollStatus(), 5000);
                                         }
                                         @endif
                                     } catch(e) { 
                                         console.error('[REEL-POLL] exception (dashboard) during poll for Reel #{{ $reel->id }}', e);
                                         setTimeout(() => this.pollStatus(), 10000); 
                                     }
                                 }
                             }">
                            <div class="aspect-[9/16] rounded-2xl lg:rounded-3xl overflow-hidden bg-slate-200 dark:bg-black/20 relative shadow-lg group-hover:shadow-rose-500/20 transition-all duration-500 border border-transparent group-hover:border-rose-500/30">
                                <a href="{{ $reel->slug ? route('reels.show', $reel->slug) : '#' }}" 
                                   :class="isOptimizing ? 'cursor-not-allowed' : ''"
                                   @click="if (isOptimizing) { event.preventDefault(); Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'info', title: isMixing ? 'This reel is still mixing audio...' : 'This reel is still processing on Bunny...', background: '#1A1A1A', color: '#ffffff'}); }"
                                   class="absolute inset-0 z-0">
                                    @if($reel->getThumbnailUrl())
                                        <img src="{{ $reel->getThumbnailUrl() }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    @endif
                                </a>

                                <!-- Optimizing State Overlay -->
                                <template x-if="isOptimizing">
                                    <div class="absolute inset-0 bg-black/60 backdrop-blur-md flex flex-col items-center justify-center z-20">
                                        <div class="relative w-10 h-10 mb-2">
                                            <svg viewBox="0 0 40 40" class="w-full h-full transform -rotate-90">
                                                <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-white/10" />
                                                <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="3" fill="transparent" class="text-rose-500 transition-all duration-500" stroke-dasharray="100.5" :stroke-dashoffset="100.5 - (100.5 * Math.min(100, Math.max(0, progress || 5)) / 100)" />
                                            </svg>
                                            <div class="absolute inset-0 flex items-center justify-center text-[9px] font-bold text-white" x-text="(progress || 5) + '%'"></div>
                                        </div>
                                         <span class="text-[7px] lg:text-[9px] font-black text-white uppercase tracking-wider text-center px-2 leading-tight animate-pulse" x-text="statusText"></span>
                                         <span class="text-[6px] lg:text-[7px] text-white/60 uppercase font-bold tracking-wider mt-1 text-center px-2 animate-pulse">Your reel will post within minutes</span>
                                    </div>
                                </template>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent flex flex-col justify-end p-4 z-10 cursor-pointer"
                                     @click="if(!event.target.closest('a, button')) { if(isOptimizing) { Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'info', title: isMixing ? 'This reel is still mixing audio...' : 'This reel is still processing on Bunny...', background: '#1A1A1A', color: '#ffffff'}); } else { window.location.href='{{ $reel->slug ? route('reels.show', $reel->slug) : '#' }}'; } }">
                                    <div class="flex items-center justify-between gap-1.5">
                                        <a href="{{ route('studio.reels.edit', $reel) }}" class="flex-1 h-8 bg-white rounded-full text-black flex items-center justify-center gap-1.5 px-2 shadow-lg active:scale-95 transition-all">
                                            <span class="material-symbols-rounded text-base">edit</span>
                                            <span class="hidden lg:inline text-[8px] font-black uppercase tracking-tight">Manage</span>
                                        </a>
                                        <button @click="confirmDelete('{{ route('studio.reels.destroy', $reel) }}')" class="w-8 h-8 bg-rose-500 rounded-full text-white flex items-center justify-center shadow-lg active:scale-95 transition-all shrink-0">
                                            <span class="material-symbols-rounded text-base">delete</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="absolute top-3 right-3 px-2 py-1 bg-black/60 backdrop-blur-md rounded-lg text-[8px] font-black text-white flex items-center gap-1 z-10">
                                    <span class="material-symbols-rounded text-[10px] text-rose-500">visibility</span>
                                    {{ number_format($reel->views_count) }}
                                </div>
                            </div>
                            <a href="{{ $reel->slug ? route('reels.show', $reel->slug) : '#' }}" 
                               :class="isOptimizing ? 'cursor-not-allowed' : ''"
                               @click="if (isOptimizing) { event.preventDefault(); Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'info', title: isMixing ? 'This reel is still mixing audio...' : 'This reel is still processing on Bunny...', background: '#1A1A1A', color: '#ffffff'}); }"
                               class="hover:text-rose-500 transition-colors">
                                <h5 class="mt-3 text-[10px] font-black text-slate-700 dark:text-white/70 uppercase tracking-widest truncate px-1">{{ $reel->title }}</h5>
                            </a>
                        </div>
                        @empty
                        <div class="col-span-full py-20 text-center rounded-[2rem] border-2 border-dashed border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                            <span class="material-symbols-rounded text-slate-200 dark:text-white/10 text-4xl">movie_off</span>
                            <p class="mt-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">No recent reels found</p>
                        </div>
                        @endforelse
                    </div>
                    </div>
            </div>

            <!-- Professional Achievement & Insights Suite -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
                <!-- Milestone Path Card -->
                <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl shadow-gray-200/50 dark:shadow-none lg:self-start">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight uppercase ">Milestone Radar</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-0.5">Your journey to the top</p>
                        </div>
                        <div class="w-10 h-10 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500 border border-orange-500/10">
                            <span class="material-symbols-rounded animate-pulse">radar</span>
                        </div>
                    </div>

                    @php
                        $minViews = $general->minimum_views ?? 1000;
                        $minSubs = $general->minimum_subscribe ?? 100;
                        $milestones = [
                            [number_format($minViews) . ' Total Views', $totalViews, $minViews ?: 1000, 'visibility', 'emerald'],
                            [number_format($minSubs) . ' Subscribers', $subscribersCount, $minSubs ?: 100, 'groups', 'blue'],
                            ['500 Total Likes', $totalLikes, 500, 'favorite', 'pink']
                        ];
                    @endphp

                    <div class="space-y-8">
                        @foreach($milestones as $m)
                        @php $percent = $m[2] > 0 ? min(100, ($m[1] / $m[2]) * 100) : 0; @endphp
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-{{ $m[4] }}-500/10 flex items-center justify-center text-{{ $m[4] }}-500">
                                        <span class="material-symbols-rounded text-lg font-black">{{ $m[3] }}</span>
                                    </div>
                                    <span class="text-[11px] font-black text-gray-700 dark:text-white/80 uppercase tracking-widest">{{ $m[0] }}</span>
                                </div>
                                <span class="text-[10px] font-black text-gray-400">{{ number_format($percent, 1) }}%</span>
                            </div>
                            <div class="h-2 w-full bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden p-[1px]">
                                <div class="h-full bg-{{ $m[4] }}-500 rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(var(--tw-color-{{ $m[4] }}-500),0.5)]" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Unreplied Comments Center -->
                <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2.5rem] p-6 lg:p-8 shadow-xl shadow-gray-200/50 dark:shadow-none">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-1.5 h-6 bg-pink-500 rounded-full"></div>
                            <div>
                                <h3 class="text-base sm:text-lg font-black text-gray-900 dark:text-white tracking-tight uppercase ">Unreplied Comments</h3>
                                <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-0.5">Engage with your audience</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-2xl bg-pink-500/10 flex items-center justify-center text-pink-500 border border-pink-500/10">
                            <span class="material-symbols-rounded">forum</span>
                        </div>
                    </div>

                    @php
                        $unrepliedVideoComments = \App\Models\Comment::whereNull('parent_id')
                            ->whereHas('video', function($q) { $q->where('user_id', Auth::id()); })
                            ->whereDoesntHave('replies', function($q) { $q->where('user_id', Auth::id()); })
                            ->with(['video', 'user'])
                            ->latest()
                            ->take(3)
                            ->get()
                            ->map(function($c) { $c->type = 'video'; return $c; });

                        $unrepliedReelComments = \App\Models\ReelComment::whereNull('parent_id')
                            ->whereHas('reel', function($q) { $q->where('user_id', Auth::id()); })
                            ->whereDoesntHave('replies', function($q) { $q->where('user_id', Auth::id()); })
                            ->with(['reel', 'user'])
                            ->latest()
                            ->take(3)
                            ->get()
                            ->map(function($c) { $c->type = 'reel'; return $c; });

                        $unrepliedComments = $unrepliedVideoComments->merge($unrepliedReelComments)
                            ->sortByDesc('created_at')
                            ->take(4);
                    @endphp

                    <div class="space-y-4">
                        @forelse($unrepliedComments as $comment)
                        @php
                            if ($comment->type == 'video') {
                                $likeUrl = route('comments.like', $comment->id);
                                $replyUrl = $comment->video ? route('comments.store', $comment->video->slug) : '#';
                                $contentKey = 'content';
                                $title = $comment->video?->title ?? 'N/A';
                                $icon = 'videocam';
                                $redirectUrl = $comment->video ? route('videos.show', $comment->video->slug) : '#';
                            } else {
                                $likeUrl = route('reels.comment.like', $comment->id);
                                $replyUrl = $comment->reel ? route('reels.comment', $comment->reel->slug) : '#';
                                $contentKey = 'comment';
                                $title = $comment->reel?->title ?? 'N/A';
                                $icon = 'movie';
                                $redirectUrl = $comment->reel ? route('reels.show', $comment->reel->slug) : '#';
                            }
                        @endphp
                        <div class="group bg-gray-50/50 dark:bg-white/[0.02] p-5 rounded-[2rem] border border-gray-100 dark:border-white/5 hover:bg-white dark:hover:bg-[#1A1A1A] transition-all duration-300"
                             x-data="{ 
                                showReply: false, 
                                replyContent: '', 
                                isLiked: {{ $comment->likes()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }},
                                likesCount: {{ $comment->likes()->count() }},
                                submitting: false,
                                async toggleLike() {
                                    try {
                                        const res = await fetch('{{ $likeUrl }}', {
                                            method: 'POST',
                                            headers: { 
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                                                'Accept': 'application/json',
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        });
                                        const data = await res.json();
                                        this.isLiked = data.liked;
                                        this.likesCount = data.likes_count;
                                        window.notify('success', data.liked ? 'Comment liked' : 'Like removed');
                                    } catch(e) { console.error(e); }
                                },
                                async submitReply() {
                                    if(!this.replyContent.trim()) return;
                                    this.submitting = true;
                                    try {
                                        const res = await fetch('{{ $replyUrl }}', {
                                            method: 'POST',
                                            headers: { 
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json',
                                                'X-Requested-With': 'XMLHttpRequest'
                                            },
                                            body: JSON.stringify({ '{{ $contentKey }}': this.replyContent, parent_id: {{ $comment->id }} })
                                        });
                                        const data = await res.json();
                                        if(data.status === 'success' || data.comment) {
                                            this.showReply = false;
                                            this.replyContent = '';
                                            window.notify('success', 'Reply posted successfully');
                                            setTimeout(() => { $el.style.opacity = '0'; $el.style.height = '0'; setTimeout(() => $el.remove(), 400); }, 1000);
                                        } else {
                                            window.notify('error', data.message || 'Error posting reply');
                                        }
                                    } catch(e) { console.error(e); }
                                    this.submitting = false;
                                }
                             }">
                            <div class="flex gap-4 mb-4">
                                <div class="w-12 h-12 rounded-full shrink-0 overflow-hidden bg-gray-200 dark:bg-white/10 ring-4 ring-white dark:ring-[#181818] shadow-sm">
                                    @if($comment->user && $comment->user->image)
                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $comment->user->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <span class="material-symbols-rounded text-2xl">person</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                      <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mb-1">
                                          <h5 class="text-sm font-black text-gray-900 dark:text-white break-words max-w-full">{{ $comment->user?->username ?? 'Anonymous' }}</h5>
                                          <div class="flex items-center gap-2 shrink-0">
                                              <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest {{ $comment->type == 'video' ? 'bg-blue-500/10 text-blue-500' : 'bg-orange-500/10 text-orange-500' }}">
                                                  {{ $comment->type == 'video' ? 'Video' : 'Reel' }}
                                              </span>
                                              <span class="text-[9px] font-bold text-gray-400 uppercase">{{ $comment->created_at ? $comment->created_at->diffForHumans() : 'N/A' }}</span>
                                          </div>
                                      </div>
                                    <p class="text-[12px] text-gray-600 dark:text-white/50 line-clamp-2 leading-relaxed ">"{{ $comment->content ?? $comment->comment ?? 'N/A' }}"</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col gap-4 pt-4 border-t border-gray-200/50 dark:border-white/5">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <a href="{{ $redirectUrl }}" target="_blank" class="flex items-center gap-2 bg-white dark:bg-white/5 px-3 py-2.5 sm:py-1.5 rounded-2xl sm:rounded-full border border-gray-100 dark:border-white/5 min-w-0 hover:bg-gray-50 dark:hover:bg-white/10 transition-colors group/link w-full sm:w-auto shadow-sm sm:shadow-none">
                                        <div class="w-6 h-6 sm:w-auto sm:h-auto rounded-full bg-gray-100 dark:bg-white/10 sm:bg-transparent flex items-center justify-center shrink-0">
                                            <span class="material-symbols-rounded text-[14px] sm:text-sm text-gray-500 dark:text-gray-400 group-hover/link:text-orange-500 transition-colors">{{ $icon }}</span>
                                        </div>
                                        <p class="text-[10px] sm:text-[9px] font-bold text-gray-700 dark:text-gray-300 sm:text-gray-500 sm:dark:text-white/40 uppercase tracking-tight truncate flex-1 group-hover/link:text-orange-500 transition-colors">
                                            {{ $title }}
                                        </p>
                                        <span class="material-symbols-rounded text-sm text-gray-300 dark:text-white/20 sm:hidden">chevron_right</span>
                                    </a>
                                    
                                    <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto mt-1 sm:mt-0">
                                        <button @click="toggleLike" 
                                                class="w-10 h-10 rounded-full flex items-center justify-center transition-all shadow-sm shrink-0"
                                                :class="isLiked ? 'bg-rose-500 text-white' : 'bg-white dark:bg-white/5 text-gray-400 hover:text-rose-500 border border-gray-100 dark:border-white/5'">
                                            <span class="material-symbols-rounded text-xl" :class="isLiked && 'material-symbols-filled'">favorite</span>
                                        </button>
                                        
                                        <button @click="showReply = !showReply" 
                                                class="flex-1 sm:flex-none h-10 px-6 gradient-orange rounded-full text-white text-[11px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 flex items-center justify-center gap-2 hover:scale-105 active:scale-95 transition-all">
                                            <span x-text="showReply ? 'Cancel' : 'Reply'"></span>
                                            <span class="material-symbols-rounded text-sm" x-text="showReply ? 'close' : 'reply'"></span>
                                        </button>
                                    </div>
                                </div>

                                <div x-show="showReply" x-collapse x-cloak>
                                    <div class="bg-white dark:bg-black/20 rounded-2xl p-3 border border-gray-100 dark:border-white/5">
                                        <textarea x-model="replyContent" 
                                                  placeholder="Write your response..." 
                                                  class="w-full bg-transparent border-0 focus:ring-0 text-[11px] font-bold text-gray-600 dark:text-white/70 placeholder:text-gray-400 resize-none px-0"
                                                  rows="2"></textarea>
                                        <div class="flex justify-end mt-2 pt-2 border-t border-gray-50 dark:border-white/5">
                                            <button @click="submitReply" 
                                                    :disabled="submitting || !replyContent.trim()"
                                                    class="px-4 py-2 gradient-orange text-white rounded-xl text-[9px] font-black uppercase tracking-widest disabled:opacity-50 shadow-lg shadow-orange-500/10 transition-all flex items-center gap-2">
                                                <span x-text="submitting ? 'Sending...' : 'Post Response'"></span>
                                                <span x-show="!submitting" class="material-symbols-rounded text-sm">send</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="py-16 text-center bg-gray-50/50 dark:bg-white/[0.01] rounded-[2rem] border border-dashed border-gray-200 dark:border-white/5">
                            <div class="w-20 h-20 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 mx-auto mb-6">
                                <span class="material-symbols-rounded text-4xl">done_all</span>
                            </div>
                            <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-2">Inbox Zero</h4>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-12">You've responded to all recent fan engagement!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

                <!-- Dynamic Creator Success Path (Live Data) -->
                <div class="space-y-6 lg:space-y-8">
                    <!-- Actionable Checklist Card -->
                    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] lg:rounded-[2.5rem] p-5 lg:p-8 shadow-xl shadow-gray-200/50 dark:shadow-none relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-6 lg:mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500 border border-orange-500/10">
                                    <span class="material-symbols-rounded text-xl lg:text-2xl">auto_fix_high</span>
                                </div>
                                <h3 class="text-base lg:text-lg font-black text-gray-900 dark:text-white tracking-tight">Strategy Guide</h3>
                            </div>
                        </div>

                        <div class="space-y-3 lg:space-y-4">
                            @php
                                $latest = $recentVideos->first();
                                $tasks = [
                                    [
                                        'label' => 'Optimize Metadata',
                                        'sub' => 'Review description for "' . ($latest->title ?? 'Latest') . '"',
                                        'route' => $latest ? route('studio.videos.edit', $latest) : route('studio.videos'),
                                        'status' => $latest && $latest->description ? 'done' : 'pending'
                                    ],
                                    [
                                        'label' => 'Monetization Check',
                                        'sub' => 'Review your current standing',
                                        'route' => route('studio.monetization'),
                                        'status' => 'pending'
                                    ],
                                    [
                                        'label' => 'Manage Content',
                                        'sub' => 'Organize your video library',
                                        'route' => route('studio.videos'),
                                        'status' => 'pending'
                                    ]
                                ];
                            @endphp
                            @foreach($tasks as $task)
                            <a href="{{ $task['route'] }}" class="p-4 lg:p-5 rounded-2xl lg:rounded-[2rem] bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-white/5 flex items-center justify-between gap-3 group/task hover:bg-white dark:hover:bg-white/5 hover:border-orange-500/30 transition-all">
                                <div class="flex items-center gap-3 lg:gap-4 flex-1 min-w-0">
                                    <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl {{ $task['status'] == 'done' ? 'bg-emerald-500 text-white' : 'bg-gray-200 dark:bg-white/10 text-gray-400' }} flex items-center justify-center shrink-0 transition-colors">
                                        <span class="material-symbols-rounded text-lg lg:text-xl">{{ $task['status'] == 'done' ? 'check' : 'bolt' }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs lg:text-sm font-black text-gray-900 dark:text-white truncate leading-tight">{{ $task['label'] }}</p>
                                        <p class="text-[9px] lg:text-[10px] text-gray-500 dark:text-white/40 font-bold uppercase tracking-wide mt-0.5 truncate leading-tight">{{ $task['sub'] }}</p>
                                    </div>
                                </div>
                                <span class="material-symbols-rounded text-gray-300 dark:text-white/10 group-hover/task:text-orange-500 transition-colors shrink-0">arrow_forward</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    </div>

        @endif

                    @if(false)
                    <!-- Membership & Growth Tiers (Premium Redesign) -->
                    <div class="mt-12">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12 px-2">
                            <div>
                                <h3 class="text-2xl lg:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Premium Creator Plans</h3>
                                <p class="text-sm font-bold text-gray-500 dark:text-white/40 uppercase tracking-widest mt-2">Unlock your channel's full potential</p>
                            </div>
                            <div class="flex items-center gap-4">
                                @php
                                    $creatorCount = \App\Models\User::where('creator_status', 1)->count();
                                    $formattedCount = $creatorCount >= 1000 ? floor($creatorCount / 1000) . 'K+' : ($creatorCount > 0 ? $creatorCount . '+' : 'New');
                                @endphp
                                <div class="hidden sm:flex -space-x-3">
                                    @for($i=1; $i<=4; $i++)
                                        <div class="w-10 h-10 rounded-full border-4 border-white dark:border-[#0F0F0F] bg-gray-200 dark:bg-white/10 overflow-hidden">
                                            <img src="https://i.pravatar.cc/100?u={{$i}}" class="w-full h-full object-cover opacity-80">
                                        </div>
                                    @endfor
                                    <div class="w-10 h-10 rounded-full border-4 border-white dark:border-[#0F0F0F] bg-orange-500 flex items-center justify-center text-[10px] font-black text-white">{{ $formattedCount }}</div>
                                </div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Joined Creators</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-8 px-3 md:px-0 pb-12">
                            @forelse($plans as $plan)
                            @php 
                                $isPro = str_contains(strtolower($plan->name), 'pro') || str_contains(strtolower($plan->name), 'premium') || $loop->index == 1;
                                $features = explode(',', $plan->features ?? 'Content Manager,Analytics,Monetization,Custom Branding');
                            @endphp
                            <div class="group/plan relative {{ $isPro ? 'mt-3 md:mt-0' : '' }}">
                                <!-- Background Glow -->
                                <div class="absolute -inset-0.5 md:-inset-1 bg-gradient-to-r {{ $isPro ? 'from-orange-500 to-pink-600' : 'from-blue-500 to-indigo-600' }} rounded-[1.25rem] md:rounded-[3rem] blur opacity-20 group-hover/plan:opacity-40 transition duration-1000 group-hover/plan:duration-200"></div>
                                
                                <div class="relative bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[1.25rem] md:rounded-[3rem] p-3 md:p-10 shadow-xl md:shadow-2xl transition-all duration-500 flex flex-col h-full hover:-translate-y-1 md:hover:-translate-y-2 overflow-visible">
                                    
                                    @if($isPro)
                                    <div class="self-center md:self-start inline-block px-3 md:px-6 py-1 md:py-1.5 mb-3 md:mb-4 rounded-full text-[8px] md:text-[10px] font-black text-white uppercase tracking-[0.1em] md:tracking-[0.2em] shadow-md text-center" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
                                        ⭐ Recommended
                                    </div>
                                    @endif

                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-3 md:mb-10">
                                        <div class="min-w-0 text-center md:text-left w-full">
                                            <h4 class="text-sm md:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $plan->name }}</h4>
                                            <p class="inline-block px-2 py-0.5 bg-gray-100 dark:bg-white/5 rounded-md text-[8px] md:text-[10px] font-medium text-gray-500 dark:text-gray-400 mt-1.5 md:mt-2">{{ $plan->duration }} Days</p>
                                        </div>
                                        <div class="hidden md:flex w-14 h-14 rounded-2xl {{ $isPro ? 'bg-orange-500/10 text-orange-500' : 'bg-blue-500/10 text-blue-500' }} items-center justify-center border border-current/10 shrink-0">
                                            <span class="material-symbols-rounded text-3xl font-bold">{{ $isPro ? 'rocket_launch' : 'auto_awesome' }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-3 md:mb-10 text-center md:text-left flex flex-col md:block">
                                        <div class="flex items-baseline justify-center md:justify-start gap-1 flex-wrap">
                                            <span class="text-lg md:text-3xl font-extrabold text-gray-900 dark:text-white">{{ showAmount($plan->price, 0) }}</span>
                                        </div>
                                        <span class="text-[9px] md:text-sm font-medium text-gray-500 mt-0.5 md:mt-1">One-time payment</span>
                                    </div>

                                    <!-- Features Grid -->
                                    <div class="grid grid-cols-1 gap-2 md:gap-4 mb-4 md:mb-12 flex-grow">
                                        @foreach($features as $feature)
                                        <div class="flex items-center gap-2 md:gap-4 p-2 md:p-3 rounded-xl md:rounded-2xl bg-gray-50 dark:bg-white/[0.03] border border-gray-100 dark:border-white/5 group-hover/plan:border-current/10 transition-colors">
                                            <div class="w-5 h-5 md:w-6 md:h-6 rounded-md md:rounded-lg {{ $isPro ? 'bg-orange-500/10 text-orange-500' : 'bg-blue-500/10 text-blue-500' }} flex items-center justify-center shrink-0">
                                                <span class="material-symbols-rounded text-[12px] md:text-[16px] font-bold">check_circle</span>
                                            </div>
                                            <span class="text-[10px] md:text-xs font-medium text-gray-600 dark:text-white/80 truncate">{{ trim($feature) }}</span>
                                        </div>
                                        @endforeach
                                    </div>

                                    @if(auth()->check() && in_array($plan->id, $userPlanIds))
                                        <button disabled class="w-full py-2.5 md:py-4 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl md:rounded-2xl font-bold text-[11px] md:text-sm tracking-wide flex items-center justify-center gap-2 cursor-default">
                                            <span class="material-symbols-rounded text-[14px] md:text-[18px]">verified</span>
                                            Subscribed
                                        </button>
                                    @else
                                        <button @click="initiatePurchase('{{ $plan->id }}')" 
                                                :disabled="loading === '{{ $plan->id }}'"
                                                class="w-full py-2.5 md:py-4 {{ $isPro ? 'shadow-orange-500/30' : 'bg-gray-900 dark:bg-white text-white dark:text-gray-900 shadow-black/20' }} rounded-xl md:rounded-2xl font-bold text-[11px] md:text-sm tracking-wide shadow-md md:shadow-xl hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 md:gap-3 disabled:opacity-50"
                                                @if($isPro) style="background: linear-gradient(to right, #f97316, #ec4899); color: white;" @endif>
                                            <template x-if="loading !== '{{ $plan->id }}'">
                                                <div class="flex items-center gap-1.5 md:gap-2">
                                                    <span>Subscribe</span>
                                                    <span class="material-symbols-rounded text-[14px] md:text-[18px]">arrow_forward</span>
                                                </div>
                                            </template>
                                            <template x-if="loading === '{{ $plan->id }}'">
                                                <div class="flex items-center gap-1.5 md:gap-2">
                                                    <svg class="animate-spin h-3 w-3 md:h-4 md:w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                    <span>Processing...</span>
                                                </div>
                                            </template>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="col-span-full py-24 text-center rounded-[4rem] border-4 border-dashed border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.01]">
                                <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-6">
                                    <span class="material-symbols-rounded text-5xl text-gray-300 dark:text-white/10">inventory_2</span>
                                </div>
                                <h3 class="text-xl font-black text-gray-400 uppercase tracking-widest">No Active Plans Found</h3>
                                <p class="text-xs font-bold text-gray-500 mt-2">Check back later for new creator opportunities.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    @endif
        </div>
    </div>
</div>

    <!-- Shared Delete Form -->
    <form x-ref="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function planPurchase() {
            return {
                loading: null,
                initiatePurchase(planId) {
                    this.loading = planId;
                    fetch(`/client/plans/buy/${planId}`, {
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
                            Swal.fire('Success', data.message || 'Action completed successfully', 'success').then(() => {
                                window.location.href = data.redirect;
                            });
                            return;
                        }

                        if (!data.key) {
                            Swal.fire('Configuration Error', 'Razorpay Key is missing.', 'error');
                            this.loading = null;
                            return;
                        }

                        const options = {
                            key: data.key,
                            amount: data.amount,
                            currency: data.currency,
                            name: "Premium Subscription",
                            description: `Upgrade to ${data.plan_name}`,
                            order_id: data.order_id,
                            handler: (response) => {
                                this.verifyPayment(response, data.trx);
                            },
                            prefill: {
                                name: data.name,
                                email: data.email,
                                contact: data.contact
                            },
                            theme: { color: "#ea580c" },
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
                    fetch('{{ route('user.plans.verify.payment') }}', {
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
                                window.location.href = '{{ route('studio.dashboard') }}';
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
</div>
@endsection

