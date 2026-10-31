@extends('layouts.app')

@section('content')
<div class="transition-colors duration-500">
    
    <!-- Mobile/Tablet Content UI -->
    <div class="lg:hidden max-w-7xl mx-auto px-6 py-10">
        <div class="flex items-center justify-between mb-10">
             <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Saved Audios</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ number_format($savedAudios->total()) }} tracks in library</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @forelse($savedAudios as $saved)
            @php 
                $music = $saved->reelMusic; 
                if (str_starts_with($music->file_path, 'http')) {
                    $audioUrl = $music->file_path;
                } elseif (str_ends_with($music->file_path, '.mp4')) {
                    $audioUrl = asset(getFilePath('reel') . '/' . $music->file_path);
                } else {
                    $audioUrl = asset(getFilePath('reelMusic') . '/' . $music->file_path);
                }
            @endphp
            <div class="bg-white dark:bg-white/5 rounded-[2.5rem] border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden group"
                 x-data="{ playing: false, togglePlay() { if(this.playing) { this.$refs.player.pause(); this.playing = false; } else { document.querySelectorAll('audio.preview-audio').forEach(a => { a.pause(); a.currentTime = 0; a.dispatchEvent(new CustomEvent('stop')); }); let p = this.$refs.player.play(); if(p) p.catch(e => notify('error', 'Audio file not found')).then(() => this.playing = true); else this.playing = true; } } }"
                 x-on:stop="playing = false">
                <audio x-ref="player" class="preview-audio hidden" @ended="playing = false">
                    @if(str_contains($audioUrl, 'play_1080p.mp4'))
                        <source src="{{ str_replace('play_1080p.mp4', 'play_1080p.mp4', $audioUrl) }}" type="audio/mp4">
                        <source src="{{ str_replace('play_1080p.mp4', 'play_720p.mp4', $audioUrl) }}" type="audio/mp4">
                        <source src="{{ str_replace('play_1080p.mp4', 'play_480p.mp4', $audioUrl) }}" type="audio/mp4">
                        <source src="{{ str_replace('play_1080p.mp4', 'play_360p.mp4', $audioUrl) }}" type="audio/mp4">
                    @else
                        <source src="{{ $audioUrl }}" type="audio/mp4">
                    @endif
                </audio>
                <div class="p-6 flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-orange-500 to-rose-600 flex items-center justify-center shadow-lg shadow-orange-500/20 overflow-hidden relative">
                        @if($music->cover_image)
                            <img src="{{ getImage(getFilePath('reelMusic') . '/' . $music->cover_image) }}" class="w-full h-full object-cover">
                        @else
                            <span class="material-symbols-rounded text-white text-3xl">music_note</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-black text-slate-900 dark:text-white truncate uppercase tracking-tight">{{ $music->title }}</h4>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $music->artist ?? 'Original Audio' }}</p>
                    </div>
                </div>
                
                <div class="px-6 pb-6 flex items-center gap-2">
                    <a href="{{ route('reels.create', ['music' => $music->slug]) }}" class="flex-1 py-4 gradient-orange text-white text-center rounded-2xl font-black text-[9px] uppercase tracking-widest shadow-lg shadow-orange-500/20 active:scale-95 transition-all">Use Audio</a>
                    <button type="button" @click="togglePlay()" class="w-12 h-12 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-white rounded-2xl flex items-center justify-center active:scale-95 transition-all border border-slate-200 dark:border-white/5">
                        <span class="material-symbols-rounded material-symbols-filled" x-text="playing ? 'pause' : 'play_arrow'"></span>
                    </button>
                    <button type="button" @click="toggleSave('{{ $music->id }}', $el)" class="w-12 h-12 bg-red-50 dark:bg-red-500/10 text-red-500 rounded-2xl flex items-center justify-center active:scale-95 transition-all border border-red-100 dark:border-white/5">
                        <span class="material-symbols-rounded material-symbols-filled">bookmark</span>
                    </button>
                </div>
            </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-white/10 mb-6">
                        <span class="material-symbols-rounded text-4xl">music_off</span>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">Your library is empty</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-xs">Save audio from reels to quickly use them in your own creations.</p>
                </div>
            @endforelse
        </div>

        @if($savedAudios->hasPages())
            <div class="pt-10">
                {{ $savedAudios->links() }}
            </div>
        @endif
    </div>

    <!-- Desktop Content UI -->
    <div class="mx-auto px-6 py-10 hidden lg:block">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Saved Audios</h1>
                <p class="text-slate-500 font-medium mt-1">Quickly access and use audio tracks you've saved from the Reels feed.</p>
            </div>
        </div>

        <div class="bg-white dark:bg-white/5 border border-slate-100 dark:border-white/10 rounded-[2.5rem] shadow-sm overflow-hidden mb-12">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b dark:border-white/5">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Track</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Artist</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Saved At</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-white/5">
                        @forelse($savedAudios as $saved)
                        @php 
                            $music = $saved->reelMusic; 
                            if (str_starts_with($music->file_path, 'http')) {
                                $audioUrl = $music->file_path;
                            } elseif (str_ends_with($music->file_path, '.mp4')) {
                                $audioUrl = asset(getFilePath('reel') . '/' . $music->file_path);
                            } else {
                                $audioUrl = asset(getFilePath('reelMusic') . '/' . $music->file_path);
                            }
                        @endphp
                        <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors"
                            x-data="{ playing: false, togglePlay() { if(this.playing) { this.$refs.player.pause(); this.playing = false; } else { document.querySelectorAll('audio.preview-audio').forEach(a => { a.pause(); a.currentTime = 0; a.dispatchEvent(new CustomEvent('stop')); }); let p = this.$refs.player.play(); if(p) p.catch(e => notify('error', 'Audio file not found')).then(() => this.playing = true); else this.playing = true; } } }"
                            x-on:stop="playing = false">
                            <td class="px-8 py-6">
                                <audio x-ref="player" class="preview-audio hidden" @ended="playing = false">
                                    @if(str_contains($audioUrl, 'play_1080p.mp4'))
                                        <source src="{{ str_replace('play_1080p.mp4', 'play_1080p.mp4', $audioUrl) }}" type="audio/mp4">
                                        <source src="{{ str_replace('play_1080p.mp4', 'play_720p.mp4', $audioUrl) }}" type="audio/mp4">
                                        <source src="{{ str_replace('play_1080p.mp4', 'play_480p.mp4', $audioUrl) }}" type="audio/mp4">
                                        <source src="{{ str_replace('play_1080p.mp4', 'play_360p.mp4', $audioUrl) }}" type="audio/mp4">
                                    @else
                                        <source src="{{ $audioUrl }}" type="audio/mp4">
                                    @endif
                                </audio>
                                <div class="flex items-center gap-4">
                                    <div class="relative w-14 h-14 rounded-xl bg-gradient-to-tr from-gray-100 to-gray-200 dark:from-white/5 dark:to-white/10 flex items-center justify-center overflow-hidden shrink-0 group/img">
                                        @if($music->cover_image)
                                            <img src="{{ getImage(getFilePath('reelMusic') . '/' . $music->cover_image) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="material-symbols-rounded text-slate-400">music_note</span>
                                        @endif
                                    </div>
                                    <p class="font-black text-slate-900 dark:text-white truncate max-w-[300px] uppercase tracking-tight">{{ $music->title }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ $music->artist ?? 'Original Audio' }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs font-bold text-slate-400">{{ $saved->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('reels.create', ['music' => $music->slug]) }}" class="h-10 px-6 rounded-xl gradient-orange text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                                        <span class="material-symbols-rounded text-base">movie</span>
                                        Use Audio
                                    </a>
                                    <button type="button" @click="togglePlay()" class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-white/5 text-slate-700 dark:text-white flex items-center justify-center border border-slate-200 dark:border-white/5 active:scale-95 transition-all" title="Play/Pause">
                                        <span class="material-symbols-rounded text-lg material-symbols-filled" x-text="playing ? 'pause' : 'play_arrow'"></span>
                                    </button>
                                    <button type="button" @click="toggleSave('{{ $music->id }}', $el)" class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center border border-red-100 dark:border-white/5 active:scale-95 transition-all" title="Remove">
                                        <span class="material-symbols-rounded text-lg material-symbols-filled">bookmark</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-center">
                                        <div class="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-300 dark:text-white/10 mb-6">
                                            <span class="material-symbols-rounded text-4xl">music_off</span>
                                        </div>
                                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">No saved audios</h3>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-xs">Tracks you save from Reels will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($savedAudios->hasPages())
                <div class="px-8 py-6 border-t dark:border-white/5">
                    {{ $savedAudios->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('script')
<script>
    async function toggleSave(musicId, btn) {
        // Find a reel that uses this music to get a slug for the route
        // Or we can just use a dedicated save-audio-direct route
        // For now, let's assume we need a slug. 
        // Better yet, let's add a direct save route by music ID.
        
        try {
            const res = await fetch(`/reels/save-audio-direct/${musicId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            
            if (data.is_saved === false) {
                // If on list page, maybe remove the row
                const row = btn.closest('tr') || btn.closest('.bg-white');
                row.style.opacity = '0.5';
                row.style.pointerEvents = 'none';
                setTimeout(() => row.remove(), 300);
            }
            
            notify('success', data.message);
        } catch (e) {
            notify('error', 'Something went wrong');
        }
    }
</script>
@endpush
@endsection
