<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $seoDescription ?? gs('seo_description') ?? 'Binteo App - The premier video sharing platform. Experience lightning fast speeds, offline viewing, and instant updates.' }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Theme Color for Android Address Bar / Location Bar -->
        <meta name="theme-color" id="theme-color-meta" content="#FFFFFF">
        <script>
            (function(){
                var meta = document.getElementById('theme-color-meta');
                function sync(){ meta.content = document.documentElement.classList.contains('dark') ? '#0A0A0A' : '#FFFFFF'; }
                sync();
                new MutationObserver(sync).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            })();
        </script>

        <title>{{ isset($pageTitle) ? $pageTitle . ' - ' : '' }}{{ gs('site_name') }}</title>
        
        @if(gs('google_analytics_id'))
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ gs('google_analytics_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', "{{ gs('google_analytics_id') }}");
        </script>
        @endif

        <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
        @include('partials.pwa')

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        {{-- Preload Critical Fonts --}}
        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght,FILL@100..700,0..1&display=block" />
        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" />

        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght,FILL@100..700,0..1&display=block" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        
        <!-- Cropper.js -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
        <script defer src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

        <!-- Alpine Plugins -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Scripts -->
        <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('style')
        <script defer src="{{ asset('assets/js/validation.js') }}"></script>
        <style>
            body { font-family: 'Outfit', sans-serif; }
            #navigation-header { z-index: 100000 !important; position: relative; pointer-events: none; }
            #navigation-header nav { pointer-events: auto; }
            .material-symbols-rounded {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                line-height: 1;
                width: 1em;
                height: 1em;
                overflow: hidden;
                white-space: nowrap;
            }
            /* NOTE: Do not force a color on icons here (e.g. transparent/inherit).
               It beats Tailwind's text-color utilities site-wide and leaves icons
               dark-on-dark in dark mode. The font URL uses display=block, which
               already hides fallback text while the font loads. */
            html.dark select option {
                background-color: #1A1A1A;
                color: #FFFFFF;
            }
            .material-symbols-filled {
                font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
            .premium-card { border-radius: 1.5rem; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
            .premium-card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); }
            
            .gradient-orange {
                background: linear-gradient(135deg, oklch(0.65 0.25 30), oklch(0.75 0.2 35), oklch(0.55 0.15 25));
            }
            .text-gradient-orange {
                background: linear-gradient(135deg, oklch(0.65 0.25 30), oklch(0.75 0.2 35), oklch(0.55 0.15 25));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            [x-cloak] { display: none !important; }
            .swal2-container { z-index: 1000000 !important; }
            
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
            .animate-shake { animation: shake 0.2s ease-in-out 0s 2; }

            /* Hide default select arrows across all browsers */
            select {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                background-image: none !important;
            }
            select::-ms-expand {
                display: none;
            }
            @keyframes slide-up {
                from { transform: translateY(100%); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            .animate-slide-up { animation: slide-up 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
            @media (display-mode: standalone) {
                .pwa-install-btn { display: none !important; }
            }
            /* Infinite Icons System */
            .infinite-icon {
                -webkit-background-clip: text !important;
                -webkit-text-fill-color: transparent !important;
                display: inline-block;
            }
            .icon-home { background: linear-gradient(135deg, #FF0080, #7928CA); }
            .icon-trending { background: linear-gradient(135deg, #F59E0B, #EF4444); }
            .icon-reels { background: linear-gradient(135deg, #8B5CF6, #EC4899); }
            .icon-ott { background: linear-gradient(135deg, #3B82F6, #10B981); }
            .icon-marketplace { background: linear-gradient(135deg, #6366F1, #A855F7); }
            .icon-history { background: linear-gradient(135deg, #64748B, #334155); }
            .icon-liked { background: linear-gradient(135deg, #F43F5E, #FB7185); }
            .icon-playlist { background: linear-gradient(135deg, #F59E0B, #D97706); }
            .icon-later { background: linear-gradient(135deg, #0EA5E9, #2563EB); }
            .icon-support { background: linear-gradient(135deg, #06B6D4, #0891B2); }
            .icon-settings { background: linear-gradient(135deg, #94A3B8, #475569); }
            .icon-about { background: linear-gradient(135deg, #F97316, #EA580C); }
            .icon-policy { background: linear-gradient(135deg, #10B981, #059669); }
            .icon-privacy { background: linear-gradient(135deg, #3B82F6, #2DD4BF); }
            .icon-terms { background: linear-gradient(135deg, #6366F1, #8B5CF6); }
            .icon-copyright { background: linear-gradient(135deg, #F59E0B, #EA580C); }
            .icon-community { background: linear-gradient(135deg, #EC4899, #F43F5E); }
            
            /* Category Specific Gradients */
            .icon-music { background: linear-gradient(135deg, #A855F7, #EC4899); }
            .icon-gaming { background: linear-gradient(135deg, #10B981, #3B82F6); }
            .icon-films { background: linear-gradient(135deg, #EF4444, #F59E0B); }
            .icon-entertainment { background: linear-gradient(135deg, #EC4899, #F97316); }
            .icon-education { background: linear-gradient(135deg, #3B82F6, #6366F1); }
            .icon-news { background: linear-gradient(135deg, #475569, #1E293B); }
            .icon-tech { background: linear-gradient(135deg, #06B6D4, #3B82F6); }
            .icon-sports { background: linear-gradient(135deg, #84CC16, #10B981); }
            .icon-vlog { background: linear-gradient(135deg, #F43F5E, #F97316); }
            .icon-cars { background: linear-gradient(135deg, #38BDF8, #6366F1); }
            .icon-comedy { background: linear-gradient(135deg, #FBBF24, #F59E0B); }
            .icon-cooking { background: linear-gradient(135deg, #F97316, #EF4444); }
            .icon-fashion { background: linear-gradient(135deg, #EC4899, #A855F7); }
            .icon-food { background: linear-gradient(135deg, #F59E0B, #EF4444); }
            .icon-kids { background: linear-gradient(135deg, #F472B6, #FBBF24); }
            .icon-lifestyle { background: linear-gradient(135deg, #34D399, #10B981); }
            .icon-live { background: linear-gradient(135deg, #EF4444, #F97316); }
            .icon-people { background: linear-gradient(135deg, #60A5FA, #818CF8); }
            .icon-pets { background: linear-gradient(135deg, #FBBF24, #F472B6); }
            .icon-science { background: linear-gradient(135deg, #22D3EE, #6366F1); }
            .icon-talent { background: linear-gradient(135deg, #FDE047, #F59E0B); }
            .icon-trending { background: linear-gradient(135deg, #F59E0B, #EF4444); }
            .icon-default-cat { background: linear-gradient(135deg, #94A3B8, #64748B); }

            /* Native-app feel: SweetAlert popups must not slide/scroll on touch (mobile + tablet only) */
            @media (max-width: 1023px) {
                .swal2-container {
                    overscroll-behavior: contain !important;
                    touch-action: none !important;
                }
                .swal2-popup {
                    touch-action: none !important;
                    width: auto !important;
                    max-width: calc(100vw - 32px) !important;
                    overflow: hidden !important;
                }
                .swal2-html-container {
                    overflow: hidden !important;
                    max-width: 100% !important;
                }
                .swal2-html-container > div[style] {
                    min-width: 0 !important;
                    max-width: 100% !important;
                }
            }
        </style>


@if(request()->routeIs('reels.index'))
<style>
    html, body {
        overflow: hidden !important;
        height: 100dvh !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        /* DO NOT use position:fixed — it kills scroll events inside child containers */
    }
</style>
@endif
        
        <script>
            // Global Bridge for Video Options — converts individual params to event detail with caching
            window.videoStates = window.videoStates || {};
            window.openVideoOptions = function(id, title, type = 'video', isLiked = false, isInWatchLater = false, isInAnyPlaylist = false, playlistIds = [], userId = null) {
                const key = type + '_' + id;
                let cached = window.videoStates[key];
                
                // If user already interacted with this item during this session,
                // use the cached state (it reflects the real server-synced values).
                // Otherwise use the fresh Blade template values.
                let state = cached ? { ...cached } : {
                    id: id,
                    title: title,
                    type: type,
                    isLiked: Boolean(isLiked),
                    isInWatchLater: Boolean(isInWatchLater),
                    isInAnyPlaylist: Boolean(isInAnyPlaylist),
                    playlistIds: playlistIds || [],
                    userId: userId
                };
                
                // Always keep id/title/type fresh
                state.id = id;
                state.title = title;
                state.type = type;
                state.userId = userId;

                window.videoStates[key] = state;
                window.dispatchEvent(new CustomEvent('trigger-video-options', { 
                    detail: { ...state } 
                }));
            };

            // Global listener to keep states in sync across all components during the session
            window.addEventListener('video-status-updated', (e) => {
                const detail = e.detail;
                if (!detail || !detail.id || !detail.type) return;
                const key = detail.type + '_' + detail.id;
                window.videoStates[key] = window.videoStates[key] || {};
                const state = window.videoStates[key];
                
                state.id = detail.id;
                state.type = detail.type;
                if (detail.hasOwnProperty('isLiked')) state.isLiked = detail.isLiked;
                if (detail.hasOwnProperty('isInWatchLater')) state.isInWatchLater = detail.isInWatchLater;
                if (detail.hasOwnProperty('isInAnyPlaylist')) state.isInAnyPlaylist = detail.isInAnyPlaylist;
                if (detail.hasOwnProperty('playlistIds')) state.playlistIds = detail.playlistIds;
            });




            window.notify = function(type, message) {
                Swal.fire({
                    title: type.charAt(0).toUpperCase() + type.slice(1),
                    text: message,
                    icon: type,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                });
            };

            window.showPublishingLoader = function(type = 'video') {
                Swal.fire({
                    html: `
                        <div class="flex flex-col items-center justify-center p-6" style="min-height: 220px; min-width: 340px;">
                            <div class="w-16 h-16 border-4 border-orange-500/20 border-t-orange-500 rounded-full animate-spin mb-6"></div>
                            <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-widest mb-2">Please Wait</h2>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-relaxed" style="min-height: 40px; white-space: pre-line; text-align: center;">The ${type} is publishing...</p>
                        </div>
                    `,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    customClass: {
                        popup: 'rounded-[2rem] border border-slate-100 dark:border-white/5 shadow-2xl'
                    }
                });
            };

            // Global Studio Actions
            window.confirmDelete = function(url) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, delete it!',
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('delete-form-global');
                        if (form) {
                            form.action = url;
                            form.submit();
                        }
                    }
                });
            };

            window.makePremium = function(videoId, currentPrice, url) {
                if (parseFloat(currentPrice) > 0) {
                    const form = document.getElementById('premium-form-global');
                    if (form) {
                        form.action = url;
                        form.submit();
                    }
                } else {
                    Swal.fire({
                        title: 'Set Video Price',
                        text: 'Enter the price for this premium video (e.g. 9.99)',
                        input: 'number',
                        inputAttributes: {
                            min: 0,
                            step: 'any',
                            placeholder: '0.00'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Make Premium',
                        confirmButtonColor: '#F97316',
                        cancelButtonColor: '#64748b',
                        showLoaderOnConfirm: true,
                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                        preConfirm: (price) => {
                            if (!price || price <= 0) {
                                Swal.showValidationMessage('Please enter a valid price');
                            }
                            return price;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('premium-form-global');
                            const input = document.getElementById('premium-price-input-global');
                            if (form && input) {
                                input.value = result.value;
                                form.action = url;
                                form.submit();
                            }
                        }
                    });
                }
            };

            @if(auth()->check())
            document.addEventListener('alpine:init', () => {
                Alpine.data('videoOptions', () => ({
                    open: false,
                    video: { id: null, title: '', type: 'video', userId: null },
                    currentUserId: {{ auth()->id() ?? 'null' }},
                    view: 'options',
                    playlists: @json(auth()->user()->playlists->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'is_member' => false])),
                    isInWatchLater: false,
                    isLiked: false,
                    isInAnyPlaylist: false,
                    loading: false,
                    lastInteractionTime: 0,
                    
                    init() {
                        // Component ready
                    },
                    
                    handleTrigger(detail) {
                        this.lastInteractionTime = Date.now();
                        if (!detail || !detail.id) return;

                        this.video = { 
                            id: detail.id, 
                            title: detail.title || '',
                            type: detail.type || 'video',
                            userId: detail.userId ?? null
                        };
                        
                        // Use strict boolean check — template passes true/false explicitly
                        this.isInWatchLater = detail.isInWatchLater === true;
                        this.isLiked = detail.isLiked === true;
                        this.isInAnyPlaylist = detail.isInAnyPlaylist === true;
                        
                        // Update individual playlist membership status instantly from template data
                        if (detail.playlistIds && Array.isArray(detail.playlistIds) && detail.playlistIds.length > 0) {
                            this.playlists.forEach(pl => {
                                pl.is_member = detail.playlistIds.includes(Number(pl.id)) || detail.playlistIds.includes(String(pl.id));
                            });
                            this.isInAnyPlaylist = true;
                        } else {
                            this.playlists.forEach(pl => pl.is_member = false);
                        }
                        
                        this.loading = false;
                        this.view = 'options';
                        this.open = true;
                        
                        // No background fetchStatus() on open — state is already accurate
                        // from Blade template values + session cache. Avoids PHP session lock
                        // contention that causes 2-3s delays on subsequent actions.
                    },

                    updateCacheAndDispatch() {
                        const key = this.video.type + '_' + this.video.id;
                        const playlistIds = this.playlists.filter(p => p.is_member).map(p => Number(p.id));
                        
                        const detail = {
                            id: this.video.id,
                            type: this.video.type,
                            isLiked: this.isLiked,
                            isInWatchLater: this.isInWatchLater,
                            isInAnyPlaylist: this.isInAnyPlaylist,
                            playlistIds: playlistIds
                        };
                        
                        window.videoStates[key] = detail;
                        window.dispatchEvent(new CustomEvent('video-status-updated', { detail }));
                    },

                    fetchStatus() {
                        const startTime = Date.now();
                        fetch(`${window.location.origin}/videos/${this.video.id}/membership-status?type=${this.video.type}&t=${Date.now()}`)
                            .then(async res => {
                                const data = await res.json().catch(() => ({}));
                                if(!res.ok) throw new Error(data.message || 'Content not found');
                                return data;
                            })
                            .then(data => {
                                // Only apply if no new interactions happened while we were fetching
                                if (startTime < this.lastInteractionTime) return;

                                // Always sync playlist membership from server (handles new playlists, cross-tab changes)
                                if (data.playlists && data.playlists.length > 0) {
                                    this.playlists = data.playlists;
                                    this.isInAnyPlaylist = data.playlists.some(pl => pl.is_member);
                                }
                                
                                if (data.hasOwnProperty('inWatchLater')) this.isInWatchLater = data.inWatchLater;
                                if (data.hasOwnProperty('isLiked')) this.isLiked = data.isLiked;
                                
                                this.updateCacheAndDispatch();
                                this.loading = false;
                            })
                            .catch(err => {
                                if (startTime < this.lastInteractionTime) return;
                                console.warn('Background sync failed:', err.message);
                                this.loading = false;
                            });
                    },

                    // Helper: animate and remove a video card from the DOM
                    _removeVideoCard(videoId, type = 'video') {
                        const card = document.getElementById(`item-${type}-${videoId}`)
                                  || document.querySelector(`[data-video-id="${videoId}"][data-video-type="${type}"]`)
                                  || document.querySelector(`[data-video-id="${videoId}"]`)
                                  || document.querySelector(`[data-reel-id="${videoId}"]`);
                        if (card) {
                            // Inside a swiper/carousel, remove the whole slide so no blank gap remains.
                            const slide = card.closest('.swiper-slide');
                            const target = slide || card;
                            const wrapper = card.closest('.swiper-wrapper');
                            target.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                            target.style.opacity = '0';
                            target.style.transform = 'translateY(10px) scale(0.95)';
                            target.style.maxHeight = target.scrollHeight + 'px';
                            setTimeout(() => {
                                target.style.maxHeight = '0px';
                                target.style.margin = '0';
                                target.style.padding = '0';
                                target.style.overflow = 'hidden';
                                setTimeout(() => {
                                    target.remove();
                                    // Section emptied (server renders empty states) -> reload for correct view.
                                    if (wrapper && wrapper.querySelectorAll('.swiper-slide').length === 0) {
                                        window.location.reload();
                                    }
                                }, 300);
                            }, 400);
                        }
                        this.open = false;
                    },

                    saveToWatchLater() {
                        this.lastInteractionTime = Date.now();
                        const previousState = this.isInWatchLater;
                        this.isInWatchLater = !previousState; // Instant Lively Optimistic Update
                        this.updateCacheAndDispatch();

                        const message = this.isInWatchLater ? 'Saved to Watch Later' : 'Removed from Watch Later';
                        Swal.fire({ 
                            toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, 
                            icon: 'success', title: message,
                            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                        });

                        const endpoint = this.video.type === 'reel' 
                            ? `${window.location.origin}/reels/${this.video.id}/watch-later`
                            : `${window.location.origin}/videos/${this.video.id}/watch-later`;

                        fetch(endpoint, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }
                        }).then(async res => {
                            const data = await res.json().catch(() => ({ message: 'Server error' }));
                            if(!res.ok) throw new Error(data.message || 'Error saving to watch later');
                            return data;
                        }).then(data => {
                            this.isInWatchLater = (data.status === 'added');
                            
                            const wlPlaylist = this.playlists.find(p => p.name.toLowerCase().includes('watch later'));
                            if (wlPlaylist) {
                                wlPlaylist.is_member = this.isInWatchLater;
                                this.isInAnyPlaylist = this.playlists.some(p => p.is_member);
                                this.playlists = [...this.playlists];
                            }

                            this.updateCacheAndDispatch();

                            if (window.location.pathname.includes('watch-later') && data.status === 'removed') {
                                this._removeVideoCard(this.video.id, this.video.type);
                            }
                        }).catch(err => {
                            this.isInWatchLater = previousState; // Rollback
                            this.updateCacheAndDispatch();
                            Swal.fire({ icon: 'error', title: 'Oops...', text: err.message, toast: true, position: 'bottom', timer: 3000, showConfirmButton: false });
                        });
                    },

                    toggleLike() {
                        this.lastInteractionTime = Date.now();
                        const previousState = this.isLiked;
                        this.isLiked = !previousState; // Instant Lively Optimistic Update
                        this.updateCacheAndDispatch();

                        const message = this.isLiked ? 'Added to Liked videos' : 'Removed from Liked videos';
                        Swal.fire({ 
                            toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, 
                            icon: 'success', title: message,
                            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                        });

                        const endpoint = this.video.type === 'reel'
                            ? `${window.location.origin}/reels/${this.video.id}/like`
                            : `${window.location.origin}/videos/${this.video.id}/like`;

                        fetch(endpoint, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }
                        }).then(async res => {
                            const data = await res.json().catch(() => ({ message: 'Server error' }));
                            if(!res.ok) throw new Error(data.message || 'Error toggling like');
                            return data;
                        }).then(data => {
                            this.isLiked = data.liked;
                            this.updateCacheAndDispatch();

                            // Live remove from Liked Videos page if unliked
                            if (window.location.pathname.includes('liked-videos') && !data.liked) {
                                this._removeVideoCard(this.video.id, this.video.type);
                            }
                        }).catch(err => {
                            this.isLiked = previousState; // Rollback
                            this.updateCacheAndDispatch();
                            Swal.fire({ icon: 'error', title: 'Oops...', text: err.message, toast: true, position: 'bottom', timer: 3000, showConfirmButton: false });
                        });
                    },
                    
                    togglePlaylist(playlistId) {
                        this.lastInteractionTime = Date.now();
                        const playlist = this.playlists.find(p => p.id == playlistId);
                        if(!playlist) return;

                        const previousState = playlist.is_member;
                        const previousAnyPlaylist = this.isInAnyPlaylist;
                        playlist.is_member = !previousState; // Instant Optimistic Update
                        this.isInAnyPlaylist = this.playlists.some(p => p.is_member); // Update label live
                        this.playlists = Array.isArray(this.playlists) ? [...this.playlists] : []; // Force Alpine refresh

                        const endpoint = this.video.type === 'reel'
                            ? `${window.location.origin}/playlists/toggle-reel/${this.video.id}`
                            : `${window.location.origin}/videos/${this.video.id}/playlist-toggle`;

                        fetch(endpoint, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ playlist_id: playlistId })
                        }).then(async res => {
                            const data = await res.json().catch(() => ({ message: 'Server error' }));
                            if(!res.ok) throw new Error(data.message || 'Error updating playlist');
                            return data;
                        }).then(data => {
                            // Sync with actual server state
                            playlist.is_member = (data.status === 'added');
                            this.playlists = Array.isArray(this.playlists) ? [...this.playlists] : [];
                            this.isInAnyPlaylist = this.playlists.some(p => p.is_member);

                            this.updateCacheAndDispatch();

                            Swal.fire({ 
                                toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, 
                                icon: 'success', title: data.message,
                                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                            });

                            // Live remove from playlist detail page if removed
                            if (window.location.pathname.includes('playlist') && data.status === 'removed') {
                                this._removeVideoCard(this.video.id, this.video.type);
                            }

                        }).catch(err => {
                            playlist.is_member = previousState; // Rollback
                            this.isInAnyPlaylist = previousAnyPlaylist; // Rollback
                            this.playlists = Array.isArray(this.playlists) ? [...this.playlists] : [];
                            Swal.fire({ icon: 'error', title: 'Oops...', text: err.message, toast: true, position: 'bottom', timer: 3000, showConfirmButton: false });
                        });

                    },
                    
                    async createNewPlaylist() {
                        const { value: name } = await Swal.fire({
                            title: 'New Playlist',
                            input: 'text',
                            inputLabel: 'Give your playlist a name',
                            inputPlaceholder: 'Enter name...',
                            showCancelButton: true,
                            confirmButtonText: 'Create',
                            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                            inputAttributes: {
                                class: 'swal2-input !bg-gray-50 dark:!bg-white/5 !text-gray-900 dark:!text-white !rounded-xl !border-0 shadow-inner'
                            },
                            customClass: {
                                popup: 'rounded-[2rem] border border-gray-100 dark:border-white/5',
                                confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all',
                                cancelButton: 'bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-700 dark:text-gray-300 font-bold py-3 px-6 rounded-xl transition-all'
                            },
                            buttonsStyling: false
                        });

                        if(!name) return;

                        this.loading = true;
                        fetch('/playlists', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ name: name })
                        }).then(async res => {
                            const data = await res.json().catch(() => ({ message: 'Server error' }));
                            if(!res.ok) throw new Error(data.message || 'Error creating playlist');
                            return data;
                        }).then(data => {
                            this.playlists.unshift(data.playlist);
                            this.loading = false;
                            Swal.fire({ 
                                icon: 'success', title: 'Created!', text: data.message, toast: true, position: 'top-end', timer: 3000, showConfirmButton: false,
                                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                            });
                        }).catch(err => {
                            this.loading = false;
                            Swal.fire({ icon: 'error', title: 'Oops...', text: err.message, toast: true, position: 'bottom', timer: 3000, showConfirmButton: false });
                        });
                    },
                    
                    markNotInterested() {
                        if (!this.video || !this.video.id) return;
                        const videoId = this.video.id;
                        const videoType = this.video.type || 'video';

                        // 1. Instantly close modal and animate/remove card from DOM (Fraction of a second response)
                        this.open = false;
                        const cards = document.querySelectorAll(`[data-video-id="${videoId}"], [data-reel-id="${videoId}"], #item-${videoType}-${videoId}`);
                        cards.forEach(card => {
                            card.style.transition = 'all 0.2s ease-out';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.9)';
                            setTimeout(() => {
                                card.style.maxHeight = '0px';
                                card.style.margin = '0';
                                card.style.padding = '0';
                                card.style.overflow = 'hidden';
                                setTimeout(() => card.remove(), 150);
                            }, 150);
                        });

                        // 2. Show instant toast notification
                        Swal.fire({ 
                            toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, 
                            icon: 'success', title: 'Item hidden from feed',
                            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                        });

                        // 3. Fire background server request (non-blocking)
                        fetch(`${window.location.origin}/videos/${videoId}/not-interested`, {
                            method: 'POST',
                            headers: { 
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        }).catch(err => {
                            console.error('Error marking as not interested:', err);
                        });

                        // If on detail page, navigate back home smoothly
                        if (window.location.pathname.includes('/videos/' + videoId) || window.location.pathname.includes('/reels/' + videoId)) {
                            setTimeout(() => { window.location.href = '/'; }, 300);
                        }
                    },
                     removeFromHistory() {
                        if (!this.video) return;
                        const videoId = this.video.id;
                        const videoType = this.video.type;
                        const endpoint = videoType === 'reel'
                            ? `${window.location.origin}/history/remove/reel/${videoId}`
                            : `${window.location.origin}/history/remove/${videoId}`;

                        // Instant: close menu + animate the entry out in the same
                        // frame, then sync with the server in the background.
                        this._removeVideoCard(videoId, videoType);

                        Swal.fire({ 
                            toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, 
                            icon: 'success', title: 'Removed from history',
                            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                        });

                        fetch(endpoint, {
                            method: 'POST',
                            headers: { 
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(async res => {
                            if (!res.ok) throw new Error('Server error');
                            await res.json().catch(() => ({}));
                        })
                        .catch(err => {
                            console.error('Error removing from history:', err);
                            Swal.fire({ icon: 'error', title: 'Could not sync removal — refreshing', toast: true, position: 'top-end', timer: 2500, showConfirmButton: false });
                            window.location.reload();
                        });
                    }
                }));


                Alpine.data('reportModal', () => ({
                    reportOpen: false, 
                    reason: '', 
                    description: '',
                    submitting: false,
                    targetId: null,
                    targetType: 'video',
                    reasons: [
                        'Copyright Infringement',
                        'Inappropriate Content',
                        'Hate Speech',
                        'Harassment',
                        'Spam or Misleading',
                        'Violence',
                        'Other'
                    ],
                    init() {
                        window.addEventListener('open-report', (e) => {
                            this.targetId = e.detail.id;
                            this.targetType = e.detail.type || 'video';
                            this.reason = '';
                            this.description = '';
                            this.reportOpen = true;
                        });
                    },
                    submitReport() {
                        if (!this.reason) {
                            Swal.fire('Validation Error', 'Please select a reason for reporting.', 'warning');
                            return;
                        }
                        if (!this.targetId) {
                            Swal.fire('Error', 'Target ID is missing. Please refresh the page and try again.', 'error');
                            return;
                        }
                        if (this.reason === 'Other' && !this.description.trim()) {
                            Swal.fire('Validation Error', 'Please provide additional details.', 'warning');
                            return;
                        }
                        this.submitting = true;

                        let url = `${window.location.origin}/videos/${this.targetId}/report`;
                        if (this.targetType === 'channel') {
                            url = `${window.location.origin}/channels/${this.targetId}/report`;
                        } else if (this.targetType === 'reel') {
                            url = `${window.location.origin}/reels/${this.targetId}/report`;
                        }

                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ reason: this.reason, description: this.description })
                        })
                        .then(async res => {
                            const data = await res.json().catch(() => ({}));
                            if (!res.ok) {
                                throw new Error(data.message || 'Server error occurred');
                            }
                            return data;
                        })
                        .then(data => {
                            this.submitting = false;
                            this.reportOpen = false;
                            if(data.success) {
                                Swal.fire({
                                    title: 'Report Submitted',
                                    text: 'Thank you for helping us keep the community safe. Our moderators will review this shortly.',
                                    icon: 'success',
                                    confirmButtonColor: '#F97316'
                                });
                            } else {
                                Swal.fire('Error', data.message || 'Failed to submit report', 'error');
                            }
                        })
                        .catch((err) => {
                            this.submitting = false;
                            Swal.fire('Error', err.message || 'Network error. Please try again.', 'error');
                        });
                    }
                }));

                Alpine.data('consentModal', () => ({
                    showConsent: true, 
                    loading: false,
                    saveConsent() {
                        this.loading = true;
                        fetch('{{ route('user.save_consent') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        }).then(() => {
                            this.showConsent = false;
                            this.loading = false;
                        });
                    }
                }));

            });
            @else
            window.openVideoOptions = function() {
                Swal.fire({
                    title: 'Authentication Required',
                    text: 'Please log in to manage your playlists and Watch Later list.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Log In',
                    cancelButtonText: 'Maybe Later',
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            };
            @endif

        </script>
    </head>
    <body class="font-sans antialiased transition-colors duration-500 bg-white dark:bg-[#0F0F0F]">
        <!-- App Splash Screen -->
        @if(!request()->routeIs('videos.show'))
        <script>
            try {
                if (sessionStorage.getItem('splash_shown')) {
                    document.write('<style>#app-splash-screen { display: none !important; }</style>');
                }
            } catch (e) {
                console.warn('Session storage not accessible:', e);
            }
        </script>
        @php 
            $splashLogo = gs('splash_logo');
            if ($splashLogo && file_exists(public_path($splashLogo))) {
                $splashLogoUrl = asset($splashLogo) . '?v=' . filemtime(public_path($splashLogo));
            } else {
                $splashLogoUrl = siteLogo();
            }
        @endphp
        {{-- Preload the splash logo so the browser starts fetching it immediately --}}
        <link rel="preload" href="{{ $splashLogoUrl }}" as="image">
        <div id="app-splash-screen" class="fixed inset-0 z-[999999999] bg-white dark:bg-[#0F0F0F] flex flex-col items-center justify-between py-12 transition-opacity duration-500 ease-in-out">
            <div class="flex-1 flex flex-col items-center justify-center gap-4 md:gap-6">
                {{-- Actual logo image — Single Source of Truth --}}
                <img
                    id="splash-logo-img"
                    src="{{ $splashLogoUrl }}"
                    alt="{{ gs('site_name') }}"
                    class="w-28 md:w-36 lg:w-44 animate-pulse drop-shadow-2xl object-contain"
                >
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tighter uppercase">{{ gs('site_name') }}</h1>
            </div>
            <div class="flex flex-col items-center justify-center space-y-3 opacity-90 pb-4">
                <div class="flex items-center gap-2.5 px-4 py-2 rounded-2xl bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/5 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 225 150" class="rounded-[2px] shadow-sm shrink-0 border border-black/5">
                        <rect width="225" height="150" fill="#138808"/>
                        <rect width="225" height="100" fill="#fff"/>
                        <rect width="225" height="50" fill="#ff9933"/>
                        <circle cx="112.5" cy="75" r="20" fill="none" stroke="#000080" stroke-width="2"/>
                        <circle cx="112.5" cy="75" r="3.5" fill="#000080"/>
                        <g id="wheel">
                            <line x1="112.5" y1="55" x2="112.5" y2="95" stroke="#000080" stroke-width="1"/>
                            <line x1="92.5" y1="75" x2="132.5" y2="75" stroke="#000080" stroke-width="1"/>
                            <line x1="98.3" y1="60.8" x2="126.7" y2="89.2" stroke="#000080" stroke-width="1"/>
                            <line x1="98.3" y1="89.2" x2="126.7" y2="60.8" stroke="#000080" stroke-width="1"/>
                        </g>
                        <use href="#wheel" transform="rotate(15 112.5 75)"/>
                        <use href="#wheel" transform="rotate(30 112.5 75)"/>
                        <use href="#wheel" transform="rotate(45 112.5 75)"/>
                        <use href="#wheel" transform="rotate(60 112.5 75)"/>
                        <use href="#wheel" transform="rotate(75 112.5 75)"/>
                    </svg>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Made in India</span>
                </div>
                <div class="text-[9px] md:text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                    &copy; {{ date('Y') }} All Rights Reserved {{ gs('site_name') }}&trade;
                </div>
                <div class="w-12 h-0.5 bg-slate-200 dark:bg-white/10 rounded-full mt-2"></div>
            </div>
        </div>

        <script>
            // Ensure splash screen fades out after logo is ready (or after timeout)
            (function() {
                var splashDismissed = false;
                
                // If already shown in session, dismiss instantly and remove DOM element
                try {
                    if (sessionStorage.getItem('splash_shown')) {
                        splashDismissed = true;
                        var splash = document.getElementById('app-splash-screen');
                        if (splash) {
                            splash.remove();
                        }
                        return;
                    }
                } catch (e) {}

                function dismissSplash() {
                    if (splashDismissed) return;
                    splashDismissed = true;
                    try {
                        sessionStorage.setItem('splash_shown', 'true');
                    } catch (e) {}
                    var splash = document.getElementById('app-splash-screen');
                    if (splash) {
                        splash.style.opacity = '0';
                        setTimeout(function() {
                            splash.style.display = 'none';
                            splash.remove();
                        }, 500);
                    }
                }

                // Primary: dismiss once the DOM is parsed (don't wait for all images)
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(dismissSplash, 300);
                });

                // Safety fallback: dismiss after 1.5s no matter what
                setTimeout(dismissSplash, 1500);
            })();
        </script>
        @endif
        
        <!-- Image Cropper Modal -->
        <div id="cropper-modal" class="fixed inset-0 z-[9999999] hidden flex items-center justify-center p-4 bg-black/95">
            <div class="bg-white dark:bg-[#1A1A1A] w-full max-w-4xl rounded-[2.5rem] overflow-hidden shadow-2xl border border-gray-100 dark:border-white/5 flex flex-col max-h-[90vh]">
                <div class="p-6 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center">
                            <span class="material-symbols-rounded">crop</span>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-gray-900 dark:text-white uppercase tracking-tight ">Crop Image</h2>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Adjust your visual alignment</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeCropper()" class="w-10 h-10 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 text-gray-400 transition-colors flex items-center justify-center">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                
                <div class="flex-grow overflow-hidden bg-gray-50 dark:bg-black/40 flex items-center justify-center p-4">
                    <div class="max-w-full max-h-full">
                        <img id="cropper-image" src="" alt="To crop" class="max-w-full block">
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-100 dark:border-white/5 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="cropper.rotate(-90)" class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:scale-110 active:scale-95 transition-all">
                            <span class="material-symbols-rounded">rotate_left</span>
                        </button>
                        <button type="button" onclick="cropper.rotate(90)" class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:scale-110 active:scale-95 transition-all">
                            <span class="material-symbols-rounded">rotate_right</span>
                        </button>
                        <button type="button" onclick="cropper.setDragMode('move')" class="ml-4 w-12 h-12 rounded-2xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:scale-110 active:scale-95 transition-all">
                            <span class="material-symbols-rounded">pan_tool</span>
                        </button>
                        <button type="button" onclick="cropper.setDragMode('crop')" class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:scale-110 active:scale-95 transition-all">
                            <span class="material-symbols-rounded">crop_free</span>
                        </button>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <button type="button" onclick="closeCropper()" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                            Cancel
                        </button>
                        <button type="button" id="crop-apply-btn" class="px-10 py-4 gradient-orange text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm">check</span>
                            Apply Crop
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            if (localStorage.getItem('dark') === 'true') {
                document.documentElement.classList.add('dark');
            }

            function confirmDelete(url, message = "You won't be able to revert this!", confirmText = 'Yes, delete it!', method = 'DELETE') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: confirmText,
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;
                        
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);

                        if (method !== 'POST') {
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = method;
                            form.appendChild(methodInput);
                        }

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            function asyncRemoveItem(btn, url, method = 'POST') {
                // Find the closest card container and animate it out
                const card = btn.closest('div[id^="item-"], .swiper-slide');
                if (card) {
                    card.style.transition = 'all 0.3s ease-out';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        card.style.maxHeight = '0px';
                        card.style.margin = '0';
                        card.style.padding = '0';
                        card.style.overflow = 'hidden';
                        setTimeout(() => {
                            card.remove();
                            // If inside swiper, update it
                            const swiperEl = document.querySelector('.activitySwiper');
                            if(swiperEl && swiperEl.swiper) swiperEl.swiper.update();
                        }, 200);
                    }, 200);
                }

                // Show a quick non-intrusive toast
                Swal.fire({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, 
                    icon: 'success', title: 'Removed',
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                });

                // Fire background request
                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).catch(err => console.error('Error removing item:', err));
            }
        </script>
        <div class="min-h-screen bg-gray-50 dark:bg-[#0F0F0F] flex flex-col">
            @php
                $activeAnnouncements = \App\Models\Announcement::where('is_active', true)->latest()->get();
            @endphp

            @if($activeAnnouncements->count() > 0 && (request()->path() == '/' || request()->is('/')))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const announcements = @json($activeAnnouncements);
                        const lastShown = localStorage.getItem('announcement_last_shown');
                        const today = new Date().toDateString();
                        
                        console.log('Announcement System Initialized');
                        console.log('Last Shown:', lastShown, 'Today:', today);

                        if (lastShown !== today) {
                            let current = 0;
                            
                            function showAnnouncement(index) {
                                if (index >= announcements.length) {
                                    localStorage.setItem('announcement_last_shown', today);
                                    return;
                                }

                                const a = announcements[index];
                                Swal.fire({
                                    title: `<span class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight ">${a.title}</span>`,
                                    html: `<div class="text-xs font-bold text-slate-500 dark:text-white/60 uppercase tracking-widest leading-relaxed p-4">${a.message}</div>`,
                                    icon: 'info',
                                    confirmButtonText: 'Understood',
                                    confirmButtonColor: '#F97316',
                                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                    customClass: {
                                        popup: 'rounded-[2.5rem] border border-slate-100 dark:border-white/5 shadow-2xl',
                                        confirmButton: 'rounded-xl px-8 py-3 text-[10px] font-black uppercase tracking-widest'
                                    }
                                }).then(() => {
                                    showAnnouncement(index + 1);
                                });
                            }

                            showAnnouncement(0);
                        }
                    });
                </script>
            @endif
            
            @if(session('moderation_strike'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const strike = @json(session('moderation_strike'));
                        Swal.fire({
                            width: '380px',
                            padding: '0',
                            showConfirmButton: false,
                            background: 'transparent',
                            html: `
                                <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] overflow-hidden border border-slate-100 dark:border-white/5 shadow-2xl text-left">
                                    <!-- Header -->
                                    <div class="p-6 bg-slate-50/50 dark:bg-white/[0.02] border-b border-slate-100 dark:border-white/5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-600 to-rose-400 flex items-center justify-center shadow-lg shadow-rose-500/30 shrink-0">
                                                <span class="material-symbols-rounded text-white text-2xl">gavel</span>
                                            </div>
                                            <div>
                                                <h2 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight leading-none ">Strike Issued (${strike.strike_count}/3)</h2>
                                                <p class="text-[9px] font-bold text-rose-500/60 uppercase tracking-[0.2em] mt-1.5">Policy Violation Detected</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-6 space-y-4">
                                        ${strike.video_title ? `
                                        <div class="p-3.5 bg-slate-50 dark:bg-white/[0.03] rounded-2xl border border-slate-100 dark:border-white/5 mb-3">
                                            <div class="flex items-center gap-2 mb-2 opacity-40">
                                                <span class="material-symbols-rounded text-[14px]">videocam</span>
                                                <span class="text-[8px] font-black uppercase tracking-[0.1em] truncate">Affected Content</span>
                                            </div>
                                            <p class="text-[11px] font-bold text-slate-900 dark:text-white leading-normal uppercase tracking-tight truncate">${strike.video_title}</p>
                                        </div>
                                        ` : ''}

                                        <div class="p-3.5 bg-slate-50 dark:bg-white/[0.03] rounded-2xl border border-slate-100 dark:border-white/5 mb-3">
                                            <div class="flex items-center gap-2 mb-2 opacity-40">
                                                <span class="material-symbols-rounded text-[14px]">inventory_2</span>
                                                <span class="text-[8px] font-black uppercase tracking-[0.1em] truncate">Violation Reason</span>
                                            </div>
                                            <p class="text-[11px] font-semibold text-slate-600 dark:text-white/80 leading-normal">${strike.reason}</p>
                                        </div>

                                        <div class="bg-amber-500/10 rounded-2xl p-4 border border-amber-500/20 flex items-center gap-4">
                                            <div class="w-8 h-8 rounded-xl bg-amber-500 flex items-center justify-center shrink-0 shadow-lg shadow-amber-500/20">
                                                <span class="material-symbols-rounded text-white text-sm">warning</span>
                                            </div>
                                            <p class="text-[10px] font-black text-amber-700 dark:text-amber-400 uppercase tracking-tight leading-tight">Repeated violations (3 strikes) will result in permanent account closure.</p>
                                        </div>

                                        <button id="acknowledge-btn" class="w-full h-14 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-[0.25em] shadow-xl active:scale-95 transition-all mt-2">
                                            Acknowledge Notice
                                        </button>
                                    </div>
                                </div>
                            `,
                            customClass: {
                                container: 'p-4',
                                popup: 'bg-transparent border-0 shadow-none'
                            },
                            didOpen: () => {
                                document.getElementById('acknowledge-btn').addEventListener('click', () => {
                                    Swal.clickConfirm();
                                });
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(`/moderation/strike/${strike.id}/read`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Content-Type': 'application/json'
                                    }
                                });
                            }
                        });
                    });
                </script>
            @endif

            @if(session('moderation_notice'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const notice = @json(session('moderation_notice'));
                        Swal.fire({
                            width: '420px',
                            padding: '0',
                            showConfirmButton: false,
                            background: 'transparent',
                            allowOutsideClick: false,
                            html: `
                                <div class="bg-white dark:bg-[#111111] rounded-[3rem] overflow-hidden border border-slate-100 dark:border-white/5 shadow-2xl text-left relative">
                                    <!-- Branding -->
                                    <div class="absolute top-6 right-8 opacity-10">
                                        <span class="material-symbols-rounded text-6xl text-slate-400">security</span>
                                    </div>

                                    <!-- Header -->
                                    <div class="p-8 pb-6">
                                        <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center mb-6 shadow-inner">
                                            <span class="material-symbols-rounded text-3xl">info</span>
                                        </div>
                                        <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-none mb-2">${notice.action_type}</h2>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Official Content Decision</p>
                                    </div>

                                    <!-- Details -->
                                    <div class="px-8 pb-8 space-y-5">
                                        <!-- Content Box -->
                                        <div class="p-5 bg-slate-50 dark:bg-white/[0.03] rounded-3xl border border-slate-100 dark:border-white/5">
                                            <div class="flex items-center gap-2 mb-3 opacity-40 text-slate-500 dark:text-white">
                                                <span class="material-symbols-rounded text-[16px]">${notice.type === 'Reel' ? 'movie' : 'videocam'}</span>
                                                <span class="text-[9px] font-black uppercase tracking-[0.1em]">Affected ${notice.type}</span>
                                            </div>
                                            <p class="text-[13px] font-black text-slate-900 dark:text-white leading-snug uppercase tracking-tight line-clamp-2">${notice.content_title}</p>
                                        </div>

                                        <!-- Violation & Feedback Grid -->
                                        <div class="grid grid-cols-1 gap-4">
                                            <div class="space-y-1.5">
                                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Reason</span>
                                                <div class="p-4 bg-slate-50 dark:bg-white/[0.02] rounded-2xl border border-slate-100 dark:border-white/5">
                                                    <p class="text-[11px] font-bold text-slate-600 dark:text-slate-300 leading-relaxed">${notice.reason}</p>
                                                </div>
                                            </div>

                                            ${notice.feedback ? `
                                            <div class="space-y-1.5">
                                                <span class="text-[9px] font-black text-blue-500 uppercase tracking-widest px-1">Admin Feedback</span>
                                                <div class="p-4 bg-blue-500/5 dark:bg-blue-500/10 rounded-2xl border border-blue-500/20">
                                                    <p class="text-[11px] font-bold text-blue-700 dark:text-blue-400 leading-relaxed ">"${notice.feedback}"</p>
                                                </div>
                                            </div>
                                            ` : ''}
                                        </div>

                                        <!-- Footer Note -->
                                        <div class="pt-2">
                                            <p class="text-[9px] font-medium text-slate-400 leading-relaxed">
                                                This decision was made based on our community standards. If you believe this is an error, please contact support.
                                            </p>
                                        </div>

                                        <!-- Button -->
                                        <button id="notice-acknowledge-btn" class="w-full h-16 bg-[#0F0F0F] dark:bg-white text-white dark:text-black rounded-2xl text-[11px] font-black uppercase tracking-[0.25em] shadow-xl active:scale-95 transition-all mt-2 group flex items-center justify-center gap-3">
                                            <span>I Understand</span>
                                            <span class="material-symbols-rounded text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                        </button>
                                    </div>
                                </div>
                            `,
                            customClass: {
                                container: 'p-4 backdrop-blur-sm',
                                popup: 'bg-transparent border-0 shadow-none'
                            },
                            didOpen: () => {
                                document.getElementById('notice-acknowledge-btn').addEventListener('click', () => {
                                    Swal.clickConfirm();
                                });
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(`/moderation/notice/${notice.type}/${notice.id}/read`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Content-Type': 'application/json'
                                    }
                                });
                            }
                        });
                    });
                </script>
            @endif

            @if(!request()->routeIs('marketplace.dashboard') && !request()->routeIs('login') && !request()->routeIs('register') && !request()->routeIs('password.*'))
            <div id="navigation-header" class="{{ (request()->routeIs('reels.create') || request()->routeIs('reels.index') || request()->routeIs('studio.*')) ? 'hidden lg:block' : '' }}">
                @include('layouts.header')
            </div>
            @endif

            {{-- Auth Header (Clean version for login/register) --}}
            @if(request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('password.*'))
            <div id="navigation-header" class="pointer-events-auto">
                @include('layouts.header')
            </div>
            @endif

            @if(request()->routeIs('user.*') || request()->routeIs('studio.*'))
            <!-- Mobile/Tablet Studio Toolbar (Root Level for Global Z-Index) -->
            <div class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-[#0F0F0F]/80 backdrop-blur-2xl border-b border-gray-100 dark:border-white/5 px-4 py-3">
                <div class="flex items-center gap-4">
                    <button onclick="window.dispatchEvent(new CustomEvent('toggle-sidebar'))" class="px-3 py-1.5 sm:px-4 sm:py-2 md:px-5 md:py-2.5 rounded-xl md:rounded-2xl gradient-orange text-white flex items-center gap-1.5 sm:gap-2 md:gap-3 shadow-xl shadow-orange-500/20 transition-all active:scale-95 border border-white/10">
                        <span class="material-symbols-rounded text-base sm:text-lg md:text-xl text-white">menu</span>
                        <span class="text-[9px] sm:text-[10px] md:text-[11px] font-black uppercase tracking-widest text-white">Menu</span>
                    </button>
                    <div class="flex items-center gap-2">
                        @if(auth()->check() && auth()->user()->isCreator())
                            <span class="material-symbols-rounded text-lg text-gradient-orange">grid_view</span>
                            <span class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-[0.2em]">Creator Studio</span>
                        @else
                            <span class="material-symbols-rounded text-lg text-red-500">workspace_premium</span>
                            <span class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-[0.2em]">Premium Plans</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="flex flex-1">
                @if(!request()->routeIs('marketplace.dashboard') && !request()->routeIs('login') && !request()->routeIs('register') && !request()->routeIs('password.*'))
                <div class="{{ request()->routeIs('reels.create') ? 'hidden lg:block' : '' }}">
                    @include('layouts.sidebar')
                </div>
                @endif

                <!-- Page Content -->
                <main class="flex-1 bg-gray-50/20 dark:bg-[#0F0F0F] 
                    {{ request()->routeIs('reels.index') ? 'lg:ml-0' : 
                       (request()->routeIs('reels.create') ? 'lg:ml-72 pt-0 lg:pt-20' :
                       (request()->routeIs('marketplace.dashboard') ? 'lg:ml-0 h-[100dvh] lg:h-screen overflow-hidden' : 
                       (request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('password.*') ? 'lg:ml-0 pt-14 lg:pt-20 flex flex-col items-center justify-center min-h-[calc(100vh-80px)]' : 'lg:ml-72 pt-14 lg:pt-20 min-h-screen'))) 
                    }} overflow-x-clip pb-10 transition-colors duration-500">


                    {{ $slot ?? '' }}
                    
                    @yield('content')
                    
                    <!-- Bottom Nav Spacer -->
                    <div class="lg:hidden h-1"></div>
                </main>
            </div>

            @if(!request()->routeIs('marketplace.dashboard'))
            @include('layouts.footer')
            @endif

            @include('layouts.bottom-nav')
        </div>

        @include('components.mini-ott-coming-soon-modal')

        @include('partials.notify')

        <!-- Mobile/Tablet Sidebar Drawer -->
        @if(request()->routeIs('user.*') || request()->routeIs('studio.*'))
        <div x-data="{ sidebarOpen: false }"
             @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
             id="mobile-sidebar-wrapper">

            <!-- Backdrop -->
            <div x-show="sidebarOpen" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 class="lg:hidden fixed inset-0 bg-black/60 backdrop-blur-sm"
                 style="z-index: 9998;">
            </div>

            <!-- Drawer Panel -->
            <div x-show="sidebarOpen" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="lg:hidden fixed left-0 top-0 bottom-0 w-[260px] bg-white dark:bg-[#0F0F0F] border-r border-gray-100 dark:border-white/5 overflow-y-auto custom-scrollbar flex flex-col shadow-[20px_0_60px_rgba(0,0,0,0.5)]"
                 style="z-index: 9999;">

                <!-- Close Header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-white/5 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-rounded text-gradient-orange text-xl">grid_view</span>
                        <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Studio</span>
                    </div>
                    <button @click="sidebarOpen = false" class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-400 hover:text-orange-500 hover:bg-orange-50 dark:hover:bg-orange-500/10 transition-all active:scale-90">
                        <span class="material-symbols-rounded text-xl">close</span>
                    </button>
                </div>

                <!-- Sidebar Content -->
                <div class="flex-1 overflow-y-auto custom-scrollbar" @click="sidebarOpen = false">
                    @include('frontend.partials.studio_sidebar')
                </div>
            </div>
        </div>
        @endif

        @auth
        <!-- Global Video Options Layer -->
        <div x-data="videoOptions" 
             x-init="window.addEventListener('trigger-video-options', (e) => handleTrigger(e.detail))"
             id="global-video-options">

            
            <!-- Mobile Bottom Sheet -->
            <template x-teleport="body">
                <div x-show="open" x-cloak class="lg:hidden fixed inset-0 z-[99999]">
                    <div x-show="open" @click="open = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                    <div x-show="open" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" class="absolute bottom-0 left-0 right-0 bg-white dark:bg-[#1A1A1A] rounded-t-[2.5rem] p-6 pb-12 shadow-2xl overflow-hidden shadow-black/50">
                        <div class="w-12 h-1.5 bg-gray-200 dark:bg-white/10 rounded-full mx-auto mb-8"></div>
                        
                        <div x-show="view === 'options'" class="space-y-2">
                            <div class="flex items-center justify-between mb-4 px-2 shrink-0">
                                <h4 class="text-xs font-black text-gray-400 dark:text-white/20 uppercase tracking-[0.2em] truncate mr-4" x-text="video?.title"></h4>
                                <button @click="open = false" class="w-8 h-8 shrink-0 rounded-full gradient-orange text-white flex items-center justify-center shadow-lg shadow-orange-500/20 transition-all active:scale-90 group">
                                    <span class="material-symbols-rounded text-[16px] group-active:rotate-90 transition-transform">close</span>
                                </button>
                            </div>
                             <button @click="saveToWatchLater" 
                                     :class="isInWatchLater ? 'bg-red-50 dark:bg-red-500/10 text-red-600' : 'text-gray-700 dark:text-gray-200'"
                                     class="w-full h-14 rounded-2xl flex items-center gap-4 px-4 hover:bg-gray-50 dark:hover:bg-white/5 active:scale-95 transition-all">
                                <span class="material-symbols-rounded text-2xl" :class="isInWatchLater ? 'text-red-500' : 'opacity-60'" x-text="isInWatchLater ? 'remove_circle' : 'schedule'"></span>
                                <span class="font-bold" x-text="isInWatchLater ? 'Remove from Watch Later' : 'Save to Watch Later'"></span>
                            </button>

                            <button @click="toggleLike" 
                                     :class="isLiked ? 'bg-red-50 dark:bg-red-500/10 text-red-600' : 'text-gray-700 dark:text-gray-200'"
                                     class="w-full h-14 rounded-2xl flex items-center gap-4 px-4 hover:bg-gray-50 dark:hover:bg-white/5 active:scale-95 transition-all">
                                <span class="material-symbols-rounded text-2xl" :class="isLiked ? 'text-red-500' : 'opacity-60'" x-text="isLiked ? 'heart_minus' : 'favorite'"></span>
                                <span class="font-bold" x-text="isLiked ? 'Remove from Liked Videos' : 'Like Video'"></span>
                            </button>

                            <button @click="view = 'playlists'" 
                                     :class="isInAnyPlaylist ? 'bg-green-50 dark:bg-green-500/10 text-green-600' : 'text-gray-700 dark:text-gray-200'"
                                     class="w-full h-14 rounded-2xl flex items-center gap-4 px-4 hover:bg-gray-50 dark:hover:bg-white/5 active:scale-95 transition-all">
                                <span class="material-symbols-rounded text-2xl" :class="isInAnyPlaylist ? 'text-green-500' : 'opacity-60'" x-text="isInAnyPlaylist ? 'playlist_add_check' : 'playlist_add'"></span>
                                <span class="font-bold" x-text="isInAnyPlaylist ? 'Manage Playlists' : 'Save to playlist'"></span>
                            </button>

                            <button x-show="String(video.userId) !== String(currentUserId)" @click="markNotInterested()" class="w-full p-4 rounded-2xl flex items-center gap-4 hover:bg-gray-50 dark:hover:bg-white/5 transition-all text-gray-700 dark:text-gray-300 font-bold group">
                                <div class="w-12 h-12 rounded-xl bg-gray-500/10 text-gray-500 flex items-center justify-center group-hover:bg-gray-500 group-hover:text-white transition-all shadow-sm">
                                    <span class="material-symbols-rounded">do_not_disturb_on</span>
                                </div>
                                <div class="flex flex-col items-start text-left">
                                    <span class="text-[13px]">Not interested</span>
                                    <span class="text-[10px] opacity-40 uppercase tracking-tighter">Hide this video from feed</span>
                                </div>
                            </button>

                            <template x-if="window.location.pathname.includes('history')">
                                <button @click="removeFromHistory()" class="w-full p-4 rounded-2xl flex items-center gap-4 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all text-red-600 font-bold group border border-transparent hover:border-red-500/20">
                                    <div class="w-12 h-12 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-all shadow-sm">
                                        <span class="material-symbols-rounded">delete_history</span>
                                    </div>
                                    <div class="flex flex-col items-start text-left">
                                        <span class="text-[13px]">Remove from History</span>
                                        <span class="text-[10px] opacity-60 uppercase tracking-tighter">Delete watch log</span>
                                    </div>
                                </button>
                            </template>

                            <button x-show="String(video.userId) !== String(currentUserId)" @click="open = false; window.openReportModal(video.id)" class="w-full p-4 rounded-2xl flex items-center gap-4 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all text-red-600 font-bold group border border-transparent hover:border-red-500/20">
                                <div class="w-12 h-12 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-all shadow-sm">
                                    <span class="material-symbols-rounded">flag</span>
                                </div>
                                <div class="flex flex-col items-start text-left">
                                    <span class="text-[13px]">Report Content</span>
                                    <span class="text-[10px] opacity-60 uppercase tracking-tighter">Flag for administrative review</span>
                                </div>
                            </button>
                        </div>

                        <div x-show="view === 'playlists'" class="space-y-4">
                             <div class="flex items-center justify-between mb-4 shrink-0">
                                 <div class="flex items-center gap-4">
                                     <button @click="view = 'options'" class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                                         <span class="material-symbols-rounded text-xl">arrow_back</span>
                                     </button>
                                     <span class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">Save to playlist</span>
                                 </div>
                                 <button @click="open = false" class="w-8 h-8 shrink-0 rounded-full gradient-orange text-white flex items-center justify-center shadow-lg shadow-orange-500/20 transition-all active:scale-90 group">
                                     <span class="material-symbols-rounded text-[16px] group-active:rotate-90 transition-transform">close</span>
                                 </button>
                             </div>
                             <div class="max-h-60 overflow-y-auto space-y-2">
                                 <template x-for="pl in playlists" :key="pl.id">
                                     <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-white/5 rounded-2xl">
                                         <span class="font-bold text-sm text-gray-700 dark:text-gray-200" x-text="pl.name"></span>
                                          <button @click="togglePlaylist(pl.id)" :class="pl.is_member ? 'bg-red-500/10 text-red-500' : 'gradient-orange text-white'" class="px-4 py-2 rounded-xl flex items-center gap-2 shadow-lg transition-all active:scale-90">
                                             <span class="material-symbols-rounded text-sm" x-text="pl.is_member ? 'remove_circle' : 'add'"></span>
                                             <span class="text-[10px] font-black uppercase tracking-widest" x-text="pl.is_member ? 'Remove' : 'Add'"></span>
                                         </button>



                                     </div>
                                 </template>
                             </div>
                             <div class="pt-4 border-t border-gray-100 dark:border-white/5">
                                 <button @click="createNewPlaylist()" class="w-full mt-4 p-4 rounded-2xl border-2 border-dashed border-orange-500/30 dark:border-orange-500/20 text-orange-600 dark:text-orange-500 font-bold flex items-center justify-center gap-2 hover:bg-orange-50 dark:hover:bg-orange-500/5 transition-all">
                                     <span class="material-symbols-rounded">add_circle</span>
                                     Create new playlist
                                 </button>
                             </div>
                        </div>
                    </div>
                </div>
            </template>
                        <!-- Desktop Context Modal (Compact & Centered) -->
            <template x-teleport="body">
                <div x-show="open" x-cloak class="hidden lg:flex fixed inset-0 z-[99999] items-center justify-center p-4">
                    <!-- Backdrop with heavy blur -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="absolute inset-0 bg-black/40 backdrop-blur-md" 
                         @click="open = false"></div>

                    <!-- Compact Modal Card -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="relative w-[380px] bg-white dark:bg-[#121212] rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
                        
                        <!-- Header -->
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-white/5 flex items-center justify-between shrink-0">
                            <div class="flex flex-col min-w-0">
                                <span class="text-[9px] font-black text-orange-500 uppercase tracking-widest">Options</span>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate" x-text="video?.title"></h4>
                            </div>
                            <button @click="open = false" class="w-8 h-8 shrink-0 rounded-full gradient-orange text-white flex items-center justify-center hover:scale-110 shadow-lg shadow-orange-500/20 transition-all group">
                                <span class="material-symbols-rounded text-lg group-hover:rotate-90 transition-transform">close</span>
                            </button>
                        </div>
                        
                        <!-- Scrollable Content -->
                        <div class="flex-1 overflow-y-auto custom-scrollbar p-3 space-y-1">
                            <div x-show="view === 'options'" class="space-y-1">
                                <button @click="saveToWatchLater" class="w-full p-3 rounded-2xl flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-white/5 transition-all text-gray-700 dark:text-gray-300 group">
                                    <div :class="isInWatchLater ? 'bg-red-500/10 text-red-500 group-hover:bg-red-500' : 'bg-blue-500/10 text-blue-500 group-hover:bg-blue-500'" class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:text-white transition-all">
                                        <span class="material-symbols-rounded text-lg" x-text="isInWatchLater ? 'remove_circle' : 'schedule'"></span>
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <span class="text-[12px] font-bold" x-text="isInWatchLater ? 'Remove from Watch Later' : 'Save to Watch Later'"></span>
                                        <span class="text-[9px] opacity-50 uppercase tracking-tighter" x-text="isInWatchLater ? 'Currently in list' : 'Add to private list'"></span>
                                    </div>
                                </button>

                                <button @click="toggleLike" class="w-full p-3 rounded-2xl flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-white/5 transition-all text-gray-700 dark:text-gray-300 group">
                                    <div :class="isLiked ? 'bg-red-500/10 text-red-500 group-hover:bg-red-500' : 'bg-pink-500/10 text-pink-500 group-hover:bg-pink-500'" class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:text-white transition-all">
                                        <span class="material-symbols-rounded text-lg" x-text="isLiked ? 'heart_minus' : 'favorite'"></span>
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <span class="text-[12px] font-bold" x-text="isLiked ? 'Remove from Liked Videos' : 'Like Video'"></span>
                                        <span class="text-[9px] opacity-50 uppercase tracking-tighter" x-text="isLiked ? 'Already liked' : 'Add to favorites'"></span>
                                    </div>
                                </button>

                                <button @click="view = 'playlists'" class="w-full p-3 rounded-2xl flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-white/5 transition-all text-gray-700 dark:text-gray-300 group">
                                    <div :class="isInAnyPlaylist ? 'bg-green-500/10 text-green-500 group-hover:bg-green-500' : 'bg-orange-500/10 text-orange-500 group-hover:bg-orange-500'" class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:text-white transition-all">
                                        <span class="material-symbols-rounded text-lg" x-text="isInAnyPlaylist ? 'playlist_add_check' : 'playlist_add'"></span>
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <span class="text-[12px] font-bold" x-text="isInAnyPlaylist ? 'Manage Playlists' : 'Save to Playlist'"></span>
                                        <span class="text-[9px] opacity-50 uppercase tracking-tighter" x-text="isInAnyPlaylist ? 'Organize content' : 'Add to collection'"></span>
                                    </div>
                                </button>

                                <button x-show="String(video.userId) !== String(currentUserId)" @click="markNotInterested()" class="w-full p-3 rounded-2xl flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-white/5 transition-all text-gray-700 dark:text-gray-300 group">
                                    <div class="w-10 h-10 rounded-xl bg-gray-500/10 text-gray-500 flex items-center justify-center group-hover:bg-gray-500 group-hover:text-white transition-all">
                                        <span class="material-symbols-rounded text-lg">do_not_disturb_on</span>
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <span class="text-[12px] font-bold">Not Interested</span>
                                        <span class="text-[9px] opacity-50 uppercase tracking-tighter">Hide from feed</span>
                                    </div>
                                </button>

                                <template x-if="window.location.pathname.includes('history')">
                                    <div class="space-y-1">
                                        <button @click="removeFromHistory()" class="w-full p-3 rounded-2xl flex items-center gap-3 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all text-red-600 font-bold group">
                                            <div class="w-10 h-10 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-all">
                                                <span class="material-symbols-rounded text-lg">delete_history</span>
                                            </div>
                                            <div class="flex flex-col items-start text-left">
                                                <span class="text-[12px]">Remove from History</span>
                                                <span class="text-[9px] opacity-60 uppercase tracking-tighter">Delete log</span>
                                            </div>
                                        </button>
                                        <button x-show="String(video.userId) !== String(currentUserId)" @click="open = false; window.openReportModal(video.id)" class="w-full p-3 rounded-2xl flex items-center gap-3 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all text-red-600 font-bold group">
                                            <div class="w-10 h-10 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-all">
                                                <span class="material-symbols-rounded text-lg">flag</span>
                                            </div>
                                            <div class="flex flex-col items-start text-left">
                                                <span class="text-[12px]">Report Content</span>
                                                <span class="text-[9px] opacity-60 uppercase tracking-tighter">Flag for review</span>
                                            </div>
                                        </button>
                                    </div>
                                </template>                                <template x-if="!window.location.pathname.includes('history')">
                                    <button x-show="String(video.userId) !== String(currentUserId)" @click="open = false; window.openReportModal(video.id)" class="w-full p-3 rounded-2xl flex items-center gap-3 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all text-red-600 font-bold group">
                                        <div class="w-10 h-10 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-all">
                                            <span class="material-symbols-rounded text-lg">flag</span>
                                        </div>
                                        <div class="flex flex-col items-start text-left">
                                            <span class="text-[12px]">Report Content</span>
                                            <span class="text-[9px] opacity-60 uppercase tracking-tighter">Flag for review</span>
                                        </div>
                                    </button>
                                </template>
                            </div>

                            <div x-show="view === 'playlists'" class="space-y-4">
                                <div class="flex items-center gap-3 mb-2 shrink-0">
                                    <button @click="view = 'options'" class="w-8 h-8 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-white/10 transition-all active:scale-90">
                                        <span class="material-symbols-rounded text-lg">arrow_back</span>
                                    </button>
                                    <span class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-widest">Save to playlist</span>
                                </div>
                                <div class="space-y-2 overflow-y-auto custom-scrollbar pr-1 max-h-[300px]">
                                    <template x-for="pl in playlists" :key="pl.id">
                                        <div class="w-full p-3 bg-gray-50 dark:bg-white/5 rounded-2xl flex items-center justify-between transition-all">
                                            <div class="flex flex-col items-start">
                                                <span class="font-bold text-gray-700 dark:text-gray-300 text-xs" x-text="pl.name"></span>
                                                <span class="text-[8px] uppercase tracking-widest font-black" :class="pl.is_member ? 'text-green-500' : 'text-gray-400'" x-text="pl.is_member ? 'Member' : 'Not Member'"></span>
                                            </div>
                                            <button @click="togglePlaylist(pl.id)" 
                                                    :class="pl.is_member ? 'bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white' : 'gradient-orange text-white hover:scale-105'" 
                                                    class="px-3 py-1.5 rounded-lg flex items-center gap-1.5 shadow-lg transition-all active:scale-95 text-[10px] font-bold uppercase">
                                                <span class="material-symbols-rounded text-sm" x-text="pl.is_member ? 'remove_circle' : 'add'"></span>
                                                <span x-text="pl.is_member ? 'Remove' : 'Add'"></span>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <button @click="createNewPlaylist()" class="w-full py-3 shrink-0 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl text-gray-500 dark:text-gray-400 font-bold flex items-center justify-center gap-2 hover:bg-gray-50 dark:hover:bg-white/5 transition-all text-xs">
                                    <span class="material-symbols-rounded text-sm">add</span>
                                    New Playlist
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
             </div> <!-- x-data videoOptions -->
    @endauth

    <form id="delete-form-global" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    <form id="premium-form-global" method="POST" style="display:none;">
        @csrf
        @method('POST')
        <input type="hidden" name="price" id="premium-price-input-global">
    </form>

    @include('partials.firebase_script')
    @include('partials.global_mini_player')

    <script>
        window.showLoginAlert = function(action = 'perform this action') {
            Swal.fire({
                title: 'Sign in required',
                text: `Please sign in to ${action}.`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#ff571a',
                cancelButtonColor: '#272727',
                confirmButtonText: 'Sign In',
                background: document.documentElement.classList.contains('dark') ? '#0F0F0F' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        }

        async function updateComment(id, content, isReel = false) {
            if (!content.trim()) return;
            const url = isReel ? `/reels/comments/${id}` : `/comments/${id}`;
            try {
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ content: content })
                });
                const data = await response.json();
                if (data.status === 'success' || data.success === true) {
                    return data.content;
                } else {
                    if (data.message && data.message.toLowerCase().includes('blocked')) {
                        Swal.fire({
                            width: '360px',
                            html: `
                                <div class="flex flex-col items-center text-center p-2">
                                    <div class="w-16 h-16 rounded-2xl bg-rose-50 dark:bg-rose-500/10 text-rose-500 flex items-center justify-center mb-5 shadow-inner">
                                        <span class="material-symbols-rounded text-4xl">block</span>
                                    </div>
                                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2 tracking-tight">Comment Blocked</h2>
                                    <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.2em] mb-6">Community Guidelines</p>
                                    
                                    <div class="w-full p-5 bg-slate-50 dark:bg-white/[0.03] rounded-2xl border border-slate-100 dark:border-white/5 mb-2">
                                        <p class="text-xs font-bold text-slate-600 dark:text-white/80 leading-relaxed">
                                            ${data.message}
                                        </p>
                                    </div>
                                </div>
                            `,
                            confirmButtonText: 'I Understand',
                            confirmButtonColor: '#0F172A',
                            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                            customClass: {
                                popup: 'rounded-[2.5rem] border border-slate-100 dark:border-white/5 shadow-2xl p-6',
                                confirmButton: 'rounded-xl px-10 py-3.5 text-[11px] font-black uppercase tracking-[0.2em] w-full shadow-lg shadow-slate-900/20'
                            }
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: data.message || 'Error updating comment',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                }
            } catch (e) {
                console.error(e);
            }
            return null;
        }

        async function deleteComment(id, isReel = false) {
            const result = await Swal.fire({
                title: 'Delete Comment?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Delete',
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });

            if (result.isConfirmed) {
                const url = isReel ? `/reels/comments/${id}` : `/comments/${id}`;
                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        const commentEls = document.querySelectorAll(`[data-comment-id="${id}"]`);
                        commentEls.forEach(el => {
                            el.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                            el.style.opacity = '0';
                            el.style.transform = 'translateY(-10px) scale(0.95)';
                            setTimeout(() => el.remove(), 400);
                        });
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Comment deleted',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // Dispatch event for other components to update counts if needed
                        window.dispatchEvent(new CustomEvent('comment-deleted', { detail: { id: id, comments_count: data.comments_count, isReel: isReel } }));
                    }
                } catch (e) {
                    console.error(e);
                }
            }
        }

        async function blockUser(userId, commentText = null) {
            const result = await Swal.fire({
                title: 'Block User?',
                text: "You won't see comments from this user anymore.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Block',
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('/user/block', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ 
                            blocked_user_id: userId,
                            comment_text: commentText
                        })
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                        setTimeout(() => {
                            window.dispatchEvent(new CustomEvent('user-blocked', { detail: { userId: userId } }));
                        }, 500);
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                } catch (e) {
                    console.error(e);
                }
            }
        }

        window.addEventListener('user-blocked', (e) => {
            const userId = e.detail.userId;
            // Remove DOM elements for videos
            document.querySelectorAll(`.comment-user-${userId}`).forEach(el => el.remove());
        });

        async function reportComment(commentId) {
            const { value: reason } = await Swal.fire({
                title: 'Report Comment',
                input: 'select',
                inputOptions: {
                    'Spam': 'Spam',
                    'Harassment or Bullying': 'Harassment or Bullying',
                    'Hate Speech': 'Hate Speech',
                    'False Information': 'False Information',
                    'Inappropriate Content': 'Inappropriate Content',
                    'Violence or Threats': 'Violence or Threats',
                    'Adult Content': 'Adult Content',
                    'Other': 'Other'
                },
                inputPlaceholder: 'Select a reason',
                showCancelButton: true,
                confirmButtonColor: '#ff571a',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Report',
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value) {
                            resolve();
                        } else {
                            resolve('You need to select a reason');
                        }
                    });
                }
            });

            if (reason) {
                try {
                    const response = await fetch('/comment/report', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ comment_id: commentId, reason: reason })
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        Swal.fire('Error', data.message || 'Error submitting report', 'error');
                    }
                } catch (e) {
                    console.error(e);
                }
            }
        }

        async function likeComment(id, isReel = false) {
            @guest
                showLoginAlert('like this comment');
                return;
            @endguest
            const url = isReel ? `/reels/comments/${id}/like` : `/comments/${id}/like`;
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();
                
                // Sync all instances of this comment
                window.dispatchEvent(new CustomEvent('comment-like-updated', { 
                    detail: { id: id, liked: data.liked, likes: data.likes_count } 
                }));
            } catch (e) {
                console.error(e);
            }
        }

        async function togglePinComment(id, isReel = false) {
            const url = isReel ? `/reels/comments/${id}/toggle-pin` : `/comments/${id}/toggle-pin`;
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();
                if (data.status === 'success') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 3000,
                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000'
                    });
                    
                    if (isReel) {
                        window.dispatchEvent(new CustomEvent('reel-comments-refreshed'));
                    } else if (window.refreshCommentsList) {
                        window.refreshCommentsList();
                    } else {
                        window.location.reload();
                    }
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: data.message || 'Error updating comment status',
                        showConfirmButton: false,
                        timer: 3000,
                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000'
                    });
                }
            } catch (e) {
                console.error(e);
            }
        }

        window.openReportModal = function(id) {
            @auth
                window.dispatchEvent(new CustomEvent('open-report', { detail: { id: id } }));
            @else
                showLoginAlert('report this content');
            @endauth
        }
    </script>

    <!-- Global Report Modal -->
    @auth
    <div x-data="reportModal" 
        x-show="reportOpen" 
        class="fixed inset-0 z-[300000] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
        x-cloak>
        <div class="relative w-full max-w-md max-h-[90vh] overflow-y-auto custom-scrollbar bg-white dark:bg-[#1A1A1A] rounded-[3rem] shadow-2xl border border-white/10 p-8" @click.away="reportOpen = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Report Content</h2>
                <button @click="reportOpen = false" class="w-8 h-8 rounded-full bg-gradient-to-tr from-orange-500 to-rose-600 text-white flex items-center justify-center shadow-lg shadow-orange-500/20 active:scale-90 transition-transform">
                    <span class="material-symbols-rounded text-base">close</span>
                </button>
            </div>
            <p class="text-[10px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-8">Tell us what's wrong with this content</p>

            <div class="space-y-2 mb-8">
                <template x-for="r in reasons">
                    <button @click="reason = r" 
                            class="w-full p-4 rounded-2xl border transition-all text-left flex items-center justify-between group"
                            :class="reason === r ? 'bg-red-500/10 border-red-500 text-red-500 shadow-lg shadow-red-500/10' : 'bg-slate-50 dark:bg-white/5 border-transparent dark:border-white/5 text-slate-600 dark:text-white/60 hover:bg-slate-100 dark:hover:bg-white/10'">
                        <span class="text-xs font-bold" x-text="r"></span>
                        <div class="w-5 h-5 rounded-full border-2 border-current flex items-center justify-center opacity-40" :class="reason === r ? 'opacity-100 bg-red-500 border-red-500' : ''">
                            <span class="material-symbols-rounded text-[14px] text-white" x-show="reason === r">check</span>
                        </div>
                    </button>
                </template>
            </div>

            <!-- Other Reason Textarea -->
            <div x-show="reason === 'Other'" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mb-8">
                <label class="text-[10px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-2 block">Additional Details</label>
                <textarea x-model="description" 
                          placeholder="Please provide more information about your report..."
                          class="w-full h-32 p-4 rounded-2xl bg-slate-50 dark:bg-white/5 border-2 border-transparent focus:border-red-500 transition-all text-slate-600 dark:text-white text-xs resize-none outline-none"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" @click="reportOpen = false" class="flex-1 py-4 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all">Cancel</button>
                <button type="button" @click="submitReport()" 
                        :disabled="!reason || submitting"
                        class="flex-[2] py-4 rounded-2xl bg-gradient-to-r from-rose-600 to-red-600 text-white text-[10px] font-black uppercase tracking-widest shadow-xl shadow-rose-500/20 disabled:opacity-50 flex items-center justify-center gap-2 active:scale-95 transition-all">
                    <template x-if="submitting">
                        <span class="w-3 h-3 border-2 border-white/20 border-t-white rounded-full animate-spin"></span>
                    </template>
                    <span x-text="submitting ? 'Submitting...' : 'Submit Report'"></span>
                </button>
            </div>
        </div>
    </div>
    @endauth
    @auth
        @if(!auth()->user()->data_consent)
            <div x-data="consentModal" 
                 x-show="showConsent" 
                 x-cloak
                 class="fixed inset-0 z-[200000] flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm">
                <div class="bg-white dark:bg-[#121212] rounded-2xl border border-slate-200 dark:border-white/10 p-5 sm:p-6 shadow-2xl max-w-sm w-full relative overflow-hidden transform transition-all"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    
                    <div class="absolute -top-20 -right-20 w-48 h-48 bg-orange-600/10 rounded-full blur-[60px] pointer-events-none"></div>

                    <div class="relative z-10 text-center">
                        <div class="w-12 h-12 rounded-xl gradient-orange flex items-center justify-center text-white shadow-lg mx-auto mb-4">
                            <span class="material-symbols-rounded text-2xl">security</span>
                        </div>

                        <h2 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white mb-2 tracking-tighter">Your Privacy Matters</h2>
                        <p class="text-slate-500 dark:text-slate-400 font-medium mb-5 leading-relaxed text-xs">
                            We use data to personalize your viral journey. By proceeding, you consent to our use of your data for improved content discovery and platform security.
                        </p>

                        <div class="flex flex-col gap-3">
                            <button :disabled="loading" @click="saveConsent()" class="h-12 px-8 gradient-orange text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-lg shadow-orange-500/25 active:scale-95 transition-all flex items-center justify-center gap-2 disabled:opacity-70">
                                <template x-if="!loading">
                                    <span>I Consent & Continue</span>
                                </template>
                                <template x-if="loading">
                                    <div class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Processing...</span>
                                    </div>
                                </template>
                            </button>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">
                            @php
                                $privacyPolicy = getContent('policy_pages.element')->filter(fn($p) => str_contains(strtolower($p->data_values->title), 'privacy'))->first();
                            @endphp
                            Read our <a href="{{ $privacyPolicy ? route('policy.pages', [$privacyPolicy->id, slug($privacyPolicy->data_values->title)]) : '#' }}" class="text-orange-500">Privacy Policy</a> for more details.
                        </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @stack('script')

        <script>
            let cropper;
            const cropperModal = document.getElementById('cropper-modal');
            const cropperImage = document.getElementById('cropper-image');
            let currentInput;
            let currentPreview;
            let onCropDone;

            window.openCropper = function(input, previewId, options = {}, callback = null) {
                if (!input.files || !input.files[0]) return;
                
                currentInput = input;
                currentPreview = document.getElementById(previewId);
                onCropDone = callback;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    cropperImage.src = e.target.result;
                    cropperModal.classList.remove('hidden');
                    
                    if (cropper) cropper.destroy();
                    
                    const defaultOptions = {
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 0.8,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                    };
                    
                    cropper = new Cropper(cropperImage, { ...defaultOptions, ...options });
                };
                reader.readAsDataURL(input.files[0]);
            };

            function closeCropper() {
                cropperModal.classList.add('hidden');
                if (cropper) cropper.destroy();
            }

            document.getElementById('crop-apply-btn').addEventListener('click', function() {
                if (!cropper) return;
                
                const canvas = cropper.getCroppedCanvas({
                    maxWidth: 4096,
                    maxHeight: 4096,
                });
                
                canvas.toBlob((blob) => {
                    const fileName = currentInput.files[0].name;
                    const file = new File([blob], fileName, { type: 'image/jpeg' });
                    
                    // Update input files
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    currentInput.files = dataTransfer.files;
                    
                    // Update preview
                    if (currentPreview) {
                        currentPreview.src = URL.createObjectURL(blob);
                        currentPreview.classList.remove('opacity-0');
                        currentPreview.classList.add('opacity-100');
                    }
                    
                    if (onCropDone) onCropDone(file, URL.createObjectURL(blob));
                    
                    closeCropper();
                    
                    window.notify('success', 'Image aligned perfectly!');
                }, 'image/jpeg', 0.9);
            });
        </script>
        <script>
            /**
             * Video Impression Tracking System
             * Automatically records an impression when a video card enters the viewport.
             */
            document.addEventListener('DOMContentLoaded', function() {
                const trackedImpressions = new Set();
                const impressionObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const videoId = entry.target.getAttribute('data-video-id');
                            if (videoId && !trackedImpressions.has(videoId)) {
                                trackedImpressions.add(videoId);
                                
                                // Record impression on server
                                fetch(`/api/videos/${videoId}/impression`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                }).catch(() => {}); // Silent fail
                                
                                // Stop observing this specific card
                                impressionObserver.unobserve(entry.target);
                            }
                        }
                    });
                }, { 
                    threshold: 0.3, // 30% visibility
                    rootMargin: '50px' // Start tracking slightly before it enters viewport
                });

                function observeCards() {
                    document.querySelectorAll('[data-video-id]:not([data-observed])').forEach(card => {
                        card.setAttribute('data-observed', 'true');
                        impressionObserver.observe(card);
                    });
                }

                // Initial scan
                observeCards();

                // Watch for new cards (Infinite scroll / AJAX)
                const mutationObserver = new MutationObserver((mutations) => {
                    let hasNewCards = false;
                    mutations.forEach(m => {
                        if (m.addedNodes.length > 0) hasNewCards = true;
                    });
                    if (hasNewCards) observeCards();
                });

                mutationObserver.observe(document.body, { 
                    childList: true, 
                    subtree: true 
                });
            });

            // ==========================================
            // GLOBAL TELEMETRY: SESSION TRACKING
            // ==========================================
            (function() {
                // Generate UUID v4 for session token
                function uuidv4() {
                    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                        return v.toString(16);
                    });
                }

                // Get or create session token
                let sessionToken = sessionStorage.getItem('telemetry_session_token');
                if (!sessionToken) {
                    sessionToken = uuidv4();
                    sessionStorage.setItem('telemetry_session_token', sessionToken);
                }

                function pingSession(action = 'ping') {
                    if (!document.querySelector('meta[name="csrf-token"]')) return;
                    fetch('/api/telemetry/session', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            session_token: sessionToken,
                            action: action
                        }),
                        // keepalive allows request to complete even if page is unloading
                        keepalive: true
                    }).catch(() => {});
                }

                // Initial ping to start the session
                pingSession('start');

                // Ping every 30 seconds
                setInterval(() => pingSession('ping'), 30000);

                // Ping on page hide/unload to ensure final duration is captured
                window.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'hidden') {
                        pingSession('end');
                    }
                });
            })();
        </script>
    </body>
</html>

