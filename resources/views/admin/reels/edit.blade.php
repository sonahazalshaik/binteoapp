@extends('admin.layouts.app')
@section('title', 'Edit Reel')
@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-8">
            <a href="{{ route('admin.reels.index') }}" class="text-sm font-bold text-slate-400 hover:text-orange-500 transition-colors flex items-center gap-1 mb-4">
                <span class="material-symbols-rounded text-sm">arrow_back</span> Back to Reels
            </a>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">{{ $pageTitle }}</h1>
        </div>

        <form action="{{ route('admin.reels.update', $reel->id) }}"
              method="POST"
              enctype="multipart/form-data"
              @submit="addTag(); window.showPublishingLoader('reel')"
              class="space-y-8"
              x-data="reelEditor()">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Left Column --}}
                <div class="space-y-8">
                    {{-- Basic Info --}}
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[2rem] border border-slate-200 dark:border-white/10 p-8 space-y-6">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 dark:text-white/20">Basic Information</h3>
                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Title</label>
                            <input type="text" name="title" required value="{{ old('title', $reel->title) }}" class="w-full h-14 px-5 bg-slate-50 dark:bg-white/5 border-0 rounded-2xl text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-orange-500">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">URL Slug</label>
                            <input type="text" name="slug" required value="{{ old('slug', $reel->slug) }}" class="w-full h-14 px-5 bg-slate-50 dark:bg-white/5 border-0 rounded-2xl text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-orange-500">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Description <span class="text-orange-500">#hashtags @mentions</span></label>
                            <textarea name="description" rows="4" class="w-full px-5 py-4 bg-slate-50 dark:bg-white/5 border-0 rounded-2xl text-slate-900 dark:text-white font-medium resize-none focus:ring-2 focus:ring-orange-500">{{ old('description', $reel->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Category</label>
                                <select name="category_id" class="w-full h-14 px-5 bg-slate-50 dark:bg-white/5 border-0 rounded-2xl font-bold text-sm">
                                    <option value="">None</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $reel->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Language</label>
                                <select name="language" required class="w-full h-14 px-5 bg-slate-50 dark:bg-white/5 border-0 rounded-2xl font-bold text-sm">
                                    @foreach(config('languages') as $code => $name)
                                        <option value="{{ $name }}" {{ old('language', $reel->language) == $name ? 'selected' : ($name == 'English' && !$reel->language ? 'selected' : '') }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Visibility</label>
                                <select name="visibility" class="w-full h-14 px-5 bg-slate-50 dark:bg-white/5 border-0 rounded-2xl font-bold">
                                    <option value="0" {{ $reel->visibility == 0 ? 'selected' : '' }}>Public — Global Access</option>
                                    <option value="1" {{ $reel->visibility == 1 ? 'selected' : '' }}>Private — Restricted</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Status</label>
                                <select name="status" class="w-full h-14 px-5 bg-slate-50 dark:bg-white/5 border-0 rounded-2xl font-bold">
                                    <option value="0" {{ $reel->status == 0 ? 'selected' : '' }}>Draft</option>
                                    <option value="1" {{ $reel->status == 1 ? 'selected' : '' }}>Published</option>
                                    <option value="2" {{ $reel->status == 2 ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Comments</label>
                                <select name="allow_comments" class="w-full h-14 px-5 bg-slate-50 dark:bg-white/5 border-0 rounded-2xl font-bold">
                                    <option value="1" {{ $reel->allow_comments ? 'selected' : '' }}>Enabled</option>
                                    <option value="0" {{ !$reel->allow_comments ? 'selected' : '' }}>Disabled</option>
                                </select>
                            </div>
                            <div class="space-y-2">

                            </div>
                        </div>

                        <div class="flex items-center gap-8 pt-2">
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="is_age_restricted" value="1" class="sr-only peer" {{ $reel->is_age_restricted ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 dark:bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500 shadow-inner"></div>
                                <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white transition-colors">18+ Restricted</span>
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="is_trending" value="1" class="sr-only peer" {{ $reel->is_trending ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 dark:bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500 shadow-inner"></div>
                                <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white transition-colors">Trending</span>
                            </label>
                        </div>
                    </div>

                    {{-- Hashtags / Tags --}}
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[2rem] border border-slate-200 dark:border-white/10 p-8 space-y-6">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 dark:text-white/20">Classification</h3>
                        
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Hashtags</label>
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1 group">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-6 pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                                        <span class="material-symbols-rounded">tag</span>
                                    </div>
                                    <input type="text" x-model="tagInput"
                                           @keydown.enter.prevent="addTag()"
                                           @keydown.comma.prevent="addTag()"
                                           @input="if(tagInput.endsWith(',') || tagInput.endsWith(' ')) { addTag(); }"
                                           @blur="addTag()"
                                           class="w-full h-14 pl-14 pr-6 bg-slate-50 dark:bg-black/20 border border-slate-200/60 dark:border-white/5 rounded-2xl text-[13px] font-black text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none transition-all"
                                           placeholder="Type a hashtag...">
                                </div>
                                <button type="button"
                                        @click="addTag()"
                                        class="h-14 w-14 lg:w-auto lg:px-6 bg-orange-600 hover:bg-orange-700 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all active:scale-[0.95] flex items-center justify-center lg:gap-1 shrink-0">
                                    <span class="material-symbols-rounded text-sm">add</span>
                                    <span class="hidden lg:inline">Add</span>
                                </button>
                            </div>
                            <div class="flex flex-wrap gap-2 mt-2 items-center">
                                <template x-for="(tag, i) in tags" :key="i">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/10 text-orange-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-orange-500/20">
                                        <span x-text="tag"></span>
                                        <button type="button" @click="tags.splice(i, 1)" class="hover:text-red-500 transition-colors">
                                            <span class="material-symbols-rounded text-sm">close</span>
                                        </button>
                                        <input type="hidden" name="hashtags[]" :value="tag">
                                    </span>
                                </template>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-white/5 space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Tags</label>
                            <div x-data="{ adminTags: {{ json_encode($reel->tags->pluck('tag')->toArray()) }}, adminTagInput: '' }">
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <template x-for="(tag, i) in adminTags" :key="i">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 dark:bg-purple-500/20 text-purple-700 rounded-full text-[10px] font-black uppercase tracking-widest">
                                            <span x-text="tag"></span>
                                            <input type="hidden" name="tags[]" :value="tag">
                                            <button type="button" @click="adminTags.splice(i,1)" class="hover:text-red-500 text-lg line-height-1">&times;</button>
                                        </span>
                                    </template>
                                </div>
                                <input type="text" x-model="adminTagInput" @keydown.enter.prevent="if(adminTagInput.trim()){adminTags.push(adminTagInput.trim());adminTagInput='';}" class="w-full h-12 px-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm" placeholder="Add tag + Enter">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-8">
                    {{-- Music & Audio --}}
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[2rem] border border-slate-200 dark:border-white/10 p-8 space-y-6">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 dark:text-white/20">Music & Audio</h3>

                        <div class="flex p-1 bg-slate-50 dark:bg-black/20 rounded-2xl border border-slate-100 dark:border-white/5">
                            <button type="button" @click="musicSource = 'library'" :class="musicSource == 'library' ? 'bg-orange-500 text-white shadow-lg' : 'text-slate-400'" class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">Library</button>
                            <button type="button" @click="musicSource = 'global'" :class="musicSource == 'global' ? 'bg-orange-500 text-white shadow-lg' : 'text-slate-400'" class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">Search</button>
                            <button type="button" @click="musicSource = 'original'" :class="musicSource == 'original' ? 'bg-orange-500 text-white shadow-lg' : 'text-slate-400'" class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">Original</button>
                        </div>

                        <input type="hidden" name="music_source" :value="musicSource">

                        <div class="space-y-4">
                            <div x-show="musicSource == 'library'">
                                <select name="music_id" class="w-full h-12 px-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm">
                                    <option value="">No library music</option>
                                    @foreach($musicTracks as $track)<option value="{{ $track->id }}" {{ $reel->music_id == $track->id ? 'selected' : '' }}>{{ $track->title }} — {{ $track->artist ?? 'Unknown' }}</option>@endforeach
                                </select>
                            </div>

                            <div x-show="musicSource == 'global'" class="space-y-3">
                                <div class="relative">
                                    <input type="text" x-model="musicSearchQuery" @input.debounce.500ms="searchMusic()" class="w-full h-12 pl-12 pr-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm" placeholder="Search iTunes Music...">
                                    <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                                </div>

                                <div x-show="musicSearchResults.length > 0" class="max-h-[250px] overflow-y-auto space-y-2 custom-scrollbar pr-2">
                                    <template x-for="song in musicSearchResults" :key="song.id">
                                        <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/10 hover:border-orange-500 transition-all group">
                                            <img :src="song.thumbnail" class="w-10 h-10 rounded-lg shadow-md">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[11px] font-black dark:text-white truncate" x-text="song.title"></p>
                                                <p class="text-[9px] text-slate-400 font-bold truncate" x-text="song.artist"></p>
                                            </div>
                                            <button type="button" @click="toggleMusicPreview(song)" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/10 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-orange-100 hover:text-orange-600 transition-all">
                                                <span class="material-symbols-rounded text-sm" x-text="previewingId == song.id ? 'pause' : 'play_arrow'"></span>
                                            </button>
                                            <button type="button" @click="selectGlobalMusic(song)" :class="globalMusic?.id == song.id ? 'bg-orange-500 text-white' : 'bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-400'" class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">
                                                <span x-text="globalMusic?.id == song.id ? 'Selected' : 'Select'"></span>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="globalMusic">
                                    <input type="hidden" name="global_music_url" :value="globalMusic?.url">
                                    <input type="hidden" name="global_music_title" :value="globalMusic?.title">
                                    <input type="hidden" name="global_music_artist" :value="globalMusic?.artist">
                                    <input type="hidden" name="global_music_thumbnail" :value="globalMusic?.thumbnail">
                                    <div class="p-3 bg-orange-50 dark:bg-orange-500/10 rounded-2xl border border-orange-100 dark:border-orange-500/20 flex items-center gap-3">
                                        <img :src="globalMusic?.thumbnail" class="w-8 h-8 rounded-lg" x-show="globalMusic?.thumbnail">
                                        <div class="flex-1">
                                            <p class="text-[10px] font-black text-orange-700 dark:text-orange-300" x-text="globalMusic?.title"></p>
                                            <p class="text-[8px] font-bold text-orange-400" x-text="globalMusic?.artist"></p>
                                        </div>
                                        <button type="button" @click="globalMusic = null" class="text-orange-400 hover:text-orange-600">&times;</button>
                                    </div>
                                </div>
                            </div>

                            <div x-show="musicSource == 'original'" class="space-y-3">
                                <div class="relative">
                                    <input type="number" name="original_reel_id" value="{{ $reel->original_reel_id }}" class="w-full h-12 px-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm" placeholder="Enter Reel ID to use its audio...">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 mt-4 pt-4 border-t border-slate-100 dark:border-white/5">
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 block">Start Time (sec)</label>
                                    <input type="number" name="music_start_time" step="0.1" value="{{ $reel->music_start_time }}" class="w-full h-12 px-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Thumbnail --}}
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[2rem] border border-slate-200 dark:border-white/10 p-8 space-y-6">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 dark:text-white/20">Media</h3>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Thumbnail</label>

                            @if($reel->thumbnail_path)
                                <div class="relative w-48 aspect-[9/16] rounded-2xl overflow-hidden group/thumb border border-slate-200 dark:border-white/10">
                                    <img src="{{ $reel->getThumbnailUrl() }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover/thumb:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="button"
                                                onclick="confirmDelete('{{ route('admin.reels.thumbnail.destroy', $reel->id) }}', 'Delete this thumbnail?')"
                                                class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                                            <span class="material-symbols-rounded">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-500/60">{{ $reel->thumbnail_path ? 'Change Thumbnail' : 'Upload Thumbnail' }}</label>
                                <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-orange-100 file:text-orange-700 file:font-bold">
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-400 space-y-2">
                            <p>Duration: <span class="text-slate-900 dark:text-white">{{ gmdate('i:s', $reel->duration) }}</span></p>
                            <p>Views: <span class="text-slate-900 dark:text-white">{{ number_format($reel->views_count) }}</span></p>
                            <p>Status: <span class="text-orange-500">{{ $reel->status == 1 ? 'PUBLISHED' : ($reel->status == 0 ? 'DRAFT' : 'REJECTED') }}</span></p>
                            @if($reel->user)
                                <p>Creator: <span class="text-slate-900 dark:text-white">{{ $reel->user->username }}</span></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.reels.index') }}" class="h-12 px-6 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white font-bold text-xs uppercase rounded-xl flex items-center">Cancel</a>
                <button type="submit" class="h-12 px-8 bg-gradient-to-r from-orange-600 to-amber-500 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow-lg">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@push('script')
<script>
    function reelEditor() {
        return {
            musicSource: '{{ $reel->music_source ?? "library" }}',
            musicId: '{{ $reel->music_id }}',
            musicSearchQuery: '',
            musicSearchResults: [],
            searchingMusic: false,
            globalMusic: {!! $reel->global_music_url ? json_encode([
                'id' => 'existing',
                'title' => $reel->global_music_title,
                'artist' => $reel->global_music_artist,
                'thumbnail' => $reel->global_music_thumbnail,
                'url' => $reel->global_music_url
            ]) : 'null' !!},
            previewingId: null,
            audioPlayer: null,

            tags: {{ json_encode($reel->hashtags->pluck('hashtag')->values()->toArray()) }},
            tagInput: '',

            addTag() {
                let raw = this.tagInput.trim();
                if (!raw) return;
                let parts = raw.split(/[\s,]+/).map(p => p.replace(/#/g, '').trim()).filter(p => p.length > 0);
                parts.forEach(part => {
                    if (!this.tags.includes(part)) {
                        this.tags.push(part);
                    }
                });
                this.tagInput = '';
            },

            async searchMusic() {
                if (!this.musicSearchQuery) {
                    this.musicSearchResults = [];
                    return;
                }
                this.searchingMusic = true;
                try {
                    const response = await fetch(`https://itunes.apple.com/search?term=${encodeURIComponent(this.musicSearchQuery)}&entity=song&limit=15`);
                    const data = await response.json();
                    this.musicSearchResults = data.results.map(item => ({
                        id: item.trackId,
                        title: item.trackName,
                        artist: item.artistName,
                        thumbnail: item.artworkUrl100,
                        url: item.previewUrl
                    }));
                } catch (err) {
                    console.error("Music search failed:", err);
                } finally {
                    this.searchingMusic = false;
                }
            },

            toggleMusicPreview(song) {
                if (this.previewingId == song.id) {
                    this.audioPlayer.pause();
                    this.previewingId = null;
                } else {
                    if (this.audioPlayer) this.audioPlayer.pause();
                    this.audioPlayer = new Audio(song.url);
                    this.audioPlayer.play();
                    this.previewingId = song.id;
                    this.audioPlayer.onended = () => this.previewingId = null;
                }
            },

            selectGlobalMusic(song) {
                this.globalMusic = song;
                if (this.audioPlayer) this.audioPlayer.pause();
                this.previewingId = null;
            }
        }
    }
</script>
@endpush
@endsection
