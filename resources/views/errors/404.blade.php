@extends(request()->is('admin*') ? 'admin.layouts.app' : 'layouts.app')

@section('title', 'Page Not Found')
@section('header_title', '404 Error')

@section('content')
<div class="relative flex flex-col items-center justify-center min-h-[60vh] bg-transparent px-4 py-16">
    <div class="w-full max-w-sm sm:max-w-md bg-white dark:bg-[#272727] rounded-[2.5rem] p-8 sm:p-10 shadow-2xl border border-black/5 dark:border-white/10 flex flex-col items-center text-center transform transition-all mt-10">
        
        {{-- Animated Icon --}}
        <div class="relative w-24 h-24 sm:w-28 sm:h-28 mb-8 flex items-center justify-center">
            <div class="absolute inset-0 bg-gradient-to-tr from-orange-500/20 to-rose-500/20 rounded-full blur-2xl animate-pulse"></div>
            <div class="absolute inset-0 bg-gradient-to-tr from-orange-500 to-rose-500 rounded-[2rem] shadow-xl rotate-3"></div>
            <div class="absolute inset-0 bg-white dark:bg-[#1A1A1A] rounded-[2rem] shadow-inner -rotate-3 flex items-center justify-center border border-black/5 dark:border-white/5">
                <span class="material-symbols-rounded text-5xl sm:text-6xl bg-gradient-to-br from-orange-500 to-rose-600 bg-clip-text text-transparent">
                    {{ str_contains(strtolower($exception->getMessage()), 'processing') ? 'movie_edit' : 'broken_image' }}
                </span>
            </div>
        </div>
        
        {{-- Title --}}
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-4 leading-tight">
            @if(str_contains(strtolower($exception->getMessage()), 'processing'))
                Video is Processing
            @else
                Page Not Found
            @endif
        </h1>
        
        {{-- Description --}}
        <p class="text-sm sm:text-base font-bold text-slate-500 dark:text-gray-400 mb-8 leading-relaxed max-w-[280px] sm:max-w-none">
            {{ $exception->getMessage() ?: "We couldn't find the page you're looking for. It might have been moved or deleted." }}
        </p>

        {{-- Action Buttons --}}
        <div class="flex flex-col w-full gap-3 sm:gap-4">
            @if(str_contains(strtolower($exception->getMessage()), 'processing'))
                <button onclick="window.location.reload()" class="w-full py-4 bg-gradient-to-tr from-orange-500 to-rose-600 hover:from-orange-600 hover:to-rose-700 text-white text-xs sm:text-sm font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-orange-500/25 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span class="material-symbols-rounded text-lg">refresh</span>
                    Check Status
                </button>
            @endif
            
            <a href="{{ route('home') }}" class="w-full py-4 bg-slate-100 dark:bg-white/10 hover:bg-slate-200 dark:hover:bg-white/20 text-slate-700 dark:text-white text-xs sm:text-sm font-black uppercase tracking-widest rounded-2xl transition-all active:scale-95 flex items-center justify-center gap-2">
                <span class="material-symbols-rounded text-lg">home</span>
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
