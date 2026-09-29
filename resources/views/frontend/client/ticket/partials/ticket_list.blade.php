<div class="grid grid-cols-1 gap-4">
    @forelse($supports as $support)
    <div class="group bg-white dark:bg-[#121212] rounded-3xl border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
        <div class="p-4 sm:p-5">
            <div class="flex items-start gap-4 sm:gap-5">
                <!-- Icon -->
                <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-500/20 text-blue-500 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-rounded text-xl">confirmation_number</span>
                </div>
                
                <div class="flex-grow min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] font-black text-gray-400 dark:text-white/40 uppercase tracking-widest">#{{ $support->ticket }}</span>
                        <span class="text-[10px] font-bold text-gray-400 dark:text-white/40">{{ $support->created_at->diffForHumans(null, true) }}</span>
                    </div>
                    <h3 class="text-sm sm:text-base font-black text-gray-900 dark:text-white line-clamp-1 mb-2">
                        @if($support->priority == 3) ⚡ @endif {{ $support->subject }}
                    </h3>
                    
                    <div class="flex flex-wrap items-center gap-2.5 sm:gap-4">
                        <!-- Status -->
                        <div class="flex items-center gap-1.5 shrink-0">
                            @if($support->status == 0)
                                <div class="flex items-center gap-1 text-emerald-500 dark:text-emerald-400">
                                    <span class="material-symbols-rounded text-sm shrink-0">progress_activity</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest whitespace-nowrap">In Progress</span>
                                </div>
                            @elseif($support->status == 1)
                                <div class="flex items-center gap-1 text-blue-500 dark:text-blue-400">
                                    <span class="material-symbols-rounded text-sm shrink-0">check_circle</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest whitespace-nowrap">Answered</span>
                                </div>
                            @elseif($support->status == 4)
                                <div class="flex items-center gap-1 text-rose-500 dark:text-rose-400">
                                    <span class="material-symbols-rounded text-sm shrink-0">cancel</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest whitespace-nowrap">Rejected</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1 text-gray-400 dark:text-white/60">
                                    <span class="material-symbols-rounded text-sm shrink-0">task_alt</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest whitespace-nowrap">Completed</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="w-px h-3 bg-gray-200 dark:bg-white/10 shrink-0"></div>
                        
                        <!-- Priority -->
                        <div class="flex items-center gap-1 shrink-0">
                            <span class="text-[10px] font-black text-gray-400 dark:text-white/60 uppercase tracking-widest whitespace-nowrap">
                                {{ $support->priority == 1 ? 'Low' : ($support->priority == 2 ? 'Medium' : 'High') }} Priority
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Footer -->
        <div class="px-4 sm:px-5 py-3 bg-gray-50/50 dark:bg-white/[0.02] border-t border-gray-100 dark:border-white/5 flex items-center justify-between gap-3 transition-colors group-hover:bg-rose-500/5">
            <button type="button" class="flex-grow lg:flex-none lg:w-fit px-4 py-2 flex items-center justify-between lg:justify-start gap-4 openHub" data-id="{{ $support->id }}" data-ticket="{{ $support->ticket }}">
                <span class="text-[10px] font-black text-rose-500 dark:text-rose-400 uppercase tracking-widest">Open Hub</span>
                <span class="material-symbols-rounded text-base text-rose-500 dark:text-rose-400 transition-transform group-hover:translate-x-1">chevron_right</span>
            </button>
            <a href="{{ route('user.ticket.view', $support->id) }}" class="w-10 h-10 rounded-xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 flex items-center justify-center text-gray-400 dark:text-white/70 hover:text-rose-500 dark:hover:text-rose-400 hover:border-rose-500/30 transition-all shadow-sm">
                <span class="material-symbols-rounded text-lg">open_in_new</span>
            </a>
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-[#181818] rounded-3xl p-12 sm:p-16 border border-gray-100 dark:border-white/5 text-center transition-colors duration-500">
        <div class="w-24 h-24 rounded-2xl bg-gray-50 dark:bg-white/2 flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-rounded text-5xl text-gray-200 dark:text-white/5">mail</span>
        </div>
        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">No Active Tickets</h3>
        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-8">Need help? Open a new ticket anytime.</p>
        <a href="{{ route('user.ticket.create') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-red-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-red-500/20 hover:bg-red-700 transition-all">
            <span class="material-symbols-rounded text-lg">chat_bubble</span>
            Start Conversation
        </a>
    </div>
    @endforelse
</div>

@if($supports->hasPages())
    <div class="mt-16 pagination-container">
        {{ $supports->links() }}
    </div>
@endif
