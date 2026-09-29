<div x-show="tab === 'portfolio'" class="space-y-6" x-cloak x-data="{ viewMode: 'list', currentId: null }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tighter" x-text="viewMode === 'list' ? 'My Portfolio' : (viewMode === 'create' ? 'Add New Portfolio' : 'Edit Portfolio')"></h3>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Manage your public portfolio entries</p>
        </div>
        <button x-show="viewMode === 'list'" @click="viewMode = 'create'" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-xl shadow-indigo-500/20 active:scale-95 flex items-center gap-2">
            <span class="material-symbols-rounded text-sm">add</span>
            Add Portfolio
        </button>
        <button x-show="viewMode !== 'list'" @click="viewMode = 'list'" class="bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 text-slate-600 dark:text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-rounded text-sm">arrow_back</span>
            Back to List
        </button>
    </div>

    <!-- Portfolio List -->
    <div x-show="viewMode === 'list'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-transition.opacity>
        @php
            $portfolios = \App\Models\MarketPortfolio::with('images')->where('marketplace_id', $client->id)->orderBy('sort_order', 'asc')->get();
        @endphp
        
        @forelse($portfolios as $portfolio)
            <div class="bg-white dark:bg-[#111] border border-slate-200 dark:border-white/5 rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group">
                <div class="h-48 bg-slate-100 dark:bg-white/5 relative overflow-hidden">
                    @if($portfolio->images->count() > 0)
                        <img src="{{ getImage($portfolio->images->first()->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-white/20">
                            <span class="material-symbols-rounded text-5xl">image</span>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4 bg-white/90 dark:bg-black/80 backdrop-blur-md px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white shadow-sm">
                        {{ $portfolio->images->count() }} Photos
                    </div>
                </div>
                <div class="p-6">
                    <h4 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2 line-clamp-1">{{ $portfolio->title }}</h4>
                    <div class="text-xs text-slate-500 dark:text-white/60 mb-6 line-clamp-2 prose prose-sm prose-slate dark:prose-invert">
                        {!! $portfolio->description !!}
                    </div>
                    
                    <div class="flex items-center justify-between border-t border-slate-100 dark:border-white/5 pt-4 mt-auto">
                        <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg {{ $portfolio->status ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }}">
                            {{ $portfolio->status ? 'Active' : 'Hidden' }}
                        </span>
                        
                        <div class="flex gap-2">
                            <button @click="viewMode = 'edit'; currentId = {{ $portfolio->id }}" class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors">
                                <span class="material-symbols-rounded text-sm">edit</span>
                            </button>
                            <form action="{{ route('marketplace.portfolio.delete', $portfolio->id) }}" method="POST" id="delete-portfolio-{{ $portfolio->id }}">
                                @csrf
                                <button type="button" @click="Swal.fire({
                                    title: 'Delete Portfolio?',
                                    text: 'All images and data will be permanently lost.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#3085d6',
                                    confirmButtonText: 'Yes, delete it!'
                                }).then((result) => {
                                    if (result.isConfirmed) document.getElementById('delete-portfolio-{{ $portfolio->id }}').submit();
                                })" class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                                    <span class="material-symbols-rounded text-sm">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white dark:bg-[#111] rounded-[2rem] border border-slate-100 dark:border-white/5 shadow-sm">
                <span class="material-symbols-rounded text-6xl text-slate-200 dark:text-white/10 mb-4 block">work_history</span>
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">No Portfolios Found</p>
                <p class="text-[10px] font-bold text-slate-400 mt-2">Click the "Add Portfolio" button to create your first entry.</p>
            </div>
        @endforelse
    </div>

    <!-- Create Form -->
    <div x-show="viewMode === 'create'" class="bg-white dark:bg-[#111] border border-slate-200 dark:border-white/5 rounded-[2.5rem] shadow-sm p-8" x-transition.opacity x-cloak>
        <form action="{{ route('marketplace.portfolio.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Portfolio Title</label>
                    <input type="text" name="title" required class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-5 py-4 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-slate-400" placeholder="e.g. Summer Campaign Shoot">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Description</label>
                    <textarea name="description" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-5 py-4 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all min-h-[150px]"></textarea>
                </div>

                <div x-data="{
                    previews: [],
                    handleFiles(event) {
                        this.previews = [];
                        Array.from(event.target.files).forEach((file, index) => {
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
                    }
                }">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Upload Images (Multiple)</label>
                    <div class="relative group" x-show="previews.length === 0">
                        <input type="file" name="images[]" multiple accept="image/*" x-ref="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="handleFiles($event)">
                        <div class="w-full bg-slate-50 dark:bg-white/5 border-2 border-dashed border-slate-200 dark:border-white/10 rounded-2xl p-8 flex flex-col items-center justify-center gap-3 group-hover:border-indigo-500 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-500/10 transition-colors">
                            <div class="w-16 h-16 rounded-full bg-white dark:bg-black shadow-sm flex items-center justify-center text-indigo-500">
                                <span class="material-symbols-rounded text-3xl">cloud_upload</span>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Click or drag images to upload</p>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Supported: JPG, PNG, GIF</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4" x-show="previews.length > 0" x-cloak>
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-xs font-bold text-slate-500" x-text="previews.length + ' files selected'"></p>
                            <button type="button" @click="$refs.fileInput.click()" class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Change Selection</button>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <template x-for="(preview, index) in previews" :key="index">
                                <div class="relative group aspect-square rounded-xl overflow-hidden border border-slate-200 dark:border-white/10">
                                    <img :src="preview.url" class="w-full h-full object-cover">
                                    <button type="button" @click.prevent="removeFile(index)" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-red-600/90 backdrop-blur-sm text-white flex items-center justify-center hover:bg-red-700 hover:scale-110 transition-all shadow-lg opacity-0 group-hover:opacity-100">
                                        <span class="material-symbols-rounded text-sm">close</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
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

            <div class="mt-8 pt-8 border-t border-slate-100 dark:border-white/5 flex justify-end gap-4">
                <button type="button" @click="viewMode = 'list'" class="px-8 py-3.5 rounded-xl text-[11px] font-black uppercase tracking-widest text-slate-500 bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 transition-colors">Cancel</button>
                <button type="submit" class="px-8 py-3.5 rounded-xl text-[11px] font-black uppercase tracking-widest text-white bg-indigo-500 hover:bg-indigo-600 shadow-xl shadow-indigo-500/20 transition-all active:scale-95">Save Portfolio</button>
            </div>
        </form>
    </div>

    <!-- Edit Forms -->
    @foreach($portfolios as $portfolio)
        <div x-show="viewMode === 'edit' && currentId === {{ $portfolio->id }}" class="bg-white dark:bg-[#111] border border-slate-200 dark:border-white/5 rounded-[2.5rem] shadow-sm p-8" x-transition.opacity x-cloak>
            <form action="{{ route('marketplace.portfolio.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="portfolio_id" value="{{ $portfolio->id }}">
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Portfolio Title</label>
                        <input type="text" name="title" value="{{ $portfolio->title }}" required class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-5 py-4 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder:text-slate-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Description</label>
                        <textarea name="description" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-5 py-4 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all min-h-[150px]">{{ strip_tags($portfolio->description) }}</textarea>
                    </div>

                    <!-- Existing Images Display -->
                    @if($portfolio->images->count() > 0)
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Current Images</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach($portfolio->images as $img)
                                <div class="relative group aspect-square rounded-xl overflow-hidden border border-slate-200 dark:border-white/10" x-data="{ removed: false }" x-show="!removed">
                                    <img src="{{ getImage($img->image) }}" class="w-full h-full object-cover">
                                    <!-- Delete Button (Always Visible) -->
                                    <div class="absolute top-2 right-2">
                                        <button type="button" @click.prevent="Swal.fire({
                                            title: 'Are you sure?',
                                            text: 'You will not be able to recover this image!',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#3085d6',
                                            confirmButtonText: 'Yes, delete it!'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                fetch('{{ route('marketplace.portfolio.image.delete', $img->id) }}', {
                                                    method: 'POST',
                                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                                                }).then(res => res.json()).then(data => {
                                                    if(data.success) {
                                                        removed = true;
                                                        Swal.fire('Deleted!', 'Your image has been deleted.', 'success');
                                                    }
                                                });
                                            }
                                        })" class="w-8 h-8 rounded-full bg-red-600 backdrop-blur-sm text-white flex items-center justify-center hover:bg-red-700 hover:scale-110 transition-all shadow-lg">
                                            <span class="material-symbols-rounded text-sm">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 ">Note: Click the trash icon on any image to permanently delete it.</p>
                    </div>
                    @endif

                    <div x-data="{
                        previews: [],
                        handleFiles(event) {
                            this.previews = [];
                            Array.from(event.target.files).forEach((file, index) => {
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
                            this.$refs.editFileInput.files = dt.files;
                        }
                    }">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Upload New Images (Appends to existing)</label>
                        <div class="relative group" x-show="previews.length === 0">
                            <input type="file" name="images[]" multiple accept="image/*" x-ref="editFileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="handleFiles($event)">
                            <div class="w-full bg-slate-50 dark:bg-white/5 border-2 border-dashed border-slate-200 dark:border-white/10 rounded-2xl p-8 flex flex-col items-center justify-center gap-3 group-hover:border-indigo-500 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-500/10 transition-colors">
                                <div class="w-16 h-16 rounded-full bg-white dark:bg-black shadow-sm flex items-center justify-center text-indigo-500">
                                    <span class="material-symbols-rounded text-3xl">add_photo_alternate</span>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">Add more images</p>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Optional</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4" x-show="previews.length > 0" x-cloak>
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-xs font-bold text-slate-500" x-text="previews.length + ' files selected'"></p>
                                <button type="button" @click="$refs.editFileInput.click()" class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Change Selection</button>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <template x-for="(preview, index) in previews" :key="index">
                                    <div class="relative group aspect-square rounded-xl overflow-hidden border border-slate-200 dark:border-white/10">
                                        <img :src="preview.url" class="w-full h-full object-cover">
                                        <button type="button" @click.prevent="removeFile(index)" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-red-600/90 backdrop-blur-sm text-white flex items-center justify-center hover:bg-red-700 hover:scale-110 transition-all shadow-lg opacity-0 group-hover:opacity-100">
                                            <span class="material-symbols-rounded text-sm">close</span>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="status" value="1" class="sr-only" {{ $portfolio->status ? 'checked' : '' }}>
                                <div class="block bg-slate-200 dark:bg-white/10 w-12 h-7 rounded-full transition-colors toggle-bg"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition-transform toggle-dot shadow-sm"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest">Active & Visible</span>
                        </label>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-100 dark:border-white/5 flex justify-end gap-4">
                    <button type="button" @click="viewMode = 'list'" class="px-8 py-3.5 rounded-xl text-[11px] font-black uppercase tracking-widest text-slate-500 bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 transition-colors">Cancel</button>
                    <button type="submit" class="px-8 py-3.5 rounded-xl text-[11px] font-black uppercase tracking-widest text-white bg-indigo-500 hover:bg-indigo-600 shadow-xl shadow-indigo-500/20 transition-all active:scale-95">Update Portfolio</button>
                </div>
            </form>
        </div>
    @endforeach
</div>

<style>
    input:checked ~ .toggle-bg { background-color: #6366f1; }
    input:checked ~ .dot { transform: translateX(100%); }
</style>

