@extends('admin.layouts.app')

@section('title', $pageTitle ?? $onboardingSlide->title)
@section('header_title', $pageTitle ?? $onboardingSlide->title)

@section('content')
<div class="max-w-3xl mx-auto animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        <div class="px-8 py-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Slide Details</h3>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.onboarding-slides.edit', $onboardingSlide->id) }}"
                   class="px-5 py-2.5 bg-blue-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all">
                    Edit
                </a>
                <a href="{{ route('admin.onboarding-slides.index') }}"
                   class="text-[10px] font-bold text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                    Back to List
                </a>
            </div>
        </div>

        <div class="p-8 space-y-8">
            @if($onboardingSlide->image_path)
            <div class="flex justify-center">
                <div class="relative">
                    <img src="{{ $onboardingSlide->image_url }}" alt="{{ $onboardingSlide->title }}"
                         class="w-64 h-64 rounded-[2rem] object-cover bg-slate-100 dark:bg-white/5 shadow-xl">
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 gap-6">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest">Title</label>
                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $onboardingSlide->title }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest">Description</label>
                    <p class="text-sm font-bold text-slate-600 dark:text-white/70 leading-relaxed">{{ $onboardingSlide->description }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest">Sort Order</label>
                    <p class="text-sm font-black text-slate-900 dark:text-white">{{ $onboardingSlide->sort_order ?? 'Not set' }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest">Created</label>
                    <p class="text-sm font-bold text-slate-500 dark:text-white/50">{{ $onboardingSlide->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
