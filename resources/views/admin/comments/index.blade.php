@extends('admin.layouts.app')

@section('title', 'Comment Moderation')
@section('header_title', 'Interaction Moderation')

@section('content')
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm dark:shadow-sm transition-all duration-300">
    @include('admin.components.header-toolbar', [
        'title' => 'Comment Moderation',
        'items' => $comments,
        'createRoute' => route('admin.comments.create'),
        'createLabel' => 'Add Comment'
    ])

    <div class="px-6 pt-6">
        @include('admin.components.table-toolbar', ['module' => 'comments', 'exportTotal' => count($comments)])
    </div>

    <!-- Mobile Card View -->
    <div x-show="$store.viewMode && $store.viewMode.mode === 'app'" x-cloak class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($comments as $comment)
        @php
            $isReel = $comment->comment_type === 'reel';
            $content = $isReel ? $comment->reel : $comment->video;
            $channel = $content->user->channel ?? $content->user;
            $contentUrl = $isReel ? route('reels.show', $content->slug) : route('videos.show', $content->slug);
        @endphp
        <div class="bg-white dark:bg-[#1A1A1A] border border-slate-200 dark:border-white/10 rounded-[2.5rem] p-6 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none transition-all duration-500 flex flex-col group relative overflow-hidden">
            <!-- Type Badge -->
            <div class="absolute top-0 right-0 z-10">
                <div class="px-5 py-2 {{ $isReel ? 'bg-purple-600' : 'bg-blue-600' }} text-white text-[9px] font-black uppercase tracking-[0.2em] rounded-bl-3xl shadow-lg">
                    {{ $isReel ? 'Reel' : 'Video' }}
                </div>
            </div>

            <!-- Content Thumbnail -->
            <div class="relative w-full aspect-video rounded-3xl overflow-hidden mb-6 group/thumb">
                <img src="{{ $content->getThumbnailUrl() }}" class="w-full h-full object-cover transition-transform duration-700 group-hover/thumb:scale-110">
                <a href="{{ $contentUrl }}" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover/thumb:opacity-100 flex items-center justify-center transition-all duration-300 backdrop-blur-[2px]">
                    <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30">
                        <span class="material-symbols-rounded text-2xl">open_in_new</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-[12px] font-black uppercase text-slate-400">
                    {{ substr($comment->user->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[14px] font-black text-slate-900 dark:text-white leading-tight truncate">{{ $comment->user->name }}</p>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest truncate">{{ $channel->name ?? $channel->username }}</span>
                    </div>
                </div>
            </div>

            <div x-data="{ expanded: false }" class="bg-slate-50 dark:bg-white/[0.02] p-5 rounded-3xl border border-slate-100 dark:border-white/5 flex-1 mb-6">
                <div class="text-[12px] font-bold text-slate-700 dark:text-white/80 leading-relaxed" :class="expanded ? 'break-all' : 'line-clamp-2 break-all'">"{{ $comment->content }}"</div>
                @if(strlen($comment->content) > 100)
                    <button @click="expanded = !expanded" class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mt-2 hover:underline">
                        <span x-text="expanded ? 'Show Less' : 'Read More'"></span>
                    </button>
                @endif
            </div>

            <div class="space-y-4">
                <div class="px-2">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Content Title</p>
                    <p class="text-[11px] font-black text-slate-900 dark:text-white leading-snug line-clamp-2">{{ $content->title }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a href="{{ route('admin.comments.show', ['comment' => $comment->id, 'type' => $comment->comment_type]) }}" class="h-12 rounded-2xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all">
                        <span class="material-symbols-rounded text-lg">visibility</span> <span>View</span>
                    </a>
                    <a href="{{ route('admin.comments.edit', ['comment' => $comment->id, 'type' => $comment->comment_type]) }}" class="h-12 rounded-2xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all">
                        <span class="material-symbols-rounded text-lg">edit</span> <span>Edit</span>
                    </a>
                    <form action="{{ route('admin.comments.destroy', ['comment' => $comment->id, 'type' => $comment->comment_type]) }}" method="POST" data-swal-question="Delete this comment?" class="col-span-2">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full h-12 rounded-2xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white hover:border-red-600 transition-all">
                            <span class="material-symbols-rounded text-lg">delete</span> <span>Delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 rounded-[2rem] bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center mb-6">
                <span class="material-symbols-rounded text-4xl text-slate-300 dark:text-white/20">comment_bank</span>
            </div>
            <h3 class="text-[14px] font-black text-slate-900 dark:text-white uppercase tracking-widest ">No Comments Found</h3>
            <p class="text-[10px] font-bold text-slate-400 dark:text-white/20 uppercase tracking-widest mt-2">All quiet in the discussion zone</p>
        </div>
        @endforelse
    </div>

    <!-- Desktop Table View -->
    <div x-show="$store.viewMode && $store.viewMode.mode === 'table'" x-cloak class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">User</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Type</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Comment</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">Video / Reel</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($comments as $comment)
                @php
                    $isReel = $comment->comment_type === 'reel';
                    $content = $isReel ? $comment->reel : $comment->video;
                    $channel = $content->user->channel ?? $content->user;
                    $contentUrl = $isReel ? route('reels.show', $content->slug) : route('videos.show', $content->slug);
                @endphp
                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center font-black text-[12px] text-slate-400 dark:text-white/40 shadow-sm transition-all group-hover:scale-110">
                                @php
                                    $commenterWords = explode(' ', $comment->user->name);
                                    $commenterInitials = (count($commenterWords) > 1) 
                                        ? substr($commenterWords[0], 0, 1) . substr(end($commenterWords), 0, 1) 
                                        : substr($commenterWords[0], 0, 1);
                                @endphp
                                {{ $commenterInitials }}
                            </div>
                            <div>
                                <div class="font-black tracking-tight text-slate-900 dark:text-white text-[12px] mb-0.5">{{ $comment->user->name }}</div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter truncate max-w-[150px]">{{ $comment->user->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest {{ $isReel ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
                            {{ $isReel ? 'Reel' : 'Video' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div x-data="{ expanded: false }" class="text-[11px] font-bold text-slate-700 dark:text-white/80 leading-relaxed bg-slate-50 dark:bg-white/5 p-4 rounded-xl border border-slate-200 dark:border-white/5 max-w-md">
                            <div :class="expanded ? 'break-all' : 'line-clamp-2 break-all'">"{{ $comment->content }}"</div>
                            @if(strlen($comment->content) > 100)
                                <button @click="expanded = !expanded" class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mt-2 hover:underline">
                                    <span x-text="expanded ? 'Show Less' : 'Read More'"></span>
                                </button>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-rounded text-slate-400 text-sm">{{ $isReel ? 'movie_edit' : 'movie' }}</span>
                                <a href="{{ $contentUrl }}" target="_blank" class="text-[11px] font-black text-slate-900 dark:text-white hover:text-blue-600 transition-colors">{{ $content->title }}</a>
                            </div>
                            <div class="flex items-center gap-2 pl-0.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Channel: {{ $channel->name ?? $channel->username }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.comments.show', ['comment' => $comment->id, 'type' => $comment->comment_type]) }}" class="flex items-center gap-1.5 px-3 h-8 rounded-lg bg-blue-600 text-white text-[9px] font-black uppercase tracking-widest shadow-sm hover:scale-105 transition-all">
                                <span class="material-symbols-rounded text-sm">visibility</span> <span>View</span>
                            </a>
                            <a href="{{ route('admin.comments.edit', ['comment' => $comment->id, 'type' => $comment->comment_type]) }}" class="flex items-center gap-1.5 px-3 h-8 rounded-lg bg-amber-500 text-white text-[9px] font-black uppercase tracking-widest shadow-sm hover:scale-105 transition-all">
                                <span class="material-symbols-rounded text-lg">edit</span> <span>Edit</span>
                            </a>
                            <form action="{{ route('admin.comments.destroy', ['comment' => $comment->id, 'type' => $comment->comment_type]) }}" method="POST" data-swal-question="Delete this comment?" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex items-center gap-1.5 px-3 h-8 rounded-lg bg-red-600 text-white text-[9px] font-black uppercase tracking-widest shadow-sm hover:scale-105 transition-all">
                                    <span class="material-symbols-rounded text-sm">delete</span> <span>Delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="100%" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-symbols-rounded text-4xl text-slate-300 dark:text-white/10 mb-4">forum</span>
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ">No Comments Detected</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($comments->hasPages())
        <div class="p-6 lg:p-6 border-t border-slate-100 dark:border-white/10 bg-slate-50 dark:bg-white/[0.01]">
            {{ $comments->links() }}
        </div>
    @endif
</div>
@endsection

