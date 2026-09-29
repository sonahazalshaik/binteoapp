@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-black flex justify-center">
    <div class="w-full max-w-lg relative">
        <!-- Video -->
        <div class="aspect-[9/16] max-h-[90vh] relative mx-auto bg-black rounded-3xl overflow-hidden shadow-2xl">
            <video src="{{ asset(getFilePath('reel') . '/' . ($reel->compressed_video_path ?? $reel->video_path)) }}" class="w-full h-full object-cover" controls loop autoplay></video>
        </div>
        <!-- Info Panel -->
        <div class="bg-white dark:bg-[#121212] rounded-3xl mt-4 p-6 mx-4 border border-slate-100 dark:border-white/5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center text-purple-600 font-black">{{ substr($reel->user->fullname, 0, 1) }}</div>
                <div><p class="font-black text-sm text-slate-900 dark:text-white">{{ $reel->user->username ?? $reel->user->fullname }}</p><p class="text-[10px] text-slate-400">{{ $reel->created_at->diffForHumans() }}</p></div>
                <div class="ml-auto flex gap-2">
                    <form action="{{ route('reels.like', $reel->slug) }}" method="POST">@csrf<button class="flex items-center gap-1 px-3 py-1.5 bg-red-50 dark:bg-red-500/10 text-red-500 rounded-xl text-xs font-bold"><span class="material-symbols-rounded text-sm">favorite</span>{{ $reel->likes_count }}</button></form>
                    <form action="{{ route('reels.share', $reel->slug) }}" method="POST">@csrf<input type="hidden" name="platform" value="copy_link"><button class="flex items-center gap-1 px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-500 rounded-xl text-xs font-bold"><span class="material-symbols-rounded text-sm">share</span>{{ $reel->shares_count }}</button></form>
                </div>
            </div>
            <h2 class="font-black text-lg text-slate-900 dark:text-white mb-2">{{ $reel->title }}</h2>
            @if($reel->description)<p class="text-sm text-slate-600 dark:text-slate-400 mb-4">{{ $reel->description }}</p>@endif
            @if($reel->hashtags->count())
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($reel->hashtags as $ht)<span class="px-2 py-1 bg-purple-50 dark:bg-purple-500/10 text-purple-600 rounded-lg text-xs font-bold">#{{ $ht->hashtag }}</span>@endforeach
            </div>
            @endif
            <!-- Comments -->
            <div class="border-t border-slate-100 dark:border-white/5 pt-4 mt-4">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4">Comments ({{ $reel->comments_count }})</h3>
                @auth
                @if($reel->allow_comments)
                <form action="{{ route('reels.comment', $reel->slug) }}" method="POST" class="flex gap-2 mb-4">@csrf
                    <input type="text" name="comment" required placeholder="Add a comment... @mention users" class="flex-1 h-10 px-4 bg-slate-50 dark:bg-white/5 border-0 rounded-xl text-sm font-medium focus:ring-2 focus:ring-purple-500 outline-none">
                    <button class="h-10 px-4 bg-purple-600 text-white rounded-xl text-xs font-bold">Post</button>
                </form>
                @endif
                @endauth
                <div class="space-y-3 max-h-60 overflow-y-auto">
                    @foreach($reel->comments->where('parent_id', null)->take(20) as $comment)
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/10 flex items-center justify-center text-xs font-black text-slate-500 shrink-0">{{ substr($comment->user->fullname, 0, 1) }}</div>
                        <div>
                            <p class="text-xs"><span class="font-bold text-slate-900 dark:text-white">{{ $comment->user->username ?? $comment->user->fullname }}</span> <span class="text-slate-400">{{ $comment->created_at->diffForHumans() }}</span></p>
                            <p class="text-sm text-slate-700 dark:text-slate-300">{{ $comment->content }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
