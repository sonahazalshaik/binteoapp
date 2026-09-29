<!-- Tab: Contacts -->
<div x-show="tab === 'contacts'" x-cloak x-transition>
    <div class="bg-white dark:bg-[#111] p-6 md:p-12 rounded-[2rem] md:rounded-[3rem] border border-slate-200 dark:border-white/5 shadow-sm">
        <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white tracking-tighter mb-8 md:mb-10">Client Inquiries</h2>
        
        <div class="space-y-4">
            @forelse($client->contacts as $contact)
            <div class="p-6 md:p-8 bg-slate-50 dark:bg-white/5 rounded-2xl md:rounded-3xl border border-slate-100 dark:border-white/5 flex flex-col md:flex-row items-center md:justify-between gap-6">
                <div class="flex items-center gap-4 md:gap-6 w-full md:w-auto">
                    <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-red-600 flex items-center justify-center font-black text-white shadow-sm shrink-0">
                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-grow">
                        <h4 class="text-base md:text-lg font-bold truncate text-slate-900 dark:text-white">{{ $contact->name }}</h4>
                        <p class="text-[10px] md:text-xs font-medium text-slate-400 truncate">{{ $contact->email }} • {{ $contact->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto justify-end border-t md:border-none pt-4 md:pt-0 border-slate-100 dark:border-white/5">
                    <button @click="Swal.fire({
                        title: 'Message from {{ $contact->name }}',
                        text: '{{ addslashes($contact->message) }}',
                        confirmButtonText: 'Close',
                        customClass: { confirmButton: 'bg-indigo-600 rounded-xl px-8 py-3 text-white font-bold' }
                    })" class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all">
                        <span class="material-symbols-rounded">visibility</span>
                    </button>
                    <form action="{{ route('marketplace.contact.delete', $contact->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all">
                            <span class="material-symbols-rounded">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="py-20 text-center">
                <span class="material-symbols-rounded text-6xl text-slate-200 mb-4">mail</span>
                <p class="text-slate-400 font-bold">No inquiries received yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
