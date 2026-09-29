@extends('admin.layouts.app')

@section('panel')
<div class="max-w-7xl mx-auto animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-20">
    
    <!-- Header -->
    <div class="mb-12 flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase ">Logo & <span class="text-orange-500">Favicon</span></h2>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.3em] mt-2">Identity & Branding Assets</p>
        </div>
        <a href="{{ route('admin.setting.system') }}" class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 hover:bg-orange-500 hover:text-white transition-all group">
            <span class="material-symbols-rounded text-xl group-hover:rotate-180 transition-transform duration-500">arrow_back</span>
            <span class="text-[10px] font-black uppercase tracking-widest">Back to Dashboard</span>
        </a>
    </div>

    <!-- Cache Intelligence Alert -->
    <div class="relative mb-12 group">
        <div class="absolute inset-0 bg-blue-500/5 blur-3xl rounded-[2rem]"></div>
        <div class="relative bg-white/40 dark:bg-white/[0.03] backdrop-blur-3xl border border-blue-500/20 dark:border-blue-500/10 rounded-[2.5rem] p-8 flex items-start gap-6 shadow-2xl">
            <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-rounded text-blue-500 fill-1 text-3xl">info</span>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">Cache Awareness Required</h4>
                <p class="text-xs font-medium text-slate-500 dark:text-white/40 mt-3 leading-relaxed max-w-4xl">
                    If changes are not reflected immediately, please <a href="{{ route('admin.system.optimize.clear') }}" class="text-blue-500 font-black hover:underline">Clear System Cache</a>. 
                    Browsers often store logos locally. If the old assets persist, a hard refresh (Ctrl+F5) or network-level cache clearing might be necessary.
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.setting.logo.icon.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Logo White (Light Theme) -->
            <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white/20 dark:border-white/10 rounded-[2.5rem] p-8 shadow-2xl flex flex-col h-full">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-orange-500/10 flex items-center justify-center">
                        <span class="material-symbols-rounded text-orange-500 fill-1">light_mode</span>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-800 dark:text-white uppercase tracking-tight">Primary Logo</h3>
                        <p class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">Logo for Dark/Navy Backgrounds</p>
                    </div>
                </div>
                
                <div class="flex-grow flex flex-col">
                    <div class="relative bg-slate-900 rounded-3xl p-8 mb-6 border border-white/10 overflow-hidden flex items-center justify-center min-h-[160px]">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,138,0,0.1),transparent)]"></div>
                        <img src="{{ siteLogo() }}?v={{ time() }}" class="max-h-16 w-auto object-contain relative z-10" alt="Logo White">
                    </div>
                    <x-image-uploader name="logo" :imagePath="siteLogo() . '?' . time()" :size="false" class="w-full" id="uploadLogo" :required="false" />
                </div>
            </div>

            <!-- Logo Dark (White Theme) -->
            <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white/20 dark:border-white/10 rounded-[2.5rem] p-8 shadow-2xl flex flex-col h-full">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center">
                        <span class="material-symbols-rounded text-indigo-500 fill-1">dark_mode</span>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-800 dark:text-white uppercase tracking-tight">Secondary Logo</h3>
                        <p class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">Logo for Light Backgrounds</p>
                    </div>
                </div>
                
                <div class="flex-grow flex flex-col">
                    <div class="relative bg-white rounded-3xl p-8 mb-6 border border-slate-200 overflow-hidden flex items-center justify-center min-h-[160px]">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(99,102,241,0.05),transparent)]"></div>
                        <img src="{{ siteLogo('dark') }}?v={{ time() }}" class="max-h-16 w-auto object-contain relative z-10" alt="Logo Dark">
                    </div>
                    <x-image-uploader name="logo_dark" :imagePath="siteLogo('dark') . '?' . time()" :size="false" class="w-full" id="uploadLogo1" :required="false" />
                </div>
            </div>

            <!-- Favicon -->
            <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white/20 dark:border-white/10 rounded-[2.5rem] p-8 shadow-2xl flex flex-col h-full">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center">
                        <span class="material-symbols-rounded text-emerald-500 fill-1">diamond</span>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-800 dark:text-white uppercase tracking-tight">Favicon</h3>
                        <p class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">Browser Tab Icon (PNG Only)</p>
                    </div>
                </div>
                
                <div class="flex-grow flex flex-col">
                    <div class="relative bg-slate-100/50 dark:bg-white/5 rounded-3xl p-8 mb-6 border border-slate-200 dark:border-white/10 overflow-hidden flex items-center justify-center min-h-[160px]">
                        <div class="w-20 h-20 rounded-2xl bg-white dark:bg-black/40 shadow-xl flex items-center justify-center p-4">
                            <img src="{{ siteFavicon() }}?v={{ time() }}" class="max-h-12 w-auto object-contain" alt="Favicon">
                        </div>
                    </div>
                    <x-image-uploader name="favicon" :imagePath="siteFavicon() . '?' . time()" :size="false" class="w-full" id="uploadFavicon" :required="false" />
                </div>
            </div>

            <!-- Splash Screen Logo -->
            <div class="bg-white/60 dark:bg-white/[0.03] backdrop-blur-xl border border-white/20 dark:border-white/10 rounded-[2.5rem] p-8 shadow-2xl flex flex-col h-full">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/10 flex items-center justify-center">
                        <span class="material-symbols-rounded text-rose-500 fill-1">phone_android</span>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-800 dark:text-white uppercase tracking-tight">Splash Screen Logo</h3>
                        <p class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1">Local Storage Only (No Cloudflare)</p>
                    </div>
                </div>
                
                <div class="flex-grow flex flex-col">
                    <div class="relative bg-slate-100/50 dark:bg-white/5 rounded-3xl p-8 mb-6 border border-slate-200 dark:border-white/10 overflow-hidden flex items-center justify-center min-h-[160px]">
                        <div class="w-24 h-24 rounded-3xl bg-white dark:bg-black/40 shadow-xl flex items-center justify-center p-4">
                            <img src="{{ asset('assets/images/logo_splash.png') }}?v={{ time() }}" class="max-h-16 w-auto object-contain" alt="Splash Logo">
                        </div>
                    </div>
                    <x-image-uploader name="splash_logo" :imagePath="asset('assets/images/logo_splash.png') . '?' . time()" :size="false" class="w-full" id="uploadSplashLogo" :required="false" />
                </div>
            </div>
        </div>

        <!-- Master Submit -->
        <div class="flex items-center justify-end pt-10">
            <button type="submit" class="h-16 px-12 rounded-3xl bg-gradient-to-r from-orange-500 to-rose-500 text-white text-[11px] font-black uppercase tracking-[0.4em] hover:scale-[1.02] active:scale-95 transition-all shadow-2xl shadow-orange-500/20 flex items-center gap-4 group">
                <span class="material-symbols-rounded text-2xl group-hover:rotate-12 transition-transform">upload</span>
                <span>Update Branding Assets</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('style')
<style>
    .fill-1 { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    
    /* Standardizing the file uploader component appearance if possible */
    .image-upload-wrapper {
        border-radius: 1.5rem !important;
        overflow: hidden !important;
    }
    .image-upload-preview {
        border-radius: 1.5rem !important;
    }
</style>
@endpush

