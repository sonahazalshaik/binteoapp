@extends('admin.layouts.app')

@section('title', 'General Settings')
@section('header_title', 'Configuration')

@section('content')
<div class="max-w-5xl mx-auto animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-20">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
        @csrf
        @method('PUT')

        <!-- Identity Section -->
        <div class="bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-6 rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden group transition-all duration-500">
            <div class="absolute -top-6 -right-10 w-32 h-32 bg-orange-500/10 rounded-full blur-3xl"></div>
            <h3 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight mb-8 flex items-center gap-3">
                <span class="material-symbols-rounded text-orange-500">fingerprint</span>
                App Branding
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ml-1">Website Name</label>
                    <input type="text" name="site_name" value="{{ $settings->site_name }}" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-6 py-2.5 text-[12px] font-bold focus:ring-4 focus:ring-orange-500/10 text-slate-900 dark:text-white outline-none transition-all">
                </div>
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ml-1">Website Logo</label>
                    <div class="flex items-center gap-6">
                        @if($settings->logo_path)
                            <div class="w-16 h-16 rounded-xl overflow-hidden orange-gradient-primary" style="background: linear-gradient(90deg, #ff8a00 0%, #ff5200 50%, #e52e71 100%) !important; border border-white/10 p-2 shadow-inner">
                                <img src="{{ Storage::url($settings->logo_path) }}" class="w-full h-full object-contain">
                            </div>
                        @else
                             <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center">
                                <span class="material-symbols-rounded text-slate-400">image</span>
                             </div>
                        @endif
                        <input type="file" name="logo" class="text-[9px] font-black text-slate-400 uppercase tracking-widest file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-orange-500/10 file:text-orange-500 hover:file:bg-orange-500/20 transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingestion Limits -->
        <div class="bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-6 rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden group transition-all duration-500">
            <div class="absolute -top-6 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl"></div>
            <h3 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight mb-8 flex items-center gap-3">
                <span class="material-symbols-rounded text-blue-500">upload_file</span>
                Upload Settings
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ml-1">Maximum Upload Size (Bytes)</label>
                    <input type="number" name="max_upload_size" value="{{ $settings->max_upload_size }}" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-6 py-2.5 text-[12px] font-bold focus:ring-4 focus:ring-blue-500/10 text-slate-900 dark:text-white outline-none transition-all">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2 ml-1 leading-relaxed">System conversion tip: 104,857,600 = 100MB</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ml-1">Allowed Video Formats</label>
                    <input type="text" name="allowed_video_types" value="{{ $settings->allowed_video_types }}" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-6 py-2.5 text-[12px] font-bold focus:ring-4 focus:ring-blue-500/10 text-slate-900 dark:text-white outline-none transition-all">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2 ml-1 leading-relaxed text-indigo-400">Supported list: mp4, mov, avi, mkv</p>
                </div>
            </div>
        </div>

        <!-- Global Logic Sections -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Media Settings -->
            <div class="bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-6 rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden group transition-all duration-500">
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-8">Video Settings</h3>
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest ml-1">Primary Streaming Quality</label>
                    <select name="default_video_quality" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-6 py-2.5 text-[12px] font-bold text-slate-900 dark:text-white outline-none transition-all">
                        <option value="360p" {{ $settings->default_video_quality === '360p' ? 'selected' : '' }}>Standard Definition (360p)</option>
                        <option value="720p" {{ $settings->default_video_quality === '720p' ? 'selected' : '' }}>High Definition (720p HD)</option>
                        <option value="1080p" {{ $settings->default_video_quality === '1080p' ? 'selected' : '' }}>Full High Definition (1080p FHD)</option>
                    </select>
                </div>
            </div>

            <!-- Feature Toggles -->
            <div class="bg-white/40 dark:bg-black/20 backdrop-blur-3xl p-6 rounded-[2rem] border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden group transition-all duration-500">
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-8">Feature Management</h3>
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-5 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/10">
                        <span class="text-[9px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest">Enable Advertisements</span>
                        <input type="checkbox" name="ads_enabled" value="1" {{ $settings->ads_enabled ? 'checked' : '' }} class="w-6 h-6 accent-red-600 rounded-lg cursor-pointer">
                    </div>
                    <div class="flex items-center justify-between p-5 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/10">
                        <span class="text-[9px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest">Enable Revenue Sharing</span>
                        <input type="checkbox" name="monetization_enabled" value="1" {{ $settings->monetization_enabled ? 'checked' : '' }} class="w-6 h-6 accent-emerald-600 rounded-lg cursor-pointer">
                    </div>
                </div>
            </div>
        </div>

        <!-- Master Submit -->
        <div class="flex items-center justify-end pt-6">
            <button type="submit" class="h-12 px-6 rounded-[2rem] orange-gradient-primary relative" style="background: linear-gradient(90deg, #ff8a00 0%, #ff5200 50%, #e52e71 100%) !important; dark:bg-white text-white dark:text-black text-[9px] font-black uppercase tracking-[0.4em] hover:scale-[1.02] active:scale-95 transition-all shadow-2xl flex items-center gap-4 group">
                <div class="absolute inset-0 orange-gradient-primary rounded-[2rem]" style="background: linear-gradient(90deg, #ff8a00 0%, #ff5200 50%, #e52e71 100%) !important; "></div>
                <span class="material-symbols-rounded relative z-10">save</span>
                <span class="relative z-10">Save Changes</span>
            </button>
        </div>
    </form>
</div>
@endsection








