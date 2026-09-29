@extends('layouts.app')

@section('content')
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-4xl mx-auto px-6">
            <div class="mb-8">
                <a href="{{ route('studio.reels') }}" class="text-sm font-bold text-gray-400 hover:text-orange-500 transition-colors flex items-center gap-1 mb-4"><span class="material-symbols-rounded text-sm">arrow_back</span> Back to Reels</a>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white">Edit Reel</h1>
            </div>
            <form action="{{ route('studio.reels.update', $reel) }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  @submit="addTag(); window.showPublishingLoader('reel')"
                  class="space-y-8"
                  x-data="{ 
                      tags: {{ json_encode($reel->hashtags->pluck('hashtag')->values()->toArray()) }}, 
                      tagInput: '',
                      visibility: '{{ old('visibility', $reel->visibility) }}',
                      isAgeRestricted: {{ old('is_age_restricted', $reel->is_age_restricted) ? 'true' : 'false' }},
                      allowComments: {{ old('allow_comments', $reel->allow_comments) ? 'true' : 'false' }},
                      allowDuet: {{ old('allow_duet', $reel->allow_duet) ? 'true' : 'false' }},
                      allowStitch: {{ old('allow_stitch', $reel->allow_stitch) ? 'true' : 'false' }},
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
                      }
                  }">
                @csrf @method('PUT')
                <div class="bg-white dark:bg-[#1A1A1A] rounded-[2rem] border border-gray-100 dark:border-white/5 p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Title</label>
                        <input type="text" name="title" required value="{{ old('title', $reel->title) }}" class="w-full h-14 px-5 bg-gray-50 dark:bg-white/5 border-0 rounded-2xl text-gray-900 dark:text-white font-bold focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Description <span class="text-orange-500">#hashtags @mentions</span></label>
                        <textarea name="description" rows="4" class="w-full px-5 py-4 bg-gray-50 dark:bg-white/5 border-0 rounded-2xl text-gray-900 dark:text-white font-medium resize-none focus:ring-2 focus:ring-orange-500">{{ old('description', $reel->description) }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Category</label>
                            <select name="category_id" class="w-full h-14 px-5 bg-gray-50 dark:bg-white/5 border-0 rounded-2xl font-bold text-sm text-slate-900 dark:text-white">
                                <option value="">None</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $reel->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Language</label>
                            <select name="language" required class="w-full h-14 px-5 bg-gray-50 dark:bg-white/5 border-0 rounded-2xl font-bold text-sm text-slate-900 dark:text-white">
                                @foreach(config('languages') as $code => $name)
                                    <option value="{{ $name }}" {{ $reel->language == $name ? 'selected' : ($name == 'English' && !$reel->language ? 'selected' : '') }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Settings</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <input type="hidden" name="visibility" :value="visibility">
                            <input type="hidden" name="is_age_restricted" :value="isAgeRestricted ? '1' : '0'">
                            <input type="hidden" name="allow_comments" :value="allowComments ? '1' : '0'">
                            <input type="hidden" name="allow_duet" :value="allowDuet ? '1' : '0'">
                            <input type="hidden" name="allow_stitch" :value="allowStitch ? '1' : '0'">

                            <label class="flex items-center justify-between p-3 bg-slate-50 dark:bg-white/5 rounded-xl cursor-pointer group active:scale-[0.99] transition-all">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-slate-400 group-hover:text-orange-500 transition-colors text-lg">visibility</span>
                                    <span class="text-xs font-bold text-slate-700 dark:text-white">Public</span>
                                </div>
                                <button type="button" @click="visibility = visibility == '0' ? '1' : '0'" class="w-10 h-5 rounded-full relative transition-all duration-300" :class="visibility == '0' ? 'gradient-orange shadow-lg shadow-orange-500/20' : 'bg-slate-200 dark:bg-white/10'">
                                    <div class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-all duration-300" :class="visibility == '0' ? 'translate-x-5' : 'translate-x-0.5'"></div>
                                </button>
                            </label>
                            <label class="flex items-center justify-between p-3 bg-slate-50 dark:bg-white/5 rounded-xl cursor-pointer group active:scale-[0.99] transition-all">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-slate-400 group-hover:text-orange-500 transition-colors text-lg">chat</span>
                                    <span class="text-xs font-bold text-slate-700 dark:text-white">Comments</span>
                                </div>
                                <button type="button" @click="allowComments = !allowComments" class="w-10 h-5 rounded-full relative transition-all duration-300" :class="allowComments ? 'gradient-orange shadow-lg shadow-orange-500/20' : 'bg-slate-200 dark:bg-white/10'">
                                    <div class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-all duration-300" :class="allowComments ? 'translate-x-5' : 'translate-x-0.5'"></div>
                                </button>
                            </label>
                            <label class="flex items-center justify-between p-3 bg-slate-50 dark:bg-white/5 rounded-xl cursor-pointer group active:scale-[0.99] transition-all">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-slate-400 group-hover:text-orange-500 transition-colors text-lg">18_up_rating</span>
                                    <span class="text-xs font-bold text-slate-700 dark:text-white">18+</span>
                                </div>
                                <button type="button" @click="isAgeRestricted = !isAgeRestricted" class="w-10 h-5 rounded-full relative transition-all duration-300" :class="isAgeRestricted ? 'gradient-orange shadow-lg shadow-orange-500/20' : 'bg-slate-200 dark:bg-white/10'">
                                    <div class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-all duration-300" :class="isAgeRestricted ? 'translate-x-5' : 'translate-x-0.5'"></div>
                                </button>
                            </label>
                            <label class="flex items-center justify-between p-3 bg-slate-50 dark:bg-white/5 rounded-xl cursor-pointer group active:scale-[0.99] transition-all">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-slate-400 group-hover:text-orange-500 transition-colors text-lg">group</span>
                                    <span class="text-xs font-bold text-slate-700 dark:text-white">Duet</span>
                                </div>
                                <button type="button" @click="allowDuet = !allowDuet" class="w-10 h-5 rounded-full relative transition-all duration-300" :class="allowDuet ? 'gradient-orange shadow-lg shadow-orange-500/20' : 'bg-slate-200 dark:bg-white/10'">
                                    <div class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-all duration-300" :class="allowDuet ? 'translate-x-5' : 'translate-x-0.5'"></div>
                                </button>
                            </label>
                            <label class="flex items-center justify-between p-3 bg-slate-50 dark:bg-white/5 rounded-xl cursor-pointer group active:scale-[0.99] transition-all">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-slate-400 group-hover:text-orange-500 transition-colors text-lg">content_cut</span>
                                    <span class="text-xs font-bold text-slate-700 dark:text-white">Stitch</span>
                                </div>
                                <button type="button" @click="allowStitch = !allowStitch" class="w-10 h-5 rounded-full relative transition-all duration-300" :class="allowStitch ? 'gradient-orange shadow-lg shadow-orange-500/20' : 'bg-slate-200 dark:bg-white/10'">
                                    <div class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-all duration-300" :class="allowStitch ? 'translate-x-5' : 'translate-x-0.5'"></div>
                                </button>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Thumbnail</label>
                        
                        @if($reel->thumbnail_path)
                            <div class="relative w-48 aspect-[9/16] rounded-2xl overflow-hidden group/thumb">
                                <img src="{{ $reel->getThumbnailUrl() }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover/thumb:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" 
                                            onclick="confirmDelete('{{ route('studio.reels.thumbnail.destroy', $reel) }}', 'Delete this thumbnail?')"
                                            class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                                        <span class="material-symbols-rounded">delete</span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-500/60">{{ $reel->thumbnail_path ? 'Change Thumbnail' : 'Upload Thumbnail' }}</label>
                            <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm text-slate-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-orange-100 file:text-orange-700 file:font-bold">
                        </div>
                    </div>
                    <!-- Tags / Hashtags -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 px-1">Hashtag</label>
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
                                       class="w-full h-16 pl-14 pr-6 bg-white dark:bg-black/20 border border-slate-200/60 dark:border-white/5 rounded-2xl text-[13px] font-black text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none transition-all" 
                                       placeholder="Type a tag...">
                            </div>
                            <button type="button" 
                                    @click="addTag()" 
                                    class="h-16 w-16 lg:w-auto lg:px-6 bg-orange-600 hover:bg-orange-700 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all active:scale-[0.95] flex items-center justify-center lg:gap-1 shrink-0">
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
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('studio.reels') }}" class="h-12 px-6 bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-white font-bold text-xs uppercase rounded-xl flex items-center">Cancel</a>
                    <button type="submit" class="h-12 px-8 bg-gradient-to-r from-orange-600 to-amber-500 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow-lg">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
