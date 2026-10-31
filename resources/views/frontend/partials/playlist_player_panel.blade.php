@php
    $currentIndex = $playlistData->videos->search(fn($v) => $v->id == $video->id);
    $totalCount = $playlistData->videos->count();
    $nextVideo = ($currentIndex !== false && $currentIndex < $totalCount - 1) ? $playlistData->videos[$currentIndex + 1] : null;
@endphp

<div x-data="{ playlistOpen: window.innerWidth > 1024, mobilePopup: false }" 
     id="playlist-container"
     class="mb-6 overflow-hidden rounded-2xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#1A1A1A] transition-all duration-500 shadow-xl shadow-black/10">

    <!-- Mobile Floating Bar -->
    <div class="lg:hidden fixed bottom-24 left-4 right-4 z-[100]" x-show="!mobilePopup">
        <div @click="mobilePopup = true" 
             class="bg-[#0F0F0F] dark:bg-[#1A1A1A] rounded-2xl p-4 shadow-2xl border border-white/10 flex items-center gap-4 active:scale-[0.98] transition-all">
            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center">
                <span class="material-symbols-rounded text-white">playlist_play</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Next:</span>
                    <p class="text-[11px] font-bold text-white truncate">{{ $nextVideo ? $nextVideo->title : 'End of Playlist' }}</p>
                </div>
                <p class="text-[10px] text-gray-400 mt-0.5">
                    {{ $playlistData->user->name }} • {{ ($currentIndex !== false ? $currentIndex + 1 : '?') }}/{{ $totalCount }}
                </p>
            </div>
            <div class="flex-shrink-0">
                <span class="material-symbols-rounded text-white">expand_less</span>
            </div>
        </div>
    </div>

    <!-- Header (Desktop) -->
    <div class="hidden lg:block p-4 bg-gray-100/50 dark:bg-white/5 border-b border-gray-100 dark:border-white/5 cursor-pointer lg:cursor-default" 
         @click="playlistOpen = !playlistOpen">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-black text-gray-900 dark:text-white truncate pr-4">{{ $playlistData->name }}</h3>
            <div class="flex items-center gap-2">
                <span class="lg:hidden material-symbols-rounded text-xl text-gray-400">open_in_new</span>
                <button @click.stop="playlistOpen = !playlistOpen" class="hidden lg:block text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <span class="material-symbols-rounded" :class="playlistOpen ? 'rotate-180' : ''">expand_more</span>
                </button>
            </div>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-[10px] font-black text-gray-500 dark:text-[#AAAAAA] uppercase tracking-widest">
                <span>{{ $playlistData->user->name }}</span>
                <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-white/10"></span>
                <span>{{ $currentIndex !== false ? $currentIndex + 1 : '?' }} / {{ $totalCount }}</span>
            </div>
            <div class="hidden lg:flex items-center gap-3 text-gray-500 dark:text-gray-400">
                <button class="hover:text-red-600 transition-colors"><span class="material-symbols-rounded text-base">repeat</span></button>
                <button class="hover:text-red-600 transition-colors"><span class="material-symbols-rounded text-base">shuffle</span></button>
            </div>
        </div>
    </div>

    <!-- Desktop List -->
    <div class="hidden lg:block max-h-[400px] overflow-y-auto custom-scrollbar" x-show="playlistOpen">
        @foreach($playlistData->videos as $index => $item)
            @include('frontend.partials.playlist_item', ['item' => $item, 'index' => $index, 'playlistData' => $playlistData, 'isActive' => $item->id == $video->id])
        @endforeach
    </div>

    <!-- Mobile Bottom Sheet / Popup -->
    <template x-teleport="body">
        <div x-show="mobilePopup" x-cloak class="lg:hidden fixed inset-0 z-[99999] flex flex-col justify-end">
            <div x-show="mobilePopup" @click="mobilePopup = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            
            <div x-show="mobilePopup" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" class="relative bg-white dark:bg-[#0F0F0F] rounded-t-[2.5rem] p-6 pb-10 max-h-[80vh] flex flex-col shadow-2xl overflow-hidden">
                <div class="w-12 h-1.5 bg-gray-200 dark:bg-white/10 rounded-full mx-auto mb-6 flex-shrink-0" @click="mobilePopup = false"></div>
                
                <div class="flex items-center justify-between mb-6 flex-shrink-0">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ $playlistData->name }}</h3>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest mt-1">{{ $totalCount }} Videos</p>
                    </div>
                    <button @click="mobilePopup = false" class="w-8 h-8 rounded-full gradient-orange text-white flex items-center justify-center shadow-lg shadow-orange-500/20 transition-all active:scale-90 group">
                        <span class="material-symbols-rounded text-[16px] group-active:rotate-90 transition-transform">close</span>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar space-y-1">
                    @foreach($playlistData->videos as $index => $item)
                        @include('frontend.partials.playlist_item', ['item' => $item, 'index' => $index, 'playlistData' => $playlistData, 'isActive' => $item->id == $video->id])
                    @endforeach
                </div>
            </div>
        </div>
    </template>
</div>

@if($nextVideo)
<script>
    document.addEventListener('DOMContentLoaded', () => {
        @php
            $isVirtual = !is_numeric($playlistData->id);
            $nextUrl = $isVirtual 
                ? route('videos.show.virtual', ['virtualSlug' => $playlistData->id, 'video' => $nextVideo->slug])
                : route('videos.show.in_playlist', ['video' => $nextVideo->slug, 'username' => optional($playlistData->user->channel)->slug ?? $playlistData->user->username ?? 'user', 'playlist' => $playlistData->slug]);
        @endphp
        window.nextVideoUrl = "{{ $nextUrl }}";
    });
</script>
@endif
