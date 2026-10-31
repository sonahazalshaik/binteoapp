<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<div x-data="{
    active: false,
    isPlaying: false,
    isMuted: true,
    isBuffering: false,
    progress: 0,
    isDragging: false,
    videoTitle: '',
    videoUrl: '',
    slug: '',
    hls: null,

    init() {
        const saved = localStorage.getItem('miniPlayerState');
        if (saved) {
            try {
                const state = JSON.parse(saved);
                const currentPath = window.location.pathname;
                
                // Only hide if we are currently looking at the full video page
                const isOnVideoPage = state.slug && (currentPath === '/videos/' + state.slug || currentPath.endsWith('/' + state.slug));
                // If on a different video page, clear stale mini player state
                const isAnyVideoPage = currentPath.match(/^\/videos\//);
                
                if (isAnyVideoPage && !isOnVideoPage) {
                    localStorage.removeItem('miniPlayerState');
                } else if (state.url && !isOnVideoPage) {
                    this.loadState(state);
                }
            } catch (e) {
                console.error('Miniplayer Init Error:', e);
            }
        }

        const video = this.$refs.miniVideo;
        if (video) {
            video.addEventListener('timeupdate', () => {
                this.progress = (video.currentTime / video.duration) * 100;
                if (this.active && !video.paused) {
                    const saved = localStorage.getItem('miniPlayerState');
                    if (saved) {
                        try {
                            const state = JSON.parse(saved);
                            state.currentTime = video.currentTime;
                            localStorage.setItem('miniPlayerState', JSON.stringify(state));
                        } catch (e) {}
                    }
                }
            });
            video.addEventListener('playing', () => { this.isPlaying = true; this.isBuffering = false; });
            video.addEventListener('waiting', () => { this.isBuffering = true; });
            video.addEventListener('pause', () => { this.isPlaying = false; });
            video.addEventListener('volumechange', () => { this.isMuted = video.muted; });
            video.addEventListener('error', (e) => {
                console.error('Miniplayer Video Error:', e);
                this.isBuffering = false;
            });
        }
    },

    loadState(state) {
        if (!state.url) return;
        this.videoTitle = state.title || '';
        this.videoUrl = state.url;
        this.slug = state.slug;
        this.active = true;
        this.isBuffering = true;

        const video = this.$refs.miniVideo;
        if (!video) return;

        // Ensure video is clean
        video.pause();
        video.src = '';
        video.load();

        if (this.videoUrl.includes('.m3u8')) {
            // Check for HLS support
            if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = this.videoUrl;
                video.addEventListener('loadedmetadata', () => {
                    video.currentTime = state.currentTime || 0;
                    this.attemptPlay();
                }, { once: true });
            } else if (typeof Hls !== 'undefined' && Hls.isSupported()) {
                if (this.hls) this.hls.destroy();
                this.hls = new Hls({
                    maxBufferLength: 10,
                    capLevelToPlayerSize: true,
                    enableWorker: true,
                    initialLiveManifestSize: 1,
                    abrEwmaDefaultEstimate: 500000, // Force low quality estimate (500kbps)
                    testBandwidth: false
                });
                this.hls.loadSource(this.videoUrl);
                this.hls.attachMedia(video);
                this.hls.on(Hls.Events.MANIFEST_PARSED, () => {
                    video.currentTime = state.currentTime || 0;
                    this.attemptPlay();
                });
                this.hls.on(Hls.Events.ERROR, (event, data) => {
                    if (data.fatal) {
                        switch (data.type) {
                            case Hls.ErrorTypes.NETWORK_ERROR: this.hls.startLoad(); break;
                            case Hls.ErrorTypes.MEDIA_ERROR: this.hls.recoverMediaError(); break;
                            default: console.warn('Miniplayer: Non-critical fatal error'); break;
                        }
                    }
                });
            }
        } else {
            video.src = this.videoUrl;
            video.addEventListener('loadedmetadata', () => {
                video.currentTime = state.currentTime || 0;
                this.attemptPlay();
            }, { once: true });
        }
    },

    attemptPlay() {
        const video = this.$refs.miniVideo;
        if (!video) return;
        
        video.muted = true; // Always start muted on mobile to ensure autoplay works
        this.isMuted = true;

        const playPromise = video.play();
        if (playPromise !== undefined) {
            playPromise.then(() => {
                this.isPlaying = true;
                this.isBuffering = false;
            }).catch(error => {
                console.log('Autoplay prevented, waiting for interaction');
                this.isBuffering = false;
                this.isPlaying = false;
            });
        }
    },

    togglePlay() {
        if (this.$refs.miniVideo.paused) this.$refs.miniVideo.play();
        else this.$refs.miniVideo.pause();
    },

    toggleMute() {
        this.isMuted = !this.isMuted;
        this.$refs.miniVideo.muted = this.isMuted;
    },

    closePlayer() {
        this.active = false;
        if (this.$refs.miniVideo) this.$refs.miniVideo.pause();
        if (this.hls) { this.hls.destroy(); this.hls = null; }
        localStorage.removeItem('miniPlayerState');
    },

    seekClientX(clientX) {
        const bar = this.$refs.progressBar;
        if (!bar) return;
        const rect = bar.getBoundingClientRect();
        const percent = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
        const video = this.$refs.miniVideo;
        if (video && video.duration) {
            video.currentTime = percent * video.duration;
        }
    },

    seek(event) {
        this.seekClientX(event.clientX || (event.touches && event.touches[0].clientX));
    },

    startDrag(event) {
        this.isDragging = true;
        this.seek(event);
    },

    onDrag(event) {
        if (!this.isDragging) return;
        event.preventDefault();
        this.seek(event);
    },

    stopDrag() {
        this.isDragging = false;
    },

    expandPlayer() {
        const url = '/videos/' + this.slug + '?t=' + Math.floor(this.$refs.miniVideo.currentTime);
        this.closePlayer();
        window.location.href = url;
    }
}"
x-show="active"
x-cloak
class="fixed bottom-24 lg:bottom-6 right-2 lg:right-4 w-48 sm:w-64 lg:w-80 aspect-video bg-black rounded-xl lg:rounded-2xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-[9999] border border-white/10 group transition-all duration-500"
:class="active ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-10 opacity-0 scale-95 pointer-events-none'">
    
    <div class="relative w-full h-full">
        <video x-ref="miniVideo" class="w-full h-full object-cover" playsinline muted preload="auto" crossorigin="anonymous"></video>
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-between p-2 lg:p-3 z-30">
            <div class="flex items-center justify-between relative z-50">
                <p class="text-[8px] lg:text-[10px] font-black text-white truncate uppercase tracking-widest flex-1 pr-2" x-text="videoTitle"></p>
                <button @click.stop="closePlayer()" class="w-6 h-6 lg:w-8 lg:h-8 rounded-lg bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-white/30 transition-all shadow-lg pointer-events-auto">
                    <span class="material-symbols-rounded text-base lg:text-lg">close</span>
                </button>
            </div>

            <div class="flex items-center justify-center gap-3 lg:gap-6 relative">
                <!-- Loading Spinner -->
                <div x-show="isBuffering" class="absolute inset-0 flex items-center justify-center bg-black/20 backdrop-blur-[2px] z-20 pointer-events-none">
                    <div class="w-6 h-6 lg:w-8 lg:h-8 border-2 border-white/20 border-t-white rounded-full animate-spin"></div>
                </div>

                <button @click="toggleMute()" class="w-8 h-8 lg:w-10 lg:h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white hover:bg-white/20 transition-all">
                    <span class="material-symbols-rounded text-lg lg:text-xl" x-text="isMuted ? 'volume_off' : 'volume_up'"></span>
                </button>
                
                <button @click="togglePlay()" class="w-10 h-10 lg:w-14 lg:h-14 rounded-full bg-white/20 backdrop-blur-xl border border-white/20 flex items-center justify-center text-white hover:scale-110 transition-all">
                    <span class="material-symbols-rounded text-2xl lg:text-4xl" x-text="isPlaying ? 'pause' : 'play_arrow'"></span>
                </button>

                <button @click="expandPlayer()" class="w-8 h-8 lg:w-10 lg:h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white hover:bg-white/20 transition-all">
                    <span class="material-symbols-rounded text-lg lg:text-xl">open_in_full</span>
                </button>
            </div>

            <div x-ref="progressBar"
                 @mousedown="startDrag"
                 @mousemove="onDrag"
                 @mouseup.window="stopDrag"
                 @mouseleave="stopDrag"
                 @touchstart="startDrag"
                 @touchmove="onDrag"
                 @touchend="stopDrag"
                 class="w-full bg-white/20 h-2 rounded-full overflow-hidden cursor-pointer group/progress relative">
                <div class="h-full bg-red-600" :class="isDragging ? '' : 'transition-all duration-150'" :style="`width: ${progress}%`"></div>
                <div class="absolute top-0 left-0 h-full opacity-0 group-hover/progress:opacity-100 transition-opacity bg-white/30" :style="`width: ${progress}%`"></div>
            </div>
        </div>

        <div class="absolute bottom-2 left-3 group-hover:opacity-0 transition-opacity">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></div>
                <span class="text-[8px] font-black text-white/60 uppercase tracking-widest">Miniplayer Active</span>
            </div>
        </div>
    </div>
</div>
