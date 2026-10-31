<div class="premium-card group bg-transparent transition-all duration-300" data-video-id="<?php echo e($video->id); ?>" 
     <?php
        $isOptimizing = ($video->isBunnyVideo() && !in_array($video->bunny_status, ['ready', 'error', 'failed'])) || (!$video->isBunnyVideo() && $video->status === 'processing');
        $hasAccess = true;
        if ($video->is_premium) {
            $hasAccess = false;
            if (auth()->check()) {
                if (auth()->id() == $video->user_id || auth()->user()->isPurchased($video->id) || auth()->user()->hasPremiumAccess()) {
                    $hasAccess = true;
                }
            }
        }
     ?>
      x-data="{
         hover: false,
         hoverTimer: null,
         muted: true,
         touchTimer: null,
         touchStartX: 0,
         touchStartY: 0,
         touchPreview: false,
         suppressClick: false,
         unmuteRetry: null,
        progress: 0,
        currentTime: 0,
        durationSeconds: 0,
        isOptimizing: <?php echo e($isOptimizing ? 'true' : 'false'); ?>,
        hasAccess: <?php echo e($hasAccess ? 'true' : 'false'); ?>,
        isAgeRestricted: <?php echo e($video->is_age_restricted ? 'true' : 'false'); ?>,
        isRestricted: <?php echo e($video->isRestricted() ? 'true' : 'false'); ?>,
        isScheduled: <?php echo e(($video->scheduled_at && $video->scheduled_at->isFuture()) ? 'true' : 'false'); ?>,
        acknowledged: false,
        init() {
            window.addEventListener('preview-stop-others', (ev) => {
                if (ev.detail !== this.$el && (this.hover || this.touchPreview)) {
                    this.stopVideo();
                    this.touchPreview = false;
                }
            });
            window.addEventListener('scroll', () => {
                if (this.hover && !this.touchPreview) {
                    this.onMouseLeave();
                }
            }, { passive: true });
            window.addEventListener('scroll-stopped', () => {
                if (!this.hover && !this.touchPreview && this.$el && this.$el.matches && this.$el.matches(':hover')) {
                    this.onMouseEnter();
                }
            });
            document.addEventListener('touchstart', (ev) => {
                if (this.touchPreview && this.$el && !this.$el.contains(ev.target)) {
                    this.stopVideo();
                    this.touchPreview = false;
                }
            }, { passive: true });
            if (this.isOptimizing) {
                this.pollStatus();
            }
            let orig = '<?php echo e($video->duration ?? ''); ?>';
            if (!orig || orig === '00:00' || orig === '0:00' || orig === '--:--') {
                this.$nextTick(() => {
                    const v = this.$el.querySelector('video');
                    if (!v) return;
                    const setDuration = () => {
                        if (v.duration && isFinite(v.duration) && v.duration > 0) {
                            this.durationSeconds = v.duration;
                        }
                    };
                    v.addEventListener('loadedmetadata', setDuration, { once: true });
                    v.addEventListener('durationchange', setDuration, { once: true });
                    try { v.load(); } catch(e) {}
                    if (v.readyState >= 1 && v.duration) setDuration();
                });
            }
        },
        syncMute(v) {
            if (!v) return;
            console.log('[syncMute] Setting v.muted =', this.muted);
            v.muted = this.muted;
            try { v.volume = this.muted ? 0 : 1; } catch(e) {}
            try { v.defaultMuted = this.muted; } catch(e) {}
            try {
                if (this.muted) v.setAttribute('muted', '');
                else v.removeAttribute('muted');
            } catch(e) {}
            console.log('[syncMute] Done syncing. v.muted is now', v.muted, 'v.volume is', v.volume);
        },
        attachHls(v, hlsUrl) {
            if (!v || !hlsUrl || v.dataset.hlsReady || v.dataset.hlsLoading) return false;
            if (v.canPlayType && v.canPlayType('application/vnd.apple.mpegurl')) {
                v.src = hlsUrl;
                v.dataset.hlsReady = '1';
                try { v.load(); } catch(e) {}
                return true;
            }
            if (!window.ensurePreviewHls) return false;
            v.dataset.hlsLoading = '1';
            window.ensurePreviewHls().then((ok) => {
                delete v.dataset.hlsLoading;
                if (!this.hover) return;
                if (ok && window.Hls && window.Hls.isSupported && window.Hls.isSupported()) {
                    try {
                        if (v._hls) { try { v._hls.destroy(); } catch(e){} }
                        const h = new window.Hls({ capLevelToPlayerSize: true, lowLatencyMode: true, maxBufferLength: 10 });
                        v._hls = h;
                        h.on(window.Hls.Events.MANIFEST_PARSED, () => {
                            if (!this.hover) return;
                            this.syncMute(v);
                            try { const p = v.play(); if (p && p.then) p.then(() => {
                                if (!this.muted) { clearInterval(this.unmuteRetry); this.unmuteRetry = null; }
                            }).catch(() => { if (!this.muted) this.retryUnmuted(v); }); } catch(e) { if (!this.muted) this.retryUnmuted(v); }
                        });
                        h.on(window.Hls.Events.FRAG_BUFFERED, () => {
                            if (!this.hover || this.muted) return;
                            if (v.paused || v.muted) {
                                this.syncMute(v);
                                try { const p = v.play(); if (p && p.catch) p.catch(() => {}); } catch(e) {}
                            }
                        });
                        h.on(window.Hls.Events.ERROR, (evt, data) => {
                            if (data && data.fatal) {
                                try { h.destroy(); } catch(e){}
                                v._hls = null;
                                delete v.dataset.hlsReady;
                            }
                        });
                        h.loadSource(hlsUrl);
                        h.attachMedia(v);
                        v.dataset.hlsReady = '1';
                        setTimeout(() => {
                            if (this.hover && v.paused) {
                                this.syncMute(v);
                                try { const p = v.play(); if (p && p.catch) p.catch(() => {}); } catch(e) {}
                            }
                        }, 800);
                        return;
                    } catch(e) {}
                }
                if (!v.src) { try { v.src = hlsUrl; } catch(e){} }
                v.dataset.hlsReady = '1';
                this.syncMute(v);
                try { const p = v.play(); if (p && p.catch) p.catch(() => {}); } catch(e) {}
            });
            return true;
        },
        playVideo() {
            if (this.isScheduled) return;
            if (!this.hasAccess) return;
            if (this.isRestricted && !this.acknowledged) return;
            this.hover = true;
            this.currentTime = 0;

            const v = window.__previewVideo ? window.__previewVideo(this.$el) : this.$el.querySelector('video');
            if(v) {
                this.syncMute(v);
                const hlsUrl = v.getAttribute('data-hls-url');
                const begin = () => {
                    this.syncMute(v);
                    try { const p = v.play(); if (p && p.catch) p.catch(() => {}); } catch(e) {}
                    if (!this.muted) this.retryUnmuted(v);
                    if (v.readyState < 2) {
                        v.addEventListener('canplay', () => {
                            if (!this.hover) return;
                            this.syncMute(v);
                            try { const p2 = v.play(); if (p2 && p2.catch) p2.catch(() => {}); } catch(e) {}
                        }, { once: true });
                    }
                    const updateTime = () => {
                        if (!this.hover) return;
                        this.currentTime = v.currentTime;
                        if (v.duration && isFinite(v.duration) && v.duration > 0) {
                            this.durationSeconds = v.duration;
                        }
                        requestAnimationFrame(updateTime);
                    };
                    requestAnimationFrame(updateTime);
                };
                if (hlsUrl && !v.dataset.hlsReady && !v.dataset.hlsLoading) {
                    const attached = this.attachHls(v, hlsUrl);
                    if (v.canPlayType && v.canPlayType('application/vnd.apple.mpegurl')) {
                        v.addEventListener('canplay', begin, { once: true });
                        begin();
                    } else if (attached) {
                        begin();
                    } else {
                        begin();
                    }
                } else {
                    if (hlsUrl && v.dataset.hlsLoading) {
                        v.addEventListener('canplay', () => {
                            if (!this.hover) return;
                            this.syncMute(v);
                            v.play().catch(() => {});
                        }, { once: true });
                    }
                    begin();
                }
            } else {
                // For animated preview image (Bunny Stream WebP/GIF)
                let startTime = Date.now();
                const timer = setInterval(() => {
                    if (!this.hover) {
                        clearInterval(timer);
                        return;
                    }
                    this.currentTime = (Date.now() - startTime) / 1000;
                }, 100);
            }
        },
        stopVideo() {
            clearTimeout(this.hoverTimer);
            this.hoverTimer = null;
            this.hover = false;
            clearInterval(this.unmuteRetry); this.unmuteRetry = null;
            const v = window.__previewVideo ? window.__previewVideo(this.$el) : this.$el.querySelector('video');
            if(v) {
                if (v._hls) { try { v._hls.destroy(); } catch(e){} v._hls = null; delete v.dataset.hlsReady; }
                delete v.dataset.hlsLoading;
                v.pause();
                try { v.currentTime = 0; } catch(e){}
            }
            this.currentTime = 0;
        },
        prewarmPreview() {
            if (this._previewPrewarmed) return;
            this._previewPrewarmed = true;
            <?php if($video->isBunnyVideo()): ?>
                const u = '<?php echo e($video->getPreviewUrl()); ?>';
                if (u) {
                    const img = new Image();
                    img.src = u;
                }
            <?php endif; ?>
        },
        onMouseEnter() {
            clearTimeout(this.hoverTimer);
            if (window.__isScrolling) return;
            this.prewarmPreview();
            this.hoverTimer = setTimeout(() => {
                if (!window.__isScrolling) {
                    this.playVideo();
                }
            }, 260);
        },
        onMouseMove() {
            if (this.hover || window.__isScrolling) return;
            this.prewarmPreview();
            if (!this.hoverTimer) {
                this.hoverTimer = setTimeout(() => {
                    if (!window.__isScrolling) {
                        this.playVideo();
                    }
                }, 260);
            }
        },
        onMouseLeave() {
            clearTimeout(this.hoverTimer);
            this.hoverTimer = null;
            this.stopVideo();
            this.touchPreview = false;
        },
        onTouchStart(e) {
            if (e.target && e.target.closest && e.target.closest('button')) return;
            const t = (e.touches && e.touches[0]) || null;
            if (t) {
                this.touchStartX = t.clientX;
                this.touchStartY = t.clientY;
            }
            clearTimeout(this.touchTimer);
            this.touchTimer = setTimeout(() => {
                if (this.isScheduled || !this.hasAccess || (this.isRestricted && !this.acknowledged)) return;
                try { window.dispatchEvent(new CustomEvent('preview-stop-others', { detail: this.$el })); } catch(err) {}
                this.touchPreview = true;
                this.suppressClick = true;
                this.playVideo();
                setTimeout(() => { this.suppressClick = false; }, 800);
            }, 450);
        },
        onTouchMove(e) {
            const t = (e.touches && e.touches[0]) || null;
            if (t) {
                const dx = Math.abs(t.clientX - this.touchStartX);
                const dy = Math.abs(t.clientY - this.touchStartY);
                if (dx + dy > 12) {
                    clearTimeout(this.touchTimer);
                    if (this.touchPreview) {
                        this.stopVideo();
                        this.touchPreview = false;
                    }
                }
            }
        },
        onTouchEnd(e) {
            clearTimeout(this.touchTimer);
            if (e.target && e.target.closest && e.target.closest('button')) return;
            if (this.touchPreview) {
                if (e.preventDefault) { try { e.preventDefault(); } catch(err){} }
                this.suppressClick = true;
                setTimeout(() => { this.suppressClick = false; }, 800);
                return;
            }
        },
        onCardClick(e) {
            if (this.suppressClick) {
                e.preventDefault();
                e.stopPropagation();
                this.suppressClick = false;
                return false;
            }
            if (this.touchPreview) {
                e.preventDefault();
                e.stopPropagation();
                this.stopVideo();
                this.touchPreview = false;
                return false;
            }
        },
        retryUnmuted(v) {
            clearInterval(this.unmuteRetry);
            this.unmuteRetry = setInterval(() => {
                if (!this.hover || this.muted) { clearInterval(this.unmuteRetry); this.unmuteRetry = null; return; }
                if (!v || !v.isConnected) { clearInterval(this.unmuteRetry); this.unmuteRetry = null; return; }
                if (!v.paused && !v.muted && v.volume > 0) { clearInterval(this.unmuteRetry); this.unmuteRetry = null; return; }
                this.syncMute(v);
                try {
                    const p = v.play();
                    if (p && p.then) p.then(() => {
                        if (!v.paused && !v.muted) { clearInterval(this.unmuteRetry); this.unmuteRetry = null; }
                    }).catch(() => {});
                } catch(e) {}
            }, 400);
            setTimeout(() => { clearInterval(this.unmuteRetry); this.unmuteRetry = null; }, 10000);
        },
        safePlay(v, onFail) {
            try {
                const p = v.play();
                if (p && typeof p.then === 'function') {
                    p.then(() => {
                        if (!v.paused && !v.muted) { clearInterval(this.unmuteRetry); this.unmuteRetry = null; }
                    }).catch(() => { if (typeof onFail === 'function') onFail(); });
                } else if (typeof onFail === 'function') {
                    onFail();
                }
            } catch(e) { if (typeof onFail === 'function') onFail(); }
        },
        toggleMute() {
            console.log('[toggleMute] Clicked! Current muted state:', this.muted);
            this.muted = !this.muted;
            console.log('[toggleMute] New muted state:', this.muted);
            
            const root = this.$el.closest('.premium-card') || this.$el;
            const v = window.__previewVideo ? window.__previewVideo(root) : root.querySelector('video');
            console.log('[toggleMute] Video element found:', v);
            
            if(!v) { 
                console.log('[toggleMute] No video element found. Aborting.');
                clearInterval(this.unmuteRetry); this.unmuteRetry = null; return; 
            }
            
            if (this.muted) { clearInterval(this.unmuteRetry); this.unmuteRetry = null; }
            
            this.syncMute(v);
            
            if (!this.muted) {
                this.hover = true;
                
                console.log('[toggleMute] Trying aggressive flush. Current time:', v.currentTime, 'Paused:', v.paused);
                
                // ULTIMATE FIX: Aggressive Audio Pipeline Flush
                try { v.pause(); console.log('[toggleMute] Forced pause'); } catch(e) { console.error('Pause error', e); }
                try { v.currentTime = v.currentTime + 0.05; console.log('[toggleMute] Nudged time to', v.currentTime); } catch(e) { console.error('Time error', e); }
                try { 
                    const p = v.play(); 
                    console.log('[toggleMute] Triggered play()');
                    if (p && p.catch) {
                        p.then(() => console.log('[toggleMute] play() promise resolved! Audio should be playing.'))
                         .catch((err)=>{ console.error('[toggleMute] play() blocked by browser:', err); });
                    }
                } catch(e) { console.error('[toggleMute] Play error', e); }
                
                this.retryUnmuted(v);
                
                v.addEventListener('playing', () => {
                    console.log('[toggleMute] playing event fired. Current volume:', v.volume, 'Muted attr:', v.muted);
                    if (!this.muted) { clearInterval(this.unmuteRetry); this.unmuteRetry = null; }
                }, { once: true });
            }
        },
        get displayDuration() {
            let orig = '<?php echo e($video->formatted_duration !== "--:--" ? $video->formatted_duration : ($video->duration ?? "")); ?>';
            let origSecs = 0;
            if (orig && orig !== '00:00' && orig !== '0:00' && orig !== '--:--') {
                let parts = orig.split(':').reverse();
                for (let i = 0; i < parts.length; i++) {
                    origSecs += parseInt(parts[i] || 0) * Math.pow(60, i);
                }
            }
            if (origSecs === 0 && this.durationSeconds > 0) {
                origSecs = this.durationSeconds;
            }

            if (this.hover && this.currentTime > 0 && origSecs > 0) {
                let remaining = Math.max(0, origSecs - this.currentTime);
                let h = Math.floor(remaining / 3600);
                let m = Math.floor((remaining % 3600) / 60);
                let s = Math.floor(remaining % 60);
                
                if (h > 0 || (orig && orig.split(':').length === 3 && parseInt(orig.split(':')[0]) > 0)) {
                    return h + ':' + (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
                } else {
                    return (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
                }
            }

            if (orig && orig !== '00:00' && orig !== '0:00' && orig !== '' && orig !== '--:--' && orig !== '00:00:00') {
                let parts = orig.split(':');
                if (parts.length === 3) {
                    let h = parseInt(parts[0]);
                    return h > 0 ? (h + ':' + parts[1] + ':' + parts[2]) : (parts[1] + ':' + parts[2]);
                }
                return orig;
            }

            if (this.durationSeconds > 0) {
                let d = this.durationSeconds;
                let h = Math.floor(d / 3600);
                let m = Math.floor((d % 3600) / 60);
                let s = Math.floor(d % 60);
                if (h > 0) {
                    return h + ':' + (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
                } else {
                    return (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
                }
            }

            return (orig && orig !== '00:00' && orig !== '0:00' && orig !== '00:00:00') ? orig : '--:--';
        },
        async pollStatus() {
            try {
                const response = await fetch('<?php echo e(route("videos.check_status", $video->slug)); ?>');
                const data = await response.json();
                if (data.success) {
                    this.progress = data.encode_progress || 0;
                    if (data.encode_progress >= 100) {
                        window.location.reload();
                    } else {
                        setTimeout(() => this.pollStatus(), 5000);
                    }
                }
            } catch (e) {
                setTimeout(() => this.pollStatus(), 10000);
            }
        }
     }" 
      @mouseenter="onMouseEnter()"
      @mousemove="onMouseMove()"
      @mouseleave="onMouseLeave()"
     @touchstart="onTouchStart($event)"
     @touchmove="onTouchMove($event)"
     @touchend="onTouchEnd($event)"
     @touchcancel="clearTimeout(touchTimer); if(!touchPreview) stopVideo()"
     @click="onCardClick($event)"
     @contextmenu.prevent
     style="touch-action: pan-y; -webkit-touch-callout: none;">
    <a href="<?php echo e(route('videos.show', $video)); ?>" class="block" style="-webkit-touch-callout: none; -webkit-user-select: none; user-select: none;" oncontextmenu="return false;" ondragstart="return false;" draggable="false" @contextmenu.prevent>
        <!-- Thumbnail Container -->
        <div class="aspect-video relative rounded-[1.25rem] overflow-hidden bg-slate-200 dark:bg-white/5 border border-gray-100 dark:border-white/5 shadow-sm group-hover:shadow-md transition-shadow">
            <!-- Video Background (Native App Feel) -->
            <?php if($video->isBunnyVideo()): ?>
                <img :src="hover ? '<?php echo e($video->getPreviewUrl()); ?>' : 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs='" 
                       class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" 
                       :class="hover ? 'scale-110' : 'scale-100'" />
                
                <video data-hls-url="<?php echo e($video->getPlayUrl()); ?>"
                       class="absolute inset-0 w-full h-full object-cover transition-all duration-700"
                       :class="hover ? 'scale-110 opacity-100' : 'scale-100 opacity-0'"
                       muted loop playsinline preload="none"></video>
            <?php elseif($video->video_path): ?>
                
                <video src="<?php echo e(asset(getFilePath('video') . '/' . $video->video_path)); ?>"
                       class="absolute inset-0 w-full h-full object-cover transition-transform duration-700"
                       :class="hover ? 'scale-110' : 'scale-100'"
                       muted loop playsinline preload="none"></video>
            <?php endif; ?>

            <!-- Static Thumbnail Overlay -->
            <img src="<?php echo e($video->getThumbnailUrl()); ?>"
                 alt="<?php echo e($video->title); ?> thumbnail"
                 decoding="async" fetchpriority="high"
                 class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 z-10"
                 :class="hover ? 'opacity-0' : 'opacity-100'"
                 onerror="this.src='<?php echo e($video->getPreviewUrl()); ?>'; this.onerror=null;">

            <!-- Glassmorphism Play Overlay (hidden while preview sound is on) -->
            <div x-show="muted" x-transition.opacity.duration.300ms class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 pointer-events-none z-20">
                <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 shadow-2xl scale-75 group-hover:scale-100 transition-transform">
                    <span class="material-symbols-rounded text-white text-3xl">play_arrow</span>
                </div>
            </div>

            <!-- 18+ Badge -->
            <?php if($video->is_age_restricted): ?>
                <div class="absolute top-2.5 left-2.5 z-30 bg-rose-600 text-white text-[9px] font-black px-1.5 py-0.5 rounded shadow-lg flex items-center gap-1 uppercase tracking-tighter border border-white/20">
                    <span class="material-symbols-rounded text-[11px]">explicit</span>
                    18+
                </div>
            <?php endif; ?>

            <!-- Premium Badge (Right for Trending, Left otherwise) -->
            <?php if($video->is_premium): ?>
                <div class="absolute top-2.5 <?php echo e((isset($isTrending) && $isTrending) ? 'right-2.5' : ($video->is_age_restricted ? 'left-14' : 'left-2.5')); ?> z-30 gradient-orange text-white text-[9px] font-black px-1.5 py-0.5 rounded shadow-lg flex items-center gap-1 uppercase tracking-tighter border border-white/20">
                    <span class="material-symbols-rounded text-[11px] material-symbols-filled">workspace_premium</span>
                    <span class="<?php echo e(request()->routeIs('history') ? 'hidden lg:inline' : ''); ?>">Premium</span>
                </div>
            <?php endif; ?>

            
            <?php if($video->video_path || $video->isBunnyVideo()): ?>
                <button type="button" x-show="hover" x-transition.opacity @click.prevent.stop="toggleMute()" @touchstart.stop @touchend.stop.prevent="toggleMute()" aria-label="Toggle preview sound" title="Toggle sound"
                        class="absolute <?php echo e(($video->is_premium && isset($isTrending) && $isTrending) ? 'top-12' : 'top-2'); ?> right-2 z-[35] w-10 h-10 sm:w-8 sm:h-8 rounded-full bg-black/60 backdrop-blur-md text-white flex items-center justify-center hover:bg-black/80 active:scale-95 transition-all shadow-lg select-none">
                    <span class="material-symbols-rounded text-xl sm:text-lg" x-text="muted ? 'volume_off' : 'volume_up'">volume_off</span>
                </button>
            <?php endif; ?>
            <?php if($video->scheduled_at && $video->scheduled_at->isFuture()): ?>
                <div class="absolute inset-0 z-[35] bg-black/80 backdrop-blur-[3px] flex flex-col items-center justify-center text-center p-2">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center mb-1 border border-blue-500/20">
                        <span class="material-symbols-rounded text-base sm:text-lg animate-pulse">schedule</span>
                    </div>
                    <p class="text-white font-black text-[7px] sm:text-[8px] uppercase tracking-widest leading-none mb-0.5">Premieres</p>
                    <p class="text-blue-400 font-bold text-[7px] sm:text-[8px] leading-none"><?php echo e($video->scheduled_at->timezone('Asia/Kolkata')->format('M d, g:i A')); ?></p>
                </div>
            <?php endif; ?>

            <!-- Age Restriction / Reported Gate -->
            <?php if($video->isRestricted() && !$isOptimizing): ?>
                <div x-show="!acknowledged"
                     class="absolute inset-0 z-[45] bg-[#0F0F0F]/80 backdrop-blur-[30px] flex flex-col items-center justify-center p-4 text-center select-none">
                    <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-3 shadow-xl shrink-0">
                        <span class="material-symbols-rounded text-3xl text-white opacity-90 flex items-center justify-center leading-none select-none">shield_lock</span>
                    </div>
                    <h4 class="text-white text-[10px] font-black uppercase tracking-widest mb-1 leading-none">Restricted</h4>
                    
                    <?php if(!auth()->check()): ?>
                        <p class="text-white/50 text-[8px] font-bold uppercase tracking-tighter mb-4">Sign in to Verify</p>
                        <button @click.prevent.stop="window.location.href='<?php echo e(route('login')); ?>'" class="px-4 py-2 bg-rose-600 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-rose-600/20">
                            Sign In
                        </button>
                    <?php else: ?>
                        <p class="text-white/50 text-[8px] font-bold uppercase tracking-tighter mb-4">Acknowledge to View</p>
                        <button @click.prevent.stop="acknowledged = true" class="px-4 py-2 bg-gradient-to-tr from-rose-600 to-orange-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-rose-600/20">
                            I Understand
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Premium Access Overlay (Only if not purchased/no access AND not optimizing) -->
            <?php if($video->is_premium && !$hasAccess && !$isOptimizing && !$video->is_age_restricted): ?>
                <div class="absolute inset-0 z-40 bg-black/60 backdrop-blur-[4px] flex flex-col items-center justify-center text-center p-4">
                    <div class="w-12 h-12 rounded-xl gradient-orange text-white flex items-center justify-center mb-3 shadow-xl shadow-orange-500/40">
                        <span class="material-symbols-rounded text-2xl material-symbols-filled">lock</span>
                    </div>
                    <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-1">Premium Access</h4>
                    <p class="text-orange-400 font-black text-xs"><?php echo e(showAmount($video->price)); ?></p>
                </div>
            <?php endif; ?>

            <!-- Optimizing State -->
            <template x-if="isOptimizing">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50 pointer-events-none">
                    <div class="flex flex-col items-center">
                        <div class="relative w-16 h-16 mb-4 flex items-center justify-center">
                            <!-- Circular Progress -->
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="4" fill="transparent" class="text-white/10" />
                                <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="4" fill="transparent" 
                                    class="text-orange-500 transition-all duration-500"
                                    stroke-dasharray="175.9"
                                    :stroke-dashoffset="175.9 - (175.9 * progress / 100)" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                            </div>
                        </div>
                        <span class="text-[9px] font-black text-white uppercase tracking-[0.2em] animate-pulse">please wait awesome is loading</span>
                    </div>
                </div>
            </template>

            <div x-text="displayDuration" class="absolute bottom-2 right-2 z-20 px-1.5 py-0.5 bg-black/80 backdrop-blur-md rounded-md text-[10px] font-black text-white tracking-tight shadow-sm">
                <?php echo e($video->formatted_duration); ?>

            </div>
            
            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
    </a>

    <!-- Meta Container -->
    <div class="mt-4 flex gap-3">
        <!-- Avatar Section -->
        <div class="shrink-0 pt-0.5">
            <a href="<?php echo e($video->user?->channel ? route('channels.show', $video->user->channel) : '#'); ?>" class="block" aria-label="View <?php echo e($video->user?->channel?->name ?? 'Creator'); ?>'s channel">
                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-gray-200 to-gray-300 dark:from-white/10 dark:to-white/5 flex items-center justify-center text-slate-500 overflow-hidden border border-gray-100 dark:border-white/5 transition-transform active:scale-90">
                    <?php if($video->user?->channel?->avatar): ?>
                        <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $video->user->channel->avatar)); ?>" alt="<?php echo e($video->user->channel->name ?? 'Creator'); ?> avatar" loading="lazy" decoding="async" class="w-full h-full object-cover">
                    <?php elseif($video->user?->image): ?>
                            <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $video->user->image)); ?>" alt="<?php echo e($video->user->name ?? 'Creator'); ?> avatar" loading="lazy" decoding="async" class="w-full h-full object-cover">
                    <?php else: ?>
                        <?php
                            $name = $video->user?->channel?->name ?? $video->user?->name ?? 'C';
                            $words = explode(' ', trim($name));
                            $initials = count($words) >= 2 
                                ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                : strtoupper(substr($name, 0, 2));
                        ?>
                        <span class="text-xs font-black text-gray-600 dark:text-white"><?php echo e($initials); ?></span>
                    <?php endif; ?>
                </div>
            </a>
        </div>

        <!-- Detail Section -->
        <div class="flex-1 min-w-0">
            <a href="<?php echo e(route('videos.show', $video)); ?>" style="-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; touch-action: pan-y;" oncontextmenu="return false;" ondragstart="return false;" draggable="false" @contextmenu.prevent>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-snug line-clamp-2 transition-colors group-hover:text-blue-600" title="<?php echo e($video->title); ?>">
                    <?php echo e($video->title); ?>

                </h3>
            </a>
            <div class="mt-1.5 flex flex-wrap items-center gap-1 text-[11px] font-medium text-slate-500 dark:text-slate-400">
                <a href="<?php echo e($video->user?->channel ? route('channels.show', $video->user->channel) : '#'); ?>" class="hover:text-slate-900 dark:hover:text-white transition-colors"><?php echo e($video->user?->channel?->name ?? $video->user?->name ?? 'Creator'); ?></a>
                <span class="text-[10px]">•</span>
                <?php if($video->scheduled_at && $video->scheduled_at->isFuture()): ?>
                    <span class="text-blue-500 font-bold">Scheduled for <?php echo e($video->scheduled_at->format('M d, Y h:i A')); ?></span>
                <?php else: ?>
                    <span><?php echo e(formatNumber($video->views_count ?? 0)); ?> views</span>
                    <span class="text-[10px]">•</span>
                    <span><?php echo e($video->created_at->diffForHumans()); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Action Menu -->
        <div class="shrink-0">
            <button @click.prevent.stop="window.openVideoOptions('<?php echo e($video->id); ?>', '<?php echo e(addslashes($video->title)); ?>', 'video', <?php echo e($video->isLikedBy(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($video->isInWatchLater(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($video->isInAnyPlaylist(auth()->user()) ? 'true' : 'false'); ?>, <?php echo json_encode($video->getPlaylistMembershipIds(auth()->user()), 15, 512) ?>, <?php echo e($video->user_id); ?>)" 
                    aria-label="Video options"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors active:scale-95">
                <span class="material-symbols-rounded text-xl">more_vert</span>
            </button>
        </div>
    </div>


</div>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\partials\video_card.blade.php ENDPATH**/ ?>