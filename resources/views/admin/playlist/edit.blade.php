@extends('admin.layouts.app')

@section('title', 'Edit Playlist Settings')
@section('header_title', 'Edit Playlist')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-700 pb-24">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-6 lg:px-0">
        <div>
            <h3 class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Edit Playlist</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Refining details for: {{ $playlist->title ?? $playlist->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.playlist.show', $playlist->id) }}" class="h-12 px-6 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 flex items-center gap-3 text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-black transition-all group">
                <span class="material-symbols-rounded text-lg transition-transform group-hover:scale-110">analytics</span>
                <span>Analytics</span>
            </a>
            <a href="{{ route('admin.playlist.index') }}" class="h-12 px-6 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 flex items-center gap-3 text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-black transition-all group">
                <span class="material-symbols-rounded text-lg transition-transform group-hover:-translate-x-1">arrow_back</span>
                <span>Back</span>
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <form action="{{ route('admin.playlist.update', $playlist->id) }}" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2.5rem] p-8 lg:p-12 shadow-2xl space-y-10">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Curator (Read-only Info) -->
                <div class="space-y-4">
                    <div class="p-6 rounded-3xl bg-slate-50 dark:bg-black/20 border border-slate-100 dark:border-white/5">
                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Playlist Owner</span>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <span class="material-symbols-rounded text-2xl ">person</span>
                            </div>
                            <div>
                                <span class="block text-[13px] font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $playlist->user?->fullname }}</span>
                                <span class="block text-[10px] font-bold text-orange-500 uppercase tracking-widest mt-0.5">@<span>{{ $playlist->user?->username }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <div class="space-y-4">
                    <x-input name="title" label="Playlist Title" value="{{ $playlist->title ?? $playlist->name }}" icon="label" required="true" hint="The public name displayed for this playlist." />
                </div>
            </div>

            <!-- Content Selection (Dynamic) -->
            <div id="content_selection_section" class="space-y-8 animate-in fade-in slide-in-from-top-4 duration-500 pt-10 border-t border-slate-100 dark:border-white/5">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-none">Modify Content</h4>
                        <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em] mt-2">Update the videos & reels associated with this collection</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="selected_count" class="px-3 py-1 rounded-lg bg-emerald-500 text-white text-[9px] font-black uppercase tracking-widest ">{{ count($playlist->videos) + count($playlist->reels) }} Selected</span>
                    </div>
                </div>

                <div class="space-y-10">
                    <!-- Videos Grid -->
                    <div id="videos_container" class="space-y-4 hidden">
                        <h5 class="text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm">movie</span>
                            Videos
                        </h5>
                        <div id="videos_grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
                    </div>

                    <!-- Reels Grid -->
                    <div id="reels_container" class="space-y-4 hidden">
                        <h5 class="text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm">reorder</span>
                            Reels
                        </h5>
                        <div id="reels_grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
                    </div>
                    
                    <div id="no_content_message" class="hidden py-20 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-[2.5rem]">
                        <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-5xl">inventory_2</span>
                        <p class="mt-4 text-[10px] font-black text-slate-400 uppercase tracking-widest ">This user has no published content</p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-4">
                <x-textarea name="description" label="Playlist Description" icon="description" hint="Describe the collection of videos in this playlist.">
                    {{ $playlist->description }}
                </x-textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 pt-6 border-t border-slate-100 dark:border-white/5">
                <!-- Visibility -->
                <div class="space-y-4">
                    <x-select name="visibility" label="Privacy Status" icon="visibility" required="true" hint="Choose who can see this playlist on the platform.">
                        <option value="0" {{ $playlist->visibility == '0' ? 'selected' : '' }}>@lang('Public - Visible to Everyone')</option>
                        <option value="1" {{ $playlist->visibility == '1' ? 'selected' : '' }}>@lang('Private - Only Visible to Owner')</option>
                    </x-select>
                </div>

                <!-- Economics -->
                @if (gs('is_playlist_sell'))
                <div class="space-y-6" x-data="{ isForSale: {{ $playlist->playlist_subscription ? 'true' : 'false' }} }">
                    <div class="flex items-center justify-between p-6 rounded-3xl bg-slate-50 dark:bg-black/20 border border-slate-100 dark:border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <span class="material-symbols-rounded text-xl ">monetization_on</span>
                            </div>
                            <div>
                                <span class="block text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-tight">Set Price / Sell Playlist</span>
                                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Allow users to buy this playlist</span>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="playlist_subscription" class="sr-only peer" x-model="isForSale">
                            <div class="w-12 h-6 bg-slate-200 dark:bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-500"></div>
                        </label>
                    </div>

                    <div x-show="isForSale" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <x-input name="price" type="number" step="any" label="Playlist Price" value="{{ getAmount($playlist->price) }}" icon="payments" hint="Set the price users pay to access this playlist." />
                    </div>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 pt-10">
                <button type="submit" class="h-14 px-10 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center gap-3 text-[11px] font-black uppercase tracking-widest hover:scale-[1.02] transition-all shadow-xl active:scale-95 group">
                    <span>Save Changes</span>
                    <span class="material-symbols-rounded text-lg transition-transform group-hover:rotate-12">sync</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script')
<script>
    (function($) {
        "use strict";

        const userId = "{{ $playlist->user_id }}";
        const existingVideoIds = @json($playlist->videos->pluck('id'));
        const existingReelIds = @json($playlist->reels->pluck('id'));

        const section = $('#content_selection_section');
        const videosGrid = $('#videos_grid');
        const reelsGrid = $('#reels_grid');
        const videosContainer = $('#videos_container');
        const reelsContainer = $('#reels_container');
        const noContentMsg = $('#no_content_message');
        const selectedCountBadge = $('#selected_count');

        // Load content on page load
        if (userId) {
            loadContent(userId);
        }

        function loadContent(id) {
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
                                const isChecked = existingVideoIds.includes(video.id);
                                videosGrid.append(createItemCard(video, 'video', isChecked));
                            });
                        }

                        if (hasReels) {
                            reelsContainer.removeClass('hidden');
                            response.reels.forEach(reel => {
                                const isChecked = existingReelIds.includes(reel.id);
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
            const name = type === 'video' ? 'video_ids[]' : 'reel_ids[]';
            const thumb = item.thumbnail || '';
            
            return `
                <label class="relative group cursor-pointer">
                    <input type="checkbox" name="${name}" value="${item.id}" class="sr-only peer content-checkbox" ${checked ? 'checked' : ''}>
                    <div class="bg-white dark:bg-black/20 border border-slate-100 dark:border-white/5 rounded-2xl p-4 transition-all peer-checked:border-orange-500 peer-checked:bg-orange-500/5 group-hover:border-orange-500/30">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-10 rounded-lg bg-slate-100 dark:bg-white/5 flex-shrink-0 overflow-hidden relative">
                                ${thumb ? `<img src="${thumb}" class="absolute inset-0 w-full h-full object-cover transition-transform group-hover:scale-110">` : `
                                <span class="absolute inset-0 flex items-center justify-center text-slate-300 dark:text-white/10">
                                    <span class="material-symbols-rounded text-lg">${type === 'video' ? 'movie' : 'reorder'}</span>
                                </span>`}
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tight truncate">${item.title || item.name}</p>
                                <p class="text-[7px] text-slate-400 mt-1 line-clamp-1">${item.description || 'No description available'}</p>
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

        $(document).on('change', '.content-checkbox', function() {
            const count = $('.content-checkbox:checked').length;
            selectedCountBadge.text(`${count} Selected`);
            if (count > 0) {
                selectedCountBadge.removeClass('bg-orange-500').addClass('bg-emerald-500');
            } else {
                selectedCountBadge.removeClass('bg-emerald-500').addClass('bg-orange-500');
            }
        });

    })(jQuery);
</script>
@endpush

