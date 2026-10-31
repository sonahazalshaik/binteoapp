@extends('admin.layouts.app')

@section('panel')
<div class="max-w-[1600px] mx-auto animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-20">
    
    <!-- Search Intelligence Header -->
    <div class="relative mb-12">
        <div class="absolute inset-0 bg-gradient-to-r from-orange-500/10 to-blue-500/10 blur-3xl rounded-[3rem]"></div>
        <div class="relative bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-8 rounded-[2.5rem] border border-white/20 shadow-2xl flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase ">System <span class="text-orange-500">Settings</span></h2>
                <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.3em] mt-2">Core Configuration Dashboard</p>
            </div>
            
            <div class="relative w-full md:w-96 group">
                <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none">
                    <span class="material-symbols-rounded text-slate-400 group-focus-within:text-orange-500 transition-colors">search</span>
                </div>
                <input type="text" class="searchInput w-full bg-slate-100/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl py-4 pl-14 pr-6 text-xs font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-4 focus:ring-orange-500/10 outline-none transition-all" placeholder="Search for settings (e.g. logo, cron, seo)...">
            </div>
        </div>
    </div>

    <!-- Modular Settings Grid -->
    <div class="emptyArea"></div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach ($settings ?? [] as $key => $setting)
            @if(isset($setting->disabled) && $setting->disabled)
                @continue
            @endif
            @php
                $params = null;
                if (@$setting->params) {
                    foreach ($setting->params as $paramVal) {
                        $params[] = array_values((array)$paramVal)[0];
                    }
                }
                
                // Custom Color Mapping based on keywords
                $colorClass = 'text-blue-500';
                $bgClass = 'bg-blue-500/10';
                $icon = $setting->icon;
                
                if(str_contains($key, 'general')) { $colorClass = 'text-orange-500'; $bgClass = 'bg-orange-500/10'; }
                elseif(str_contains($key, 'notification')) { $colorClass = 'text-purple-500'; $bgClass = 'bg-purple-500/10'; }
                elseif(str_contains($key, 'payment') || str_contains($key, 'withdraw')) { $colorClass = 'text-emerald-500'; $bgClass = 'bg-emerald-500/10'; }
                elseif(str_contains($key, 'seo') || str_contains($key, 'frontend')) { $colorClass = 'text-cyan-500'; $bgClass = 'bg-cyan-500/10'; }
                elseif(str_contains($key, 'maintenance') || str_contains($key, 'robots')) { $colorClass = 'text-rose-500'; $bgClass = 'bg-rose-500/10'; }
                
                // Convert Font Awesome to Material Symbols where possible
                $materialIcon = 'settings';
                if(str_contains($icon, 'cog')) $materialIcon = 'settings';
                elseif(str_contains($icon, 'images')) $materialIcon = 'image';
                elseif(str_contains($icon, 'bell')) $materialIcon = 'notifications';
                elseif(str_contains($icon, 'credit-card')) $materialIcon = 'payments';
                elseif(str_contains($icon, 'bank')) $materialIcon = 'account_balance';
                elseif(str_contains($icon, 'globe')) $materialIcon = 'public';
                elseif(str_contains($icon, 'html5') || str_contains($icon, 'list')) $materialIcon = 'web';
                elseif(str_contains($icon, 'user-check')) $materialIcon = 'verified_user';
                elseif(str_contains($icon, 'percentage')) $materialIcon = 'percent';
                elseif(str_contains($icon, 'ad')) $materialIcon = 'campaign';
                elseif(str_contains($icon, 'calendar')) $materialIcon = 'event';
                elseif(str_contains($icon, 'folder')) $materialIcon = 'storage';
                elseif(str_contains($icon, 'user-circle')) $materialIcon = 'account_circle';
                elseif(str_contains($icon, 'language')) $materialIcon = 'language';
                elseif(str_contains($icon, 'puzzle')) $materialIcon = 'extension';
                elseif(str_contains($icon, 'clock')) $materialIcon = 'schedule';
                elseif(str_contains($icon, 'shield')) $materialIcon = 'security';
                elseif(str_contains($icon, 'robot')) $materialIcon = 'smart_toy';
                elseif(str_contains($icon, 'cookie')) $materialIcon = 'cookie';
                elseif(str_contains($icon, 'css')) $materialIcon = 'css';
                elseif(str_contains($icon, 'sitemap')) $materialIcon = 'account_tree';
            @endphp

            <div class="{{ $key }} searchItems group">
                <a href="{{ route($setting->route_name, $params) }}" class="block relative h-full bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white/20 dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                    <!-- Hover Glow -->
                    <div class="absolute -top-24 -right-24 w-48 h-48 {{ $bgClass }} rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <div class="flex items-start gap-6 relative z-10">
                        <div class="w-16 h-16 rounded-2xl {{ $bgClass }} flex items-center justify-center flex-shrink-0 transition-transform duration-500 group-hover:scale-110">
                            <span class="material-symbols-rounded text-3xl {{ $colorClass }} fill-1">{{ $materialIcon }}</span>
                        </div>
                        
                        <div class="flex-grow">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ $setting->title }}</h3>
                                <span class="material-symbols-rounded text-slate-300 dark:text-white/10 group-hover:text-orange-500 transition-colors">arrow_forward_ios</span>
                            </div>
                            <p class="text-xs font-medium text-slate-500 dark:text-white/40 mt-3 leading-relaxed">{{ $setting->subtitle }}</p>
                        </div>
                    </div>

                    <!-- Keywords Hidden for Search -->
                    <span class="hidden">@foreach($setting->keyword as $word){{ $word }} @endforeach</span>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            
            $('.searchInput').on('input', function() {
                var query = $(this).val().toLowerCase().trim();
                var hasResults = false;

                $('.searchItems').each(function() {
                    var content = $(this).text().toLowerCase();
                    if (content.includes(query)) {
                        $(this).show();
                        hasResults = true;
                    } else {
                        $(this).hide();
                    }
                });

                $('.emptyArea').html('');
                if (!hasResults) {
                    $('.emptyArea').html(`
                        <div class="w-full py-20 flex flex-col items-center justify-center bg-white/20 dark:bg-white/[0.02] backdrop-blur-xl rounded-[3rem] border border-dashed border-slate-200 dark:border-white/10">
                            <span class="material-symbols-rounded text-6xl text-slate-300 dark:text-white/10">search_off</span>
                            <h5 class="text-slate-400 dark:text-white/20 font-black uppercase tracking-[0.3em] mt-6">No matching settings found</h5>
                        </div>
                    `);
                }
            });

        })(jQuery);
    </script>
@endpush

@push('style')
<style>
    .fill-1 { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

