@extends('admin.layouts.app')

@section('title', 'Update Service')
@section('header_title', 'Marketplace Services')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Update Service</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Edit service details for #{{ $service->id }}</p>
        </div>
        <a href="{{ route('admin.marketplace.services.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90">
            <span class="material-symbols-rounded text-xl">close</span>
        </a>
    </div>

    <form action="{{ route('admin.marketplace.services.update', $service->id) }}" method="POST" enctype="multipart/form-data" 
          x-data="{ 
            synching: false, 
            open: false, 
            search: '', 
            selectedId: '{{ $service->marketplace_id }}', 
            selectedName: '{{ @$service->marketplace->name ?? 'Select User...' }}',
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
            <!-- Left Panel: User & Image -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <span class="material-symbols-rounded text-lg">person</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">User Information</h3>
                        </div>

                        <div class="space-y-2" @click.away="open = false">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Assigned Marketplace User</label>
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

                <!-- Current Service Image -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm group">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">Current Service Photo</h3>
                            <span class="material-symbols-rounded text-orange-500">image</span>
                        </div>

                        <div class="relative aspect-square rounded-2xl border-2 border-dashed border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] overflow-hidden flex items-center justify-center">
                            <img src="{{ $service->photoUrl() }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Service Details + New Images -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <span class="material-symbols-rounded text-lg">edit_note</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Service Details</h3>
                        </div>

                        <div class="space-y-6">
                            <x-input name="service_name" label="Service Name" value="{{ old('service_name', $service->service_name) }}" required="true" icon="label" hint="Update the name of the service." />
                            
                            <x-textarea name="service_brief" label="Description" placeholder="Update the detailed description of what this service includes..." rows="10" required="true" icon="description" hint="Update the service details for the user.">{{ old('service_brief', $service->service_brief) }}</x-textarea>
                        </div>

                        <!-- Upload Additional Service Images -->
                        <div class="pt-4 border-t border-slate-100 dark:border-white/5">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Create Additional Services</label>
                            <p class="text-[10px] font-medium text-slate-500 dark:text-white/30 mb-4 ">Upload new images to create additional services with the same name/description.</p>
                            <div class="relative group" x-show="previews.length === 0">
                                <input type="file" name="images[]" multiple accept="image/*" x-ref="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="handleFiles($event)">
                                <div class="w-full bg-slate-50 dark:bg-white/[0.02] border-2 border-dashed border-slate-200 dark:border-white/10 rounded-2xl p-8 flex flex-col items-center justify-center gap-3 group-hover:border-blue-500 group-hover:bg-blue-50 dark:group-hover:bg-blue-500/5 transition-all">
                                    <div class="w-16 h-16 rounded-2xl bg-white dark:bg-black/40 shadow-sm flex items-center justify-center text-blue-500">
                                        <span class="material-symbols-rounded text-3xl">add_a_photo</span>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">Add more services</p>
                                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Optional</p>
                                    </div>
                                </div>
                            </div>
                            <div x-show="previews.length > 0" x-cloak class="mt-4">
                                <div class="flex justify-between items-center mb-3">
                                    <p class="text-sm font-bold text-slate-500" x-text="previews.length + ' new service(s)'"></p>
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

                        <div class="flex pt-6 border-t border-slate-100 dark:border-white/5">
                            <button type="submit" :disabled="synching || !selectedId" 
                                    class="w-full h-16 rounded-2xl bg-blue-600 text-white text-sm font-bold uppercase tracking-widest hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed shadow-lg shadow-blue-500/20">
                                <span x-show="!synching" class="material-symbols-rounded">sync</span>
                                <span x-show="synching" class="material-symbols-rounded animate-spin">sync</span>
                                <span x-text="synching ? 'Updating...' : 'Update Service'">Update Service</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

