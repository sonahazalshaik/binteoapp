@extends('admin.layouts.app')

@section('title', 'Campaign Intelligence: ' . $campaign->title)
@section('header_title', 'Initiative Oversight')

@section('content')
<div class="space-y-12 animate-in fade-in slide-in-from-bottom-10 duration-700 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Campaign Oversight</h3>
            <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3 leading-relaxed">In-depth performance analysis for #{{ $campaign->title }}</p>
        </div>
        <a href="{{ route('admin.campaign.index') }}" class="w-12 h-12 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 dark:text-white/40 hover:bg-slate-900 hover:text-white transition-all shadow-xl active:scale-90">
            <span class="material-symbols-rounded">arrow_back</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Summary Card -->
        <div class="lg:col-span-2 space-y-10">
            <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2rem] p-6 shadow-2xl relative overflow-hidden group">
                 <div class="flex items-start gap-4">
                    <div class="w-24 h-12 rounded-[2rem] bg-gradient-to-r from-orange-500 to-red-600 text-white flex items-center justify-center shadow-2xl shadow-indigo-600/30">
                        <span class="material-symbols-rounded text-xl">analytics</span>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-tight">{{ $campaign->title }}</h4>
                        <div class="flex items-center gap-4 mt-4">
                            <span class="px-4 py-1 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black text-[9px] font-black uppercase tracking-widest shadow-lg">Advertiser: {{ $campaign->user->username ?? 'System' }}</span>
                            <span class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ">Launched: {{ $campaign->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Associated Assets -->
            <div class="bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-6 rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between mb-10">
                    <h5 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight ">Linked Advertisements</h5>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $campaign->advertisements->count() }} Creative(s)</span>
                </div>
                
                <div class="space-y-6">
                    @forelse($campaign->advertisements as $ad)
                        <div class="flex items-center justify-between p-6 bg-white/60 dark:bg-white/5 rounded-[2rem] border border-white dark:border-white/5 group hover:border-indigo-500/30 transition-all">
                            <div class="flex items-center gap-5">
                                <div class="w-16 aspect-video rounded-xl overflow-hidden bg-slate-900 border border-white/10">
                                    @if($ad->logo)
                                        <img src="{{ getImage(getFilePath('adLogo').'/'.$ad->logo) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[12px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $ad->title }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1 ">{{ $ad->impression }} Impressions • {{ $ad->click }} Clicks</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.advertisement.show', $ad->id) }}" class="p-3 rounded-xl bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-orange-500 transition-all">
                                <span class="material-symbols-rounded">open_in_new</span>
                            </a>
                        </div>
                    @empty
                        <p class="text-center py-10 text-slate-400 font-black uppercase tracking-widest text-xs opacity-20">No Advertisements Linked</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Strategy Info -->
        <div class="space-y-8">
            <div class="bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-4 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden group">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Financial Status</p>
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full @if($campaign->status == 1) bg-emerald-500 @else bg-slate-300 @endif shadow-lg"></span>
                    <h4 class="text-[12px] font-black text-slate-900 dark:text-white uppercase ">${{ number_format($campaign->total_amount, 2) }}</h4>
                </div>
                
                <div class="mt-8 space-y-6 pt-6 border-t border-slate-100 dark:border-white/5">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Global Status</span>
                        <span class="text-xs font-black @if($campaign->status == 1) text-emerald-600 @else text-slate-400 @endif uppercase tracking-widest">{{ $campaign->status == 1 ? 'Active' : 'Paused' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Payment</span>
                        <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Verified</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.campaign.status', $campaign->id) }}" method="POST">
                @csrf
                <button class="w-full h-12 rounded-3xl @if($campaign->status == 1) bg-rose-600 text-white @else bg-emerald-600 text-white @endif flex items-center justify-center gap-4 text-[9px] font-black uppercase tracking-[0.3em] hover:scale-[1.05] transition-all shadow-xl active:scale-95 ">
                    <span class="material-symbols-rounded">@if($campaign->status == 1) pause_circle @else play_circle @endif</span>
                    {{ $campaign->status == 1 ? 'Suspend Initiative' : 'Resume Initiative' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection



