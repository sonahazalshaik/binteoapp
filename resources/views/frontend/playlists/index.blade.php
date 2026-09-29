<x-app-layout>
    <div id="playlists-index" class="py-12 pb-32 lg:pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8 px-2">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white">Your Playlists</h2>
                    <p class="text-gray-400 font-medium">Manage your saved collections</p>
                </div>
                <button onclick="document.getElementById('createPlaylistModal').classList.toggle('hidden')" class="bg-black dark:bg-white text-white dark:text-black px-6 py-3 rounded-2xl font-bold text-sm hover:bg-gray-800 dark:hover:bg-gray-200 transition-all active:scale-95 shadow-xl shadow-gray-200 dark:shadow-none">
                    Create New
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($playlists as $playlist)
                    @php
                        $firstVideo = $playlist->videos->first();
                        $firstReel = $playlist->reels->first();
                        $thumbnail = $firstVideo ? $firstVideo->getThumbnailUrl() : ($firstReel ? $firstReel->getThumbnailUrl() : null);
                        $totalCount = $playlist->videos_count + $playlist->reels_count;
                    @endphp
                    <div class="group relative" x-data="{ open: false }">
                        <a href="{{ route('playlists.show', ['username' => optional($playlist->user->channel)->slug ?? $playlist->user->username ?? 'user', 'playlist' => $playlist->slug]) }}" 
                           class="block bg-white dark:bg-[#1A1A1A] p-3 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-2xl hover:shadow-gray-200/50 dark:hover:shadow-black/50 transition-all duration-500"
                           :class="open ? 'z-50 relative shadow-2xl' : ''">
                            
                            <div class="aspect-video bg-gray-100 dark:bg-white/5 rounded-2xl overflow-hidden mb-4 relative">
                                @if($thumbnail)
                                    <img src="{{ $thumbnail }}" alt="{{ $playlist->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-100 dark:from-white/10 dark:to-white/5">
                                        <span class="material-symbols-rounded text-4xl opacity-20">playlist_play</span>
                                    </div>
                                @endif
                                
                                <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-[2px]">
                                    <span class="material-symbols-rounded text-4xl">play_circle</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest mt-2">View All</span>
                                </div>

                                <div class="absolute bottom-2 right-2 bg-black/80 backdrop-blur-md px-3 py-1 rounded-lg text-[10px] font-black text-white uppercase tracking-widest flex items-center gap-2">
                                    <span class="material-symbols-rounded text-xs">playlist_play</span>
                                    {{ $totalCount }}
                                </div>
                            </div>

                            <div class="px-2 pb-2">
                                <div class="flex items-start justify-between gap-2 relative">
                                    <h3 class="font-black text-gray-900 dark:text-white truncate group-hover:text-red-600 transition-colors flex-1">{{ $playlist->name }}</h3>
                                    
                                    <!-- Three Dots Menu -->
                                    <div class="relative">
                                        <button @click.prevent="open = !open" 
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-all">
                                            <span class="material-symbols-rounded text-xl">more_vert</span>
                                        </button>
                                        
                                        <div x-show="open" 
                                             @click.away="open = false"
                                             x-transition
                                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#1A1A1A] border border-gray-100 dark:border-white/5 rounded-2xl shadow-2xl z-[100] overflow-hidden backdrop-blur-xl"
                                             style="display: none;">
                                            <div @click.prevent="window.location.href = '{{ route('playlists.show', ['username' => optional($playlist->user->channel)->slug ?? $playlist->user->username ?? 'user', 'playlist' => $playlist->slug]) }}'" 
                                               class="cursor-pointer flex items-center gap-3 px-4 py-3 text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                                <span class="material-symbols-rounded text-lg">visibility</span>
                                                View Playlist
                                            </div>
                                            <div @click.prevent="confirmDelete('{{ route('playlists.destroy', $playlist->id) }}', 'This playlist and all its associations will be removed.')" 
                                                    class="cursor-pointer w-full flex items-center gap-3 px-4 py-3 text-xs font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors border-t border-gray-50 dark:border-white/5">
                                                <span class="material-symbols-rounded text-lg">delete</span>
                                                Delete
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Updated {{ $playlist->updated_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <!-- Simple Modal -->
    <div id="createPlaylistModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#1A1A1A] w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl border border-gray-100 dark:border-white/5">
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">New Playlist</h3>
            <p class="text-gray-400 dark:text-gray-500 text-sm font-medium mb-6">Give your collection a name</p>
            <form action="{{ route('playlists.store') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="E.g. Favorites, Gym Mix..." class="w-full bg-gray-50 dark:bg-white/5 border-transparent rounded-2xl py-4 px-6 focus:ring-red-600 focus:border-red-600 dark:text-white transition-all font-medium mb-6">
                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('createPlaylistModal').classList.add('hidden')" class="flex-1 py-4 font-bold text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors uppercase tracking-widest text-xs">Cancel</button>
                    <button type="submit" class="flex-1 bg-black dark:bg-white text-white dark:text-black py-4 rounded-2xl font-bold shadow-lg shadow-gray-200 dark:shadow-none active:scale-95 transition-all text-xs uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-gray-200">Create</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Page-scoped icon visibility fix (this page only).
           The layout globally forces `.material-symbols-rounded` to `color: inherit`,
           leaving the no-cover placeholder icon dark-on-dark in dark mode. */
        .dark #playlists-index span.material-symbols-rounded.text-4xl.opacity-20 { color: rgba(255,255,255,0.6) !important; }
    </style>
</x-app-layout>
