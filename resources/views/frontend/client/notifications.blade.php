<x-app-layout>
    <div class="py-6 bg-white dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[800px] mx-auto px-4 sm:px-6">
            
            <!-- Dynamic Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter uppercase">Activity</h1>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                        <span class="w-8 h-[2px] bg-[#ff571a]"></span>
                        Notifications & Alerts
                    </p>
                </div>
                <div class="relative">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-white/5 flex items-center justify-center border border-slate-100 dark:border-white/10 text-slate-400 group hover:border-[#ff571a]/50 transition-all cursor-pointer shadow-sm">
                        <span class="material-symbols-rounded text-2xl group-hover:scale-110 transition-transform">notifications_active</span>
                        @if(isset($notifications) && $notifications->where('is_read', 0)->count() > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-[#ff571a] rounded-full border-4 border-white dark:border-[#0F0F0F]"></span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Enhanced Notification Stack -->
            <div class="space-y-4">
                @forelse($notifications ?? [] as $notification)
                <div class="relative group">
                    <div class="bg-white dark:bg-[#181818] p-4 sm:p-5 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm hover:shadow-md hover:shadow-[#ff571a]/5 transition-all duration-300 relative overflow-hidden">
                        
                        <!-- Read Status Indicator -->
                        @if(!$notification->is_read)
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-[#ff571a] to-[#ff8c1a] shadow-[2px_0_10px_rgba(255,87,26,0.3)]"></div>
                        @endif
                        
                        <div class="flex items-start gap-4">
                            <!-- Profile Image or Category Icon -->
                            <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-white/2 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform border border-slate-100 dark:border-white/5 overflow-hidden">
                                @if($notification->sender)
                                    @php
                                        $avatar = $notification->sender->channel?->avatar 
                                            ? getImage(getFilePath('channelAvatar') . '/' . $notification->sender->channel->avatar) 
                                            : ($notification->sender->image ? getImage(getFilePath('userProfile') . '/' . $notification->sender->image) : null);
                                    @endphp
                                    @if($avatar)
                                        <img src="{{ $avatar }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="hidden w-full h-full bg-gradient-to-br from-[#ff571a] to-[#ff8c1a] items-center justify-center text-white font-black text-sm uppercase">
                                            {{ substr($notification->sender->firstname ?? $notification->sender->username ?? 'U', 0, 1) }}{{ substr($notification->sender->lastname ?? '', 0, 1) }}
                                        </div>
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-[#ff571a] to-[#ff8c1a] flex items-center justify-center text-white font-black text-sm uppercase">
                                            {{ substr($notification->sender->firstname ?? $notification->sender->username ?? 'U', 0, 1) }}{{ substr($notification->sender->lastname ?? '', 0, 1) }}
                                        </div>
                                    @endif
                                @else
                                    @php
                                        $title = strtolower($notification->title);
                                        $icon = 'notifications';
                                        $color = 'text-slate-400';
                                        if(str_contains($title, 'deposit')) { $icon = 'account_balance_wallet'; $color = 'text-emerald-500'; }
                                        elseif(str_contains($title, 'withdraw')) { $icon = 'payments'; $color = 'text-amber-500'; }
                                        elseif(str_contains($title, 'subscription')) { $icon = 'workspace_premium'; $color = 'text-[#ff571a]'; }
                                        elseif(str_contains($title, 'security')) { $icon = 'shield_person'; $color = 'text-rose-500'; }
                                    @endphp
                                    <span class="material-symbols-rounded text-2xl {{ $color }}">{{ $icon }}</span>
                                @endif
                            </div>
                            
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between mb-1.5">
                                    <h3 class="font-black text-slate-900 dark:text-white text-[11px] md:text-sm tracking-tight uppercase line-clamp-2 md:line-clamp-3">{{ __($notification->title) }}</h3>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex-shrink-0 ml-4 opacity-60">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[12px] font-medium text-slate-500 dark:text-slate-400 leading-relaxed mb-3 line-clamp-2">
                                    {{ __($notification->message ?? $notification->details ?? '') }}
                                </p>
                                
                                <div class="flex items-center gap-4">
                                    <!-- Dynamic Profile/Channel Link -->
                                    @if($notification->sender)
                                    <a href="{{ $notification->sender->getProfileUrl() }}" class="text-[10px] font-black text-[#ff571a] dark:text-[#ff571a] uppercase tracking-widest hover:underline flex items-center gap-1.5 group/link">
                                        View Profile <span class="material-symbols-rounded text-sm group-hover/link:translate-x-1 transition-transform">person</span>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white dark:bg-[#181818] rounded-3xl p-12 sm:p-16 border border-slate-100 dark:border-white/5 text-center transition-all duration-500 shadow-sm">
                    <div class="w-24 h-24 rounded-2xl bg-slate-50 dark:bg-white/2 flex items-center justify-center mx-auto mb-6 border border-slate-100 dark:border-white/5">
                        <span class="material-symbols-rounded text-5xl text-slate-200 dark:text-white/5">notifications_none</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2 tracking-tighter uppercase">Quiet for now</h3>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest max-w-xs mx-auto">We'll alert you here when new activity arrives.</p>
                </div>
                @endforelse
            </div>

            <!-- Styled Pagination -->
            @if(isset($notifications) && $notifications->hasPages())
            <div class="mt-16 flex justify-center">
                <div class="p-2 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/10">
                    {{ $notifications->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <style>
        /* Custom Pagination Styling */
        .pagination { display: flex; gap: 0.5rem; list-style: none; padding: 0; margin: 0; }
        .page-item { display: flex; }
        .page-link { width: 2.5rem; height: 2.5rem; display: flex; items-center; justify-content: center; border-radius: 0.75rem; font-weight: 900; font-size: 0.75rem; transition: all 0.3s; border: 1px solid transparent; text-decoration: none; }
        .page-item.active .page-link { background: #ff571a; color: white; box-shadow: 0 10px 15px -3px rgba(255, 87, 26, 0.2); }
        .page-item:not(.active) .page-link { background: rgba(255,255,255,0.05); color: #94a3b8; }
        .page-item:not(.active) .page-link:hover { border-color: rgba(255, 87, 26, 0.3); color: #ff571a; }
        
        .dark .page-item:not(.active) .page-link { background: rgba(255,255,255,0.05); }
    </style>
</x-app-layout>
