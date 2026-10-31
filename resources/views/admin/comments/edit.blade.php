@extends('admin.layouts.app')

@section('title', 'Edit Comment')
@section('header_title', 'Comment Management')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-1000 pb-24">
    <div class="flex items-center justify-between px-6 lg:px-0">
        <div>
            <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Edit Comment</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3 leading-relaxed">Update comment details for ID: #{{ $comment->id }}</p>
        </div>
        <a href="{{ route('admin.comments.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-all shadow-sm active:scale-90">
            <span class="material-symbols-rounded text-xl">close</span>
        </a>
    </div>

    <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST" 
          x-data="{ 
            synching: false, 
            open: false, 
            search: '', 
            selectedId: '{{ $comment->user_id }}', 
            selectedName: '{{ $comment->user?->fullname }}',
            selectUser(id, name) {
                this.selectedId = id;
                this.selectedName = name;
                this.open = false;
                this.search = '';
                // Trigger content load
                loadContent(id);
            }
          }" 
          @submit="synching = true"
          class="space-y-8">
        @csrf
        <input type="hidden" name="user_id" :value="selectedId" required>
        <input type="hidden" name="comment_type" id="comment_type_input" value="{{ $comment->comment_type }}">
        <input type="hidden" name="old_type" value="{{ $comment->comment_type }}">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Panel: User Selection -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <span class="material-symbols-rounded text-lg">person</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Select User</h3>
                        </div>

                        <!-- Custom Searchable Select -->
                        <div class="space-y-2" @click.away="open = false">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Comment Author</label>
                            
                            <div class="relative">
                                <button type="button" 
                                        @click="open = !open"
                                        class="w-full h-14 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-5 flex items-center justify-between text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                    <span x-text="selectedName" class="truncate">Select User...</span>
                                    <span class="material-symbols-rounded text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                                </button>

                                <!-- Dropdown Panel -->
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="absolute z-[100] mt-2 w-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden"
                                     x-cloak>
                                    
                                    <div class="p-3 border-b border-slate-100 dark:border-white/5">
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-rounded text-slate-400 text-sm">search</span>
                                            <input type="text" x-model="search" placeholder="Search user..." 
                                                   class="w-full h-10 bg-slate-50 dark:bg-black/20 border-none rounded-xl pl-10 pr-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-1 focus:ring-blue-500/50 transition-all">
                                        </div>
                                    </div>

                                    <div class="max-h-60 overflow-y-auto p-2 space-y-1 scrollbar-hide">
                                        @foreach($users as $user)
                                            <button type="button"
                                                    x-show="search === '' || '{{ strtolower($user->username) }}'.includes(search.toLowerCase()) || '{{ strtolower($user->fullname) }}'.includes(search.toLowerCase())"
                                                    @click="selectUser('{{ $user->id }}', '{{ $user->fullname }}')"
                                                    class="w-full text-left p-3 rounded-xl hover:bg-blue-600 group transition-all flex items-center gap-3"
                                                    :class="selectedId == '{{ $user->id }}' ? 'bg-blue-500/10' : ''">
                                                <div class="w-8 h-8 rounded-full overflow-hidden border border-slate-200 dark:border-white/10 flex-shrink-0 relative">
                                                    @if($user->image)
                                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full bg-blue-600 flex items-center justify-center text-[10px] font-black text-white ">
                                                            {{ strtoupper(substr($user->firstname, 0, 1) . substr($user->lastname, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex flex-col flex-grow">
                                                    <span class="text-[11px] font-bold group-hover:text-white transition-colors"
                                                          :class="selectedId == '{{ $user->id }}' ? 'text-blue-500' : 'text-slate-900 dark:text-white'">{{ $user->fullname }}</span>
                                                    <span class="text-[9px] font-medium text-slate-400 dark:text-white/30 group-hover:text-white/60 transition-colors mt-0.5">@<span>{{ $user->username }}</span></span>
                                                </div>
                                                <span class="material-symbols-rounded text-blue-500 group-hover:text-white transition-all text-sm" x-show="selectedId == '{{ $user->id }}'">check</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-orange-500">
                            <span class="material-symbols-rounded">info</span>
                            <span class="text-[10px] font-black uppercase tracking-widest">Context</span>
                        </div>
                        <p class="text-[11px] text-slate-400 dark:text-white/30 leading-relaxed font-medium"> Modifying a comment's author or target content will re-synchronize its position in the community timeline.</p>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Content & Comment -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Content Selection Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                    <span class="material-symbols-rounded text-lg">movie</span>
                                </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Select Video/Reel</h3>
                    </div>
                </div>

                        <div id="content_selection_section" class="space-y-8 hidden animate-in fade-in slide-in-from-top-4 duration-500">
                            <div class="space-y-10">
                                <!-- Videos Grid -->
                                <div id="videos_container" class="space-y-4 hidden">
                                    <h5 class="text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                        <span class="material-symbols-rounded text-sm">movie</span>
                                        Videos
                                    </h5>
                                    <div id="videos_grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
                                </div>

                                <!-- Reels Grid -->
                                <div id="reels_container" class="space-y-4 hidden">
                                    <h5 class="text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                        <span class="material-symbols-rounded text-sm">reorder</span>
                                        Reels
                                    </h5>
                                    <div id="reels_grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
                                </div>
                                
                                <div id="no_content_message" class="hidden py-16 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[2rem]">
                                    <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-4xl">inventory_2</span>
                                    <p class="mt-4 text-[10px] font-black text-slate-400 uppercase tracking-widest ">User has no published content</p>
                                </div>
                            </div>
                        </div>

                        <div id="selection_placeholder" class="py-20 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[2rem]">
                            <span class="material-symbols-rounded text-slate-100 dark:text-white/5 text-5xl">person_search</span>
                            <p class="mt-4 text-[10px] font-black text-slate-300 dark:text-white/10 uppercase tracking-widest ">Loading context...</p>
                        </div>
                    </div>
                </div>

                <!-- Comment Content Card -->
                <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                <span class="material-symbols-rounded text-lg">chat_bubble</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Comment Message</h3>
                        </div>

                        <div class="space-y-6">
                            <x-textarea name="comment" label="Write Comment" placeholder="Update your comment message here..." rows="8" required="true" icon="edit_note" hint="Edit the comment text as needed.">
                                {{ old('comment', $comment->content) }}
                            </x-textarea>
                        </div>

                        <div class="flex pt-6 border-t border-slate-100 dark:border-white/5">
                            <button type="submit" :disabled="synching || !selectedId" 
                                    class="w-full h-16 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-black text-[12px] font-black uppercase tracking-[0.3em] hover:scale-[1.01] active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed shadow-2xl relative overflow-hidden group ">
                                <div class="absolute inset-0 bg-blue-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <span x-show="!synching" class="material-symbols-rounded relative z-10">update</span>
                                <span x-show="synching" class="material-symbols-rounded animate-spin relative z-10">sync</span>
                                <span x-text="synching ? 'Updating...' : 'Update Comment'" class="relative z-10">Update Comment</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script')
<script>
    const initialUserId = "{{ $comment->user_id }}";
    const currentTargetId = "{{ $comment->video_id ?? $comment->reel_id }}";
    const currentType = "{{ $comment->comment_type }}";

    $(document).ready(function() {
        if (initialUserId) {
            loadContent(initialUserId, currentTargetId, currentType);
        }
    });

    function loadContent(id, preSelectId = null, preSelectType = null) {
        const section = $('#content_selection_section');
        const placeholder = $('#selection_placeholder');
        const videosGrid = $('#videos_grid');
        const reelsGrid = $('#reels_grid');
        const videosContainer = $('#videos_container');
        const reelsContainer = $('#reels_container');
        const noContentMsg = $('#no_content_message');
        const commentTypeInput = $('#comment_type_input');

        section.removeClass('hidden');
        placeholder.addClass('hidden');
        videosGrid.empty();
        reelsGrid.empty();
        videosContainer.addClass('hidden');
        reelsContainer.addClass('hidden');
        noContentMsg.addClass('hidden');

        $.ajax({
            url: "{{ route('admin.playlist.user-videos', ':id') }}".replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let hasVideos = response.videos && response.videos.length > 0;
                    let hasReels = response.reels && response.reels.length > 0;

                    if (hasVideos) {
                        videosContainer.removeClass('hidden');
                        response.videos.forEach(video => {
                            const isChecked = preSelectId && video.id == preSelectId && (preSelectType || commentTypeInput.val()) === 'video';
                            videosGrid.append(createItemCard(video, 'video', isChecked));
                        });
                    }

                    if (hasReels) {
                        reelsContainer.removeClass('hidden');
                        response.reels.forEach(reel => {
                            const isChecked = preSelectId && reel.id == preSelectId && (preSelectType || commentTypeInput.val()) === 'reel';
                            reelsGrid.append(createItemCard(reel, 'reel', isChecked));
                        });
                    }

                    if (!hasVideos && !hasReels) {
                        noContentMsg.removeClass('hidden');
                    }
                }
            }
        });
    }

    function createItemCard(item, type, checked = false) {
        const name = type === 'video' ? 'video_id' : 'reel_id';
        const thumb = item.thumbnail || '';
        
        return `
            <label class="relative group cursor-pointer">
                <input type="radio" name="${name}" value="${item.id}" class="sr-only peer content-radio" data-type="${type}" ${checked ? 'checked' : ''}>
                <div class="bg-slate-50 dark:bg-black/20 border border-slate-100 dark:border-white/5 rounded-2xl p-4 transition-all peer-checked:border-orange-500 peer-checked:bg-orange-500/5 group-hover:border-orange-500/30">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-10 rounded-lg bg-white dark:bg-white/5 flex-shrink-0 overflow-hidden relative shadow-sm">
                            ${thumb ? `<img src="${thumb}" class="absolute inset-0 w-full h-full object-cover transition-transform group-hover:scale-110">` : `
                            <span class="absolute inset-0 flex items-center justify-center text-slate-300 dark:text-white/10">
                                <span class="material-symbols-rounded text-lg ">${type === 'video' ? 'movie' : 'reorder'}</span>
                            </span>`}
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tight truncate">${item.title || item.name}</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">${type.toUpperCase()}</p>
                        </div>
                    </div>
                    <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
                        <span class="material-symbols-rounded text-orange-500 text-lg">check_circle</span>
                    </div>
                </div>
            </label>
        `;
    }

    $(document).on('change', '.content-radio', function() {
        const type = $(this).data('type');
        $('#comment_type_input').val(type);
        
        const otherType = type === 'video' ? 'reel' : 'video';
        $(`.content-radio[data-type="${otherType}"]`).prop('checked', false);
    });
</script>
@endpush

