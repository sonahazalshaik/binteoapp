@extends('admin.layouts.app')

@section('title', 'Upload Video')
@section('header_title', 'Video Management')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-[#0A0A0A] transition-colors duration-500 pb-24" x-data="videoUpload()">
    <div class="fixed inset-0 pointer-events-none opacity-[0.03] dark:opacity-[0.02] z-0" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="max-w-5xl mx-auto px-4 pt-8 relative z-10">
        <div class="mb-6 flex items-start justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">Upload Video</h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Upload a video for a creator on the platform.</p>
            </div>
            <button type="button" x-show="step > 1" @click="confirmBack()" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-100 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all">
                <span class="material-symbols-rounded">close</span>
            </button>
            <a x-show="step == 1" href="{{ route('admin.videos.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-100 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all">
                <span class="material-symbols-rounded">close</span>
            </a>
        </div>

        <!-- Progress Bar (mirrors frontend/videos/create.blade.php:18) -->
        <div class="mb-6 sm:mb-6">
            <div class="flex items-center justify-between px-1 mb-2">
                <div class="flex flex-col">
                    <span class="text-[9px] font-bold text-orange-600 uppercase tracking-widest mb-0.5">Upload Progress</span>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white" x-text="step == 1 ? 'Select Content' : (step == 2 ? 'Video Details' : 'Finalize')"></h2>
                </div>
                <div class="text-right">
                    <span class="text-sm font-black text-slate-300 dark:text-white/20"><span class="text-orange-600" x-text="step"></span>/3</span>
                </div>
            </div>
            <div class="h-1 w-full bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-orange-600 to-orange-500 transition-all duration-700 ease-out rounded-full shadow-[0_0_10px_rgba(249,115,22,0.4)]" :style="`width: ${ (step/3) * 100 }%` "></div>
            </div>
        </div>

        <form @submit.prevent="startUpload(false)" class="space-y-6 sm:space-y-8">
            @csrf
            <input type="hidden" name="duration" :value="duration">

            <!-- STEP 1: Upload Hub -->
            <div x-show="step == 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="max-w-3xl mx-auto">
                <label class="block">
                    <div class="relative overflow-hidden rounded-[1.25rem] sm:rounded-[1.5rem] border-2 border-dashed border-slate-200 dark:border-white/10 bg-white dark:bg-[#121212] hover:border-orange-500 transition-all cursor-pointer shadow-sm active:scale-[0.98] group">
                         <div class="absolute inset-0 bg-orange-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="py-10 sm:py-12 px-6 flex flex-col items-center text-center relative z-10">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-[1.5rem] bg-gradient-to-r from-orange-600 to-orange-500 text-white flex items-center justify-center shadow-lg shadow-orange-500/30 mb-3 transform group-hover:rotate-6 transition-transform">
                                <span class="material-symbols-rounded text-2xl sm:text-3xl">cloud_upload</span>
                            </div>
                            <h2 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Upload Video</h2>
                            <p class="text-slate-500 dark:text-slate-400 font-medium mb-4 max-w-sm leading-relaxed text-sm">Choose a video file from your device to start upload for this creator.</p>
                            <div class="w-full sm:w-auto px-8 py-3.5 rounded-lg bg-gradient-to-r from-orange-600 to-orange-500 text-white font-black text-[9px] uppercase tracking-widest shadow-md shadow-orange-500/25">
                                Select File
                            </div>
                        </div>
                    </div>
                    <input type="file" name="video" class="hidden" accept="video/*" @change="onVideoChange" />
                </label>
            </div>

            <!-- STEP 2: Details (mirrors frontend step 2 + admin creator selector) -->
            <div x-show="step == 2" x-cloak x-transition.opacity class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-10">
                <div class="lg:col-span-8 space-y-6 sm:space-y-8 order-2 lg:order-1">
                    <div class="bg-white dark:bg-[#121212] rounded-3xl sm:rounded-3xl border border-slate-100 dark:border-white/5 p-6 sm:p-6 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-6 opacity-[0.02] -mr-4 -mt-4">
                            <span class="material-symbols-rounded text-[120px] text-orange-500">article</span>
                        </div>
                        <div class="space-y-8 relative z-10">
                            <!-- Creator Selector (Admin Only) -->
                            <div class="p-4 rounded-2xl bg-orange-500/[0.03] border border-orange-500/10" @click.away="creatorOpen = false">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1 mb-3 block">Assigned Creator <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <button type="button" @click="creatorOpen = !creatorOpen"
                                            class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl px-5 flex items-center justify-between text-sm font-bold text-slate-700 dark:text-white transition-all">
                                        <span x-text="selectedCreatorName" class="truncate">Select Creator...</span>
                                        <span class="material-symbols-rounded text-slate-400" :class="creatorOpen ? 'rotate-180' : ''">expand_more</span>
                                    </button>
                                    <div x-show="creatorOpen" class="absolute z-[100] mt-2 w-full bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden" x-cloak>
                                        <div class="p-3 border-b border-slate-100 dark:border-white/5">
                                            <input type="text" x-model="creatorSearch" placeholder="Search creator..." class="w-full h-10 bg-slate-50 dark:bg-black/20 border-none rounded-xl px-4 text-xs font-bold text-slate-700 dark:text-white focus:ring-1 focus:ring-orange-500/50 transition-all">
                                        </div>
                                        <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                            @foreach($users as $user)
                                                <div x-show="'{{ strtolower($user->username) }}'.includes(creatorSearch.toLowerCase()) || '{{ strtolower($user->channel_name) }}'.includes(creatorSearch.toLowerCase())"
                                                     @click="selectedCreatorId = '{{ $user->id }}'; selectedCreatorName = '{{ $user->username }} ({{ $user->channel_name }})'; creatorOpen = false"
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
                                <p class="text-[10px] text-slate-400 px-1 mt-2 font-medium" x-show="!selectedCreatorId">Select the channel this video will be published to.</p>
                            </div>

                            <x-input
                                name="title"
                                label="Video Title"
                                x-model="title"
                                required
                                placeholder="Give your video a name"
                                hint="Tip: Use keywords in your title to help people find your video!"
                                class="h-12 px-6 bg-slate-50 dark:bg-white/5 border-none font-bold text-lg"
                            />

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <x-select
                                    name="category_id"
                                    label="Category"
                                    required
                                    hint="Selecting the right category is key for visibility."
                                    class="h-12 px-6 bg-slate-50 dark:bg-white/5 border-none font-bold text-lg"
                                >
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-select
                                    name="language"
                                    label="Language"
                                    required
                                    hint="Select the primary language used in this video."
                                    class="h-12 px-6 bg-slate-50 dark:bg-white/5 border-none font-bold text-lg"
                                >
                                    <option value="" disabled>Select Language</option>
                                    @foreach(config('languages') as $code => $name)
                                        <option value="{{ $name }}" {{ $name == 'English' ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </x-select>
                                <x-input
                                    name="location"
                                    x-model="location"
                                    label="Location (Optional)"
                                    placeholder="Add location"
                                    hint="Adding a location helps local viewers discover your story."
                                    class="h-12 px-6 bg-slate-50 dark:bg-white/5 border-none font-bold text-lg"
                                />
                            </div>

                            <x-textarea
                                name="description"
                                x-model="description"
                                label="Detailed Description"
                                rows="5"
                                placeholder="Tell viewers about your video"
                                hint="Tip: Detailed descriptions improve your search ranking by up to 200%!"
                                class="bg-slate-50 dark:bg-white/5 border-none font-medium"
                            />

                            <!-- Tag / Hashtag Chips Input -->
                            <div class="space-y-4">
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
                                        </span>
                                    </template>
                                </div>
                                <span class="text-[10px] text-slate-400 px-1 font-medium block">Add tags to improve categorization and search discoverability.</span>
                            </div>

                            <!-- Captions (Admin extra, kept) -->
                            <div class="text-left pt-2 border-t border-slate-100 dark:border-white/5">
                                <h4 class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1 mb-2">Subtitles (Optional)</h4>
                                <div class="p-4 rounded-2xl bg-orange-500/[0.03] border border-orange-500/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-r from-orange-600 to-orange-500 flex items-center justify-center text-white shadow-lg">
                                            <span class="material-symbols-rounded">subtitles</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tight">Upload Captions</p>
                                            <p class="text-[9px] text-slate-400 font-bold">Supported: .vtt, .srt</p>
                                        </div>
                                    </div>
                                    <label class="w-full sm:w-auto bg-white dark:bg-white/5 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest cursor-pointer hover:bg-orange-500 hover:text-white transition-all text-center border border-slate-200 dark:border-white/10 shadow-sm">
                                        Choose File
                                        <input type="file" name="captions" class="hidden" accept=".vtt,.srt" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="button" @click="validateAndProceed(3)" class="w-full sm:w-auto sm:ml-auto h-12 px-12 bg-gradient-to-r from-orange-600 to-orange-500 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl shadow-orange-500/25 active:scale-95 transition-all">
                            Proceed to Publish
                        </button>
                        <button type="button" @click="confirmBack()" class="w-full sm:w-auto h-12 order-last sm:order-first text-slate-400 font-bold hover:text-slate-600 dark:hover:text-white transition-colors bg-white dark:bg-white/5 rounded-2xl px-8">
                            Back
                        </button>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 space-y-6 order-1 lg:order-2">
                    <div class="aspect-video bg-black rounded-3xl overflow-hidden shadow-2xl relative border border-white/10 group/preview">
                        <video x-ref="previewPlayer"
                               :src="videoPreview"
                               class="w-full h-full object-cover"
                               controls
                               autoplay
                               muted
                               loop
                               playsinline
                               webkit-playsinline
                               preload="metadata"
                               x-show="videoPreview"
                               x-cloak></video>
                        <button type="button"
                                x-show="videoPreview"
                                @click.stop="removeVideo()"
                                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-black/60 backdrop-blur-md text-white flex items-center justify-center opacity-0 group-hover/preview:opacity-100 transition-all hover:bg-red-500 z-30 shadow-lg border border-white/10 active:scale-90">
                            <span class="material-symbols-rounded text-xl">delete</span>
                        </button>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover/preview:bg-black/20 transition-all" x-show="!videoPreview">
                            <span class="material-symbols-rounded text-white/50 text-5xl group-hover/preview:scale-110 transition-transform">play_circle</span>
                        </div>
                        <div class="absolute bottom-5 right-5 px-3 py-1.5 bg-black/80 backdrop-blur-md rounded-xl text-[10px] font-black text-white" x-text="duration" x-show="duration !== '00:00' && videoPreview"></div>
                    </div>

                    <div class="bg-white dark:bg-[#121212] rounded-3xl border border-slate-100 dark:border-white/5 p-6 relative overflow-hidden group">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 px-1">Visual Cover</h4>
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
                                <input type="file" name="thumbnail" class="hidden" accept="image/*" @change="onThumbnailChange" />
                            </label>
                            <button type="button"
                                    x-show="thumbnailPreview"
                                    @click.stop.prevent="removeThumbnail()"
                                    class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 backdrop-blur-md text-white flex items-center justify-center opacity-0 group-hover/thumb:opacity-100 transition-all hover:bg-red-500 z-20">
                                <span class="material-symbols-rounded text-lg">delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Publish (mirrors frontend step 3 + draft option) -->
            <div x-show="step == 3" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="max-w-2xl mx-auto">
                <div class="bg-white dark:bg-[#121212] rounded-[1.25rem] border border-slate-200/60 dark:border-white/10 p-4 sm:p-5 shadow-lg relative overflow-hidden">
                    <div class="text-left mb-3 border-b border-slate-100 dark:border-white/5 pb-3">
                        <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tighter">Visibility Control</h2>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Configure how the world sees this content.</p>
                    </div>

                    <div class="space-y-2 mb-4">
                        <label class="relative flex items-start p-3 sm:p-4 rounded-lg border-2 cursor-pointer transition-all active:scale-[0.98]"
                                 :class="policy == 'public' ? 'border-orange-500 bg-orange-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                            <input type="radio" value="public" x-model="policy" @change="if(policy != 'public') scheduleVideo = false;" class="peer hidden">
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
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Broadcast to the entire platform immediately.</p>
                            </div>
                        </label>

                        <label class="relative flex items-start p-3 sm:p-4 rounded-lg border-2 cursor-pointer transition-all active:scale-[0.98]"
                               :class="policy == 'private' ? 'border-orange-500 bg-orange-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                            <input type="radio" value="private" x-model="policy" @change="if(policy != 'public') scheduleVideo = false;" class="peer hidden">
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
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Only authorized viewers can access.</p>
                            </div>
                        </label>

                        <label class="relative flex items-start p-3 sm:p-4 rounded-lg border-2 cursor-pointer transition-all active:scale-[0.98]"
                               :class="policy == 'draft' ? 'border-orange-500 bg-orange-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                            <input type="radio" value="draft" x-model="policy" @change="if(policy != 'public') scheduleVideo = false;" class="peer hidden">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-all shadow"
                                 :class="policy == 'draft' ? 'bg-gradient-to-r from-orange-600 to-orange-500 text-white' : 'bg-white dark:bg-white/10 text-slate-400'">
                                <span class="material-symbols-rounded text-base">draft</span>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-black text-slate-900 dark:text-white">Draft</span>
                                    <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                         :class="policy == 'draft' ? 'border-orange-500 bg-orange-500' : 'border-slate-300 dark:border-white/20'">
                                        <div class="w-1.5 h-1.5 bg-white rounded-full shadow-sm" x-show="policy == 'draft'"></div>
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Unpublished internal asset.</p>
                            </div>
                        </label>
                    </div>

                    <div class="text-left mb-3 mt-4 border-t border-slate-100 dark:border-white/5 pt-3">
                        <h4 class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1 mb-2">Audience Safety</h4>
                        <label class="relative flex items-center p-3 sm:p-4 rounded-lg border-2 cursor-pointer transition-all active:scale-[0.98] group"
                               :class="isAgeRestricted ? 'border-rose-500 bg-rose-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                            <input type="checkbox" x-model="isAgeRestricted" class="hidden">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-all shadow"
                                 :class="isAgeRestricted ? 'bg-rose-500 text-white' : 'bg-white dark:bg-white/10 text-slate-400'">
                                <span class="material-symbols-rounded text-base">explicit</span>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-black text-slate-900 dark:text-white">18+ Restricted</span>
                                    <div class="w-8 h-4 rounded-full p-0.5 transition-colors duration-300 relative"
                                         :class="isAgeRestricted ? 'bg-rose-500' : 'bg-slate-300 dark:bg-white/20'">
                                        <div class="w-3 h-3 bg-white rounded-full shadow-md transition-transform duration-300"
                                             :class="isAgeRestricted ? 'translate-x-4' : 'translate-x-0'"></div>
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Restrict this video to users aged 18 and above only.</p>
                            </div>
                        </label>
                    </div>

                    <div class="text-left mb-3 mt-4 border-t border-slate-100 dark:border-white/5 pt-3" x-show="policy == 'public'" x-transition>
                        <h4 class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1 mb-2">Scheduling</h4>
                        <label class="relative flex items-center p-3 sm:p-4 rounded-lg border-2 cursor-pointer transition-all active:scale-[0.98] group"
                               :class="scheduleVideo ? 'border-blue-500 bg-blue-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                            <input type="checkbox" value="1" x-model="scheduleVideo" class="hidden">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-all shadow"
                                 :class="scheduleVideo ? 'bg-blue-500 text-white' : 'bg-white dark:bg-white/10 text-slate-400'">
                                <span class="material-symbols-rounded text-base">schedule</span>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-black text-slate-900 dark:text-white">Schedule Video</span>
                                    <div class="w-8 h-4 rounded-full p-0.5 transition-colors duration-300 relative"
                                         :class="scheduleVideo ? 'bg-blue-500' : 'bg-slate-300 dark:bg-white/20'">
                                        <div class="w-3 h-3 bg-white rounded-full shadow-md transition-transform duration-300"
                                             :class="scheduleVideo ? 'translate-x-4' : 'translate-x-0'"></div>
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Set a date and time (Asia/Kolkata) to publish.</p>
                            </div>
                        </label>
                    </div>

                    <div class="space-y-2 mb-4" x-show="scheduleVideo" x-cloak>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[8px] font-black uppercase tracking-widest text-slate-400 px-1">Publish Date</label>
                                <input type="date" x-model="scheduleDate" class="w-full h-12 px-3 bg-slate-50 dark:bg-white/5 border border-transparent dark:border-white/5 rounded-lg text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-blue-500 transition-all" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[8px] font-black uppercase tracking-widest text-slate-400 px-1">Publish Time (IST)</label>
                                <input type="time" x-model="scheduleTime" class="w-full h-12 px-3 bg-slate-50 dark:bg-white/5 border border-transparent dark:border-white/5 rounded-lg text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-blue-500 transition-all" />
                            </div>
                        </div>
                    </div>

                    <div class="text-left mb-3 border-b border-slate-100 dark:border-white/5 pb-3">
                        <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tighter">Monetization</h2>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Earn from content by setting a price.</p>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[8px] font-black uppercase tracking-widest text-slate-400 px-1">Pricing Tier</label>
                                @php $miniOttAdminCreateSoon = gs('mini_ott_status') !== null && (int) gs('mini_ott_status') === 0; @endphp
                                <div class="relative">
                                    <select name="pricing_tier" x-model="pricingTier" class="w-full h-12 px-3 bg-slate-50 dark:bg-white/5 border border-transparent dark:border-white/5 rounded-lg text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-orange-500 transition-all">
                                        <option value="free">Free</option>
                                        @if(!$miniOttAdminCreateSoon)
                                        <option value="premium" :disabled="!hasPremiumAccess">Premium (Paid)</option>
                                        <option value="exclusive" :disabled="!hasPremiumAccess">Exclusive (Paid)</option>
                                        @endif
                                    </select>
                                    @if($miniOttAdminCreateSoon)
                                    <p class="text-[8px] text-slate-400 font-bold px-1 mt-1">Premium options coming soon.</p>
                                    @endif
                                </div>
                                @if(!$miniOttAdminCreateSoon)
                                <template x-if="!hasPremiumAccess">
                                    <p class="text-[8px] text-orange-500 font-bold px-1 ">Upgrade your plan to unlock paid video uploads.</p>
                                </template>
                                @endif
                            </div>
                            <div class="space-y-1.5" x-show="pricingTier === 'premium' || pricingTier === 'exclusive'" x-transition x-cloak>
                                <label class="text-[8px] font-black uppercase tracking-widest text-slate-400 px-1">Custom Price ({{ gs('cur_text') }})</label>
                                <div class="relative">
                                    <input type="number" step="1" min="0" name="price" x-model="price" class="w-full h-12 px-3 bg-slate-50 dark:bg-white/5 border border-transparent dark:border-white/5 rounded-lg text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-orange-500 transition-all" placeholder="0" />
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">{{ gs('cur_text') }}</span>
                                </div>
                                <p class="text-[8px] text-slate-400 px-1 mt-1 font-medium">You will earn {{ $general->ppv_creator_commission_percent ?? gs('ppv_creator_commission_percent') }}% of the revenue.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-white/5">
                        <button type="submit"
                                class="w-full sm:w-auto px-6 h-10 bg-gradient-to-r from-orange-600 to-orange-500 text-white font-black text-[9px] uppercase tracking-widest rounded-lg shadow-md shadow-orange-500/25 active:scale-95 transition-all disabled:opacity-50 flex items-center justify-center min-w-[160px]"
                                :disabled="uploading">
                            <div class="flex items-center gap-1.5" x-show="!uploading">
                                <span class="material-symbols-rounded text-sm">rocket_launch</span>
                                <span x-text="policy == 'draft' ? 'Save Draft' : 'Publish Video'"></span>
                            </div>
                            <div class="flex items-center gap-1.5" x-show="uploading">
                                <div class="w-3.5 h-3.5 border-2 border-white/20 border-t-white rounded-full animate-spin"></div>
                                <span>Processing <span x-text="Math.round(progress) + '%'"></span></span>
                            </div>
                        </button>
                        <button type="button" @click="step = 2" class="w-full sm:w-auto h-10 px-6 rounded-lg text-slate-400 font-bold text-[10px] hover:text-slate-600 dark:hover:text-white transition-colors order-last sm:order-first bg-slate-50 dark:bg-white/5">
                            Modify Details
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="max-w-3xl mx-auto mt-6 p-4 sm:p-5 rounded-[1.25rem] bg-orange-500/[0.03] border border-orange-500/10 flex items-start gap-3 mb-6 md:mb-0">
            <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500 shrink-0">
                <span class="material-symbols-rounded text-base">verified_user</span>
            </div>
            <div>
                <p class="text-[8px] font-black text-orange-600/80 uppercase tracking-widest mb-0.5 leading-none">Security Protocol</p>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-relaxed">The video will undergo automated copyright and quality scanning before going live.</p>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    body { -webkit-tap-highlight-color: transparent; }
    input, select, textarea { font-size: 16px !important; }
</style>

<script src="{{ asset('assets/js/tus.min.js') }}?v={{ filemtime(public_path('assets/js/tus.min.js')) }}"></script>

<script>
    function mobileSwalOptions(extra = {}) {
        const isMobile = window.innerWidth < 768;
        return {
            width: isMobile ? '95%' : 'auto',
            grow: isMobile ? 'fullscreen' : false,
            padding: isMobile ? '1.25rem' : '2rem',
            ...extra
        };
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('videoUpload', () => ({
            step: 1,
            videoPreview: null,
            thumbnailPreview: null,
            videoFile: null,
            uploading: false,
            progress: 0,
            title: '',
            description: '',
            location: '',
            // Admin policy maps to visibility + is_draft (mirrors frontend visibility logic plus draft)
            policy: 'public',
            get visibility() { return this.policy === 'private' ? 1 : 0; },
            get isDraft() { return this.policy === 'draft'; },
            isAgeRestricted: false,
            scheduleVideo: false,
            scheduleDate: '',
            scheduleTime: '',
            duration: '00:00',
            pricingTier: 'free',
            price: 0,
            hasPremiumAccess: true,
            selectedCreatorId: '',
            selectedCreatorName: 'Select Creator...',
            creatorOpen: false,
            creatorSearch: '',
            tags: [],
            tagInput: '',
            addTag() {
                let raw = this.tagInput.trim();
                if (!raw) return;
                let parts = raw.split(/[\s,]+/).map(p => p.replace(/#/g, '').trim()).filter(p => p.length > 0);
                parts.forEach(part => { if (!this.tags.includes(part)) this.tags.push(part); });
                this.tagInput = '';
            },
            removeTag(index) { this.tags.splice(index, 1); },
            formatTime(seconds) {
                if (!seconds || isNaN(seconds)) return '00:00';
                const h = Math.floor(seconds / 3600);
                const m = Math.floor((seconds % 3600) / 60);
                const s = Math.floor(seconds % 60);
                if (h > 0) return `${h}:${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
                return `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
            },
            validateAndProceed(targetStep) {
                if (!this.selectedCreatorId) {
                    const swalFn = window.Swal ? Swal.fire.bind(Swal) : (window.adminSwal || function(o){ alert(o.text); });
                    swalFn({ title: 'Select Creator', text: 'Please select the creator this video belongs to.', icon: 'warning', confirmButtonColor: '#ff571a', background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff', color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000' });
                    return;
                }
                if (!this.title) {
                    Swal.fire({ title: 'Missing Title', text: 'Please give the video a name before proceeding.', icon: 'warning', confirmButtonColor: '#ff571a', background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff', color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000' });
                    return;
                }
                const catId = document.querySelector('select[name="category_id"]')?.value;
                if (!catId) {
                    Swal.fire({ title: 'Select Category', text: 'Choose a category to help viewers find this content.', icon: 'warning', confirmButtonColor: '#ff571a', background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff', color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000' });
                    return;
                }
                this.step = targetStep;
            },
            confirmBack() {
                if (!this.videoFile) { this.step = 1; return; }
                Swal.fire({
                    title: 'Discard Changes?', text: "You will lose the current video and all entered details.",
                    icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, Discard', cancelButtonText: 'Keep Editing',
                    confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b',
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000'
                }).then((result) => { if (result.isConfirmed) window.location.reload(); });
            },
            onVideoChange(e) {
                const file = e.target.files[0];
                e.target.value = '';
                if (!file) return;
                if (!file.type) {
                    try {
                        const ext = file.name.split('.').pop().toLowerCase();
                        const mimeTypes = { 'mp4': 'video/mp4', 'mov': 'video/quicktime', 'avi': 'video/x-msvideo', 'mkv': 'video/x-matroska', 'webm': 'video/webm', '3gp': 'video/3gpp', 'm4v': 'video/x-m4v' };
                        Object.defineProperty(file, 'type', { value: mimeTypes[ext] || 'video/mp4', writable: false, configurable: true });
                    } catch (err) { console.warn("Could not override file.type", err); }
                }
                this.videoFile = file;
                if (this.videoPreview) URL.revokeObjectURL(this.videoPreview);
                const objUrl = URL.createObjectURL(file);
                this.videoPreview = objUrl;
                this.duration = '00:00';
                this.step = 2;
                if (!this.title && file.name) {
                    let name = file.name.replace(/\.[^/.]+$/, '');
                    if (name.toLowerCase() === 'image' || name.toLowerCase() === 'video' || /^\d+$/.test(name)) name = 'Video ' + new Date().toLocaleDateString();
                    this.title = name.replace(/[_-]/g, ' ');
                }
                this.$nextTick(() => {
                    requestAnimationFrame(() => {
                        setTimeout(() => {
                            const player = this.$refs.previewPlayer;
                            if (!player) return;
                            player.onloadedmetadata = null;
                            player.onseeked = null;
                            player.onerror = null;
                            const setDuration = (d) => {
                                if (d === Infinity || isNaN(d) || !isFinite(d)) {
                                    try {
                                        player.currentTime = Number.MAX_SAFE_INTEGER;
                                        player.onseeked = () => {
                                            player.onseeked = null;
                                            const nd = player.duration;
                                            if (nd && isFinite(nd) && nd !== Infinity) this.duration = this.formatTime(nd);
                                            try { player.currentTime = 0; } catch(e) {}
                                            player.muted = true;
                                            player.play().catch(()=>{});
                                        };
                                    } catch(e) {}
                                } else if (d && isFinite(d)) {
                                    this.duration = this.formatTime(d);
                                }
                            };
                            player.onloadedmetadata = () => setDuration(player.duration);
                            player.onerror = () => console.warn('Preview load error', player.error);
                            if (player.readyState >= 1 && player.duration) setDuration(player.duration);
                            try { player.pause(); player.removeAttribute('src'); player.load(); } catch(e) {}
                            player.src = objUrl;
                            player.muted = true;
                            player.setAttribute('muted', '');
                            player.setAttribute('playsinline', '');
                            player.setAttribute('webkit-playsinline', '');
                            player.preload = 'metadata';
                            player.autoplay = true;
                            player.loop = true;
                            player.controls = true;
                            try { player.load(); } catch(e) {}
                            const tryPlay = () => {
                                player.muted = true;
                                const p = player.play();
                                if (p && p.catch) p.catch(err => {
                                    setTimeout(() => { player.muted = true; player.play().catch(e => console.warn('Play failed', e)); }, 100);
                                });
                            };
                            if (player.readyState >= 2) tryPlay();
                            else {
                                player.addEventListener('loadeddata', tryPlay, { once: true });
                                player.addEventListener('canplay', tryPlay, { once: true });
                                setTimeout(tryPlay, 150);
                            }
                        }, 60);
                    });
                });
            },
            removeVideo() {
                const player = this.$refs.previewPlayer;
                if (player) {
                    try { player.pause(); player.removeAttribute('src'); player.load(); } catch(e) {}
                    player.onloadedmetadata = null;
                    player.onseeked = null;
                    player.onerror = null;
                }
                if (this.videoPreview) URL.revokeObjectURL(this.videoPreview);
                this.videoFile = null; this.videoPreview = null; this.duration = '00:00'; this.step = 1;
                const input = document.querySelector('input[name="video"]'); if (input) input.value = '';
            },
            onThumbnailChange(e) {
                const file = e.target.files[0];
                if (file) {
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type) && !file.name.toLowerCase().match(/\.(jpe?g|png|gif|webp)$/)) {
                        Swal.fire({ title: 'Invalid Format', text: 'Please select a valid image file (JPEG, PNG, GIF, or WebP).', icon: 'error', confirmButtonColor: '#ff571a' });
                        e.target.value = ''; this.thumbnailPreview = null; return;
                    }
                    const reader = new FileReader(); reader.onload = (e) => this.thumbnailPreview = e.target.result; reader.readAsDataURL(file);
                }
            },
            removeThumbnail() { this.thumbnailPreview = null; const input = document.querySelector('input[name="thumbnail"]'); if (input) input.value = ''; },

            async startUpload(isDraft = false) {
                if (typeof isDraft !== 'boolean') isDraft = false;
                if (this.uploading) return;
                if (this.step < 2) return;
                if (this.step < 3 && !isDraft) { this.step = 3; return; }
                if (!this.selectedCreatorId) {
                    Swal.fire({ title: 'Select Creator', text: 'Please select a creator first.', icon: 'warning', confirmButtonColor: '#ff571a' }); this.step = 2; return;
                }
                if (!this.title) {
                    Swal.fire({ title: 'Missing Title', text: 'Please give the video a name before publishing.', icon: 'warning', confirmButtonColor: '#ff571a' }); return;
                }
                const catId = document.querySelector('select[name="category_id"]')?.value;
                if (!catId) {
                    Swal.fire({ title: 'Select Category', text: 'Choose a category to help viewers find this content.', icon: 'warning', confirmButtonColor: '#ff571a' }); return;
                }
                if (!this.videoFile) {
                    Swal.fire({ title: 'No Video Selected', text: 'Please select a video file to upload.', icon: 'error', confirmButtonColor: '#ff571a' }); this.step = 1; return;
                }

                this.uploading = true; this.progress = 0;

                try {
                    const formData = new FormData();
                    formData.append('user', this.selectedCreatorId);
                    formData.append('title', this.title);
                    formData.append('description', this.description);
                    formData.append('category_id', catId);
                    formData.append('visibility', this.visibility);
                    formData.append('location', this.location);
                    formData.append('duration', this.duration);
                    formData.append('pricing_tier', this.pricingTier);
                    formData.append('price', this.price);
                    formData.append('is_age_restricted', this.isAgeRestricted ? 1 : 0);
                    formData.append('schedule_video', this.scheduleVideo ? 1 : 0);
                    formData.append('schedule_date', this.scheduleDate);
                    formData.append('schedule_time', this.scheduleTime);
                    formData.append('tags', JSON.stringify(this.tags));
                    formData.append('policy', this.policy);
                    if (this.isDraft || isDraft) formData.append('is_draft', '1');
                    // language
                    const langVal = document.querySelector('select[name="language"]')?.value || '';
                    formData.append('language', langVal);
                    // captions if selected
                    const capInput = document.querySelector('input[name="captions"]');
                    if (capInput && capInput.files[0]) formData.append('captions', capInput.files[0]);

                    const prepRes = await fetch("{{ route('admin.videos.prepare_upload') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: formData
                    });
                    if (!prepRes.ok) {
                        const errText = await prepRes.text().catch(() => '');
                        throw new Error(`Prepare failed (HTTP ${prepRes.status}): ${errText.slice(0, 300)}`);
                    }
                    const prepData = await prepRes.json();
                    if(!prepData.success) throw new Error(prepData.message || 'Failed to prepare upload');

                    // Android direct PUT (browser -> Bunny non-TUS) - zero server load
                    const isAndroidDevice = /Android/i.test(navigator.userAgent);
                    if (isAndroidDevice) {
                        const file = this.videoFile;
                        const totalSizeMB = (file.size / 1048576).toFixed(1);
                        let lastLoaded = 0; let lastTime = Date.now();
                        const uploadResult = await new Promise((resolve, reject) => {
                            const xhr = new XMLHttpRequest();
                            xhr.open('PUT', prepData.direct.url, true);
                            xhr.setRequestHeader('AccessKey', prepData.direct.access_key);
                            xhr.setRequestHeader('Content-Type', 'application/octet-stream');
                            xhr.upload.onprogress = (e) => {
                                if (e.lengthComputable) {
                                    this.progress = (e.loaded / e.total) * 100;
                                }
                            };
                            xhr.onload = () => { if (xhr.status >= 200 && xhr.status < 300) resolve({ success: true }); else reject(new Error(`Bunny Stream rejected direct upload with status ${xhr.status}`)); };
                            xhr.onerror = () => reject(new Error('Direct browser-to-Bunny network connection failed'));
                            xhr.send(file);
                        });
                        if (uploadResult.success) {
                            const thumbInput = document.querySelector('input[name="thumbnail"]');
                            if (thumbInput && thumbInput.files[0]) {
                                try {
                                    const thumbData = new FormData(); thumbData.append('thumbnail', thumbInput.files[0]);
                                    await fetch("{{ url('admin/videos') }}/" + prepData.video_slug + "/thumbnail", { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: thumbData });
                                } catch (thumbErr) { console.error('Thumbnail upload error:', thumbErr); }
                            }
                            this.uploading = false;
                            if (window.Swal) Swal.fire({ icon: 'success', title: 'Published!', text: 'Video uploaded successfully' });
                            setTimeout(() => window.location.href = "{{ route('admin.videos.index') }}", 900);
                            return;
                        }
                    }

                    // TUS upload (browser -> /tus-proxy -> Bunny)
                    const file = this.videoFile;
                    const uploadResult = await new Promise((resolve, reject) => {
                        let lastLoaded = 0; let lastTime = Date.now();
                        const uploadRoutes = ['https://video.bunnycdn.com/tusupload', prepData.tus.endpoint];
                        const startRoute = (routeIdx) => {
                            const routeChunkSize = routeIdx === 0 ? (2 * 1024 * 1024) : (512 * 1024);
                            const tusUpload = new tus.Upload(file, {
                                endpoint: uploadRoutes[routeIdx],
                                uploadUrl: null,
                                retryDelays: [0, 3000, 5000, 10000, 20000],
                                chunkSize: routeChunkSize,
                                headers: prepData.tus.headers,
                                metadata: { filename: file.name, filetype: file.type || 'video/mp4' },
                                storeFingerprintForResuming: false,
                                removeFingerprintOnSuccess: false,
                                fingerprint: (file, options) => Promise.resolve(null),
                                onProgress: (bytesUploaded, bytesTotal) => { this.progress = (bytesUploaded / bytesTotal) * 100; },
                                onSuccess: () => resolve({ success: true }),
                                onError: (error) => {
                                    if (routeIdx + 1 < uploadRoutes.length) {
                                        fetch("{{ route('admin.videos.log_error') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ error_message: 'Route ' + routeIdx + ' (' + uploadRoutes[routeIdx] + ') failed: ' + (error?.message || String(error)), type: 'video', details: { failed_route: uploadRoutes[routeIdx], route_index: routeIdx, video_id: prepData.video_id, bunny_id: prepData.bunny_id } }) }).catch(function(e) {});
                                        try { tusUpload.abort(); } catch (e) {}
                                        startRoute(routeIdx + 1);
                                    } else { reject(error); }
                                }
                            });
                            tusUpload.start();
                        };
                        startRoute(0);
                    });

                    if (uploadResult.success) {
                        const thumbInput = document.querySelector('input[name="thumbnail"]');
                        if (thumbInput && thumbInput.files[0]) {
                            try {
                                const thumbData = new FormData(); thumbData.append('thumbnail', thumbInput.files[0]);
                                await fetch("{{ url('admin/videos') }}/" + prepData.video_slug + "/thumbnail", { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: thumbData });
                            } catch (thumbErr) { console.error('Thumbnail upload error:', thumbErr); }
                        }
                        this.uploading = false;
                        if (window.Swal) Swal.fire({ icon: 'success', title: 'Published!', text: 'Video uploaded successfully and is now processing.' });
                        setTimeout(() => window.location.href = "{{ route('admin.videos.index') }}", 900);
                    } else {
                        throw new Error('Upload failed');
                    }

                 } catch (error) {
                    console.error('Upload error:', error);
                    fetch("{{ route('admin.videos.log_error') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ error_message: error?.message || String(error), type: 'video', details: { title: this.title } }) }).catch(e => console.error('Failed to log error', e));

                    if (typeof prepData !== 'undefined' && prepData && prepData.video_id) {
                        try {
                            const directFormData = new FormData(); directFormData.append('video', this.videoFile); directFormData.append('video_id', prepData.video_id);
                            const directRes = await fetch("{{ route('admin.videos.direct_upload') }}", { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: directFormData });
                            if (directRes.ok) {
                                const directResult = await directRes.json();
                                if (directResult.success) {
                                    const thumbInput = document.querySelector('input[name="thumbnail"]');
                                    if (thumbInput && thumbInput.files[0]) {
                                        try { const thumbData = new FormData(); thumbData.append('thumbnail', thumbInput.files[0]); await fetch("{{ url('admin/videos') }}/" + prepData.video_slug + "/thumbnail", { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: thumbData }); } catch (thumbErr) {}
                                    }
                                    this.uploading = false;
                                    if (window.Swal) Swal.fire({ icon: 'success', title: 'Published via backup', text: 'Video uploaded via backup route.' });
                                    setTimeout(() => window.location.href = "{{ route('admin.videos.index') }}", 900);
                                    return;
                                }
                            }
                        } catch (fallbackErr) { console.error('Server fallback upload failed:', fallbackErr); }
                    }

                    if(window.Swal) Swal.close();
                    const msg = (error && error.message) ? String(error.message).slice(0, 250) : 'An error occurred during upload. Please try again.';
                    const swalFn2 = window.Swal ? Swal.fire.bind(Swal) : (window.adminSwal || function(o){ alert(o.text); });
                    swalFn2({ icon: 'error', title: 'Upload Failed', text: msg });
                    this.uploading = false;
                }
            }
        }));
    });
</script>
@endsection
