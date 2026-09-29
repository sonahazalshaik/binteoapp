@extends('layouts.app')

@section('content')
<div id="studio-video-edit" class="min-h-screen bg-gray-50 dark:bg-[#0A0A0A] pb-40 transition-colors duration-500" x-init="initDuration()" x-data="{ 
    videoPreview: '{{ $video->hls_path ? asset($video->hls_path) : getVideo($video->video_path, $video) }}',
    thumbnailPreview: '{{ $video->thumbnail_path ? getImage(getFilePath('thumbnail') . '/' . $video->thumbnail_path) : '' }}',
    visibility: '{{ $video->status == \App\Constants\Status::DRAFT ? 'draft' : ($video->visibility === 'public' ? 0 : ($video->visibility === 'private' ? 1 : $video->visibility)) }}',
    isAgeRestricted: {{ $video->is_age_restricted ? 'true' : 'false' }},
    scheduleVideo: {{ $video->scheduled_at ? 'true' : 'false' }},
    scheduleDate: '{{ $video->scheduled_at ? $video->scheduled_at->timezone('Asia/Kolkata')->format('Y-m-d') : '' }}',
    scheduleTime: '{{ $video->scheduled_at ? $video->scheduled_at->timezone('Asia/Kolkata')->format('H:i') : '' }}',
    duration: '{{ $video->duration ?? '' }}',
    pricingTier: '{{ $video->pricing_tier > 0 ? (['free', 'premium', 'exclusive'][$video->pricing_tier] ?? 'free') : ($video->is_premium ? 'premium' : 'free') }}',
    price: '{{ (int) $video->price }}',
    hasPremiumAccess: {{ $hasPremiumAccess ? 'true' : 'false' }},
    uploading: false,
    tags: {{ json_encode($video->tags->pluck('tag')->values()->toArray()) }},
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
    removeTag(index) {
        this.tags.splice(index, 1);
    },

    formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return '00:00';
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = Math.floor(seconds % 60);
        if (h > 0) return `${h}:${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
        return `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
    },

    initDuration() {
        if(!this.duration || this.duration == '00:00') {
            const video = document.createElement('video');
            video.preload = 'metadata';
            video.onloadedmetadata = () => {
                this.duration = this.formatTime(video.duration);
            };
            video.src = this.videoPreview;
        }
    },

    onThumbnailChange(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                this.thumbnailPreview = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}">
    <!-- Professional Grid Overlay -->
    <div class="fixed inset-0 pointer-events-none opacity-[0.03] dark:opacity-[0.02] z-0" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="max-w-4xl mx-auto px-4 pt-10 relative z-10">
        <!-- Minimal Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter leading-tight">Edit Video</h1>
                <p class="text-slate-500 font-medium mt-2">Update your video details and settings.</p>
            </div>
            <a href="{{ route('studio.videos') }}" class="w-12 h-10 rounded-2xl bg-white dark:bg-white/5 border border-slate-100 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all shadow-sm">
                <span class="material-symbols-rounded">close</span>
            </a>
        </div>

        <form action="{{ route('studio.videos.update', $video) }}" 
              method="POST" 
              enctype="multipart/form-data" 
              @submit="addTag(); window.showPublishingLoader('video')"
              class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-10">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="duration" :value="duration">

            <div class="lg:col-span-8 space-y-4">
                <!-- Title & Details Card -->
                <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-4 sm:p-5 shadow-sm transition-all relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-[0.02] transform rotate-12">
                        <span class="material-symbols-rounded text-[120px] text-orange-500">edit_note</span>
                    </div>
                    
                    <h3 class="text-[10px] font-black text-orange-500 uppercase tracking-[0.2em] mb-6 relative z-10">Basic Details</h3>
                    
                    <div class="space-y-4 relative z-10">
                        <!-- Title -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest px-1" for="title">Video Title</label>
                            <input type="text" name="title" id="title" value="{{ $video->title }}" required 
                                   class="w-full h-9 px-4 bg-slate-50 dark:bg-black/10 border border-transparent dark:border-white/5 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 transition-all outline-none font-bold text-base"
                                   placeholder="Give your video a name" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Category -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest px-1" for="category_id">Category</label>
                                <div class="relative">
                                    <select name="category_id" id="category_id" required 
                                            class="w-full h-9 px-4 bg-slate-50 dark:bg-black/10 border border-transparent dark:border-white/5 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 outline-none font-bold">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ ($video->categories->first()?->id == $category->id || $video->category_id == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Language -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest px-1" for="language">Language</label>
                                <div class="relative">
                                    <select name="language" id="language" required 
                                            class="w-full h-9 px-4 bg-slate-50 dark:bg-black/10 border border-transparent dark:border-white/5 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 outline-none font-bold">
                                        @foreach(config('languages') as $code => $name)
                                            <option value="{{ $name }}" {{ $video->language == $name ? 'selected' : ($name == 'English' && !$video->language ? 'selected' : '') }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest px-1" for="location">Location (Optional)</label>
                                <input type="text" name="location" id="location" value="{{ $video->location }}" 
                                       class="w-full h-9 px-4 bg-slate-50 dark:bg-black/10 border border-transparent dark:border-white/5 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 outline-none font-bold"
                                       placeholder="Add location (optional)" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest px-1" for="description">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="w-full px-4 py-3 bg-slate-50 dark:bg-black/10 border border-transparent dark:border-white/5 rounded-[1.5rem] text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 transition-all outline-none resize-none font-medium leading-relaxed"
                                      placeholder="Tell viewers about your video">{{ $video->description }}</textarea>
                        </div>

                        <!-- Tag / Hashtag Chips Input -->
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest px-1">Hashtag</label>
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1 group">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                                        <span class="material-symbols-rounded">tag</span>
                                    </div>
                                    <input type="text" x-model="tagInput" 
                                           @keydown.enter.prevent="addTag()" 
                                           @keydown.comma.prevent="addTag()" 
                                           @input="if(tagInput.endsWith(',') || tagInput.endsWith(' ')) { addTag(); }"
                                           @blur="addTag()"
                                           class="w-full h-9 pl-10 pr-4 bg-slate-50 dark:bg-black/10 border border-transparent dark:border-white/5 rounded-2xl text-[12px] font-black text-slate-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/10 outline-none transition-all" 
                                           placeholder="Type a tag...">
                                </div>
                                <button type="button" 
                                        @click="addTag()" 
                                        class="h-9 w-12 lg:w-auto lg:px-4 bg-orange-500 hover:bg-orange-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-[0.95] flex items-center justify-center lg:gap-1 shrink-0">
                                    <span class="material-symbols-rounded text-sm">add</span>
                                    <span class="hidden lg:inline">Add</span>
                                </button>
                            </div>
                            <div class="flex flex-wrap gap-2 mt-2 items-center">
                                <template x-for="(tag, index) in tags" :key="index">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/10 text-orange-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-orange-500/20">
                                        <span x-text="tag"></span>
                                        <button type="button" @click="removeTag(index)" class="hover:text-red-500 transition-colors">
                                            <span class="material-symbols-rounded text-sm">close</span>
                                        </button>
                                        <input type="hidden" name="tags[]" :value="tag" />
                                    </span>
                                </template>
                            </div>
                            <span class="text-[10px] text-slate-400 px-1 font-medium block">Add tags to improve search ranking and discoverability.</span>
                        </div>

                        <!-- Captions Update -->
                        <div class="p-4 rounded-2xl bg-orange-500/[0.03] border border-orange-500/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl gradient-orange flex items-center justify-center text-white shadow-lg">
                                    <span class="material-symbols-rounded">subtitles</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tight">Subtitles</p>
                                    <p class="text-[9px] text-slate-400 font-bold max-w-[200px] truncate">{{ $video->captions_path ? 'Current File: ' . basename($video->captions_path) : 'No captions uploaded.' }}</p>
                                </div>
                            </div>
                            <label class="w-full sm:w-auto bg-white dark:bg-white/5 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest cursor-pointer hover:bg-orange-500 hover:text-white transition-all text-center border border-slate-200 dark:border-white/10 shadow-sm">
                                Upload File
                                <input type="file" name="captions" class="hidden" accept=".vtt,.srt" />
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Final Action Bar (Desktop only, below left column) -->
                <div class="gradient-orange rounded-3xl p-4 hidden lg:flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl shadow-orange-500/20">
                    <div class="text-white text-center sm:text-left">
                        <p class="font-black text-xl tracking-tight">Save Changes</p>
                        <p class="text-orange-100/70 text-[10px] font-bold uppercase tracking-widest mt-1">Updates will be applied instantly.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="confirmDelete('{{ route('studio.videos.destroy', $video) }}')" 
                                class="h-9 px-5 bg-white/20 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-red-500/80 active:scale-95 transition-all border border-white/20 flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-base">delete</span>
                            <span>Delete</span>
                        </button>
                        <button type="submit" 
                                class="h-9 px-6 bg-white text-orange-600 font-black text-[10px] uppercase tracking-widest rounded-xl shadow-md hover:scale-[1.02] active:scale-95 transition-all">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-rounded text-base">save</span>
                                <span>Save Video</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-4">
                <!-- Thumbnail Selector -->
                <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-4 shadow-sm group">
                    <div class="flex items-center justify-between mb-4 px-1">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Thumbnail</h4>
                        @if($video->thumbnail_path)
                        <button type="button" 
                                @click="Swal.fire({
                                    title: 'Delete thumbnail?',
                                    text: 'This will permanently remove the cover image.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#ef4444',
                                    cancelButtonColor: '#64748b',
                                    confirmButtonText: 'Yes, delete it!',
                                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        fetch('{{ route('studio.videos.thumbnail.destroy', $video) }}', {
                                            method: 'DELETE',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json',
                                            },
                                        }).then(response => {
                                            if (response.ok || response.redirected) {
                                                thumbnailPreview = '';
                                                Swal.fire({
                                                    title: 'Deleted!',
                                                    text: 'Thumbnail has been removed.',
                                                    icon: 'success',
                                                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                                }).then(() => window.location.reload());
                                            } else {
                                                Swal.fire('Error', 'Failed to delete thumbnail.', 'error');
                                            }
                                        }).catch(() => {
                                            Swal.fire('Error', 'Network error. Please try again.', 'error');
                                        });
                                    }
                                })" 
                                class="w-8 h-8 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                            <span class="material-symbols-rounded text-sm">delete</span>
                        </button>
                        @endif
                    </div>
                    <label class="block aspect-video rounded-3xl overflow-hidden bg-slate-50 dark:bg-black/20 border-2 border-dashed border-slate-200 dark:border-white/10 hover:border-orange-500 transition-all cursor-pointer relative active:scale-95">
                        <template x-if="thumbnailPreview">
                            <img :src="thumbnailPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!thumbnailPreview">
                            <div class="flex flex-col items-center justify-center h-full gap-3 text-slate-300">
                                <span class="material-symbols-rounded text-3xl">add_photo_alternate</span>
                                <p class="text-[8px] font-black uppercase tracking-widest">Select Image</p>
                            </div>
                        </template>
                        <input type="file" name="thumbnail" class="hidden" accept="image/*" @change="onThumbnailChange" />
                    </label>
                </div>

                <!-- Video Status & Duration -->
                <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-4">
                    <div class="flex items-center justify-between mb-4 px-1">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Video Statistics</h4>
                        <div class="px-3 py-1 rounded-lg gradient-orange text-white text-[10px] font-black shadow-sm shadow-orange-500/20" x-text="duration || '--:--'"></div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-white/[0.02] rounded-2xl">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-tighter">Total Views</span>
                            <span class="text-sm font-black text-slate-900 dark:text-white tracking-widest">{{ formatNumber($video->views_count) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-white/[0.02] rounded-2xl">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-tighter">Total Likes</span>
                            <span class="text-sm font-black text-slate-900 dark:text-white tracking-widest">{{ $video->likes->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Monetization Section -->
                <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-4">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 px-1">Monetization</h4>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Pricing Tier</label>
                            @php $miniOttEditSoon = gs('mini_ott_status') !== null && (int) gs('mini_ott_status') === 0; @endphp
                            <div class="relative">
                                <select name="pricing_tier" x-model="pricingTier" class="w-full h-12 px-4 bg-slate-50 dark:bg-black/10 border border-transparent dark:border-white/5 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 outline-none font-bold">
                                    <option value="free">Free</option>
                                    @if(!$miniOttEditSoon)
                                    <option value="premium" :disabled="!hasPremiumAccess">Premium (Paid)</option>
                                    <option value="exclusive" :disabled="!hasPremiumAccess">Exclusive (Paid)</option>
                                    @endif
                                </select>
                                @if($miniOttEditSoon)
                                <p class="text-[10px] text-slate-400 font-bold px-1 mt-1">Premium options coming soon.</p>
                                @endif
                            </div>
                            @if(!$miniOttEditSoon)
                            <template x-if="!hasPremiumAccess">
                                <p class="text-[10px] text-orange-500 font-bold px-1 ">Upgrade your plan to unlock paid video settings.</p>
                            </template>
                            @endif
                        </div>

                        <div class="space-y-2" x-show="pricingTier === 'premium' || pricingTier === 'exclusive'" x-transition x-cloak>
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Custom Price ({{ gs('cur_text') }})</label>
                            <div class="relative">
                                <input type="number" step="1" min="0" name="price" x-model="price" class="w-full h-12 px-4 bg-slate-50 dark:bg-black/10 border border-transparent dark:border-white/5 rounded-2xl text-slate-900 dark:text-white outline-none font-bold focus:ring-2 focus:ring-orange-500 transition-all" placeholder="0" />
                                <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">{{ gs('cur_text') }}</span>
                            </div>
                            <p class="text-[9px] text-slate-400 px-1 mt-1 font-bold ">You will earn {{ gs('ppv_creator_commission_percent') }}% of the revenue.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-4">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 px-1">Visibility & Status</h4>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer p-3 rounded-2xl border-2 transition-all active:scale-95" 
                               :class="visibility == 0 ? 'border-orange-500 bg-orange-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent'">
                            <input type="radio" name="visibility" value="0" x-model="visibility" @change="if(visibility != 0) scheduleVideo = false;" class="hidden">
                            <span class="material-symbols-rounded text-lg" :class="visibility == 0 ? 'text-orange-500' : 'text-slate-400'">public</span>
                            <span class="text-[11px] font-black uppercase tracking-widest" :class="visibility == 0 ? 'text-slate-900 dark:text-white' : 'text-slate-400'">Public</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 rounded-2xl border-2 transition-all active:scale-95" 
                               :class="visibility == 1 ? 'border-orange-500 bg-orange-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent'">
                            <input type="radio" name="visibility" value="1" x-model="visibility" @change="if(visibility != 0) scheduleVideo = false;" class="hidden">
                            <span class="material-symbols-rounded text-lg" :class="visibility == 1 ? 'text-orange-500' : 'text-slate-400'">lock</span>
                            <span class="text-[11px] font-black uppercase tracking-widest" :class="visibility == 1 ? 'text-slate-900 dark:text-white' : 'text-slate-400'">Private</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 rounded-2xl border-2 transition-all active:scale-95" 
                               :class="visibility == 'draft' ? 'border-amber-500 bg-amber-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent'">
                            <input type="radio" name="visibility" value="draft" x-model="visibility" @change="scheduleVideo = false;" class="hidden">
                            <span class="material-symbols-rounded text-lg" :class="visibility == 'draft' ? 'text-amber-500' : 'text-slate-400'">draft</span>
                            <span class="text-[11px] font-black uppercase tracking-widest" :class="visibility == 'draft' ? 'text-amber-500 font-bold' : 'text-slate-400'">Draft</span>
                        </label>
                    </div>
                </div>

                <!-- Age Restriction (Standardized) -->
                <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-4">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 px-1">Age Restriction</h4>
                    <label class="flex items-center justify-between p-3 rounded-2xl border-2 transition-all cursor-pointer group"
                           :class="isAgeRestricted ? 'border-rose-500 bg-rose-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-rounded text-lg" :class="isAgeRestricted ? 'text-rose-500 fill-1' : 'text-slate-400'">explicit</span>
                            <span class="text-[11px] font-black uppercase tracking-widest" :class="isAgeRestricted ? 'text-slate-900 dark:text-white' : 'text-slate-400'">18+ Restricted</span>
                        </div>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_age_restricted" x-model="isAgeRestricted" class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-rose-500"></div>
                        </div>
                    </label>
                    <p class="text-[9px] text-slate-400 font-bold mt-3 px-1 ">When enabled, only verified viewers can access this content.</p>
                </div>

                <!-- Scheduling -->
                <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-4" x-show="visibility == 0" x-transition x-cloak>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 px-1">Scheduling</h4>
                    <label class="flex items-center justify-between p-3 rounded-2xl border-2 transition-all cursor-pointer group"
                           :class="scheduleVideo ? 'border-blue-500 bg-blue-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-rounded text-lg" :class="scheduleVideo ? 'text-blue-500' : 'text-slate-400'">schedule</span>
                            <span class="text-[11px] font-black uppercase tracking-widest" :class="scheduleVideo ? 'text-slate-900 dark:text-white' : 'text-slate-400'">Schedule Publish</span>
                        </div>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="schedule_video" value="1" x-model="scheduleVideo" class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 dark:bg-white/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-500"></div>
                        </div>
                    </label>

                    <div class="space-y-4 mt-4" x-show="scheduleVideo" x-cloak>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Publish Date</label>
                            <input type="date" name="schedule_date" x-model="scheduleDate" class="w-full h-12 px-4 bg-slate-50 dark:bg-black/10 border border-transparent dark:border-white/5 rounded-2xl text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Publish Time (IST)</label>
                            <input type="time" name="schedule_time" x-model="scheduleTime" class="w-full h-12 px-4 bg-slate-50 dark:bg-black/10 border border-transparent dark:border-white/5 rounded-2xl text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Final Action Bar (Mobile/Tablet only, below age restriction) -->
                <div class="gradient-orange rounded-3xl p-4 lg:hidden flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl shadow-orange-500/20">
                    <div class="text-white text-center sm:text-left">
                        <p class="font-black text-xl tracking-tight">Save Changes</p>
                        <p class="text-orange-100/70 text-[10px] font-bold uppercase tracking-widest mt-1">Updates will be applied instantly.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="confirmDelete('{{ route('studio.videos.destroy', $video) }}')" 
                                class="h-9 px-5 bg-white/20 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-red-500/80 active:scale-95 transition-all border border-white/20 flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-base">delete</span>
                            <span>Delete</span>
                        </button>
                        <button type="submit" 
                                class="h-9 px-6 bg-white text-orange-600 font-black text-[10px] uppercase tracking-widest rounded-xl shadow-md hover:scale-[1.02] active:scale-95 transition-all">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-rounded text-base">save</span>
                                <span>Save Video</span>
                            </div>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    /* Dark mode only: render native date/time picker icons for dark fields */
    .dark #studio-video-edit { color-scheme: dark; }
</style>
@endsection

