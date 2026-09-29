@extends('layouts.app')

@section('content')
<div id="video-upload" class="min-h-screen bg-gray-50 dark:bg-[#0A0A0A] transition-colors duration-500 pb-28 md:pb-12" x-data="videoUpload()">
    <div class="fixed inset-0 pointer-events-none opacity-[0.03] dark:opacity-[0.02] z-0" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="max-w-5xl mx-auto px-4 pt-8 relative z-10">
        <div class="mb-6 flex items-start justify-between">
            <div class="flex items-center gap-3">
                <button type="button" @click="confirmBack()" class="w-10 h-10 rounded-2xl bg-white dark:bg-white/5 border border-slate-100 dark:border-white/10 flex items-center justify-center text-slate-600 dark:text-slate-200 hover:text-orange-500 dark:hover:text-orange-500 transition-all active:scale-95 shadow-sm">
                    <span class="material-symbols-rounded text-xl">arrow_back</span>
                </button>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">Create your story</h1>
                    <p class="text-slate-500 font-medium text-sm mt-1">Upload and manage with professional tools.</p>
                </div>
            </div>
            <button type="button" x-show="step > 1" @click="confirmBack()" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-100 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>

        <!-- Native-Inspired Progress Bar (Mobile Optimized) -->
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
                <div class="h-full gradient-orange transition-all duration-700 ease-out rounded-full shadow-[0_0_10px_rgba(249,115,22,0.4)]" :style="`width: ${ (step/3) * 100 }%` "></div>
            </div>
        </div>

        <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 sm:space-y-8"
              @submit.prevent="startUpload(false)">
            @csrf
            
            <input type="hidden" name="duration" :value="duration">

            <!-- STEP 1: Mobile-First Upload Hub -->
            <div x-show="step == 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="max-w-3xl mx-auto">
                <label class="block">
                    <div class="relative overflow-hidden rounded-[1.25rem] sm:rounded-[1.5rem] border-2 border-dashed border-slate-200 dark:border-white/10 bg-white dark:bg-[#121212] hover:border-orange-500 transition-all cursor-pointer shadow-sm active:scale-[0.98] group">
                         <!-- Glow background on hover -->
                         <div class="absolute inset-0 bg-orange-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="py-10 sm:py-12 px-6 flex flex-col items-center text-center relative z-10">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-[1.5rem] gradient-orange text-white flex items-center justify-center shadow-lg shadow-orange-500/30 mb-3 transform group-hover:rotate-6 transition-transform">
                                <span class="material-symbols-rounded text-2xl sm:text-3xl">cloud_upload</span>
                            </div>

                            <h2 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">Upload Video</h2>
                            <p class="text-slate-500 dark:text-slate-400 font-medium mb-4 max-w-sm leading-relaxed text-sm">Choose a video file from your device to start your viral journey.</p>
                            
                            <div class="w-full sm:w-auto px-8 py-3.5 rounded-lg gradient-orange text-white font-black text-[9px] uppercase tracking-widest shadow-md shadow-orange-500/25">
                                Select File
                            </div>
                        </div>
                    </div>
                    <input type="file" name="video" class="hidden" accept="video/*" @click="$event.target.value = null" @change="onVideoChange" />
                </label>
            </div>

            <!-- STEP 2: Android Details Layout -->
            <div x-show="step == 2" x-cloak x-transition.opacity class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-10">
                <div class="lg:col-span-8 space-y-6 sm:space-y-8 order-2 lg:order-1">
                    <div class="bg-white dark:bg-[#121212] rounded-3xl sm:rounded-3xl border border-slate-100 dark:border-white/5 p-6 sm:p-6 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-6 opacity-[0.02] -mr-4 -mt-4">
                            <span class="material-symbols-rounded text-[120px] text-orange-500">article</span>
                        </div>
                        
                        <div class="space-y-6 relative z-10">
                            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                                <div x-show="saveStatus || backgroundUploading" x-transition.opacity class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-3 py-1.5 rounded-full border border-amber-500/20 shadow-sm">
                                    <span class="material-symbols-rounded text-[14px]" x-text="backgroundUploading ? 'cloud_upload' : (saveStatus === 'Saving...' ? 'sync' : (saveStatus.startsWith('Saved') || saveStatus.includes('Complete') ? 'draft' : 'cloud_off'))" :class="{'animate-spin': saveStatus === 'Saving...' || backgroundUploading}"></span>
                                    <span class="font-bold uppercase tracking-wider text-[10px]" x-text="getUploadStatusText()"></span>
                                </div>
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
                                    x-model="categoryId"
                                    required
                                    hint="Selecting the right category is key for visibility."
                                    class="h-12 px-6 bg-slate-50 dark:bg-white/5 border-none font-bold text-lg"
                                >
                                    <option value="" disabled>Select Category</option>
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
                                            class="h-12 w-16 lg:w-auto lg:px-6 bg-orange-500 hover:bg-orange-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all active:scale-[0.95] flex items-center justify-center lg:gap-1 shrink-0">
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
                        </div>
                    </div>

                    <!-- Mobile Action Bar (Sticky-ready) -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="button" @click="validateAndProceed(3)" class="w-full sm:w-auto sm:ml-auto h-12 px-12 gradient-orange text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl shadow-orange-500/25 active:scale-95 transition-all">
                            Proceed to Publish
                        </button>
                        <button type="button" @click="confirmBack()" class="w-full sm:w-auto h-12 order-last sm:order-first text-slate-400 font-bold hover:text-slate-600 dark:hover:text-white transition-colors bg-white dark:bg-white/5 rounded-2xl px-8">
                            Back
                        </button>
                    </div>
                </div>

                <!-- Sidebar Elements -->
                <div class="lg:col-span-4 space-y-6 order-1 lg:order-2">
                    <div class="aspect-video bg-black rounded-3xl overflow-hidden shadow-2xl relative border border-white/10 group/preview">
                        <!-- Local Video Player Preview -->
                        <video x-ref="previewPlayer" 
                               :src="videoPreview"
                               class="w-full h-full object-cover relative z-0" 
                               controls
                               autoplay 
                               muted 
                               loop 
                               playsinline 
                               webkit-playsinline
                               preload="auto"
                               x-on:error="isUnplayableFormat = true"
                               x-show="videoPreview"
                               x-cloak></video>

                        <!-- Fallback Placeholder when Bunny Stream is ready -->
                        <div x-show="!videoPreview && (bunnyStatus === 'ready' || bunnyStatus === 'processing' || bunnyStatus === 'encoding')" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900 p-4 text-center z-10">
                            <div class="w-12 h-12 rounded-2xl bg-orange-500/20 text-orange-500 flex items-center justify-center mb-2 animate-pulse">
                                <span class="material-symbols-rounded text-2xl">movie</span>
                            </div>
                            <span class="text-xs font-black text-white uppercase tracking-wider mb-1">Video Stream Ready</span>
                            <span class="text-[10px] text-slate-300 font-medium" x-text="bunnyStatus === 'ready' ? 'Video processed and ready for publishing' : 'Video uploaded & background processing...'"></span>
                        </div>

                        <!-- Fallback Placeholder when local file is missing and upload is incomplete/interrupted -->
                        <div x-show="!videoPreview && bunnyStatus !== 'ready' && bunnyStatus !== 'processing' && bunnyStatus !== 'encoding'" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900 p-4 text-center z-10 cursor-pointer group/reselect hover:bg-slate-800 transition-colors" @click="document.querySelector('input[type=file][name=video]').click()">
                            <div class="w-12 h-12 rounded-2xl bg-orange-500/20 text-orange-500 flex items-center justify-center mb-2 group-hover/reselect:scale-110 transition-transform">
                                <span class="material-symbols-rounded text-2xl">cloud_upload</span>
                            </div>
                            <span class="text-xs font-black text-white uppercase tracking-wider mb-1">Upload Interrupted</span>
                            <span class="text-[10px] text-orange-400 font-bold underline">Click here to select video file</span>
                        </div>

                        <!-- Live Upload Progress Overlay over Video Preview -->
                        <div x-show="backgroundUploading" class="absolute inset-x-0 bottom-0 p-3.5 bg-gradient-to-t from-black/95 via-black/75 to-transparent z-20 backdrop-blur-[2px]">
                            <div class="flex items-center justify-between text-[11px] font-extrabold text-white mb-1.5">
                                <div class="flex items-center gap-1.5 text-orange-400">
                                    <span class="material-symbols-rounded text-sm animate-spin">sync</span>
                                    <span class="uppercase tracking-wider text-[10px]">Uploading</span>
                                </div>
                                <span class="font-black text-orange-400" x-text="Math.round(backgroundProgress) + '%'"></span>
                            </div>
                            <div class="h-1.5 w-full bg-white/20 rounded-full overflow-hidden mb-1.5 shadow-inner">
                                <div class="h-full bg-gradient-to-r from-orange-500 to-amber-400 transition-all duration-300 rounded-full shadow-[0_0_8px_rgba(249,115,22,0.6)]" :style="`width: ${backgroundProgress}%`"></div>
                            </div>
                            <div class="flex justify-between items-center text-[10px] font-bold text-slate-200" x-show="videoFile && videoFile.size">
                                <span x-text="videoFile?.size ? formatFileSize(Math.round((backgroundProgress / 100) * videoFile.size)) : ''"></span>
                                <span x-text="videoFile?.size ? formatFileSize(videoFile.size) : ''"></span>
                            </div>
                        </div>

                        <!-- Remove Video Button -->
                        <button type="button" 
                                x-show="videoPreview" 
                                @click.stop="removeVideo()" 
                                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-black/60 backdrop-blur-md text-white flex items-center justify-center opacity-0 group-hover/preview:opacity-100 transition-all hover:bg-red-500 z-30 shadow-lg border border-white/10 active:scale-90">
                            <span class="material-symbols-rounded text-xl">delete</span>
                        </button>

                        <div class="absolute bottom-5 right-5 px-3 py-1.5 bg-black/80 backdrop-blur-md rounded-xl text-[10px] font-black text-white" x-text="duration" x-show="videoPreview && duration !== '00:00' && !backgroundUploading"></div>
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

                            <!-- Delete Button Overlay -->
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

            <!-- STEP 3: Simple & Clean Publish Section -->
            <div x-show="step == 3" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="max-w-2xl mx-auto">
                <div class="bg-white dark:bg-[#121212] rounded-[1.25rem] border border-slate-200/60 dark:border-white/10 p-4 sm:p-5 shadow-lg relative overflow-hidden">
                    
                    <div class="text-left mb-3 border-b border-slate-100 dark:border-white/5 pb-3">
                        <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tighter">Visibility Control</h2>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Configure how the world sees your content.</p>
                    </div>

                    <div class="space-y-2 mb-4">
                        <label class="relative flex items-start p-3 sm:p-4 rounded-lg border-2 cursor-pointer transition-all active:scale-[0.98]"
                                 :class="visibility == 0 ? 'border-orange-500 bg-orange-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                            <input type="radio" name="visibility" value="0" x-model="visibility" @change="if(visibility != 0) scheduleVideo = false;" class="peer hidden">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-all shadow"
                                 :class="visibility == 0 ? 'gradient-orange text-white' : 'bg-white dark:bg-white/10 text-slate-400'">
                                <span class="material-symbols-rounded text-base">public</span>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-black text-slate-900 dark:text-white">Public</span>
                                    <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                         :class="visibility == 0 ? 'border-orange-500 bg-orange-500' : 'border-slate-300 dark:border-white/20'">
                                        <div class="w-1.5 h-1.5 bg-white rounded-full shadow-sm" x-show="visibility == 0"></div>
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Broadcast to the entire platform immediately.</p>
                            </div>
                        </label>

                        <label class="relative flex items-start p-3 sm:p-4 rounded-lg border-2 cursor-pointer transition-all active:scale-[0.98]"
                               :class="visibility == 1 ? 'border-orange-500 bg-orange-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                            <input type="radio" name="visibility" value="1" x-model="visibility" @change="if(visibility != 0) scheduleVideo = false;" class="peer hidden">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-all shadow"
                                 :class="visibility == 1 ? 'gradient-orange text-white' : 'bg-white dark:bg-white/10 text-slate-400'">
                                <span class="material-symbols-rounded text-base">visibility_off</span>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-black text-slate-900 dark:text-white">Private</span>
                                    <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center"
                                         :class="visibility == 1 ? 'border-orange-500 bg-orange-500' : 'border-slate-300 dark:border-white/20'">
                                        <div class="w-1.5 h-1.5 bg-white rounded-full shadow-sm" x-show="visibility == 1"></div>
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Only you and authorized managers can view.</p>
                            </div>
                        </label>
                    </div>

                    <div class="text-left mb-3 mt-4 border-t border-slate-100 dark:border-white/5 pt-3">
                        <h4 class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1 mb-2">Audience Safety</h4>
                        <label class="relative flex items-center p-3 sm:p-4 rounded-lg border-2 cursor-pointer transition-all active:scale-[0.98] group"
                               :class="isAgeRestricted ? 'border-rose-500 bg-rose-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                            <input type="checkbox" name="is_age_restricted" x-model="isAgeRestricted" class="hidden">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-all shadow"
                                 :class="isAgeRestricted ? 'bg-rose-500 text-white' : 'bg-white dark:bg-white/10 text-slate-400'">
                                <span class="material-symbols-rounded text-base" :class="isAgeRestricted ? 'fill-1' : ''">explicit</span>
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
                    
                    <div class="text-left mb-3 mt-4 border-t border-slate-100 dark:border-white/5 pt-3" x-show="visibility == 0" x-transition>
                        <h4 class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1 mb-2">Scheduling</h4>
                        <label class="relative flex items-center p-3 sm:p-4 rounded-lg border-2 cursor-pointer transition-all active:scale-[0.98] group"
                               :class="scheduleVideo ? 'border-blue-500 bg-blue-500/[0.03]' : 'border-slate-50 dark:border-white/5 bg-slate-50 dark:bg-transparent hover:border-slate-200 dark:hover:border-white/10'">
                            <input type="checkbox" name="schedule_video" value="1" x-model="scheduleVideo" class="hidden">
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
                                <input type="date" name="schedule_date" x-model="scheduleDate" class="w-full h-12 px-3 bg-slate-50 dark:bg-white/5 border border-transparent dark:border-white/5 rounded-lg text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-blue-500 transition-all" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[8px] font-black uppercase tracking-widest text-slate-400 px-1">Publish Time (IST)</label>
                                <input type="time" name="schedule_time" x-model="scheduleTime" class="w-full h-12 px-3 bg-slate-50 dark:bg-white/5 border border-transparent dark:border-white/5 rounded-lg text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-blue-500 transition-all" />
                            </div>
                        </div>
                    </div>

                    <div class="text-left mb-3 border-b border-slate-100 dark:border-white/5 pb-3">
                        <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tighter">Monetization</h2>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Earn from your content by setting a price.</p>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[8px] font-black uppercase tracking-widest text-slate-400 px-1">Pricing Tier</label>
                                @php $miniOttCreateSoon = gs('mini_ott_status') !== null && (int) gs('mini_ott_status') === 0; @endphp
                                <div class="relative">
                                    <select name="pricing_tier" x-model="pricingTier" class="w-full h-12 px-3 bg-slate-50 dark:bg-white/5 border border-transparent dark:border-white/5 rounded-lg text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-orange-500 transition-all">
                                        <option value="free">Free</option>
                                        @if(!$miniOttCreateSoon)
                                        <option value="premium" :disabled="!hasPremiumAccess">Premium (Paid)</option>
                                        <option value="exclusive" :disabled="!hasPremiumAccess">Exclusive (Paid)</option>
                                        @endif
                                    </select>
                                    @if($miniOttCreateSoon)
                                    <p class="text-[8px] text-slate-400 font-bold px-1 mt-1">Premium options coming soon.</p>
                                    @endif
                                </div>
                                <template x-if="!hasPremiumAccess">
                                    <p class="text-[8px] text-orange-500 font-bold px-1 ">Upgrade your plan to unlock paid video uploads.</p>
                                </template>
                            </div>
                            <div class="space-y-1.5" x-show="pricingTier === 'premium' || pricingTier === 'exclusive'" x-transition x-cloak>
                                <label class="text-[8px] font-black uppercase tracking-widest text-slate-400 px-1">Custom Price ({{ gs('cur_text') }})</label>
                                <div class="relative">
                                    <input type="number" step="1" min="0" name="price" x-model="price" class="w-full h-12 px-3 bg-slate-50 dark:bg-white/5 border border-transparent dark:border-white/5 rounded-lg text-slate-900 dark:text-white outline-none font-bold text-xs focus:ring-2 focus:ring-orange-500 transition-all" placeholder="0" />
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">{{ gs('cur_text') }}</span>
                                </div>
                                <p class="text-[8px] text-slate-400 px-1 mt-1 font-medium">You will earn {{ gs('ppv_creator_commission_percent') }}% of the revenue.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-white/5">
                        <button type="submit" 
                                class="w-full sm:w-auto px-6 h-10 gradient-orange text-white font-black text-[9px] uppercase tracking-widest rounded-lg shadow-md shadow-orange-500/25 active:scale-95 transition-all disabled:opacity-50 flex items-center justify-center min-w-[160px]"
                                :disabled="uploading">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-rounded text-sm" x-show="!uploading">rocket_launch</span>
                                <div class="w-3.5 h-3.5 border-2 border-white/20 border-t-white rounded-full animate-spin" x-show="uploading" x-cloak></div>
                                <span x-text="uploading ? 'Publishing...' : 'Publish Video'"></span>
                            </div>
                        </button>
                        <button type="button" @click="step = 2" class="w-full sm:w-auto h-10 px-6 rounded-lg text-slate-400 font-bold text-[10px] hover:text-slate-600 dark:hover:text-white transition-colors order-last sm:order-first bg-slate-50 dark:bg-white/5">
                            Modify Details
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Post-Publish Hint -->
        <div class="max-w-3xl mx-auto mt-6 p-4 sm:p-5 rounded-[1.25rem] bg-orange-500/[0.03] border border-orange-500/10 flex items-start gap-3 mb-6 md:mb-0">
            <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500 shrink-0">
                <span class="material-symbols-rounded text-base">verified_user</span>
            </div>
            <div>
                <p class="text-[8px] font-black text-orange-600/80 uppercase tracking-widest mb-0.5 leading-none">Security Protocol</p>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-relaxed">Your video will undergo automated copyright and quality scanning before going live.</p>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    
    /* Native App Styles */
    body {
        -webkit-tap-highlight-color: transparent;
    }
    
    input, select, textarea {
        font-size: 16px !important;
    }

    /* Custom Scrollbar for better UI */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Page-scoped icon visibility fix (dark mode only, this page only).
       The layout globally forces `.material-symbols-rounded` to `color: inherit`,
       which beats Tailwind's text-color utilities and leaves icons dark-on-dark
       in dark mode. */
    .dark #video-upload span.material-symbols-rounded.text-orange-500 { color: #f97316 !important; }
</style>

<script src="{{ asset('assets/js/tus.min.js') }}?v={{ filemtime(public_path('assets/js/tus.min.js')) }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js"></script>

<script>
    function mobileSwalOptions(extra = {}) {
        const isMobile = window.innerWidth < 768;
        return {
            width: isMobile ? '90%' : '420px',
            padding: isMobile ? '1.5rem' : '2rem',
            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            ...extra
        };
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('videoUpload', () => ({
            step: {{ old('title') ? 2 : 1 }},
            draftId: null,
            saveStatus: '',
            localFileStatus: 'idle',
            saveTimeout: null,
            videoPreview: null,
            thumbnailPreview: @json(($draft && $draft->thumbnail_path) ? getImage(getFilePath('thumbnail') . '/' . $draft->thumbnail_path) : null),
            videoFile: null,
            isUnplayableFormat: false,
            uploading: false,
            progress: 0,
            title: @json(old('title', '')),
            autoFilledTitle: '',
            categoryId: @json((string) old('category_id', $draft->category_id ?? '')),
            description: @json(old('description', '')),
            location: '',
            visibility: 0,
            isAgeRestricted: false,
            scheduleVideo: false,
            scheduleDate: '',
            scheduleTime: '',
            duration: '00:00',
            pricingTier: 'free',
            price: 0,
            hasPremiumAccess: {{ $hasPremiumAccess ? 'true' : 'false' }},
            tags: [],
            tagInput: '',
            
            bunnyId: null,
            bunnyStatus: null,
            bunnyEmbedUrl: @json(($draft && $draft->bunny_id) ? $draft->getBunnyEmbedUrl(true) : ''),
            tusParams: null,
            directParams: null,
            backgroundUploading: false,
            backgroundProgress: 0,
            uploadStartTime: null,
            uploadProgressText: '',

            formatFileSize(bytes) {
                if (!bytes || isNaN(bytes) || bytes <= 0) return '';
                const mb = bytes / (1024 * 1024);
                if (mb >= 1024) {
                    return (mb / 1024).toFixed(2) + ' GB';
                }
                return mb.toFixed(1) + ' MB';
            },

            updateUploadStats(loaded, total) {
                if (!loaded || !total) return;
                this.backgroundProgress = Math.round((loaded / total) * 100);

                if (!this.uploadStartTime) {
                    this.uploadStartTime = Date.now();
                }
                const elapsedSec = (Date.now() - this.uploadStartTime) / 1000;
                const bytesPerSec = elapsedSec > 0 ? (loaded / elapsedSec) : 0;

                let speedStr = '0 KB/S';
                if (bytesPerSec >= 1024 * 1024) {
                    speedStr = (bytesPerSec / (1024 * 1024)).toFixed(1) + ' MB/S';
                } else if (bytesPerSec >= 1024) {
                    speedStr = Math.round(bytesPerSec / 1024) + ' KB/S';
                } else {
                    speedStr = Math.round(bytesPerSec) + ' B/S';
                }

                const remainingBytes = total - loaded;
                let remainingStr = '~0S LEFT';
                if (bytesPerSec > 0 && remainingBytes > 0) {
                    const remainingSec = Math.round(remainingBytes / bytesPerSec);
                    if (remainingSec >= 60) {
                        const mins = Math.floor(remainingSec / 60);
                        const secs = remainingSec % 60;
                        remainingStr = `~${mins}M ${secs}S LEFT`;
                    } else {
                        remainingStr = `~${remainingSec}S LEFT`;
                    }
                }

                const loadedMB = (loaded / (1024 * 1024)).toFixed(1);
                const totalMB = (total / (1024 * 1024)).toFixed(1);

                this.uploadProgressText = `UPLOADING VIDEO... (${this.backgroundProgress}%) | ${loadedMB} / ${totalMB} MB | ${speedStr} | ${remainingStr}`;

                const swalStatusEl = document.getElementById('swal-upload-status');
                if (swalStatusEl) {
                    swalStatusEl.innerText = this.uploadProgressText;
                }
            },

            getUploadStatusText() {
                if (this.backgroundUploading) {
                    return this.uploadProgressText || `Uploading Background ${this.backgroundProgress}%`;
                }
                if (this.saveStatus === 'Saving...') return 'Saving Draft...';
                if (this.saveStatus.startsWith('Saved')) return 'Draft Saved ✓';
                return this.saveStatus;
            },
            
            logDraftEvent(event, extraData = {}) {
                try {
                    const payload = Object.assign({
                        event: event,
                        draft_id: this.draftId,
                        bunny_id: this.bunnyId,
                        file_name: this.videoFile ? this.videoFile.name : null,
                        file_size: this.videoFile ? this.videoFile.size : null,
                        upload_method: /Android/i.test(navigator.userAgent) ? 'Direct PUT' : 'TUS',
                        timestamp: new Date().toISOString()
                    }, extraData);

                    if (navigator.sendBeacon) {
                        const blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
                        navigator.sendBeacon("{{ route('videos.log_draft_event') }}", blob);
                    } else {
                        fetch("{{ route('videos.log_draft_event') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(payload),
                            keepalive: true
                        }).catch(e => {});
                    }
                } catch(e) {}
            },

            logProgress(percent, loaded, total) {
                const step = Math.floor(percent / 5) * 5;
                if (step > (this._lastLoggedPercent || 0)) {
                    this._lastLoggedPercent = step;
                    this.logDraftEvent('upload_progress', {
                        progress: step,
                        uploaded_bytes: loaded,
                        total_bytes: total
                    });
                }
            },

            init() {
                window.addEventListener('beforeunload', () => {
                    if (this.backgroundUploading || this.uploading) {
                        this.logDraftEvent('upload_interrupted', {
                            last_known_progress: this.backgroundProgress || this.progress || 0,
                            reason: 'User navigated away / closed page'
                        });
                    }
                    if (this.draftId || this.title || this.videoFile) {
                        this.autoSaveDraft(true);
                    }
                });

                window.addEventListener('pagehide', () => {
                    if (this.draftId || this.title || this.videoFile) {
                        this.autoSaveDraft(true);
                    }
                });

                const draftData = @json($draft ?? null);
                if (draftData) {
                    this.draftId = draftData.id;
                    this.bunnyId = draftData.bunny_id || null;
                    this.bunnyStatus = draftData.bunny_status || null;
                    this.title = draftData.title !== 'Untitled Draft' ? draftData.title : '';
                    this.categoryId = draftData.category_id || '{{ old('category_id') }}' || '';
                    this.description = draftData.description || '';
                    this.visibility = draftData.visibility || 0;
                    this.location = draftData.location || '';
                    
                    this.logDraftEvent('draft_reopened', { bunny_status: this.bunnyStatus });

                    if (this.draftId && this.bunnyId) {
                        fetch(`/videos/${this.draftId}/status`, { headers: { 'Accept': 'application/json' } })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success && data.bunny_status) {
                                    this.bunnyStatus = data.bunny_status;
                                }
                            }).catch(e => {});
                    }
                    
                    if (window.localforage) {
                        if (navigator.storage && navigator.storage.persist) {
                            navigator.storage.persist().catch(e => console.log('Storage persist request failed', e));
                        }
                        
                        localforage.getItem('draft_file_' + this.draftId).then(file => {
                            const isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

                            if (file) {
                                // Rule 1: Local file IS available in storage
                                this.videoFile = file;
                                this.saveStatus = '';
                                this.backgroundUploading = false;
                                this.videoPreview = URL.createObjectURL(file);
                                this.step = 2; 

                                this.$nextTick(() => {
                                    this.initPreviewPlayer(this.videoPreview);
                                });

                                // Automatically restart upload from byte 0 if Bunny is not ready/processing
                                if (this.bunnyStatus !== 'ready' && this.bunnyStatus !== 'processing' && this.bunnyStatus !== 'encoding') {
                                    this.checkOrResumeUpload();
                                }
                            } else if (this.bunnyStatus === 'ready' || this.bunnyStatus === 'processing' || this.bunnyStatus === 'encoding') {
                                // Rule 3: Bunny is already ready/processing/encoding
                                this.step = 2;
                            } else {
                                // Rule 2: Local file is MISSING and Bunny is not ready
                                this.step = 2;
                                if (window.Swal) {
                                    this.logDraftEvent('interrupted_prompt_shown', {
                                        draft_id: this.draftId,
                                        bunny_status: this.bunnyStatus
                                    });

                                    Swal.fire({
                                        title: 'Upload Interrupted',
                                        text: 'Your previous video upload was interrupted. Please select your video again to continue uploading.',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Select Video',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonColor: '#ff571a',
                                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                        customClass: {
                                            popup: 'rounded-[2rem]',
                                            confirmButton: 'rounded-xl font-bold px-6 py-3',
                                            cancelButton: 'rounded-xl font-bold px-6 py-3'
                                        }
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            const fileInput = document.querySelector('input[type="file"][accept*="video"]');
                                            if (fileInput) fileInput.click();
                                        }
                                    });
                                }
                            }
                        });
                    }
                }
                
                this.$watch('title', () => this.autoSaveDraft());
                this.$watch('categoryId', () => this.autoSaveDraft());
                this.$watch('description', () => this.autoSaveDraft());
                this.$watch('visibility', () => this.autoSaveDraft());

                if (window.localforage) {
                    fetch("{{ route('videos.active_drafts') }}")
                        .then(res => res.json())
                        .then(data => {
                            const activeDrafts = data.active_drafts || [];
                            localforage.keys().then(keys => {
                                keys.forEach(key => {
                                    if (key.startsWith('draft_file_')) {
                                        const keyId = parseInt(key.replace('draft_file_', ''), 10);
                                        if (!activeDrafts.includes(keyId)) {
                                            localforage.removeItem(key).catch(e => {});
                                        }
                                    }
                                });
                            }).catch(e => {});
                        })
                        .catch(e => console.warn('Could not fetch active drafts for cleanup', e));
                }

                window.addEventListener('pagehide', () => {
                    if (this.title || this.videoFile || this.draftId) {
                        const formData = new FormData();
                        if (this.draftId) formData.append('draft_id', this.draftId);
                        formData.append('title', this.title || 'Untitled Draft');
                        const catVal = this.categoryId || (document.querySelector('select[name="category_id"]') ? document.querySelector('select[name="category_id"]').value : '') || '';
                        if (catVal) formData.append('category_id', catVal);
                        formData.append('description', this.description || '');
                        formData.append('visibility', this.visibility || 'public');
                        formData.append('pricing_tier', this.pricingTier || 'free');
                        formData.append('price', this.price || 0);
                        formData.append('location', this.location || '');
                        formData.append('language', this.language || '');
                        formData.append('_token', '{{ csrf_token() }}');
                        if (navigator.sendBeacon) {
                            navigator.sendBeacon("{{ route('videos.auto_save_draft') }}", formData);
                        }
                    }
                });
            },

            async checkOrResumeUpload() {
                if (!this.videoFile) return;

                if (
                    this.bunnyStatus === 'processing' ||
                    this.bunnyStatus === 'encoding' ||
                    this.bunnyStatus === 'ready'
                ) {
                    return;
                }

                return this.startBackgroundUpload();
            },

            async startBackgroundUpload() {
                console.log('[UPLOAD DEBUG] background upload START', { draftId: this.draftId, backgroundUploading: this.backgroundUploading, hasFile: !!this.videoFile });
                if (!this.videoFile) return;
                if (this.backgroundUploading) return;
                if (
                    this.bunnyStatus === 'processing' ||
                    this.bunnyStatus === 'encoding' ||
                    this.bunnyStatus === 'ready'
                ) {
                    return;
                }
                
                // Reset interrupted status
                if (this.saveStatus === 'Upload Interrupted') {
                    this.saveStatus = '';
                }

                // If we don't have draftId yet, run immediate draft save to generate it
                if (!this.draftId) {
                    await new Promise(resolve => this.autoSaveDraft(true, false, resolve));
                }

                if (!this.draftId) return;

                const isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                this.backgroundUploading = true;
                const file = this.videoFile;

                console.log('[UPLOAD DEBUG] XHR/TUS START', { isMobile: isMobileDevice, tusParams: !!this.tusParams, directParams: !!this.directParams });

                if (isMobileDevice) {
                    // Mobile Branch: Non-TUS Direct Upload (Direct PUT to Bunny CDN)
                    console.log('[UPLOAD DEBUG] TRANSPORT = MOBILE_DIRECT_PUT', { directParams: this.directParams });
                    this.logDraftEvent('upload_start', { upload_method: 'Direct Upload (Mobile)' });

                    if (!this.directParams || !this.directParams.url || !this.directParams.access_key) {
                        console.log('[UPLOAD DEBUG] Mobile directParams missing, fetching from prepare_upload...');
                        try {
                            const formData = new FormData();
                            if (this.draftId) formData.append('draft_id', this.draftId);
                            formData.append('title', this.title || 'Untitled Video');
                            formData.append('is_draft', '1');
                            const prepRes = await fetch("{{ route('videos.prepare_upload') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: formData
                            });
                            const prepData = await prepRes.json();
                            if (prepData.success && prepData.direct) {
                                this.directParams = prepData.direct;
                                if (prepData.tus) this.tusParams = prepData.tus;
                                if (prepData.bunny_id) this.bunnyId = prepData.bunny_id;
                                if (prepData.video_id) this.draftId = prepData.video_id;
                            }
                        } catch(e) {
                            console.error('[UPLOAD DEBUG] Failed to fetch directParams:', e);
                        }
                    }
                    
                    if (this.directParams && this.directParams.url && this.directParams.access_key) {
                        const xhr = new XMLHttpRequest();
                        this._activeMobileXHR = xhr;
                        console.log('[UPLOAD DEBUG] XHR CREATED', { url: this.directParams.url });

                        xhr.open('PUT', this.directParams.url, true);
                        console.log('[UPLOAD DEBUG] XHR OPEN');

                        xhr.setRequestHeader('AccessKey', this.directParams.access_key);
                        xhr.setRequestHeader('Content-Type', 'application/octet-stream');
                        console.log('[UPLOAD DEBUG] XHR HEADERS SET');

                        xhr.upload.onprogress = (e) => {
                            if (e.lengthComputable && e.total > 0) {
                                this.updateUploadStats(e.loaded, e.total);
                                this.logProgress(this.backgroundProgress, e.loaded, e.total);
                            }
                        };

                        xhr.onload = () => {
                            console.log('[UPLOAD DEBUG] XHR LOAD', { status: xhr.status, responseText: xhr.responseText?.substring(0, 100) });
                            if (xhr.status >= 200 && xhr.status < 300) {
                                this.backgroundUploading = false;
                                this.bunnyStatus = 'processing';
                                this.saveStatus = 'Upload Complete ✓';
                                this.logDraftEvent('upload_completed', { progress: 100, upload_method: 'Direct PUT (Bunny CDN)' });
                            } else {
                                console.warn('[UPLOAD DEBUG] Direct PUT to Bunny returned status', xhr.status, 'Falling back to server direct upload...');
                                this.uploadViaServerDirect(file);
                            }
                        };

                        xhr.onerror = (err) => {
                            console.warn('[UPLOAD DEBUG] XHR ERROR', { status: xhr.status, err });
                            this.uploadViaServerDirect(file);
                        };

                        xhr.onabort = () => {
                            console.warn('[UPLOAD DEBUG] XHR ABORT');
                        };

                        xhr.ontimeout = () => {
                            console.warn('[UPLOAD DEBUG] XHR TIMEOUT');
                        };

                        console.log('[UPLOAD DEBUG] XHR SEND START', { fileSize: file?.size });
                        xhr.send(file);
                    } else {
                        console.warn('[UPLOAD DEBUG] No directParams available, falling back to uploadViaServerDirect');
                        this.uploadViaServerDirect(file);
                    }
                    return;
                }

                // Desktop Branch: TUS Upload
                if (!this.tusParams) {
                    await new Promise(resolve => this.autoSaveDraft(true, false, resolve));
                }
                if (!this.tusParams) return;

                this.logDraftEvent('upload_start', { upload_method: 'TUS' });

                const uploadRoutes = [
                    'https://video.bunnycdn.com/tusupload',
                    this.tusParams.endpoint
                ];

                const startTusWithRoute = (routeIdx = 0) => {
                    const endpoint = uploadRoutes[routeIdx] || uploadRoutes[0];
                    const routeChunkSize = routeIdx === 0 ? (2 * 1024 * 1024) : (512 * 1024);

                    const tusUpload = new tus.Upload(file, {
                        endpoint: endpoint,
                        retryDelays: [0, 1000, 3000, 5000, 10000, 15000],
                        chunkSize: routeChunkSize,
                        headers: this.tusParams.headers,
                        metadata: { 
                            filename: file.name || 'video.mp4', 
                            filetype: file.type || 'video/mp4' 
                        },
                        storeFingerprintForResuming: true,
                        removeFingerprintOnSuccess: true,
                        fingerprint: (f, options) => Promise.resolve(['tus', this.draftId || this.bunnyId || (f ? f.name : 'file'), f ? f.size : 0].join('_')),
                        onProgress: (bytesUploaded, bytesTotal) => {
                            this.updateUploadStats(bytesUploaded, bytesTotal);
                            this.logProgress(this.backgroundProgress, bytesUploaded, bytesTotal);
                        },
                        onSuccess: () => {
                            this.backgroundUploading = false;
                            this.bunnyStatus = 'processing';
                            this.saveStatus = 'Upload Complete ✓';
                            this.logDraftEvent('upload_completed', { progress: 100, upload_method: 'TUS' });
                        },
                        onError: (error) => {
                            console.warn(`TUS upload error on route ${routeIdx}:`, error);
                            if (routeIdx < uploadRoutes.length - 1) {
                                console.log(`Switching TUS route to ${uploadRoutes[routeIdx + 1]}...`);
                                startTusWithRoute(routeIdx + 1);
                            } else {
                                this.backgroundUploading = false;
                                this.saveStatus = 'Upload Interrupted';
                                this.logDraftEvent('upload_error', { reason: (error && error.message ? error.message : String(error)) || String(error) });
                            }
                        }
                    });

                    tusUpload.findPreviousUploads().then(previousUploads => {
                        if (previousUploads.length) {
                            tusUpload.resumeFromPreviousUpload(previousUploads[0]);
                        }
                        tusUpload.start();
                    }).catch(() => {
                        tusUpload.start();
                    });
                };

                startTusWithRoute(0);
            },

            uploadViaServerDirect(file) {
                console.log('[UPLOAD DEBUG] uploadViaServerDirect START', { hasFile: !!file, draftId: this.draftId });
                if (!file || !this.draftId) return;

                const formData = new FormData();
                formData.append('video', file);
                formData.append('video_id', this.draftId);

                let targetUrl = "{{ route('videos.direct_upload') }}";
                if (window.location.protocol === 'https:' && targetUrl.startsWith('http:')) {
                    targetUrl = targetUrl.replace('http:', 'https:');
                }

                const xhr = new XMLHttpRequest();
                this._activeServerXHR = xhr;
                console.log('[UPLOAD DEBUG] SERVER XHR CREATED', targetUrl);

                xhr.open('POST', targetUrl, true);
                xhr.withCredentials = true;
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                xhr.upload.onprogress = (e) => {
                    if (e.lengthComputable && e.total > 0) {
                        this.updateUploadStats(e.loaded, e.total);
                        this.logProgress(this.backgroundProgress, e.loaded, e.total);
                    }
                };

                xhr.onload = () => {
                    console.log('[UPLOAD DEBUG] SERVER XHR LOAD', { status: xhr.status, responseText: xhr.responseText?.substring(0, 100) });
                    if (xhr.status >= 200 && xhr.status < 300) {
                        this.backgroundUploading = false;
                        this.bunnyStatus = 'ready';
                        this.saveStatus = 'Upload Complete ✓';
                        this.logDraftEvent('upload_completed', { progress: 100, upload_method: 'Direct Upload (Server)' });
                    } else {
                        console.error('[UPLOAD DEBUG] Direct upload server error:', xhr.status, xhr.responseText);
                        this.backgroundUploading = false;
                        this.saveStatus = 'Upload Interrupted';
                        this.logDraftEvent('upload_error', { reason: (xhr.responseText || ('Server returned status ' + xhr.status)) });
                    }
                };

                xhr.onerror = (err) => {
                    console.error('[UPLOAD DEBUG] SERVER XHR ERROR', err);
                    this.backgroundUploading = false;
                    this.saveStatus = 'Upload Interrupted';
                    this.logDraftEvent('upload_error', { reason: 'Network error during server direct upload' });
                };

                xhr.onabort = () => {
                    console.warn('[UPLOAD DEBUG] SERVER XHR ABORT');
                };

                console.log('[UPLOAD DEBUG] SERVER XHR SEND START');
                xhr.send(formData);
            },

            updateUploadStats(loaded, total) {
                if (!total || total <= 0) return;

                const now = Date.now();
                if (!this._uploadStartTime) {
                    this._uploadStartTime = now;
                    this._lastBytes = loaded;
                    this._lastTime = now;
                    this._lastUiRenderTime = 0;
                }

                // Throttle reactive UI/DOM updates to once every ~120ms to eliminate main-thread rendering overhead.
                // CRITICAL REQUIREMENT: If loaded >= total or loaded === total, bypass the throttle immediately to guarantee 100% UI state.
                const isComplete = (loaded >= total);
                if (!isComplete && this._lastUiRenderTime && (now - this._lastUiRenderTime < 120)) {
                    return;
                }
                this._lastUiRenderTime = now;

                const percent = Math.min(100, Math.round((loaded / total) * 100));
                this.backgroundProgress = percent;

                const uploadedMB = (loaded / (1024 * 1024)).toFixed(1);
                const totalMB = (total / (1024 * 1024)).toFixed(1);

                const timeDiff = (now - this._lastTime) / 1000;
                let mbps = this._lastSpeed || '0.0';
                let timeStr = this._lastTimeStr || 'calculating...';

                if (timeDiff >= 0.3 || isComplete) {
                    const bytesDiff = loaded - this._lastBytes;
                    const bps = bytesDiff / (timeDiff || 1);
                    mbps = (bps / (1024 * 1024)).toFixed(1);

                    const remainingBytes = total - loaded;
                    const secondsRemaining = Math.max(0, Math.ceil(remainingBytes / (bps || 1)));

                    this._lastBytes = loaded;
                    this._lastTime = now;
                    this._lastSpeed = mbps;

                    timeStr = secondsRemaining > 60
                        ? `~${Math.ceil(secondsRemaining / 60)}M LEFT`
                        : `~${secondsRemaining}S LEFT`;
                    this._lastTimeStr = timeStr;
                }

                const progressStr = `UPLOADING VIDEO... (${percent}%) | ${uploadedMB} / ${totalMB} MB | ${mbps} MB/S | ${timeStr.toUpperCase()}`;
                this.uploadProgressText = progressStr;

                const swalStatusEl = document.getElementById('swal-upload-status');
                if (swalStatusEl) {
                    swalStatusEl.innerText = progressStr;
                }

                const swalCircleEl = document.getElementById('swal-progress-circle');
                const swalPercentEl = document.getElementById('swal-percent-text');
                if (swalCircleEl) {
                    const dashoffset = 283 - (283 * percent / 100);
                    swalCircleEl.style.strokeDashoffset = dashoffset;
                }
                if (swalPercentEl) {
                    swalPercentEl.innerText = `${percent}%`;
                }
            },
            
            autoSaveDraft(isImmediate = false, resetVideo = false, callback = null) {
                console.log('[UPLOAD DEBUG] draft creation START', { isImmediate, hasDraftId: !!this.draftId, draftId: this.draftId, isAutoSaving: this.isAutoSaving, hasPromise: !!this._draftCreationPromise });
                if (!this.title && !this.videoFile) {
                    if (callback) callback();
                    return;
                }
                
                clearTimeout(this.saveTimeout);
                
                // Single-flight mechanism: if initial draft creation is in-flight, await it instead of polling setTimeouts
                if (this._draftCreationPromise && !this.draftId) {
                    console.log('[UPLOAD DEBUG] Draft creation already in-flight, awaiting existing creation promise...');
                    this._draftCreationPromise.then(() => {
                        if (callback) callback();
                    });
                    return;
                }

                if (this.isAutoSaving && !isImmediate) {
                    this.saveTimeout = setTimeout(() => this.autoSaveDraft(isImmediate, resetVideo, callback), 1000);
                    return;
                }

                this.isAutoSaving = true;
                this.saveStatus = 'Saving...';
                
                const executeSave = () => {
                    const formData = new FormData();
                    if (this.draftId) formData.append('draft_id', this.draftId);
                    formData.append('title', this.title || 'Untitled Draft');
                    const catVal = this.categoryId || (document.querySelector('select[name="category_id"]') ? document.querySelector('select[name="category_id"]').value : '') || '';
                    if (catVal) formData.append('category_id', catVal);
                    formData.append('description', this.description);
                    formData.append('visibility', this.visibility);
                    formData.append('pricing_tier', this.pricingTier);
                    formData.append('price', this.price);
                    if (this.location) formData.append('location', this.location);
                    if (this.bunnyStatus) formData.append('bunny_status', this.bunnyStatus);
                    if (resetVideo) formData.append('reset_video', '1');
                    
                    const p = fetch("{{ route('videos.auto_save_draft') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.draft_id) {
                            console.log('[UPLOAD DEBUG] draft creation SUCCESS', data.draft_id);
                            console.log('[UPLOAD DEBUG] bunny_id received', data.bunny_id);
                            const oldDraftId = this.draftId;
                            this.draftId = data.draft_id;
                            if (data.bunny_id) this.bunnyId = data.bunny_id;
                            if (data.tus) this.tusParams = data.tus;
                            if (data.direct) this.directParams = data.direct;
                            
                            this.saveStatus = 'Saved ' + new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            
                            if (!oldDraftId && this.videoFile && window.localforage) {
                                localforage.getItem('draft_file_temp').then(tempFile => {
                                    if (tempFile) {
                                        localforage.setItem('draft_file_' + this.draftId, tempFile).then(() => {
                                            localforage.removeItem('draft_file_temp');
                                            this.localFileStatus = 'saved';
                                        }).catch(e => {});
                                    }
                                }).catch(e => {});
                            }

                            // Trigger immediate background upload when file and params are ready
                            const uploadNeeded =
                                this.videoFile &&
                                !this.backgroundUploading &&
                                !['processing', 'encoding', 'ready'].includes(this.bunnyStatus);

                            if (uploadNeeded) {
                                this.startBackgroundUpload();
                            }
                        } else {
                            this.saveStatus = 'Save failed';
                        }
                        this.isAutoSaving = false;
                        this._draftCreationPromise = null;
                        if (callback) callback();
                    })
                    .catch(err => {
                        console.error('Auto-save failed', err);
                        this.saveStatus = 'Offline (Will retry later)';
                        this.isAutoSaving = false;
                        this._draftCreationPromise = null;
                        if (callback) callback();
                    });

                    if (!this.draftId) {
                        this._draftCreationPromise = p;
                    }
                };
                
                if (isImmediate) {
                    executeSave();
                } else {
                    this.saveTimeout = setTimeout(executeSave, 1500);
                }
            },
            
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
                const h = Math.floor(seconds / 3600);
                const m = Math.floor((seconds % 3600) / 60);
                const s = Math.floor(seconds % 60);
                if (h > 0) return `${h}:${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
                return `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
            },
            
            validateAndProceed(targetStep) {
                if (!this.title) {
                    Swal.fire({
                        title: 'Missing Title',
                        text: 'Please give your story a name before proceeding.',
                        icon: 'warning',
                        confirmButtonText: 'Got it',
                        confirmButtonColor: '#ff571a',
                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                        customClass: {
                            popup: 'rounded-[2rem]',
                            confirmButton: 'rounded-xl font-bold px-8 py-3'
                        }
                    });
                    return;
                }

                const catId = (document.querySelector('select[name="category_id"]') ? document.querySelector('select[name="category_id"]').value : '');
                if (!catId) {
                    Swal.fire({
                        title: 'Select Category',
                        text: 'Choose a category to help viewers find your content.',
                        icon: 'warning',
                        confirmButtonText: 'Select Now',
                        confirmButtonColor: '#ff571a',
                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                        customClass: {
                            popup: 'rounded-[2rem]',
                            confirmButton: 'rounded-xl font-bold px-8 py-3'
                        }
                    });
                    return;
                }

                this.step = targetStep;
            },

            confirmBack() {
                // Ensure current draft metadata is saved before navigating away
                this.autoSaveDraft(true, false, () => {
                    window.location.href = "{{ route('studio.videos') }}";
                });
            },

            onVideoChange(e) {
                const selectedFile = e.target.files ? e.target.files[0] : null;
                if (!selectedFile) return;

                // Section A: Store single synchronous File reference intact (do not clear input.value)
                this.videoFile = selectedFile;
                this.isUnplayableFormat = false;
                this.saveStatus = '';
                this.bunnyStatus = 'uploading';
                this.backgroundProgress = 0;
                this.backgroundUploading = false;

                // Section G: Revoke previous preview URL when selecting a new video
                if (this._activePreviewUrl) {
                    try { URL.revokeObjectURL(this._activePreviewUrl); } catch(err) {}
                    this._activePreviewUrl = null;
                }

                // Section B: Immediate preview Object URL creation & assignment
                const ext = selectedFile.name ? selectedFile.name.split('.').pop().toLowerCase() : 'mp4';
                const mimeTypes = {
                    'mp4': 'video/mp4',
                    'mov': 'video/quicktime',
                    'avi': 'video/x-msvideo',
                    'mkv': 'video/x-matroska',
                    'webm': 'video/webm',
                    '3gp': 'video/3gpp',
                    'm4v': 'video/x-m4v'
                };
                const fileMime = selectedFile.type || mimeTypes[ext] || 'video/mp4';

                const previewBlob = (ext === 'mkv' || ext === 'avi' || (selectedFile.type && (selectedFile.type.includes('matroska') || selectedFile.type.includes('mkv'))))
                    ? new Blob([selectedFile], { type: 'video/mp4' })
                    : selectedFile;

                const previewUrl = URL.createObjectURL(previewBlob);
                this.videoPreview = previewUrl;
                this._activePreviewUrl = previewUrl;

                this.duration = '00:00';
                this.step = 2;

                // Auto-fill title if empty or if previously auto-filled
                if (selectedFile.name) {
                    let name = selectedFile.name.replace(/\.[^/.]+$/, '');
                    if (name.toLowerCase() === 'image' || name.toLowerCase() === 'video' || /^\d+$/.test(name)) {
                        name = 'Video ' + new Date().toLocaleDateString();
                    }
                    const generatedTitle = name.replace(/[_-]/g, ' ');
                    if (!this.title || (this.autoFilledTitle && this.title === this.autoFilledTitle)) {
                        this.title = generatedTitle;
                        this.autoFilledTitle = generatedTitle;
                    }
                }

                // Section B: Initialize Preview Player after Alpine DOM update
                this.$nextTick(() => {
                    this.initPreviewPlayer(previewUrl);
                });

                // Section C & E: Independent best-effort background IndexedDB save
                this.saveFileToIndexedDBBestEffort(selectedFile);

                // Section F & I: Single-flight draft save & background upload initiation
                this.autoSaveDraft(true, false);
            },

            saveFileToIndexedDBBestEffort(file) {
                if (!window.localforage || !file) return;
                const cacheKey = this.draftId ? ('draft_file_' + this.draftId) : 'draft_file_temp';
                this.localFileStatus = 'saving';
                localforage.setItem(cacheKey, file)
                    .then(() => {
                        this.localFileStatus = 'saved';
                    })
                    .catch(err => {
                        console.warn('[LocalCache] Best-effort localforage save failed (non-critical):', err?.message || err);
                        this.localFileStatus = 'failed';
                        // Non-blocking: DO NOT interrupt preview, upload, or draft progress
                    });
            },

            initPreviewPlayer(url) {
                if (!url) return;

                const player = this.$refs.previewPlayer;
                if (!player) return;

                player.muted = true;
                player.defaultMuted = true;
                player.playsInline = true;
                player.setAttribute('muted', '');
                player.setAttribute('playsinline', 'true');
                player.setAttribute('webkit-playsinline', 'true');

                player.onerror = () => {
                    if (player.error && player.error.code === MediaError.MEDIA_ERR_SRC_NOT_SUPPORTED) {
                        console.warn('Video preview playback warning - unsupported codec or browser restriction', player.error);
                        this.isUnplayableFormat = true;
                    }
                };

                const onMetadata = () => {
                    const d = player.duration;
                    if (d && isFinite(d) && d !== Infinity) {
                        this.duration = this.formatTime(d);
                    }
                };

                player.onloadedmetadata = onMetadata;
                if (player.readyState >= 1 && player.duration && isFinite(player.duration)) {
                    onMetadata();
                }

                if (player.src !== url) {
                    player.src = url;
                    try { player.load(); } catch(e) {}
                }

                const triggerPlay = () => {
                    const p = player.play();
                    if (p && p.catch) {
                        p.catch(err => {
                            // Silent catch for autoplay policy
                        });
                    }
                };

                if (player.readyState >= 2) {
                    triggerPlay();
                } else {
                    player.addEventListener('canplay', triggerPlay, { once: true });
                    player.addEventListener('loadeddata', triggerPlay, { once: true });
                }
            },

            removeVideo() {
                const player = this.$refs.previewPlayer;
                if (player) {
                    try { player.pause(); player.removeAttribute('src'); player.load(); } catch(e) {}
                    player.onloadedmetadata = null;
                    player.onseeked = null;
                    player.onerror = null;
                    delete player._activePreviewUrl;
                }
                if (this._activePreviewUrl) {
                    try { URL.revokeObjectURL(this._activePreviewUrl); } catch(err) {}
                    this._activePreviewUrl = null;
                }
                this.videoFile = null;
                this.videoPreview = null;
                this.duration = '00:00';
                this.step = 1;
                if (this.autoFilledTitle && this.title === this.autoFilledTitle) {
                    this.title = '';
                    this.autoFilledTitle = '';
                }
                const input = document.querySelector('input[name="video"]');
                if (input) input.value = '';
            },
            onThumbnailChange(e) {
                const file = e.target.files[0];
                if (file) {
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type) && !file.name.toLowerCase().match(/\.(jpe?g|png|gif|webp)$/)) {
                        Swal.fire({
                            title: 'Invalid Format',
                            text: 'Please select a valid image file (JPEG, PNG, GIF, or WebP).',
                            icon: 'error',
                            confirmButtonColor: '#ff571a',
                            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                            customClass: {
                                popup: 'rounded-[2rem]',
                                confirmButton: 'rounded-xl font-bold px-8 py-3'
                            }
                        });
                        e.target.value = '';
                        this.thumbnailPreview = null;
                        return;
                    }
                    this.thumbnailFile = file;
                    const reader = new FileReader();
                    reader.onload = (e) => this.thumbnailPreview = e.target.result;
                    reader.readAsDataURL(file);
                }
            },
            removeThumbnail() {
                this.thumbnailPreview = null;
                const input = document.querySelector('input[name="thumbnail"]');
                if (input) input.value = '';
            },

            async startUpload(isDraft = false) {
                if (this.uploading) return;
                
                this.uploading = true;
                if (this.saveStatus === 'Upload Interrupted' || this.saveStatus === 'Upload Failed') {
                    this.saveStatus = '';
                }

                // 1. Recover video file from localforage if memory reference was lost
                if (!this.videoFile && window.localforage) {
                    try {
                        const cachedFile = (this.draftId ? await localforage.getItem('draft_file_' + this.draftId) : null) || await localforage.getItem('draft_file_temp');
                        if (cachedFile && cachedFile instanceof File) {
                            this.videoFile = cachedFile;
                        }
                    } catch(e) {
                        console.warn('Failed to retrieve file from localforage:', e);
                    }
                }

                const isAlreadyUploaded = ['processing', 'encoding', 'ready'].includes(this.bunnyStatus);

                // 2. If upload never completed and we still don't have a file, prompt user to re-select
                if (!isAlreadyUploaded && !this.videoFile && !this.backgroundUploading) {
                    this.uploading = false;
                    if (window.Swal) {
                        Swal.fire({
                            title: 'Video File Required',
                            text: 'The background upload was interrupted or incomplete. Please select your video file again.',
                            icon: 'warning',
                            confirmButtonText: 'Select Video',
                            confirmButtonColor: '#ff571a',
                            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                            customClass: {
                                popup: 'rounded-[2rem]',
                                confirmButton: 'rounded-xl font-bold px-8 py-3'
                            }
                        }).then(() => {
                            const input = document.querySelector('input[type=file][name=video]');
                            if (input) input.click();
                        });
                    }
                    return;
                }

                // 3. Set accurate initial status text
                let initialStatusText = 'UPLOADING VIDEO...';
                if (!isAlreadyUploaded) {
                    initialStatusText = `UPLOADING VIDEO... (${Math.round(this.backgroundProgress || 0)}%)`;
                } else if (['processing', 'encoding'].includes(this.bunnyStatus)) {
                    initialStatusText = 'UPLOAD COMPLETE — PROCESSING VIDEO...';
                } else if (this.bunnyStatus === 'ready') {
                    initialStatusText = 'VIDEO READY — PUBLISHING...';
                }
                
                if (window.Swal) {
                    const initPercent = Math.round(this.backgroundProgress || 0);
                    const initDashoffset = 283 - (283 * initPercent / 100);
                    Swal.fire(mobileSwalOptions({
                        html: `
                            <div class="flex flex-col items-center justify-center p-2 text-center">
                                <div class="relative w-24 h-24 mb-5 mx-auto flex items-center justify-center">
                                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="45" stroke="#ff571a" stroke-opacity="0.15" stroke-width="8" fill="transparent" />
                                        <circle id="swal-progress-circle" cx="50" cy="50" r="45" stroke="#ff571a" stroke-width="8" stroke-linecap="round" fill="transparent" class="transition-all duration-300" stroke-dasharray="283" stroke-dashoffset="${initDashoffset}" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span id="swal-percent-text" class="text-base font-black text-slate-900 dark:text-white">${initPercent}%</span>
                                    </div>
                                </div>
                                <h3 class="text-xl font-black uppercase tracking-wider text-slate-900 dark:text-white mb-3">PLEASE WAIT</h3>
                                <div id="swal-upload-status" class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest leading-relaxed max-w-xs mx-auto">
                                    ${initialStatusText}
                                </div>
                            </div>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-[2rem] p-6 sm:p-8 shadow-2xl border border-slate-100 dark:border-white/10'
                        }
                    }));
                }

                try {
                    // Ensure background upload is started if user clicked Publish before file transfer began or after interruption
                    if (!this.backgroundUploading && !isAlreadyUploaded && this.videoFile) {
                        console.log('[PUBLISH] Triggering direct background upload to Bunny CDN...');
                        this.startBackgroundUpload();
                    }

                    // PHASE 1: Wait for HTTP byte transfer to complete (backgroundUploading reaching 100%)
                    if (this.backgroundUploading || (!isAlreadyUploaded && this.videoFile)) {
                        await new Promise((resolve, reject) => {
                            const checkInterval = setInterval(() => {
                                const swalStatusEl = document.getElementById('swal-upload-status');
                                const swalCircleEl = document.getElementById('swal-progress-circle');
                                const swalPercentEl = document.getElementById('swal-percent-text');
                                const currentPct = Math.round(this.backgroundProgress || 0);
                                if (swalStatusEl && this.uploadProgressText) {
                                    swalStatusEl.innerText = this.uploadProgressText;
                                }
                                if (swalCircleEl) {
                                    swalCircleEl.style.strokeDashoffset = 283 - (283 * currentPct / 100);
                                }
                                if (swalPercentEl) {
                                    swalPercentEl.innerText = `${currentPct}%`;
                                }
                                if (!this.backgroundUploading || (this.backgroundProgress >= 100) || ['processing', 'encoding', 'ready'].includes(this.bunnyStatus) || (this.saveStatus && this.saveStatus.includes('Upload Complete'))) {
                                    clearInterval(checkInterval);
                                    resolve();
                                } else if (this.saveStatus === 'Upload Interrupted' || this.saveStatus === 'Upload Failed') {
                                    clearInterval(checkInterval);
                                    reject(new Error(this.saveStatus || 'Upload failed'));
                                }
                            }, 300);
                        });
                    }

                    // Ensure metadata auto-save has completed and draftId is set
                    await new Promise(resolve => this.autoSaveDraft(true, false, resolve));

                    // UNIFIED CREATION PAGE UX: Once byte transfer to Bunny completes, save video as PUBLISHED and redirect immediately to Studio without showing processing modal or polling
                    if (!isDraft) {
                        try {
                            const formData = new FormData();
                            if (this.title) formData.append('title', this.title);
                            if (this.draftId) formData.append('draft_id', this.draftId);
                            const catVal = this.categoryId || (document.querySelector('select[name="category_id"]') ? document.querySelector('select[name="category_id"]').value : '') || '';
                            if (catVal) formData.append('category_id', catVal);
                            formData.append('description', this.description);
                            formData.append('visibility', this.visibility);
                            formData.append('language', this.language || '');
                            formData.append('location', this.location);
                            formData.append('duration', this.duration);
                            formData.append('pricing_tier', this.pricingTier);
                            formData.append('price', this.price);
                            formData.append('is_age_restricted', this.isAgeRestricted ? 1 : 0);
                            formData.append('schedule_video', this.scheduleVideo ? 1 : 0);
                            formData.append('schedule_date', this.scheduleDate);
                            formData.append('schedule_time', this.scheduleTime);
                            formData.append('tags', JSON.stringify(this.tags));
                            formData.append('is_draft', '0');

                            await fetch("{{ route('videos.prepare_upload') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: formData
                            });
                        } catch(e) {
                            console.warn('Failed to send publish status during redirect:', e);
                        }

                        this.uploading = false;
                        if (window.localforage && this.draftId) {
                            localforage.removeItem('draft_file_' + this.draftId);
                        }
                        if (window.Swal) Swal.close();
                        notify('success', 'Video published successfully! Processing in background.');
                        window.location.href = "{{ route('studio.videos') }}";
                        return;
                    }

                    // PHASE 3: Authoritative Final Publish Transition (only when Bunny is genuinely READY)
                    const swalStatusEl = document.getElementById('swal-upload-status');
                    if (swalStatusEl) {
                        swalStatusEl.innerText = 'VIDEO READY — PUBLISHING...';
                    }

                    const formData = new FormData();
                    if (this.title) formData.append('title', this.title);
                    if (this.draftId) formData.append('draft_id', this.draftId);
                    const catVal = this.categoryId || (document.querySelector('select[name="category_id"]') ? document.querySelector('select[name="category_id"]').value : '') || '';
                    if (catVal) formData.append('category_id', catVal);
                    formData.append('description', this.description);
                    formData.append('visibility', this.visibility);
                    formData.append('language', this.language || '');
                    formData.append('location', this.location);
                    formData.append('duration', this.duration);
                    formData.append('pricing_tier', this.pricingTier);
                    formData.append('price', this.price);
                    formData.append('is_age_restricted', this.isAgeRestricted ? 1 : 0);
                    formData.append('schedule_video', this.scheduleVideo ? 1 : 0);
                    formData.append('schedule_date', this.scheduleDate);
                    formData.append('schedule_time', this.scheduleTime);
                    formData.append('tags', JSON.stringify(this.tags));
                    if (isDraft) formData.append('is_draft', '1');

                    const prepRes = await fetch("{{ route('videos.prepare_upload') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    if (!prepRes.ok) {
                        const errText = await prepRes.text().catch(() => '');
                        throw new Error(`Publish failed (HTTP ${prepRes.status}): ${errText.slice(0, 150)}`);
                    }

                    const prepData = await prepRes.json();
                    if (!prepData.success) {
                        throw new Error(prepData.message || 'Failed to publish video');
                    }

                    const thumbInput = document.querySelector('input[name="thumbnail"]');
                    if (thumbInput && thumbInput.files[0]) {
                        if (swalStatusEl) {
                            swalStatusEl.innerText = 'UPLOADING THUMBNAIL...';
                        }

                        const thumbData = new FormData();
                        thumbData.append('thumbnail', thumbInput.files[0]);
                        await fetch(`/videos/${prepData.video_slug}/thumbnail`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: thumbData
                        });
                    }

                    this.uploading = false;
                    if (window.localforage && this.draftId) {
                        localforage.removeItem('draft_file_' + this.draftId);
                    }
                    notify('success', 'Video published successfully');
                    window.location.href = "{{ route('studio.dashboard') }}";

                } catch (err) {
                    this.uploading = false;
                    if (window.Swal) Swal.close();
                    notify('error', err.message || 'An error occurred during publish');
                    console.error('Upload Error:', err);
                }
            },

        }));
    });
</script>

@endsection

