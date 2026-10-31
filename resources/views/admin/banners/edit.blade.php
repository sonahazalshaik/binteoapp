@extends('admin.layouts.app')

@section('title', $pageTitle)
@section('header_title', $pageTitle)

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-700 pb-20">
    <div class="flex items-center gap-4 px-4 lg:px-0">
        <a href="{{ route('admin.banners.index', $slot) }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-colors shadow-sm">
            <span class="material-symbols-rounded">arrow_back</span>
        </a>
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Campaign Calibration: {{ strtoupper($slot) }}</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Refining active deployment for this specific cloud slot</p>
        </div>
    </div>

    <form action="{{ route('admin.banners.update', ['slot' => $slot, 'id' => $banner->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[3rem] p-12 shadow-2xl relative overflow-hidden group">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/5 rounded-full blur-[80px] pointer-events-none"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 relative z-10">
                <!-- Slot Display (Read-only) -->
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ">Target Slot Assignment</label>
                    <div class="w-full h-16 rounded-[1.5rem] bg-slate-100 dark:bg-white/10 flex items-center px-6 text-sm font-black text-slate-500 uppercase tracking-widest ">
                        {{ strtoupper($slot) }}
                    </div>
                </div>

                <!-- External Link -->
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ">Target Destination URL</label>
                    <input type="url" name="link" value="{{ $banner->link }}" placeholder="https://example.com/promo" class="w-full h-16 rounded-[1.5rem] bg-slate-50 dark:bg-white/5 border-2 border-transparent focus:border-blue-500/50 focus:bg-white transition-all px-6 text-sm font-bold text-slate-900 dark:text-white outline-none">
                </div>

                <!-- Start Date -->
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ">Campaign Ignition Date</label>
                    <input type="datetime-local" name="start_date" value="{{ $banner->start_date->format('Y-m-d\TH:i') }}" class="w-full h-16 rounded-[1.5rem] bg-slate-50 dark:bg-white/5 border-2 border-transparent focus:border-blue-500/50 focus:bg-white transition-all px-6 text-sm font-bold text-slate-900 dark:text-white outline-none" required>
                </div>

                <!-- End Date -->
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ">Campaign Termination Date</label>
                    <input type="datetime-local" name="end_date" value="{{ $banner->end_date->format('Y-m-d\TH:i') }}" class="w-full h-16 rounded-[1.5rem] bg-slate-50 dark:bg-white/5 border-2 border-transparent focus:border-blue-500/50 focus:bg-white transition-all px-6 text-sm font-bold text-slate-900 dark:text-white outline-none" required>
                </div>

                <!-- Active / Inactive Switch -->
                <div class="md:col-span-2 flex items-center justify-between p-6 bg-slate-50 dark:bg-white/[0.03] rounded-[1.5rem] border border-slate-100 dark:border-white/5">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1">Campaign Status</span>
                        <span class="text-xs font-bold text-slate-900 dark:text-white" id="status-label">{{ $banner->status ? 'Active — shows on site' : 'Inactive — hidden from site' }}</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" class="sr-only peer" @if($banner->status) checked @endif onchange="document.getElementById('status-label').textContent = this.checked ? 'Active — shows on site' : 'Inactive — hidden from site'">
                        <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500"></div>
                    </label>
                </div>

                <!-- Image Upload -->
                <div class="md:col-span-2 space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ">Update Visual Assets (R2 Multi-Support)</label>
                    <div class="relative group/upload">
                        <input type="file" name="banner_imgs[]" id="banner_imgs" class="hidden" accept="image/*" multiple onchange="previewImages(this)">
                        <label for="banner_imgs" class="w-full min-h-[16rem] rounded-[2.5rem] border-4 border-dashed border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] flex flex-col items-center justify-center cursor-pointer hover:bg-slate-100 dark:hover:bg-white/5 transition-all group-hover/upload:border-blue-500/30 p-8">
                            <div id="upload-placeholder" class="hidden flex flex-col items-center">
                                <span class="material-symbols-rounded text-5xl text-slate-300 group-hover/upload:scale-110 transition-transform">library_add</span>
                                <p class="mt-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] text-center">Swap current or add new assets</p>
                            </div>
                            
                            <div id="preview-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full">
                                <!-- Current Image -->
                                <div class="relative aspect-video rounded-xl overflow-hidden border-2 border-blue-500/50 shadow-lg shadow-blue-500/20" id="current-preview-wrapper">
                                    <div class="absolute top-2 left-2 z-10 bg-blue-500 text-white text-[8px] font-black px-2 py-1 rounded-md uppercase tracking-widest">Active</div>
                                    <img id="preview" src="{{ getImage($banner->image) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                        <a href="{{ getImage($banner->image) }}" target="_blank" class="text-white text-[10px] font-bold underline flex items-center gap-1">
                                            <span class="material-symbols-rounded text-sm">link</span> R2 URL
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end">
                <button type="submit" class="h-16 px-12 rounded-[1.5rem] bg-slate-900 dark:bg-white text-white dark:text-black text-[11px] font-black uppercase tracking-[0.4em] hover:scale-[1.02] transition-all shadow-2xl active:scale-95 flex items-center gap-4">
                    <span class="material-symbols-rounded text-2xl">published_with_changes</span>
                    Synchronize {{ strtoupper($slot) }} Changes
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImages(input) {
        const grid = document.getElementById('preview-grid');
        const placeholder = document.getElementById('upload-placeholder');
        const currentWrapper = document.getElementById('current-preview-wrapper');
        
        // Remove all but the current wrapper if no new files
        if (input.files && input.files.length > 0) {
            // Keep current wrapper as a reference or clear everything
            grid.innerHTML = '';
            placeholder.classList.add('hidden');
            
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-video rounded-xl overflow-hidden border-2 ' + (index === 0 ? 'border-blue-500 shadow-lg shadow-blue-500/20' : 'border-white/10');
                    div.innerHTML = `
                        ${index === 0 ? '<div class="absolute top-2 left-2 z-10 bg-blue-500 text-white text-[8px] font-black px-2 py-1 rounded-md uppercase tracking-widest">Replacing</div>' : '<div class="absolute top-2 left-2 z-10 bg-green-500 text-white text-[8px] font-black px-2 py-1 rounded-md uppercase tracking-widest">New</div>'}
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                    `;
                    grid.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endsection

