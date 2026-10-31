<!-- Edit Service Modal -->
<template x-teleport="body">
    <div x-show="editService.open" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editService.open = false"></div>
        <div class="relative w-full max-w-xl bg-white dark:bg-[#151515] rounded-[3rem] shadow-2xl border border-slate-100 dark:border-white/5 overflow-hidden" x-transition>
            <div class="p-8 md:p-12">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Edit Service</h2>
                    <button @click="editService.open = false" class="w-10 h-10 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>

                <form :action="`/marketplace/service/update/${editService.id}`" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="showServiceLoader('Updating your service parameters...')">
                    @csrf
                    <div class="flex items-center gap-6 mb-8 p-4 bg-slate-50 dark:bg-white/5 rounded-3xl">
                        <div class="w-20 h-20 rounded-2xl overflow-hidden bg-white dark:bg-black shrink-0 shadow-sm border border-slate-100 dark:border-white/10">
                            <img :src="editService.img" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Change Icon</label>
                            <input type="file" name="service_img" class="w-full text-xs font-bold" @change="editService.img = URL.createObjectURL($event.target.files[0])">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2 block">Service Title</label>
                        <input type="text" name="service_name" x-model="editService.name" class="w-full px-6 py-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold" required>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2 block">Service Details</label>
                        <textarea name="service_brief" x-model="editService.brief" rows="3" class="w-full px-8 py-6 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-[2rem] text-sm font-bold"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-3xl font-bold text-sm tracking-tight shadow-xl shadow-indigo-500/20 active:scale-95 transition-all">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
