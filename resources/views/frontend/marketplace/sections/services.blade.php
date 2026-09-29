<!-- Tab: Services -->
<div x-show="tab === 'services'" x-cloak x-transition class="space-y-8">
    <div class="bg-white dark:bg-[#111] p-3 md:p-5 rounded-2xl border border-slate-200 dark:border-white/5 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg md:text-xl font-black text-slate-900 dark:text-white tracking-tighter">Service Offerings</h2>
            <button @click="document.getElementById('new-service-form').scrollIntoView({behavior: 'smooth'})" class="px-4 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-bold text-[9px] uppercase tracking-widest">Add New</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            @foreach($client->services as $service)
            <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5 flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-white dark:bg-black shadow-sm shrink-0">
                        <img src="{{ getImage($service->service_img) }}" class="w-full h-full object-cover">

                    </div>
                    <div class="min-w-0">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $service->service_name }}</h4>
                        <p class="text-[11px] font-medium text-slate-500 line-clamp-1">{{ $service->service_brief }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 md:opacity-0 md:group-hover:opacity-100 transition-all shrink-0">
                    <button @click="editService.open = true; editService.id = {{ $service->id }}; editService.name = '{{ addslashes($service->service_name) }}'; editService.brief = '{{ addslashes($service->service_brief) }}'; editService.img = '{{ getImage($service->service_img) }}'" 
                            class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all">
                        <span class="material-symbols-rounded text-lg">edit</span>
                    </button>
                    <form action="{{ route('marketplace.service.delete', $service->id) }}" method="POST" onsubmit="showServiceLoader('Permanently removing this service offering...')">
                        @csrf
                        <button type="submit" class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all">
                            <span class="material-symbols-rounded text-lg">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div id="new-service-form" class="pt-6 border-t border-slate-100 dark:border-white/5">
            <h3 class="text-base font-bold mb-5 text-slate-900 dark:text-white">Create New Offering</h3>
            <form action="{{ route('marketplace.service.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="showServiceLoader('Architecting your new service offering...')">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-3 mb-1.5 block">Service Icon</label>
                        <input type="file" name="service_img" class="w-full px-4 py-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-[10px] font-bold" required>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-3 mb-1.5 block">Service Title</label>
                        <input type="text" name="service_name" class="w-full px-4 py-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-bold" required>
                    </div>
                </div>
                <textarea name="service_brief" rows="2" class="w-full px-5 py-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-bold" placeholder="Service description..."></textarea>
                <div class="flex items-center justify-end gap-3 mt-4">
                    <button type="button" @click="document.getElementById('new-service-form').scrollIntoView({behavior: 'smooth'}); document.querySelector('#new-service-form input, #new-service-form textarea').value = ''" class="px-6 py-2.5 bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" class="px-8 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-sm hover:bg-slate-800 transition-all">Create Offering</button>
                </div>
            </form>
        </div>
    </div>
</div>
