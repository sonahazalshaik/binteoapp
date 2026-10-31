@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('header_title', 'Modify Category')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-in fade-in slide-in-from-bottom-6 duration-700 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Modify Identity</h3>
            <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-widest mt-2 leading-relaxed ">Refining parameters for segment #{{ $category->id }}</p>
        </div>
        <a href="{{ route('admin.category.index') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all shadow-sm active:scale-95">
            <span class="material-symbols-rounded">arrow_back</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] p-8 lg:p-12 shadow-sm relative overflow-hidden group">
        <form action="{{ route('admin.category.save', $category->id) }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Name -->
                <x-input 
                    name="name" 
                    label="Identity Name" 
                    value="{{ $category->name }}" 
                    placeholder="E.g. Gaming..." 
                    required 
                    hint="Enter the display name for this category."
                />

                <!-- Slug -->
                <x-input 
                    name="slug" 
                    label="URL Signal (Slug)" 
                    value="{{ $category->slug }}" 
                    placeholder="gaming-videos" 
                    required 
                    hint="The unique URL-friendly version of the name."
                />

                <!-- Icon -->
                <div class="md:col-span-2" x-data="categoryIconPicker(@js($materialIcons ?? []), @js(old('icon', $category->icon ?? 'category')))">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30 px-1 mb-2">Visual Symbol</label>
                    <input type="hidden" name="icon" :value="selected">
                    <button type="button" @click="open = !open" class="w-full min-h-12 sm:min-h-16 px-6 rounded-2xl border border-slate-200/60 dark:border-white/5 bg-white dark:bg-black/20 flex items-center gap-4 transition-all duration-300 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 text-left">
                        <span class="material-symbols-rounded text-2xl text-orange-500 shrink-0" x-text="selected || 'category'"></span>
                        <span class="text-[13px] font-black text-slate-900 dark:text-white truncate" x-text="selected || 'category'"></span>
                        <span class="material-symbols-rounded ml-auto text-slate-400 shrink-0 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition class="mt-2 rounded-2xl border border-slate-200/60 dark:border-white/10 bg-white dark:bg-[#121212] shadow-xl overflow-hidden">
                        <div class="p-3 border-b border-slate-100 dark:border-white/5">
                            <input type="text" x-model="search" placeholder="Search icons..." class="w-full h-11 px-4 rounded-xl border border-slate-200/60 dark:border-white/10 bg-slate-50 dark:bg-black/20 text-[13px] font-bold text-slate-900 dark:text-white placeholder:text-slate-400 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10">
                        </div>
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 p-4 max-h-44 overflow-y-auto custom-scrollbar">
                            <template x-for="icon in filtered" :key="icon">
                                <button type="button" @click="selected = icon; open = false" :title="icon" class="flex flex-col items-center gap-1 p-3 rounded-xl border transition-all" :class="selected === icon ? 'border-orange-500 bg-orange-500/10 text-orange-500' : 'border-slate-100 dark:border-white/5 text-slate-500 dark:text-white/60 hover:border-orange-500/50 hover:text-orange-500'">
                                    <span class="material-symbols-rounded text-2xl" x-text="icon"></span>
                                    <span class="text-[8px] font-bold truncate w-full text-center" x-text="icon"></span>
                                </button>
                            </template>
                            <template x-if="filtered.length === 0">
                                <p class="col-span-full text-center text-[11px] font-bold text-slate-400 py-4">No icons match "<span x-text="search"></span>" — use the custom name box below.</p>
                            </template>
                        </div>
                        <div class="p-3 border-t border-slate-100 dark:border-white/5">
                            <button type="button" @click="customOpen = !customOpen" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-orange-500 transition-colors">Custom icon name...</button>
                            <div x-show="customOpen" class="mt-2">
                                <input type="text" x-model="selected" placeholder="sports_esports" class="w-full h-11 px-4 rounded-xl border border-slate-200/60 dark:border-white/10 bg-slate-50 dark:bg-black/20 text-[13px] font-bold text-slate-900 dark:text-white placeholder:text-slate-400 outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10">
                            </div>
                        </div>
                    </div>
                    <p class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1 px-1">
                        <span class="material-symbols-rounded text-xs">lightbulb</span>
                        Pick a Material Symbols icon — live preview above. Current: <span class="text-orange-500" x-text="selected"></span>
                    </p>
                    @error('icon')
                        <div class="mt-1 text-xs text-red-500 font-medium flex items-center gap-1">
                            <span class="material-symbols-rounded text-sm">error</span>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-8 border-t border-slate-100 dark:border-white/5">
                <button type="submit" class="h-14 px-12 rounded-2xl orange-gradient-primary text-white flex items-center justify-center gap-3 text-[12px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-orange-500/20 active:scale-95 ">
                    <span class="material-symbols-rounded text-lg">sync</span>
                    Apply Modifications
                </button>
            </div>
        </form>
    </div>
</div>

@push('script')
<script>
function categoryIconPicker(icons, initial) {
    return {
        open: false,
        search: '',
        customOpen: false,
        icons: Array.isArray(icons) ? icons : [],
        selected: initial || 'category',
        get filtered() {
            const q = (this.search || '').trim().toLowerCase();
            if (!q) return this.icons;
            return this.icons.filter(n => String(n).toLowerCase().includes(q));
        }
    };
}
</script>
@endpush
@endsection

