@extends('layouts.app')
@section('content')
<div class="fixed inset-0 lg:top-20 lg:left-72 bg-black z-[9999] overflow-hidden select-none" x-data="reelsPlayer()" @user-blocked.window="comments = comments.filter(c => c.user_id != $event.detail.userId)">
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    
    <div class="flex h-full w-full">
        {{-- MAIN FEED AREA --}}
        <div class="flex-1 h-full relative overflow-hidden bg-black">
            {{-- ═══ SLIDING TRACK ═══ --}}
            <div class="w-full h-full will-change-transform"
                 :class="isTouching ? '' : 'transition-transform duration-250 ease-[cubic-bezier(0.22,1,0.36,1)]'"
                 :style="'transform: translateY(' + offset + 'px)'"
                 x-on:touchstart.passive="onTouchStart($event)"
                 x-on:touchmove.passive="onTouchMove($event)"
                 x-on:touchend="onTouchEnd()"
                 x-on:wheel.prevent="onWheel($event)">

        @forelse($reels as $index => $reel)
        <div class="w-full relative flex items-center justify-center bg-black overflow-hidden h-[100dvh] lg:h-full">
            <template x-if="isInBufferZone({{ $index }})">
            <div class="w-full h-full">
            {{-- DESKTOP BACKDROP - POSTER ONLY (optimized: decorative video removed to save bandwidth for current reel) --}}
            <div class="absolute inset-0 hidden lg:block overflow-hidden pointer-events-none opacity-40">
                @if($reel->status == \App\Constants\Status::PUBLISHED && $reel->bunny_status == 'ready')
                    <div class="w-full h-full bg-cover bg-center blur-[80px] scale-110" style="background-image: url('{{ $reel->getThumbnailUrl() }}')"></div>
                @else
                    <div class="w-full h-full bg-slate-900 blur-[80px] scale-110"></div>
                @endif
            </div>

            <div class="relative flex items-center lg:justify-center 2xl:gap-x-12 w-full h-full">
                
                {{-- DESKTOP LEFT INFO (Phase) --}}
                <div class="hidden 2xl:flex flex-col gap-6 z-20 mb-12 w-64 shrink-0 pointer-events-auto">
                    <div class="flex flex-col gap-5">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <a href="{{ ($reel->channel && $reel->channel->slug) ? route('channels.show', $reel->channel->slug) : '#' }}" class="block w-14 h-14 rounded-full border-2 border-white/20 overflow-hidden shadow-2xl hover:scale-105 transition-all duration-300">
                                     @if($reel->user->channel?->avatar)
                                        <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $reel->user->channel->avatar) }}" class="w-full h-full object-cover">
                                     @elseif($reel->user->image)
                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $reel->user->image) }}" class="w-full h-full object-cover">

                                     @else
                                        <div class="w-full h-full gradient-orange flex items-center justify-center text-white font-black text-xl uppercase">
                                            @php
                                                $name = $reel->user->username ?? $reel->user->fullname;
                                                $words = explode(' ', trim($name));
                                                $initials = count($words) >= 2 
                                                    ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                                    : strtoupper(substr($name, 0, 2));
                                            @endphp
                                            {{ $initials }}
                                        </div>
                                     @endif
                                </a>
                            </div>
                            <div class="flex flex-col">
                                <a href="{{ ($reel->user->channel && $reel->user->channel->slug) ? route('channels.show', $reel->user->channel->slug) : '#' }}" class="text-white font-black text-lg tracking-tight hover:text-orange-500 transition-colors">{{ $reel->user->channel_name ?? $reel->user->username }}</a>
                                <div class="flex gap-2 mt-1">
                                    @if(auth()->id() != $reel->user_id)
                                        <button 
                                            x-on:click.stop="toggleSubscribe({{ $reel->channel->id ?? 0 }}, '{{ $reel->slug }}')" 
                                            class="px-4 py-1.5 text-white rounded-full text-[10px] font-black uppercase tracking-widest active:scale-90 transition-all shadow-lg border border-white/5"
                                            :class="channelSubscriptions[{{ $reel->channel->id ?? 0 }}] ? 'bg-white/10 text-white' : 'bg-[#FF4B2B] text-white'"
                                            x-text="channelSubscriptions[{{ $reel->channel->id ?? 0 }}] ? 'Subscribed' : 'Subscribe'"
                                        ></button>
                                    @else
                                        <a href="{{ route('studio.analytics') }}" 
                                           class="px-4 py-1.5 bg-white/10 backdrop-blur-md text-white rounded-full text-[10px] font-black uppercase tracking-widest active:scale-90 transition-all shadow-lg flex items-center gap-1 border border-white/10">
                                            Analytics
                                        </a>
                                    @endif
                                </div>
                                <div class="flex items-center gap-4 pt-2">
                                    <div class="flex items-center gap-1.5 px-3 py-1 bg-white/5 rounded-full border border-white/5">
                                        <span class="material-symbols-rounded text-white/40 text-[14px]">visibility</span>
                                        <span class="text-white/60 text-[10px] font-black tracking-widest" x-text="formatNumber(reelStates['{{ $reel->slug }}'].viewsCount)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3" x-data="{ expanded: false }">
                            <div class="relative group cursor-pointer" x-on:click.stop="openDetails('{{ $reel->slug }}')">
                                <h3 class="text-white font-black text-base leading-tight drop-shadow-md line-clamp-2">{{ $reel->title }}</h3>
                                @if(strlen($reel->title) > 50)
                                    <button class="text-orange-500 hover:text-orange-400 font-black text-[10px] uppercase tracking-widest outline-none transition-colors mt-1 block">
                                        ...more
                                    </button>
                                @endif
                            </div>
                            @if($reel->description)
                                <div class="relative cursor-pointer" x-on:click.stop="expanded = !expanded">
                                    <p class="text-white/50 text-xs leading-relaxed drop-shadow-sm transition-all duration-300"
                                       :class="expanded ? '' : 'line-clamp-3'">{!! nl2br(preg_replace('/#(\w+)/', '<a href="/search?q=%23$1" class="text-orange-500 hover:underline">#$1</a>', e($reel->description))) !!}</p>
                                    <button x-show="!expanded"
                                            class="text-orange-500 hover:text-orange-400 font-black text-[10px] uppercase tracking-widest outline-none transition-colors mt-1 block">
                                        ...more
                                    </button>
                                </div>
                            @endif
                            @if($reel->hashtags->count() > 0)
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    @foreach($reel->hashtags as $hashtag)
                                        <a href="/search?q=%23{{ urlencode(trim($hashtag->hashtag)) }}" class="inline-flex items-center px-2 py-0.5 bg-orange-500/10 hover:bg-orange-500/20 text-orange-500 rounded-full text-[10px] font-bold transition-all hover:scale-105">
                                            #{{ trim($hashtag->hashtag) }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- IMMERSIVE CONTAINER (Android Native Look) --}}
                <div class="w-full lg:w-auto h-full lg:h-full lg:aspect-[9/16] relative overflow-hidden bg-black rounded-none lg:rounded-[1rem] lg:border-x border-white/5 shadow-2xl flex items-center justify-center group">

            {{-- TOP OVERLAY --}}
            <div class="absolute top-0 left-0 right-0 z-30 p-4 md:p-6 flex items-center justify-between pointer-events-none">
                <button onclick="goBack()" class="w-10 h-10 rounded-full bg-black/20 backdrop-blur-md flex items-center justify-center text-white pointer-events-auto active:scale-90 transition-transform border border-white/5">
                    <span class="material-symbols-rounded">arrow_back</span>
                </button>
                <button x-on:click.stop="activeOptionsReel = '{{ $reel->slug }}'" class="w-10 h-10 rounded-full bg-black/20 backdrop-blur-md flex items-center justify-center text-white pointer-events-auto active:scale-90 transition-transform border border-white/5">
                    <span class="material-symbols-rounded">more_vert</span>
                </button>
            </div>

            {{-- Video or Processing State --}}
            @if($reel->status == \App\Constants\Status::PUBLISHED && $reel->bunny_status == 'ready')
                {{-- Premium Age Restriction Gate (Native App Look) --}}
                @if($reel->is_age_restricted)
                <div x-data="{ acknowledged: false }" 
                     x-show="!acknowledged" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-110"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute inset-0 z-[150] bg-[#0F0F0F]/80 backdrop-blur-[40px] flex flex-col items-center justify-center p-10 text-center pointer-events-auto">
                    
                    {{-- Native Security Icon --}}
                    <div class="relative mb-10">
                        <div class="absolute inset-0 bg-rose-500 blur-3xl opacity-20 animate-pulse"></div>
                        <div class="w-24 h-24 rounded-[2.5rem] bg-white/5 border border-white/10 flex items-center justify-center relative z-10 shadow-2xl">
                            <span class="material-symbols-rounded text-6xl text-white opacity-90">shield_lock</span>
                        </div>
                    </div>

                    <h2 class="text-white text-3xl font-black uppercase tracking-tight mb-4 leading-none">Age Restricted</h2>
                    <div class="w-12 h-1 bg-rose-500 rounded-full mb-6 mx-auto"></div>
                    
                    <p class="text-white/70 text-xs font-bold mb-12 max-w-[280px] leading-relaxed uppercase tracking-[0.1em]">
                        This content is age-restricted and intended for <span class="text-white underline decoration-rose-500/50 underline-offset-4">mature audiences</span> only.
                    </p>
                    
                    <div class="flex flex-col gap-4 w-full max-w-[260px]">
                        @if(!auth()->check())
                            <a href="{{ route('login') }}" class="w-full py-5 bg-white text-black rounded-3xl font-black text-[11px] uppercase tracking-[0.2em] hover:scale-105 active:scale-95 transition-all shadow-2xl shadow-white/10 flex items-center justify-center">
                                Sign In to Verify
                            </a>
                            <button onclick="goBack()" class="w-full py-4 bg-white/5 border border-white/10 text-white/60 rounded-3xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white/10 transition-all">
                                Cancel & Exit
                            </button>
                        @else
                            <button @click="acknowledged = true; $dispatch('play-restricted-video', { index: {{ $index }} })" 
                                    class="w-full py-5 bg-gradient-to-tr from-rose-600 to-orange-500 text-white rounded-3xl font-black text-[11px] uppercase tracking-[0.2em] hover:scale-105 active:scale-95 transition-all shadow-xl shadow-rose-500/20">
                                I Understand & View
                            </button>
                            <button onclick="goBack()" class="w-full py-4 bg-white/5 border border-white/10 text-white/40 rounded-3xl text-[10px] font-black uppercase tracking-[0.2em] hover:text-white transition-all">
                                Go Back
                            </button>
                        @endif
                    </div>

                    <div class="mt-12 flex items-center gap-2 opacity-30">
                        <span class="material-symbols-rounded text-xs text-white">verified_user</span>
                        <span class="text-[9px] font-black text-white uppercase tracking-widest">Platform Security Standard</span>
                    </div>
                </div>
                @endif
                <div class="absolute inset-0 w-full h-full bg-black flex items-center justify-center">
                    {{-- POSTER: Sharp transition layer --}}
                    <div id="reel-poster-wrap-{{ $index }}" class="absolute inset-0 z-[5] transition-all duration-500 ease-out" style="opacity: 1;">
                        <img id="reel-poster-{{ $index }}" 
                             src="{{ $reel->getThumbnailUrl() }}" 
                             class="w-full h-full object-cover" 
                             onerror="this.src='{{ asset('assets/images/default.png') }}'; this.onerror=null;">
                    </div>
                    
                    {{-- Subtle loading shimmer while video buffers --}}
                    <div id="reel-loader-{{ $index }}" class="absolute inset-0 flex flex-col items-center justify-center gap-3 pointer-events-none z-[20]">
                        <div class="w-10 h-10 border-3 border-white/10 border-t-white/80 rounded-full animate-spin drop-shadow-lg"></div>
                        <span id="reel-loader-text-{{ $index }}" style="display: none;" class="text-white/80 text-[10px] font-black uppercase tracking-[0.2em] drop-shadow-lg">Slow connection…</span>
                    </div>
                    
                    <video data-src="{{ $reel->getPlayUrl() }}"
                           data-type="{{ str_ends_with($reel->getPlayUrl(), '.m3u8') ? 'hls' : 'mp4' }}"
                           data-restricted="{{ $reel->is_age_restricted ? 'true' : 'false' }}"
                           class="absolute inset-0 w-full h-full object-cover lg:object-contain z-10 opacity-0"
                           data-video="{{ $index }}"
                           preload="metadata"
                           loop muted playsinline
                           x-on:ended="videoEnded()"
                           x-on:timeupdate="updateProgress($el)"
                           x-on:click="handleTap($el)"></video>
                </div>
                
                {{-- SCRUBBABLE PROGRESS BAR --}}
                <div class="absolute left-0 w-full cursor-pointer group/progress pointer-events-auto"
                     style="bottom: 0; height: 20px; z-index: 100; display: block;"
                     x-on:mousedown.prevent.stop="startSeeking($event, $el)"
                     x-on:touchstart.prevent.stop="startSeeking($event, $el)">
                    <div class="absolute bottom-0 left-0 w-full bg-white/20 pointer-events-none" style="height: 5px;">
                        <div class="h-full transition-[width] ease-linear"
                             :style="'background: #ff5722; box-shadow: 0 0 20px rgba(255,87,34,0.9); width: ' + progress + '%; transition-duration: ' + ((isDraggingProgress || progressInstant) ? '0ms' : '150ms')"></div>
                    </div>
                </div>
            @endif

            {{-- PLAY/PAUSE OVERLAY --}}
            <div x-show="showPlayState" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-50"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-150"
                 class="absolute inset-0 flex items-center justify-center pointer-events-none z-40">
                <div class="w-20 h-20 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white border border-white/10">
                    <span class="material-symbols-rounded text-5xl" x-text="lastState === 'play' ? 'play_arrow' : 'pause'"></span>
                </div>
            </div>

            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20 pointer-events-none" style="background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.3) 50%, transparent 80%, rgba(0, 0, 0, 0.25) 100%);"></div>

            {{-- BOTTOM INFO (Left - Mobile Only) --}}
            <div x-show="!activeCommentReel && !activeShareReel"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute bottom-0 left-0 z-10 p-6 pb-10 w-full pr-24 pointer-events-none 2xl:hidden"
                 style="background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.4) 65%, transparent 100%);">
                
                {{-- User Info --}}
                <div class="flex items-center gap-3 mb-3 pointer-events-auto">
                    <a href="{{ ($reel->channel && $reel->channel->slug) ? route('channels.show', $reel->channel->slug) : '#' }}" class="w-9 h-9 rounded-full border-2 border-white/50 overflow-hidden shadow-lg flex-shrink-0">
                        @if($reel->user->channel?->avatar)
                            <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $reel->user->channel->avatar) }}" class="w-full h-full object-cover">
                        @elseif($reel->user->image)
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $reel->user->image) }}" class="w-full h-full object-cover">

                        @else
                            <div class="w-full h-full gradient-orange flex items-center justify-center text-white font-black text-xs uppercase">
                                @php
                                    $name = $reel->user->username ?? $reel->user->fullname;
                                    $words = explode(' ', trim($name));
                                    $initials = count($words) >= 2 
                                        ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                        : strtoupper(substr($name, 0, 2));
                                @endphp
                                {{ $initials }}
                            </div>
                        @endif
                    </a>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <a href="{{ ($reel->user->channel && $reel->user->channel->slug) ? route('channels.show', $reel->user->channel->slug) : '#' }}" class="text-white font-black text-[13px] tracking-tight">{{ $reel->user->channel_name ?? $reel->user->username }}</a>
                            <span class="text-white/40 text-[10px]">•</span>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-rounded text-white/60 text-[12px]">visibility</span>
                                <span class="text-white/80 text-[10px] font-bold" x-text="formatNumber(reelStates['{{ $reel->slug }}'].viewsCount)"></span>
                            </div>
                        </div>
                        <div class="mt-1 flex items-center">
                            @if(auth()->id() != $reel->user_id)
                                <button 
                                    x-on:click.stop="toggleSubscribe({{ $reel->channel->id ?? 0 }})" 
                                    class="w-fit inline-flex items-center px-2 py-0.5 text-white rounded-full text-[8px] font-black uppercase tracking-widest active:scale-90 transition-all shadow-lg"
                                    :class="channelSubscriptions[{{ $reel->channel->id ?? 0 }}] ? 'bg-white/20' : 'bg-[#FF4B2B]'"
                                    x-text="channelSubscriptions[{{ $reel->channel->id ?? 0 }}] ? 'Subscribed' : 'Subscribe'"
                                ></button>
                            @else
                                <a href="{{ route('studio.analytics') }}" 
                                   class="w-fit inline-flex items-center px-2 py-0.5 bg-white/10 backdrop-blur-md text-white rounded-full text-[8px] font-black uppercase tracking-widest active:scale-90 transition-all shadow-lg border border-white/10">
                                    Analytics
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Description & Music Info (No Pill) --}}
                <div class="pointer-events-auto" x-data="{ expanded: false }">
                    <div class="relative group cursor-pointer" x-on:click.stop="openDetails('{{ $reel->slug }}')">
                        <h3 class="text-white font-bold text-sm mb-1 leading-tight drop-shadow-md line-clamp-2">{{ $reel->title }}</h3>
                        @if(strlen($reel->title) > 50)
                            <button x-on:click.stop="openDetails('{{ $reel->slug }}')"
                                    class="text-white font-black text-[10px] mb-2 hover:text-orange-500 transition-colors uppercase tracking-widest outline-none">
                                ...more
                            </button>
                        @endif
                    </div>

                    @if($reel->description)
                        <div class="relative">
                            <p class="text-white/80 text-[11px] leading-relaxed transition-all duration-300 drop-shadow-sm line-clamp-1">{!! preg_replace('/#(\w+)/', '<a href="/search?q=%23$1" class="text-orange-500 hover:underline">#$1</a>', e($reel->description)) !!}</p>
                            @if(strlen($reel->description) > 40 || strlen($reel->title) > 50)
                                @if(!(strlen($reel->title) > 50))
                                <button x-on:click.stop="openDetails('{{ $reel->slug }}')"
                                        class="text-white font-black text-[10px] mt-1 hover:text-orange-500 transition-colors uppercase tracking-widest outline-none">
                                    ...more
                                </button>
                                @endif
                            @endif
                        </div>
                    @endif

                    {{-- Always show audio pill (Defaults to Original Audio) --}}
                    <div class="mt-4 flex items-center gap-2 w-fit max-w-[160px] drop-shadow-md cursor-pointer" x-on:click.stop="activeAudioReel = '{{ $reel->slug }}'">
                        <span class="material-symbols-rounded text-white text-[16px] animate-pulse">music_note</span>
                        <div class="marquee-container flex-1">
                            <span class="marquee-content text-white text-[11px] font-bold tracking-tight opacity-90">
                                {{ $reel->music?->title ?? $reel->audio_name ?? 'Original Audio' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

                    <div class="2xl:hidden absolute right-4 bottom-10 flex flex-col items-center gap-5 z-20">
                        {{-- Mute --}}
                        <button x-on:click.stop="isUnmuted = !isUnmuted" class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white border border-white/20 shadow-lg active:scale-90 transition-transform">
                            <span class="material-symbols-rounded text-2xl" x-text="isUnmuted ? 'volume_up' : 'volume_off'"></span>
                        </button>
                        {{-- Like --}}
                        <div class="flex flex-col items-center">
                            <button x-on:click.stop="toggleLike('{{ $reel->slug }}')" class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center transition-all border border-white/20 shadow-lg active:scale-90" :class="reelStates['{{ $reel->slug }}'].isLiked ? 'text-red-500 scale-110' : 'text-white'">
                                <span class="material-symbols-rounded text-2xl" :style="reelStates['{{ $reel->slug }}'].isLiked ? 'font-variation-settings: \'FILL\' 1' : ''">favorite</span>
                            </button>
                            <span class="text-white text-[10px] font-black mt-1 drop-shadow-md" x-text="reelStates['{{ $reel->slug }}'].likesCount"></span>
                        </div>
                        {{-- Comments --}}
                        <div class="flex flex-col items-center">
                            @if($reel->allow_comments)
                                <button x-on:click.stop="openComments('{{ $reel->slug }}')" class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white border border-white/20 shadow-lg active:scale-90">
                                    <span class="material-symbols-rounded text-2xl">chat</span>
                                </button>
                                <span class="text-white text-[10px] font-black mt-1 drop-shadow-md" x-text="reelStates['{{ $reel->slug }}'].commentsCount"></span>
                            @else
                                <button x-on:click.stop="Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'info', title: 'Comments are turned off', background: '#1A1A1A', color: '#ffffff'})" class="w-11 h-11 rounded-full bg-black/20 backdrop-blur-md flex items-center justify-center text-white/50 border border-white/10 shadow-lg">
                                    <span class="material-symbols-rounded text-2xl">comments_disabled</span>
                                </button>
                                <span class="text-white/50 text-[10px] font-black mt-1 drop-shadow-md">Off</span>
                            @endif
                        </div>
                        {{-- Share --}}
                        <button x-on:click.stop="activeShareReel = '{{ $reel->slug }}'" class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white border border-white/20 shadow-lg active:scale-90">
                            <span class="material-symbols-rounded text-2xl">share</span>
                        </button>

                        {{-- Rotating Music Icon --}}
                        <div x-on:click.stop="activeAudioReel = '{{ $reel->slug }}'" class="w-10 h-10 rounded-full bg-gradient-to-tr from-gray-900 via-gray-800 to-gray-900 p-[2px] border-2 border-white/10 shadow-2xl animate-spin-slow cursor-pointer active:scale-90 transition-transform">
                            <div class="w-full h-full rounded-full bg-black flex items-center justify-center overflow-hidden relative group">
                                @if($reel->global_music_thumbnail)
                                    <img src="{{ $reel->global_music_thumbnail }}" class="w-full h-full object-cover">
                                @elseif($reel->music && $reel->music->cover_image)
                                    <img src="{{ getImage(getFilePath('reelMusicCover') . '/' . $reel->music->cover_image) }}" class="w-full h-full object-cover">
                                @elseif($reel->user->image)
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $reel->user->image) }}" class="w-full h-full object-cover">
                                @elseif($reel->user->channel && $reel->user->channel->avatar)
                                    <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $reel->user->channel->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full rounded-full bg-gradient-to-tr from-orange-500 to-rose-600 flex items-center justify-center">
                                        <span class="material-symbols-rounded text-white text-base">music_note</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden 2xl:flex flex-col items-center gap-4 z-20 mb-12 self-end">
                    {{-- Mute --}}
                    <div class="flex flex-col items-center group">
                        <button x-on:click.stop="isUnmuted = !isUnmuted" class="w-12 h-12 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-[#272727] dark:hover:bg-[#3f3f3f] flex items-center justify-center text-gray-700 dark:text-white transition-all active:scale-90 shadow-xl border border-black/10 dark:border-white/10" title="Mute/Unmute">
                            <span class="material-symbols-rounded text-2xl" x-text="isUnmuted ? 'volume_up' : 'volume_off'"></span>
                        </button>
                    </div>

                    {{-- Like --}}
                    <div class="flex flex-col items-center group">
                        <button x-on:click.stop="toggleLike('{{ $reel->slug }}')" class="w-12 h-12 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-[#272727] dark:hover:bg-[#3f3f3f] flex items-center justify-center transition-all active:scale-90 shadow-xl border border-black/10 dark:border-white/10" :class="reelStates['{{ $reel->slug }}'].isLiked ? 'text-red-500' : 'text-gray-700 dark:text-white'" title="Like">
                            <span class="material-symbols-rounded text-2xl" :style="reelStates['{{ $reel->slug }}'].isLiked ? 'font-variation-settings: \'FILL\' 1' : ''">favorite</span>
                        </button>
                        <span class="text-white text-[11px] font-black mt-1 drop-shadow-md" x-text="reelStates['{{ $reel->slug }}'].likesCount"></span>
                    </div>

                    {{-- Comments --}}
                    <div class="flex flex-col items-center group">
                        @if($reel->allow_comments)
                            <button x-on:click.stop="openComments('{{ $reel->slug }}')" class="w-12 h-12 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-[#272727] dark:hover:bg-[#3f3f3f] flex items-center justify-center text-gray-700 dark:text-white transition-all active:scale-90 shadow-xl border border-black/10 dark:border-white/10" title="Comments">
                                <span class="material-symbols-rounded text-2xl">chat</span>
                            </button>
                            <span class="text-white text-[11px] font-black mt-1 drop-shadow-md" x-text="reelStates['{{ $reel->slug }}'].commentsCount"></span>
                        @else
                            <button x-on:click.stop="Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'info', title: 'Comments are turned off', background: '#1A1A1A', color: '#ffffff'})" class="w-12 h-12 rounded-full bg-gray-200/50 dark:bg-[#272727]/50 flex items-center justify-center text-gray-700/50 dark:text-white/50 shadow-xl border border-black/5 dark:border-white/5 cursor-not-allowed" title="Comments Disabled">
                                <span class="material-symbols-rounded text-2xl">comments_disabled</span>
                            </button>
                            <span class="text-white/50 text-[11px] font-black mt-1 drop-shadow-md">Off</span>
                        @endif
                    </div>

                    {{-- Share --}}
                    <div class="flex flex-col items-center group">
                        <button x-on:click.stop="activeShareReel = '{{ $reel->slug }}'" class="w-12 h-12 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-[#272727] dark:hover:bg-[#3f3f3f] flex items-center justify-center text-gray-700 dark:text-white transition-all active:scale-90 shadow-xl border border-black/10 dark:border-white/10" title="Share">
                            <span class="material-symbols-rounded text-2xl">share</span>
                        </button>
                    </div>

                    {{-- Watch Later --}}
                    <div class="flex flex-col items-center group">
                        <button x-on:click.stop="watchLater('{{ $reel->slug }}')" 
                                class="w-12 h-12 rounded-full flex items-center justify-center transition-all active:scale-90 shadow-xl border border-white/10"
                                :class="reelStates['{{ $reel->slug }}'].isSaved ? 'bg-orange-500 text-white border-orange-500' : 'bg-gray-200 hover:bg-gray-300 dark:bg-[#272727] dark:hover:bg-[#3f3f3f] text-gray-700 dark:text-white'"
                                title="Save to Watch Later">
                            <span class="material-symbols-rounded text-2xl" x-text="reelStates['{{ $reel->slug }}'].isSaved ? 'playlist_add_check' : 'playlist_add'"></span>
                        </button>
                    </div>

                    {{-- Rotating Music Icon --}}
                    <div x-on:click.stop="activeAudioReel = '{{ $reel->slug }}'" class="w-10 h-10 rounded-full bg-gradient-to-tr from-gray-200 via-gray-300 to-gray-200 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 p-[2px] border-2 border-black/10 dark:border-white/10 shadow-2xl animate-spin-slow cursor-pointer active:scale-90 transition-transform mt-2" title="Audio Info">
                        <div class="w-full h-full rounded-full bg-black flex items-center justify-center overflow-hidden relative group">
                            @if($reel->global_music_thumbnail)
                                <img src="{{ $reel->global_music_thumbnail }}" class="w-full h-full object-cover">
                            @elseif($reel->music && $reel->music->cover_image)
                                <img src="{{ getImage(getFilePath('reelMusicCover') . '/' . $reel->music->cover_image) }}" class="w-full h-full object-cover">
                            @elseif($reel->user->image)
                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $reel->user->image) }}" class="w-full h-full object-cover">
                            @elseif($reel->user->channel && $reel->user->channel->avatar)
                                <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $reel->user->channel->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full rounded-full bg-gradient-to-tr from-orange-500 to-rose-600 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-white text-lg">music_note</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            </div>
            </template>
        </div>
        @empty
        <div class="w-full relative h-screen flex items-center justify-center overflow-hidden bg-gray-100 dark:bg-black">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-900 dark:to-black"></div>
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-purple-600/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-orange-500/10 blur-3xl"></div>

            <div class="relative z-10 text-center px-8">
                <div class="w-24 h-24 rounded-3xl bg-black/5 dark:bg-white/5 backdrop-blur-xl border border-black/10 dark:border-white/10 flex items-center justify-center mx-auto mb-8 shadow-2xl">
                    <span class="material-symbols-rounded text-6xl text-gray-400 dark:text-white/20">slow_motion_video</span>
                </div>

                <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-4 ">No Reels Found</h2>
                <p class="text-gray-500 dark:text-white/40 text-sm font-medium max-w-xs mx-auto mb-10 leading-relaxed uppercase tracking-widest">Be the first to drop a reel and light things up.</p>

                <button @click="createReel()"
                        class="px-10 py-5 bg-gradient-to-r from-orange-500 to-rose-600 text-white rounded-3xl text-sm font-black uppercase tracking-[0.2em] shadow-2xl shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-4 mx-auto">
                    <span class="material-symbols-rounded text-2xl">add_circle</span>
                    Create Reel
                </button>

                <div class="mt-12 flex items-center justify-center gap-8">
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-600 dark:text-white/30 dark:hover:text-white transition-colors text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                        <span class="material-symbols-rounded text-sm">home</span>
                        Home
                    </a>
                    <button onclick="location.reload()" class="text-gray-400 hover:text-gray-600 dark:text-white/30 dark:hover:text-white transition-colors text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                        <span class="material-symbols-rounded text-sm">refresh</span>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
        @endforelse

        {{-- ═══ END SLIDE (No More Reels) ═══ --}}
        <div class="w-full relative flex items-center justify-center bg-gray-100 dark:bg-black overflow-hidden h-[100dvh] lg:h-full">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-900 dark:to-black"></div>
            
            <div class="relative z-10 text-center px-8">
                <div class="w-24 h-24 rounded-3xl bg-black/5 dark:bg-white/5 backdrop-blur-xl border border-black/10 dark:border-white/10 flex items-center justify-center mx-auto mb-8 shadow-2xl">
                    <span class="material-symbols-rounded text-6xl text-gray-400 dark:text-white/20">movie_edit</span>
                </div>
                
                <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-4 ">You've caught up!</h2>
                <p class="text-gray-500 dark:text-white/40 text-sm font-medium max-w-xs mx-auto mb-10 leading-relaxed uppercase tracking-widest">No more reels to show right now. Why not create your own?</p>
                
                <button @click="createReel()" 
                        class="px-10 py-5 bg-gradient-to-r from-orange-500 to-rose-600 text-white rounded-3xl text-sm font-black uppercase tracking-[0.2em] shadow-2xl shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-4 mx-auto">
                    <span class="material-symbols-rounded text-2xl">add_circle</span>
                    Create Reel
                </button>

                <div class="mt-12 flex items-center justify-center gap-8">
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-600 dark:text-white/30 dark:hover:text-white transition-colors text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                        <span class="material-symbols-rounded text-sm">home</span>
                        Home
                    </a>
                    <button @click="currentIndex = 0; activateSlide(0)" class="text-gray-400 hover:text-gray-600 dark:text-white/30 dark:hover:text-white transition-colors text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                        <span class="material-symbols-rounded text-sm">refresh</span>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
        </div>
        </div>

        {{-- ═══ MORE REELS SIDEBAR (Right - Desktop Only) ═══ --}}
        <div class="hidden lg:flex w-[380px] h-full bg-white dark:bg-[#0F0F0F] border-l border-gray-200 dark:border-white/5 flex-col overflow-hidden shrink-0 z-[10000]">
            <div class="p-6 border-b border-gray-200 dark:border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-rounded text-orange-500">video_library</span>
                    <h2 class="text-gray-900 dark:text-white font-black uppercase tracking-[0.2em] text-sm">More Reels</h2>
                </div>
                <div class="px-3 py-1 bg-black/5 dark:bg-white/5 rounded-full border border-black/10 dark:border-white/10">
                    <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ $moreReels->count() }} Items</span>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-4">
                @foreach($moreReels as $more)
                @if(!$more->slug) @continue @endif
                <a href="{{ route('reels.show', $more->slug) }}" class="flex gap-4 p-3 hover:bg-black/5 dark:hover:bg-white/5 rounded-2xl transition-all group pointer-events-auto">
                    <div class="w-24 h-40 rounded-xl bg-gray-200 dark:bg-gray-900 overflow-hidden shrink-0 relative shadow-2xl border border-black/5 dark:border-white/5">
                        <img src="{{ $more->getThumbnailUrl() }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" onerror="this.src='{{ asset('assets/images/default.png') }}'; this.onerror=null;">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                        <div class="absolute bottom-2 right-2 px-1.5 py-0.5 bg-black/60 backdrop-blur-md rounded text-[9px] font-black text-white border border-white/10">
                            {{ $more->duration > 0 ? $more->duration . 's' : '00' }}
                        </div>
                    </div>
                    <div class="flex flex-col justify-center py-1 flex-1">
                        <h4 class="text-gray-900 dark:text-white font-black text-sm line-clamp-2 mb-2 group-hover:text-orange-500 transition-colors tracking-tight leading-snug">{{ $more->title }}</h4>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-5 h-5 rounded-full overflow-hidden border border-black/10 dark:border-white/10 bg-gray-200 dark:bg-gray-800">
                                @if($more->user->channel?->avatar)
                                    <img src="{{ getImage(getFilePath('channelAvatar') . '/' . $more->user->channel->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full gradient-orange flex items-center justify-center text-[8px] text-white font-black uppercase">
                                        {{ substr($more->user->username, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <p class="text-gray-500 dark:text-white/40 text-[11px] font-black uppercase tracking-tighter truncate">{{ $more->user->channel_name ?? $more->user->username }}</p>
                        </div>
                        <div class="flex items-center gap-3 text-gray-400 dark:text-white/30 text-[10px] font-black uppercase tracking-widest">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-rounded text-[14px]">visibility</span>
                                <span>{{ formatNumber($more->views_count) }}</span>
                            </div>
                            <span>•</span>
                            <span>{{ $more->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
                @endforeach

                @if($moreReels->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center opacity-30">
                    <span class="material-symbols-rounded text-6xl mb-4">video_library</span>
                    <p class="text-[10px] font-black uppercase tracking-widest">No suggestions yet</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══ NAVIGATION BUTTONS (Desktop Only) ═══ --}}
    <div x-show="!activeCommentReel && !activeShareReel" class="hidden lg:flex fixed right-10 top-1/2 -translate-y-1/2 z-[10002] flex-col gap-6 pointer-events-auto">
        <button x-on:click="prev()" 
                class="w-14 h-14 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-[#272727] dark:hover:bg-[#3f3f3f] flex items-center justify-center text-gray-700 dark:text-white transition-all active:scale-90 border border-black/10 dark:border-white/10 shadow-2xl backdrop-blur-md group relative"
                :class="currentIndex === 0 ? 'opacity-20 pointer-events-none' : ''"
                title="Previous Reel">
            <span class="material-symbols-rounded text-3xl group-hover:-translate-y-1 transition-transform">keyboard_arrow_up</span>
            <div class="absolute -top-12 left-1/2 -translate-x-1/2 px-2 py-1 bg-black/60 backdrop-blur-md text-white text-[10px] font-black rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none uppercase tracking-widest">Previous</div>
        </button>
        <button x-on:click="next()" 
                class="w-14 h-14 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-[#272727] dark:hover:bg-[#3f3f3f] flex items-center justify-center text-gray-700 dark:text-white transition-all active:scale-90 border border-black/10 dark:border-white/10 shadow-2xl backdrop-blur-md group relative"
                :class="currentIndex === totalReels - 1 ? 'opacity-20 pointer-events-none' : ''"
                title="Next Reel">
            <span class="material-symbols-rounded text-3xl group-hover:translate-y-1 transition-transform">keyboard_arrow_down</span>
            <div class="absolute -bottom-12 left-1/2 -translate-x-1/2 px-2 py-1 bg-black/60 backdrop-blur-md text-white text-[10px] font-black rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none uppercase tracking-widest">Next</div>
        </button>
    </div>

    {{-- ═══ COMMENTS DRAWER (Native Sheet Look) ═══ --}}
    <div x-show="activeCommentReel" x-cloak class="fixed inset-0 lg:top-20 z-[10001] flex items-end sm:items-center lg:items-center lg:justify-end p-0 sm:p-4 lg:p-12">
        {{-- Backdrop --}}
        <div x-on:click="activeCommentReel = null" 
             x-show="activeCommentReel"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        {{-- The Sheet --}}
        <div x-show="activeCommentReel"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full sm:scale-95 sm:opacity-0"
             x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 sm:scale-100"
             x-transition:leave-end="translate-y-full sm:scale-95 sm:opacity-0"
             class="relative w-full sm:max-w-md bg-white dark:bg-[#121212] rounded-t-[2rem] sm:rounded-[2rem] h-[75vh] sm:h-[600px] flex flex-col overflow-hidden shadow-2xl">
            
            {{-- Drag Handle (Mobile) --}}
            <div class="h-1.5 w-12 bg-gray-200 dark:bg-white/10 rounded-full mx-auto mt-4 mb-2 sm:hidden"></div>

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-black uppercase tracking-widest dark:text-white">Comments</span>
                    <span class="px-2 py-0.5 bg-gray-100 dark:bg-white/5 rounded-full text-[10px] font-black text-gray-500 dark:text-gray-400" x-text="comments.length"></span>
                </div>
                <button x-on:click="activeCommentReel = null" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                    <span class="material-symbols-rounded text-gray-400">close</span>
                </button>
            </div>

            {{-- Comments List --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
                <template x-if="loadingComments">
                    <div class="flex flex-col items-center justify-center h-full space-y-4">
                        <div class="w-8 h-8 border-4 border-orange-500/20 border-t-orange-500 rounded-full animate-spin"></div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Fetching discussions...</p>
                    </div>
                </template>

                <template x-if="!loadingComments && comments.length === 0">
                    <div class="text-center py-20 text-gray-400 flex flex-col items-center">
                        <div class="w-16 h-16 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center mb-4">
                            <span class="material-symbols-rounded text-4xl opacity-20">chat_bubble</span>
                        </div>
                        <p class="text-xs font-bold uppercase tracking-widest">No comments yet</p>
                        <p class="text-[10px] mt-1 opacity-60">Be the first to share your thoughts!</p>
                    </div>
                </template>

                <template x-for="comment in comments" :key="comment.id">
                    <div class="mb-4">
                        <div class="flex gap-4 group">
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/5 flex-shrink-0 overflow-hidden ring-2 ring-transparent group-hover:ring-orange-500/20 transition-all flex items-center justify-center">
                                <template x-if="comment.user_avatar">
                                    <img :src="comment.user_avatar" class="w-full h-full object-cover">
                                </template>
                            <template x-if="!comment.user_avatar">
                                <div class="w-full h-full gradient-orange flex items-center justify-center text-white font-black text-xs uppercase" x-text="getInitials(comment.username)"></div>
                            </template>
                        </div>
                        <div class="flex-1 min-w-0">
                            <template x-if="!comment.parent_id && comment.is_pinned">
                                <div class="inline-flex items-center gap-1.5 bg-orange-500/10 dark:bg-orange-500/20 text-orange-600 dark:text-orange-400 px-2.5 py-1 rounded-full text-[9px] font-bold mb-2 shadow-sm border border-orange-500/20">
                                    <span class="material-symbols-rounded text-[11px] font-variation-filled">push_pin</span>
                                    <template x-if="activeCommentReel">
                                        <div class="flex items-center">
                                            <template x-if="reelStates[activeCommentReel]?.user_avatar">
                                                <img :src="reelStates[activeCommentReel].user_avatar" class="w-3.5 h-3.5 rounded-full object-cover border border-orange-500/30">
                                            </template>
                                            <template x-if="!reelStates[activeCommentReel]?.user_avatar">
                                                <div class="w-3.5 h-3.5 rounded-full gradient-orange flex items-center justify-center text-[7px] text-white font-black uppercase shrink-0" x-text="getInitials(reelStates[activeCommentReel].username)"></div>
                                            </template>
                                        </div>
                                    </template>
                                    <span x-text="'Pinned by ' + (activeCommentReel ? reelStates[activeCommentReel].username : '')"></span>
                                </div>
                            </template>
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <p class="text-[11px] font-black dark:text-white uppercase tracking-tight" x-text="comment.username"></p>
                                    <span x-show="comment.is_edited" class="text-[9px] text-gray-400 dark:text-gray-500 font-bold lowercase tracking-widest">(edited)</span>
                                </div>
                                <span class="text-[9px] text-gray-400" x-text="comment.created_at"></span>
                            </div>
                            <div x-data="{ 
                                isEditing: false, 
                                editedContent: comment.comment,
                                expanded: false,
                                showMore: false,
                                initComment() {
                                    this.$nextTick(() => {
                                        const el = this.$refs.commentBody;
                                        if (el) {
                                            const observer = new ResizeObserver(() => {
                                                this.showMore = el.scrollHeight > el.clientHeight + 1;
                                            });
                                            observer.observe(el);
                                            this.showMore = el.scrollHeight > el.clientHeight + 1;
                                        }
                                    });
                                }
                            }" x-init="initComment()">
                                <div x-show="!isEditing">
                                    <div x-ref="commentBody" :class="expanded ? '' : 'line-clamp-5'" :style="expanded ? '' : '-webkit-box-orient: vertical; overflow: hidden;'" class="text-[13px] text-gray-700 dark:text-white/90 leading-relaxed break-all lg:break-words" x-html="formatComment(comment.comment)"></div>
                                    <button x-show="!isEditing && showMore" @click="expanded = !expanded" class="text-[11px] font-bold text-orange-500 hover:underline mt-1 transition-colors uppercase tracking-widest">
                                        <span x-text="expanded ? 'Show less' : 'Read more'"></span>
                                    </button>
                                </div>
                                
                                <div x-show="isEditing" class="mt-2" x-cloak>
                                    <textarea x-model="editedContent" class="w-full bg-white dark:bg-[#1A1A1A] border border-orange-500/30 rounded-xl p-3 text-sm text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500 transition-all"></textarea>
                                    <div class="flex justify-end gap-2 mt-2">
                                        <button @click="isEditing = false" class="px-3 py-1 text-[10px] font-bold text-gray-400 uppercase">Cancel</button>
                                        <button @click="window.updateComment(comment.id, editedContent, true).then(newContent => { if(newContent) { comment.comment = newContent; comment.is_edited = true; isEditing = false; } })" 
                                                class="px-4 py-1 text-[10px] font-bold bg-orange-500 text-white rounded-lg uppercase">Save</button>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 mt-2">
                                    <button x-on:click="replyTo(comment.id, comment.username)" class="text-[10px] font-bold text-gray-400 hover:text-orange-500 transition-colors uppercase tracking-widest">Reply</button>
                                    <button x-on:click="window.likeComment(comment.id, true)" 
                                            :class="comment.is_liked ? 'text-red-500' : 'text-gray-400'"
                                            class="flex items-center gap-1 text-[10px] font-bold hover:text-red-500 transition-colors text-gray-400">
                                        <span class="material-symbols-rounded text-xs" :class="comment.is_liked ? 'fill-1' : ''">favorite</span>
                                        <span x-text="comment.likes_count || 0">0</span>
                                    </button>
                                    
                                    <div class="relative" x-data="{ menuOpen: false }" x-show="currentUserId">
                                        <button @click.stop="menuOpen = !menuOpen" class="p-1 hover:bg-white/10 rounded-full transition-all text-gray-400 hover:text-white">
                                            <span class="material-symbols-rounded text-[18px]">more_vert</span>
                                        </button>
                                        <div x-show="menuOpen" @click.outside="menuOpen = false" x-cloak x-transition class="absolute right-0 mt-1 w-36 bg-white dark:bg-[#1A1A1A] shadow-2xl rounded-xl border border-gray-100 dark:border-white/10 py-1 z-50 overflow-hidden">
                                            <template x-if="!comment.parent_id && (activeCommentReel && reelStates[activeCommentReel]?.channelId == currentChannelId)">
                                                <button @click="window.togglePinComment(comment.id, true); menuOpen = false" class="w-full px-4 py-2 text-left text-[11px] font-bold hover:bg-gray-100 dark:hover:bg-white/5 flex items-center gap-3 transition-colors text-gray-900 dark:text-white uppercase tracking-tighter">
                                                    <span class="material-symbols-rounded text-[18px]">push_pin</span>
                                                    <span x-text="comment.is_pinned ? 'Unpin' : 'Pin'"></span>
                                                </button>
                                            </template>
                                            <template x-if="comment.user_id == currentUserId">
                                                <button @click="isEditing = true; menuOpen = false" class="w-full px-4 py-2 text-left text-[11px] font-bold hover:bg-gray-100 dark:hover:bg-white/5 flex items-center gap-3 transition-colors text-gray-900 dark:text-white uppercase tracking-tighter">
                                                    <span class="material-symbols-rounded text-[18px]">edit</span>
                                                    <span>Edit</span>
                                                </button>
                                            </template>
                                            <template x-if="comment.user_id == currentUserId || (activeCommentReel && reelStates[activeCommentReel]?.channelId == currentChannelId)">
                                                <button @click="window.deleteComment(comment.id, true); menuOpen = false" class="w-full px-4 py-2 text-left text-[11px] font-bold text-red-500 hover:bg-gray-100 dark:hover:bg-white/5 flex items-center gap-3 transition-colors uppercase tracking-tighter">
                                                    <span class="material-symbols-rounded text-[18px]">delete</span>
                                                    <span>Delete</span>
                                                </button>
                                            </template>
                                            <template x-if="comment.user_id != currentUserId">
                                                <div>
                                                    <button @click="window.blockUser(comment.user_id, comment.comment); menuOpen = false" class="w-full px-4 py-2 text-left text-[11px] font-bold hover:bg-gray-100 dark:hover:bg-white/5 flex items-center gap-3 transition-colors text-gray-900 dark:text-white uppercase tracking-tighter">
                                                        <span class="material-symbols-rounded text-[18px]">block</span>
                                                        <span>Block User</span>
                                                    </button>
                                                    <button @click="window.reportComment(comment.id); menuOpen = false" class="w-full px-4 py-2 text-left text-[11px] font-bold hover:bg-gray-100 dark:hover:bg-white/5 flex items-center gap-3 transition-colors text-gray-900 dark:text-white uppercase tracking-tighter">
                                                        <span class="material-symbols-rounded text-[18px]">flag</span>
                                                        <span>Report</span>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Replies List -->
                    <template x-if="comment.replies && comment.replies.length > 0">
                        <div class="mt-2">
                            <button @click="comment.showReplies = !comment.showReplies" class="flex items-center gap-2 text-orange-600 dark:text-orange-400 text-[13px] font-bold hover:bg-orange-50 dark:hover:bg-orange-900/20 px-3 py-1.5 rounded-full transition-colors w-fit">
                                <span class="material-symbols-rounded transform transition-transform" :class="comment.showReplies ? 'rotate-180' : ''">expand_more</span>
                                <span x-text="comment.showReplies ? 'Hide' : 'View'"></span> 
                                <span x-text="comment.replies.length"></span> 
                                <span x-text="comment.replies.length == 1 ? 'reply' : 'replies'"></span>
                            </button>
                            
                            <div x-show="comment.showReplies" class="mt-4 space-y-6" x-cloak>
                                <template x-for="reply in comment.replies" :key="reply.id">
                                    <div class="flex gap-4 group">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/5 flex-shrink-0 overflow-hidden ring-2 ring-transparent group-hover:ring-orange-500/20 transition-all flex items-center justify-center">
                                            <template x-if="reply.user_avatar">
                                                <img :src="reply.user_avatar" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!reply.user_avatar">
                                                <div class="w-full h-full gradient-orange flex items-center justify-center text-white font-black text-xs uppercase" x-text="getInitials(reply.username)"></div>
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-1">
                                                <div class="flex items-center gap-2">
                                                    <p class="text-[11px] font-black dark:text-white uppercase tracking-tight" x-text="reply.username"></p>
                                                    <span x-show="reply.is_edited" class="text-[9px] text-gray-400 dark:text-gray-500 font-bold lowercase tracking-widest">(edited)</span>
                                                </div>
                                                <span class="text-[9px] text-gray-400" x-text="reply.created_at"></span>
                                            </div>
                                            <div x-data="{ 
                                                isEditing: false, 
                                                editedContent: reply.comment,
                                                expanded: false,
                                                showMore: false,
                                                initComment() {
                                                    this.$nextTick(() => {
                                                        const el = this.$refs.commentBody;
                                                        if (el) {
                                                            const observer = new ResizeObserver(() => {
                                                                this.showMore = el.scrollHeight > el.clientHeight + 1;
                                                            });
                                                            observer.observe(el);
                                                            this.showMore = el.scrollHeight > el.clientHeight + 1;
                                                        }
                                                    });
                                                }
                                            }" x-init="initComment()">
                                                <div x-show="!isEditing">
                                                    <div x-ref="commentBody" :class="expanded ? '' : 'line-clamp-5'" :style="expanded ? '' : '-webkit-box-orient: vertical; overflow: hidden;'" class="text-[13px] text-gray-700 dark:text-white/90 leading-relaxed break-all lg:break-words" x-html="formatComment(reply.comment)"></div>
                                                    <button x-show="!isEditing && showMore" @click="expanded = !expanded" class="text-[11px] font-bold text-orange-500 hover:underline mt-1 transition-colors uppercase tracking-widest">
                                                        <span x-text="expanded ? 'Show less' : 'Read more'"></span>
                                                    </button>
                                                </div>
                                                
                                                <div x-show="isEditing" class="mt-2" x-cloak>
                                                    <textarea x-model="editedContent" class="w-full bg-white dark:bg-[#1A1A1A] border border-orange-500/30 rounded-xl p-3 text-sm text-gray-900 dark:text-white focus:ring-orange-500 focus:border-orange-500 transition-all"></textarea>
                                                    <div class="flex justify-end gap-2 mt-2">
                                                        <button @click="isEditing = false" class="px-3 py-1 text-[10px] font-bold text-gray-400 uppercase">Cancel</button>
                                                        <button @click="window.updateComment(reply.id, editedContent, true).then(newContent => { if(newContent) { reply.comment = newContent; reply.is_edited = true; isEditing = false; } })" 
                                                                class="px-4 py-1 text-[10px] font-bold bg-orange-500 text-white rounded-lg uppercase">Save</button>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-4 mt-2">
                                                    <button x-on:click="replyTo(comment.id, reply.username)" class="text-[10px] font-bold text-gray-400 hover:text-orange-500 transition-colors uppercase tracking-widest">Reply</button>
                                                    <button x-on:click="window.likeComment(reply.id, true)" 
                                                            :class="reply.is_liked ? 'text-red-500' : 'text-gray-400'"
                                                            class="flex items-center gap-1 text-[10px] font-bold hover:text-red-500 transition-colors text-gray-400">
                                                        <span class="material-symbols-rounded text-xs" :class="reply.is_liked ? 'fill-1' : ''">favorite</span>
                                                        <span x-text="reply.likes_count || 0">0</span>
                                                    </button>
                                                    
                                                    <div class="relative" x-data="{ menuOpen: false }" x-show="currentUserId">
                                                        <button @click.stop="menuOpen = !menuOpen" class="p-1 hover:bg-white/10 rounded-full transition-all text-gray-400 hover:text-white">
                                                            <span class="material-symbols-rounded text-[18px]">more_vert</span>
                                                        </button>
                                                        <div x-show="menuOpen" @click.outside="menuOpen = false" x-cloak x-transition class="absolute right-0 mt-1 w-36 bg-white dark:bg-[#1A1A1A] shadow-2xl rounded-xl border border-gray-100 dark:border-white/10 py-1 z-50 overflow-hidden">
                                                            <template x-if="reply.user_id == currentUserId">
                                                                <button @click="isEditing = true; menuOpen = false" class="w-full px-4 py-2 text-left text-[11px] font-bold hover:bg-gray-100 dark:hover:bg-white/5 flex items-center gap-3 transition-colors text-gray-900 dark:text-white uppercase tracking-tighter">
                                                                    <span class="material-symbols-rounded text-[18px]">edit</span>
                                                                    <span>Edit</span>
                                                                </button>
                                                            </template>
                                                            <template x-if="reply.user_id == currentUserId || (activeCommentReel && reelStates[activeCommentReel]?.channelId == currentChannelId)">
                                                                <button @click="window.deleteComment(reply.id, true); menuOpen = false" class="w-full px-4 py-2 text-left text-[11px] font-bold text-red-500 hover:bg-gray-100 dark:hover:bg-white/5 flex items-center gap-3 transition-colors uppercase tracking-tighter">
                                                                    <span class="material-symbols-rounded text-[18px]">delete</span>
                                                                    <span>Delete</span>
                                                                </button>
                                                            </template>
                                                            <template x-if="reply.user_id != currentUserId">
                                                                <div>
                                                                    <button @click="window.blockUser(reply.user_id, reply.comment); menuOpen = false" class="w-full px-4 py-2 text-left text-[11px] font-bold hover:bg-gray-100 dark:hover:bg-white/5 flex items-center gap-3 transition-colors text-gray-900 dark:text-white uppercase tracking-tighter">
                                                                        <span class="material-symbols-rounded text-[18px]">block</span>
                                                                        <span>Block User</span>
                                                                    </button>
                                                                    <button @click="window.reportComment(reply.id); menuOpen = false" class="w-full px-4 py-2 text-left text-[11px] font-bold hover:bg-gray-100 dark:hover:bg-white/5 flex items-center gap-3 transition-colors text-gray-900 dark:text-white uppercase tracking-tighter">
                                                                        <span class="material-symbols-rounded text-[18px]">flag</span>
                                                                        <span>Report</span>
                                                                    </button>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    </div> <!-- End of mb-4 wrapping div -->
                </template>
            </div>

            {{-- Input Area --}}
            <div class="p-4 pb-8 sm:pb-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#1a1a1a]">
                
                {{-- Reply Indicator --}}
                <div x-show="replyToId" x-transition class="mb-3 px-4 py-2 bg-orange-100 dark:bg-orange-500/20 rounded-xl flex items-center justify-between border border-orange-200 dark:border-orange-500/30">
                    <p class="text-[10px] font-black uppercase text-orange-600 dark:text-orange-400">Replying to a comment</p>
                    <button @click="replyToId = null; $refs.commentInput.value = ''" class="material-symbols-rounded text-sm text-orange-600 dark:text-orange-400">close</button>
                </div>

                <div class="flex gap-3 items-end bg-white dark:bg-white/5 p-2 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm focus-within:border-orange-500/50 transition-colors">
                    <div class="relative flex-1">
                        <textarea 
                            x-ref="commentInput"
                            rows="1"
                            x-on:input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'; handleInput($event)"
                            @keydown.down.prevent="if(showSuggestions) suggestionIndex = (suggestionIndex + 1) % mentionSuggestions.length"
                            @keydown.up.prevent="if(showSuggestions) suggestionIndex = (suggestionIndex - 1 + mentionSuggestions.length) % mentionSuggestions.length"
                            @keydown.enter.prevent="if(showSuggestions) { insertMention(mentionSuggestions[suggestionIndex].username); } else { $el.blur(); }"
                            @keydown.escape="showSuggestions = false"
                            placeholder="Add a comment..." 
                             class="w-full bg-transparent border-none focus:ring-0 text-sm font-medium dark:text-white px-3 py-2 pl-8 resize-none max-h-32"
                        ></textarea>
                        <button type="button" @click="mainEmojisOpen = !mainEmojisOpen" class="absolute left-1 top-1/2 -translate-y-1/2 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-colors flex items-center justify-center" title="Add emoji">
                            <span class="material-symbols-rounded text-[22px] text-gray-400">emoji_emotions</span>
                        </button>
                        <div x-show="mainEmojisOpen" x-transition @click.away="mainEmojisOpen = false" class="absolute bottom-full mb-2 left-0 w-[280px] bg-white dark:bg-[#1A1A1A] border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl z-[100] overflow-hidden">
                            <div class="p-1.5 border-b border-gray-100 dark:border-white/5">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2">Emoji</span>
                            </div>
                            <div class="p-2 grid grid-cols-8 gap-0.5 max-h-[220px] overflow-y-auto">
                                <template x-for="(emoji, idx) in (window.EMOJI_LIST || [])" :key="idx">
                                    <button type="button" @click="$refs.commentInput.focus(); const el = $refs.commentInput; const start = el.selectionStart; const end = el.selectionEnd; el.value = el.value.slice(0,start) + emoji + el.value.slice(end); el.selectionStart = el.selectionEnd = start + emoji.length; $el.dispatchEvent(new Event('input')); mainEmojisOpen = false;" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 cursor-pointer transition-colors text-lg" x-text="emoji"></button>
                                </template>
                            </div>
                        </div>

                        <!-- Mention Suggestions Dropdown -->
                        <div x-show="showSuggestions" 
                             x-transition
                             class="absolute bottom-full left-0 mb-2 w-64 bg-white dark:bg-[#1A1A1A] rounded-2xl shadow-2xl border border-gray-100 dark:border-white/5 py-2 z-[100] overflow-hidden"
                             @click.outside="showSuggestions = false">
                            <template x-for="(suggestion, index) in mentionSuggestions" :key="index">
                                <div @click="insertMention(suggestion.username)"
                                     :class="suggestionIndex === index ? 'bg-orange-500 text-white' : 'hover:bg-gray-50 dark:hover:bg-white/5 text-gray-900 dark:text-white'"
                                     class="px-4 py-2 flex items-center gap-3 cursor-pointer transition-colors">
                                    <img :src="suggestion.avatar" class="w-8 h-8 rounded-full object-cover border border-white/10">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold" x-text="suggestion.name"></span>
                                        <span class="text-[10px] opacity-60" x-text="'@' + suggestion.username"></span>
                                    </div>
                                </div>
                            </template>
                            <div x-show="mentionSuggestions.length === 0" class="px-4 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                No users found
                            </div>
                        </div>
                    </div>
                    <button 
                        x-on:click="addComment(activeCommentReel, $refs.commentInput.value)"
                        :disabled="postingComment"
                        class="w-10 h-10 rounded-xl gradient-orange text-white flex items-center justify-center shadow-lg shadow-orange-500/20 active:scale-90 transition-all flex-shrink-0 disabled:opacity-50 disabled:active:scale-100"
                    >
                        <template x-if="!postingComment">
                            <span class="material-symbols-rounded">send</span>
                        </template>
                        <template x-if="postingComment">
                            <div class="w-5 h-5 border-2 border-white/20 border-t-white rounded-full animate-spin"></div>
                        </template>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ AUDIO DRAWER (Native Sheet Look) ═══ --}}
    <div x-show="activeAudioReel" x-cloak class="fixed inset-0 z-[10001] flex items-end sm:items-center justify-center p-0 sm:p-4">
        {{-- Backdrop --}}
        <div x-on:click="activeAudioReel = null" 
             x-show="activeAudioReel"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        {{-- The Sheet --}}
        <div x-show="activeAudioReel"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full sm:scale-95 sm:opacity-0"
             x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 sm:scale-100"
             x-transition:leave-end="translate-y-full sm:scale-95 sm:opacity-0"
             class="relative w-full sm:max-w-md bg-white dark:bg-[#121212] rounded-t-[2rem] sm:rounded-[2rem] max-h-[85vh] h-auto flex flex-col overflow-y-auto custom-scrollbar shadow-2xl">
            
            <div class="h-1.5 w-12 bg-gray-200 dark:bg-white/10 rounded-full mx-auto mt-4 mb-2 sm:hidden"></div>

            <div class="px-6 py-6 text-center border-b border-gray-100 dark:border-white/5 relative">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] dark:text-white">Audio Options</h3>
                <button x-on:click="activeAudioReel = null" class="absolute right-6 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-gradient-to-tr from-orange-500 to-rose-600 text-white flex items-center justify-center shadow-lg shadow-orange-500/20 active:scale-90 transition-transform">
                    <span class="material-symbols-rounded text-base">close</span>
                </button>
            </div>

            <div class="p-6 space-y-3">
                <button x-on:click="useAudio(activeAudioReel)" class="w-full flex items-center gap-4 p-4 bg-gray-50 dark:bg-white/5 rounded-2xl hover:bg-gray-100 dark:hover:bg-white/10 transition-all active:scale-95 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-purple-600 to-indigo-600 text-white flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform">
                        <span class="material-symbols-rounded text-2xl">music_video</span>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-black dark:text-white uppercase tracking-tight">Use This Audio</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Create a new Reel</p>
                    </div>
                </button>

                <button x-show="reelStates[activeAudioReel]?.allow_duet" x-on:click="duet(activeAudioReel)" class="w-full flex items-center gap-4 p-4 bg-gray-50 dark:bg-white/5 rounded-2xl hover:bg-gray-100 dark:hover:bg-white/10 transition-all active:scale-95 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-orange-500 to-red-600 text-white flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform">
                        <span class="material-symbols-rounded text-2xl">groups</span>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-black dark:text-white uppercase tracking-tight">Remix / Duet</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Collab side-by-side</p>
                    </div>
                </button>

                <button x-on:click="saveAudio(activeAudioReel)" class="w-full flex items-center gap-4 p-4 bg-gray-50 dark:bg-white/5 rounded-2xl hover:bg-gray-100 dark:hover:bg-white/10 transition-all active:scale-95 group">
                    <div class="w-12 h-12 rounded-xl bg-gray-200 dark:bg-white/10 text-gray-600 dark:text-white flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform">
                        <span class="material-symbols-rounded text-2xl">library_add</span>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-black dark:text-white uppercase tracking-tight">Save Audio</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Add to your library</p>
                    </div>
                </button>

                <button x-on:click="viewAudioPage(activeAudioReel)" class="w-full flex items-center gap-4 p-4 bg-gray-50 dark:bg-white/5 rounded-2xl hover:bg-gray-100 dark:hover:bg-white/10 transition-all active:scale-95 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-600 text-white flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform">
                        <span class="material-symbols-rounded text-2xl">info</span>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-black dark:text-white uppercase tracking-tight">Audio Page</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">See reels with this audio</p>
                    </div>
                </button>
            </div>

            <div class="p-6 pt-0 hidden sm:block">
                <button x-on:click="activeAudioReel = null" class="w-full py-4 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">Cancel</button>
            </div>
        </div>
    </div>

    {{-- ═══ OPTIONS DRAWER (Native Sheet Look) ═══ --}}
    <div x-show="activeOptionsReel" x-cloak class="fixed inset-0 z-[10001] flex items-end sm:items-center justify-center p-0 sm:p-4">
        {{-- Backdrop --}}
        <div x-on:click="activeOptionsReel = null" 
             x-show="activeOptionsReel"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        {{-- The Sheet --}}
        <div x-show="activeOptionsReel"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full sm:scale-95 sm:opacity-0"
             x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 sm:scale-100"
             x-transition:leave-end="translate-y-full sm:scale-95 sm:opacity-0"
             class="relative w-full sm:max-w-md bg-white dark:bg-[#121212] rounded-t-[2rem] sm:rounded-[2rem] h-auto flex flex-col overflow-hidden shadow-2xl">
            <div class="h-1.5 w-12 bg-gray-200 dark:bg-white/10 rounded-full mx-auto mt-4 mb-2 sm:hidden"></div>

            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] dark:text-white">More Options</h3>
                <button x-on:click="activeOptionsReel = null" class="w-8 h-8 rounded-full bg-gradient-to-tr from-orange-500 to-rose-600 text-white flex items-center justify-center shadow-lg shadow-orange-500/20 active:scale-90 transition-transform">
                    <span class="material-symbols-rounded text-base">close</span>
                </button>
            </div>

            <div class="p-6 space-y-2">
                <button x-on:click.stop="saveToPlaylist(activeOptionsReel)" class="w-full flex items-center gap-4 p-4 hover:bg-gray-50 dark:hover:bg-white/5 rounded-2xl transition-all active:scale-95 group text-gray-400 dark:text-white/70 hover:text-orange-500">
                    <span class="material-symbols-rounded text-2xl transition-colors" x-text="reelStates[activeOptionsReel]?.isInAnyPlaylist ? 'playlist_remove' : 'bookmark'"></span>
                    <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight" x-text="reelStates[activeOptionsReel]?.isInAnyPlaylist ? 'Manage Playlist' : 'Save to Playlist'"></span>
                </button>
                <button x-on:click.stop="watchLater(activeOptionsReel)" class="w-full flex items-center gap-4 p-4 hover:bg-gray-50 dark:hover:bg-white/5 rounded-2xl transition-all active:scale-95 group text-gray-400 dark:text-white/70 hover:text-orange-500">
                    <span class="material-symbols-rounded text-2xl transition-colors">schedule</span>
                    <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Watch Later</span>
                </button>
                <button x-show="activeOptionsReel && reelStates[activeOptionsReel] && reelStates[activeOptionsReel].user_id != currentUserId"
                        x-on:click.stop="notInterested(activeOptionsReel)" class="w-full flex items-center gap-4 p-4 hover:bg-gray-50 dark:hover:bg-white/5 rounded-2xl transition-all active:scale-95 group text-gray-400 dark:text-white/70 hover:text-red-500">
                    <span class="material-symbols-rounded text-2xl transition-colors">visibility_off</span>
                    <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Not Interested</span>
                </button>
                <button x-show="activeOptionsReel && reelStates[activeOptionsReel] && reelStates[activeOptionsReel].user_id != currentUserId"
                        x-on:click.stop="openReport(activeOptionsReel)" 
                        class="w-full flex items-center gap-4 p-4 hover:bg-gray-50 dark:hover:bg-white/5 rounded-2xl transition-all active:scale-95 group border-t border-gray-100 dark:border-white/5 mt-2 pt-4 text-gray-400 dark:text-white/70 hover:text-red-600">
                    <span class="material-symbols-rounded text-2xl transition-colors">report</span>
                    <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Report</span>
                </button>
            </div>

            <div class="p-6 pt-0">
                <button x-on:click="activeOptionsReel = null" class="w-full py-4 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">Cancel</button>
            </div>
        </div>
    </div>

    {{-- ═══ SHARE DRAWER (Native Sheet Look) ═══ --}}
    <div x-show="activeShareReel" x-cloak class="fixed inset-0 z-[10001] flex items-end sm:items-center justify-center p-0 sm:p-4">
        {{-- Backdrop --}}
        <div x-on:click="activeShareReel = null" 
             x-show="activeShareReel"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        {{-- The Sheet --}}
        <div x-show="activeShareReel"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full sm:scale-95 sm:opacity-0"
             x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 sm:scale-100"
             x-transition:leave-end="translate-y-full sm:scale-95 sm:opacity-0"
             class="relative w-full sm:max-w-md bg-white dark:bg-[#121212] rounded-t-[2rem] sm:rounded-[2rem] h-auto flex flex-col overflow-hidden shadow-2xl">
            
            <div class="h-1.5 w-12 bg-gray-200 dark:bg-white/10 rounded-full mx-auto mt-4 mb-2 sm:hidden"></div>

            <div class="px-6 py-6 text-center border-b border-gray-100 dark:border-white/5 relative">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] dark:text-white">Share To</h3>
                <button x-on:click="activeShareReel = null" class="absolute right-6 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-gradient-to-tr from-orange-500 to-rose-600 text-white flex items-center justify-center shadow-lg shadow-orange-500/20 active:scale-90 transition-transform">
                    <span class="material-symbols-rounded text-base">close</span>
                </button>
            </div>

            <div class="py-6">
                <div class="flex items-center gap-6 overflow-x-auto no-scrollbar px-8 py-2">                    {{-- WhatsApp --}}
                    <button x-on:click="shareTo('whatsapp')" class="flex flex-col items-center gap-3 group flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg shadow-green-500/20 group-active:scale-90 transition-transform" style="background-color: #25D366;">
                            <svg style="width: 28px; height: 28px; fill: white;" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 0 5.414 0 12.05c0 2.123.553 4.197 1.604 6.02L0 24l6.134-1.61a11.752 11.752 0 005.914 1.56h.005c6.635 0 12.05-5.414 12.05-12.05 0-3.217-1.252-6.242-3.525-8.514z"/></svg>
                        </div>
                        <span class="text-[9px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">WhatsApp</span>
                    </button>

                    {{-- Messenger --}}
                    <button x-on:click="shareTo('messenger')" class="flex flex-col items-center gap-3 group flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg group-active:scale-90 transition-transform" style="background: linear-gradient(45deg, #006AFF, #00E2FF);">
                            <svg style="width: 28px; height: 28px; fill: white;" viewBox="0 0 24 24"><path d="M12 0C5.463 0 0 5.14 0 11.48c0 3.606 1.776 6.81 4.542 8.874V24l3.522-1.936A12.72 12.72 0 0012 22.96c6.537 0 12-5.14 12-11.48S18.537 0 12 0zm1.31 15.34l-3.35-3.582-6.54 3.582 7.194-7.638 3.35 3.582 6.54-3.582-7.194 7.638z"/></svg>
                        </div>
                        <span class="text-[9px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Messenger</span>
                    </button>

                    {{-- Instagram --}}
                    <button x-on:click="shareTo('instagram')" class="flex flex-col items-center gap-3 group flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg group-active:scale-90 transition-transform" style="background: linear-gradient(45deg, #f9ce34, #ee2a7b, #6228d7);">
                            <svg style="width: 28px; height: 28px; fill: white;" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 1.764.308 2.227.77.462.463.708.861.77 2.228.056 1.265.07 1.644.07 4.848s-.014 3.583-.07 4.848c-.062 1.367-.308 1.765-.77 2.227-.463.462-.861.708-2.227.77-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-1.764-.308-2.227-.77-.462-.463-.708-.861-.77-2.228-.056-1.265-.07-1.644-.07-4.848s.014-3.583.07-4.848c.062-1.367.308-1.765.77-2.227.463-.462.861-.708 2.227-.77 1.266-.058 1.646-.07 4.85-.07M12 0C8.741 0 8.333.014 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.741 0 12s.014 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126s1.078 1.078 1.866 1.384c.765.297 1.635.499 2.913.558C8.333 23.986 8.741 24 12 24s3.667-.014 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384s1.078-1.078 1.384-1.866c.297-.765.499-1.635.558-2.913.06-1.28.072-1.687.072-4.947s-.014-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126s-1.078-1.078-1.866-1.384c-.765-.297-1.635-.499-2.913-.558C15.667.012 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.88 1.44 1.44 0 000-2.88z"/></svg>
                        </div>
                        <span class="text-[9px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Instagram</span>
                    </button>

                    {{-- Facebook --}}
                    <button x-on:click="shareTo('facebook')" class="flex flex-col items-center gap-3 group flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg group-active:scale-90 transition-transform" style="background-color: #1877F2;">
                            <svg style="width: 28px; height: 28px; fill: white;" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </div>
                        <span class="text-[9px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Facebook</span>
                    </button>

                    {{-- X (Twitter) --}}
                    <button x-on:click="shareTo('twitter')" class="flex flex-col items-center gap-3 group flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl bg-black dark:bg-white/10 flex items-center justify-center shadow-lg group-active:scale-90 transition-transform">
                            <svg style="width: 24px; height: 24px; fill: white;" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </div>
                        <span class="text-[9px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">X (Twitter)</span>
                    </button>

                    {{-- Snapchat --}}
                    <button x-on:click="shareTo('snapchat')" class="flex flex-col items-center gap-3 group flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg shadow-yellow-400/20 group-active:scale-90 transition-transform" style="background-color: #FFFC00;">
                            <svg style="width: 28px; height: 28px; fill: black;" viewBox="0 0 24 24"><path d="M12 0C8.36 0 5.4 1.72 5.4 4.75c0 .35.04.7.12 1.03-.68.17-1.16.79-1.16 1.51 0 .34.11.66.3.93-.19.27-.3.59-.3.93 0 1.15 1.18 1.34 1.18 2.37 0 .35-.04.7-.12 1.03-.68.17-1.16.79-1.16 1.51 0 .34.11.66.3.93-.19.27-.3.59-.3.93 0 .73.47 1.1 1.04 1.25-.08.33-.12.68-.12 1.03 0 3.03 2.96 4.75 6.6 4.75s6.6-1.72 6.6-4.75c0-.35-.04-.7-.12-1.03.57-.15 1.04-.52 1.04-1.25 0-.34-.11-.66-.3-.93.19-.27.3-.59.3-.93 0-.72-.48-1.34-1.16-1.51-.08-.33-.12-.68-.12-1.03 0-1.03 1.18-1.22 1.18-2.37 0-.34-.11-.66-.3-.93.19-.27.3-.59.3-.93 0-.72-.48-1.34-1.16-1.51.08-.33.12-.68.12-1.03C18.6 1.72 15.64 0 12 0z"/></svg>
                        </div>
                        <span class="text-[9px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Snapchat</span>
                    </button>
                </div>

                <div class="mt-4 flex flex-wrap gap-4 px-8 justify-center">
                    <button class="flex items-center gap-2 px-6 py-3 bg-gray-50 dark:bg-white/5 text-slate-700 dark:text-white rounded-2xl hover:bg-gray-100 dark:hover:bg-white/10 transition-all border border-gray-100 dark:border-white/10 group active:scale-95" 
                            x-on:click="navigator.clipboard.writeText(window.location.origin + '/reels/' + activeShareReel); Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Link copied!', background: '#1A1A1A', color: '#ffffff', showConfirmButton: false, timer: 3000 })">
                        <span class="material-symbols-rounded text-xl group-hover:rotate-12 transition-transform">link</span>
                        <span class="text-[10px] font-black uppercase tracking-widest">Copy Link</span>
                    </button>
                    <button class="flex items-center gap-2 px-6 py-3 bg-gray-50 dark:bg-white/5 text-slate-700 dark:text-white rounded-2xl hover:bg-gray-100 dark:hover:bg-white/10 transition-all border border-gray-100 dark:border-white/10 group active:scale-95"
                            x-on:click="generateQRCode(activeShareReel)">
                        <span class="material-symbols-rounded text-xl group-hover:rotate-12 transition-transform">qr_code_2</span>
                        <span class="text-[10px] font-black uppercase tracking-widest">QR Code</span>
                    </button>
                </div>
            </div>

            <div class="p-6 pt-0 border-t border-gray-100 dark:border-white/5 hidden sm:block">
                <button x-on:click="activeShareReel = null" class="w-full py-4 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">Close</button>
            </div>
        </div>
    </div>

    {{-- ═══ DETAILS DRAWER (Native Sheet Look) ═══ --}}
    <div x-show="activeDetailsReel" x-cloak class="fixed inset-0 z-[10001] flex items-end sm:items-center justify-center p-0 sm:p-4">
        {{-- Backdrop --}}
        <div x-on:click="activeDetailsReel = null" 
             x-show="activeDetailsReel"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        {{-- The Sheet --}}
        <div x-show="activeDetailsReel"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full sm:scale-95 sm:opacity-0"
             x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 sm:scale-100"
             x-transition:leave-end="translate-y-full sm:scale-95 sm:opacity-0"
             class="relative w-full sm:max-w-md bg-white dark:bg-[#121212] rounded-t-[2rem] sm:rounded-[2rem] h-auto flex flex-col overflow-hidden shadow-2xl max-h-[85vh]">
            
            <div class="h-1.5 w-12 bg-gray-200 dark:bg-white/10 rounded-full mx-auto mt-4 mb-2"></div>

            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] dark:text-white">Reel Details</h3>
                <button x-on:click="activeDetailsReel = null" class="w-8 h-8 rounded-full bg-gradient-to-tr from-orange-500 to-rose-600 text-white flex items-center justify-center shadow-lg shadow-orange-500/20 active:scale-90 transition-transform">
                    <span class="material-symbols-rounded text-base">close</span>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar space-y-8">
                {{-- Reel Info --}}
                <div class="space-y-4">
                    <h2 class="text-xl font-black dark:text-white leading-tight" x-text="currentReelData?.title"></h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed" x-html="formatDescription(currentReelData?.description)"></p>
                </div>

                {{-- Hashtags / Tags --}}
                <template x-if="currentReelData?.hashtags && currentReelData.hashtags.length > 0">
                    <div class="space-y-2">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Hashtags</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="tag in currentReelData.hashtags" :key="tag">
                                <a :href="'/search?q=%23' + encodeURIComponent(tag)" class="inline-flex items-center px-3 py-1 bg-orange-500/10 hover:bg-orange-500/20 text-orange-500 rounded-full text-xs font-bold transition-all hover:scale-105">
                                    <span x-text="'#' + tag"></span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Channel Info --}}
                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-white/5 rounded-[2rem] border border-gray-100 dark:border-white/5">
                    <a :href="currentReelData?.channel_url" class="flex-shrink-0">
                        <template x-if="currentReelData?.user_avatar">
                            <img :src="currentReelData?.user_avatar" class="w-14 h-14 rounded-full border-2 border-white/10 object-cover">
                        </template>
                        <template x-if="!currentReelData?.user_avatar">
                            <div class="w-14 h-14 rounded-full border-2 border-white/10 gradient-orange flex items-center justify-center text-white font-black text-xs uppercase" x-text="getInitials(currentReelData?.username)"></div>
                        </template>
                    </a>
                    <a :href="currentReelData?.channel_url" class="flex-1">
                        <p class="text-sm font-black dark:text-white uppercase tracking-tight" x-text="currentReelData?.username"></p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest" x-text="currentReelData?.subscribers_count + ' Subscribers'"></p>
                    </a>
                    <a :href="currentReelData?.user_id == currentUserId ? '{{ route('studio.dashboard') }}' : currentReelData?.channel_url" 
                       class="px-5 py-2.5 bg-orange-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-orange-500/20 active:scale-95 transition-all"
                       x-text="currentReelData?.user_id == currentUserId ? 'Analytics' : 'Visit'"></a>
                </div>

                {{-- Stats Grid --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-3xl border border-gray-100 dark:border-white/5 text-center">
                        <span class="material-symbols-rounded text-orange-500 mb-2">visibility</span>
                        <p class="text-[10px] font-black dark:text-white uppercase tracking-widest" x-text="currentReelData?.viewsCount"></p>
                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-tighter">Views</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-3xl border border-gray-100 dark:border-white/5 text-center">
                        <span class="material-symbols-rounded text-rose-500 mb-2">favorite</span>
                        <p class="text-[10px] font-black dark:text-white uppercase tracking-widest" x-text="currentReelData?.likesCount"></p>
                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-tighter">Likes</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-3xl border border-gray-100 dark:border-white/5 text-center">
                        <span class="material-symbols-rounded text-blue-500 mb-2">chat</span>
                        <p class="text-[10px] font-black dark:text-white uppercase tracking-widest" x-text="currentReelData?.commentsCount"></p>
                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-tighter">Comments</p>
                    </div>
                </div>

                {{-- Additional Info --}}
                <div class="space-y-4 pt-4 border-t border-gray-100 dark:border-white/5">
                    <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-widest">
                        <span class="text-gray-400">Published</span>
                        <span class="dark:text-white" x-text="currentReelData?.created_at"></span>
                    </div>
                    <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-widest">
                        <span class="text-gray-400">Category</span>
                        <span class="px-3 py-1 bg-white/10 rounded-full dark:text-white" x-text="currentReelData?.category_name"></span>
                    </div>
                </div>
            </div>

            <div class="p-6 pt-0 hidden sm:block">
                <button x-on:click="activeDetailsReel = null" class="w-full py-4 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">Close Details</button>
            </div>
        </div>
    </div>
    </div>

    {{-- ═══ REPORT BOTTOM SHEET (Native Sheet Look) ═══ --}}
    <div x-show="activeReportReel" x-cloak class="fixed inset-0 z-[10001] flex items-end sm:items-center justify-center">
        {{-- Backdrop --}}
        <div x-on:click="activeReportReel = null" 
             x-show="activeReportReel"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        {{-- The Sheet --}}
        <div x-show="activeReportReel"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
             x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 sm:scale-100 sm:opacity-100"
             x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
             class="relative w-full sm:max-w-md bg-white dark:bg-[#1A1A1A] rounded-t-[2.5rem] sm:rounded-[2.5rem] p-6 pb-12 sm:p-8 shadow-2xl shadow-black/50 overflow-y-auto max-h-[90vh] custom-scrollbar">
            
            <div class="w-12 h-1.5 bg-gray-200 dark:bg-white/10 rounded-full mx-auto mb-6 sm:hidden"></div>

            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Report Reel</h3>
                <button x-on:click="activeReportReel = null" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center transition-all active:scale-90">
                    <span class="material-symbols-rounded text-sm">close</span>
                </button>
            </div>
            <p class="text-[10px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-8">Tell us what's wrong with this reel</p>

            <div class="space-y-2 mb-8">
                <template x-for="r in ['Inappropriate Content', 'Hate Speech', 'Harassment', 'Spam or Misleading', 'Violence', 'Copyright Infringement', 'Other']">
                    <button @click="reportReason = r" 
                            class="w-full p-4 rounded-2xl border transition-all text-left flex items-center justify-between group"
                            :class="reportReason === r ? 'bg-red-500/10 border-red-500 text-red-500 shadow-lg shadow-red-500/10' : 'bg-slate-50 dark:bg-white/5 border-transparent dark:border-white/5 text-slate-600 dark:text-white/60 hover:bg-slate-100 dark:hover:bg-white/10'">
                        <span class="text-xs font-bold" x-text="r"></span>
                        <div class="w-5 h-5 rounded-full border-2 border-current flex items-center justify-center opacity-40" :class="reportReason === r ? 'opacity-100 bg-red-500 border-red-500' : ''">
                            <span class="material-symbols-rounded text-[14px] text-white" x-show="reportReason === r">check</span>
                        </div>
                    </button>
                </template>
            </div>

            <div x-show="reportReason === 'Other'" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mb-8">
                <label class="text-[10px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-2 block">Additional Details</label>
                <textarea x-model="reportDetails" 
                          placeholder="Please provide more information about your report..."
                          class="w-full h-32 p-4 rounded-2xl bg-slate-50 dark:bg-white/5 border-2 border-transparent focus:border-red-500 transition-all text-slate-600 dark:text-white text-xs resize-none outline-none"></textarea>
            </div>

            <div class="flex gap-3">
                <button x-on:click="activeReportReel = null" class="flex-1 py-4 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all">Cancel</button>
                <button @click="submitReport()" 
                        :disabled="!reportReason || submittingReport"
                        class="flex-[2] py-4 rounded-2xl bg-gradient-to-r from-rose-600 to-red-600 text-white text-[10px] font-black uppercase tracking-widest shadow-xl shadow-rose-500/20 disabled:opacity-50 flex items-center justify-center gap-2 active:scale-95 transition-all">
                    <template x-if="submittingReport">
                        <span class="w-3 h-3 border-2 border-white/20 border-t-white rounded-full animate-spin"></span>
                    </template>
                    <span x-text="submittingReport ? 'Submitting...' : 'Submit Report'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
</div>


<style>
    .animate-spin-slow { animation: reelSpin 5s linear infinite; }
    @keyframes reelSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    .marquee-container {
        overflow: hidden;
        white-space: nowrap;
        position: relative;
        mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
    }
    .marquee-content {
        display: inline-block;
        animation: marquee 12s linear infinite;
        padding-left: 20px;
    }
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-100%); }
    }
</style>

<script>
    window.EMOJI_LIST = ["\u{1f600}","\u{1f603}","\u{1f604}","\u{1f601}","\u{1f606}","\u{1f605}","\u{1f923}","\u{1f602}","\u{1f642}","\u{1f643}","\u{1f609}","\u{1f60a}","\u{1f607}","\u{1f970}","\u{1f60d}","\u{1f929}","\u{1f618}","\u{1f617}","\u{263a}\u{fe0f}","\u{1f61a}","\u{1f619}","\u{1f60b}","\u{1f61b}","\u{1f61c}","\u{1f92a}","\u{1f61d}","\u{1f911}","\u{1f917}","\u{1f92d}","\u{1f92b}","\u{1f914}","\u{1f910}","\u{1f928}","\u{1f610}","\u{1f611}","\u{1f636}","\u{1f60f}","\u{1f612}","\u{1f644}","\u{1f62c}","\u{1f925}","\u{1f60c}","\u{1f614}","\u{1f62a}","\u{1f924}","\u{1f634}","\u{1f637}","\u{1f912}","\u{1f915}","\u{1f922}","\u{1f92e}","\u{1f927}","\u{1f975}","\u{1f976}","\u{1f974}","\u{1f635}","\u{1f92f}","\u{1f920}","\u{1f973}","\u{1f60e}","\u{1f913}","\u{1f9d0}","\u{1f615}","\u{1f61f}","\u{1f641}","\u{1f62e}","\u{1f62f}","\u{1f632}","\u{1f633}","\u{1f97a}","\u{1f626}","\u{1f627}","\u{1f628}","\u{1f630}","\u{1f625}","\u{1f622}","\u{1f62d}","\u{1f631}","\u{1f616}","\u{1f623}","\u{1f61e}","\u{1f613}","\u{1f629}","\u{1f62b}","\u{1f971}","\u{1f624}","\u{1f621}","\u{1f620}","\u{1f92c}","\u{1f608}","\u{1f47f}","\u{1f480}","\u{2620}\u{fe0f}","\u{1f4a9}","\u{1f921}","\u{1f479}","\u{1f47a}","\u{1f47b}","\u{1f47d}","\u{1f47e}","\u{1f916}","\u{1f63a}","\u{1f638}","\u{1f639}","\u{1f63b}","\u{1f63c}","\u{1f63d}","\u{1f640}","\u{1f63f}","\u{1f63e}","\u{1f648}","\u{1f649}","\u{1f64a}","\u{1f48b}","\u{1f48c}","\u{1f498}","\u{1f49d}","\u{1f496}","\u{1f497}","\u{1f493}","\u{1f49e}","\u{1f495}","\u{1f49f}","\u{2763}\u{fe0f}","\u{1f494}","\u{2764}\u{fe0f}","\u{1f9e1}","\u{1f49b}","\u{1f49a}","\u{1f499}","\u{1f49c}","\u{1f90e}","\u{1f5a4}","\u{1f90d}","\u{1f4af}","\u{1f4a2}","\u{1f4a5}","\u{1f4ab}","\u{1f4a6}","\u{1f4a8}","\u{1f573}\u{fe0f}","\u{1f4a3}","\u{1f4ac}","\u{1f441}\u{fe0f}\u{200d}\u{1f5e8}\u{fe0f}","\u{1f5e8}\u{fe0f}","\u{1f5ef}\u{fe0f}","\u{1f4ad}","\u{1f4a4}","\u{1f44b}","\u{1f91a}","\u{1f590}\u{fe0f}","\u{270b}","\u{1f596}","\u{1f44c}","\u{1f90f}","\u{270c}\u{fe0f}","\u{1f91e}","\u{1f91f}","\u{1f918}","\u{1f919}","\u{1f448}","\u{1f449}","\u{1f446}","\u{1f595}","\u{1f447}","\u{261d}\u{fe0f}","\u{1f44d}","\u{1f44e}","\u{270a}","\u{1f44a}","\u{1f91b}","\u{1f91c}","\u{1f44f}","\u{1f64c}","\u{1f450}","\u{1f932}","\u{1f91d}","\u{1f64f}","\u{270d}\u{fe0f}","\u{1f485}","\u{1f933}","\u{1f4aa}","\u{1f9b5}","\u{1f9b6}","\u{1f442}","\u{1f443}","\u{1f9e0}","\u{1f9b7}","\u{1f9b4}","\u{1f440}","\u{1f441}\u{fe0f}","\u{1f445}","\u{1f444}","\u{1f525}","\u{2728}","\u{1f31f}","\u{2b50}","\u{1f389}","\u{1f38a}","\u{1f388}","\u{1f381}","\u{1f3c6}","\u{1f947}","\u{1f948}","\u{1f949}","\u{26bd}","\u{1f3c0}","\u{1f3c8}","\u{26be}","\u{1f3be}","\u{1f3d0}","\u{1f3c9}","\u{1f3b1}","\u{1f3af}","\u{1f3ae}","\u{1f579}\u{fe0f}","\u{1f3b0}","\u{1f3b2}","\u{1f3a8}","\u{1f3ac}","\u{1f3a4}","\u{1f3a7}","\u{1f3bc}","\u{1f3b5}","\u{1f3b6}","\u{1f3b9}","\u{1f941}","\u{1f3b7}","\u{1f3ba}","\u{1f3b8}","\u{1fa95}","\u{1f3bb}","\u{265f}\u{fe0f}","\u{1f3b3}"];
    
    document.addEventListener('alpine:init', () => {
        Alpine.data('reelsPlayer', () => ({
            formatDescription(text) {
                if (!text) return '';
                let div = document.createElement('div');
                div.textContent = text;
                let escaped = div.innerHTML;
                return escaped.replace(/#(\w+)/g, '<a href="/search?q=%23$1" class="text-orange-500 font-bold hover:underline transition-all">#$1</a>');
            },
            getInitials(name) {
                if (!name) return '';
                const words = name.trim().split(/\s+/);
                if (words.length >= 2) {
                    return (words[0].charAt(0) + words[words.length - 1].charAt(0)).toUpperCase();
                }
                return name.substring(0, 2).toUpperCase();
            },
            currentIndex: 0,
            baseUrl: '{{ url('/') }}',
            currentUserId: {{ auth()->id() ?? 'null' }},
            currentChannelId: {{ auth()->user()?->channel?->id ?? 0 }},
            totalReels: {{ count($reels) > 0 ? count($reels) + 1 : 0 }},
            isAnimating: false,
            touchStartY: 0,
            touchDeltaY: 0,
            isTouching: false,
            wheelLock: false,
            activeCommentReel: null,
            mentionSuggestions: [],
            showSuggestions: false,
            mentionQuery: '',
            suggestionIndex: 0,

            handleInput(e) {
                const text = e.target.value;
                const cursorPos = e.target.selectionStart;
                const textBeforeCursor = text.substring(0, cursorPos);
                const mentionMatch = textBeforeCursor.match(/@(\w*)$/);

                if (mentionMatch) {
                    this.mentionQuery = mentionMatch[1];
                    this.showSuggestions = true;
                    this.fetchMentions(this.mentionQuery);
                } else {
                    this.showSuggestions = false;
                }
            },

            async fetchMentions(query) {
                try {
                    const response = await fetch(`/api/search/mentions?q=${query}`);
                    this.mentionSuggestions = await response.json();
                    this.suggestionIndex = 0;
                } catch (e) {
                    console.error('Failed to fetch mentions', e);
                }
            },

            insertMention(username) {
                const text = this.$refs.commentInput.value;
                const cursorPos = this.$refs.commentInput.selectionStart;
                const textBeforeCursor = text.substring(0, cursorPos);
                const textAfterCursor = text.substring(cursorPos);
                
                const newTextBeforeCursor = textBeforeCursor.replace(/@(\w*)$/, `@${username} `);
                this.$refs.commentInput.value = newTextBeforeCursor + textAfterCursor;
                
                this.showSuggestions = false;
                this.$refs.commentInput.focus();
                
                // Adjust height
                this.$refs.commentInput.style.height = 'auto';
                this.$refs.commentInput.style.height = this.$refs.commentInput.scrollHeight + 'px';
            },
            activeShareReel: null,
            activeAudioReel: null,
            activeOptionsReel: null,
            activeDetailsReel: null,
            activeReportReel: null,
            reportReason: '',
            submittingReport: false,
            currentReelData: null,
            isUnmuted: true,
            showPlayState: false,
            lastState: 'play',
            progress: 0,
            isDraggingProgress: false,
            progressInstant: false,
            progressSnapTimer: null,
            playStateTimeout: null,
            comments: [],
            loadingComments: false,
            postingComment: false,
            replyToId: null,
            replyToUser: '',
            viewTimer: null,
            slideStartTimes: {},
            isPausedByUser: false,
            mainEmojisOpen: false,
            channelSubscriptions: {
                @foreach($reels as $r)
                @if($r->channel)
                {{ $r->channel->id }}: {{ $r->channel->subscribers->contains(auth()->id()) ? 'true' : 'false' }},
                @endif
                @endforeach
            },
            reelStates: {
                @foreach($reels as $r)
                @json($r->slug): {
                    viewsCount: {{ $r->views_count }},
                    likesCount: {{ $r->likes_count }},
                    commentsCount: {{ $r->comments_count }},
                    sharesCount: {{ $r->shares_count }},
                    isLiked: {{ $r->likes->where('user_id', auth()->id())->count() > 0 ? 'true' : 'false' }},
                    isSubscribed: {{ $r->channel && $r->channel->subscribers->contains(auth()->id()) ? 'true' : 'false' }},
                    channelId: {{ $r->channel->id ?? 0 }},
                    title: @json($r->title),
                    description: {!! json_encode(str_replace(["\r", "\n"], ' ', $r->description), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
                    username: @json($r->user->channel_name ?? $r->user->username),
                    user_avatar: @json($r->user->image ? getImage(getFilePath('userProfile') . '/' . $r->user->image) : ($r->user->channel?->avatar ? getImage(getFilePath('channelAvatar') . '/' . $r->user->channel->avatar) : '')),
                    created_at: @json($r->created_at->diffForHumans()),
                    category_name: @json($r->category?->name ?? 'General'),
                    subscribers_count: {{ $r->channel?->subscribers_count ?? 0 }},
                    channel_url: @json($r->user->channel ? route('channels.show', $r->user->channel->slug) : '#'),
                    music_id: @json($r->music_id),
                    music_slug: @json($r->music?->slug),
                    id: {{ $r->id }},
                    user_id: {{ $r->user_id }},
                    allow_duet: {{ $r->allow_duet ? 'true' : 'false' }},
                    isAgeRestricted: {{ $r->is_age_restricted ? 'true' : 'false' }},
                    isSaved: {{ auth()->check() ? (in_array($r->id, $watchLaterReelIds) ? 'true' : 'false') : 'false' }},
                    isInAnyPlaylist: {{ auth()->check() ? (!empty($playlistData[$r->id] ?? []) ? 'true' : 'false') : 'false' }},
                    playlistIds: @json($playlistData[$r->id] ?? []),
                    hashtags: @json($r->hashtags->pluck('hashtag'))
                },
                @endforeach
            },
            processingProgress: {
                @foreach($reels as $r)
                @if($r->status != \App\Constants\Status::PUBLISHED || $r->bunny_status != 'ready')
                @json($r->slug): 0,
                @endif
                @endforeach
            },

            saveAudio(slug) {
                if (!slug) return;
                @guest
                    window.showLoginAlert('save this audio');
                    return;
                @endguest
                fetch(`${this.baseUrl}/reels/${slug}/save-audio`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(d => {
                    if (!d.error) {
                        this.activeAudioReel = null;
                    }
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: d.error ? 'error' : 'success',
                        title: d.message || d.error,
                        background: '#1A1A1A',
                        color: '#ffffff'
                    });
                })
                .catch(err => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'error',
                        title: 'Failed to save audio',
                        background: '#1A1A1A',
                        color: '#ffffff'
                    });
                });
            },

            useAudio(slug) {
                if (!slug) return;
                @auth
                    window.location.href = `/reels/${slug}/use-audio`;
                @else
                    window.showLoginAlert('use this audio');
                @endauth
            },

            duet(slug) {
                if (!slug) return;
                @auth
                    window.location.href = `/reels/${slug}/duet`;
                @else
                    window.showLoginAlert('create a duet');
                @endauth
            },

            viewAudioPage(slug) {
                if (!slug) return;
                const reel = this.reelStates[slug];
                if (!reel) return;
                const musicId = reel.music_id && reel.music_id !== 'null' && reel.music_slug ? reel.music_slug : ('original_' + slug);
                window.location.href = `/reels/audio/${musicId}`;
            },
            
            openDetails(slug) {
                this.currentReelData = this.reelStates[slug];
                this.activeDetailsReel = slug;
            },

            get offset() {
                const h = this.$el.clientHeight || window.innerHeight;
                return this.isTouching
                    ? -(this.currentIndex * h) + this.touchDeltaY
                    : -(this.currentIndex * h);
            },

            reelsSlugs: [
                @foreach($reels as $r)
                @json($r->slug),
                @endforeach
            ],

            init() {
                // Merged from earlier duplicate init — refresh comments when event fires
                window.addEventListener('reel-comments-refreshed', () => {
                    if (this.activeCommentReel) {
                        this.openComments(this.activeCommentReel);
                    }
                });

                window.addEventListener('comment-like-updated', (e) => {
                    const comment = this.comments.find(c => c.id == e.detail.id);
                    if (comment) {
                        comment.is_liked = e.detail.liked;
                        comment.likes_count = e.detail.likes;
                    }
                });

                // Deep link handling
                const urlParams = new URLSearchParams(window.location.search);
                const reelSlug = urlParams.get('reel');
                if (reelSlug) {
                    const reelIndex = this.reelsSlugs.indexOf(reelSlug);
                    if (reelIndex !== -1) {
                        this.currentIndex = reelIndex;
                    }
                }

                this.$nextTick(() => {
                    this.activateSlide(this.currentIndex);
                    // OPTIMIZED: Only next 1 lightweight metadata - current has priority
                    this.preloadSlide(1);
                    this.startAutoPolling();
                });

                this.$watch('isUnmuted', (val) => {
                    let v = document.querySelector('[data-video="' + this.currentIndex + '"]');
                    if (v) {
                        v.muted = !val;
                    }
                });

                window.addEventListener('comment-deleted', (e) => {
                    if (e.detail.isReel) {
                        this.comments = this.comments.filter(c => c.id !== e.detail.id);
                        if (this.activeCommentReel && this.reelStates[this.activeCommentReel]) {
                            this.reelStates[this.activeCommentReel].commentsCount = e.detail.comments_count;
                        }
                    }
                });

                window.addEventListener('video-status-updated', (e) => {
                    if (e.detail.type !== 'reel') return;
                    for (const slug in this.reelStates) {
                        if (this.reelStates[slug].id == e.detail.id) {
                            this.reelStates[slug].isInAnyPlaylist = e.detail.isInAnyPlaylist;
                            this.reelStates[slug].playlistIds = e.detail.playlistIds || [];
                            break;
                        }
                    }
                });

                window.addEventListener('play-restricted-video', (e) => {
                    let v = document.querySelector('[data-video="' + e.detail.index + '"]');
                    if (v) {
                        v.dataset.acknowledged = 'true';
                        this.tryPlay(v);
                    }
                });

                window.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowDown') { e.preventDefault(); this.next(); }
                    if (e.key === 'ArrowUp')   { e.preventDefault(); this.prev(); }
                });

                // ═══ CLEANUP: Stop audio/video when user leaves page ═══
                // Handles: browser back, tab switch, app switch (mobile), navigation
                this._onVisibilityChange = () => {
                    if (document.hidden) {
                        this.stopAllVideos();
                    } else {
                        // Resume current video when user comes back to tab
                        // But only if user hadn't manually paused it
                        if (!this.isPausedByUser) {
                            let v = document.querySelector('[data-video="' + this.currentIndex + '"]');
                            if (v && v.style.opacity === '1') {
                                v.muted = !this.isUnmuted; // Restore mute state
                                if (v.hls) v.hls.startLoad(); // Resume HLS buffer
                                this.tryPlay(v);
                            }
                        }
                    }
                };
                document.addEventListener('visibilitychange', this._onVisibilityChange);

                this._onPageHide = () => {
                    this.stopAllVideos();
                };
                window.addEventListener('pagehide', this._onPageHide);
                window.addEventListener('beforeunload', this._onPageHide);
            },

            // Alpine lifecycle: cleanup when component is removed from DOM (supports both v2/v3)
            destroy() {
                this.stopAllVideos();
                if (this._onVisibilityChange) {
                    document.removeEventListener('visibilitychange', this._onVisibilityChange);
                }
                if (this._onPageHide) {
                    window.removeEventListener('pagehide', this._onPageHide);
                    window.removeEventListener('beforeunload', this._onPageHide);
                }
            },
            destroyed() {
                this.stopAllVideos();
                if (this._onVisibilityChange) {
                    document.removeEventListener('visibilitychange', this._onVisibilityChange);
                }
                if (this._onPageHide) {
                    window.removeEventListener('pagehide', this._onPageHide);
                    window.removeEventListener('beforeunload', this._onPageHide);
                }
            },

            // Stop ALL videos (pause + stop HLS loading)
            stopAllVideos() {
                document.querySelectorAll('video[data-video]').forEach(v => {
                    try {
                        v.pause();
                        if (v.hls) {
                            v.hls.stopLoad();
                        }
                    } catch(e) {}
                });
            },

            startAutoPolling() {
                setInterval(() => {
                    const currentSlide = document.querySelector('[data-video="' + this.currentIndex + '"]')?.closest('.w-full.relative.flex');
                    if (currentSlide && currentSlide.querySelector('[data-processing="true"]')) {
                        const currentReelSlug = this.reelsSlugs[this.currentIndex];
                        if (currentReelSlug) {
                            fetch(`${this.baseUrl}/reels/${currentReelSlug}/status`)
                            .then(r => r.json())
                            .then(d => {
                                if (d.bunny_status === 'ready') {
                                    location.reload();
                                } else {
                                    this.processingProgress[currentReelSlug] = d.encode_progress || 0;
                                }
                            });
                        }
                    }
                }, 10000); 
            },

            next() {
                if (this.isAnimating) return;
                if (this.currentIndex >= this.totalReels - 1) return;
                if (this.activeCommentReel || this.activeShareReel || this.activeAudioReel || this.activeOptionsReel) return;

                const prevIndex = this.currentIndex;
                this.deactivateSlide(prevIndex);
                this.isAnimating = true;
                this.currentIndex++;

                setTimeout(() => {
                    this.isAnimating = false;
                    this.activateSlide(this.currentIndex);
                    this.purgeFarSlides();
                }, 250);
            },

            prev() {
                if (this.isAnimating) return;
                if (this.currentIndex <= 0) return;
                if (this.activeCommentReel || this.activeShareReel || this.activeAudioReel || this.activeOptionsReel) return;

                const prevIndex = this.currentIndex;
                this.deactivateSlide(prevIndex);
                this.isAnimating = true;
                this.currentIndex--;

                setTimeout(() => {
                    this.isAnimating = false;
                    this.activateSlide(this.currentIndex);
                    this.purgeFarSlides();
                }, 250);
            },

            purgeFarSlides() {
                for (let idx = 0; idx < this.totalReels - 1; idx++) {
                    if (Math.abs(idx - this.currentIndex) > 2) {
                        let v = document.querySelector('[data-video="' + idx + '"]');
                        if (v) {
                            v.pause();
                            if (v.hls) {
                                v.hls.destroy();
                                v.hls = null;
                            }
                            delete v.dataset.loadedSrc;
                            v.removeAttribute('src');
                            v.load();
                        }
                    }
                }
            },

            videoEnded() {
                let v = document.querySelector('[data-video="' + this.currentIndex + '"]');
                if (v) {
                    v.currentTime = 0;
                    this.tryPlay(v);
                }
            },

            // Reveal video + hide poster with smooth fade-in transition
            revealVideo(i) {
                const posterWrap = document.getElementById('reel-poster-wrap-' + i);
                const loader = document.getElementById('reel-loader-' + i);
                const v = document.querySelector('[data-video="' + i + '"]');
                if (loader) loader.style.display = 'none';
                if (v) {
                    v.style.transition = 'opacity 300ms ease-in';
                    v.style.opacity = '1';
                }
                if (posterWrap) {
                    posterWrap.style.opacity = '0';
                    posterWrap.style.pointerEvents = 'none';
                }
            },

            // Reset poster to visible for a slide (when arriving)
            resetPoster(i) {
                const posterWrap = document.getElementById('reel-poster-wrap-' + i);
                const loader = document.getElementById('reel-loader-' + i);
                const v = document.querySelector('[data-video="' + i + '"]');
                if (posterWrap) {
                    posterWrap.style.transition = 'none';
                    posterWrap.style.opacity = '1';
                    posterWrap.offsetHeight;
                    posterWrap.style.transition = 'opacity 300ms ease-out';
                }
                if (loader) loader.style.display = 'flex';
                if (v) v.style.opacity = '0';
            },

            activateSlide(i) {
                this.isPausedByUser = false;
                this.slideStartTimes[i] = Date.now();
                let v = document.querySelector('[data-video="' + i + '"]');
                if (v) {
                    this.progressInstant = true;
                    this.progress = 0;
                    clearTimeout(this.progressSnapTimer);
                    this.progressSnapTimer = setTimeout(() => { this.progressInstant = false; }, 200);
                    v.muted = !this.isUnmuted;

                    // If video is ready, reveal immediately; otherwise set poster until ready
                    if (v.readyState >= 2 || v.currentTime > 0) {
                        this.revealVideo(i);
                    } else {
                        this.resetPoster(i);
                    }
                    
                    const src = v.getAttribute('data-src');
                    const type = v.getAttribute('data-type');

                    if (!src || src.endsWith('/assets/reels/')) {
                        return;
                    }

                    // Attach reveal listeners so video is revealed as soon as frames are ready or playing starts
                    // Prevent memory leak by only binding once
                    if (v.dataset.revealBound !== 'true') {
                        const onReady = () => this.revealVideo(i);
                        v.addEventListener('canplay', onReady);
                        v.addEventListener('playing', onReady);
                        v.addEventListener('loadeddata', onReady);
                        v.addEventListener('timeupdate', onReady);
                        v.addEventListener('play', onReady);

                        const loader = document.getElementById('reel-loader-' + i);
                        const slowText = document.getElementById('reel-loader-text-' + i);
                        // True stall check: ignore signals when paused, ended,
                        // finished, or not the active slide (e.g. preload slides).
                        const isActuallyStuck = () => {
                            if (!v || v.ended || v.paused) return false;
                            if (this.currentIndex !== i) return false;
                            try {
                                if (isFinite(v.duration) && v.duration > 0 && v.currentTime >= v.duration - 0.15) return false;
                            } catch (e) { return false; }
                            return v.readyState < 3;
                        };
                        const showStallLoader = () => {
                            if (loader) loader.style.display = 'flex';
                            // "Slow connection…" label only if the stall persists.
                            clearTimeout(v._slowTextTimer);
                            v._slowTextTimer = setTimeout(() => {
                                if (slowText && isActuallyStuck()) slowText.style.display = 'block';
                            }, 3000);
                            // Auto-recovery nudge if the stall persists.
                            clearTimeout(v._stallTimer);
                            v._stallTimer = setTimeout(() => {
                                if (!isActuallyStuck()) return;
                                try {
                                    if (v.hls) { v.hls.startLoad(); }
                                    else { v.currentTime = v.currentTime + 0.1; }
                                } catch (e) {}
                            }, 5000);
                        };
                        const hideStallLoader = () => {
                            if (loader) loader.style.display = 'none';
                            if (slowText) slowText.style.display = 'none';
                            clearTimeout(v._slowTextTimer);
                            clearTimeout(v._stallTimer);
                        };
                        v.addEventListener('waiting', showStallLoader);
                        v.addEventListener('stalled', () => { if (isActuallyStuck()) showStallLoader(); });
                        v.addEventListener('suspend', () => { if (isActuallyStuck()) showStallLoader(); });
                        v.addEventListener('emptied', () => { if (isActuallyStuck()) showStallLoader(); });
                        v.addEventListener('playing', () => {
                            hideStallLoader();
                        });
                        v.addEventListener('canplay', () => {
                            if (loader) loader.style.display = 'none';
                        });

                        v.dataset.revealBound = 'true';
                    }

                    const isRestricted = v.getAttribute('data-restricted') === 'true';
                    const shouldPlay = !isRestricted || v.dataset.acknowledged === 'true';

                    if (type === 'hls' && typeof Hls !== 'undefined' && Hls.isSupported() && src.endsWith('.m3u8')) {
                        if (!v.hls) {
                            v.setAttribute('fetchpriority', 'high');
                            const hls = new Hls({
                                capLevelToPlayerSize: true,
                                autoStartLoad: true,
                                // Aggressive ABR for 1-5 Mbps connections
                                abrEwmaFastLive: 3.0,        // react faster to bandwidth drops
                                abrEwmaSlowLive: 9.0,        // slower recovery = stay low longer
                                abrBandWidthFactor: 0.8,     // use 80% of measured bandwidth
                                abrBandWidthUpFactor: 0.7,   // be conservative going up
                                maxBufferLength: 15,         // smaller buffer = faster switches
                                maxMaxBufferLength: 30,
                                maxBufferSize: 15 * 1024 * 1024,  // 15 MB cap
                                enableWorker: true,          // offload parsing
                                lowLatencyMode: true         // reduce latency for live-like
                            });
                            hls.loadSource(src);
                            hls.attachMedia(v);
                            v.hls = hls;
                            v.hlsReady = false;
                            hls.on(Hls.Events.MANIFEST_PARSED, () => {
                                v.hlsReady = true;
                                if (this.currentIndex === i && shouldPlay) {
                                    this.tryPlay(v);
                                }
                            });
                            // HLS-specific buffer stall detection
                            hls.on(Hls.Events.FRAG_LOAD_EMERGENCY_ABORTED, () => {
                                if (isActuallyStuck()) showStallLoader();
                            });
                            hls.on(Hls.Events.BUFFER_STALLED_ERROR, () => {
                                if (isActuallyStuck()) showStallLoader();
                            });
                            hls.on(Hls.Events.ERROR, (event, data) => {
                                if (data.fatal) {
                                    switch (data.type) {
                                        case Hls.ErrorTypes.NETWORK_ERROR:
                                            console.warn('[Reel] HLS network error, recovering...', data.details);
                                            hls.startLoad();
                                            break;
                                        case Hls.ErrorTypes.MEDIA_ERROR:
                                            console.warn('[Reel] HLS media error, recovering...', data.details);
                                            hls.recoverMediaError();
                                            break;
                                        default:
                                            console.warn('[Reel] HLS fatal error:', data.type, data.details);
                                            hls.destroy();
                                            v.hls = null;
                                            v.hlsReady = false;
                                            const loader = document.getElementById('reel-loader-' + i);
                                            if (loader) loader.style.display = 'none';
                                            break;
                                    }
                                }
                            });
                        } else {
                            if (v.hls) {
                                v.hls.startLoad();
                            }
                            if (shouldPlay) {
                                this.tryPlay(v);
                            }
                        }
                    } else {
                        if (v.dataset.loadedSrc !== src) {
                            v.setAttribute('fetchpriority', 'high');
                            v.preload = 'auto';
                            v.src = src;
                            v.dataset.loadedSrc = src;
                            v.load();
                        }
                        if (shouldPlay) {
                            this.tryPlay(v);
                        }
                    }

                    // Record view after 2 seconds of continuous watching
                    if (this.viewTimer) clearTimeout(this.viewTimer);
                    this.viewTimer = setTimeout(() => {
                        this.recordView(this.reelsSlugs[i]);
                    }, 2000);
                }

                // Smart preload: buffer next/prev slides
                this.$nextTick(() => {
                    this.preloadSlide(i + 1);
                    this.preloadSlide(i + 2);
                    this.preloadSlide(i - 1);
                });
            },

            recordView(slug) {
                fetch(`${this.baseUrl}/reels/${slug}/view`, {
                    method: 'POST',
                    keepalive: true,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success && this.reelStates[slug]) {
                        this.reelStates[slug].viewsCount = d.views_count;
                    }
                })
                .catch(err => console.error("Error recording view", err));
            },

            tryPlay(v) {
                let playPromise = v.play();
                if (playPromise !== undefined) {
                    playPromise.catch(error => {
                        v.muted = true;
                        this.isUnmuted = false;
                        let mutedPlayPromise = v.play();
                        if (mutedPlayPromise !== undefined) {
                            mutedPlayPromise.catch(() => {
                                this.showPlayState = true;
                                this.lastState = 'pause';
                            });
                        }
                    });
                }
            },

            deactivateSlide(i) {
                // Log dwell time (scroll stop tracking)
                if (this.slideStartTimes[i]) {
                    let dwell = Math.round((Date.now() - this.slideStartTimes[i]) / 1000);
                    if (dwell >= 2) {
                        let slug = this.reelsSlugs[i];
                        if (slug) {
                            fetch(`${this.baseUrl}/reels/${slug}/dwell`, {
                                method: 'POST',
                                keepalive: true,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ dwell_seconds: dwell })
                            }).catch(() => {});
                        }
                    }
                    delete this.slideStartTimes[i];
                }
                let v = document.querySelector('[data-video="' + i + '"]');
                if (v) {
                    v.pause();
                    if (v.hls) {
                        v.hls.stopLoad();
                    }
                }
            },

            handleTap(el) {
                if (!this.isUnmuted) {
                    this.isUnmuted = true;
                    el.muted = false;
                    this.isPausedByUser = false;
                    this.showFlash('play');
                    // Ensure the video plays if it was stuck/paused
                    if (el.paused) {
                        el.play();
                    }
                } else {
                    if (el.paused) {
                        el.play();
                        this.isPausedByUser = false;
                        this.showFlash('play');
                    } else {
                        el.pause();
                        this.isPausedByUser = true;
                        this.showFlash('pause');
                    }
                }
            },

            updateProgress(el) {
                if (this.isDraggingProgress || el.seeking || !el.duration) return;
                
                // Only update progress if this is the currently active slide
                if (el.getAttribute('data-video') != this.currentIndex) return;
                
                // Do not move progress if the video is still hidden behind the blurred poster
                if (el.style.opacity !== '1') return;

                let newProgress = (el.currentTime / el.duration) * 100;
                
                // Fix HLS jitter: adaptive streaming can cause currentTime to jump backward slightly.
                // Ignore small backward drops (< 2%) to keep the progress bar smooth.
                if (newProgress < this.progress && (this.progress - newProgress) < 2) {
                    return;
                }
                
                this.progress = newProgress;
            },

            seek(e, el, isFinal = false) {
                let v = document.querySelector('[data-video="' + this.currentIndex + '"]');
                if (!v || !v.duration) return;
                
                let rect = el.getBoundingClientRect();
                let clientX = e.clientX || (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
                if (e.changedTouches && e.changedTouches[0]) {
                    clientX = e.changedTouches[0].clientX; // Fix for touchend where touches is empty
                }
                
                let x = clientX - rect.left;
                let percentage = Math.max(0, Math.min(1, x / rect.width));
                
                // Only update the visual progress while dragging
                this.progress = percentage * 100;
                
                // Only seek the actual video when the user releases the mouse/touch
                if (isFinal) {
                    v.currentTime = percentage * v.duration;
                }
            },

            startSeeking(e, el) {
                this.isDraggingProgress = true;
                this.seek(e, el, false); // Initial visual update
                
                const moveHandler = (moveEvent) => {
                    if (this.isDraggingProgress) {
                        this.seek(moveEvent, el, false); // Visual update only
                    }
                };
                
                const stopHandler = (stopEvent) => {
                    this.seek(stopEvent || e, el, true); // Final seek to the video

                    // Resume timeupdate-driven updates ONLY after the video finishes
                    // seeking, so stale currentTime values can never yank the bar back.
                    const v = document.querySelector('[data-video="' + this.currentIndex + '"]');
                    const finish = () => {
                        this.isDraggingProgress = false;
                    };
                    if (!v || !v.seeking) {
                        finish();
                    } else {
                        v.addEventListener('seeked', finish, { once: true });
                        setTimeout(() => {
                            v.removeEventListener('seeked', finish);
                            finish();
                        }, 1000);
                    }

                    document.removeEventListener('mousemove', moveHandler);
                    document.removeEventListener('mouseup', stopHandler);
                    document.removeEventListener('touchmove', moveHandler);
                    document.removeEventListener('touchend', stopHandler);
                    document.removeEventListener('touchcancel', stopHandler);
                };

                document.addEventListener('mousemove', moveHandler);
                document.addEventListener('mouseup', stopHandler);
                document.addEventListener('touchmove', moveHandler, { passive: false });
                document.addEventListener('touchend', stopHandler);
                document.addEventListener('touchcancel', stopHandler);
            },

            showFlash(state) {
                this.lastState = state;
                this.showPlayState = true;
                if (this.playStateTimeout) clearTimeout(this.playStateTimeout);
                this.playStateTimeout = setTimeout(() => {
                    this.showPlayState = false;
                }, 800);
            },

            onTouchStart(e) {
                if (this.isAnimating || this.activeCommentReel || this.activeShareReel || this.activeAudioReel || this.activeOptionsReel) return;
                this.touchStartY = e.touches[0].clientY;
                this.touchDeltaY = 0;
                this.isTouching = true;
            },

            onTouchMove(e) {
                if (!this.isTouching) return;
                let delta = e.touches[0].clientY - this.touchStartY;
                if (this.currentIndex === 0 && delta > 0) delta = delta * 0.3;
                if (this.currentIndex === this.totalReels - 1 && delta < 0) delta = delta * 0.3;
                this.touchDeltaY = delta;
            },

            onTouchEnd() {
                if (!this.isTouching) return;
                this.isTouching = false;
                let h = this.$el.clientHeight || window.innerHeight;
                let threshold = h * 0.15;
                if (this.touchDeltaY < -threshold) {
                    this.next();
                } else if (this.touchDeltaY > threshold) {
                    this.prev();
                }
                this.touchDeltaY = 0;
            },

            onWheel(e) {
                if (this.wheelLock || this.isAnimating) return;
                if (this.activeCommentReel || this.activeShareReel || this.activeAudioReel || this.activeOptionsReel) return;
                this.wheelLock = true;
                setTimeout(() => this.wheelLock = false, 700);
                if (e.deltaY > 30) this.next();
                else if (e.deltaY < -30) this.prev();
            },

            openOptions(reelId) {
                this.activeOptionsReel = reelId;
            },

            watchLater(reelSlug) {
                @guest
                    window.showLoginAlert('save this reel for later');
                    return;
                @endguest
                fetch(`${this.baseUrl}/reels/${reelSlug}/watch-later`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(d => {
                    this.activeOptionsReel = null;
                    if (d.status === 'added') {
                        this.reelStates[reelSlug].isSaved = true;
                    } else {
                        this.reelStates[reelSlug].isSaved = false;
                    }
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: d.message || 'Saved to Watch Later',
                        background: '#1A1A1A',
                        color: '#ffffff'
                    });
                });
            },

            saveToPlaylist(reelSlug) {
                @guest
                    window.showLoginAlert('save this reel to a playlist');
                    return;
                @endguest
                const reel = this.reelStates[reelSlug];
                if (!reel) return;
                this.activeOptionsReel = null;
                window.openVideoOptions(
                    reel.id,
                    reel.title,
                    'reel',
                    reel.isLiked,
                    reel.isSaved || false,
                    reel.isInAnyPlaylist || false,
                    reel.playlistIds || [],
                    reel.user_id
                );
            },

            notInterested(reelSlug) {
                if (confirm('Hide this reel?')) {
                    this.activeOptionsReel = null;
                    
                    fetch(`${this.baseUrl}/reels/${reelSlug}/not-interested`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            window.showToast(data.message, 'success');
                        }
                    }).catch(err => console.error(err));

                    this.next(); 
                }
            },

            openComments(reelId) {
                @guest
                    window.showLoginAlert('view comments');
                    return;
                @endguest
                this.activeCommentReel = reelId;
                this.loadingComments = true;
                this.comments = [];
                this.replyToId = null;
                this.replyToUser = '';
                fetch(`${this.baseUrl}/reels/` + reelId + '/comments')
                    .then(r => r.json())
                    .then(d => { this.comments = d; this.loadingComments = false; });
            },

            addComment(reelId, commentText) {
                if (!commentText.trim()) return;
                
                let formData = new FormData();
                formData.append('comment', commentText);
                formData.append('_token', '{{ csrf_token() }}');
                if (this.replyToId) {
                    formData.append('parent_id', this.replyToId);
                }

                @guest
                    window.showLoginAlert('post a comment');
                    return;
                @endguest

                this.postingComment = true;
                let url = `/reels/${reelId}/comment`;

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async r => {
                    const d = await r.json();
                    if (!r.ok) {
                        throw new Error(d.message || d.error || 'Something went wrong');
                    }
                    return d;
                })
                .then(d => {
                    this.postingComment = false;
                    console.log("Response from addComment API:", d);
                    if (d.comment) {
                        if (d.comment.parent_id) {
                            console.log("This is a reply to parent_id:", d.comment.parent_id);
                            let parent = this.comments.find(c => c.id == d.comment.parent_id);
                            console.log("Found parent:", parent);
                            if (parent) {
                                if (!parent.replies) parent.replies = [];
                                // Use array assignment to guarantee Alpine reactivity triggers
                                parent.replies = [d.comment, ...parent.replies];
                                parent.showReplies = true;
                                console.log("Parent replies array after update:", parent.replies);
                            } else {
                                console.log("Parent not found in this.comments! Adding to top level.");
                                this.comments.unshift(d.comment);
                            }
                        } else {
                            console.log("This is a top level comment");
                            this.comments.unshift(d.comment);
                        }
                        this.$refs.commentInput.value = '';
                        this.$refs.commentInput.style.height = 'auto';
                        this.replyToId = null;
                        this.replyToUser = '';
                        
                        if (this.activeCommentReel && this.reelStates[this.activeCommentReel]) {
                            this.reelStates[this.activeCommentReel].commentsCount++;
                        }
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            icon: 'error',
                            title: d.error || 'Error adding comment',
                            background: '#1A1A1A',
                            color: '#ffffff'
                        });
                    }
                })
                .catch(err => {
                    this.postingComment = false;
                    const msg = err.message || 'Connection error';
                    if (msg.toLowerCase().includes('blocked')) {
                        const isDark = document.documentElement.classList.contains('dark');
                        this.$refs.commentInput.value = '';
                        this.$refs.commentInput.style.height = 'auto';
                        this.replyToId = null;
                        this.replyToUser = '';
                        Swal.fire({
                            width: '360px',
                            html: `
                                <div class="flex flex-col items-center text-center p-2">
                                    <div class="w-16 h-16 rounded-2xl bg-rose-50 dark:bg-rose-500/10 text-rose-500 flex items-center justify-center mb-5 shadow-inner">
                                        <span class="material-symbols-rounded text-4xl">block</span>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 tracking-tight">Comment Blocked</h3>
                                    <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.2em] mb-6">Community Guidelines</p>
                                    
                                    <div class="w-full p-5 bg-slate-50 dark:bg-white/[0.03] rounded-2xl border border-slate-100 dark:border-white/5 mb-2">
                                        <p class="text-xs font-bold text-slate-600 dark:text-white/80 leading-relaxed">
                                            ${msg}
                                        </p>
                                    </div>
                                </div>
                            `,
                            confirmButtonText: 'I Understand',
                            confirmButtonColor: isDark ? '#FFFFFF' : '#0F172A',
                            background: isDark ? '#1A1A1A' : '#FFFFFF',
                            color: isDark ? '#ffffff' : '#0F172A',
                            customClass: {
                                popup: 'rounded-[2.5rem] border border-slate-100 dark:border-white/5 shadow-2xl p-6',
                                confirmButton: isDark
                                    ? 'rounded-xl px-10 py-3.5 text-[11px] font-black uppercase tracking-[0.2em] w-full shadow-lg shadow-slate-900/20 !bg-white !text-slate-900'
                                    : 'rounded-xl px-10 py-3.5 text-[11px] font-black uppercase tracking-[0.2em] w-full shadow-lg shadow-slate-900/20'
                            }
                        });
                    } else {
                        const isDark = document.documentElement.classList.contains('dark');
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            icon: 'error',
                            title: msg,
                            background: isDark ? '#1A1A1A' : '#FFFFFF',
                            color: isDark ? '#ffffff' : '#0F172A'
                        });
                    }
                });
            },

            replyTo(commentId, username) {
                this.replyToId = commentId;
                this.$refs.commentInput.value = `@${username} `;
                this.$refs.commentInput.focus();
            },

            async deleteComment(commentId) {
                const result = await Swal.fire({
                    title: 'Delete Comment?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff571a',
                    cancelButtonColor: '#272727',
                    confirmButtonText: 'Yes, delete it!',
                    background: '#1A1A1A',
                    color: '#ffffff'
                });

                if (result.isConfirmed) {
                    fetch(`${this.baseUrl}/reels/comments/${commentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            this.comments = this.comments.filter(c => c.id !== commentId);
                            const reel = this.reelsSlugs[this.currentIndex];
                            if (reel && this.reelStates[reel]) {
                                this.reelStates[reel].commentsCount = d.comments_count;
                            }
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'success',
                                title: 'Comment deleted',
                                background: '#1A1A1A',
                                color: '#ffffff'
                            });
                        }
                    });
                }
            },

            toggleLike(reelSlug) {
                @guest
                    window.showLoginAlert('like this reel');
                    return;
                @endguest
                fetch(`${this.baseUrl}/reels/${reelSlug}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(d => {
                    if (this.reelStates[reelSlug]) {
                        this.reelStates[reelSlug].isLiked = d.liked;
                        this.reelStates[reelSlug].likesCount = d.likes_count;
                    }
                });
            },

            toggleSubscribe(channelId) {
                if (!channelId) return;
                @auth
                    let currentVal = this.channelSubscriptions[channelId];
                    const newVal = !currentVal;
                    this.channelSubscriptions[channelId] = newVal;
                    
                    // Synchronize with all reel states that belong to this channel
                    Object.keys(this.reelStates).forEach(slug => {
                        if (this.reelStates[slug].channelId == channelId) {
                            this.reelStates[slug].isSubscribed = newVal;
                        }
                    });

                    fetch(`${this.baseUrl}/channel/${channelId}/subscribe`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(d => {
                        this.channelSubscriptions[channelId] = d.subscribed;
                        // Ensure sync with server response
                        Object.keys(this.reelStates).forEach(slug => {
                            if (this.reelStates[slug].channelId == channelId) {
                                this.reelStates[slug].isSubscribed = d.subscribed;
                            }
                        });
                    })
                    .catch((err) => {
                        this.channelSubscriptions[channelId] = currentVal;
                        Object.keys(this.reelStates).forEach(slug => {
                            if (this.reelStates[slug].channelId == channelId) {
                                this.reelStates[slug].isSubscribed = currentVal;
                            }
                        });
                    });
                @else
                    window.showLoginAlert('subscribe to this channel');
                @endauth
            },

            shareTo(platform) {
                const url = window.location.origin + '/reels/' + this.activeShareReel;
                const text = "Check out this reel!";
                let shareUrl = '';

                switch(platform) {
                    case 'whatsapp':
                        shareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(text + ' ' + url)}`;
                        break;
                    case 'messenger':
                        shareUrl = `fb-messenger://share/?link=${encodeURIComponent(url)}`;
                        if (!/Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                            shareUrl = `https://www.facebook.com/dialog/send?link=${encodeURIComponent(url)}&app_id=123456789&redirect_uri=${encodeURIComponent(url)}`;
                        }
                        break;
                    case 'facebook':
                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                        break;
                    case 'twitter':
                        shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
                        break;
                    case 'instagram':
                        navigator.clipboard.writeText(url);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Link copied for Instagram!',
                            background: '#1A1A1A',
                            color: '#ffffff',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return;
                    case 'snapchat':
                        shareUrl = `https://www.snapchat.com/scan?attachmentUrl=${encodeURIComponent(url)}`;
                        break;
                }

                if (shareUrl) {
                    window.open(shareUrl, '_blank');
                }
            },

            generateQRCode(slug) {
                const url = `${this.baseUrl}/reels/${slug}`;
                const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(url)}`;
                
                Swal.fire({
                    title: '<span class="text-xs font-black uppercase tracking-widest">Reel QR Code</span>',
                    html: `
                        <div class="p-6 bg-white rounded-[2.5rem] border-4 border-gray-50 shadow-inner">
                            <img src="${qrUrl}" class="w-full aspect-square rounded-2xl shadow-sm" alt="QR Code">
                        </div>
                        <p class="mt-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Scan to watch on mobile</p>
                    `,
                    showConfirmButton: false,
                    showCloseButton: true,
                    background: '#1A1A1A',
                    color: '#ffffff',
                    customClass: {
                        popup: 'rounded-[3rem] border border-white/5 p-8',
                        closeButton: 'rounded-full bg-white/5 text-white border-none'
                    }
                });
            },

            openReport(slug) {
                @guest
                    window.showLoginAlert('report this reel');
                    return;
                @endguest
                this.activeOptionsReel = null;
                window.dispatchEvent(new CustomEvent('open-report', {
                    detail: { id: slug, type: 'reel' }
                }));
            },

            async submitReport() {
                if (!this.reportReason.trim()) return;
                this.submittingReport = true;
                const slug = this.activeReportReel;
                
                try {
                    const response = await fetch(`${this.baseUrl}/reels/${slug}/report`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ reason: this.reportReason })
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        this.activeReportReel = null;
                        this.reportReason = '';
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Report Submitted',
                            background: '#1A1A1A',
                            color: '#ffffff',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        throw new Error(data.message || 'Failed to submit report');
                    }
                } catch (e) {
                    Swal.fire('Error', e.message || 'Something went wrong', 'error');
                } finally {
                    this.submittingReport = false;
                }
            },
            refreshReelStatus(reelSlug, silent = false) {
                fetch(`${this.baseUrl}/reels/${reelSlug}/status`)
                .then(r => r.json())
                .then(d => {
                    if (d.bunny_status === 'ready') {
                        if (!silent) {
                            location.reload(); 
                        }
                    } else if (!silent) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            icon: 'info',
                            title: 'Still encoding on Bunny CDN...',
                            background: '#1A1A1A',
                            color: '#ffffff'
                        });
                    }
                });
            },
            createReel() {
                @auth
                    window.location.href = "{{ route('reels.create') }}";
                @else
                    Swal.fire({
                        title: 'Ready to create?',
                        text: "Login to start sharing your amazing reels with the world!",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#ff571a',
                        cancelButtonColor: '#272727',
                        confirmButtonText: 'Login Now',
                        background: '#0F0F0F',
                        color: '#ffffff'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('login') }}";
                        }
                    });
                @endauth
            },
            formatNumber(num) {
                if (num > 999999) return (num / 1000000).toFixed(1) + 'M';
                if (num > 999) return (num / 1000).toFixed(1) + 'K';
                return num;
            },
            getInitials(name) {
                if (!name) return '??';
                let words = name.trim().split(' ');
                if (words.length >= 2) {
                    return (words[0][0] + words[words.length - 1][0]).toUpperCase();
                }
                return name.substring(0, 2).toUpperCase();
            },
            formatComment(text) {
                if (!text) return '';
                // Escape HTML to prevent XSS
                let div = document.createElement('div');
                div.textContent = text;
                let escaped = div.innerHTML;
                
                // Replace @mentions with links
                // Regex: @ followed by alphanumeric/underscore
                return escaped.replace(/@(\w+)/g, '<a href="/@$1" class="text-orange-500 font-bold hover:underline">@$1</a>');
            },
            isInBufferZone(index) {
                // OPTIMIZED: DOM buffer 2 for thumbnails/posters (no icons on fast swipe), video bytes still only current+1 via preloadSlide/purgeFarSlides
                return Math.abs(index - this.currentIndex) <= 2;
            },
            preloadSlide(i) {
                if (i < 0 || i >= this.totalReels - 1) return;
                let v = document.querySelector('[data-video="' + i + '"]');
                if (!v) return;
                const src = v.getAttribute('data-src');
                const type = v.getAttribute('data-type');
                if (!src || src.endsWith('/assets/reels/')) return;

                // Network-aware preload: buffer more on fast connections, less on slow
                const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
                const isSlow = conn && (conn.effectiveType === '2g' || conn.effectiveType === 'slow-2g' || conn.saveData);
                const preloadLevel = isSlow ? 'metadata' : 'auto';

                if (type === 'hls' && typeof Hls !== 'undefined' && Hls.isSupported() && src.endsWith('.m3u8')) {
                    if (v.hls) return; // Already preloaded
                    const hls = new Hls({ 
                        capLevelToPlayerSize: true, 
                        autoStartLoad: !isSlow,
                        abrEwmaFastLive: 3.0,
                        abrEwmaSlowLive: 9.0,
                        abrBandWidthFactor: 0.8,
                        abrBandWidthUpFactor: 0.7,
                        maxBufferLength: 15,
                        maxMaxBufferLength: 30,
                        maxBufferSize: 15 * 1024 * 1024,
                        enableWorker: true,
                        lowLatencyMode: true
                    });
                    hls.loadSource(src);
                    hls.attachMedia(v);
                    v.hls = hls;
                    v.hlsReady = false;
                    hls.on(Hls.Events.MANIFEST_PARSED, () => {
                        v.hlsReady = true;
                        if (this.currentIndex === i) {
                            const isRestricted = v.getAttribute('data-restricted') === 'true';
                            if (!isRestricted || v.dataset.acknowledged === 'true') {
                                this.tryPlay(v);
                            }
                        }
                    });
                    hls.on(Hls.Events.ERROR, (event, data) => {
                        if (data.fatal) {
                            console.warn('[Reel] HLS preload error for slide ' + i + ':', data.type, data.details);
                            hls.destroy();
                            v.hls = null;
                            v.hlsReady = false;
                        }
                    });
                    hls.on(Hls.Events.MANIFEST_LOADED, () => {
                        if (!isSlow) {
                            hls.startLoad();
                        }
                    });
                } else if (!v.dataset.loadedSrc) {
                    v.preload = preloadLevel;
                    v.setAttribute('fetchpriority', 'low');
                    v.src = src;
                    v.dataset.loadedSrc = src;
                    v.load();
                }
            }
        }))
    })
</script>

<script>
    function goBack() {
        if (document.referrer && document.referrer.includes(window.location.host)) {
            window.location.href = document.referrer;
        } else if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = "{{ route('home') }}";
        }
    }
</script>
@endsection

