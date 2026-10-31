<x-app-layout>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Purchased Playlists</h1>
                    <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Collections you have unlocked</p>
                </div>
                <div class="flex items-center gap-4">
                     <form action="" method="GET" class="relative group">
                        <input type="text" name="search" value="{{ request()->search }}" placeholder="Search playlists..." class="pl-12 pr-6 py-3.5 bg-white dark:bg-[#1A1A1A] border border-gray-100 dark:border-white/5 rounded-2xl text-xs font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none w-64 transition-all">
                        <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-red-500 transition-colors">search</span>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="hidden md:block">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 dark:bg-white/2 border-b border-gray-100 dark:border-white/5">
                            <tr>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">TRX ID</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Playlist</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Purchased</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Amount</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($purchasedPlaylists as $purchased)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-white/2 transition-colors">
                                <td class="px-8 py-6">
                                    <span class="font-black text-sm text-gray-900 dark:text-white">{{ $purchased->trx }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center overflow-hidden">
                                            @if($purchased->playlist->image)
                                                <img src="{{ getImage(getFilePath('thumbnail') . '/' . $purchased->playlist->image) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="material-symbols-rounded text-gray-400">playlist_play</span>
                                            @endif
                                        </div>
                                        <span class="font-black text-sm text-gray-900 dark:text-white">{{ __($purchased->playlist->title) }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ showDateTime($purchased->created_at, 'd M Y') }}</div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">{{ diffForHumans($purchased->created_at) }}</div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="font-black text-sm text-gray-900 dark:text-white">{{ showAmount($purchased->amount) }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center">
                                        <a href="{{ route('preview.playlist.videos', [$purchased->playlist->slug, $purchased->playlist->user->slug]) }}" target="_blank" class="w-10 h-10 rounded-xl bg-red-500/10 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-lg shadow-red-500/5">
                                            <span class="material-symbols-rounded">play_arrow</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <span class="material-symbols-rounded text-6xl text-gray-200 dark:text-white/5 mb-6">playlist_add_check</span>
                                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No purchased playlists found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-gray-50 dark:divide-white/5">
                    @forelse($purchasedPlaylists as $purchased)
                    <div class="p-6 space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if($purchased->playlist->image)
                                    <img src="{{ getImage(getFilePath('thumbnail') . '/' . $purchased->playlist->image) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-rounded text-2xl text-gray-400">playlist_play</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-black text-gray-900 dark:text-white text-sm line-clamp-1">{{ __($purchased->playlist->title) }}</h4>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ showAmount($purchased->amount) }} • {{ diffForHumans($purchased->created_at) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('preview.playlist.videos', [$purchased->playlist->slug, $purchased->playlist->user->slug]) }}" target="_blank" class="w-full py-3 bg-red-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded text-lg">play_arrow</span>
                            Watch Playlist
                        </a>
                    </div>
                    @empty
                    <div class="py-16 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No purchased playlists yet</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @if($purchasedPlaylists->hasPages())
            <div class="mt-12">
                {{ $purchasedPlaylists->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
