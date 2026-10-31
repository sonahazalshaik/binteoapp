@extends('admin.layouts.app')

@section('title', 'Create New Playlist')
@section('header_title', 'Add Playlist')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-700 pb-24">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-6 lg:px-0">
        <div>
            <h3 class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Create Playlist</h3>
            <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Set up a new video collection for users</p>
        </div>
        <a href="{{ route('admin.playlist.index') }}" class="h-12 px-6 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 flex items-center gap-3 text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-black transition-all group">
            <span class="material-symbols-rounded text-lg transition-transform group-hover:-translate-x-1">arrow_back</span>
            <span>Back to Playlists</span>
        </a>
    </div>

    <!-- Form Card -->
    <form action="{{ route('admin.playlist.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white/60 dark:bg-white/5 backdrop-blur-3xl border border-white dark:border-white/10 rounded-[2.5rem] p-8 lg:p-12 shadow-2xl space-y-10">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- User Selection -->
                <div class="space-y-4">
                    <x-select name="user_id" label="Playlist Owner" icon="person" required="true" hint="Identify the user account this playlist belongs to.">
                        <option value="">@lang('Select User / Owner...')</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->fullname }} (@<span>{{ $user->username }}</span>)
                            </option>
                        @endforeach
                    </x-select>
                </div>

                <!-- Title -->
                <div class="space-y-4">
                    <x-input name="title" label="Playlist Title" placeholder="e.g., My Favorite Tech Videos" icon="label" required="true" hint="The name that will be displayed for this playlist." />
                </div>
            </div>

            <!-- Content Selection (Dynamic) -->
            <div id="content_selection_section" class="hidden space-y-8 animate-in fade-in slide-in-from-top-4 duration-500 pt-10 border-t border-slate-100 dark:border-white/5">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-none">Select Content</h4>
                        <p class="text-[9px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.2em] mt-2">Populate this playlist with existing videos & reels</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="selected_count" class="px-3 py-1 rounded-lg bg-orange-500 text-white text-[9px] font-black uppercase tracking-widest ">0 Selected</span>
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
                <x-textarea name="description" label="Playlist Description" placeholder="Briefly describe what this playlist is about..." icon="description" hint="Describe the collection of videos in this playlist." />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 pt-6 border-t border-slate-100 dark:border-white/5">
                <!-- Visibility -->
                <div class="space-y-4">
                    <x-select name="visibility" label="Privacy Status" icon="visibility" required="true" hint="Choose who can see this playlist on the platform.">
                        <option value="0" {{ old('visibility') == '0' ? 'selected' : '' }}>@lang('Public - Visible to Everyone')</option>
                        <option value="1" {{ old('visibility') == '1' ? 'selected' : '' }}>@lang('Private - Only Visible to Owner')</option>
                    </x-select>
                </div>

                <!-- Economics -->
                @if (gs('is_playlist_sell'))
                <div class="space-y-6" x-data="{ isForSale: {{ old('playlist_subscription') ? 'true' : 'false' }} }">
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
                        <x-input name="price" type="number" step="any" label="Playlist Price" placeholder="0.00" icon="payments" hint="Set the price users pay to access this playlist." />
                    </div>
                </div>
                @endif
            </div>


            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 pt-10">
                <button type="reset" class="h-14 px-8 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 text-[11px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition-all active:scale-95">
                    @lang('Reset Form')
                </button>
                <button type="submit" class="h-14 px-10 rounded-2xl bg-orange-500 text-white flex items-center gap-3 text-[11px] font-black uppercase tracking-widest hover:scale-[1.02] transition-all shadow-xl shadow-orange-500/20 active:scale-95 group">
                    <span>Create Playlist</span>
                    <span class="material-symbols-rounded text-lg transition-transform group-hover:translate-x-1">rocket_launch</span>
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

        const userSelect = $('select[name="user_id"]');
        const section = $('#content_selection_section');
        const videosGrid = $('#videos_grid');
        const reelsGrid = $('#reels_grid');
        const videosContainer = $('#videos_container');
        const reelsContainer = $('#reels_container');
        const noContentMsg = $('#no_content_message');
        const selectedCountBadge = $('#selected_count');

        userSelect.on('change', function() {
            const userId = $(this).val();
            if (!userId) {
                section.addClass('hidden');
                return;
            }

            // Show loading state or just section
            section.removeClass('hidden');
            videosGrid.empty();
            reelsGrid.empty();
            videosContainer.addClass('hidden');
            reelsContainer.addClass('hidden');
            noContentMsg.addClass('hidden');

            $.ajax({
                url: "{{ route('admin.playlist.user-videos', ':id') }}".replace(':id', userId),
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        let hasVideos = response.videos && response.videos.length > 0;
                        let hasReels = response.reels && response.reels.length > 0;

                        if (hasVideos) {
                            videosContainer.removeClass('hidden');
                            response.videos.forEach(video => {
                                videosGrid.append(createItemCard(video, 'video'));
                            });
                        }

                        if (hasReels) {
                            reelsContainer.removeClass('hidden');
                            response.reels.forEach(reel => {
                                reelsGrid.append(createItemCard(reel, 'reel'));
                            });
                        }

                        if (!hasVideos && !hasReels) {
                            noContentMsg.removeClass('hidden');
                        }
                    }
                }
            });
        });

        function createItemCard(item, type) {
            const name = type === 'video' ? 'video_ids[]' : 'reel_ids[]';
            const thumb = item.thumbnail || '';
            
            return `
                <label class="relative group cursor-pointer">
                    <input type="checkbox" name="${name}" value="${item.id}" class="sr-only peer content-checkbox">
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

