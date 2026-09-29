@extends('admin.layouts.app')

@section('title', 'Create Portfolio')
@section('header_title', 'Marketplace Portfolios')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Create Portfolio</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Add a new portfolio entry with multiple images</p>
        </div>
        <a href="{{ route('admin.marketplace.portfolio.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90">
            <span class="material-symbols-rounded text-xl">close</span>
        </a>
    </div>

    <form action="{{ route('admin.marketplace.portfolio.store') }}" method="POST" enctype="multipart/form-data" 
          x-data="{ 
            synching: false, 
            open: false, 
            search: '', 
            selectedId: '{{ old('marketplace_id') }}', 
            selectedName: '{{ old('marketplace_id') ? $talents->firstWhere('id', old('marketplace_id'))?->name : 'Select Creator...' }}',
            previews: [],
            handleFiles(event) {
                this.previews = [];
                Array.from(event.target.files).forEach((file) => {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.previews.push({ url: e.target.result, file: file });
                    };
                    reader.readAsDataURL(file);
                });
            },
            removeFile(index) {
                this.previews.splice(index, 1);
                const dt = new DataTransfer();
                this.previews.forEach(p => dt.items.add(p.file));
                this.$refs.fileInput.files = dt.files;
            },
            selectTalent(id, name) {
                this.selectedId = id;
                this.selectedName = name;
                this.open = false;
                this.search = '';
            }
          }" 
          @submit="synching = true"
          class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Panel: User & Title -->
            <div class="lg:col-span-4 space-y-6">
                <!-- User Selection Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                                <span class="material-symbols-rounded text-lg">person</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Creator</h3>
                        </div>

                        <div class="space-y-2" @click.away="open = false">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Marketplace Creator</label>
                            <input type="hidden" name="marketplace_id" :value="selectedId" required>
                            
                            <div class="relative">
                                <button type="button" 
                                        @click="open = !open"
                                        class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-5 flex items-center justify-between text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                    <span x-text="selectedName" class="truncate">Select Creator...</span>
                                    <span class="material-symbols-rounded text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                                </button>

                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="absolute z-[100] mt-2 w-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden"
                                     x-cloak>
                                    
                                    <div class="p-3 border-b border-slate-100 dark:border-white/5">
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400 text-sm">search</span>
                                            <input type="text" x-model="search" placeholder="Search creator..." 
                                                   class="w-full h-10 bg-slate-50 dark:bg-black/20 border-none rounded-xl pl-10 pr-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-1 focus:ring-indigo-500/50 transition-all">
                                        </div>
                                    </div>

                                    <div class="max-h-60 overflow-y-auto p-2 space-y-1">
                                        @foreach($talents as $talent)
                                            <button type="button"
                                                    x-show="search === '' || '{{ strtolower($talent->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($talent->type) }}'.includes(search.toLowerCase())"
                                                    @click="selectTalent('{{ $talent->id }}', '{{ $talent->name }}')"
                                                    class="w-full text-left p-3 rounded-xl hover:bg-indigo-600 group transition-all flex items-center justify-between"
                                                    :class="selectedId == '{{ $talent->id }}' ? 'bg-indigo-500/10' : ''">
                                                <div class="flex flex-col">
                                                    <span class="text-[11px] font-bold group-hover:text-white transition-colors"
                                                          :class="selectedId == '{{ $talent->id }}' ? 'text-indigo-500' : 'text-slate-900 dark:text-white'">{{ $talent->name }}</span>
                                                    <span class="text-[9px] font-medium text-slate-400 dark:text-white/30 group-hover:text-white/60 transition-colors mt-0.5">{{ $talent->type }}</span>
                                                </div>
                                                <span class="material-symbols-rounded text-indigo-500 group-hover:text-white transition-all text-sm" x-show="selectedId == '{{ $talent->id }}'">check</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Title & Description -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <span class="material-symbols-rounded text-lg">edit_note</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Details</h3>
                        </div>

                        <div class="space-y-4">
                            <x-input name="title" label="Portfolio Title" placeholder="e.g. Summer Campaign Shoot" required="true" icon="label" hint="A catchy title for this portfolio entry." />
                            <x-textarea name="description" label="Description" placeholder="Describe this portfolio entry..." rows="6" icon="description" hint="Optional description of the work." />
                        </div>

                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" name="status" value="1" class="sr-only" checked>
                                    <div class="block bg-slate-200 dark:bg-white/10 w-12 h-7 rounded-full transition-colors toggle-bg"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition-transform toggle-dot shadow-sm"></div>
                                </div>
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest">Active & Visible</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Image Upload -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                                    <span class="material-symbols-rounded text-lg">photo_library</span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Portfolio Images</h3>
                            </div>
                        </div>

                        <!-- Upload Drop Zone -->
                        <div class="relative group" x-show="previews.length === 0">
                            <input type="file" name="images[]" multiple accept="image/*" x-ref="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="handleFiles($event)" required>
                            <div class="w-full aspect-video bg-slate-50 dark:bg-white/[0.02] border-2 border-dashed border-slate-200 dark:border-white/10 rounded-2xl flex flex-col items-center justify-center gap-3 group-hover:border-indigo-500 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-500/5 transition-all">
                                <div class="w-16 h-16 rounded-2xl bg-white dark:bg-black/40 shadow-sm flex items-center justify-center text-indigo-500">
                                    <span class="material-symbols-rounded text-3xl">cloud_upload</span>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">Click or drag images to upload</p>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Supported: JPG, PNG, GIF</p>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Grid -->
                        <div x-show="previews.length > 0" x-cloak>
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-sm font-bold text-slate-500" x-text="previews.length + ' files selected'"></p>
                                <button type="button" @click="$refs.fileInput.click()" class="text-xs font-bold text-indigo-500 uppercase tracking-widest hover:text-indigo-600 transition-colors">Add More</button>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <template x-for="(preview, index) in previews" :key="index">
                                    <div class="relative group aspect-square rounded-2xl overflow-hidden border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5">
                                        <img :src="preview.url" class="w-full h-full object-cover">
                                        <button type="button" @click.prevent="removeFile(index)" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-red-600/90 backdrop-blur-sm text-white flex items-center justify-center hover:bg-red-700 hover:scale-110 transition-all shadow-lg opacity-0 group-hover:opacity-100">
                                            <span class="material-symbols-rounded text-sm">close</span>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="flex pt-4">
                            <button type="submit" :disabled="synching || !selectedId || previews.length === 0" 
                                    class="w-full h-16 rounded-2xl bg-indigo-600 text-white text-sm font-bold uppercase tracking-widest hover:bg-indigo-700 active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed shadow-lg shadow-indigo-500/20">
                                <span x-show="!synching" class="material-symbols-rounded">check_circle</span>
                                <span x-show="synching" class="material-symbols-rounded animate-spin">sync</span>
                                <span x-text="synching ? 'Saving...' : 'Create Portfolio'">Create Portfolio</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    input:checked ~ .toggle-bg { background-color: #6366f1; }
    input:checked ~ .dot { transform: translateX(100%); }
</style>
@endsection

