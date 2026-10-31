@php
    $relChannelName = $related->user->channel->name ?? $related->user->name ?? 'Creator';
    $relWords = explode(' ', trim($relChannelName));
    $relInitials = count($relWords) >= 2 
        ? strtoupper(substr($relWords[0], 0, 1) . substr($relWords[count($relWords)-1], 0, 1))
        : strtoupper(substr($relChannelName, 0, 2));
@endphp
<!-- Thumbnail & Text Flex Block -->
<a href="{{ route('videos.show', $related) }}" 
   class="flex flex-col lg:flex-row gap-3 lg:gap-2.5 group cursor-pointer"
   onmouseenter="this.querySelector('video')?.play()?.catch(()=>{})"
   onmouseleave="const v = this.querySelector('video'); if(v) { v.pause(); v.currentTime = 0; }">
    <div class="relative w-full lg:w-[168px] aspect-video flex-shrink-0 rounded-2xl lg:rounded-xl overflow-hidden bg-gray-200 dark:bg-[#272727]">
        @if($related->isBunnyVideo())
            <img src="{{ $related->getPreviewUrl() }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy" />
        @else
            <video src="{{ $related->getVideoUrl() }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" preload="metadata" muted playsinline></video>
        @endif
        <img src="{{ $related->getThumbnailUrl() }}" 
             class="absolute inset-0 w-full h-full object-cover group-hover:opacity-0 transition-opacity duration-500 z-10"
             onerror="this.src='{{ $related->getPreviewUrl() }}'; this.onerror=null;">
        <span class="absolute bottom-2 right-2 lg:bottom-1 lg:right-1 bg-black/80 text-white text-[12px] px-1.5 py-0.5 rounded font-medium z-20">{{ $related->formatted_duration }}</span>
    </div>
    <div class="flex flex-1 items-start gap-3 lg:gap-0 lg:flex-col py-0.5 lg:py-0.5 min-w-0 px-1 lg:px-0">
        <!-- Mobile-Only Channel Avatar (Left of Title & Meta) -->
        <div class="lg:hidden w-10 h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-800 flex-shrink-0 mt-0.5">
            @if($related->user->channel && $related->user->channel->avatar)
                <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $related->user->channel->avatar) }}" class="w-full h-full object-cover">
            @elseif($related->user->image)
                <img src="{{ getImage(getFilePath('userProfile') . '/' . $related->user->image) }}" class="w-full h-full object-cover">
            @else
                <span class="text-[10px] font-black text-gray-500 dark:text-white flex items-center justify-center w-full h-full">{{ $relInitials }}</span>
            @endif
        </div>

        <!-- Content Column -->
        <div class="flex-1 min-w-0">
            <div class="flex justify-between items-start gap-2">
                <h4 class="font-bold text-[14px] sm:text-[15px] lg:text-[14px] leading-snug line-clamp-2 text-gray-900 dark:text-[#F1F1F1]">{{ $related->title }}</h4>
                <button onclick="event.preventDefault(); event.stopPropagation(); window.openVideoOptions('{{ $related->id }}', '{{ addslashes($related->title) }}', 'video', {{ $related->isLikedBy(auth()->user()) ? 'true' : 'false' }}, {{ $related->isInWatchLater(auth()->user()) ? 'true' : 'false' }}, {{ $related->isInAnyPlaylist(auth()->user()) ? 'true' : 'false' }}, @json($related->getPlaylistMembershipIds(auth()->user() ?? null)), {{ $related->user_id }})" 
                        class="text-gray-400 dark:text-[#AAAAAA] hover:text-gray-900 dark:hover:text-white transition-colors shrink-0 p-1 -mr-1">
                    <span class="material-symbols-rounded text-[20px]">more_vert</span>
                </button>
            </div>
            <div class="flex flex-col mt-1 text-[12px] text-gray-500 dark:text-[#AAAAAA]">
                <!-- Desktop Channel Avatar + Name Row -->
                <div class="hidden lg:flex items-center gap-2 mb-1">
                    <div class="w-4 h-4 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-800 flex items-center justify-center shrink-0">
                        @if($related->user->channel && $related->user->channel->avatar)
                            <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $related->user->channel->avatar) }}" class="w-full h-full object-cover">
                        @elseif($related->user->image)
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $related->user->image) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-[6px] font-black text-gray-500 dark:text-white">{{ $relInitials }}</span>
                        @endif
                    </div>
                    <p class="hover:text-gray-900 dark:hover:text-white transition-colors line-clamp-1 truncate">{{ $relChannelName }}</p>
                </div>

                <!-- Mobile Channel Name + Views + Time Row -->
                <p class="lg:hidden text-[12px] text-gray-500 dark:text-gray-400 line-clamp-1">
                    {{ $relChannelName }} &bull; {{ number_format($related->views_count) }} views &bull; {{ $related->created_at->diffForHumans() }}
                </p>

                <!-- Desktop Views & Time -->
                <p class="hidden lg:block">{{ number_format($related->views_count) }} views &bull; {{ $related->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>
</a>
