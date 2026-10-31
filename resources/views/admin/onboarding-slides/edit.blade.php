@extends('admin.layouts.app')

@section('title', $pageTitle ?? 'Edit Onboarding Slide')
@section('header_title', $pageTitle ?? 'Edit Onboarding Slide')

@section('content')
<div class="max-w-3xl mx-auto animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        <div class="px-8 py-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Edit Onboarding Slide</h3>
            <a href="{{ route('admin.onboarding-slides.index') }}" class="text-[10px] font-bold text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">Back to List</a>
        </div>

        <form action="{{ route('admin.onboarding-slides.update', $onboardingSlide->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf @method('PUT')

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest">Title</label>
                <input type="text" name="title" value="{{ old('title', $onboardingSlide->title) }}" required maxlength="60"
                       class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/20 dark:focus:ring-white/20 transition-all">
                @error('title') <p class="text-[10px] font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest">Description</label>
                <textarea name="description" required maxlength="280" rows="3"
                          class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/20 dark:focus:ring-white/20 transition-all resize-none">{{ old('description', $onboardingSlide->description) }}</textarea>
                @error('description') <p class="text-[10px] font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest">Current Image</label>
                @if($onboardingSlide->image_path)
                    <div class="mb-3 flex items-center gap-4" id="currentImageWrap">
                        <img src="{{ $onboardingSlide->image_url }}" alt="{{ $onboardingSlide->title }}" class="w-32 h-32 rounded-2xl object-cover bg-slate-100 dark:bg-white/5">
                        <button type="button" id="removeImageBtn" class="px-4 py-2.5 bg-red-500/10 text-red-500 rounded-xl text-[10px] font-black uppercase tracking-widest border border-red-500/20 hover:bg-red-500 hover:text-white active:scale-95 transition-all">
                            Remove image
                        </button>
                    </div>
                    <div id="imageRemovedNote" style="display: none;" class="mb-3 items-center gap-4 rounded-2xl border border-dashed border-red-500/30 bg-red-500/5 px-4 py-3">
                        <p class="text-[10px] font-black text-red-500 uppercase tracking-widest">Image marked for removal — it will be deleted when you update the slide.</p>
                        <button type="button" id="undoRemoveImageBtn" class="px-4 py-2 bg-white dark:bg-white/10 text-slate-600 dark:text-white/80 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 dark:border-white/10 hover:bg-slate-100 active:scale-95 transition-all">
                            Undo
                        </button>
                    </div>
                    <input type="hidden" name="remove_image" id="remove_image_flag" value="0">
                    <script>
                    (function () {
                        var wrap = document.getElementById('currentImageWrap');
                        var note = document.getElementById('imageRemovedNote');
                        var flag = document.getElementById('remove_image_flag');
                        var rmBtn = document.getElementById('removeImageBtn');
                        var undoBtn = document.getElementById('undoRemoveImageBtn');
                        if (!wrap || !flag) return;
                        if (rmBtn) rmBtn.addEventListener('click', function () {
                            wrap.style.display = 'none';
                            if (note) note.style.display = 'flex';
                            flag.value = '1';
                        });
                        if (undoBtn) undoBtn.addEventListener('click', function () {
                            wrap.style.display = 'flex';
                            if (note) note.style.display = 'none';
                            flag.value = '0';
                        });
                        var fileInput = document.querySelector('input[type="file"][name="image"]');
                        if (fileInput) fileInput.addEventListener('change', function () {
                            if (fileInput.files && fileInput.files.length) {
                                flag.value = '0';
                                wrap.style.display = 'flex';
                                if (note) note.style.display = 'none';
                            }
                        });
                    })();
                    </script>
                @endif
                <input type="file" name="image" accept="image/*"
                       class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold text-slate-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-slate-900 dark:file:bg-white file:text-white dark:file:text-slate-900 hover:file:bg-slate-700 transition-all">
                <p class="text-[9px] font-bold text-slate-400 mt-1">Leave empty to keep current image.</p>
                @error('image') <p class="text-[10px] font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $onboardingSlide->sort_order) }}" min="0"
                       class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/20 dark:focus:ring-white/20 transition-all">
                @error('sort_order') <p class="text-[10px] font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                        class="w-full py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-[0.25em] shadow-xl active:scale-[0.98] transition-all">
                    Update Slide
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
