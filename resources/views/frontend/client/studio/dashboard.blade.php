<x-app-layout>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <!-- Studio Header -->
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Channel Analytics</h1>
                    <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Welcome back, {{ auth()->user()->name }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('videos.create') }}" class="px-8 py-3.5 bg-red-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-red-200 dark:shadow-none hover:bg-red-700 transition-all active:scale-95 flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>Upload Video</span>
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                @foreach([
                    ['Total Views', number_format($totalViews), 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'from-blue-500 to-indigo-600'],
                    ['Subscribers', number_format($subscribersCount), 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'from-red-500 to-orange-600'],
                    ['Likes', number_format($totalLikes), 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'from-pink-500 to-rose-600'],
                    ['Comments', number_format($totalComments), 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'from-purple-500 to-violet-600']
                ] as $stat)
                <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-8 border border-gray-100 dark:border-white/5 shadow-sm group hover:border-red-500 transition-all duration-500 scale-100 hover:scale-[1.02]">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr {{ $stat[3] }} flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat[2] }}"></path></svg>
                        </div>
                        <span class="text-[10px] font-black text-green-500 bg-green-50 dark:bg-green-500/10 px-3 py-1 rounded-full">+12.5%</span>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-1">{{ $stat[1] }}</h3>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">{{ $stat[0] }}</p>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Recent Videos (Channel Content) -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="flex items-center justify-between px-2">
                        <h2 class="text-xl font-black text-gray-900 dark:text-white">Uploaded Content</h2>
                        <a href="{{ route('studio.videos') }}" class="text-[10px] font-black text-red-600 uppercase tracking-widest hover:underline">See all videos</a>
                    </div>
                    
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50 dark:bg-white/2 border-b border-gray-100 dark:border-white/5">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Video</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Visibility</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Views</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                                @foreach($recentVideos as $video)
                                <tr class="hover:bg-gray-50/30 dark:hover:bg-white/2 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-20 aspect-video rounded-xl bg-gray-100 dark:bg-white/5 overflow-hidden flex-shrink-0">
                                                @if($video->thumbnail_path)
                                                    <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover">


                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-black text-gray-800 dark:text-white truncate max-w-[200px]">{{ $video->title }}</p>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase mt-1">{{ $video->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="flex items-center space-x-2 text-[10px] font-black text-green-500 uppercase tracking-widest">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                            <span>Public</span>
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 font-black text-sm text-gray-700 dark:text-gray-300">
                                        {{ number_format($video->views_count) }}
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('studio.edit', $video) }}" class="p-2 bg-gray-100 dark:bg-white/5 rounded-xl text-gray-400 hover:text-red-600 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                            <form action="{{ route('studio.destroy', $video) }}" method="POST" onsubmit="return confirm('Delete permanently?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-gray-100 dark:bg-white/5 rounded-xl text-gray-400 hover:text-red-600 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Latest Video Card -->
                <div class="space-y-8">
                    <h2 class="text-xl font-black text-gray-900 dark:text-white px-2">Performance</h2>
                    @if($recentVideos->first())
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-8 border border-gray-100 dark:border-white/5 shadow-sm text-center">
                        <div class="relative w-full aspect-video rounded-3xl overflow-hidden mb-8 shadow-xl">
                            @if($recentVideos->first()->thumbnail_path)
                                <img src="{{ $recentVideos->first()->getThumbnailUrl() }}" class="w-full h-full object-cover">


                            @endif
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div>
                            </div>
                        </div>
                        <h4 class="text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Latest Upload</h4>
                        <p class="text-lg font-black text-gray-900 dark:text-white mb-8 line-clamp-1 px-4">{{ $recentVideos->first()->title }}</p>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-xs font-bold px-4">
                                <span class="text-gray-400">Views</span>
                                <span class="dark:text-white">{{ number_format($recentVideos->first()->views_count) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs font-bold px-4">
                                <span class="text-gray-400">Average Duration</span>
                                <span class="dark:text-white">03:45</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 dark:bg-white/5 rounded-full mt-6 overflow-hidden">
                                <div class="w-2/3 h-full bg-red-600 rounded-full shadow-[0_0_15px_rgba(255,0,0,0.5)]"></div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Reels Section -->
            <div class="mt-12">
                <div class="flex items-center justify-between px-2 mb-6">
                    <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-rounded text-purple-500">slow_motion_video</span> Your Reels
                    </h2>
                    <a href="{{ route('studio.reels') }}" class="text-[10px] font-black text-purple-600 uppercase tracking-widest hover:underline">See all reels</a>
                </div>
                @if(isset($recentReels) && $recentReels->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($recentReels as $reel)
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-2xl overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm group">
                        <div class="aspect-[9/16] max-h-[200px] bg-black relative overflow-hidden">
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-900 to-pink-900">
                                <span class="material-symbols-rounded text-3xl text-white/30">slow_motion_video</span>
                            </div>
                            <div class="absolute bottom-2 right-2 px-2 py-0.5 bg-black/70 rounded text-[9px] font-black text-white">{{ gmdate('i:s', $reel->duration) }}</div>
                        </div>
                        <div class="p-3">
                            <p class="font-bold text-xs text-gray-900 dark:text-white line-clamp-1 mb-1">{{ $reel->title }}</p>
                            <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold">
                                <span>❤ {{ $reel->likes_count }}</span>
                                <span>👁 {{ $reel->views_count }}</span>
                            </div>
                            <div class="flex gap-1 mt-2">
                                <a href="{{ route('studio.reels.edit', $reel) }}" class="flex-1 h-7 bg-blue-50 dark:bg-blue-500/10 text-blue-600 rounded-lg flex items-center justify-center text-[10px] font-bold">Edit</a>
                                <form action="{{ route('studio.reels.destroy', $reel) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                    <button class="w-full h-7 bg-red-50 dark:bg-red-500/10 text-red-600 rounded-lg text-[10px] font-bold">Del</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-white dark:bg-[#1A1A1A] rounded-2xl p-8 text-center border border-gray-100 dark:border-white/5">
                    <span class="material-symbols-rounded text-4xl text-gray-200 dark:text-white/10 mb-2 block">slow_motion_video</span>
                    <p class="text-sm text-gray-400 font-bold">No reels yet</p>
                    <a href="{{ route('reels.create') }}" class="inline-flex items-center gap-1 mt-3 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-500 text-white rounded-xl text-xs font-bold"><span class="material-symbols-rounded text-sm">add</span> Upload Reel</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
