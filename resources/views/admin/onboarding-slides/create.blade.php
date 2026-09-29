@extends('admin.layouts.app')

@section('title', $pageTitle ?? 'Create Onboarding Slide')
@section('header_title', $pageTitle ?? 'Create Onboarding Slide')

@section('content')
<div class="max-w-3xl mx-auto animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        <div class="px-8 py-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">New Onboarding Slide</h3>
            <a href="{{ route('admin.onboarding-slides.index') }}" class="text-[10px] font-bold text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">Back to List</a>
        </div>

        <form action="{{ route('admin.onboarding-slides.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required maxlength="60"
                       class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/20 dark:focus:ring-white/20 transition-all">
                @error('title') <p class="text-[10px] font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest">Description</label>
                <textarea name="description" required maxlength="280" rows="3"
                          class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/20 dark:focus:ring-white/20 transition-all resize-none">{{ old('description') }}</textarea>
                @error('description') <p class="text-[10px] font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest">Image (Max 2MB)</label>
                <input type="file" name="image" accept="image/*" required
                       class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold text-slate-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-slate-900 dark:file:bg-white file:text-white dark:file:text-slate-900 hover:file:bg-slate-700 transition-all">
                @error('image') <p class="text-[10px] font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order') }}" min="0"
                       class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/20 dark:focus:ring-white/20 transition-all">
                @error('sort_order') <p class="text-[10px] font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                        class="w-full py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-[0.25em] shadow-xl active:scale-[0.98] transition-all">
                    Create Slide
                </button>
                <a href="{{ route('admin.onboarding-slides.index') }}"
                   class="w-full py-4 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 rounded-2xl text-[10px] font-black uppercase tracking-[0.25em] text-center hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
