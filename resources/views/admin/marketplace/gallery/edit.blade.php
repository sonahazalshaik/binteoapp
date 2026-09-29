@extends('admin.layouts.app')

@section('title', 'Update Photos')
@section('header_title', 'Marketplace Gallery')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Update Photos</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Manage gallery photos for #{{ $gallery->marketplace_id }}</p>
        </div>
        <a href="{{ route('admin.marketplace.gallery.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90">
            <span class="material-symbols-rounded text-xl">close</span>
        </a>
    </div>

    <form action="{{ route('admin.marketplace.gallery.update', $gallery->marketplace_id) }}" method="POST" enctype="multipart/form-data" 
          x-data="{ 
            synching: false, 
            open: false, 
            search: '', 
            selectedId: '{{ $gallery->marketplace_id }}', 
            selectedName: '{{ @$gallery->marketplace->name ?? 'Select User...' }}',
            previews: [],
            deletedExisting: [],
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
          @submit.prevent="
              const currentCount = {{ $existingImages->count() }} - deletedExisting.length;
              if (currentCount === 0 && previews.length === 0) {
                  window.adminSwal({
                      title: 'Photos Required',
                      text: 'Please select one or more files to update the gallery.',
                      icon: 'warning',
                      confirmButtonText: 'Okay',
                      confirmButtonColor: '#3b82f6'
                  });
              } else {
                  synching = true;
                  $el.submit();
              }
          "
          class="space-y-8">
        @csrf
        
        <template x-for="deletedId in deletedExisting" :key="deletedId">
            <input type="hidden" name="deleted_images[]" :value="deletedId">
        </template>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Panel: User Information -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <span class="material-symbols-rounded text-lg">person</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">User Info</h3>
                        </div>

                        <div class="space-y-2" @click.away="open = false">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Assigned User</label>
                            <input type="hidden" name="marketplace_id" :value="selectedId" required>
                            
                            <div class="relative">
                                <button type="button" 
                                        @click="open = !open"
                                        class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-5 flex items-center justify-between text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                    <span x-text="selectedName" class="truncate">Select User...</span>
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
                                            <input type="text" x-model="search" placeholder="Search user..." 
                                                   class="w-full h-10 bg-slate-50 dark:bg-black/20 border-none rounded-xl pl-10 pr-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-1 focus:ring-blue-500/50 transition-all">
                                        </div>
                                    </div>

                                    <div class="max-h-60 overflow-y-auto p-2 space-y-1">
                                        @foreach($talents as $talent)
                                            <button type="button"
                                                    x-show="search === '' || '{{ strtolower($talent->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($talent->email) }}'.includes(search.toLowerCase())"
                                                    @click="selectTalent('{{ $talent->id }}', '{{ $talent->name }}')"
                                                    class="w-full text-left p-3 rounded-xl hover:bg-blue-600 group transition-all flex items-center justify-between"
                                                    :class="selectedId == '{{ $talent->id }}' ? 'bg-blue-500/10' : ''">
                                                <div class="flex flex-col">
                                                    <span class="text-[11px] font-bold group-hover:text-white transition-colors"
                                                          :class="selectedId == '{{ $talent->id }}' ? 'text-blue-500' : 'text-slate-900 dark:text-white'">{{ $talent->name }}</span>
                                                    <span class="text-[9px] font-medium text-slate-400 dark:text-white/30 group-hover:text-white/60 transition-colors mt-0.5">{{ $talent->email }}</span>
                                                </div>
                                                <span class="material-symbols-rounded text-blue-500 group-hover:text-white transition-all text-sm" x-show="selectedId == '{{ $talent->id }}'">check</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 rounded-3xl bg-blue-50 dark:bg-white/5 border border-blue-200 dark:border-white/10 flex items-start gap-4">
                    <span class="material-symbols-rounded text-blue-500 text-lg">history</span>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Existing Photos</p>
                        <p class="text-[11px] font-medium text-slate-500 dark:text-white/30 leading-relaxed">{{ $existingCount ?? 0 }} photo(s) on file. Upload more to append.</p>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Image Management -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                    <span class="material-symbols-rounded text-lg">photo_library</span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Photo Management</h3>
                            </div>
                        </div>

                        <!-- Existing Images -->
                        @if($existingImages->count() > 0)
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Current Photos</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($existingImages as $img)
                                <div class="relative group aspect-square rounded-2xl overflow-hidden border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5" x-show="!deletedExisting.includes({{ $img->id }})">
                                    <img src="{{ $img->photoUrl() }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center">
                                        <button type="button" @click="deletedExisting.push({{ $img->id }})"
                                           class="w-10 h-10 rounded-full bg-red-600/90 backdrop-blur-sm text-white flex items-center justify-center hover:bg-red-700 hover:scale-110 transition-all shadow-lg opacity-0 group-hover:opacity-100">
                                            <span class="material-symbols-rounded text-lg">delete</span>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Upload New Images -->
                        <div class="pt-4 border-t border-slate-100 dark:border-white/5">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Add New Photos (Appends)</label>
                            <div class="relative group" x-show="previews.length === 0">
                                <input type="file" name="images[]" multiple accept="image/*" x-ref="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="handleFiles($event)">
                                <div class="w-full bg-slate-50 dark:bg-white/[0.02] border-2 border-dashed border-slate-200 dark:border-white/10 rounded-2xl p-8 flex flex-col items-center justify-center gap-3 group-hover:border-blue-500 group-hover:bg-blue-50 dark:group-hover:bg-blue-500/5 transition-all">
                                    <div class="w-16 h-16 rounded-2xl bg-white dark:bg-black/40 shadow-sm flex items-center justify-center text-blue-500">
                                        <span class="material-symbols-rounded text-3xl">add_photo_alternate</span>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">Add more photos</p>
                                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Optional</p>
                                    </div>
                                </div>
                            </div>
                            <div x-show="previews.length > 0" x-cloak class="mt-4">
                                <div class="flex justify-between items-center mb-3">
                                    <p class="text-sm font-bold text-slate-500" x-text="previews.length + ' new files'"></p>
                                    <button type="button" @click="$refs.fileInput.click()" class="text-xs font-bold text-blue-500 uppercase tracking-widest">Change Selection</button>
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
                        </div>

                        <div class="flex pt-4">
                            <button type="submit" :disabled="synching || !selectedId" 
                                    class="w-full h-16 rounded-2xl bg-blue-600 text-white text-sm font-bold uppercase tracking-widest hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed shadow-lg shadow-blue-500/20">
                                <span x-show="!synching" class="material-symbols-rounded">sync</span>
                                <span x-show="synching" class="material-symbols-rounded animate-spin">sync</span>
                                <span x-text="synching ? 'Updating...' : 'Update Photos'">Update Photos</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection

