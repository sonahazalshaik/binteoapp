<x-app-layout>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[1600px] mx-auto px-6">
            <div class="mb-12">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-600 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-purple-500/30">
                        <span class="material-symbols-rounded text-2xl fill-1">movie</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Stock Videos</h1>
                        <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1">Premium licensed content available for purchase</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($videos ?? [] as $video)
                <a href="{{ route('watch', $video->slug ?? $video->id) }}" class="bg-white dark:bg-[#1A1A1A] rounded-[2rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-500 group">
                    <div class="relative aspect-video overflow-hidden">
                        @if($video->image)
                            <img src="{{ getImage(getFilePath('thumbnail') . '/' . $video->image->thumb) }}" alt="{{ $video->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                                <span class="material-symbols-rounded text-4xl text-gray-300 dark:text-white/10">smart_display</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-white/90 flex items-center justify-center">
                                <span class="material-symbols-rounded text-2xl text-red-600 fill-1">play_arrow</span>
                            </div>
                        </div>
                        <!-- Price Badge -->
                        <div class="absolute top-3 right-3 px-3 py-1.5 bg-purple-600 text-white rounded-xl text-[10px] font-black uppercase shadow-lg shadow-purple-500/30">
                            {{ showAmount($video->price ?? 0) }}
                        </div>
                        @if($video->formatted_duration !== '--:--')
                        <span class="absolute bottom-2 right-2 bg-black/80 text-white px-2 py-0.5 rounded-md text-[10px] font-black">{{ $video->formatted_duration }}</span>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="font-black text-sm text-gray-900 dark:text-white line-clamp-2 mb-2 group-hover:text-purple-600 transition-colors">{{ $video->title }}</h3>
                        <div class="flex items-center gap-3">
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $video->user->username ?? 'Creator' }}</span>
                            <span class="w-1 h-1 bg-gray-300 dark:bg-white/10 rounded-full"></span>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ number_format($video->views) }} views</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full bg-white dark:bg-[#1A1A1A] rounded-[2rem] p-20 border border-gray-100 dark:border-white/5 text-center">
                    <span class="material-symbols-rounded text-6xl text-gray-200 dark:text-white/5 mb-6">movie</span>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">No Stock Videos</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Premium content will appear here when available.</p>
                </div>
                @endforelse
            </div>

            @if(isset($videos) && $videos->hasPages())
            <div class="mt-12">
                {{ $videos->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
