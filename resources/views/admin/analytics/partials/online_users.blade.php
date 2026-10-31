@foreach($onlineUsers as $onlineUser)
    <div class="relative p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm hover:shadow-md transition-all group overflow-hidden">
        
        <!-- Top Section: Avatar, Info, Timestamp -->
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="relative shrink-0">
                    <a href="{{ route('admin.users.detail', $onlineUser->id) }}">
                        @if($onlineUser->image)
                            <img src="{{ getImage(getFilePath('userProfile').'/'.$onlineUser->image, getFileSize('userProfile')) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border-2 border-slate-100 dark:border-slate-700 hover:border-emerald-500 transition-colors">
                        @else
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 font-black text-sm border-2 border-slate-100 dark:border-slate-700 hover:border-emerald-500 transition-colors">
                                {{ getUserInitials($onlineUser) }}
                            </div>
                        @endif
                    </a>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white dark:border-slate-900 rounded-full"></span>
                </div>
                <div class="min-w-0">
                    <a href="{{ route('admin.users.detail', $onlineUser->id) }}" class="hover:text-emerald-500 transition-colors">
                        <h5 class="text-sm font-black text-slate-800 dark:text-white truncate">
                            {{ $onlineUser->fullname ?? $onlineUser->username }}
                        </h5>
                    </a>
                    @if($onlineUser->channel)
                        <a href="{{ route('admin.channels.show', $onlineUser->channel) }}" class="hover:text-emerald-500 transition-colors block">
                            <p class="text-[11px] font-bold text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                <span class="material-symbols-rounded text-[11px]">tv</span> {{ $onlineUser->channel->name }}
                            </p>
                        </a>
                    @else
                        <a href="{{ route('admin.users.detail', $onlineUser->id) }}" class="hover:text-emerald-500 transition-colors block">
                            <p class="text-[11px] font-bold text-slate-400 truncate mt-0.5">
                                {{ '@' . $onlineUser->username }}
                            </p>
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="shrink-0 mt-1">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $onlineUser->last_seen->diffForHumans(null, true, true) }}</span>
            </div>
        </div>

        <!-- Bottom Section: Action Button -->
        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <a href="{{ route('admin.users.detail', $onlineUser->id) }}" class="w-full flex justify-center items-center gap-1.5 py-2 rounded-lg bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-[11px] font-black uppercase tracking-wider shadow-sm shadow-orange-500/20 hover:shadow-md hover:shadow-orange-500/40 transition-all active:scale-[0.98]">
                <span class="material-symbols-rounded text-[14px]">visibility</span> @lang('Visit Profile')
            </a>
        </div>
        
    </div>
@endforeach
