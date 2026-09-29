@php
    $isVirtual = !is_numeric($playlistData->id);
    $itemRoute = $isVirtual 
        ? route('videos.show.virtual', ['virtualSlug' => $playlistData->id, 'video' => $item->slug])
        : route('videos.show.in_playlist', ['video' => $item->slug, 'username' => optional($playlistData->user->channel)->slug ?? $playlistData->user->username ?? 'user', 'playlist' => $playlistData->slug]);
@endphp

<a href="{{ $itemRoute }}" 
   class="flex items-center gap-3 p-3 hover:bg-gray-200/50 dark:hover:bg-white/5 transition-colors border-l-4 {{ $isActive ? 'border-red-600 bg-red-50/50 dark:bg-red-600/5' : 'border-transparent' }}">
    <div class="flex-shrink-0 w-6 text-center text-[10px] font-black {{ $isActive ? 'text-red-600' : 'text-gray-400' }}">
        @if($isActive)
            <span class="material-symbols-rounded text-sm fill-1">play_arrow</span>
        @else
            {{ $index + 1 }}
        @endif
    </div>
    <div class="relative w-24 aspect-video rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-800">
        <img src="{{ $item->getThumbnailUrl() }}" class="w-full h-full object-cover">
        <div class="absolute bottom-1 right-1 px-1 py-0.5 bg-black/80 rounded text-[8px] font-bold text-white">
            {{ $item->duration ?? '0:00' }}
        </div>
    </div>
    <div class="flex-grow min-w-0">
        <h4 class="text-[12px] font-bold {{ $isActive ? 'text-red-600' : 'text-gray-900 dark:text-[#F1F1F1]' }} line-clamp-2 leading-tight">
            {{ $item->title }}
        </h4>
        <p class="text-[10px] text-gray-500 dark:text-[#AAAAAA] mt-1">{{ $item->user->channel->name ?? $item->user->name }}</p>
    </div>
    <button @click.prevent.stop="window.openVideoOptions('{{ $item->id }}', '{{ addslashes($item->title) }}', 'video', {{ $item->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $item->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $item->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($item->getPlaylistMembershipIds(auth()->user() ?? null)), {{ $item->user_id }})" 
            class="flex-shrink-0 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-900 dark:hover:text-white active:scale-90 transition-transform">
        <span class="material-symbols-rounded text-lg">more_vert</span>
    </button>
</a>
