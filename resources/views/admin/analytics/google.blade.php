@extends('admin.layouts.app')

@section('panel')
<div class="max-w-7xl mx-auto pb-12 animate-in fade-in duration-700">
    
    <!-- Top Configuration Status Bar -->
    <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mb-8">
        <div class="p-6 sm:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-rounded text-2xl">analytics</span>
                </div>
                <div>
                    <h4 class="text-base font-bold text-slate-800">@lang('Google Analytics Property Integration')</h4>
                    <p class="text-xs text-slate-400 mt-1">@lang('Current tracking status and reporting dashboards.')</p>
                </div>
            </div>

            <!-- Status Badges -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-100 text-xs font-semibold text-slate-600">
                    <span class="w-2 h-2 rounded-full {{ gs('google_analytics_id') ? 'bg-emerald-500' : 'bg-rose-400' }}"></span>
                    <span>@lang('Tracking Tag ID'): <strong>{{ gs('google_analytics_id') ?: __('Not Configured') }}</strong></span>
                </div>
                <a href="{{ route('admin.setting.general') }}" class="h-9 px-4 rounded-lg bg-indigo-50 border border-indigo-100 text-xs font-black text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all flex items-center gap-1.5 uppercase tracking-wider">
                    <span class="material-symbols-rounded text-sm">settings</span>
                    @lang('Update Keys')
                </a>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Section -->
    @if(gs('google_analytics_embed_url'))
        <!-- Full Embedded Dashboard View -->
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden relative flex flex-col min-h-[75vh]">
            <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h4 class="text-sm font-bold text-slate-700">@lang('Live Looker Studio Dashboard')</h4>
                </div>
                <button onclick="toggleFullscreen()" class="h-8 px-3 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center gap-1.5 text-[10px] font-black uppercase tracking-wider transition-all">
                    <span class="material-symbols-rounded text-sm">fullscreen</span>
                    @lang('Toggle Fullscreen')
                </button>
            </div>
            
            <div class="flex-grow w-full relative min-h-[650px] bg-slate-50" id="embed-container">
                <iframe src="{{ gs('google_analytics_embed_url') }}" 
                        frameborder="0" 
                        style="border:0" 
                        allowfullscreen 
                        sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"
                        class="absolute inset-0 w-full h-full"></iframe>
            </div>
        </div>
    @else
        <!-- Instruction Screen if Not Configured -->
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 sm:p-12 text-center max-w-3xl mx-auto">
            <div class="w-20 h-20 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-6 shadow-inner">
                <span class="material-symbols-rounded text-4xl">dashboard_customize</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-slate-800 uppercase tracking-tight mb-3">Setup GA4 Visual Dashboard</h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-8">
                @lang('Integrate a beautiful, live-updating Google Analytics report into your admin panel. By creating a Google Looker Studio report and sharing it, you can embed rich charts directly here.')
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left mb-10">
                <div class="p-5 rounded-xl border border-slate-100 bg-slate-50/50">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-xs mb-3">1</div>
                    <h5 class="text-xs font-bold text-slate-700 mb-1">Create Report</h5>
                    <p class="text-[10px] text-slate-400 leading-relaxed">Go to <a href="https://lookerstudio.google.com" target="_blank" class="text-indigo-600 font-bold hover:underline">Looker Studio</a>, create a blank report, and connect your Google Analytics property.</p>
                </div>
                <div class="p-5 rounded-xl border border-slate-100 bg-slate-50/50">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-xs mb-3">2</div>
                    <h5 class="text-xs font-bold text-slate-700 mb-1">Generate Embed URL</h5>
                    <p class="text-[10px] text-slate-400 leading-relaxed">Click <strong>Share</strong> -> <strong>Embed report</strong>. Select <strong>Embed URL</strong> and copy the generated link source.</p>
                </div>
                <div class="p-5 rounded-xl border border-slate-100 bg-slate-50/50">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-xs mb-3">3</div>
                    <h5 class="text-xs font-bold text-slate-700 mb-1">Save Embed Link</h5>
                    <p class="text-[10px] text-slate-400 leading-relaxed">Go to <strong>General Settings</strong>, paste the link in the Google Analytics Embed URL field, and submit.</p>
                </div>
            </div>

            <a href="{{ route('admin.setting.general') }}" class="inline-flex h-12 px-6 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-black uppercase tracking-widest text-[11px] items-center justify-center gap-2 shadow-lg shadow-orange-500/20 active:scale-95 transition-all ">
                <span class="material-symbols-rounded text-base">settings</span>
                @lang('Go Configure Embed Link')
            </a>
        </div>
    @endif
</div>

@if(gs('google_analytics_embed_url'))
<script>
    function toggleFullscreen() {
        const elem = document.getElementById('embed-container');
        if (!document.fullscreenElement) {
            elem.requestFullscreen().catch(err => {
                alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
            });
        } else {
            document.exitFullscreen();
        }
    }
</script>
@endif
@endsection

