<x-app-layout>
    <div class="py-6 md:py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6">
            <!-- Ticket Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-8 mb-6 md:mb-12">
                <div>
                    <a href="{{ route('user.ticket.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 md:mb-6 hover:text-red-600 transition-colors">
                        <span class="material-symbols-rounded text-lg">arrow_back</span>
                        Back to Ledger
                    </a>
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <h1 class="text-xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Ticket #{{ $myTicket->ticket }}</h1>
                        @if($myTicket->status == 1)
                            <span class="bg-blue-500/10 text-blue-500 px-2.5 py-0.5 md:px-3 md:py-1 rounded-lg text-[8px] md:text-[9px] font-black uppercase tracking-widest border border-blue-500/10">Open</span>
                        @elseif($myTicket->status == 2)
                            <span class="bg-emerald-500/10 text-emerald-500 px-2.5 py-0.5 md:px-3 md:py-1 rounded-lg text-[8px] md:text-[9px] font-black uppercase tracking-widest border border-emerald-500/10">Answered</span>
                        @elseif($myTicket->status == 3)
                            <span class="bg-amber-500/10 text-amber-600 px-2.5 py-0.5 md:px-3 md:py-1 rounded-lg text-[8px] md:text-[9px] font-black uppercase tracking-widest border border-amber-500/10">Replied</span>
                        @else
                            <span class="bg-gray-100 dark:bg-white/5 text-gray-400 px-2.5 py-0.5 md:px-3 md:py-1 rounded-lg text-[8px] md:text-[9px] font-black uppercase tracking-widest border border-gray-200 dark:border-white/10">Closed</span>
                        @endif
                    </div>
                    <h2 class="text-sm md:text-xl font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest leading-snug">{{ $myTicket->subject }}</h2>
                </div>

                <div class="flex items-center gap-4">
                    <form action="{{ route('user.ticket.close', $myTicket->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 md:px-6 py-2.5 md:py-3 border border-red-500 text-red-600 rounded-xl font-black text-[9px] md:text-[10px] uppercase tracking-widest hover:bg-red-50 dark:hover:bg-red-500/10 transition-all active:scale-95">Close Ticket</button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 md:gap-12">
                <!-- Thread -->
                <div class="lg:col-span-3 space-y-6 md:space-y-12 pb-10 md:pb-20">
                    <!-- Reply Box -->
                    @if($myTicket->status != \App\Constants\Status::TICKET_CLOSE)
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-2xl md:rounded-[2.5rem] p-5 md:p-8 border border-gray-100 dark:border-white/5 shadow-sm">
                        <form action="{{ route('user.ticket.reply', $myTicket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <textarea name="message" rows="4" placeholder="Share more details or reply to our analyst..." class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-xl md:rounded-[1.5rem] px-4 md:px-8 py-4 md:py-6 text-xs md:text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none resize-none mb-4 md:mb-6" required></textarea>
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 md:gap-6">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 md:gap-4 w-full sm:w-auto">
                                    <div class="relative group cursor-pointer">
                                        <input type="file" name="attachments[]" id="ticketAttachments" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" multiple accept="image/*" onchange="validateTicketFiles(this)">
                                        <div class="px-4 md:px-6 py-2.5 md:py-3 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/10 text-[10px] md:text-xs font-black text-gray-400 uppercase tracking-widest flex items-center gap-2 group-hover:text-red-500 transition-colors">
                                            <span class="material-symbols-rounded text-base md:text-lg">attach_file</span>
                                            <span>Attachments</span>
                                        </div>
                                    </div>
                                    <p class="text-[8px] md:text-[9px] font-bold text-gray-400 uppercase tracking-widest ">Images only | Max 4</p>
                                </div>
                                <div id="ticketPreview" class="flex flex-wrap gap-3 mt-4 w-full"></div>
                                <button type="submit" class="w-full sm:w-auto px-6 md:px-10 py-3 md:py-4 bg-gray-900 dark:bg-white text-white dark:text-black rounded-xl md:rounded-2xl font-black text-[9px] md:text-[10px] uppercase tracking-widest shadow-xl hover:opacity-90 transition-all active:scale-95">Post Response</button>
                            </div>
                        </form>
                    </div>
                    @else
                    <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl md:rounded-[2.5rem] p-5 md:p-8 text-center">
                        <span class="material-symbols-rounded text-3xl md:text-4xl text-amber-500 mb-3 md:mb-4">lock</span>
                        <h3 class="text-base md:text-lg font-black text-amber-600 uppercase tracking-widest mb-1">Ticket Closed</h3>
                        <p class="text-[10px] md:text-xs font-bold text-amber-600/70 uppercase tracking-widest">This conversation has been concluded. If you need further assistance, please open a new ticket.</p>
                    </div>
                    @endif

                    <!-- Message Feed -->
                    <div class="space-y-6 md:space-y-10 relative">
                        <div class="hidden md:block absolute left-8 top-0 bottom-0 w-px bg-gray-100 dark:bg-white/5"></div>

                        @foreach($messages as $message)
                        <div class="relative md:pl-20">
                            <!-- Timeline Dot -->
                            <div class="hidden md:block absolute left-7 top-0 w-3 h-3 rounded-full {{ $message->admin_id ? 'bg-red-500' : 'bg-blue-500' }} border-4 border-[#FAFAFA] dark:border-[#0F0F0F] z-10"></div>

                            <div class="bg-white dark:bg-[#1A1A1A] rounded-2xl md:rounded-[2rem] p-4 md:p-8 border border-gray-100 dark:border-white/5 shadow-sm">
                                <div class="flex items-center justify-between mb-4 md:mb-6">
                                    <div class="flex items-center gap-3 md:gap-4">
                                        @if($message->admin_id)
                                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-red-500 flex items-center justify-center text-white shrink-0">
                                                <span class="material-symbols-rounded text-xl md:text-2xl">support_agent</span>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-blue-500 overflow-hidden flex items-center justify-center text-white shrink-0">
                                                @if(auth()->user()->channel && auth()->user()->channel->avatar)
                                                    <img src="{{ getImage(auth()->user()->channel->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
                                                @else
                                                    <span class="material-symbols-rounded text-xl md:text-2xl">person</span>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <h4 class="font-black text-gray-900 dark:text-white text-xs md:text-sm truncate">{{ $message->admin_id ? 'Support Analyst' : auth()->user()->fullname }}</h4>
                                            <p class="text-[8px] md:text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ diffForHumans($message->created_at) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-[11px] md:text-sm font-bold text-gray-600 dark:text-gray-400 leading-relaxed uppercase tracking-widest break-words">
                                    {!! nl2br(e($message->message)) !!}
                                </div>

                                @if($message->attachments->count() > 0)
                                <div class="mt-4 md:mt-8 grid grid-cols-2 sm:grid-cols-3 gap-2 md:gap-4 pt-4 md:pt-6 border-t border-gray-50 dark:border-white/5">
                                    @foreach($message->attachments as $attachment)
                                    <div class="group/attachment relative">
                                        @if($attachment->is_image)
                                            <div class="aspect-video rounded-xl md:rounded-2xl overflow-hidden bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 mb-1.5 md:mb-2">
                                                <img src="{{ getImage($attachment->attachment) }}" class="w-full h-full object-cover group-hover/attachment:scale-110 transition-all duration-500 cursor-zoom-in" onclick="window.open(this.src, '_blank')">
                                            </div>
                                        @endif
                                        <a href="{{ $attachment->download_url }}" class="flex items-center gap-2 md:gap-3 px-3 md:px-4 py-1.5 md:py-2 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/10 hover:border-red-500 transition-all group">
                                            <span class="material-symbols-rounded text-base md:text-lg text-gray-400 group-hover:text-red-500 transition-colors">description</span>
                                            <span class="text-[8px] md:text-[9px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest truncate">File #{{ $loop->iteration }}</span>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="space-y-4 md:space-y-8">
                    <h3 class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Ticket Analytics</h3>
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-2xl md:rounded-[2.5rem] p-5 md:p-8 border border-gray-100 dark:border-white/5 shadow-sm">
                        <div class="space-y-5 md:space-y-8">
                            <div>
                                <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Priority Level</p>
                                <p class="text-[11px] md:text-xs font-black {{ $myTicket->priority == 3 ? 'text-red-500' : ($myTicket->priority == 2 ? 'text-amber-500' : 'text-blue-500') }} uppercase tracking-widest">
                                    {{ $myTicket->priority == 3 ? 'Critical' : ($myTicket->priority == 2 ? 'Medium' : 'Standard') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Created</p>
                                <p class="text-[11px] md:text-xs font-black text-gray-900 dark:text-white uppercase tracking-widest">{{ $myTicket->created_at->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Last Update</p>
                                <p class="text-[11px] md:text-xs font-black text-gray-900 dark:text-white uppercase tracking-widest">{{ diffForHumans($myTicket->last_reply) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
function validateTicketFiles(input) {
    const allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    const files = Array.from(input.files || []);
    const invalid = files.filter(f => !allowed.includes(f.type));
    if (invalid.length > 0) {
        Swal.fire({ icon: 'error', title: 'Invalid File', text: 'Only images (jpg, png, webp, gif) are allowed.', confirmButtonColor: '#ef4444' });
        input.value = '';
        document.getElementById('ticketPreview').innerHTML = '';
        return;
    }
    if (files.length > 4) {
        Swal.fire({ icon: 'error', title: 'Too Many Files', text: 'Maximum 4 files can be uploaded.', confirmButtonColor: '#ef4444' });
        input.value = '';
        document.getElementById('ticketPreview').innerHTML = '';
        return;
    }
    const container = document.getElementById('ticketPreview');
    container.innerHTML = '';
    files.forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative w-20 h-20 rounded-xl overflow-hidden border border-gray-100 dark:border-white/10 bg-gray-50 dark:bg-white/5 flex-shrink-0';
            div.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">' +
                '<button type="button" onclick="removeTicketPreview(' + i + ')" class="absolute top-0.5 right-0.5 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center hover:bg-red-600">&times;</button>';
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removeTicketPreview(index) {
    const input = document.getElementById('ticketAttachments');
    const dt = new DataTransfer();
    const files = Array.from(input.files || []);
    files.forEach((f, i) => { if (i !== index) dt.items.add(f); });
    input.files = dt.files;
    validateTicketFiles(input);
}
</script>
</x-app-layout>

