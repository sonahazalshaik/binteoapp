<x-app-layout>
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Channel Content</h1>
                    <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Manage your uploads and analytics</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative group">
                        <input type="text" placeholder="Search content..." class="pl-12 pr-6 py-3.5 bg-white dark:bg-[#1A1A1A] border-gray-100 dark:border-white/5 rounded-2xl w-80 text-sm font-bold focus:ring-red-500 focus:border-red-500 transition-all shadow-sm">
                        <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <a href="{{ route('videos.create') }}" class="px-8 py-3.5 bg-red-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-red-200 dark:shadow-none hover:bg-red-700 transition-all flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Content Table -->
            <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 dark:bg-white/2 border-b border-gray-100 dark:border-white/5">
                        <tr>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Video</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Views</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Likes</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                        @forelse($videos as $video)
                        <tr class="hover:bg-gray-50/30 dark:hover:bg-white/2 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-6">
                                    <div class="w-32 aspect-video rounded-2xl bg-gray-100 dark:bg-white/5 overflow-hidden flex-shrink-0 relative">
                                        @if($video->thumbnail_path)
                                            <img src="{{ $video->getThumbnailUrl() }}" class="w-full h-full object-cover">


                                        @endif
                                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors"></div>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[15px] font-black text-gray-900 dark:text-white mb-1 truncate">{{ $video->title }}</p>
                                        <p class="text-[11px] font-bold text-gray-400 line-clamp-1 opacity-60">{{ $video->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @if($video->status === 'ready')
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-green-50 dark:bg-green-500/10 text-green-500 text-[10px] font-black uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-orange-50 dark:bg-orange-500/10 text-orange-500 text-[10px] font-black uppercase tracking-widest text-center">
                                        <div class="w-1.5 h-1.5 bg-orange-500 rounded-full mr-2 animate-ping"></div>
                                        Processing
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-tighter">
                                {{ $video->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-sm font-black text-gray-700 dark:text-gray-300">{{ number_format($video->views_count) }}</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-sm font-black text-gray-700 dark:text-gray-300">{{ number_format($video->likes()->count()) }}</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end space-x-3 opacity-20 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('videos.show', $video) }}" target="_blank" class="p-2.5 bg-gray-50 dark:bg-white/5 rounded-xl text-gray-400 hover:text-red-500 transition-all border border-transparent hover:border-red-500/20"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                    <a href="{{ route('studio.edit', $video) }}" class="p-2.5 bg-gray-50 dark:bg-white/5 rounded-xl text-gray-400 hover:text-red-500 transition-all border border-transparent hover:border-red-500/20"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                    <form action="{{ route('studio.destroy', $video) }}" method="POST" onsubmit="return confirm('Kill this video forever? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-gray-50 dark:bg-white/5 rounded-xl text-gray-400 hover:text-red-600 transition-all border border-transparent hover:border-red-600/20 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gray-50 dark:bg-white/5 rounded-[2rem] flex items-center justify-center text-gray-200 dark:text-gray-800 mb-6"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg></div>
                                    <h3 class="text-lg font-black text-gray-900 dark:text-white">Silence is not golden here.</h3>
                                    <p class="text-sm font-bold text-gray-400 mt-2">Upload your first masterpiece to start the journey.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-8 py-6 border-t border-gray-50 dark:border-white/5">
                    {{ $videos->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
