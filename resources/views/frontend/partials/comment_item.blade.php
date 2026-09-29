@php
    $hasLiked = auth()->check() && $comment->likes->where('user_id', auth()->id())->first();
    $isReel = $comment instanceof \App\Models\ReelComment;
@endphp
<div class="comment-item comment-user-{{ $comment->user_id }} group/comment" id="comment-{{ $comment->id }}" data-comment-id="{{ $comment->id }}" data-pinned="{{ $comment->is_pinned ? 'true' : 'false' }}" 
     x-data="{ 
        replying: false, 
        editing: false, 
        liked: {{ $hasLiked ? 'true' : 'false' }}, 
        likes: {{ $comment->likes->count() }}, 
        showReplies: false,
        expanded: false,
        showMore: false,
        content: '{{ addslashes($comment->content) }}',
        isReel: {{ $isReel ? 'true' : 'false' }},
        isEdited: {{ $comment->created_at != $comment->updated_at ? 'true' : 'false' }},
        init() {
            this.$nextTick(() => {
                const el = this.$refs.commentBody;
                if (el) {
                    const observer = new ResizeObserver(() => {
                        this.showMore = el.scrollHeight > el.clientHeight + 1;
                    });
                    observer.observe(el);
                    
                    // Initial check just in case
                    this.showMore = el.scrollHeight > el.clientHeight + 1;
                }
            });
        }
     }"
     @comment-like-updated.window="if ($event.detail.id == {{ $comment->id }}) { liked = $event.detail.liked; likes = $event.detail.likes; }">

    {{-- Comment Row: Avatar + Content --}}
    <div class="flex gap-4 items-start">
        <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-white font-bold text-sm shrink-0 overflow-hidden border border-gray-100 dark:border-white/10 shadow-sm">
            @if($comment->user->channel && $comment->user->channel->avatar)
                <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $comment->user->channel->avatar) }}" class="w-full h-full object-cover">
            @elseif($comment->user->image)
                <img src="{{ getImage(getFilePath('userProfile') . '/' . $comment->user->image) }}" class="w-full h-full object-cover">
            @else
                @php
                    $name = $comment->user->channel->name ?? $comment->user->name;
                    $words = explode(' ', trim($name));
                    $initials = count($words) >= 2 
                        ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                        : strtoupper(substr($name, 0, 2));
                @endphp
                <span class="text-[13px] font-black text-gray-500 dark:text-white">{{ $initials }}</span>
            @endif
        </div>

        <div class="flex-1 min-w-0">
            @if(!$comment->parent_id && $comment->is_pinned)
                <div class="inline-flex items-center gap-1.5 bg-orange-500/10 dark:bg-orange-500/20 text-orange-600 dark:text-orange-400 px-2.5 py-1 rounded-full text-[10px] font-bold mb-2 shadow-sm border border-orange-500/20">
                    <span class="material-symbols-rounded text-[13px] font-variation-filled">push_pin</span>
                    @if(isset($video) && $video->user->channel?->avatar)
                        <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $video->user->channel->avatar) }}" class="w-3.5 h-3.5 rounded-full object-cover border border-orange-500/30">
                    @elseif(isset($video))
                        <div class="w-3.5 h-3.5 rounded-full gradient-orange flex items-center justify-center text-[7px] text-white font-black uppercase shrink-0">
                            @php
                                $chanName = $video->user->channel_name ?? $video->user->username;
                                $words = explode(' ', trim($chanName));
                                $initials = count($words) >= 2 
                                    ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                    : strtoupper(substr($chanName, 0, 2));
                            @endphp
                            {{ $initials }}
                        </div>
                    @elseif($comment->video && $comment->video->user->channel?->avatar)
                        <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $comment->video->user->channel->avatar) }}" class="w-3.5 h-3.5 rounded-full object-cover border border-orange-500/30">
                    @elseif($comment->video)
                        <div class="w-3.5 h-3.5 rounded-full gradient-orange flex items-center justify-center text-[7px] text-white font-black uppercase shrink-0">
                            @php
                                $chanName = $comment->video->user->channel_name ?? $comment->video->user->username;
                                $words = explode(' ', trim($chanName));
                                $initials = count($words) >= 2 
                                    ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                    : strtoupper(substr($chanName, 0, 2));
                            @endphp
                            {{ $initials }}
                        </div>
                    @endif
                    <span>Pinned by {{ isset($video) ? ($video->user->channel_name ?? $video->user->username) : ($comment->video->user->channel_name ?? $comment->video->user->username) }}</span>
                </div>
            @endif
            <div class="flex items-center gap-2 mb-0.5">
                <span class="text-[13px] font-bold text-gray-900 dark:text-white">@ {{ str_replace(' ', '', strtolower($comment->user->name)) }}</span>
                <span class="text-[12px] text-gray-500 dark:text-[#AAAAAA]">{{ $comment->created_at->diffForHumans() }}</span>
                <span x-show="isEdited" class="text-[10px] text-gray-400 dark:text-[#666666]">(edited)</span>
            </div>

            {{-- Comment Content / Edit Form --}}
            <div class="text-[14px] text-gray-900 dark:text-[#F1F1F1] leading-relaxed mb-1">
                <div x-show="!editing" x-ref="commentBody" x-html="window.formatComment(content)" :class="expanded ? '' : 'line-clamp-5'" class="whitespace-pre-wrap break-all lg:break-words" :style="expanded ? '' : '-webkit-box-orient: vertical; overflow: hidden;'"></div>
                <button x-show="!editing && showMore" @click="expanded = !expanded" class="text-[13px] font-bold text-blue-600 dark:text-blue-400 hover:underline mt-1 transition-colors">
                    <span x-text="expanded ? 'Show less' : 'Read more'"></span>
                </button>
                
                <div x-show="editing" x-cloak class="mt-2 space-y-2" x-data="{ 
                    emojisOpen: false,
                    emojiList: window.EMOJI_LIST || []
                }">
                    <textarea 
                        x-ref="editText"
                        class="w-full bg-transparent border-b-2 border-gray-200 dark:border-white/10 focus:border-red-600 dark:focus:border-red-500 outline-none py-1 text-[14px] resize-none transition-colors"
                        rows="2"
                    >{{ $comment->content }}</textarea>
                    <!-- Emoji Picker -->
                    <div class="relative inline-block">
                        <button type="button" @click="emojisOpen = !emojisOpen" class="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-colors" title="Add emoji">
                            <span class="material-symbols-rounded text-[20px] text-gray-500 dark:text-gray-400">emoji_emotions</span>
                        </button>
                        <div x-show="emojisOpen" x-transition @click.away="emojisOpen = false" class="absolute bottom-full mb-2 left-0 w-[280px] bg-white dark:bg-[#1A1A1A] border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden">
                            <div class="p-1.5 border-b border-gray-100 dark:border-white/5">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2">Emoji</span>
                            </div>
                            <div class="p-2 grid grid-cols-8 gap-0.5 max-h-[220px] overflow-y-auto">
                                <template x-for="(emoji, idx) in emojiList" :key="idx">
                                    <button type="button" @click="$refs.editText.focus(); const el = $refs.editText; const start = el.selectionStart; const end = el.selectionEnd; el.value = el.value.slice(0,start) + emoji + el.value.slice(end); el.selectionStart = el.selectionEnd = start + emoji.length; emojisOpen = false;" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 cursor-pointer transition-colors text-lg" x-text="emoji"></button>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button @click="editing = false" class="px-4 py-1.5 hover:bg-gray-100 dark:hover:bg-white/5 rounded-full text-[12px] font-bold transition-colors dark:text-white">Cancel</button>
                        <button @click="window.updateComment({{ $comment->id }}, $refs.editText.value, {{ $isReel ? 'true' : 'false' }}).then(res => { if(res) { content = res; isEdited = true; editing = false } })" class="px-4 py-1.5 bg-red-600 text-white rounded-full font-bold text-[12px] hover:bg-red-700 transition-colors shadow-lg shadow-red-600/20">Save</button>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-1 text-gray-500 dark:text-[#AAAAAA]">
                <button @click="window.likeComment({{ $comment->id }}, {{ $isReel ? 'true' : 'false' }})" 
                        :class="liked ? 'text-red-500' : 'text-gray-400'"
                        class="flex items-center gap-1.5 hover:text-red-500 transition-colors py-1 px-2 rounded-full hover:bg-red-50 dark:hover:bg-red-900/10">
                    <span class="material-symbols-rounded text-[18px]" :class="liked ? 'fill-1' : ''">favorite</span>
                    <span class="text-[12px] font-bold" x-text="likes"></span>
                </button>
                
                <button @click="@auth replying = !replying @else showLoginAlert('reply to this comment') @endauth" 
                        class="text-[12px] font-bold hover:text-gray-900 dark:hover:text-white transition-colors py-1 px-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-[#272727] rounded-full uppercase tracking-tighter">
                    Reply
                </button>
                @if(auth()->check())
                    <div class="relative" x-data="{ menuOpen: false }">
                        <button @click.stop="menuOpen = !menuOpen" class="p-1 hover:bg-gray-100 dark:hover:bg-[#272727] rounded-full transition-all">
                            <span class="material-symbols-rounded text-[18px]">more_vert</span>
                        </button>
                        <div x-show="menuOpen" @click.outside="menuOpen = false" x-cloak x-transition class="absolute right-0 mt-1 w-36 bg-white dark:bg-[#1f1f1f] shadow-2xl rounded-xl border border-gray-100 dark:border-white/5 py-1 z-50 overflow-hidden">
                            @if(!$comment->parent_id && (
                                (isset($video) && $video->user_id == auth()->id()) ||
                                ($comment->video && $comment->video->user_id == auth()->id()) || 
                                ($comment->reel && $comment->reel->user_id == auth()->id())
                            ))
                            <button @click="togglePinComment({{ $comment->id }}, {{ $isReel ? 'true' : 'false' }}); menuOpen = false" class="w-full px-4 py-2 text-left text-[13px] font-bold hover:bg-gray-50 dark:hover:bg-white/5 flex items-center gap-3 transition-colors dark:text-white">
                                <span class="material-symbols-rounded text-[18px]">push_pin</span>
                                <span>{{ $comment->is_pinned ? 'Unpin' : 'Pin' }}</span>
                            </button>
                            @endif
                            @if($comment->user_id == auth()->id() || (isset($video) && $video->user_id == auth()->id()) || ($comment->video && $comment->video->user_id == auth()->id()) || ($comment->reel && $comment->reel->user_id == auth()->id()))
                                @if($comment->user_id == auth()->id())
                                <button @click="editing = true; menuOpen = false; $nextTick(() => $refs.editText.focus())" class="w-full px-4 py-2 text-left text-[13px] font-bold hover:bg-gray-50 dark:hover:bg-white/5 flex items-center gap-3 transition-colors dark:text-white">
                                    <span class="material-symbols-rounded text-[18px]">edit</span>
                                    <span>Edit</span>
                                </button>
                                @endif
                                <button @click="window.deleteComment({{ $comment->id }}, {{ $isReel ? 'true' : 'false' }}); menuOpen = false" class="w-full px-4 py-2 text-left text-[13px] font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 flex items-center gap-3 transition-colors">
                                    <span class="material-symbols-rounded text-[18px]">delete</span>
                                    <span>Delete</span>
                                </button>
                            @else
                                <button @click="window.blockUser({{ $comment->user_id }}, `{{ addslashes($comment->comment) }}`); menuOpen = false" class="w-full px-4 py-2 text-left text-[13px] font-bold hover:bg-gray-50 dark:hover:bg-white/5 flex items-center gap-3 transition-colors dark:text-white">
                                    <span class="material-symbols-rounded text-[18px]">block</span>
                                    <span>Block User</span>
                                </button>
                                <button @click="window.reportComment({{ $comment->id }}); menuOpen = false" class="w-full px-4 py-2 text-left text-[13px] font-bold hover:bg-gray-50 dark:hover:bg-white/5 flex items-center gap-3 transition-colors dark:text-white">
                                    <span class="material-symbols-rounded text-[18px]">flag</span>
                                    <span>Report</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Reply Form -->
            <div x-show="replying" x-collapse class="mt-4 mb-6">
                <div class="flex gap-4">
                    @auth
                    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center shrink-0 uppercase overflow-hidden border border-white/10 shadow-inner">
                        @if(auth()->user()->channel?->avatar)
                            <img src="{{ getImage(getFilePath('channelAvatar') . '/' . auth()->user()->channel->avatar) }}" class="w-full h-full object-cover">
                        @elseif(auth()->user()->image)
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . auth()->user()->image) }}" class="w-full h-full object-cover">
                        @else
                            @php
                                $name = auth()->user()->channel?->name ?? auth()->user()->name;
                                $words = explode(' ', trim($name));
                                $initials = count($words) >= 2 
                                    ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                    : strtoupper(substr($name, 0, 2));
                            @endphp
                            <span class="text-[10px] font-black text-gray-500 dark:text-white">{{ $initials }}</span>
                        @endif
                    </div>
                    <div class="flex-1 flex flex-col gap-2" x-data="{ 
                        replyEmojisOpen: false,
                        replyEmojiList: window.EMOJI_LIST || []
                    }">
                        <div class="relative">
                            <textarea x-ref="replyText" rows="1" @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'" class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-white/10 focus:border-red-600 focus:ring-0 px-0 py-2 pr-10 text-[13px] text-gray-900 dark:text-white resize-none" placeholder="Add a reply..."></textarea>
                            <button type="button" @click="replyEmojisOpen = !replyEmojisOpen" class="absolute right-0 top-2 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-colors" title="Add emoji">
                                <span class="material-symbols-rounded text-[18px] text-gray-400">emoji_emotions</span>
                            </button>
                            <div x-show="replyEmojisOpen" x-transition @click.away="replyEmojisOpen = false" class="absolute bottom-full mb-2 right-0 w-[280px] bg-white dark:bg-[#1A1A1A] border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden">
                                <div class="p-1.5 border-b border-gray-100 dark:border-white/5">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2">Emoji</span>
                                </div>
                                <div class="p-2 grid grid-cols-8 gap-0.5 max-h-[220px] overflow-y-auto">
                                    <template x-for="(emoji, idx) in replyEmojiList" :key="idx">
                                        <button type="button" @click="$refs.replyText.focus(); const el = $refs.replyText; const start = el.selectionStart; const end = el.selectionEnd; el.value = el.value.slice(0,start) + emoji + el.value.slice(end); el.selectionStart = el.selectionEnd = start + emoji.length; replyEmojisOpen = false;" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 cursor-pointer transition-colors text-lg" x-text="emoji"></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button @click="replying = false" type="button" class="px-4 py-1.5 rounded-full font-bold text-[12px] text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">Cancel</button>
                            <button @click="submitReply($refs.replyText.value, {{ $comment->id }}); $refs.replyText.value = ''; replying = false" type="button" class="px-4 py-1.5 bg-red-600 text-white rounded-full font-bold text-[12px] hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">Reply</button>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Replies List -->
    @if($comment->replies->count() > 0)
        <div class="mt-2">
            <button @click="showReplies = !showReplies" class="flex items-center gap-2 text-blue-600 dark:text-blue-400 text-[13px] font-bold hover:bg-blue-50 dark:hover:bg-blue-900/20 px-3 py-1.5 rounded-full transition-colors">
                <span class="material-symbols-rounded transform transition-transform" :class="showReplies ? 'rotate-180' : ''">expand_more</span>
                <span x-text="showReplies ? 'Hide' : 'View'"></span> {{ $comment->replies->count() }} {{ $comment->replies->count() == 1 ? 'reply' : 'replies' }}
            </button>
            
            <div x-show="showReplies" x-collapse class="mt-4 space-y-6">
                @foreach($comment->replies as $reply)
                    @include('frontend.partials.comment_item', ['comment' => $reply])
                @endforeach
                <div class="reply-container-{{ $comment->id }} space-y-6"></div>
            </div>
        </div>
    @else
        <div class="reply-container-{{ $comment->id }} mt-4 space-y-6"></div>
    @endif
</div>
