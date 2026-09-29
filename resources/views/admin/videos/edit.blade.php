@extends('admin.layouts.app')

@section('title', 'Edit Video')
@section('header_title', 'Video Management')

@section('content')
<div class="min-h-screen transition-colors duration-500 pb-24"
     x-data="{
     pricingTier: '{{ $video->pricing_tier > 0 ? (['free', 'premium', 'exclusive'][$video->pricing_tier] ?? 'free') : ($video->is_premium ? 'premium' : 'free') }}',
     policy: '{{ $video->status == '0' ? 'draft' : ($video->visibility == 'private' ? 'private' : 'public') }}',
     get status() { return this.policy === 'draft' ? '0' : '1' },
     get visibility() { return this.policy === 'public' ? '0' : '1' },
     isAgeRestricted: {{ $video->is_age_restricted ? 'true' : 'false' }},
     scheduleVideo: {{ $video->scheduled_at ? 'true' : 'false' }},
     scheduleDate: '{{ $video->scheduled_at ? $video->scheduled_at->timezone('Asia/Kolkata')->format('Y-m-d') : '' }}',
     scheduleTime: '{{ $video->scheduled_at ? $video->scheduled_at->timezone('Asia/Kolkata')->format('H:i') : '' }}',
     thumbnailPreview: '{{ $video->thumbnail_path ? getImage(getFilePath('thumbnail') . '/' . $video->thumbnail_path) : '' }}',
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
     onThumbnailChange(e) {
         const file = e.target.files[0];
         if (file) {
             const reader = new FileReader();
             reader.onload = (event) => this.thumbnailPreview = event.target.result;
             reader.readAsDataURL(file);
         }
     },
     removeThumbnail() {
         this.thumbnailPreview = null;
         const input = document.querySelector(&quot;input[name='thumb_image']&quot;);
         if (input) input.value = '';
     }
     }">
    <div class="fixed inset-0 pointer-events-none opacity-[0.03] dark:opacity-[0.02] z-0" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="max-w-5xl mx-auto px-4 pt-8 relative z-10">
        <div class="mb-6 flex items-start justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">Edit Video</h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Update video details and settings.</p>
            </div>
            <a href="{{ route('admin.videos.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-100 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all">
                <span class="material-symbols-rounded">close</span>
            </a>
        </div>

        <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data" @submit="addTag(); window.showPublishingLoader('video')">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-10">
                <div class="lg:col-span-8 space-y-6 order-2 lg:order-1">
                    <!-- Creator Selector (Admin Only) -->
                    <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-5 shadow-sm relative">
                        <div class="absolute top-0 right-0 p-6 opacity-[0.02] -mr-4 -mt-4 pointer-events-none">
                            <span class="material-symbols-rounded text-[120px] text-orange-500">person</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-orange-500/[0.03] border border-orange-500/10 relative z-10" x-data="{ open: false, search: '', selectedId: '{{ $video->user_id }}', selectedName: '{{ $video->user->username ?? 'Unknown' }} ({{ $video->user->channel_name ?? 'No Channel' }})' }" @click.away="open = false">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1 mb-3 block">Assigned Creator</label>
                            <input type="hidden" name="user" :value="selectedId" required>
                            <div class="relative">
                                <button type="button" @click="open = !open"
                                        class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-5 flex items-center justify-between text-sm font-bold text-slate-700 dark:text-white transition-all">
                                    <span x-text="selectedName" class="truncate">Select Creator...</span>
                                    <span class="material-symbols-rounded text-slate-400" :class="open ? 'rotate-180' : ''">expand_more</span>
                                </button>
                                <div x-show="open" class="absolute z-[100] mt-2 w-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden" x-cloak>
                                    <div class="p-3 border-b border-slate-100 dark:border-white/5">
                                        <input type="text" x-model="search" placeholder="Search creator..." class="w-full h-10 bg-slate-50 dark:bg-black/20 border-none rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-1 focus:ring-orange-500/50 transition-all">
                                    </div>
                                    <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                        @foreach($users as $user)
                                            <div x-show="'{{ strtolower($user->username) }}'.includes(search.toLowerCase()) || '{{ strtolower($user->channel_name) }}'.includes(search.toLowerCase())"
                                                 @click="selectedId = '{{ $user->id }}'; selectedName = '{{ $user->username }} ({{ $user->channel_name }})'; open = false"
                                                 class="p-4 hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer flex items-center gap-3 transition-colors">
                                                <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500 text-[10px] font-black uppercase">
                                                    {{ substr($user->username, 0, 2) }}
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-900 dark:text-white leading-none">{{ $user->username }}</p>
                                                    <p class="text-[9px] text-slate-400 mt-1 uppercase tracking-tighter">{{ $user->channel_name }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Details Card -->
                    <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-5 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-6 opacity-[0.02] -mr-4 -mt-4">
                            <span class="material-symbols-rounded text-[120px] text-orange-500">edit_note</span>
                        </div>

                        <h3 class="text-[10px] font-black text-orange-500 uppercase tracking-[0.2em] mb-5 relative z-10">Basic Details</h3>

                        <div class="space-y-5 relative z-10">
                            <x-input name="title" label="Video Title" :value="old('title', $video->title)" required icon="label" />

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <x-select name="category" label="Category" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category', $video->category_id) == $category->id ? 'selected' : '' }}>{{ __($category->name) }}</option>
                                    @endforeach
                                </x-select>
                                <x-select name="language" label="Language" required>
                                    @foreach(config('languages') as $code => $name)
                                        <option value="{{ $name }}" {{ old('language', $video->language) == $name ? 'selected' : ($name == 'English' && !$video->language ? 'selected' : '') }}>{{ $name }}</option>
                                    @endforeach
                                </x-select>
                                <x-input name="location" label="Location (Optional)" :value="old('location', $video->location)" placeholder="e.g. London, UK" icon="location_on" />
                            </div>

                            <x-textarea name="description" label="Detailed Description" rows="5" icon="notes">{{ old('description', $video->description) }}</x-textarea>

                            <!-- Tags -->
                            <div class="space-y-3">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Hashtag</label>
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
                                               class="w-full h-12 pl-14 pr-6 bg-slate-50 dark:bg-white/5 border border-transparent rounded-2xl text-[13px] font-black text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none transition-all"
                                               placeholder="Type a tag...">
                                    </div>
                                    <button type="button"
                                            @click="addTag()"
                                            class="h-12 w-16 lg:w-auto lg:px-6 bg-gradient-to-r from-orange-600 to-orange-500 hover:from-orange-700 hover:to-orange-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all active:scale-[0.95] flex items-center justify-center lg:gap-1 shrink-0">
                                        <span class="material-symbols-rounded text-sm">add</span>
                                        <span class="hidden lg:inline">Add</span>
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-2 mt-2 items-center">
                                    <template x-for="(tag, i) in tags" :key="i">
                                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/10 text-orange-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-orange-500/20">
                                            <span x-text="tag"></span>
                                            <button type="button" @click="removeTag(i)" class="hover:text-red-500 transition-colors">
                                                <span class="material-symbols-rounded text-sm">close</span>
                                            </button>
                                            <input type="hidden" name="tags[]" :value="tag" />
                                        </span>
                                    </template>
                                </div>
                                <span class="text-[10px] text-slate-400 px-1 font-medium block">Add tags to improve categorization and search discoverability.</span>
                            </div>

                            <!-- Captions -->
                            <div class="p-4 rounded-2xl bg-orange-500/[0.03] border border-orange-500/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-r from-orange-600 to-orange-500 flex items-center justify-center text-white shadow-lg">
                                        <span class="material-symbols-rounded">subtitles</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tight">Subtitles</p>
                                        <p class="text-[9px] text-slate-400 font-bold max-w-[200px] truncate">{{ $video->captions_path ? 'Current: ' . basename($video->captions_path) : 'No captions uploaded.' }}</p>
                                    </div>
                                </div>
                                <label class="w-full sm:w-auto bg-white dark:bg-white/5 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest cursor-pointer hover:bg-orange-500 hover:text-white transition-all text-center border border-slate-200 dark:border-white/10 shadow-sm">
                                    Choose File
                                    <input type="file" name="captions" class="hidden" accept=".vtt,.srt" />
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Action Bar -->
                    <div class="bg-gradient-to-r from-orange-600 to-orange-500 rounded-3xl p-5 hidden lg:flex items-center justify-between gap-4 shadow-xl shadow-orange-500/20">
                        <div class="text-white">
                            <p class="font-black text-xl tracking-tight">Save Changes</p>
                            <p class="text-orange-100/70 text-[10px] font-bold uppercase tracking-widest mt-1">Updates will be applied instantly.</p>
                        </div>
                        <button type="submit"
                                class="h-10 px-6 bg-white text-orange-600 font-black text-[10px] uppercase tracking-widest rounded-xl shadow-md hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">save</span>
                            <span>Update Video</span>
                        </button>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 space-y-5 order-1 lg:order-2">
                    <!-- Video Preview -->
                    <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-3 shadow-sm">
                        <div class="aspect-video bg-black rounded-2xl overflow-hidden relative">
                            @if($video->isBunnyVideo())
                                <iframe
                                    src="{{ $video->getBunnyEmbedUrl(false) }}&responsive=true&preload=true"
                                    loading="lazy"
                                    style="border:none;position:absolute;top:0;left:0;height:100%;width:100%;"
                                    allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;"
                                    allowfullscreen>
                                </iframe>
                            @else
                                <video class="w-full h-full object-cover" controls>
                                    <source src="{{ Storage::url($video->video_path) }}" type="video/mp4" />
                                </video>
                            @endif
                        </div>
                        <div class="flex items-center justify-between mt-3 px-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Preview</span>
                            <span class="px-3 py-1 rounded-lg bg-gradient-to-r from-orange-600 to-orange-500 text-white text-[10px] font-black shadow-sm shadow-orange-500/20">{{ $video->duration ?? '--:--' }}</span>
                        </div>
                    </div>

                    <!-- Thumbnail -->
                    <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-4 shadow-sm group">
                        <div class="flex items-center justify-between mb-4 px-1">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Thumbnail</h4>
                        </div>
                        <div class="relative aspect-video rounded-3xl overflow-hidden group/thumb">
                            <label class="block w-full h-full bg-slate-50 dark:bg-white/5 border-2 border-dashed border-slate-200 dark:border-white/10 cursor-pointer relative active:scale-95 transition-transform group-hover:border-orange-500/50">
                                <template x-if="thumbnailPreview">
                                    <img :src="thumbnailPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!thumbnailPreview">
                                    <div class="flex flex-col items-center justify-center h-full text-slate-300">
                                        <span class="material-symbols-rounded text-3xl mb-2">add_photo_alternate</span>
                                        <p class="text-[8px] font-black uppercase tracking-widest">Select Image</p>
                                    </div>
                                </template>
                                <input type="file" name="thumb_image" class="hidden" accept="image/*" @change="onThumbnailChange" />
                            </label>
                            <button type="button"
                                    x-show="thumbnailPreview"
                                    @click="removeThumbnail()"
                                    class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 backdrop-blur-md text-white flex items-center justify-center opacity-0 group-hover/thumb:opacity-100 transition-all hover:bg-red-500 z-20">
                                <span class="material-symbols-rounded text-lg">delete</span>
                            </button>
                        </div>
                    </div>

                    <!-- Video Policy -->
                    <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-4 shadow-sm">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 px-1">Video Policy</h4>
                        <div class="space-y-2">
                            <label class="relative flex items-start p-3 rounded-lg border-2 cursor-pointer transition-all active:scale-[0.98]"
                                   :class="policy == 'public' ? 'border-orange-500 bg-orange-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                                <input type="radio" name="policy" x-model="policy" value="public" @change="if(policy != 'public') scheduleVideo = false;" class="peer hidden">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-all shadow"
                                     :class="policy == 'public' ? 'bg-gradient-to-r from-orange-600 to-orange-500 text-white' : 'bg-white dark:bg-white/10 text-slate-400'">
                                    <span class="material-symbols-rounded text-base">public</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black text-slate-900 dark:text-white">Public</span>
                                        <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                             :class="policy == 'public' ? 'border-orange-500 bg-orange-500' : 'border-slate-300 dark:border-white/20'">
                                            <div class="w-1.5 h-1.5 bg-white rounded-full shadow-sm" x-show="policy == 'public'"></div>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Published & Visible to Everyone</p>
                                </div>
                            </label>

                            <label class="relative flex items-start p-3 rounded-lg border-2 cursor-pointer transition-all active:scale-[0.98]"
                                   :class="policy == 'private' ? 'border-orange-500 bg-orange-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                                <input type="radio" name="policy" x-model="policy" value="private" @change="if(policy != 'public') scheduleVideo = false;" class="peer hidden">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-all shadow"
                                     :class="policy == 'private' ? 'bg-gradient-to-r from-orange-600 to-orange-500 text-white' : 'bg-white dark:bg-white/10 text-slate-400'">
                                    <span class="material-symbols-rounded text-base">visibility_off</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black text-slate-900 dark:text-white">Private</span>
                                        <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                             :class="policy == 'private' ? 'border-orange-500 bg-orange-500' : 'border-slate-300 dark:border-white/20'">
                                            <div class="w-1.5 h-1.5 bg-white rounded-full shadow-sm" x-show="policy == 'private'"></div>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Published but Restricted Access</p>
                                </div>
                            </label>

                            <label class="relative flex items-start p-3 rounded-lg border-2 cursor-pointer transition-all active:scale-[0.98]"
                                   :class="policy == 'draft' ? 'border-amber-500 bg-amber-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                                <input type="radio" name="policy" x-model="policy" value="draft" @change="if(policy != 'public') scheduleVideo = false;" class="peer hidden">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-all shadow"
                                     :class="policy == 'draft' ? 'bg-amber-500 text-white' : 'bg-white dark:bg-white/10 text-slate-400'">
                                    <span class="material-symbols-rounded text-base">draft</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black text-slate-900 dark:text-white">Draft</span>
                                        <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                             :class="policy == 'draft' ? 'border-amber-500 bg-amber-500' : 'border-slate-300 dark:border-white/20'">
                                            <div class="w-1.5 h-1.5 bg-white rounded-full shadow-sm" x-show="policy == 'draft'"></div>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Unpublished Internal Asset</p>
                                </div>
                            </label>
                        </div>

                        <input type="hidden" name="status" :value="status">
                        <input type="hidden" name="visibility" :value="visibility">
                    </div>

                    <!-- Monetization -->
                    <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-4 shadow-sm">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 px-1">Monetization</h4>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Pricing Tier</label>
                                @php $miniOttAdminEditSoon = gs('mini_ott_status') !== null && (int) gs('mini_ott_status') === 0; @endphp
                                <select name="pricing_tier" x-model="pricingTier" class="w-full h-12 px-4 bg-slate-50 dark:bg-white/5 border border-transparent dark:border-white/5 rounded-2xl text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-orange-500 transition-all">
                                    <option value="free">Free</option>
                                    @if(!$miniOttAdminEditSoon)
                                    <option value="premium">Premium (Paid)</option>
                                    <option value="exclusive">Exclusive (Paid)</option>
                                    @endif
                                </select>
                                @if($miniOttAdminEditSoon)
                                <p class="text-[10px] text-slate-400 font-bold px-1 mt-1">Premium options coming soon.</p>
                                @endif
                            </div>
                            <div class="space-y-2" x-show="pricingTier === 'premium' || pricingTier === 'exclusive'" x-transition x-cloak>
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Custom Price ({{ gs('cur_text') }})</label>
                                <div class="relative">
                                    <input type="number" step="1" min="0" name="price" value="{{ getAmount($video->price) }}" class="w-full h-12 px-4 bg-slate-50 dark:bg-white/5 border border-transparent dark:border-white/5 rounded-2xl text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-orange-500 transition-all" placeholder="0" />
                                    <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">{{ gs('cur_text') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Age Restriction -->
                    <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-4 shadow-sm">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 px-1">Age Restriction</h4>
                        <label class="flex items-center justify-between p-3 rounded-2xl border-2 transition-all cursor-pointer group"
                               :class="isAgeRestricted ? 'border-rose-500 bg-rose-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-lg" :class="isAgeRestricted ? 'text-rose-500' : 'text-slate-400'">explicit</span>
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
                    <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-4 shadow-sm" x-show="policy == 'public'" x-transition x-cloak>
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
                                <input type="date" name="schedule_date" x-model="scheduleDate" class="w-full h-12 px-4 bg-slate-50 dark:bg-white/5 border border-transparent dark:border-white/5 rounded-2xl text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Publish Time (IST)</label>
                                <input type="time" name="schedule_time" x-model="scheduleTime" class="w-full h-12 px-4 bg-slate-50 dark:bg-white/5 border border-transparent dark:border-white/5 rounded-2xl text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Action Bar -->
                    <div class="bg-gradient-to-r from-orange-600 to-orange-500 rounded-3xl p-5 lg:hidden flex items-center justify-between gap-4 shadow-xl shadow-orange-500/20">
                        <div class="text-white">
                            <p class="font-black text-xl tracking-tight">Save Changes</p>
                            <p class="text-orange-100/70 text-[10px] font-bold uppercase tracking-widest mt-1">Updates applied instantly.</p>
                        </div>
                        <button type="submit"
                                class="h-10 px-6 bg-white text-orange-600 font-black text-[10px] uppercase tracking-widest rounded-xl shadow-md hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">save</span>
                            <span>Update</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="max-w-3xl mx-auto mt-6 p-4 sm:p-5 rounded-[1.25rem] bg-orange-500/[0.03] border border-orange-500/10 flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500 shrink-0">
                <span class="material-symbols-rounded text-base">verified_user</span>
            </div>
            <div>
                <p class="text-[8px] font-black text-orange-600/80 uppercase tracking-widest mb-0.5 leading-none">Admin Notice</p>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-relaxed">Changes to video policy, monetization, and metadata are applied immediately.</p>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    input, select, textarea { font-size: 16px !important; }
</style>
@endsection
