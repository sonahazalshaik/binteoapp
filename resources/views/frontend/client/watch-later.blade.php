<x-app-layout>
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-baseline justify-between mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Watch Later</h1>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2 flex items-center gap-2">
                        <span class="material-symbols-rounded text-base">playlist_play</span>
                        {{ count($videos ?? []) }} Premium Videos Saved
                    </p>
                </div>
            </div>

            <!-- Video Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($videos ?? [] as $video)
                <div class="group relative bg-white dark:bg-[#181818] rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <!-- Thumbnail Area -->
                    <a href="{{ route('watch', $video->slug) }}" class="block relative aspect-video overflow-hidden">
                        @if($video->image)
                            <img src="{{ getImage(getFilePath('thumbnail') . '/' . $video->image->thumb) }}" alt="{{ $video->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                                <span class="material-symbols-rounded text-4xl text-gray-300 dark:text-white/10">smart_display</span>
                            </div>
                        @endif
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center scale-75 group-hover:scale-100 transition-transform duration-500">
                                <span class="material-symbols-rounded text-3xl text-white fill-1">play_arrow</span>
                            </div>
                        </div>

                        <!-- Duration Badge -->
                        @if($video->formatted_duration !== '--:--')
                        <div class="absolute bottom-3 right-3 bg-black/80 backdrop-blur-md text-white px-2.5 py-1 rounded-lg text-[10px] font-black tracking-widest">
                            {{ $video->formatted_duration }}
                        </div>
                        @endif
                    </a>

                    <!-- Content -->
                    <div class="p-6">
                        <div class="flex gap-4">
                            <!-- Channel Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 overflow-hidden">
                                    @if($video->user->channel && $video->user->channel->avatar)
                                        <img src="{{ getImage($video->user->channel->avatar) }}" class="w-full h-full object-cover">

                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-red-500/10 text-red-500">
                                            <span class="material-symbols-rounded text-xl">person</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex-grow min-w-0">
                                <a href="{{ route('watch', $video->slug) }}" class="block">
                                    <h3 class="font-black text-gray-900 dark:text-white text-sm line-clamp-2 leading-tight group-hover:text-red-500 transition-colors mb-2">{{ $video->title }}</h3>
                                </a>
                                <div class="flex items-center flex-wrap gap-x-3 gap-y-1">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate max-w-[120px]">{{ $video->user->username }}</p>
                                    <span class="w-1 h-1 bg-gray-300 dark:bg-white/10 rounded-full"></span>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ number_format($video->views) }} Views</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Android-style Floating Action (Remove) -->
                    <form action="{{ route('user.watch.later.remove', $video->id) }}" method="POST" class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                        @csrf
                        <button type="submit" class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-md flex items-center justify-center text-red-600 shadow-lg hover:bg-red-600 hover:text-white transition-all">
                            <span class="material-symbols-rounded text-lg">close</span>
                        </button>
                    </form>
                </div>
                @empty
                <div class="col-span-full py-32 flex flex-col items-center justify-center text-center">
                    <div class="w-32 h-32 rounded-[3.5rem] bg-gray-50 dark:bg-white/2 flex items-center justify-center mb-8">
                        <span class="material-symbols-rounded text-7xl text-gray-200 dark:text-white/5">auto_stories</span>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">Your library is empty</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-10 max-w-xs">Save videos you want to watch later while exploring the platform.</p>
                    <a href="{{ route('home') }}" class="px-10 py-4 bg-red-600 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-red-500/30 hover:bg-red-700 hover:-translate-y-1 transition-all active:scale-95">
                        Discover Content
                    </a>
                </div>
                @endforelse
            </div>

            @if(isset($videos) && $videos->hasPages())
            <div class="mt-16">
                {{ $videos->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
