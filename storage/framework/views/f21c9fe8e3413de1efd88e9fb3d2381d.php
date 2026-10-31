<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startPush('style'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <?php $__env->stopPush(); ?>
    <?php if($video->status == \App\Constants\Status::DRAFT): ?>
        <?php
            header('Location: ' . route('videos.create', ['draft_id' => $video->id]));
            exit;
        ?>
    <?php endif; ?>
    <?php if(!empty($video->user?->channel?->avatar)): ?>
    <link rel="preload" as="image" href="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $video->user->channel->avatar)); ?>" fetchpriority="high">
    <?php endif; ?>
    <link rel="preload" as="image" href="<?php echo e($video->getThumbnailUrl()); ?>" fetchpriority="high">

    <!-- DNS Preconnect for faster CDN resolution -->
    <link rel="preconnect" href="https://cdn.plyr.io" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <?php if($video->isBunnyVideo()): ?>
    <link rel="preconnect" href="https://<?php echo e(gs('bunny_cdn_hostname')); ?>" crossorigin>
    <?php endif; ?>

    <!-- Plyr Assets -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script defer src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    <!-- Razorpay deferred -->
    <script src="https://checkout.razorpay.com/v1/checkout.js" defer></script>

    <script>
        window.EMOJI_LIST = ["\u{1f600}","\u{1f603}","\u{1f604}","\u{1f601}","\u{1f606}","\u{1f605}","\u{1f923}","\u{1f602}","\u{1f642}","\u{1f643}","\u{1f609}","\u{1f60a}","\u{1f607}","\u{1f970}","\u{1f60d}","\u{1f929}","\u{1f618}","\u{1f617}","\u{263a}\u{fe0f}","\u{1f61a}","\u{1f619}","\u{1f60b}","\u{1f61b}","\u{1f61c}","\u{1f92a}","\u{1f61d}","\u{1f911}","\u{1f917}","\u{1f92d}","\u{1f92b}","\u{1f914}","\u{1f910}","\u{1f928}","\u{1f610}","\u{1f611}","\u{1f636}","\u{1f60f}","\u{1f612}","\u{1f644}","\u{1f62c}","\u{1f925}","\u{1f60c}","\u{1f614}","\u{1f62a}","\u{1f924}","\u{1f634}","\u{1f637}","\u{1f912}","\u{1f915}","\u{1f922}","\u{1f92e}","\u{1f927}","\u{1f975}","\u{1f976}","\u{1f974}","\u{1f635}","\u{1f92f}","\u{1f920}","\u{1f973}","\u{1f60e}","\u{1f913}","\u{1f9d0}","\u{1f615}","\u{1f61f}","\u{1f641}","\u{1f62e}","\u{1f62f}","\u{1f632}","\u{1f633}","\u{1f97a}","\u{1f626}","\u{1f627}","\u{1f628}","\u{1f630}","\u{1f625}","\u{1f622}","\u{1f62d}","\u{1f631}","\u{1f616}","\u{1f623}","\u{1f61e}","\u{1f613}","\u{1f629}","\u{1f62b}","\u{1f971}","\u{1f624}","\u{1f621}","\u{1f620}","\u{1f92c}","\u{1f608}","\u{1f47f}","\u{1f480}","\u{2620}\u{fe0f}","\u{1f4a9}","\u{1f921}","\u{1f479}","\u{1f47a}","\u{1f47b}","\u{1f47d}","\u{1f47e}","\u{1f916}","\u{1f63a}","\u{1f638}","\u{1f639}","\u{1f63b}","\u{1f63c}","\u{1f63d}","\u{1f640}","\u{1f63f}","\u{1f63e}","\u{1f648}","\u{1f649}","\u{1f64a}","\u{1f48b}","\u{1f48c}","\u{1f498}","\u{1f49d}","\u{1f496}","\u{1f497}","\u{1f493}","\u{1f49e}","\u{1f495}","\u{1f49f}","\u{2763}\u{fe0f}","\u{1f494}","\u{2764}\u{fe0f}","\u{1f9e1}","\u{1f49b}","\u{1f49a}","\u{1f499}","\u{1f49c}","\u{1f90e}","\u{1f5a4}","\u{1f90d}","\u{1f4af}","\u{1f4a2}","\u{1f4a5}","\u{1f4ab}","\u{1f4a6}","\u{1f4a8}","\u{1f573}\u{fe0f}","\u{1f4a3}","\u{1f4ac}","\u{1f441}\u{fe0f}\u{200d}\u{1f5e8}\u{fe0f}","\u{1f5e8}\u{fe0f}","\u{1f5ef}\u{fe0f}","\u{1f4ad}","\u{1f4a4}","\u{1f44b}","\u{1f91a}","\u{1f590}\u{fe0f}","\u{270b}","\u{1f596}","\u{1f44c}","\u{1f90f}","\u{270c}\u{fe0f}","\u{1f91e}","\u{1f91f}","\u{1f918}","\u{1f919}","\u{1f448}","\u{1f449}","\u{1f446}","\u{1f595}","\u{1f447}","\u{261d}\u{fe0f}","\u{1f44d}","\u{1f44e}","\u{270a}","\u{1f44a}","\u{1f91b}","\u{1f91c}","\u{1f44f}","\u{1f64c}","\u{1f450}","\u{1f932}","\u{1f91d}","\u{1f64f}","\u{270d}\u{fe0f}","\u{1f485}","\u{1f933}","\u{1f4aa}","\u{1f9b5}","\u{1f9b6}","\u{1f442}","\u{1f443}","\u{1f9e0}","\u{1f9b7}","\u{1f9b4}","\u{1f440}","\u{1f441}\u{fe0f}","\u{1f445}","\u{1f444}","\u{1f525}","\u{2728}","\u{1f31f}","\u{2b50}","\u{1f389}","\u{1f38a}","\u{1f388}","\u{1f381}","\u{1f3c6}","\u{1f947}","\u{1f948}","\u{1f949}","\u{26bd}","\u{1f3c0}","\u{1f3c8}","\u{26be}","\u{1f3be}","\u{1f3d0}","\u{1f3c9}","\u{1f3b1}","\u{1f3af}","\u{1f3ae}","\u{1f579}\u{fe0f}","\u{1f3b0}","\u{1f3b2}","\u{1f3a8}","\u{1f3ac}","\u{1f3a4}","\u{1f3a7}","\u{1f3bc}","\u{1f3b5}","\u{1f3b6}","\u{1f3b9}","\u{1f941}","\u{1f3b7}","\u{1f3ba}","\u{1f3b8}","\u{1fa95}","\u{1f3bb}","\u{265f}\u{fe0f}","\u{1f3b3}"];
        window.__INITIAL_TOP_COMMENT__ = null;

        function comment() {
            return {
                replying: false,
                editing: false,
                liked: false,
                likes: 0,
                showReplies: false,
                expanded: false,
                showMore: false,
                content: '',
                isReel: false,
                isEdited: false,
                init() {
                    this.liked = this.$el.dataset.liked === '1';
                    this.likes = parseInt(this.$el.dataset.likes || '0', 10);
                    this.isReel = this.$el.dataset.isReel === '1';
                    this.isEdited = this.$el.dataset.isEdited === '1';
                    const raw = this.$el.dataset.rawContent || '';
                    if (raw) {
                        try {
                            this.content = new TextDecoder().decode(Uint8Array.from(atob(raw), c => c.charCodeAt(0)));
                        } catch(e) {
                            this.content = atob(raw);
                        }
                    }
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
            };
        }
        window.comment = comment;
        if (typeof Alpine !== 'undefined' && Alpine.data) {
            Alpine.data('comment', comment);
        } else {
            document.addEventListener('alpine:init', () => {
                Alpine.data('comment', comment);
            });
        }

        function mobileCommentsPreview(initialCount) {
            return {
                mobileCommentsOpen: false,
                commentsCount: initialCount,
                previewComment: window.__INITIAL_TOP_COMMENT__ || null,
                init() {
                    window.addEventListener('comment-added', (e) => {
                        if (e.detail && e.detail.commentsCount !== undefined) {
                            this.commentsCount = e.detail.commentsCount;
                        }
                        if (e.detail && e.detail.latestComment && e.detail.isTopLevel !== false) {
                            this.previewComment = e.detail.latestComment;
                        }
                    });
                    window.addEventListener('comment-deleted', (e) => {
                        if (e.detail && e.detail.comments_count !== undefined) {
                            this.commentsCount = e.detail.comments_count;
                            if (this.commentsCount === 0) {
                                this.previewComment = null;
                            }
                        }
                    });
                }
            };
        }
        window.mobileCommentsPreview = mobileCommentsPreview;

        function commentSystem() {
            return {
                mainComment: '',
                loading: false,
                mainEmojisOpen: false,
                sortLoading: false,
                commentsCount: <?php echo e($video->comments->count()); ?>,
                sort: '<?php echo e(request()->get('comment_sort', 'new')); ?>',
                init() {
                    
                    window.addEventListener('comment-added', (e) => {
                        if (e.detail && e.detail.commentsCount !== undefined) {
                            this.commentsCount = e.detail.commentsCount;
                        }
                    });
                    window.addEventListener('comment-deleted', (e) => {
                        if (e.detail && e.detail.comments_count !== undefined) {
                            this.commentsCount = e.detail.comments_count;
                        }
                    });

                    window.refreshCommentsList = async () => {
                        try {
                            const response = await fetch(`<?php echo e(route('comments.fetch', $video)); ?>?sort=${this.sort}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            const data = await response.json();
                            if (data.status === 'success') {
                                const desktopList = document.getElementById('comments-list');
                                const mobileList = document.getElementById('mobile-comments-list');
                                if (desktopList) {
                                    desktopList.innerHTML = data.html;
                                    Alpine.initTree(desktopList);
                                }
                                if (mobileList) {
                                    mobileList.innerHTML = data.html;
                                    Alpine.initTree(mobileList);
                                }
                            }
                        } catch (error) {
                            console.error('Failed to refresh comments:', error);
                        }
                    };
                },
                async setSort(newSort) {
                    if (this.sort === newSort) return;
                    this.sort = newSort;
                    this.sortLoading = true;
                    try {
                        const response = await fetch(`<?php echo e(route('comments.fetch', $video)); ?>?sort=${newSort}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const data = await response.json();
                        if (data.status === 'success') {
                            const desktopList = document.getElementById('comments-list');
                            const mobileList = document.getElementById('mobile-comments-list');
                            if (desktopList) {
                                desktopList.innerHTML = data.html;
                                Alpine.initTree(desktopList);
                            }
                            if (mobileList) {
                                mobileList.innerHTML = data.html;
                                Alpine.initTree(mobileList);
                            }
                        }
                    } catch (error) {
                        console.error('Failed to sort comments:', error);
                    } finally {
                        this.sortLoading = false;
                    }
                },
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
                    const text = this.$refs.commentText.value;
                    const cursorPos = this.$refs.commentText.selectionStart;
                    const textBeforeCursor = text.substring(0, cursorPos);
                    const textAfterCursor = text.substring(cursorPos);
                    
                    const newTextBeforeCursor = textBeforeCursor.replace(/@(\w*)$/, `@${username} `);
                    this.$refs.commentText.value = newTextBeforeCursor + textAfterCursor;
                    this.mainComment = this.$refs.commentText.value;
                    
                    this.showSuggestions = false;
                    this.$refs.commentText.focus();
                    
                    this.$refs.commentText.style.height = 'auto';
                    this.$refs.commentText.style.height = this.$refs.commentText.scrollHeight + 'px';
                },

                formatComment(text) {
                    if (!text) return '';
                    let div = document.createElement('div');
                    div.textContent = text;
                    let escaped = div.innerHTML;
                    return escaped.replace(/@(\w+)/g, '<a href="/@$1" class="text-orange-500 font-black hover:underline transition-all">@$1</a>');
                },
                
                async submitComment(containerId = 'comments-list') {
                    if (!this.mainComment.trim()) return;
                    this.loading = true;
                    try {
                        const response = await fetch('<?php echo e(route('comments.store', $video)); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ content: this.mainComment })
                        });
                        const data = await response.json();
                        if (data.status === 'success') {
                            this.mainComment = '';
                            this.commentsCount = data.comments_count;
                            const targetContainers = [
                                document.getElementById('comments-list'),
                                document.getElementById('mobile-comments-list')
                            ];
                            targetContainers.forEach(container => {
                                if (container) {
                                    const noMsg = container.querySelector('.no-comments-msg');
                                    if (noMsg) noMsg.remove();
                                    
                                    const div = document.createElement('div');
                                    div.innerHTML = data.html;
                                    const newEl = div.firstElementChild;
                                    const pinnedEl = container.querySelector('.comment-item[data-pinned="true"]');
                                    if (pinnedEl) {
                                        pinnedEl.after(newEl);
                                    } else {
                                        container.prepend(newEl);
                                    }
                                    // Initialize Alpine components in dynamically added HTML (Alpine v3 internal method)
                                    if (typeof Alpine !== 'undefined' && Alpine.initTree) {
                                        Alpine.initTree(newEl);
                                    } else if (typeof Alpine !== 'undefined' && Alpine['initTree']) {
                                        Alpine['initTree'](newEl);
                                    }
                                }
                            });

                            window.dispatchEvent(new CustomEvent('comment-added', {
                                detail: {
                                    commentsCount: data.comments_count,
                                    latestComment: data.latest_comment,
                                    isTopLevel: !data.parent_id
                                }
                            }));
                            
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Comment posted!',
                                showConfirmButton: false,
                                timer: 3000,
                                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                            });
                        } else {
                            this.mainComment = '';
                            
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
                                                ${data.message || 'Inappropriate language detected.'}
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
                        }
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.loading = false;
                    }
                },
                
                async submitReply(content, parentId) {
                    if (!content.trim()) return;
                    try {
                        const response = await fetch('<?php echo e(route('comments.store', $video)); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ content: content, parent_id: parentId })
                        });
                        const data = await response.json();
                        if (data.status === 'success') {
                            this.commentsCount = data.comments_count;
                            const container = document.querySelector(`.reply-container-${parentId}`);
                            if (container) {
                                container.insertAdjacentHTML('beforeend', data.html);
                            } else {
                                console.error('Could not find reply container for', parentId);
                            }
                            
                            window.dispatchEvent(new CustomEvent('comment-added', {
                                detail: {
                                    commentsCount: data.comments_count,
                                    isTopLevel: false
                                }
                            }));
                            
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Reply posted!',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    } catch (e) {
                        console.error(e);
                    }
                }
            };
        }
    </script>
    

    <!-- Material Icons for YouTube App Look -->
    
    

    <style>
        :root { --plyr-color-main: #FF0000; }
        .plyr--full-ui input[type=range] { color: #FF0000; }
        .plyr__control--overlaid { background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); }
        .plyr__control.plyr__tab-focus, .plyr__control:hover, .plyr__control[aria-expanded=true] { background: transparent; color: #FF0000; }
        .plyr--video .plyr__control.plyr__tab-focus, .plyr--video .plyr__control:hover, .plyr--video .plyr__control[aria-expanded=true] { background: transparent; }
        
        /* YouTube Native App Settings Menu UI Clone */
        .plyr__menu__container {
            border-radius: 16px;
            background: rgba(28, 28, 28, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.1);
            color: #F1F1F1;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            padding: 8px 0;
            overflow: hidden;
            transform: translateY(-8px);
        }
        .plyr__menu__container .plyr__control {
            color: #F1F1F1;
            font-size: 14px;
            padding: 10px 24px;
            display: flex;
            align-items: center;
        }
        .plyr__menu__container .plyr__control:hover, 
        .plyr__menu__container .plyr__control[aria-expanded=true] {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .plyr__menu__container .plyr__control[role=menuitemradio]::before {
            background: rgba(255,255,255,0.2) !important;
            box-shadow: none !important;
        }
        .plyr__menu__container .plyr__control[role=menuitemradio][aria-checked=true]::before { 
            background: #FF0000 !important; 
        }

        /* Force Plyr to fill parent container regardless of video source state */
        .plyr { width: 100% !important; height: 100% !important; }

        /* Zoom to fill support override for Plyr container */
        .plyr-zoomed video {
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
            max-width: none !important;
            max-height: none !important;
            min-width: 100% !important;
            min-height: 100% !important;
            aspect-ratio: auto !important;
            transform: none !important;
        }
        .plyr-zoomed .plyr__video-wrapper {
            width: 100% !important;
            height: 100% !important;
            padding-bottom: 0 !important;
            aspect-ratio: auto !important;
        }

        /* Custom Video Player Overlay (Works on Mobile, Desktop & Fullscreen) */
        .yt-mobile-overlay {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background: rgba(0,0,0,0.5);
            z-index: 9999 !important;
            display: flex !important;
            flex-direction: column;
            justify-content: space-between;
            padding: 12px;
            color: white;
            pointer-events: auto;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .yt-mobile-overlay.visible { opacity: 1; }

        .yt-mobile-top, .yt-mobile-bottom { display: flex; align-items: center; justify-content: space-between; gap: 15px; }
        .yt-mobile-center { display: flex; align-items: center; justify-content: center; gap: 40px; flex: 1; }
        .yt-mobile-center button { flex-shrink: 0; aspect-ratio: 1/1; }
        .yt-play-btn { width: 72px !important; height: 72px !important; background: rgba(255,255,255,0.1); border-radius: 50% !important; display: flex !important; align-items: center; justify-content: center; backdrop-filter: blur(10px); flex-shrink: 0; border: 1px solid rgba(255,255,255,0.2); overflow: hidden !important; transform: translateZ(0); }
        .yt-play-btn .material-symbols-rounded { font-size: 40px !important; }
        .yt-mobile-center .material-symbols-rounded { font-size: 28px !important; }
        .yt-mobile-bottom { padding-bottom: 20px; }
        
        .yt-seek-bar { 
            position: absolute; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            height: 32px; 
            padding: 10px 0;
            background: transparent; 
            z-index: 10000 !important; 
            cursor: pointer;
            display: flex;
            align-items: center;
            touch-action: none;
            user-select: none;
            -webkit-user-select: none;
        }
        .yt-seek-bar-track {
            width: 100%;
            height: 5px;
            background: rgba(255, 255, 255, 0.3);
            position: relative;
            border-radius: 3px;
            overflow: visible;
        }
        .yt-seek-progress { height: 100%; background: #F97316; position: relative; border-radius: 3px; }
        .yt-seek-knob { position: absolute; right: -10px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; background: #F97316; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 15px rgba(249, 115, 22, 0.6); }

        /* Hide Plyr Native UI Globally - Using our High-Fidelity Custom Overlay instead */
        .plyr__controls, .plyr__control--overlaid { display: none !important; }

        /* Material Symbols Config */
        .material-symbols-rounded { font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24; }
        /* Glyph-only guard matching layout: hide ligature text until the font is
           ready so slow networks never show clipped text ("lay", "mb o").
           Buttons and labels stay visible via server-rendered fallbacks. */
        html:not(.msr-ready) .material-symbols-rounded,
        html:not(.msr-ready) .material-symbols-filled { visibility: hidden; }
        [x-cloak] { display: none !important; }
        .material-symbols-filled { font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 24; }
        .font-variation-filled { font-variation-settings: 'FILL' 1; }

        /* Settings Bottom Sheet (Native Android Look) */
        .mobile-settings-sheet {
            position: fixed;
            inset: 0;
            z-index: 999999;
            display: flex;
            align-items: flex-end;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        @media (min-width: 1024px) {
            .mobile-settings-sheet {
                align-items: center;
                justify-content: center;
            }
        }
        .mobile-settings-sheet.active { visibility: visible; }
        .mobile-settings-sheet .sheet-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .mobile-settings-sheet.active .sheet-overlay { opacity: 1; }
        .mobile-settings-sheet .sheet-content {
            width: 100%;
            background: #ffffff;
            border-radius: 24px 24px 0 0;
            padding: 20px 0 0 0;
            position: relative;
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.1, 0.7, 0.1, 1);
            color: #0F0F0F;
            z-index: 1001;
            box-shadow: 0 -10px 40px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            max-height: 85vh;
        }
        .dark .mobile-settings-sheet .sheet-content {
            background: #282828;
            color: #F1F1F1;
            box-shadow: none;
        }
        @media (min-width: 1024px) {
            .mobile-settings-sheet .sheet-content {
                width: 450px;
                border-radius: 24px;
                transform: scale(0.9) translateY(20px);
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
                opacity: 0;
                margin-bottom: 0;
                border: 1px solid rgba(0,0,0,0.05);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            .dark .mobile-settings-sheet .sheet-content {
                border: 1px solid rgba(255,255,255,0.1);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            }
        }
        .mobile-settings-sheet.active .sheet-content { 
            transform: translateY(0);
        }
        @media (min-width: 1024px) {
            .mobile-settings-sheet.active .sheet-content {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }
        @media (max-height: 500px) {
            .mobile-settings-sheet {
                align-items: center;
                justify-content: center;
            }
            .mobile-settings-sheet .sheet-content {
                width: 360px;
                max-width: 90%;
                border-radius: 24px;
                transform: scale(0.9) translateY(20px);
                opacity: 0;
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
                padding: 16px 0 0 0;
            }
            .mobile-settings-sheet.active .sheet-content {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
            .sheet-item {
                padding: 10px 24px;
                gap: 12px;
            }
        }
        .sheet-item {
            padding: 16px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .sheet-item:hover { background: rgba(0,0,0,0.03); }
        .dark .sheet-item:hover { background: rgba(255,255,255,0.05); }
        .sheet-item:active { background: rgba(0,0,0,0.08); }
        .dark .sheet-item:active { background: rgba(255,255,255,0.1); }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .mobile-section-card { border-radius: 20px; background: rgba(0,0,0,0.03); }
        .dark .mobile-section-card { background: rgba(255,255,255,0.05); }

        .gradient-orange {
            background: linear-gradient(135deg, #F97316 0%, #EA580C 100%) !important;
        }

        /* Volume Slider Styling */
        .yt-volume-slider {
            -webkit-appearance: none;
            height: 2px;
            background: rgba(255,255,255,0.2);
            border-radius: 1px;
            outline: none;
            cursor: pointer;
        }
        .yt-volume-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 12px;
            height: 12px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 0 5px rgba(0,0,0,0.5);
        }

        .yt-vertical-volume {
            position: absolute;
            bottom: calc(100% - 5px);
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            background: rgba(15, 15, 15, 0.9);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 12px 0;
            z-index: 100;
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }
        .yt-vertical-volume input[type=range] {
            writing-mode: vertical-lr;
            direction: rtl;
            width: 4px;
            height: 80px;
            background: rgba(249, 115, 22, 0.2);
            outline: none;
            cursor: pointer;
            accent-color: #F97316;
        }
        /* Vertical range slider for modern browsers */
        .yt-vertical-slider {
            appearance: none;
            width: 36px;
            height: 80px;
            background: transparent;
            border-radius: 2px;
            outline: none;
            writing-mode: vertical-lr;
            direction: rtl;
            cursor: pointer;
        }
        .yt-vertical-slider::-webkit-slider-thumb {
            appearance: none;
            width: 14px;
            height: 14px;
            background: #F97316;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 10px rgba(249, 115, 22, 0.5);
        }

    </style>

    <?php
        $isLiked = $video->isLikedBy(auth()->user()) ? 'true' : 'false';
        $isSubscribed = (auth()->check() && $video->user?->channel?->id && \App\Models\Subscription::where('user_id', auth()->id())->where('channel_id', $video->user->channel->id)->exists()) ? 'true' : 'false';
        $isSavedWL = 'false';
        if(auth()->check()){
            $wlPlaylist = auth()->user()->playlists()->where('name', 'Watch Later')->first();
            if($wlPlaylist && $wlPlaylist->videos()->where('video_id', $video->id)->exists()) $isSavedWL = 'true';
        }
    ?>

    <div x-data="{ 
                   theatre: false, 
                   showDescription: false, 
                   playlistOpen: true,
                   videoUnavailable: false,
                   liked: <?php echo e($isLiked); ?>, 
                   disliked: false,
                   likes: <?php echo e($video->likes->count()); ?>,
                   toggleLike() {
                        <?php if(auth()->guard()->check()): ?>
                            if(!this.liked && this.disliked) this.disliked = false;
                            this.liked = !this.liked;
                            fetch('<?php echo e(route('videos.like', $video)); ?>', {method:'POST', headers:{'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'}})
                                .then(r => r.json())
                                .then(d => { 
                                    this.liked = d.liked;
                                    this.likes = d.likes_count; 
                                });
                        <?php else: ?>
                            showLoginAlert('like this video');
                        <?php endif; ?>
                   },
                   toggleDislike() {
                        <?php if(auth()->guard()->check()): ?>
                            if(!this.disliked && this.liked) {
                                this.toggleLike();
                            }
                            this.disliked = !this.disliked;
                            Swal.fire({ 
                                toast: true, 
                                position: 'top-end', 
                                icon: 'success', 
                                title: this.disliked ? 'Disliked' : 'Dislike removed', 
                                showConfirmButton: false, 
                                timer: 2000,
                                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                            });
                        <?php else: ?>
                            showLoginAlert('dislike this video');
                        <?php endif; ?>
                    },
                    subscribed: <?php echo e($isSubscribed); ?>,
                    subscribersCount: <?php echo e($video->user->channel->subscribers_count ?? 0); ?>,
                    subDropdownOpen: false,
                    notificationPreference: '<?php echo e(auth()->check() ? (\App\Models\Subscription::where("user_id", auth()->id())->where("channel_id", $video->user->channel->id ?? 0)->value("notification_preference") ?? "all") : "all"); ?>',
                    toggleSubscribe() {
                        <?php if(auth()->guard()->check()): ?>
                            if (<?php echo e(auth()->id()); ?> === <?php echo e($video->user_id); ?>) {
                                Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Cannot subscribe to your own channel', showConfirmButton: false, timer: 3000 });
                                return;
                            }
                            
                            if (this.subscribed) {
                                this.subDropdownOpen = !this.subDropdownOpen;
                                return;
                            }

                            this.performSubscribe();
                        <?php else: ?>
                            Swal.fire({
                                title: 'Login Required',
                                text: 'Please login to subscribe to this channel',
                                icon: 'info',
                                showCancelButton: true,
                                confirmButtonText: 'Login',
                                confirmButtonColor: '#F97316',
                                cancelButtonColor: '#64748b',
                                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '<?php echo e(route('login')); ?>';
                                }
                            });
                        <?php endif; ?>
                    },
                    performSubscribe() {
                        const prevSubscribed = this.subscribed;
                        const prevCount = this.subscribersCount;
                        
                        this.subscribed = true;
                        this.subscribersCount++;

                        fetch('<?php echo e(route('channels.subscribe', $video->user->channel->id ?? 0)); ?>', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Network response was not ok');
                            return res.json();
                        })
                        .then(data => { 
                            if (data.error) throw new Error(data.error);
                            this.subscribed = data.subscribed; 
                            this.subscribersCount = data.subscribers_count;
                            this.notificationPreference = data.notification_preference;
                            
                            if (this.subscribed) {
                                this.subDropdownOpen = true;
                            }
                        })
                        .catch(error => { 
                            console.error('Subscription failed:', error);
                            this.subscribed = prevSubscribed; 
                            this.subscribersCount = prevCount;
                            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: error.message || 'Subscription failed', showConfirmButton: false, timer: 3000 });
                        });
                    },
                    unsubscribe() {
                        Swal.fire({
                            title: 'Unsubscribe?',
                            text: 'Are you sure you want to unsubscribe from this channel?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Unsubscribe',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#000000',
                            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const prevSubscribed = this.subscribed;
                                const prevCount = this.subscribersCount;
                                
                                this.subscribed = false;
                                this.subscribersCount = Math.max(0, this.subscribersCount - 1);
                                this.subDropdownOpen = false;

                                fetch('<?php echo e(route('channels.subscribe', $video->user->channel->id ?? 0)); ?>', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(r => r.json())
                                .then(d => { 
                                    this.subscribed = d.subscribed; 
                                    this.subscribersCount = d.subscribers_count;
                                })
                                .catch(() => { 
                                    this.subscribed = prevSubscribed; 
                                    this.subscribersCount = prevCount;
                                });
                            }
                        });
                    },
                    setNotification(pref) {
                        const oldPref = this.notificationPreference;
                        this.notificationPreference = pref;
                        this.subDropdownOpen = false;
                        
                        fetch('<?php echo e(route('channels.subscribe.preference', $video->user->channel->id ?? 0)); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ preference: pref })
                        })
                        .then(r => r.json())
                        .then(d => {
                            if (d.success) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    icon: 'success',
                                    title: pref === 'all' ? 'Notifications set to All' : 'Notifications set to None',
                                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                });
                            }
                        })
                        .catch(() => {
                            this.notificationPreference = oldPref;
                        });
                    },
                   shareOpen: false,
                   shareType: 'video',
                   shareUrl: '<?php echo e(url()->current()); ?>',
                   shareTitle: 'Share Video',
                   setShareType(type) {
                       this.shareType = type;
                       if (type === 'channel') {
                           this.shareUrl = '<?php echo e(route('channels.show_by_username', $video->user->username)); ?>';
                           this.shareTitle = 'Share Channel';
                       } else {
                           this.shareUrl = '<?php echo e(url()->current()); ?>';
                           this.shareTitle = 'Share Video';
                       }
                   },
                   savedWL: <?php echo e($isSavedWL); ?>,
                   savingWL: false,
                   saveToWatchLater() {
                       <?php if(auth()->guard()->check()): ?>
                           if (this.savingWL) return;
                           this.savingWL = true;
                           fetch('/videos/<?php echo e($video->id); ?>/watch-later', {
                               method: 'POST',
                               headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' }
                           })
                           .then(r => r.json())
                           .then(d => {
                               this.savedWL = d.status === 'added';
                               Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, icon: 'success', title: d.message,
                                   background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                   color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                               });
                           })
                           .catch(() => {
                               Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, icon: 'error', title: 'Failed to save' });
                           })
                           .finally(() => { this.savingWL = false; });
                       <?php else: ?>
                           showLoginAlert('save to Watch Later');
                       <?php endif; ?>
                   }
                }" 
         @toggle-theatre.window="theatre = !theatre" @open-description.window="showDescription = true" @video-unavailable.window="videoUnavailable = true"
         class="bg-white dark:bg-[#0F0F0F] min-h-[calc(100vh-60px)] text-gray-900 dark:text-[#F1F1F1] font-sans pb-10 transition-colors duration-300">
        
        <div :class="theatre ? 'mx-auto w-full px-0' : 'max-w-[1800px] mx-auto px-0 lg:px-6 xl:px-8 mt-0 lg:mt-6 transition-all duration-300'">
            
            <div :class="theatre ? 'flex flex-col' : 'flex flex-col lg:flex-row gap-6'">
                
                <!-- Video & Meta Left Column -->
                <?php
                    // Gate inputs computed once for every gate below (ads, sources, overlays, player).
                    // The pricing tier is authoritative: drafts can publish with tier set but the
                    // premium flag missing, so exclusive (tier 2) must lock immediately and premium
                    // (tier 1) must get the PPV teaser — never play freely.
                    $payTier = (int) $video->pricing_tier;
                    // Teaser is premium-only: it needs an explicit tier of 1.
                    // Anything else locked (exclusive, or legacy flag-only rows)
                    // locks immediately with zero preview playback.
                    $isLocked = $video->is_premium || $payTier === 1 || $payTier === 2;
                    $isScheduled = $video->scheduled_at && $video->scheduled_at->isFuture();
                    if ($isLocked && !$hasAccess && !$isScheduled) {
                        $ownsVideo = auth()->check() && auth()->id() == $video->user_id;
                        $boughtVideo = $ownsVideo ? true : (auth()->check() && auth()->user()->isPurchased($video->id));
                        $channelId = optional(optional($video->user)->channel)->id;
                        $hasChannelSub = auth()->check() && $channelId && auth()->user()->hasActiveChannelSubscription($channelId);
                        $hasAccess = $ownsVideo || $boughtVideo || $hasChannelSub;
                    }
                    // Pay-per-view teaser: length comes from Admin → General Setting → PPV teaser
                    // (0 = disabled). Applies to premium (tier 1) only.
                    $teaserSeconds = max(0, (int) (gs('ppv_teaser_duration_seconds') ?? 30));
                    $previewMode = $isLocked && !$hasAccess && $isAgeVerified && $payTier === 1 && $teaserSeconds > 0;
                    // Guests on age-restricted videos get no media at all until they sign in —
                    // the sign-in gate is their only overlay (no playable confirm buttons).
                    $mediaAllowed = ($hasAccess || $previewMode) && (!$video->is_age_restricted || $isAgeVerified);
                ?>
                <div :class="theatre ? 'w-full max-lg:contents' : 'max-lg:contents lg:w-[75%] flex-1 min-w-0'" 
                     x-data="{ adShowing: <?php echo e($ad ? 'true' : 'false'); ?>, 
                               adSkipped: false, 
                               skipTimer: <?php echo e($ad ? $ad->skip_after : 0); ?>,
                               adDuration: <?php echo e($ad ? $ad->duration ?? 30 : 30); ?>,
                               adStarted: false,
                               adsShown: 0,
                               lastAdTime: 0,
                               adInterval: <?php echo e((gs('ad_config')?->per_minute ?? 3) * 60); ?>,
                               maxAds: <?php echo e(gs('ad_config')?->ad_views ?? 3); ?>,
                               
                                startAd() {
                                    <?php if(!$hasAccess || $video->is_age_restricted): ?>
                                    this.adShowing = false;
                                    return;
                                    <?php endif; ?>
                                   if(this.adsShown >= this.maxAds) return;
                                   console.log('Attempting to start advertisement...');
                                   this.adShowing = true;
                                   this.adSkipped = false;
                                   this.skipTimer = <?php echo e($ad ? $ad->skip_after : 5); ?>;
                                   
                                   const mainVideo = document.getElementById('player');
                                   if(window.plyrPlayer) window.plyrPlayer.pause();
                                   else if(mainVideo) mainVideo.pause();

                                   setTimeout(() => {
                                       const adVideo = document.getElementById('ad-player');
                                       if(adVideo) {
                                           adVideo.play().then(() => {
                                               console.log('Ad playback started successfully.');
                                           }).catch(err => {
                                               console.error('Ad playback failed:', err);
                                               this.finishAd();
                                           });
                                           this.adStarted = true;
                                           let interval = setInterval(() => {
                                               if(this.skipTimer > 0) this.skipTimer--;
                                               else clearInterval(interval);
                                           }, 1000);
                                       }
                                   }, 500);
                               },
                               finishAd() {
                                   this.adShowing = false;
                                   this.adSkipped = true;
                                   this.adsShown++;
                                   this.lastAdTime = window.plyrPlayer ? window.plyrPlayer.currentTime : 0;
                                   
                                   const mainVideo = document.getElementById('player');
                                   if(mainVideo) {
                                       <?php if($hasAccess && !$video->is_age_restricted): ?>
                                       if(window.plyrPlayer) window.plyrPlayer.play();
                                       else mainVideo.play().catch(()=>{});
                                       <?php endif; ?>
                                   }
                                   
                                   <?php if($ad): ?>
                                   fetch('/api/videos/<?php echo e($video->id); ?>/ad-impression', { 
                                       method: 'POST', 
                                       headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' } 
                                   });
                                   <?php endif; ?>
                               }
                             }"
                     @timeupdate.window="if(!adShowing && adsShown < maxAds && ($event.detail.currentTime - lastAdTime >= adInterval)) { startAd(); }"
                     x-init="if(adShowing) { setTimeout(() => startAd(), 1000) }">
                    
                    <!-- Ad Overlay Vessel -->
                    <template x-if="adShowing">
                        <div class="aspect-video w-full bg-black lg:rounded-xl overflow-hidden relative shadow-sm z-[90] sticky top-14 lg:top-20 lg:static lg:z-50 transform-gpu backface-hidden" style="will-change: transform; -webkit-transform: translate3d(0,0,0); transform: translate3d(0,0,0); -webkit-backface-visibility: hidden;">
                            <?php if($ad): ?>
                            <video id="ad-player" @ended="finishAd" class="w-full h-full object-contain" src="<?php echo e($ad->media_path ? Storage::url($ad->media_path) : ''); ?>" playsinline></video>
                            
                            <!-- Ad UI Overlay -->
                            <div class="absolute inset-0 flex flex-col justify-end p-6 bg-gradient-to-t from-black/60 to-transparent pointer-events-none">
                                <div class="flex items-center justify-between pointer-events-auto">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-white/10 backdrop-blur-md flex items-center justify-center text-white border border-white/10">
                                            <span class="material-symbols-rounded">ads_click</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest leading-none">Sponsored</p>
                                            <a href="<?php echo e($ad->click_url); ?>" target="_blank" class="text-sm font-black text-white hover:underline"><?php echo e($ad->title); ?></a>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col items-end gap-2">
                                        <button x-show="skipTimer > 0" class="bg-black/60 backdrop-blur-md text-white px-6 py-2.5 rounded-full text-xs font-black uppercase tracking-widest border border-white/10">
                                            Skip in <span x-text="skipTimer"></span>s
                                        </button>
                                        <button x-show="skipTimer == 0" @click="finishAd" class="bg-white text-black px-8 py-3 rounded-full text-sm font-black uppercase tracking-widest hover:bg-gray-200 transition-colors">
                                            Skip Ad
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </template>

                    <!-- Player Hub (Optimized for Platform Stability) -->
                    <?php if($video->isStruck()): ?>
                        <div class="aspect-video w-full bg-[#1A1A1A] lg:rounded-xl overflow-hidden relative flex flex-col items-center justify-center border border-white/5 shadow-2xl sticky top-14 lg:top-20 lg:static z-[90] lg:z-auto transform-gpu backface-hidden" style="will-change: transform; -webkit-transform: translate3d(0,0,0); transform: translate3d(0,0,0); -webkit-backface-visibility: hidden;">
                            <div class="w-20 h-20 rounded-3xl bg-red-500/10 text-red-500 flex items-center justify-center mb-6">
                                <span class="material-symbols-rounded text-5xl">warning</span>
                            </div>
                            <h3 class="text-xl font-black text-white uppercase tracking-tight ">Content Removed</h3>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-[0.2em] mt-2">This video is no longer available due to a policy violation.</p>
                            <div class="mt-8 flex items-center gap-4">
                                <a href="<?php echo e(route('home')); ?>" class="px-8 py-3 bg-white/10 hover:bg-white/20 text-white text-[10px] font-black uppercase tracking-widest rounded-xl border border-white/10 transition-all">Back to Feed</a>
                            </div>
                        </div>
                    <?php else: ?>
                    <div x-show="!adShowing" x-data="playerHub('<?php echo e($mediaAllowed ? $videoPlayUrl : ''); ?>')" class="w-full relative min-h-[200px] lg:min-h-[400px] bg-black lg:rounded-xl overflow-hidden sticky top-14 lg:top-20 lg:static z-[90] lg:z-auto transform-gpu backface-hidden" :class="miniPlayer ? 'aspect-video' : videoAspectClass" style="will-change: transform; -webkit-transform: translate3d(0,0,0); transform: translate3d(0,0,0); -webkit-backface-visibility: hidden;">

                        <div x-show="!adShowing" 
                             @click="if(miniPlayer) { toggleMiniPlayer(); return; }"
                             class="w-full bg-black"
                             :class="[
                                 theatre && !miniPlayer ? videoAspectClass + ' w-full max-h-[85vh] bg-black relative' : '',
                                 !theatre && !miniPlayer ? videoAspectClass + ' w-full bg-black lg:rounded-xl overflow-hidden relative shadow-2xl' : '',
                                 miniPlayer ? 'fixed bottom-4 left-4 w-[65%] sm:w-72 aspect-video bg-black rounded-xl overflow-hidden z-[9999] shadow-2xl transition-all duration-300' : ''
                             ]">

                            <?php if($video->isBunnyVideo() && $video->bunny_status !== 'ready'): ?>
                                
                                <div id="bunny-processing-overlay" class="absolute inset-0 w-full h-full flex flex-col items-center justify-center bg-slate-900/80 backdrop-blur-md border border-white/5 z-20">
                                    <div class="relative w-20 h-20 mb-6 flex items-center justify-center">
                                        <div class="absolute inset-0 border-4 border-orange-500/20 border-t-orange-500 rounded-full animate-spin"></div>

                                    </div>
                                    
                                    <h3 class="text-white font-black tracking-widest text-sm uppercase">please wait awesome is loading</h3>


                                    <div class="mt-8 flex flex-col items-center gap-4">
                                        <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                            <span class="text-[9px] font-black text-white/40 uppercase tracking-widest">Auto-syncing status</span>
                                        </div>
                                        
                                        <button onclick="window.location.reload()" class="px-6 py-2 bg-white/10 hover:bg-white/20 text-white text-[10px] font-black uppercase tracking-widest rounded-xl border border-white/10 transition-all active:scale-95">
                                            Refresh Status
                                        </button>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        let checkInterval = setInterval(() => {
                                            fetch('/videos/<?php echo e($video->slug); ?>/status')
                                                .then(res => res.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        const progress = Math.min(Math.max(data.encode_progress || 0, 0), 100);


                                                        


                                                        
                                                        if (progress >= 100) {
                                                            clearInterval(checkInterval);
                                                            // Give it a small delay for the progress bar to fill and then reload
                                                            setTimeout(() => {
                                                                window.location.reload();
                                                            }, 1500);
                                                        }
                                                    }
                                                })
                                                .catch(err => console.error('Error checking status:', err));
                                        }, 5000); // 5 seconds interval for better balance between real-time and server load
                                    });
                                </script>
                            <?php endif; ?>

                            <?php
                                $isScheduled = $video->scheduled_at && $video->scheduled_at->isFuture();
                                $isOwner = auth()->check() && auth()->id() == $video->user_id;
                            ?>

                            <?php if($isScheduled): ?>
                                
                                <div id="scheduled-overlay" class="absolute inset-0 w-full h-full flex flex-col items-center justify-center z-[9999] px-4" style="background: radial-gradient(ellipse at center, #1e293b 0%, #0f172a 50%, #020617 100%);">
                                    
                                    
                                    <div class="relative mb-2 sm:mb-3 lg:mb-5">
                                        <div class="absolute -inset-1.5 sm:-inset-2 lg:-inset-3 rounded-full border border-blue-500/20 animate-ping" style="animation-duration: 3s;"></div>
                                        <div class="absolute -inset-0.5 sm:-inset-1 lg:-inset-2 rounded-full border border-blue-400/10"></div>
                                        <div class="w-9 h-9 sm:w-10 sm:h-10 lg:w-14 lg:h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                            <span class="material-symbols-rounded text-white text-lg sm:text-xl lg:text-3xl">schedule</span>
                                        </div>
                                    </div>

                                    
                                    <p class="text-[8px] sm:text-[9px] lg:text-[11px] font-black text-blue-400 uppercase tracking-[0.25em] sm:tracking-[0.3em] mb-0.5 sm:mb-1">Scheduled Premiere</p>
                                    
                                    
                                    <h3 class="text-white font-bold text-[13px] sm:text-sm lg:text-lg tracking-tight text-center px-8 line-clamp-1 mb-3 sm:mb-4 lg:mb-5 opacity-80"><?php echo e($video->title); ?></h3>

                                    
                                    <div class="flex items-center gap-1.5 sm:gap-2 lg:gap-3">
                                        <div class="flex items-center gap-1 sm:gap-1.5 bg-white/[0.06] px-2.5 sm:px-3 lg:px-4 py-1.5 sm:py-2 lg:py-2.5 rounded-md sm:rounded-lg border border-white/[0.06]">
                                            <span class="material-symbols-rounded text-blue-400 text-[14px] sm:text-[15px] lg:text-[18px]">calendar_today</span>
                                            <span class="text-white font-bold text-[10px] sm:text-[11px] lg:text-[13px]"><?php echo e($video->scheduled_at->timezone('Asia/Kolkata')->format('M d, Y')); ?></span>
                                        </div>
                                        <div class="flex items-center gap-1 sm:gap-1.5 bg-white/[0.06] px-2.5 sm:px-3 lg:px-4 py-1.5 sm:py-2 lg:py-2.5 rounded-md sm:rounded-lg border border-white/[0.06]">
                                            <span class="material-symbols-rounded text-blue-400 text-[14px] sm:text-[15px] lg:text-[18px]">schedule</span>
                                            <span class="text-white font-bold text-[10px] sm:text-[11px] lg:text-[13px]"><?php echo e($video->scheduled_at->timezone('Asia/Kolkata')->format('h:i A')); ?> IST</span>
                                        </div>
                                    </div>

                                    
                                    <a href="<?php echo e(route('home')); ?>" class="mt-3 sm:mt-5 lg:mt-7 inline-flex items-center gap-1.5 px-4 sm:px-5 lg:px-6 py-1.5 sm:py-2 lg:py-2.5 bg-white/[0.08] hover:bg-white/[0.14] text-white/70 hover:text-white text-[9px] sm:text-[10px] lg:text-[11px] font-bold rounded-full border border-white/[0.08] transition-all active:scale-95">
                                        <span class="material-symbols-rounded text-[14px] sm:text-[15px] lg:text-[16px]">arrow_back</span>
                                        Back to Feed
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if(!$isScheduled): ?>
                                <?php if(($video->is_age_restricted ?? false) && $isAgeVerified): ?>
                                <div class="absolute inset-0 z-[10000] bg-[#121212] flex flex-col items-center justify-center p-6 text-center lg:rounded-xl" x-data="{ acknowledged: false }" x-show="!acknowledged">
                                    <div class="w-16 h-16 rounded-[2rem] bg-white/5 border border-white/10 flex items-center justify-center text-white/50 mb-4 shadow-inner">
                                        <span class="material-symbols-rounded text-3xl">policy</span>
                                    </div>
                                    <h3 class="text-white font-black text-xl tracking-tight uppercase mb-1">Restricted</h3>
                                    <p class="text-white/50 text-[10px] font-bold uppercase tracking-widest mb-8">Acknowledge to View</p>
                                    <button @click.stop.prevent="acknowledged = true; setTimeout(() => { if(window.plyrPlayer) window.plyrPlayer.play(); else if($refs.mainPlayer) $refs.mainPlayer.play(); }, 200);" class="px-8 py-3.5 bg-gradient-to-r from-rose-500 to-orange-500 rounded-2xl text-[11px] font-black text-white uppercase tracking-[0.2em] shadow-xl shadow-rose-500/20 hover:scale-105 active:scale-95 transition-all">
                                        I Understand
                                    </button>
                                </div>
                                <?php endif; ?>
                                <!-- Main Video Element -->
                                <video x-ref="mainPlayer" id="player" crossorigin playsinline webkit-playsinline preload="auto"
                                       class="w-full h-full lg:rounded-xl transition-all duration-500 ease-in-out" 
                                       :class="isZoomed ? 'object-cover' : 'object-contain'"
                                       poster="<?php echo e($video->getThumbnailUrl()); ?>">
                                     <?php if($video->isBunnyVideo()): ?>
                                          <?php if($video->bunny_status === 'ready' && $mediaAllowed): ?>
                                              <source src="<?php echo e($videoPlayUrl); ?>" type="application/x-mpegURL">
                                         <?php endif; ?>
                                     <?php else: ?>
                                         <?php
                                             $paths = [
                                                 'storage/' . $video->video_path,
                                                 'assets/' . $video->video_path,
                                                 'assets/videos/' . $video->video_path
                                             ];
                                             $finalPath = null;
                                             foreach($paths as $p) {
                                                 if(file_exists(public_path($p))) {
                                                     $finalPath = asset($p);
                                                     break;
                                                 }
                                             }
                                             if(!$finalPath) $finalPath = Storage::url($video->video_path);
                                         ?>
                                          <?php if($video->video_path && $mediaAllowed): ?>
                                              <source src="<?php echo e($finalPath); ?>" type="video/mp4">
                                         <?php endif; ?>
                                     <?php endif; ?>
                                </video>

                                <!-- Video Error / Deleted Overlay -->
                                <div x-show="videoHasError" x-cloak class="absolute inset-0 z-[150] bg-black/90 backdrop-blur-md flex flex-col items-center justify-center p-4 sm:p-6 text-center">
                                    <div class="relative flex items-center justify-center w-12 h-12 sm:w-20 sm:h-20 mb-3 sm:mb-5">
                                        <div class="absolute inset-0 bg-red-500/20 rounded-full animate-ping" style="animation-duration: 3s;"></div>
                                        <div class="relative flex items-center justify-center w-10 h-10 sm:w-16 sm:h-16 bg-red-500 rounded-full shadow-xl shadow-red-500/20">
                                            <span class="material-symbols-rounded text-xl sm:text-3xl text-white">block</span>
                                        </div>
                                    </div>
                                    <h3 class="text-base sm:text-xl font-black text-white uppercase tracking-tight mb-1 sm:mb-2">Video Unavailable</h3>
                                    <p class="text-slate-400 text-[10px] sm:text-xs font-bold max-w-[200px] sm:max-w-xs mb-4 sm:mb-6 leading-relaxed">This video was deleted or is no longer available.</p>
                                    <a href="<?php echo e(route('home')); ?>" class="px-4 py-2 sm:px-6 sm:py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-full text-[10px] sm:text-xs font-black uppercase tracking-widest transition-all border border-white/10 flex items-center gap-1.5 sm:gap-2">
                                        <span class="material-symbols-rounded text-[12px] sm:text-sm">home</span>
                                        Back to Feed
                                    </a>
                                </div>
                            <?php endif; ?>

                        <?php if(!$isScheduled): ?>
                        <!-- Zoom Level Indicator Overlay -->
                         <div x-show="showZoomIndicator" x-cloak 
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 scale-90"
                              x-transition:enter-end="opacity-100 scale-100"
                              x-transition:leave="transition ease-in duration-300"
                              x-transition:leave-start="opacity-100 scale-100"
                              x-transition:leave-end="opacity-0 scale-90"
                              class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[200] pointer-events-none">
                             <div class="bg-black/60 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/10 flex items-center gap-3">
                                 <span class="material-symbols-rounded text-white" x-text="isZoomed ? 'zoom_in_map' : 'zoom_out_map'"></span>
                                 <span class="text-white font-black text-sm uppercase tracking-widest whitespace-nowrap" x-text="zoomLevelText"></span>
                             </div>
                         </div>

                        <!-- Age Gate Overlay -->
                        <?php if(!$isAgeVerified): ?>
                        <div class="absolute inset-0 z-[10000] bg-[#0F0F0F] flex items-center justify-center p-4">
                            <div class="w-full max-w-[420px] text-center scale-90 sm:scale-100 origin-center">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto rounded-[1rem] sm:rounded-[1.5rem] bg-rose-600 text-white flex items-center justify-center mb-4 sm:mb-6 shadow-xl shadow-rose-600/30">
                                    <span class="material-symbols-rounded text-3xl sm:text-4xl font-black">explicit</span>
                                </div>
                                <h2 class="text-lg sm:text-2xl font-black text-white mb-2 uppercase tracking-tight">Age Restricted</h2>
                                <p class="text-[10px] sm:text-sm font-medium text-slate-400 mb-6 sm:mb-8 px-4">This video may be inappropriate for some users. Please sign in to confirm your age.</p>
                                
                                <div class="flex flex-col gap-2 sm:gap-3 px-4 sm:px-6">
                                    <a href="<?php echo e(route('login')); ?>" class="w-full py-2 sm:py-3.5 bg-white text-black text-[8px] sm:text-[10px] font-black uppercase tracking-[0.1em] sm:tracking-[0.2em] rounded-lg sm:rounded-xl hover:bg-gray-100 transition-all shadow-md">
                                        Sign In to Verify
                                    </a>
                                    <a href="<?php echo e(route('home')); ?>" class="w-full py-2 sm:py-3.5 bg-white/5 text-white/60 text-[8px] sm:text-[10px] font-black uppercase tracking-[0.1em] sm:tracking-[0.2em] rounded-lg sm:rounded-xl hover:bg-white/10 transition-all border border-white/5">
                                        Back to Home
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Premium Access Overlay (Ultra-Compact & Responsive) -->
                        <?php if(!$hasAccess && $isAgeVerified): ?>
                        <div x-show="showPaywall" x-cloak class="absolute inset-0 z-[10000] bg-black/90 backdrop-blur-[12px] flex items-center justify-center p-2 sm:p-4">
                            <div class="w-full max-w-[380px] text-center">
                                <!-- Lock Icon (Hidden on small screens to save space) -->
                                <div class="hidden sm:flex w-12 h-12 mx-auto rounded-xl gradient-orange text-white items-center justify-center mb-4 shadow-lg shadow-orange-500/20">
                                    <span class="material-symbols-rounded text-2xl material-symbols-filled">lock</span>
                                </div>
                                
                                <h2 class="text-lg sm:text-xl font-black text-white mb-0.5 tracking-tight uppercase leading-tight">Premium Content</h2>
                                <p class="text-orange-400 font-bold text-[8px] sm:text-[9px] uppercase tracking-[0.2em] mb-4 sm:mb-6">Exclusive Access</p>
                                
                                <div class="flex flex-col items-center gap-2 sm:gap-3 px-4">
                                    <button onclick="payNow()" class="w-fit px-6 sm:px-8 h-10 sm:h-12 rounded-lg sm:rounded-xl gradient-orange text-white font-black text-[10px] sm:text-[11px] uppercase tracking-widest active:scale-[0.98] transition-all flex items-center justify-center gap-2 border border-white/10 shadow-lg shadow-orange-500/20">
                                        <span class="material-symbols-rounded text-lg">payments</span>
                                        Buy Video: <?php echo e(showAmount($video->price)); ?>

                                    </button>
                                    
                                    <button onclick="window.dispatchEvent(new CustomEvent('open-channel-membership-modal'))" class="w-fit px-6 sm:px-8 h-10 sm:h-12 rounded-lg sm:rounded-xl bg-white/10 border border-white/10 text-white font-black text-[10px] sm:text-[11px] uppercase tracking-widest hover:bg-white/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                        <span class="material-symbols-rounded text-lg">loyalty</span>
                                        Subscribe to the Plan
                                    </button>
                                </div>
                                
                                <div class="mt-4 sm:mt-6 flex items-center justify-center gap-4 opacity-30 text-white">
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-rounded text-[14px] text-white">verified_user</span>
                                        <span class="text-[7px] font-black uppercase tracking-widest text-white">Secure</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-rounded text-[14px] text-white">bolt</span>
                                        <span class="text-[7px] font-black uppercase tracking-widest text-white">Instant</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                        <script>
                            function payNow() {
                                <?php if(auth()->guard()->check()): ?>
                                    Swal.fire({
                                        title: 'Please Wait',
                                        text: 'Initializing payment...',
                                        icon: 'info',
                                        showConfirmButton: false,
                                        allowOutsideClick: false,
                                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                        didOpen: () => { Swal.showLoading(); }
                                    });

                                    // Step 1: Create Razorpay order on server
                                    fetch("<?php echo e(route('videos.create-order', $video)); ?>", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        Swal.close();
                                        if (data.error) {
                                            Swal.fire({
                                                title: 'Error',
                                                text: data.error,
                                                icon: 'error',
                                                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000'
                                            });
                                            return;
                                        }

                                        // Step 2: Open Razorpay checkout with order_id
                                        var options = {
                                            "key": data.key,
                                            "amount": data.amount,
                                            "currency": data.currency,
                                            "name": "<?php echo e($general->site_name); ?>",
                                            "description": "Purchase Video: <?php echo e($video->title); ?>",
                                            "image": "<?php echo e(getImage(getFilePath('logoIcon') .'/logo.png')); ?>",
                                            "order_id": data.order_id,
                                            "handler": function (response) {
                                                Swal.fire({
                                                    title: 'Please Wait',
                                                    text: 'Verifying payment...',
                                                    icon: 'info',
                                                    showConfirmButton: false,
                                                    allowOutsideClick: false,
                                                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                                    didOpen: () => { Swal.showLoading(); }
                                                });

                                                // Step 3: Verify payment on server
                                                fetch("<?php echo e(route('videos.purchase', $video)); ?>", {
                                                    method: "POST",
                                                    headers: {
                                                        "Content-Type": "application/json",
                                                        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                                                    },
                                                    body: JSON.stringify({
                                                        razorpay_payment_id: response.razorpay_payment_id,
                                                        razorpay_order_id: response.razorpay_order_id,
                                                        razorpay_signature: response.razorpay_signature,
                                                        trx: data.trx
                                                    })
                                                })
                                                .then(res => res.json())
                                                .then(result => {
                                                    if (result.success) {
                                                        window.location.reload();
                                                    } else {
                                                        Swal.close();
                                                        Swal.fire({
                                                            title: 'Error',
                                                            text: result.error || 'Payment verification failed',
                                                            icon: 'error',
                                                            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000'
                                                        });
                                                    }
                                                })
                                                .catch(err => {
                                                    Swal.close();
                                                    Swal.fire({
                                                        title: 'Error',
                                                        text: 'An unexpected error occurred during verification.',
                                                        icon: 'error',
                                                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000'
                                                    });
                                                });
                                            },
                                        "prefill": {
                                            "name": "<?php echo e(auth()->user()->fullname ?? auth()->user()->username); ?>",
                                            "email": "<?php echo e(auth()->user()->email); ?>"
                                        },
                                        "theme": {
                                            "color": "#F97316"
                                        }
                                    };
                                    var rzp1 = new Razorpay(options);
                                    rzp1.open();
                                })
                                .catch(err => {
                                    Swal.close();
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'An unexpected error occurred while creating the order.',
                                        icon: 'error',
                                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000'
                                    });
                                });
                                <?php else: ?>
                                    window.showLoginAlert('purchase this video');
                                <?php endif; ?>
                            }
                        </script>

                        <!-- Age Restriction Layer -->
                        <?php if($video->is_age_restricted && $isAgeVerified): ?>
                        <div x-data="{ confirmed: false }" 
                             x-show="!confirmed" 
                             class="absolute inset-0 z-[10000] bg-black/60 backdrop-blur-3xl flex flex-col items-center justify-center p-6 text-center animate-in fade-in duration-500">
                            
                            <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-2xl sm:rounded-3xl bg-red-600/20 text-red-600 flex items-center justify-center mb-4 sm:mb-8 shadow-xl border border-red-600/30">
                                <span class="material-symbols-rounded text-4xl sm:text-6xl material-symbols-filled">warning</span>
                            </div>

                            <h3 class="text-xl sm:text-3xl font-black text-white uppercase tracking-tighter leading-tight mb-2 sm:mb-4 font-sans">Age Restricted</h3>
                            <p class="text-white/60 text-[9px] sm:text-xs font-bold uppercase tracking-[0.2em] max-w-[280px] mb-6 sm:mb-10 leading-relaxed font-sans">
                                This content is restricted to adult audiences only. Please confirm you are over 18.
                            </p>

                            <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4 w-full max-w-[400px] px-2 sm:px-0">
                                <button @click="confirmed = true; 
                                        const playMedia = () => {
                                            const plyr = window.plyrPlayer;
                                            const v = document.getElementById('player');
                                            if (plyr) {
                                                let p = plyr.play();
                                                if (p && p.catch) {
                                                    p.catch(() => {
                                                        if (plyr.media) {
                                                            plyr.media.muted = true;
                                                            plyr.play().then(() => { setTimeout(() => { plyr.media.muted = false; }, 300); }).catch(() => {});
                                                        }
                                                    });
                                                }
                                            } else if (v) {
                                                let p = v.play();
                                                if (p && p.catch) {
                                                    p.catch(() => {
                                                        v.muted = true;
                                                        v.play().then(() => { setTimeout(() => { v.muted = false; }, 300); }).catch(() => {});
                                                    });
                                                }
                                            }
                                        };
                                        playMedia();" 
                                        class="w-full h-10 sm:h-12 bg-red-600 hover:bg-red-700 text-white rounded-lg sm:rounded-xl font-black text-[8px] sm:text-[10px] uppercase tracking-[0.1em] sm:tracking-widest transition-all shadow-lg flex items-center justify-center font-sans">
                                    I am 18+ & Wish to Watch
                                </button>
                                <a href="<?php echo e(route('home')); ?>" 
                                   class="w-full h-10 sm:h-12 bg-white/5 hover:bg-white/10 text-white/60 hover:text-white rounded-lg sm:rounded-xl font-black text-[8px] sm:text-[10px] uppercase tracking-[0.1em] sm:tracking-widest transition-all border border-white/10 flex items-center justify-center font-sans">
                                    Go Back
                                </a>
                            </div>

                            <p class="mt-8 text-[8px] font-black text-white/20 uppercase tracking-widest font-sans">Platform Safety Protocol v2.4</p>
                        </div>
                        <?php endif; ?>

                        <!-- Mobile-Native UI Overlay (Native Android High-Fidelity Look) -->
                        <div class="yt-mobile-overlay transition-all duration-300 group" x-cloak 
                             :class="(showUI && !videoHasError) ? 'visible pointer-events-auto' : 'opacity-0 pointer-events-none'"
                             @dblclick.prevent.stop=""
                             @click.self="showUI = !showUI; if(showUI) { startHideTimer(); if(player) player.toggleControls(true); } else { if(player) player.toggleControls(false); }">
                            
                            <!-- Vignette/Gradient Backdrop for Contrast -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/60 pointer-events-none" :class="isAdjustingVolume ? 'opacity-0' : ''"></div>

                            <div class="yt-mobile-top relative z-10 transition-opacity duration-300" :class="isAdjustingVolume ? 'opacity-0' : ''" @click.stop>
                                <button @click="toggleMiniPlayer" class="p-3 text-white/90 hover:text-white transition-colors">
                                    <span class="material-symbols-rounded text-3xl" x-text="miniPlayer ? 'expand_less' : 'expand_more'">expand_more</span>
                                </button>
                                <div class="flex-1"></div>
                                <div class="flex items-center gap-2 pr-2">
                                    <button @click="if(player) player.captions.active = !player.captions.active" class="p-3 text-white/90 hover:text-white transition-colors relative">
                                        <span class="material-symbols-rounded text-2xl" :class="player?.captions?.active ? 'text-red-500' : ''" x-text="player?.captions?.active ? 'closed_caption_disabled' : 'closed_caption'"></span>
                                        <div x-show="player?.captions?.active" class="absolute bottom-2 left-1/2 -translate-x-1/2 w-1 h-1 bg-red-500 rounded-full"></div>
                                    </button>
                                    <button @click="console.log('Settings Button Clicked'); window.dispatchEvent(new CustomEvent('toggle-mobile-settings'))" class="p-3 text-white/90 hover:text-white transition-colors">
                                        <span class="material-symbols-rounded text-2xl">settings</span>
                                    </button>
                                </div>
                            </div>
                            <div class="yt-mobile-center relative z-10 transition-opacity duration-300" :class="isAdjustingVolume ? 'opacity-0' : ''" @click.stop>
                                <button @click="if(player) player.currentTime -= 10" @dblclick.stop="" class="w-12 h-12 flex items-center justify-center bg-white/5 backdrop-blur-md rounded-full border border-white/10 active:scale-90 transition-transform flex-shrink-0">
                                    <span class="material-symbols-rounded">replay_10</span>
                                </button>
                                
                                <button @click="togglePlay" @dblclick.stop="" class="yt-play-btn active:scale-95 transition-all group/play relative">
                                    <div class="absolute inset-0 bg-white/5 rounded-full blur-xl group-hover/play:scale-110 transition-transform"></div>
                                    <span class="material-symbols-rounded relative z-10" x-text="playing ? 'pause' : 'play_arrow'"></span>
                                </button>
 
                                <button @click="if(player) player.currentTime += 10" @dblclick.stop="" class="w-12 h-12 flex items-center justify-center bg-white/5 backdrop-blur-md rounded-full border border-white/10 active:scale-90 transition-transform flex-shrink-0">
                                    <span class="material-symbols-rounded">forward_10</span>
                                </button>
                            </div>
 


                            <div class="yt-mobile-bottom relative z-10 pb-4" @click.stop>
                                <div class="flex items-center gap-2 font-bold text-[12px] tracking-tight">
                                    <span class="text-white" x-text="formatTime(currentTime)"></span>
                                    <span class="text-white/40">/</span>
                                    <span class="text-white/40" x-text="formatTime(duration)"></span>
                                </div>
                                <div class="flex-1 transition-opacity duration-300" :class="isAdjustingVolume ? 'opacity-0' : ''"></div>
                                
                                <!-- Volume Control -->
                                <div class="flex items-center group/volume mr-2 relative z-20" 
                                     @mouseenter="isAdjustingVolume = true;" 
                                     @mouseleave="if(!isDraggingVolume) { isAdjustingVolume = false; startHideTimer(); }">
                                    
                                    <!-- Vertical Volume Control -->
                                    <div class="yt-vertical-volume" 
                                         x-show="showVerticalVolume && showUI"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 translate-y-4"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 translate-y-4"
                                         @mouseenter="isAdjustingVolume = true"
                                         @mouseleave="if(!isDraggingVolume) { isAdjustingVolume = false; startHideTimer(); }"
                                         @click.stop>
                                        <span @click.stop="if(player) { player.volume = Math.min(1, player.volume + 0.1); volume = player.volume; player.muted = false; muted = false; }" style="color: #F97316 !important;" class="material-symbols-rounded text-xl cursor-pointer hover:brightness-125 transition-all hover:scale-110 active:scale-95">volume_up</span>
                                        <input type="range" min="0" max="1" step="0.01" 
                                               :value="volume"
                                               :style="`background: linear-gradient(to top, #F97316 ${volume * 100}%, rgba(255,255,255,0.2) ${volume * 100}%) no-repeat center center / 4px 100% !important;`"
                                               @mousedown="isAdjustingVolume = true; isDraggingVolume = true"
                                               @mouseup="isDraggingVolume = false"
                                               @touchstart="isAdjustingVolume = true; isDraggingVolume = true"
                                               @touchend="isDraggingVolume = false"
                                               @input="if(player) { player.volume = $event.target.value; volume = parseFloat($event.target.value); player.muted = (volume <= 0.01); muted = player.muted; }"
                                               class="yt-vertical-slider">
                                        <span @click.stop="if(player) { player.volume = Math.max(0, player.volume - 0.1); volume = player.volume; player.muted = (volume <= 0.01); muted = player.muted; }" style="color: #F97316 !important;" class="material-symbols-rounded text-xl cursor-pointer hover:brightness-125 transition-all hover:scale-110 active:scale-95" x-text="volume <= 0.01 ? 'volume_off' : 'volume_down'">volume_down</span>
                                    </div>

                                    <button @click.stop="showVerticalVolume = !showVerticalVolume; isAdjustingVolume = showVerticalVolume; if(player && player.muted) { player.muted = false; muted = false; } if(!showVerticalVolume) startHideTimer();" class="p-3 text-white/90 hover:text-white transition-colors" title="Adjust Volume">
                                        <span class="material-symbols-rounded text-2xl" x-text="(muted || volume <= 0.01) ? 'volume_off' : (volume < 0.5 ? 'volume_down' : 'volume_up')">volume_up</span>
                                    </button>
                                </div>

                                <button @click="toggleFullscreen" class="p-3 text-white/90 hover:text-white transition-colors" :class="isAdjustingVolume ? 'opacity-0' : ''">
                                    <span class="material-symbols-rounded text-2xl">fullscreen</span>
                                </button>
                            </div>

                            <!-- High-Fidelity Custom Seekbar -->
                            <div class="yt-seek-bar group/seek transition-opacity duration-300" 
                                 :class="(isAdjustingVolume && isMobile) ? 'opacity-0 pointer-events-none' : ''"
                                 @click.stop="seek($event)"
                                 @mousedown="isSeeking = true"
                                 @touchstart="isSeeking = true"
                                 @mousemove.window="if(isSeeking) seek($event)"
                                 @touchmove.window="if(isSeeking) seek($event)"
                                 @mouseup.window="isSeeking = false"
                                 @touchend.window="isSeeking = false">
                                <div class="yt-seek-bar-track">
                                    <div class="yt-seek-progress transition-all duration-75" :style="`width: ${progress}%`">
                                        <div class="yt-seek-knob" x-show="showUI"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?> 
                    </div>
                    <?php endif; ?>
                </div>

<script>
        function isVerticalVideo(video) {
            if (!video || !video.videoWidth || !video.videoHeight) return false;
            return video.videoHeight > video.videoWidth * 1.2;
        }

        function getVideoAspectClass(video) {
            return isVerticalVideo(video) ? 'aspect-[9/16]' : 'aspect-video';
        }

        document.addEventListener('alpine:init', () => {
                            Alpine.data('playerHub', (hlsPath) => ({
                                showPaywall: <?php echo e((!$hasAccess && $isAgeVerified && !$previewMode) ? 'true' : 'false'); ?>,
                                previewMode: <?php echo e($previewMode ? 'true' : 'false'); ?>,
                                previewLimit: <?php echo e($teaserSeconds); ?>,
                                previewDone: false,
                                videoHasError: false,
                                playing: false,
                                showUI: true,
                                currentTime: 0,
                                duration: 0,
                                volume: 1,
                                muted: false,
                                isMobile: false,
                                isAdjustingVolume: false,
                                showSettings: false,
                                progress: 0,
                                hideTimeout: null,
                                player: null,
                                miniPlayer: false,
                                isSeeking: false,
                                isDraggingVolume: false,
                                showVerticalVolume: false,
                                isZoomed: false,
                                zoomLevelText: '',
                                showZoomIndicator: false,
                                 zoomTimeout: null,
                                viewCounted: false,
                                lastProgressUpdate: 0,
                                progressInterval: null,
                                audioTracks: [],
                                activeAudioTrack: -1,
                                isVerticalVideo: false,
                                videoAspectClass: 'aspect-video',

                                logEvent(type, value = null, secondsAt = null) {
                                    fetch('<?php echo e(route("analytics.log_event")); ?>', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                        },
                                        body: JSON.stringify({
                                            video_id: '<?php echo e($video->id); ?>',
                                            event_type: type,
                                            value: value,
                                            seconds_at: secondsAt ?? (this.player ? this.player.currentTime : 0)
                                        })
                                    }).catch(e => console.error('Failed to log event', e));
                                },

                                updateProgress(force = false) {
                                    if (!this.player) return;
                                    const now = Date.now();
                                    // Update every 10 seconds or if forced (e.g. on pause/close)
                                    if (!force && (now - this.lastProgressUpdate < 10000)) return;
                                    
                                    this.lastProgressUpdate = now;
                                    const isCompleted = this.player.currentTime / this.player.duration > 0.95;

                                    fetch('<?php echo e(route("analytics.update_progress")); ?>', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                        },
                                        body: JSON.stringify({
                                            video_id: '<?php echo e($video->id); ?>',
                                            progress_seconds: this.player.currentTime,
                                            watch_delta: this.activeSeconds || 0,
                                            total_duration: this.player.duration,
                                            is_completed: isCompleted,
                                            device_type: this.isMobile ? 'mobile' : 'desktop'
                                        })
                                    }).then(() => { this.activeSeconds = 0; }).catch(e => console.error('Failed to update progress', e));
                                },

                                toggleMiniPlayer() {
                                    if (this.previewMode && !this.previewDone) {
                                        Swal.fire({
                                            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'info',
                                            title: 'Preview only — buy to use mini-player',
                                            background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                        });
                                        return;
                                    }
                                    try {
                                        const video = this.$refs.mainPlayer;
                                        const state = {
                                            title: '<?php echo e(addslashes($video->title)); ?>',
                                            url: hlsPath,
                                            slug: '<?php echo e($video->slug); ?>',
                                            currentTime: video ? video.currentTime : 0
                                        };
                                        if (!state.url) {
                                            Swal.fire({
                                                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'info',
                                                title: 'Video not ready for mini-player',
                                                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                                                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                            });
                                            return;
                                        }
                                        localStorage.setItem('miniPlayerState', JSON.stringify(state));
                                        window.location.href = '/';
                                    } catch (e) {
                                        console.error('Miniplayer toggle failed', e);
                                    }
                                },

                                init() {
                                    this.isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                                    this.showUI = true; 
                                    
                                    console.log("playerHub Initialized with hlsUrl:", hlsPath);
                                    // Wait for deferred CDN libraries (Plyr/Hls) - script order
                                    // is not guaranteed because Alpine loads from layout head.
                                    this.whenLibrariesReady(() => this.initPlayer(hlsPath));
                                    this.watchTimer = setInterval(() => {
                                        if (this.player && !this.player.paused && !this.miniPlayer) {
                                            this.activeSeconds = (this.activeSeconds || 0) + 1;
                                        }
                                    }, 1000);

                                    window.addEventListener('toggle-zoom', () => {
                                        this.isZoomed = !this.isZoomed;
                                        window.isZoomedGlobal = this.isZoomed;
                                        this.showZoom(this.isZoomed ? 'Zoomed to fill' : 'Original');
                                    });

                                    // Pause video on tab switch or minimize
                                    document.addEventListener('visibilitychange', () => {
                                        if (document.hidden && this.player && !this.player.paused) {
                                            this.player.pause();
                                        }
                                    });

                                    // Auto-polling for Bunny processing status
                                    <?php if($video->isBunnyVideo() && $video->bunny_status !== 'ready'): ?>
                                        this.pollStatus();
                                    <?php endif; ?>
                                 },

                                whenLibrariesReady(cb) {
                                    let tries = 0;
                                    const check = () => {
                                        if (typeof window.Plyr !== 'undefined' && typeof window.Hls !== 'undefined') {
                                            cb();
                                            return;
                                        }
                                        if (++tries > 240) { // 240 x 50ms = 12s max
                                            console.error('[playerHub] Plyr/Hls libraries failed to load');
                                            return;
                                        }
                                        setTimeout(check, 50);
                                    };
                                    check();
                                },

                                syncAudioTracks(hls) {
                                    if (!hls || !Array.isArray(hls.audioTracks)) return;
                                    this.audioTracks = hls.audioTracks.map((t, i) => ({
                                        id: t.id ?? i,
                                        name: t.name || (t.lang ? t.lang.toUpperCase() : 'Audio ' + (i + 1)),
                                        lang: t.lang || ''
                                    }));
                                    this.activeAudioTrack = typeof hls.audioTrack === 'number' && hls.audioTrack >= 0 ? hls.audioTrack : 0;
                                    console.log('[HLS] Audio tracks:', this.audioTracks.map(t => t.name));
                                },

                                setAudioTrack(id) {
                                    if (!window.hlsPlayer) return;
                                    window.hlsPlayer.audioTrack = id;
                                    this.activeAudioTrack = id;
                                    window.dispatchEvent(new CustomEvent('audio-track-switched', { detail: { id: id } }));
                                },

                                pollStatus() {
                                    setInterval(async () => {
                                        try {
                                            const response = await fetch('/videos/<?php echo e($video->slug); ?>/status');
                                            const data = await response.json();
                                            if (data.success && data.bunny_status === 'ready') {
                                                window.location.reload();
                                            }
                                        } catch (e) {
                                            console.error("Status poll failed", e);
                                        }
                                    }, 5000);
                                },

                                initPlayer(hlsUrl) {
                                    const video = this.$refs.mainPlayer;
                                    if (!video) {
                                        console.log("No video element found (might be scheduled/blocked).");
                                        return;
                                    }

                                    const defaultOptions = {
                                        autoplay: false,
                                        controls: ['play-large', 'play', 'progress', 'current-time', 'duration', 'mute', 'volume', 'captions', 'settings', 'pip', 'fullscreen'],
                                        settings: ['quality', 'speed'],
                                        invertTime: false,
                                        hideControls: true,
                                        clickToPlay: false, 
                                        fullscreen: {
                                            enabled: true,
                                            fallback: true,
                                            iosNative: true
                                        }
                                    };

                                        if (Hls.isSupported() && hlsUrl && hlsUrl.includes('.m3u8')) {
                                            // Initialize Plyr first
                                            this.setupPlyr(video, defaultOptions);

                                            const hls = new Hls({
                                                // === Fast-Start Optimizations ===
                                                maxBufferLength: 10,           // Only buffer 10s initially (was 30s)
                                                maxMaxBufferLength: 120,       // Cap max buffer at 2 min (was 10 min)
                                                maxBufferSize: 30 * 1000000,   // 30MB max buffer size
                                                maxBufferHoleTolerance: 0.5,
                                                startLevel: 0,                 // Start with lowest quality for instant first frame
                                                capLevelOnFPSDrop: true,       // Auto-reduce quality if device can't keep up
                                                testBandwidth: true,           // Measure bandwidth then upgrade quality
                                                abrEwmaDefaultEstimate: 500000, // Assume 500kbps initially (conservative)
                                                nudgeOffset: 0.1,
                                                nudgeMaxRetry: 10,
                                                enableWorker: true,
                                                lowLatencyMode: false,
                                                // Faster manifest/fragment loading
                                                manifestLoadingTimeOut: 8000,   // 8s timeout (was default 10s)
                                                manifestLoadingMaxRetry: 3,
                                                levelLoadingTimeOut: 8000,
                                                fragLoadingTimeOut: 15000,
                                                xhrSetup: function(xhr, url) {
                                                    xhr.withCredentials = false;
                                                }
                                            });
                                            window.hlsPlayer = hls;
                                            hls.loadSource(hlsUrl);
                                            hls.attachMedia(video);
                                            
                                            hls.on(Hls.Events.MANIFEST_PARSED, () => {
                                                const qualities = hls.levels.map(l => l.height);
                                                console.log('[HLS] Manifest Parsed. Levels:', qualities);

                                                // Capture audio tracks served by Bunny (from master playlist)
                                                this.syncAudioTracks(hls);
                                                hls.on(Hls.Events.AUDIO_TRACKS_UPDATED, () => this.syncAudioTracks(hls));
                                                hls.on(Hls.Events.AUDIO_TRACK_SWITCHED, (data) => {
                                                    this.activeAudioTrack = data.id;
                                                    window.dispatchEvent(new CustomEvent('audio-track-switched', { detail: { id: data.id } }));
                                                });
                                                
                                                // Sync with Plyr
                                                if (this.player) {
                                                    this.player.config.quality.options = [0, ...qualities];
                                                    
                                                    if (qualities.includes(480)) {
                                                        const idx = hls.levels.findIndex(l => l.height === 480);
                                                        hls.currentLevel = idx;
                                                        hls.loadLevel = idx;
                                                        this.player.quality = 480;
                                                    } else {
                                                        this.player.quality = 0;
                                                    }
                                                }
                                                this.videoAspectClass = 'aspect-video';

                                                // Autoplay after HLS is ready
                                                <?php if(($hasAccess || $previewMode) && !$video->is_age_restricted): ?>
                                                video.muted = false;
                                                video.play().then(() => {
                                                    this.playing = true;
                                                }).catch(e => {
                                                    console.log('Autoplay unmuted blocked, falling back to muted autoplay:', e.message);
                                                    video.muted = true;
                                                    video.play().then(() => {
                                                        this.playing = true;
                                                        this.muted = true;
                                                        const enableSound = () => {
                                                            if (this.player) {
                                                                this.player.muted = false;
                                                                this.muted = false;
                                                            } else if (video) {
                                                                video.muted = false;
                                                            }
                                                            window.removeEventListener('touchstart', enableSound);
                                                            window.removeEventListener('click', enableSound);
                                                        };
                                                        window.addEventListener('touchstart', enableSound, { once: true });
                                                        window.addEventListener('click', enableSound, { once: true });
                                                    }).catch(err => console.log('Autoplay blocked:', err.message));
                                                });
                                                <?php endif; ?>
                                            });

                                            // Get duration once HLS media is attached and loaded
                                            video.addEventListener('loadedmetadata', () => {
                                                if (video.duration && !isNaN(video.duration) && video.duration !== Infinity) {
                                                    this.duration = video.duration;
                                                }
                                                // Detect vertical video
                                                this.isVerticalVideo = video.videoHeight > video.videoWidth * 1.2;
                                                this.videoAspectClass = 'aspect-video';
                                            });
                                            video.addEventListener('durationchange', () => {
                                                if (video.duration && !isNaN(video.duration) && video.duration !== Infinity) {
                                                    this.duration = video.duration;
                                                }
                                            });

                                             hls.on(Hls.Events.LEVEL_SWITCHED, (event, data) => {
                                                 const quality = hls.levels[data.level].height;
                                                 console.log('[HLS] Level switched to:', quality + 'p');
                                                 window.dispatchEvent(new CustomEvent('quality-updated', { detail: { quality: quality } }));
                                             });

                                             hls.on(Hls.Events.ERROR, (event, data) => {
                                                 if (data.fatal) {
                                                     this.logEvent('hls_fatal_error', data.details);
                                                     if (data.response && (data.response.code === 404 || data.response.code === 403)) {
                                                         this.videoHasError = true; this.$dispatch('video-unavailable');
                                                         hls.destroy();
                                                         return;
                                                     }
                                                     switch (data.type) {
                                                         case Hls.ErrorTypes.NETWORK_ERROR: 
                                                             if (data.details === 'manifestLoadError') {
                                                                 this.videoHasError = true; this.$dispatch('video-unavailable');
                                                                 hls.destroy();
                                                             } else {
                                                                 hls.startLoad();
                                                             }
                                                             break;
                                                         case Hls.ErrorTypes.MEDIA_ERROR: hls.recoverMediaError(); break;
                                                         default: 
                                                             console.error("Fatal HLS error", data);
                                                             this.videoHasError = true; this.$dispatch('video-unavailable');
                                                             hls.destroy(); 
                                                             break;
                                                     }
                                                 } else if (data.details === 'bufferStalledError' || data.details === 'bufferSeekOverHole') {
                                                     this.logEvent('buffer_stall', data.details);
                                                     video.currentTime += 0.1;
                                                 }
                                             });

                                             video.addEventListener('error', () => {
                                                 this.videoHasError = true; this.$dispatch('video-unavailable');
                                             });
                                         } else {
                                             if (hlsUrl) {
                                                 video.src = hlsUrl;
                                             }
                                             this.setupPlyr(video, defaultOptions);

                                            // Autoplay for non-HLS videos
                                            video.addEventListener('loadedmetadata', () => {
                                                if (video.duration && !isNaN(video.duration) && video.duration !== Infinity) {
                                                    this.duration = video.duration;
                                                }
                                                // Detect vertical video
                                                this.isVerticalVideo = video.videoHeight > video.videoWidth * 1.2;
                                                this.videoAspectClass = 'aspect-video';
                                                <?php if(($hasAccess || $previewMode) && !$video->is_age_restricted): ?>
                                                video.muted = false;
                                                video.play().then(() => {
                                                    this.playing = true;
                                                }).catch(e => {
                                                    console.log('Autoplay unmuted blocked, falling back to muted autoplay:', e.message);
                                                    video.muted = true;
                                                    video.play().then(() => {
                                                        this.playing = true;
                                                        this.muted = true;
                                                        const enableSound = () => {
                                                            if (this.player) {
                                                                this.player.muted = false;
                                                                this.muted = false;
                                                            } else if (video) {
                                                                video.muted = false;
                                                            }
                                                            window.removeEventListener('touchstart', enableSound);
                                                            window.removeEventListener('click', enableSound);
                                                        };
                                                        window.addEventListener('touchstart', enableSound, { once: true });
                                                        window.addEventListener('click', enableSound, { once: true });
                                                    }).catch(err => console.log('Autoplay blocked:', err.message));
                                                });
                                                <?php endif; ?>
                                            });
                                            video.addEventListener('durationchange', () => {
                                                if (video.duration && !isNaN(video.duration) && video.duration !== Infinity) {
                                                    this.duration = video.duration;
                                                }
                                            });
                                        }
                                    // Pay-per-view teaser cap (seconds from Admin → General Setting):
                                    // applies to HLS and native playback alike; pauses at the
                                    // limit — even on seek — and raises the paywall.
                                    if (this.previewMode) {
                                        video.addEventListener('timeupdate', () => {
                                            const t = this.player ? this.player.currentTime : video.currentTime;
                                            if (!this.previewDone && t >= this.previewLimit) {
                                                this.previewDone = true;
                                                this.playing = false;
                                                if (this.player) this.player.pause(); else video.pause();
                                                this.showPaywall = true;
                                            }
                                        });
                                    }
                                },

                                ensureOverlay() {
                                    const overlay = document.querySelector('.yt-mobile-overlay');
                                    const plyrContainer = this.player?.elements?.container;
                                    
                                    if (plyrContainer && overlay && overlay.parentElement !== plyrContainer) {
                                        plyrContainer.appendChild(overlay);
                                        console.log('Overlay moved to Plyr container');
                                    }

                                    // Add click/touch listeners directly to the plyrContainer (All Devices & Fullscreen Safe)
                                    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                                     if (plyrContainer && !plyrContainer._uiBound) {
                                         plyrContainer._uiBound = true;
                                         
                                         // Prevent double click/tap from toggling fullscreen on container or empty space
                                         plyrContainer.addEventListener('dblclick', (e) => {
                                             e.preventDefault();
                                             e.stopPropagation();
                                             e.stopImmediatePropagation();
                                         }, true);

                                         // Global click listener on the player container itself (Fullscreen Safe!)
                                        plyrContainer.addEventListener('click', (e) => {
                                            // IF we clicked an interactive control, do NOT toggle the UI visibility
                                            if (this.showUI && (e.target.closest('button') || e.target.closest('input') || e.target.closest('.yt-seek-bar') || e.target.closest('.yt-vertical-volume') || e.target.closest('.yt-volume-slider'))) {
                                                return;
                                            }

                                            this.showUI = !this.showUI;
                                            if (this.showUI) {
                                                this.startHideTimer();
                                                if (this.player) this.player.toggleControls(true);
                                            } else {
                                                this.showVerticalVolume = false;
                                                if (this.player) this.player.toggleControls(false);
                                            }
                                        }, true); // Use capture phase to catch clicks before they are stopped

                                        // Pinch to Zoom logic bound to player container & document for Android fullscreen support
                                        let initialDistance = 0;
                                        const handleZoomStart = (e) => {
                                            if (e.touches.length === 2) {
                                                initialDistance = Math.hypot(
                                                    e.touches[0].pageX - e.touches[1].pageX,
                                                    e.touches[0].pageY - e.touches[1].pageY
                                                );
                                            }
                                        };
                                        const handleZoomMove = (e) => {
                                            if (e.touches.length === 2 && initialDistance > 0) {
                                                const currentDistance = Math.hypot(
                                                    e.touches[0].pageX - e.touches[1].pageX,
                                                    e.touches[0].pageY - e.touches[1].pageY
                                                );
                                                // Pinch Out (Zoom to fill)
                                                if (currentDistance > initialDistance + 30 && !this.isZoomed) {
                                                    e.preventDefault();
                                                    window.dispatchEvent(new CustomEvent('toggle-zoom'));
                                                    initialDistance = currentDistance;
                                                } 
                                                // Pinch In (Contain)
                                                else if (currentDistance < initialDistance - 30 && this.isZoomed) {
                                                    e.preventDefault();
                                                    window.dispatchEvent(new CustomEvent('toggle-zoom'));
                                                    initialDistance = currentDistance;
                                                }
                                            }
                                        };
                                        const handleZoomEnd = (e) => {
                                            if (e.touches.length < 2) {
                                                initialDistance = 0;
                                            }
                                        };

                                        // Attach directly to plyrContainer so it triggers during Android/Mobile fullscreen mode
                                        plyrContainer.addEventListener('touchstart', handleZoomStart, { passive: true });
                                        plyrContainer.addEventListener('touchmove', handleZoomMove, { passive: false });
                                        plyrContainer.addEventListener('touchend', handleZoomEnd, { passive: true });

                                        // Fallback attach to document
                                        document.addEventListener('touchstart', handleZoomStart, { passive: true });
                                        document.addEventListener('touchmove', handleZoomMove, { passive: false });
                                        document.addEventListener('touchend', handleZoomEnd, { passive: true });

                                        // Desktop Specific: Also show UI on mouse move for better UX on large screens
                                        if (!isMobile) {
                                            plyrContainer.addEventListener('mousemove', () => {
                                                this.showUI = true;
                                                this.startHideTimer();
                                                if (this.player) this.player.toggleControls(true);
                                            });
                                        }
                                    }
                                },

                                 setupPlyr(video, options) {
                                     if (typeof window.Plyr === 'undefined') {
                                         console.error('[playerHub] Plyr missing - skipping player init');
                                         return;
                                     }
                                     if (this.player) this.player.destroy();
                                     this.player = new Plyr(video, options);
                                     window.plyrPlayer = this.player;

                                     this.player.on('ready', () => {
                                         if (this.player.duration && !isNaN(this.player.duration)) {
                                             this.duration = this.player.duration;
                                         }
                                         
                                         this.volume = this.player.volume;
                                         this.muted = this.player.muted || (this.volume <= 0.01);
                                         
                                         this.injectTheatreButton();
                                         this.injectMiniPlayerButton();
                                         this.ensureOverlay();
                                     });

                                     this.player.on('volumechange', () => {
                                         if (!this.isDraggingVolume && this.player) {
                                             this.volume = this.player.volume;
                                             this.muted = this.player.muted || (this.volume <= 0.01);
                                         }
                                     });

                                     this.player.on('loadedmetadata', () => {
                                         if (this.player.duration && !isNaN(this.player.duration)) {
                                             this.duration = this.player.duration;
                                         }
                                         const v = this.player.media;
                                         if (v && v.videoHeight > v.videoWidth * 1.2) {
                                             this.isVerticalVideo = true;
                                             this.videoAspectClass = 'aspect-video';
                                         }
                                         
                                         this.player.on('timeupdate', () => {
                                           if (!this.player) return;
                                           this.currentTime = this.player.currentTime || 0;
                                           this.duration = this.player.duration || 0;
                                           this.progress = this.duration > 0 ? (this.currentTime / this.duration) * 100 : 0;
                                           if (this.currentTime >= 3 && !this.viewCounted) {
                                               this.viewCounted = true;
                                               this.updateProgress(true);
                                               if (typeof window.triggerBunnyViewPing === 'function') {
                                                   window.triggerBunnyViewPing();
                                               }
                                           }
                                         });

                                         this.player.on('play', () => {
                                             if (window.triggerBunnyViewPing) window.triggerBunnyViewPing(); 
                                             this.playing = true; 
                                             this.startHideTimer();
                                         });

                                         this.player.on('pause', () => {
                                             this.playing = false;
                                             this.showUI = true;
                                             if (this.player) this.player.toggleControls(true);
                                         });

                                         setTimeout(() => this.ensureOverlay(), 100);
                                     });

                                      this.player.on('enterfullscreen', () => {
                                          setTimeout(() => this.ensureOverlay(), 100);
                                          if (screen.orientation && screen.orientation.lock) {
                                              screen.orientation.lock('landscape').catch(() => {});
                                          }
                                      });

                                      this.player.on('exitfullscreen', () => {
                                          setTimeout(() => this.ensureOverlay(), 100);
                                          if (screen.orientation && screen.orientation.unlock) {
                                              screen.orientation.unlock();
                                          }
                                      });
                                   },

                                   seek(e) {
                                       if (!this.player || !this.duration) return;
                                       const bar = document.querySelector('.yt-seek-bar');
                                       if (!bar) return;
                                       const rect = bar.getBoundingClientRect();
                                       const clientX = e.touches && e.touches.length > 0 ? e.touches[0].clientX : e.clientX;
                                       if (typeof clientX !== 'number') return;
                                       const offsetX = Math.max(0, Math.min(clientX - rect.left, rect.width));
                                       const percentage = rect.width > 0 ? offsetX / rect.width : 0;
                                       const targetTime = percentage * this.duration;
                                       this.progress = percentage * 100;
                                       this.currentTime = targetTime;
                                       this.player.currentTime = targetTime;
                                   },

                                  showZoom(text) {
                                      this.zoomLevelText = text;
                                      this.showZoomIndicator = true;
                                      clearTimeout(this.zoomTimeout);
                                      this.zoomTimeout = setTimeout(() => {
                                          this.showZoomIndicator = false;
                                      }, 1500);
                                      var video = this.$refs.mainPlayer || document.getElementById('player');
                                      if (video) {
                                          video.style.objectFit = this.isZoomed ? 'cover' : 'contain';
                                          video.style.transform = 'none';
                                          const container = video.closest('.plyr');
                                          if (container) {
                                              if (this.isZoomed) {
                                                  container.classList.add('plyr-zoomed');
                                              } else {
                                                  container.classList.remove('plyr-zoomed');
                                              }
                                          }
                                      }
                                  },

                                  startHideTimer() {
                                      if (!this.playing || this.isAdjustingVolume) return;
                                      clearTimeout(this.hideTimeout);
                                      this.hideTimeout = setTimeout(() => { 
                                          if (this.playing && !this.isAdjustingVolume) {
                                              this.showUI = false; 
                                              this.showVerticalVolume = false;
                                              if (this.player) this.player.toggleControls(false);
                                          }
                                      }, 2000);
                                  },

                                  toggleUI() {
                                       this.showUI = !this.showUI;
                                       if (this.showUI) this.startHideTimer();
                                   },

                                   togglePlay() {
                                       if (this.player) {
                                           if (this.player.muted) {
                                               this.player.muted = false;
                                               this.muted = false;
                                               if (this.player.volume <= 0.05) {
                                                   this.player.volume = 1;
                                                   this.volume = 1;
                                               }
                                           }
                                           this.player.togglePlay();
                                       } else {
                                           const video = this.$refs.mainPlayer || document.getElementById('player');
                                           if (video) {
                                               if (video.muted) video.muted = false;
                                               if (video.paused) video.play(); else video.pause();
                                           }
                                       }
                                   },

                                   toggleFullscreen() {
                                       const video = this.$refs.mainPlayer || document.getElementById('player') || (this.player ? this.player.media : null);
                                       const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);

                                       if (isIOS && video) {
                                           if (video.webkitDisplayingFullscreen) {
                                               if (typeof video.webkitExitFullscreen === 'function') {
                                                   video.webkitExitFullscreen();
                                                   return;
                                               }
                                           } else {
                                               if (typeof video.webkitEnterFullscreen === 'function') {
                                                   video.webkitEnterFullscreen();
                                                   return;
                                               }
                                               if (typeof video.webkitSetPresentationMode === 'function') {
                                                   video.webkitSetPresentationMode('fullscreen');
                                                   return;
                                               }
                                           }
                                       }

                                       if (!this.player) return;
                                       const isFs = this.player.fullscreen.active;
                                       this.player.fullscreen.toggle();
                                       if (screen.orientation) {
                                           if (!isFs && screen.orientation.lock) {
                                               screen.orientation.lock('landscape').catch(() => {});
                                           } else if (isFs && screen.orientation.unlock) {
                                               screen.orientation.unlock();
                                           }
                                       }
                                   },

                                   injectTheatreButton() {},
                                   injectMiniPlayerButton() {}
                               }));
                           });

                        function formatTime(seconds) {
                            if (!seconds || isNaN(seconds)) return '0:00';
                            const h = Math.floor(seconds / 3600);
                            const m = Math.floor((seconds % 3600) / 60);
                            const s = Math.floor(seconds % 60);
                            if (h > 0) return `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                            return `${m}:${s.toString().padStart(2, '0')}`;
                        }
                    </script>

                    <div x-show="!videoUnavailable" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" :class="theatre ? 'max-w-[1280px] mx-auto px-4 lg:px-0 mt-2 lg:mt-6 pt-0' : '-mt-2 sm:-mt-1 lg:mt-6 px-4 sm:px-0 pt-0'">
                        
                        <div class="mb-4">
                            <?php if($video->location): ?>
                                <div class="flex items-center gap-1 text-[12px] font-black text-blue-600 uppercase tracking-tighter mb-1.5 hover:underline cursor-pointer">
                                    <span class="material-symbols-rounded text-[14px]">pin_drop</span>
                                    <?php echo e($video->location); ?>

                                </div>
                            <?php endif; ?>
                            <h1 class="text-[18px] sm:text-[22px] font-black text-gray-900 dark:text-white leading-tight mb-2 line-clamp-2 lg:line-clamp-none">
                                <?php if($video->categories->count() > 0): ?>
                                    <a href="<?php echo e(route('category.show', $video->categories->first()->slug)); ?>" class="inline-block px-2 py-0.5 rounded text-[10px] font-black bg-orange-500/10 text-orange-600 dark:bg-orange-500/20 dark:text-orange-400 uppercase tracking-widest hover:bg-orange-500 hover:text-white dark:hover:bg-orange-500 dark:hover:text-white transition-all border border-orange-500/20 shadow-sm mr-2 align-middle relative -top-0.5">
                                        <?php echo e($video->categories->first()->name); ?>

                                    </a>
                                <?php endif; ?>
                                <span class="inline"><?php echo preg_replace('/#([\p{L}\p{M}\p{N}_]+)/u', '<a href="/search?q=%23$1" class="text-blue-600 dark:text-blue-400 hover:underline">#$1</a>', e($video->title)); ?></span>
                            </h1>
                            <p class="text-[12px] font-bold text-gray-500 dark:text-[#AAAAAA]">
                                @<span class="hover:underline cursor-pointer"><?php echo e($video->user->name); ?></span> and others &bull; <?php echo e(number_format($video->views_count)); ?> views &bull; <?php echo e($video->created_at->diffForHumans()); ?> <span @click="$dispatch('open-description')" class="text-gray-900 dark:text-white ml-1 cursor-pointer hover:underline">...more</span>
                            </p>
                        </div>
                        
                        <!-- NEW Channel Info & Actions Row -->
                        <div class="flex flex-wrap items-center justify-between gap-y-4 gap-x-4 mb-6 pb-4 border-b dark:border-white/5">
                            <div class="flex items-center gap-4 md:gap-6 shrink-0">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <a href="<?php echo e($video->user?->channel ? route('channels.show', $video->user->channel) : '#'); ?>" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-800 flex items-center justify-center shrink-0 border border-gray-100 dark:border-white/10">
                                        <?php if($video->user->channel && $video->user->channel->avatar): ?>
                                            <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $video->user->channel->avatar)); ?>" alt="avatar" class="w-full h-full object-cover shrink-0">
                                        <?php elseif($video->user->image): ?>
                                            <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $video->user->image)); ?>" alt="avatar" class="w-full h-full object-cover shrink-0">
                                        <?php else: ?>
                                            <?php
                                                $fallbackName = $video->user->channel?->name ?? $video->user->name;
                                                $words = explode(' ', trim($fallbackName));
                                                $initials = count($words) >= 2 
                                                    ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                                    : strtoupper(substr($fallbackName, 0, 2));
                                            ?>
                                            <span class="text-gray-600 dark:text-white font-black text-[15px] tracking-[0.05em]"><?php echo e($initials); ?></span>
                                        <?php endif; ?>
                                    </a>
                                    <div class="flex flex-col">
                                        <a href="<?php echo e($video->user?->channel ? route('channels.show', $video->user->channel) : '#'); ?>" class="text-[14px] font-black text-gray-900 dark:text-white leading-none mb-1 hover:underline whitespace-nowrap truncate max-w-[150px] sm:max-w-[250px]"><?php echo e($video->user->channel->name ?? $video->user->name); ?></a>
                                        <span class="text-[10px] font-bold text-gray-500 dark:text-[#AAAAAA] whitespace-nowrap"><span x-text="subscribersCount"><?php echo e($video->user->channel->subscribers_count ?? 0); ?></span> subscribers</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center shrink-0">
                                    <?php if(auth()->id() != $video->user_id): ?>
                                        <div class="relative" @click.away="subDropdownOpen = false">
                                            <button 
                                                @click="toggleSubscribe" 
                                                :class="subscribed ? 'bg-gray-100 dark:bg-white/10 text-gray-900 dark:text-white pr-3' : 'gradient-orange text-white shadow-orange-500/20'" 
                                                class="px-3 py-1.5 sm:px-6 sm:py-2 rounded-full font-black text-[10px] sm:text-[13px] shadow-lg transition-all active:scale-95 whitespace-nowrap flex items-center gap-1.5 sm:gap-2">
                                                
                                                
                                                <div x-show="subscribed" style="<?php echo e($isSubscribed === 'true' ? '' : 'display:none'); ?>" class="flex items-center gap-1.5 sm:gap-2">
                                                    <span class="material-symbols-rounded text-[14px] sm:text-xl"
                                                          :class="{ 'material-symbols-filled': notificationPreference === 'all' }"
                                                          x-text="notificationPreference === 'all' ? 'notifications_active' : 'notifications_off'"><?php echo e(auth()->check() && (\App\Models\Subscription::where('user_id', auth()->id())->where('channel_id', $video->user->channel->id ?? 0)->value('notification_preference') ?? 'all') === 'all' ? 'notifications_active' : 'notifications_off'); ?></span>
                                                    <span>Subscribed</span>
                                                    <span class="material-symbols-rounded text-[14px] sm:text-xl transition-transform" :class="subDropdownOpen ? 'rotate-180' : ''">keyboard_arrow_down</span>
                                                </div>

                                                <div x-show="!subscribed" style="<?php echo e($isSubscribed === 'true' ? 'display:none' : ''); ?>">
                                                    <span>Subscribe</span>
                                                </div>
                                            </button>

                                            <!-- YouTube Style Dropdown -->
                                            <div x-show="subDropdownOpen" 
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-75"
                                                 x-transition:leave-start="opacity-100 scale-100"
                                                 x-transition:leave-end="opacity-0 scale-95"
                                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#282828] rounded-xl shadow-2xl py-2 z-[100] border border-gray-100 dark:border-white/10 overflow-hidden"
                                                 style="display: none;">
                                                
                                                <button @click="setNotification('all')" class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                                    <span class="material-symbols-rounded text-xl" :class="notificationPreference === 'all' ? 'material-symbols-filled text-orange-600' : ''">notifications_active</span>
                                                    <span class="text-sm font-bold">All</span>
                                                </button>

                                                <button @click="setNotification('none')" class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                                    <span class="material-symbols-rounded text-xl" :class="notificationPreference === 'none' ? 'material-symbols-filled' : ''">notifications_off</span>
                                                    <span class="text-sm font-bold">None</span>
                                                </button>

                                                <div class="h-[1px] bg-gray-100 dark:bg-white/10 my-1"></div>

                                                <button @click="unsubscribe()" class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors text-red-600">
                                                    <span class="material-symbols-rounded text-xl">person_remove</span>
                                                    <span class="text-sm font-bold">Unsubscribe</span>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if(auth()->id() == $video->user_id): ?>
                                    <a href="<?php echo e(route('studio.analytics')); ?>" 
                                       class="px-3 py-1.5 sm:px-6 sm:py-2 bg-gray-900 dark:bg-white/10 backdrop-blur-md text-white rounded-full text-[10px] sm:text-[13px] font-black uppercase tracking-tight active:scale-90 transition-all shadow-lg border border-transparent dark:border-white/10 flex items-center gap-1.5 sm:gap-2 whitespace-nowrap">
                                        <span class="material-symbols-rounded text-[14px] sm:text-sm">analytics</span>
                                        <span>Analytics</span>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar w-full md:w-auto pb-1 md:pb-0">
                                <div class="flex items-center bg-gray-100 dark:bg-[#272727] rounded-full shrink-0 border border-transparent dark:border-white/5 shadow-sm">
                                    <button @click="toggleLike" class="flex items-center gap-1.5 px-3 py-1.5 border-r border-gray-200 dark:border-[#3F3F3F] hover:bg-gray-200 dark:hover:bg-[#3F3F3F] rounded-l-full transition-colors">
                                        <span class="material-symbols-rounded text-[18px]" :class="liked ? 'material-symbols-filled text-blue-600' : ''">thumb_up</span>
                                        <span class="font-black text-[12px]" x-text="likes"><?php echo e($video->likes->count()); ?></span>
                                    </button>
                                    <button @click="toggleDislike()" class="px-3 py-1.5 hover:bg-gray-200 dark:hover:bg-[#3F3F3F] rounded-r-full transition-colors flex items-center">
                                        <span class="material-symbols-rounded text-[18px]" :class="disliked ? 'material-symbols-filled text-red-600' : ''">thumb_down</span>
                                    </button>
                                </div>
                                <button @click="shareOpen = true" class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 dark:bg-[#272727] rounded-full shrink-0 border border-transparent dark:border-white/5 shadow-sm hover:bg-gray-200 dark:hover:bg-[#3F3F3F] transition-colors">
                                    <span class="material-symbols-rounded text-[18px]">share</span>
                                    <span class="font-black text-[12px] pr-1">Share</span>
                                </button>

                                <button @click="saveToWatchLater()" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full shrink-0 transition-all border border-transparent dark:border-white/5 shadow-sm" :class="savedWL ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-[#272727] hover:bg-gray-200 dark:hover:bg-[#3F3F3F] text-gray-900 dark:text-white'">
                                    <span class="material-symbols-rounded text-[18px]" :class="savedWL ? 'material-symbols-filled' : ''" x-text="savedWL ? 'check_circle' : 'schedule'">schedule</span>
                                    <span class="font-black text-[12px] pr-1" x-text="savedWL ? 'Saved' : 'Save'">Save</span>
                                </button>

                                <?php if(auth()->guard()->check()): ?>
                                    <?php if(auth()->id() != $video->user_id): ?>
                                <button @click="window.openReportModal('<?php echo e($video->id); ?>')" class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 dark:bg-[#272727] text-gray-900 dark:text-white rounded-full shrink-0 border border-transparent dark:border-white/5 shadow-sm hover:bg-gray-200 dark:hover:bg-[#3F3F3F] transition-colors">
                                    <span class="material-symbols-rounded text-[18px]">flag</span>
                                    <span class="font-black text-[12px] pr-1">Report</span>
                                </button>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <button onclick="window.openVideoOptions('<?php echo e($video->id); ?>', '<?php echo e(addslashes($video->title)); ?>', 'video', <?php echo e($video->isLikedBy(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($video->isInWatchLater(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($video->isInAnyPlaylist(auth()->user()) ? 'true' : 'false'); ?>, <?php echo json_encode($video->getPlaylistMembershipIds(auth()->user()), 15, 512) ?>, <?php echo e($video->user_id); ?>)" class="flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-[#272727] rounded-full shrink-0 hover:bg-gray-200 dark:hover:bg-[#3F3F3F] transition-all border border-transparent dark:border-white/5 shadow-sm">
                                    <span class="material-symbols-rounded text-[18px]">more_horiz</span>
                                </button>
                            </div>
                        </div>

                        <?php
                            $previewCommentsCount = $video->comments->count();
                            $topComment = $video->comments->first();
                            $initialTopComment = null;
                            if ($topComment) {
                                $topUser = $topComment->user;
                                $topName = $topUser->channel->name ?? $topUser->name ?? 'User';
                                $topAvatar = null;
                                if ($topUser->channel && $topUser->channel->avatar) {
                                    $topAvatar = getImage(getFilePath('channelAvatar') . '/' . $topUser->channel->avatar);
                                } elseif ($topUser->image) {
                                    $topAvatar = getImage(getFilePath('userProfile') . '/' . $topUser->image);
                                }
                                $topWords = explode(' ', trim($topName));
                                $topInitials = count($topWords) >= 2 
                                    ? strtoupper(substr($topWords[0], 0, 1) . substr($topWords[count($topWords)-1], 0, 1))
                                    : strtoupper(substr($topName, 0, 2));
                                $initialTopComment = [
                                    'author' => $topName,
                                    'avatar' => $topAvatar,
                                    'initials' => $topInitials,
                                    'content' => $topComment->content,
                                ];
                            }
                        ?>

                        <script>
                            window.__INITIAL_TOP_COMMENT__ = <?php echo json_encode($initialTopComment, 15, 512) ?>;
                        </script>

                        <!-- Mobile & Tablet Only Comments Preview (Falls below channel info) -->
                        <div class="lg:hidden mb-8" x-data="mobileCommentsPreview(<?php echo e((int)$previewCommentsCount); ?>)">
                             <div @click="mobileCommentsOpen = true" class="bg-gray-100 dark:bg-[#272727] rounded-xl p-3 transition-colors cursor-pointer active:bg-gray-200 dark:active:bg-[#3F3F3F] border border-transparent dark:border-white/5 shadow-sm">
                                <div class="flex items-center justify-between" :class="previewComment ? 'mb-1.5' : ''">
                                    <h2 class="text-[13px] font-black text-gray-900 dark:text-white">Comments <span class="text-gray-500 dark:text-[#AAAAAA] font-normal pl-1" x-text="commentsCount.toLocaleString()"><?php echo e(number_format($previewCommentsCount)); ?></span></h2>
                                    <span class="material-symbols-rounded text-gray-900 dark:text-white text-base">unfold_more</span>
                                </div>
                                <template x-if="previewComment">
                                    <div class="flex items-start gap-2">
                                        <div class="w-5 h-5 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-800 flex items-center justify-center shrink-0 mt-0.5">
                                            <template x-if="previewComment.avatar">
                                                <img :src="previewComment.avatar" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!previewComment.avatar">
                                                <span class="text-[7px] font-black text-gray-500 dark:text-white" x-text="previewComment.initials"></span>
                                            </template>
                                        </div>
                                        <p class="text-[11px] font-medium text-gray-900 dark:text-[#F1F1F1] line-clamp-2 leading-snug">
                                            <span class="font-black text-[10px] text-gray-600 dark:text-[#AAAAAA] mr-1" x-text="previewComment.author"></span>
                                            <span x-text="previewComment.content"></span>
                                        </p>
                                    </div>
                                </template>
                             </div>

                             <!-- Mobile Comments Bottom Sheet Popup -->
                             <div x-show="mobileCommentsOpen" 
                                  @keydown.escape.window="mobileCommentsOpen = false"
                                  class="fixed inset-0 z-[200000] flex items-end lg:hidden"
                                  x-cloak>
                                 <div x-show="mobileCommentsOpen" 
                                      x-transition.opacity 
                                      class="fixed inset-0 bg-black/60 backdrop-blur-sm" 
                                      @click="mobileCommentsOpen = false"></div>
                                 
                                 <div x-show="mobileCommentsOpen" 
                                      x-transition:enter="transition ease-out duration-300 transform" 
                                      x-transition:enter-start="translate-y-full" 
                                      x-transition:enter-end="translate-y-0" 
                                      x-transition:leave="transition ease-in duration-200 transform" 
                                      x-transition:leave-start="translate-y-0" 
                                      x-transition:leave-end="translate-y-full" 
                                      x-data="commentSystem()"
                                      class="w-full h-[80vh] bg-white dark:bg-[#0F0F0F] rounded-t-[2.5rem] shadow-2xl relative flex flex-col border-t border-white/10">
                                      
                                      <div class="w-12 h-1.5 bg-gray-300 dark:bg-white/20 rounded-full mx-auto mt-4 mb-2 flex-shrink-0" @click="mobileCommentsOpen = false"></div>
                                      
                                      <div class="px-6 py-3 border-b border-gray-100 dark:border-white/5 flex items-center justify-between flex-shrink-0">
                                           <div class="flex items-center gap-4">
                                                <h3 class="font-black text-lg text-gray-900 dark:text-white">Comments <span class="text-gray-500 dark:text-[#AAAAAA] font-normal text-sm ml-1" x-text="commentsCount.toLocaleString()"><?php echo e(number_format($video->comments->count())); ?></span></h3>
                                                <!-- Mobile Sort -->
                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open" :disabled="sortLoading" class="flex items-center gap-1.5 px-3 py-1 bg-gray-100 dark:bg-white/10 rounded-full text-[12px] font-black text-gray-900 dark:text-white">
                                                        <span class="material-symbols-rounded text-[16px]" :class="{ 'animate-spin': sortLoading }">sort</span>
                                                        <span x-text="sortLoading ? 'Sorting...' : (sort === 'top' ? 'Top' : 'Newest')"></span>
                                                    </button>
                                                    <div x-show="open" 
                                                         x-transition
                                                         @click.outside="open = false"
                                                         class="absolute left-0 mt-2 w-36 bg-white dark:bg-[#1A1A1A] rounded-xl shadow-2xl border border-gray-100 dark:border-white/5 py-1 z-[100]">
                                                        <button @click="setSort('top'); open = false" 
                                                                class="w-full text-left px-4 py-2 text-[12px] font-black"
                                                                :class="sort === 'top' ? 'text-red-600 bg-red-500/5' : 'text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-white/5'">
                                                            Top comments
                                                        </button>
                                                        <button @click="setSort('new'); open = false" 
                                                                class="w-full text-left px-4 py-2 text-[12px] font-black"
                                                                :class="sort === 'new' ? 'text-red-600 bg-red-500/5' : 'text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-white/5'">
                                                            Newest first
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                           <button @click="mobileCommentsOpen = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white hover:bg-gray-200 dark:hover:bg-white/20 transition-colors">
                                               <span class="material-symbols-rounded text-[20px]">close</span>
                                           </button>
                                      </div>
                                      
                                      <div class="flex-1 overflow-y-auto px-4 pb-4 pt-0 custom-scrollbar">
                                           <div class="space-y-6">
                                                <?php if(auth()->guard()->check()): ?>
                                                    <div class="flex flex-col gap-3 mb-6 sticky top-0 bg-white dark:bg-[#0F0F0F] z-[60] pt-2 pb-4 border-b border-gray-100 dark:border-white/5 shadow-sm">
                                                        <div class="flex gap-3 w-full">
                                                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center shrink-0 uppercase overflow-hidden border border-gray-100 dark:border-white/10">
                                                            <?php if(auth()->user()->channel?->avatar): ?>
                                                                <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . auth()->user()->channel->avatar)); ?>" class="w-full h-full object-cover">
                                                            <?php elseif(auth()->user()->image): ?>
                                                                <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . auth()->user()->image)); ?>" class="w-full h-full object-cover">
                                                            <?php else: ?>
                                                                <span class="text-[10px] font-black text-gray-500 dark:text-white"><?php echo e(substr(auth()->user()->channel?->name ?? auth()->user()->name, 0, 1)); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                         <div class="relative w-full">
                                                              <textarea 
                                                                  x-ref="commentText"
                                                                  x-model="mainComment"
                                                                  rows="1"
                                                                  @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'; handleInput($event)"
                                                                  @keydown.down.prevent="if(showSuggestions) suggestionIndex = (suggestionIndex + 1) % mentionSuggestions.length"
                                                                  @keydown.up.prevent="if(showSuggestions) suggestionIndex = (suggestionIndex - 1 + mentionSuggestions.length) % mentionSuggestions.length"
                                                                  @keydown.enter.prevent="if(showSuggestions) { insertMention(mentionSuggestions[suggestionIndex].username); } else { $el.blur(); }"
                                                                  @keydown.escape="showSuggestions = false"
                                                                  class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-white/10 focus:border-red-600 focus:ring-0 px-0 py-1 pr-8 text-[13px] text-gray-900 dark:text-white resize-none transition-all duration-300" 
                                                                  placeholder="Add a comment..."></textarea>
                                                             <button type="button" @click="mainEmojisOpen = !mainEmojisOpen" class="absolute right-1 bottom-2 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-colors" title="Add emoji">
<span class="material-symbols-rounded text-[18px] text-gray-400">sentiment_satisfied</span>
                                                             </button>
                                                              <div x-show="mainEmojisOpen" x-transition @click.away="mainEmojisOpen = false" class="absolute top-full mt-2 right-0 w-[280px] bg-white dark:bg-[#1A1A1A] border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl z-[100] overflow-hidden">
                                                                  <div class="p-1.5 border-b border-gray-100 dark:border-white/5">
                                                                      <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2">Emoji</span>
                                                                  </div>
                                                                  <div class="p-2 grid grid-cols-8 gap-0.5 max-h-[220px] overflow-y-auto">
                                                                     <template x-for="(emoji, idx) in (window.EMOJI_LIST || [])" :key="idx">
                                                                         <button type="button" @click="$refs.commentText.focus(); const el = $refs.commentText; const start = el.selectionStart; const end = el.selectionEnd; el.value = el.value.slice(0,start) + emoji + el.value.slice(end); el.selectionStart = el.selectionEnd = start + emoji.length; mainComment = el.value; mainEmojisOpen = false;" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 cursor-pointer transition-colors text-lg" x-text="emoji"></button>
                                                                     </template>
                                                                 </div>
                                                             </div>
                                                             
                                                              <!-- Mention Suggestions Dropdown -->
                                                              <div x-show="showSuggestions" 
                                                                   x-transition
                                                                   class="absolute top-full mt-2 left-0 w-64 bg-white dark:bg-[#1A1A1A] rounded-2xl shadow-2xl border border-gray-100 dark:border-white/5 py-2 z-[100] overflow-hidden"
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
                                                        </div>
                                                        <div class="flex justify-end gap-2 w-full mt-2">
                                                            <button @click="mainComment = ''" type="button" class="px-4 py-1.5 rounded-full font-black text-[12px] text-gray-900 dark:text-white">Cancel</button>
                                                            <button @click="submitComment('mobile-comments-list')" :disabled="loading" class="px-4 py-1.5 bg-red-600 text-white rounded-full font-black text-[12px] disabled:opacity-50">
                                                                <span x-text="loading ? 'Posting...' : 'Comment'">Comment</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="flex gap-3 mb-6 cursor-pointer sticky top-0 bg-white dark:bg-[#0F0F0F] z-[60] pt-2 pb-4 border-b border-gray-100 dark:border-white/5 shadow-sm" onclick="showLoginAlert('comment on this video')">
                                                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center text-gray-400 shrink-0">
                                                            <span class="material-symbols-rounded text-[18px]">account_circle</span>
                                                        </div>
                                                        <div class="flex-1 border-b-2 border-gray-200 dark:border-white/10 py-1">
                                                            <span class="text-[13px] text-gray-500">Add a comment...</span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <div id="mobile-comments-list" class="space-y-6">
                                               <?php $__empty_1 = true; $__currentLoopData = $video->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <?php echo $__env->make('frontend.partials.comment_item', ['comment' => $comment, 'video' => $video], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <p class="no-comments-msg text-[13px] text-gray-500 dark:text-[#AAAAAA] text-center mt-4">No comments yet. Be the first!</p>
                                                <?php endif; ?>
                                               </div>
                                           </div>
                                      </div>
                                 </div>
                             </div>
                        </div>

                        <!-- Dynamic Comments (Standard Section) -->
                        <div class="mt-8 border-t dark:border-white/5 pt-8 hidden lg:block" x-data="commentSystem()">
                            <div class="flex items-center gap-6 mb-8">
                                <h2 class="text-xl font-black text-gray-900 dark:text-white"><span x-text="commentsCount"><?php echo e(number_format($video->comments->count())); ?></span> Comments</h2>
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" :disabled="sortLoading" class="flex items-center gap-2 text-[14px] font-black text-gray-900 dark:text-white hover:opacity-70 transition-opacity">
                                        <span class="material-symbols-rounded" :class="{ 'animate-spin': sortLoading }">sort</span> 
                                        <span x-text="sortLoading ? 'Sorting...' : 'Sort'"></span>
                                    </button>
                                    <div x-show="open" 
                                         x-transition
                                         @click.outside="open = false"
                                         class="absolute left-0 mt-2 w-48 bg-white dark:bg-[#1A1A1A] rounded-xl shadow-2xl border border-gray-100 dark:border-white/5 py-2 z-[100] overflow-hidden">
                                        <button @click="setSort('top'); open = false" 
                                                class="w-full text-left px-4 py-2.5 text-[14px] font-black transition-colors"
                                                :class="sort === 'top' ? 'text-red-600 bg-red-500/5' : 'text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-white/5'">
                                            Top comments
                                        </button>
                                        <button @click="setSort('new'); open = false" 
                                                class="w-full text-left px-4 py-2.5 text-[14px] font-black transition-colors"
                                                :class="sort === 'new' ? 'text-red-600 bg-red-500/5' : 'text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-white/5'">
                                            Newest first
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <?php if(auth()->guard()->check()): ?>
                                <div class="flex gap-4 mb-10">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center shrink-0 uppercase overflow-hidden border border-gray-100 dark:border-white/10">
                                        <?php if(auth()->user()->channel?->avatar): ?>
                                            <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . auth()->user()->channel->avatar)); ?>" class="w-full h-full object-cover">

                                        <?php elseif(auth()->user()->image): ?>
                                            <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . auth()->user()->image)); ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <?php
                                                $name = auth()->user()->channel?->name ?? auth()->user()->name;
                                                $words = explode(' ', trim($name));
                                                $initials = count($words) >= 2 
                                                    ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                                    : strtoupper(substr($name, 0, 2));
                                            ?>
                                            <span class="text-[13px] font-black text-gray-500 dark:text-white"><?php echo e($initials); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="relative w-full">
                                        <div class="relative">
                                            <textarea 
                                                x-ref="commentText"
                                                x-model="mainComment"
                                                rows="1"
                                                @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'; handleInput($event)"
                                                @keydown.down.prevent="if(showSuggestions) suggestionIndex = (suggestionIndex + 1) % mentionSuggestions.length"
                                                @keydown.up.prevent="if(showSuggestions) suggestionIndex = (suggestionIndex - 1 + mentionSuggestions.length) % mentionSuggestions.length"
                                                @keydown.enter.prevent="if(showSuggestions) { insertMention(mentionSuggestions[suggestionIndex].username); } else { $el.blur(); }"
                                                @keydown.escape="showSuggestions = false"
                                                class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-white/10 focus:border-red-600 focus:ring-0 px-0 py-2 pr-8 text-[14px] text-gray-900 dark:text-white resize-none transition-all duration-300" 
                                                placeholder="Add a comment..."></textarea>
                                            <button type="button" @click="mainEmojisOpen = !mainEmojisOpen" class="absolute right-1 top-2 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-colors" title="Add emoji">
                                                <span class="material-symbols-rounded text-[18px] text-gray-400">emoji_emotions</span>
                                            </button>
                                        </div>
                                        <div x-show="mainEmojisOpen" x-transition @click.away="mainEmojisOpen = false" class="absolute bottom-full mb-2 right-0 w-[280px] bg-white dark:bg-[#1A1A1A] border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl z-[100] overflow-hidden">
                                            <div class="p-1.5 border-b border-gray-100 dark:border-white/5">
                                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-2">Emoji</span>
                                            </div>
                                            <div class="p-2 grid grid-cols-8 gap-0.5 max-h-[220px] overflow-y-auto">
                                                <template x-for="(emoji, idx) in (window.EMOJI_LIST || [])" :key="idx">
                                                    <button type="button" @click="$refs.commentText.focus(); const el = $refs.commentText; const start = el.selectionStart; const end = el.selectionEnd; el.value = el.value.slice(0,start) + emoji + el.value.slice(end); el.selectionStart = el.selectionEnd = start + emoji.length; mainComment = el.value; mainEmojisOpen = false;" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 cursor-pointer transition-colors text-lg" x-text="emoji"></button>
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
                                        <div class="flex justify-end gap-3 mt-2">
                                            <button @click="mainComment = ''" type="button" class="px-6 py-2 rounded-full font-black text-[14px] text-gray-900 dark:text-white">Cancel</button>
                                            <button @click="submitComment('comments-list')" :disabled="loading" class="px-6 py-2 bg-red-600 text-white rounded-full font-black text-[14px] disabled:opacity-50">
                                                <span x-text="loading ? 'Posting...' : 'Comment'">Comment</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="flex gap-4 mb-10 cursor-pointer" onclick="showLoginAlert('comment on this video')">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center text-gray-400 shrink-0">
                                        <span class="material-symbols-rounded">account_circle</span>
                                    </div>
                                    <div class="flex-1 border-b-2 border-gray-200 dark:border-white/10 py-2">
                                        <span class="text-[14px] text-gray-500">Add a comment...</span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="space-y-6" id="comments-list">
                                <?php $__empty_1 = true; $__currentLoopData = $video->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php echo $__env->make('frontend.partials.comment_item', ['comment' => $comment, 'video' => $video], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="no-comments-msg text-[14px] text-gray-500 dark:text-[#AAAAAA]">No comments yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>


                        <!-- HIGH-FIDELITY Reels Section -->
                        <div class="mb-8 overflow-hidden mobile-section-card p-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-red-600 font-variation-filled">movie</span>
                                    <span class="text-lg font-black text-gray-900 dark:text-white">Reels</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button onclick="this.closest('.mobile-section-card').querySelector('.shorts-shelf').scrollBy({left: -300, behavior: 'smooth'})" 
                                            class="hidden md:flex w-10 h-10 rounded-full bg-white dark:bg-[#272727] items-center justify-center text-gray-900 dark:text-white transition-all border border-gray-200 dark:border-white/10 active:scale-90 shadow-lg hover:bg-gray-50 dark:hover:bg-[#3f3f3f] group">
                                        <span class="material-symbols-rounded text-xl group-hover:-translate-x-1 transition-transform">chevron_left</span>
                                    </button>
                                    <button onclick="this.closest('.mobile-section-card').querySelector('.shorts-shelf').scrollBy({left: 300, behavior: 'smooth'})" 
                                            class="hidden md:flex w-10 h-10 rounded-full bg-white dark:bg-[#272727] items-center justify-center text-gray-900 dark:text-white transition-all border border-gray-200 dark:border-white/10 active:scale-90 shadow-lg hover:bg-gray-50 dark:hover:bg-[#3f3f3f] group">
                                        <span class="material-symbols-rounded text-xl group-hover:translate-x-1 transition-transform">chevron_right</span>
                                    </button>
                                    <span class="material-symbols-rounded text-gray-500 cursor-pointer hover:text-red-600 transition-colors ml-2">close</span>
                                </div>
                            </div>
                            <div class="flex gap-4 overflow-x-auto no-scrollbar -mx-4 px-4 shorts-shelf scroll-smooth">
                                <?php $__empty_1 = true; $__currentLoopData = $reels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reelItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                     <a href="<?php echo e(route('reels.index', ['reel' => $reelItem->slug])); ?>" 
                                        class="min-w-[150px] md:min-w-[180px] aspect-[9/16] rounded-[1.8rem] relative overflow-hidden shrink-0 group shorts-item border border-white/10 dark:border-white/5 shadow-xl shadow-black/10 dark:shadow-black/40 hover:shadow-2xl hover:shadow-[#ff571a]/20 transition-all duration-500 hover:scale-[1.03]"
                                        @mouseenter="$el.querySelector('video')?.play()?.catch(()=>{})" 
                                        @mouseleave="const v = $el.querySelector('video'); if(v) { v.pause(); v.currentTime = 0; }">
                                        
                                        <!-- Video Background -->
                                        <video src="<?php echo e($reelItem->getVideoUrl()); ?>#t=0.1" 
                                               class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 z-0" 
                                               muted playsinline preload="metadata"></video>

                                        <!-- Static Thumbnail/Preview Fallback -->
                                        <?php if($reelItem->getThumbnailUrl()): ?>
                                             <img src="<?php echo e($reelItem->getThumbnailUrl()); ?>" 
                                                  class="absolute inset-0 w-full h-full object-cover z-10 transition-opacity duration-500 group-hover:opacity-0"
                                                  onerror="this.remove();">
                                        <?php endif; ?>

                                        <?php echo $reelItem->getBadgeHtml(); ?>


                                        <!-- Play Button Center (on hover) -->
                                        <div class="absolute inset-0 flex items-center justify-center z-10 opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none">
                                            <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border border-white/30 shadow-2xl scale-75 group-hover:scale-100 transition-transform duration-500">
                                                <span class="material-symbols-rounded text-white text-2xl ml-0.5">play_arrow</span>
                                            </div>
                                        </div>

                                        <!-- Bottom Content -->
                                        <div class="absolute bottom-0 left-0 right-0 z-20 p-3 md:p-4">
                                            <!-- Bottom gradient bg (only on hover) -->
                                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-all duration-500" style="background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.5) 50%, transparent 100%);"></div>
                                            
                                            <!-- User Info -->
                                            <div class="relative flex items-center gap-2 mb-2">
                                                <div class="w-6 h-6 md:w-7 md:h-7 rounded-full border-2 border-white/40 overflow-hidden shadow-lg flex-shrink-0 bg-gray-800">
                                                    <?php if($reelItem->user->image): ?>
                                                        <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $reelItem->user->image)); ?>" class="w-full h-full object-cover">
                                                    <?php elseif($reelItem->user->channel && $reelItem->user->channel->avatar): ?>
                                                        <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $reelItem->user->channel->avatar)); ?>" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <div class="w-full h-full bg-gradient-to-tr from-[#ff571a] to-rose-500 flex items-center justify-center text-white text-[9px] font-black">
                                                            <?php echo e(strtoupper(substr($reelItem->user->name ?? 'U', 0, 1))); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="text-white text-[10px] font-black truncate drop-shadow-lg"><?php echo e($reelItem->user->channel->name ?? $reelItem->user->name); ?></span>
                                            </div>

                                            <!-- Title -->
                                            <h3 class="relative text-white text-[11px] md:text-[12px] font-bold leading-snug line-clamp-2 drop-shadow-lg"><?php echo e($reelItem->title); ?></h3>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="w-full flex flex-col items-center justify-center text-center py-10 px-6 rounded-[1.5rem] bg-gray-50 dark:bg-white/[0.03] border border-dashed border-gray-200 dark:border-white/10">
                                        <div class="w-14 h-14 rounded-2xl bg-red-500/10 dark:bg-red-500/15 flex items-center justify-center mb-4">
                                            <span class="material-symbols-rounded text-3xl text-red-500">movie</span>
                                        </div>
                                        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-1.5">No Reels Yet</h3>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 leading-relaxed max-w-xs mb-5">There are no reels to show right now. Check back soon or explore fresh short videos from creators.</p>
                                        <a href="<?php echo e(route('reels.index')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 gradient-orange text-white rounded-full text-[11px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/25 hover:scale-105 active:scale-95 transition-all">
                                            <span class="material-symbols-rounded text-base">explore</span>
                                            Explore Reels
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Banner Ad Slot 3 -->
                        <?php if(isset($slot3Banners) && $slot3Banners->count() > 0): ?>
        <div x-data="{ 
                            active: 0, 
                            count: <?php echo e($slot3Banners->count()); ?>,
                            progress: 0,
                            timer: null,
                            start() {
                                if (this.timer) clearInterval(this.timer);
                                this.timer = setInterval(() => {
                                    this.progress += 1;
                                    if (this.progress >= 100) {
                                        this.active = (this.active + 1) % this.count;
                                        this.progress = 0;
                                    }
                                }, 50);
                            },
                            init() {
                                if (this.count > 1) this.start();
                            },
                            next() { this.active = (this.active + 1) % this.count; this.start(); },
                            prev() { this.active = (this.active - 1 + this.count) % this.count; this.start(); },
                            goto(i) { this.active = i; this.start(); }
                        }" class="mb-8 group relative">
                            <!-- Sponsored Badge -->
                            <div class="absolute top-2 left-2 z-10 bg-black/60 backdrop-blur-md px-2 py-1 rounded text-[8px] font-black text-white uppercase tracking-[0.2em] flex items-center gap-1 border border-white/10">
                                <span class="material-symbols-rounded text-[10px]">ads_click</span> Sponsored
                            </div>

                            <div class="relative aspect-[3/1] rounded-xl overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm">
                                <div class="flex h-full transition-transform duration-700 ease-in-out" :style="`width: ${count * 100}%; transform: translateX(-${active * (100 / count)}%)`">
                                    <?php $__currentLoopData = $slot3Banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="h-full flex-shrink-0 relative group/item" style="width: <?php echo e(100 / $slot3Banners->count()); ?>%">
                                            <a href="<?php echo e(route('banner.redirect', $banner->slug)); ?>" target="_blank" class="block w-full h-full">
                                                <img src="<?php echo e(getImage($banner->image)); ?>" alt="<?php echo e($banner->title); ?>" class="w-full h-full object-cover">
                                            </a>
                                            <a href="<?php echo e(route('banner.redirect', $banner->slug)); ?>" target="_blank" class="absolute bottom-2 right-2 bg-black/40 hover:bg-black/60 backdrop-blur-sm text-[8px] text-white px-2 py-1 rounded-md transition-all flex items-center gap-1 z-20">
                                                <span class="material-symbols-rounded text-[10px]">open_in_new</span> Visit
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <!-- Indicators -->
                            <?php if($slot3Banners->count() > 1): ?>
                                <div class="flex gap-2 mt-3 justify-center px-4">
                                    <?php $__currentLoopData = $slot3Banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <button @click="goto(<?php echo e($index); ?>)" 
                                                class="flex-1 h-1.5 rounded-full bg-gray-200 dark:bg-white/10 overflow-hidden relative transition-all duration-300"
                                                :class="active === <?php echo e($index); ?> ? 'opacity-100' : 'opacity-40'">
                                            <div class="absolute inset-y-0 left-0 bg-orange-500 transition-all duration-100 ease-linear"
                                                 :style="active === <?php echo e($index); ?> ? `width: ${progress}%` : 'width: 0%'"></div>
                                        </button>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- HIGH-FIDELITY Related Videos Shelf (Desktop & Tablet) -->
                        <div class="mb-10 overflow-hidden mobile-section-card p-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-indigo-600 font-variation-filled">subscriptions</span>
                                    <span class="text-lg font-black text-gray-900 dark:text-white">Up Next</span>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="this.closest('.mobile-section-card').querySelector('.flex.gap-4.overflow-x-auto').scrollBy({left: -300, behavior: 'smooth'})" 
                                            class="w-10 h-10 rounded-full bg-white dark:bg-[#272727] flex items-center justify-center text-gray-900 dark:text-white transition-all border border-gray-200 dark:border-white/10 active:scale-90 shadow-lg hover:bg-gray-50 dark:hover:bg-[#3f3f3f] group">
                                        <span class="material-symbols-rounded text-xl group-hover:-translate-x-1 transition-transform">chevron_left</span>
                                    </button>
                                    <button onclick="this.closest('.mobile-section-card').querySelector('.flex.gap-4.overflow-x-auto').scrollBy({left: 300, behavior: 'smooth'})" 
                                            class="w-10 h-10 rounded-full bg-white dark:bg-[#272727] flex items-center justify-center text-gray-900 dark:text-white transition-all border border-gray-200 dark:border-white/10 active:scale-90 shadow-lg hover:bg-gray-50 dark:hover:bg-[#3f3f3f] group">
                                        <span class="material-symbols-rounded text-xl group-hover:translate-x-1 transition-transform">chevron_right</span>
                                    </button>
                                </div>
                            </div>
                            <div class="flex gap-4 overflow-x-auto no-scrollbar -mx-4 px-4">
                                <?php $__empty_1 = true; $__currentLoopData = $relatedVideos->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relVideo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <a href="<?php echo e(route('videos.show', $relVideo)); ?>" 
                                       class="min-w-[240px] md:min-w-[280px] group cursor-pointer"
                                        <?php
                                             $hasAccess = true;
                                             if ($relVideo->is_premium) {
                                                 $hasAccess = false;
                                                 if (auth()->check() && (auth()->id() == $relVideo->user_id || auth()->user()->isPurchased($relVideo->id) || auth()->user()->hasPremiumAccess())) {
                                                     $hasAccess = true;
                                                 }
                                             }
                                         ?>
                                        x-data="{
                                             hover: false,
                                             currentTime: 0,
                                             durationSeconds: 0,
                                             hasAccess: <?php echo e($hasAccess ? 'true' : 'false'); ?>,
                                            playVideo() {
                                                if (!this.hasAccess) return;
                                                this.hover = true;
                                                this.$nextTick(() => { 
                                                    const v = this.$el.querySelector('video');
                                                    if(v) {
                                                        v.play().catch(() => {});
                                                        v.ontimeupdate = () => {
                                                            this.currentTime = v.currentTime;
                                                            this.durationSeconds = v.duration || 0;
                                                        };
                                                    }
                                                });
                                            },
                                            stopVideo() {
                                                this.hover = false;
                                                const v = this.$el.querySelector('video');
                                                if(v) {
                                                    v.pause();
                                                    v.currentTime = 0;
                                                    v.ontimeupdate = null;
                                                    this.currentTime = 0;
                                                }
                                            },
                                            get displayDuration() {
                                                let orig = '<?php echo e($relVideo->formatted_duration); ?>';
                                                if (!orig || orig === '--:--' || !this.hover || this.currentTime === 0) return orig || '--:--';
                                                
                                                let parts = orig.split(':').reverse();
                                                let origSecs = 0;
                                                for (let i = 0; i < parts.length; i++) {
                                                    origSecs += parseInt(parts[i] || 0) * Math.pow(60, i);
                                                }
                                                
                                                let remaining = Math.max(0, origSecs - this.currentTime);
                                                let h = Math.floor(remaining / 3600);
                                                let m = Math.floor((remaining % 3600) / 60);
                                                let s = Math.floor(remaining % 60);
                                                
                                                if (parts.length > 2) {
                                                    return h + ':' + (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
                                                } else {
                                                    return m + ':' + (s < 10 ? '0' + s : s);
                                                }
                                            }
                                       }"
                                       @mouseenter="playVideo()"
                                       @mouseleave="stopVideo()">
                                        <div class="aspect-video w-full shrink-0 rounded-2xl overflow-hidden bg-gray-200 dark:bg-white/5 relative mb-3">
                                            <?php if($relVideo->isBunnyVideo()): ?>
                                                <img :src="hover ? '<?php echo e($relVideo->getPreviewUrl()); ?>' : ''" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" :class="hover ? 'scale-110' : 'scale-100'" loading="lazy" />
                                            <?php elseif($relVideo->video_path): ?>
                                                <video src="<?php echo e(asset(getFilePath('video') . '/' . $relVideo->video_path)); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" :class="hover ? 'scale-110' : 'scale-100'" preload="metadata" muted playsinline></video>
                                            <?php else: ?>
                                                <video src="<?php echo e($relVideo->getVideoUrl()); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" :class="hover ? 'scale-110' : 'scale-100'" preload="metadata" muted playsinline></video>
                                            <?php endif; ?>
                                            <img src="<?php echo e($relVideo->getThumbnailUrl()); ?>" 
                                                 class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 z-10"
                                                 :class="hover ? 'opacity-0' : 'opacity-100'"
                                                 onerror="this.src='<?php echo e($relVideo->getPreviewUrl()); ?>'; this.onerror=null;">
                                            <span x-text="displayDuration" class="absolute bottom-2 right-2 bg-black/80 text-white text-[11px] px-1.5 py-0.5 rounded-lg font-black z-20"><?php echo e($relVideo->formatted_duration); ?></span>
                                            
                                            <!-- Play Overlay -->
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 z-30">
                                                <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 scale-75 group-hover:scale-100 transition-transform">
                                                    <span class="material-symbols-rounded text-white text-2xl">play_arrow</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-800 flex items-center justify-center shrink-0 border border-gray-100 dark:border-white/10 mt-1">
                                                <?php if($relVideo->user->channel && $relVideo->user->channel->avatar): ?>
                                                    <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $relVideo->user->channel->avatar)); ?>" class="w-full h-full object-cover">
                                                <?php elseif($relVideo->user->image): ?>
                                                    <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $relVideo->user->image)); ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <?php
                                                        $name = $relVideo->user->channel->name ?? $relVideo->user->name;
                                                        $words = explode(' ', trim($name));
                                                        $initials = count($words) >= 2 
                                                            ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                                            : strtoupper(substr($name, 0, 2));
                                                    ?>
                                                    <span class="text-[10px] font-black text-gray-500 dark:text-white"><?php echo e($initials); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start gap-2">
                                                <h4 class="text-[14px] font-black text-gray-900 dark:text-white line-clamp-2 leading-tight group-hover:text-indigo-600 transition-colors flex-1 min-h-[35px]"><?php echo e($relVideo->title); ?></h4>
                                                <button onclick="event.preventDefault(); event.stopPropagation(); window.openVideoOptions('<?php echo e($relVideo->id); ?>', '<?php echo e(addslashes($relVideo->title)); ?>', 'video', <?php echo e($relVideo->isLikedBy(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($relVideo->isInWatchLater(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($relVideo->isInAnyPlaylist(auth()->user()) ? 'true' : 'false'); ?>, <?php echo json_encode($relVideo->getPlaylistMembershipIds(auth()->user() ?? null), 15, 512) ?>, <?php echo e($relVideo->user_id); ?>)" 
                                                        class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors shrink-0">
                                                    <span class="material-symbols-rounded text-[18px]">more_vert</span>
                                                </button>
                                            </div>
                                                <div class="flex flex-col mt-1">
                                                    <p class="text-[11px] font-bold text-gray-500 truncate hover:underline"><?php echo e($relVideo->user->channel->name ?? $relVideo->user->name); ?></p>
                                                    <p class="text-[11px] font-bold text-gray-400 mt-0.5"><?php echo e(number_format($relVideo->views_count)); ?> views &bull; <?php echo e($relVideo->created_at->diffForHumans()); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="w-full flex flex-col items-center justify-center text-center py-10 px-6 rounded-[1.5rem] bg-gray-50 dark:bg-white/[0.03] border border-dashed border-gray-200 dark:border-white/10">
                                        <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 dark:bg-indigo-500/15 flex items-center justify-center mb-4">
                                            <span class="material-symbols-rounded text-3xl text-indigo-500">subscriptions</span>
                                        </div>
                                        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-1.5">No Related Videos</h3>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 leading-relaxed max-w-xs mb-5">We couldn't find videos related to this one right now. Discover more content from across the platform.</p>
                                        <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 gradient-orange text-white rounded-full text-[11px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/25 hover:scale-105 active:scale-95 transition-all">
                                            <span class="material-symbols-rounded text-base">explore</span>
                                            Discover Videos
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- HIGH-FIDELITY Related Videos (Mobile-Only) -->
                        <div class="lg:hidden mb-8 space-y-6">
                            <?php $__empty_1 = true; $__currentLoopData = $relatedVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e(route('videos.show', $rel)); ?>" 
                                   class="flex flex-col gap-3 group cursor-pointer"
                                   <?php
                                         $hasAccess = true;
                                         if ($rel->is_premium) {
                                             $hasAccess = false;
                                             if (auth()->check() && (auth()->id() == $rel->user_id || auth()->user()->isPurchased($rel->id) || auth()->user()->hasPremiumAccess())) {
                                                 $hasAccess = true;
                                             }
                                         }
                                         $relChannelName = $rel->user->channel->name ?? $rel->user->name ?? 'Creator';
                                         $relWords = explode(' ', trim($relChannelName));
                                         $relInitials = count($relWords) >= 2 
                                             ? strtoupper(substr($relWords[0], 0, 1) . substr($relWords[count($relWords)-1], 0, 1))
                                             : strtoupper(substr($relChannelName, 0, 2));
                                     ?>
                                    x-data="{
                                         hover: false,
                                         currentTime: 0,
                                         durationSeconds: 0,
                                         hasAccess: <?php echo e($hasAccess ? 'true' : 'false'); ?>,
                                        playVideo() {
                                            if (!this.hasAccess) return;
                                            this.hover = true;
                                            this.$nextTick(() => { 
                                                const v = this.$el.querySelector('video');
                                                if(v) {
                                                    v.play().catch(() => {});
                                                    v.ontimeupdate = () => {
                                                        this.currentTime = v.currentTime;
                                                        this.durationSeconds = v.duration || 0;
                                                    };
                                                }
                                            });
                                        },
                                        stopVideo() {
                                            this.hover = false;
                                            const v = this.$el.querySelector('video');
                                            if(v) {
                                                v.pause();
                                                v.currentTime = 0;
                                                v.ontimeupdate = null;
                                                this.currentTime = 0;
                                            }
                                        },
                                        get displayDuration() {
                                            let orig = '<?php echo e($rel->formatted_duration); ?>';
                                            if (!orig || orig === '--:--' || !this.hover || this.currentTime === 0) return orig || '--:--';
                                            
                                            let parts = orig.split(':').reverse();
                                            let origSecs = 0;
                                            for (let i = 0; i < parts.length; i++) {
                                                origSecs += parseInt(parts[i] || 0) * Math.pow(60, i);
                                            }
                                            
                                            let remaining = Math.max(0, origSecs - this.currentTime);
                                            let h = Math.floor(remaining / 3600);
                                            let m = Math.floor((remaining % 3600) / 60);
                                            let s = Math.floor(remaining % 60);
                                            
                                            if (parts.length > 2) {
                                                return h + ':' + (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
                                            } else {
                                                return m + ':' + (s < 10 ? '0' + s : s);
                                            }
                                        }
                                   }"
                                   @mouseenter="playVideo()"
                                   @mouseleave="stopVideo()">
                                    <div class="w-full aspect-video rounded-2xl overflow-hidden bg-gray-200 dark:bg-white/5 relative shrink-0">
                                        <?php if($rel->isBunnyVideo()): ?>
                                            <img :src="hover ? '<?php echo e($rel->getPreviewUrl()); ?>' : ''" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" :class="hover ? 'scale-110' : 'scale-100'" loading="lazy" />
                                        <?php elseif($rel->video_path): ?>
                                            <video src="<?php echo e(asset(getFilePath('video') . '/' . $rel->video_path)); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" :class="hover ? 'scale-110' : 'scale-100'" preload="metadata" muted playsinline></video>
                                        <?php else: ?>
                                            <video src="<?php echo e($rel->getVideoUrl()); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" :class="hover ? 'scale-110' : 'scale-100'" preload="metadata" muted playsinline></video>
                                        <?php endif; ?>
                                        <img src="<?php echo e($rel->getThumbnailUrl()); ?>" 
                                             class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 z-10"
                                             :class="hover ? 'opacity-0' : 'opacity-100'"
                                             onerror="this.src='<?php echo e($rel->getPreviewUrl()); ?>'; this.onerror=null;">
                                        <span x-text="displayDuration" class="absolute bottom-2 right-2 bg-black/80 text-white text-[12px] px-1.5 py-0.5 rounded font-bold z-20"><?php echo e($rel->formatted_duration); ?></span>
                                    </div>
                                    <div class="flex items-start gap-3 px-1">
                                        <!-- Channel Avatar (Left) -->
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-800 flex items-center justify-center shrink-0 mt-0.5">
                                            <?php if($rel->user->channel && $rel->user->channel->avatar): ?>
                                                <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $rel->user->channel->avatar)); ?>" class="w-full h-full object-cover">
                                            <?php elseif($rel->user->image): ?>
                                                <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $rel->user->image)); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <span class="text-[10px] font-black text-gray-500 dark:text-white"><?php echo e($relInitials); ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Text info & 3-dots -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start gap-2">
                                                <h4 class="text-[14px] sm:text-[15px] font-bold text-gray-900 dark:text-white line-clamp-2 leading-snug"><?php echo e($rel->title); ?></h4>
                                                <button onclick="event.preventDefault(); event.stopPropagation(); window.openVideoOptions('<?php echo e($rel->id); ?>', '<?php echo e(addslashes($rel->title)); ?>', 'video', <?php echo e($rel->isLikedBy(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($rel->isInWatchLater(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($rel->isInAnyPlaylist(auth()->user()) ? 'true' : 'false'); ?>, <?php echo json_encode($rel->getPlaylistMembershipIds(auth()->user() ?? null), 15, 512) ?>, <?php echo e($rel->user_id); ?>)" 
                                                        class="text-gray-400 dark:text-[#AAAAAA] hover:text-gray-900 dark:hover:text-white transition-colors shrink-0 p-1 -mr-1">
                                                    <span class="material-symbols-rounded text-[20px]">more_vert</span>
                                                </button>
                                            </div>
                                            <div class="text-[12px] text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">
                                                <?php echo e($relChannelName); ?> &bull; <?php echo e(number_format($rel->views_count)); ?> views &bull; <?php echo e($rel->created_at->diffForHumans()); ?>

                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="w-full flex flex-col items-center justify-center text-center py-10 px-6 rounded-[1.5rem] bg-gray-50 dark:bg-white/[0.03] border border-dashed border-gray-200 dark:border-white/10">
                                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 dark:bg-indigo-500/15 flex items-center justify-center mb-4">
                                        <span class="material-symbols-rounded text-3xl text-indigo-500">subscriptions</span>
                                    </div>
                                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-1.5">No Related Videos</h3>
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 leading-relaxed max-w-xs mb-5">We couldn't find videos related to this one right now. Discover more content from across the platform.</p>
                                    <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 gradient-orange text-white rounded-full text-[11px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/25 hover:scale-105 active:scale-95 transition-all">
                                        <span class="material-symbols-rounded text-base">explore</span>
                                        Discover Videos
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>



                    </div>
                </div>

                <!-- Right Column (Up Next & Filters) -->
                <div :class="theatre ? 'max-w-[1280px] mx-auto px-4 lg:px-0 mt-6 lg:w-[400px] xl:w-[426px]' : 'px-4 sm:px-0 lg:w-[25%] lg:flex-shrink-0 mt-6 lg:mt-0'">
                    
                    <?php if(isset($playlistData)): ?>
                        <?php echo $__env->make('frontend.partials.playlist_player_panel', ['playlistData' => $playlistData], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endif; ?>

                    
                    <!-- Chips Navigation (Desktop) -->
                    <?php $currentFilter = request()->query('filter', 'all'); ?>
                    <div class="hidden lg:flex items-center overflow-x-auto no-scrollbar gap-3 mb-6 pb-1">
                        <a href="<?php echo e(request()->fullUrlWithQuery(['filter' => 'all'])); ?>" class="<?php echo e($currentFilter === 'all' ? 'bg-gray-900 text-white dark:bg-[#F1F1F1] dark:text-[#0F0F0F]' : 'bg-gray-100 hover:bg-gray-200 dark:bg-[#272727] dark:hover:bg-[#3F3F3F] text-gray-900 dark:text-white'); ?> px-3 py-1.5 rounded-lg text-[14px] font-medium whitespace-nowrap transition-colors">All</a>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['filter' => 'channel'])); ?>" class="<?php echo e($currentFilter === 'channel' ? 'bg-gray-900 text-white dark:bg-[#F1F1F1] dark:text-[#0F0F0F]' : 'bg-gray-100 hover:bg-gray-200 dark:bg-[#272727] dark:hover:bg-[#3F3F3F] text-gray-900 dark:text-white'); ?> px-3 py-1.5 rounded-lg text-[14px] font-medium whitespace-nowrap transition-colors">From <?php echo e($video->user->channel->name ?? 'Channel'); ?></a>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['filter' => 'related'])); ?>" class="<?php echo e($currentFilter === 'related' ? 'bg-gray-900 text-white dark:bg-[#F1F1F1] dark:text-[#0F0F0F]' : 'bg-gray-100 hover:bg-gray-200 dark:bg-[#272727] dark:hover:bg-[#3F3F3F] text-gray-900 dark:text-white'); ?> px-3 py-1.5 rounded-lg text-[14px] font-medium whitespace-nowrap transition-colors">Related</a>
                    </div>

                    <div class="space-y-4">
                        <!-- Banner Ad Slot 1 -->
                        <?php if(isset($slot1Banners) && $slot1Banners->count() > 0): ?>
        <div x-data="{ 
                            active: 0, 
                            count: <?php echo e($slot1Banners->count()); ?>,
                            progress: 0,
                            timer: null,
                            start() {
                                if (this.timer) clearInterval(this.timer);
                                this.timer = setInterval(() => {
                                    this.progress += 1;
                                    if (this.progress >= 100) {
                                        this.active = (this.active + 1) % this.count;
                                        this.progress = 0;
                                    }
                                }, 50); // 50ms * 100 = 5000ms (5 seconds)
                            },
                            init() {
                                if (this.count > 1) this.start();
                            },
                            next() { this.active = (this.active + 1) % this.count; this.start(); },
                            prev() { this.active = (this.active - 1 + this.count) % this.count; this.start(); },
                            goto(i) { this.active = i; this.start(); }
                        }" class="mb-6 group relative">
                            <!-- Sponsored Badge -->
                            <div class="absolute top-2 left-2 z-10 bg-black/60 backdrop-blur-md px-2 py-1 rounded text-[8px] font-black text-white uppercase tracking-[0.2em] flex items-center gap-1 border border-white/10">
                                <span class="material-symbols-rounded text-[10px]">ads_click</span> Sponsored
                            </div>

                            <div class="relative aspect-[3/1] rounded-xl overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm">
                                <div class="flex h-full transition-transform duration-700 ease-in-out" :style="`width: ${count * 100}%; transform: translateX(-${active * (100 / count)}%)`">
                                    <?php $__currentLoopData = $slot1Banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="h-full flex-shrink-0 relative group/item" style="width: <?php echo e(100 / $slot1Banners->count()); ?>%">
                                            <a href="<?php echo e(route('banner.redirect', $banner->slug)); ?>" target="_blank" class="block w-full h-full">
                                                <img src="<?php echo e(getImage($banner->image)); ?>" alt="<?php echo e($banner->title); ?>" class="w-full h-full object-cover">
                                            </a>
                                            <a href="<?php echo e(route('banner.redirect', $banner->slug)); ?>" target="_blank" class="absolute bottom-2 right-2 bg-black/40 hover:bg-black/60 backdrop-blur-sm text-[8px] text-white px-2 py-1 rounded-md transition-all flex items-center gap-1 z-20">
                                                <span class="material-symbols-rounded text-[10px]">open_in_new</span> Visit
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>


                            </div>

                            <!-- Indicators -->
                            <?php if($slot1Banners->count() > 1): ?>
                                <div class="flex gap-2 mt-3 justify-center px-4">
                                    <?php $__currentLoopData = $slot1Banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <button @click="goto(<?php echo e($index); ?>)" 
                                                class="flex-1 h-1.5 rounded-full bg-gray-200 dark:bg-white/10 overflow-hidden relative transition-all duration-300"
                                                :class="active === <?php echo e($index); ?> ? 'opacity-100' : 'opacity-40'">
                                            <div class="absolute inset-y-0 left-0 bg-orange-500 transition-all duration-100 ease-linear"
                                                 :style="active === <?php echo e($index); ?> ? `width: ${progress}%` : 'width: 0%'"></div>
                                        </button>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <div x-data="infiniteScrollRelated('<?php echo e(route('videos.related', $video->id)); ?>')">
                            <div id="related-videos-container" class="flex flex-col gap-6 lg:gap-3">
                                <?php $__currentLoopData = $relatedVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($index == 2): ?>
                    <!-- Sidebar Reels Shelf (Home Page Carousel Style) -->
                                <div class="py-6 border-y dark:border-white/5 my-4 overflow-hidden relative">
                                    <div class="flex items-center justify-between mb-4 px-1">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-rounded text-red-600 font-variation-filled">movie</span>
                                            <span class="text-[14px] font-black text-gray-900 dark:text-white uppercase tracking-tight">Reels</span>
                                        </div>
                                        <a href="<?php echo e(route('reels.index')); ?>" class="text-[11px] font-bold text-gray-500 hover:text-gray-900 dark:hover:text-white uppercase tracking-widest transition-colors flex items-center">
                                            See All <span class="material-symbols-rounded text-[14px]">chevron_right</span>
                                        </a>
                                    </div>
                                    
                                    <div class="flex gap-3 overflow-x-auto orange-scrollbar pb-4 scroll-smooth" id="sidebar-reels-carousel">
                                        <?php $__currentLoopData = $reels->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebarReel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(route('reels.index', ['reel' => $sidebarReel->slug])); ?>" 
                                               class="group relative flex-shrink-0 w-[140px] md:w-[160px] aspect-[9/16] rounded-[1.2rem] overflow-hidden shadow-md shadow-black/5 dark:shadow-black/20 hover:shadow-xl hover:shadow-[#ff571a]/20 transition-all duration-300 hover:scale-[1.02] cursor-pointer border border-gray-200 dark:border-white/10"
                                               @mouseenter="$el.querySelector('video')?.play()" 
                                               @mouseleave="$el.querySelector('video')?.pause(); $el.querySelector('video').currentTime = 0">
                                                
                                                <div class="absolute top-2 right-2 z-30">
                                                    <button onclick="event.preventDefault(); event.stopPropagation(); window.openVideoOptions('<?php echo e($sidebarReel->id); ?>', '<?php echo e(addslashes($sidebarReel->title)); ?>', 'reel', <?php echo e($sidebarReel->isLikedBy(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($sidebarReel->isInWatchLater(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($sidebarReel->isInAnyPlaylist(auth()->user()) ? 'true' : 'false'); ?>, <?php echo json_encode($sidebarReel->getPlaylistMembershipIds(auth()->user() ?? null), 15, 512) ?>, <?php echo e($sidebarReel->user_id); ?>)" 
                                                            class="w-6 h-6 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center border border-white/10 text-white hover:bg-orange-500 transition-all shadow-lg">
                                                        <span class="material-symbols-rounded text-[14px]">more_vert</span>
                                                    </button>
                                                </div>

                                                <!-- Video Background -->
                                                <video src="<?php echo e($sidebarReel->getVideoUrl()); ?>#t=0.1" 
                                                       class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 z-0" 
                                                       muted playsinline preload="metadata"></video>

                                                <!-- Static Thumbnail/Preview Fallback -->
                                                <?php if($sidebarReel->getThumbnailUrl()): ?>
                                                <img src="<?php echo e($sidebarReel->getThumbnailUrl()); ?>" 
                                                     class="absolute inset-0 w-full h-full object-cover z-10 transition-opacity duration-300 group-hover:opacity-0"
                                                     onerror="this.remove();">
                                                <?php endif; ?>

                                                <!-- Multi-layer Gradient Overlay -->
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-black/30 group-hover:from-black/70 transition-all duration-300" style="background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.5) 40%, rgba(0, 0, 0, 0) 70%, rgba(0, 0, 0, 0.3) 100%);"></div>

                                                <!-- Bottom Content -->
                                                <div class="absolute bottom-0 left-0 right-0 z-20 p-3 pb-3">
                                                    <!-- Title -->
                                                    <h3 class="text-white text-[11px] font-bold leading-snug line-clamp-2 drop-shadow-md mb-2"><?php echo e($sidebarReel->title); ?></h3>

                                                    <!-- Engagement Stats -->
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex items-center gap-1 text-white/90">
                                                            <span class="material-symbols-rounded text-[12px]">visibility</span>
                                                            <span class="text-[9px] font-black"><?php echo e($sidebarReel->views_count > 999 ? number_format($sidebarReel->views_count / 1000, 1) . 'K' : $sidebarReel->views_count); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                
                                <!-- Banner Ad Slot 2 -->
                                <?php if(isset($slot2Banners) && $slot2Banners->count() > 0): ?>
                <div x-data="{ 
                                    active: 0, 
                                    count: <?php echo e($slot2Banners->count()); ?>,
                                    progress: 0,
                                    timer: null,
                                    start() {
                                        if (this.timer) clearInterval(this.timer);
                                        this.timer = setInterval(() => {
                                            this.progress += 1;
                                            if (this.progress >= 100) {
                                                this.active = (this.active + 1) % this.count;
                                                this.progress = 0;
                                            }
                                        }, 50);
                                    },
                                    init() {
                                        if (this.count > 1) this.start();
                                    },
                                    next() { this.active = (this.active + 1) % this.count; this.start(); },
                                    prev() { this.active = (this.active - 1 + this.count) % this.count; this.start(); },
                                    goto(i) { this.active = i; this.start(); }
                                }" class="mb-6 group relative">
                                    <!-- Sponsored Badge -->
                                    <div class="absolute top-2 left-2 z-10 bg-black/60 backdrop-blur-md px-2 py-1 rounded text-[8px] font-black text-white uppercase tracking-[0.2em] flex items-center gap-1 border border-white/10">
                                        <span class="material-symbols-rounded text-[10px]">ads_click</span> Sponsored
                                    </div>

                                    <div class="relative aspect-[3/1] rounded-xl overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm">
                                        <div class="flex h-full transition-transform duration-700 ease-in-out" :style="`width: ${count * 100}%; transform: translateX(-${active * (100 / count)}%)`">
                                            <?php $__currentLoopData = $slot2Banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="h-full flex-shrink-0 relative group/item" style="width: <?php echo e(100 / $slot2Banners->count()); ?>%">
                                                    <a href="<?php echo e(route('banner.redirect', $banner->slug)); ?>" target="_blank" class="block w-full h-full">
                                                        <img src="<?php echo e(getImage($banner->image)); ?>" alt="<?php echo e($banner->title); ?>" class="w-full h-full object-cover">
                                                    </a>
                                                    <a href="<?php echo e(route('banner.redirect', $banner->slug)); ?>" target="_blank" class="absolute bottom-2 right-2 bg-black/40 hover:bg-black/60 backdrop-blur-sm text-[8px] text-white px-2 py-1 rounded-md transition-all flex items-center gap-1 z-20">
                                                        <span class="material-symbols-rounded text-[10px]">open_in_new</span> Visit
                                                    </a>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>


                                    </div>

                                    <!-- Indicators -->
                                    <?php if($slot2Banners->count() > 1): ?>
                                        <div class="flex gap-2 mt-3 justify-center px-4">
                                            <?php $__currentLoopData = $slot2Banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button @click="goto(<?php echo e($index); ?>)" 
                                                        class="flex-1 h-1.5 rounded-full bg-gray-200 dark:bg-white/10 overflow-hidden relative transition-all duration-300"
                                                        :class="active === <?php echo e($index); ?> ? 'opacity-100' : 'opacity-40'">
                                                    <div class="absolute inset-y-0 left-0 bg-orange-500 transition-all duration-100 ease-linear"
                                                         :style="active === <?php echo e($index); ?> ? `width: ${progress}%` : 'width: 0%'"></div>
                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if($index == 5): ?>
                                <!-- Banner Ad Slot 4 -->
                                <?php if(isset($slot4Banners) && $slot4Banners->count() > 0): ?>
                <div x-data="{ 
                                    active: 0, 
                                    count: <?php echo e($slot4Banners->count()); ?>,
                                    progress: 0,
                                    timer: null,
                                    start() {
                                        if (this.timer) clearInterval(this.timer);
                                        this.timer = setInterval(() => {
                                            this.progress += 1;
                                            if (this.progress >= 100) {
                                                this.active = (this.active + 1) % this.count;
                                                this.progress = 0;
                                            }
                                        }, 50);
                                    },
                                    init() {
                                        if (this.count > 1) this.start();
                                    },
                                    next() { this.active = (this.active + 1) % this.count; this.start(); },
                                    prev() { this.active = (this.active - 1 + this.count) % this.count; this.start(); },
                                    goto(i) { this.active = i; this.start(); }
                                }" class="mb-6 group relative">
                                    <!-- Sponsored Badge -->
                                    <div class="absolute top-2 left-2 z-10 bg-black/60 backdrop-blur-md px-2 py-1 rounded text-[8px] font-black text-white uppercase tracking-[0.2em] flex items-center gap-1 border border-white/10">
                                        <span class="material-symbols-rounded text-[10px]">ads_click</span> Sponsored
                                    </div>

                                    <div class="relative aspect-[3/1] rounded-xl overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm">
                                        <div class="flex h-full transition-transform duration-700 ease-in-out" :style="`width: ${count * 100}%; transform: translateX(-${active * (100 / count)}%)`">
                                            <?php $__currentLoopData = $slot4Banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="h-full flex-shrink-0 relative group/item" style="width: <?php echo e(100 / $slot4Banners->count()); ?>%">
                                                    <a href="<?php echo e(route('banner.redirect', $banner->slug)); ?>" target="_blank" class="block w-full h-full">
                                                        <img src="<?php echo e(getImage($banner->image)); ?>" alt="<?php echo e($banner->title); ?>" class="w-full h-full object-cover">
                                                    </a>
                                                    <a href="<?php echo e(route('banner.redirect', $banner->slug)); ?>" target="_blank" class="absolute bottom-2 right-2 bg-black/40 hover:bg-black/60 backdrop-blur-sm text-[8px] text-white px-2 py-1 rounded-md transition-all flex items-center gap-1 z-20">
                                                        <span class="material-symbols-rounded text-[10px]">open_in_new</span> Visit
                                                    </a>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    <!-- Indicators -->
                                    <?php if($slot4Banners->count() > 1): ?>
                                        <div class="flex gap-2 mt-3 justify-center px-4">
                                            <?php $__currentLoopData = $slot4Banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button @click="goto(<?php echo e($index); ?>)" 
                                                        class="flex-1 h-1.5 rounded-full bg-gray-200 dark:bg-white/10 overflow-hidden relative transition-all duration-300"
                                                        :class="active === <?php echo e($index); ?> ? 'opacity-100' : 'opacity-40'">
                                                    <div class="absolute inset-y-0 left-0 bg-orange-500 transition-all duration-100 ease-linear"
                                                         :style="active === <?php echo e($index); ?> ? `width: ${progress}%` : 'width: 0%'"></div>
                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Thumbnail & Text Flex Block -->
                            <a href="<?php echo e(route('videos.show', $related)); ?>" 
                               class="flex flex-col lg:flex-row gap-3 lg:gap-2.5 group cursor-pointer"
                               <?php
                                     $hasAccess = true;
                                     if ($related->is_premium) {
                                         $hasAccess = false;
                                         if (auth()->check() && (auth()->id() == $related->user_id || auth()->user()->isPurchased($related->id) || auth()->user()->hasPremiumAccess())) {
                                             $hasAccess = true;
                                         }
                                     }
                                     $relChannelName = $related->user->channel->name ?? $related->user->name ?? 'Creator';
                                     $relWords = explode(' ', trim($relChannelName));
                                     $relInitials = count($relWords) >= 2 
                                         ? strtoupper(substr($relWords[0], 0, 1) . substr($relWords[count($relWords)-1], 0, 1))
                                         : strtoupper(substr($relChannelName, 0, 2));
                                 ?>
                                x-data="{
                                     hover: false,
                                     currentTime: 0,
                                     durationSeconds: 0,
                                     hasAccess: <?php echo e($hasAccess ? 'true' : 'false'); ?>,
                                    playVideo() {
                                        if (!this.hasAccess) return;
                                        this.hover = true;
                                        this.$nextTick(() => { 
                                            const v = this.$el.querySelector('video');
                                            if(v) {
                                                v.play().catch(() => {});
                                                v.ontimeupdate = () => {
                                                    this.currentTime = v.currentTime;
                                                    this.durationSeconds = v.duration || 0;
                                                };
                                            }
                                        });
                                    },
                                    stopVideo() {
                                        this.hover = false;
                                        const v = this.$el.querySelector('video');
                                        if(v) {
                                            v.pause();
                                            v.currentTime = 0;
                                            v.ontimeupdate = null;
                                            this.currentTime = 0;
                                        }
                                    },
                                    get displayDuration() {
                                        let orig = '<?php echo e($related->formatted_duration); ?>';
                                        if (!orig || orig === '--:--' || !this.hover || this.currentTime === 0) return orig || '--:--';
                                        
                                        let parts = orig.split(':').reverse();
                                        let origSecs = 0;
                                        for (let i = 0; i < parts.length; i++) {
                                            origSecs += parseInt(parts[i] || 0) * Math.pow(60, i);
                                        }
                                        
                                        let remaining = Math.max(0, origSecs - this.currentTime);
                                        let h = Math.floor(remaining / 3600);
                                        let m = Math.floor((remaining % 3600) / 60);
                                        let s = Math.floor(remaining % 60);
                                        
                                        if (parts.length > 2) {
                                            return h + ':' + (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
                                        } else {
                                            return m + ':' + (s < 10 ? '0' + s : s);
                                        }
                                    }
                               }"
                               @mouseenter="playVideo()"
                               @mouseleave="stopVideo()">
                                <div class="relative w-full lg:w-[168px] aspect-video flex-shrink-0 rounded-2xl lg:rounded-xl overflow-hidden bg-gray-200 dark:bg-[#272727]">
                                    <?php if($related->isBunnyVideo()): ?>
                                        <img :src="hover ? '<?php echo e($related->getPreviewUrl()); ?>' : ''" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" :class="hover ? 'scale-110' : 'scale-100'" loading="lazy" />
                                    <?php elseif($related->video_path): ?>
                                        <video src="<?php echo e(asset(getFilePath('video') . '/' . $related->video_path)); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" :class="hover ? 'scale-110' : 'scale-100'" preload="metadata" muted playsinline></video>
                                    <?php else: ?>
                                        <video src="<?php echo e($related->getVideoUrl()); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700" :class="hover ? 'scale-110' : 'scale-100'" preload="metadata" muted playsinline></video>
                                    <?php endif; ?>
                                    <img src="<?php echo e($related->getThumbnailUrl()); ?>" 
                                         class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 z-10"
                                         :class="hover ? 'opacity-0' : 'opacity-100'"
                                         onerror="this.src='<?php echo e($related->getPreviewUrl()); ?>'; this.onerror=null;">
                                    <span x-text="displayDuration" class="absolute bottom-2 right-2 lg:bottom-1 lg:right-1 bg-black/80 text-white text-[12px] px-1.5 py-0.5 rounded font-medium z-20"><?php echo e($related->formatted_duration); ?></span>
                                </div>
                                <div class="flex flex-1 items-start gap-3 lg:gap-0 lg:flex-col py-0.5 lg:py-0.5 min-w-0 px-1 lg:px-0">
                                    <!-- Mobile-Only Channel Avatar (Left of Title & Meta) -->
                                    <div class="lg:hidden w-10 h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-800 flex-shrink-0 mt-0.5">
                                        <?php if($related->user->channel && $related->user->channel->avatar): ?>
                                            <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $related->user->channel->avatar)); ?>" class="w-full h-full object-cover">
                                        <?php elseif($related->user->image): ?>
                                            <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $related->user->image)); ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <span class="text-[10px] font-black text-gray-500 dark:text-white flex items-center justify-center w-full h-full"><?php echo e($relInitials); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Content Column -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start gap-2">
                                            <h4 class="font-bold text-[14px] sm:text-[15px] lg:text-[14px] leading-snug line-clamp-2 text-gray-900 dark:text-[#F1F1F1]"><?php echo e($related->title); ?></h4>
                                            <button onclick="event.preventDefault(); event.stopPropagation(); window.openVideoOptions('<?php echo e($related->id); ?>', '<?php echo e(addslashes($related->title)); ?>', 'video', <?php echo e($related->isLikedBy(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($related->isInWatchLater(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($related->isInAnyPlaylist(auth()->user()) ? 'true' : 'false'); ?>, <?php echo json_encode($related->getPlaylistMembershipIds(auth()->user() ?? null), 15, 512) ?>, <?php echo e($related->user_id); ?>)" 
                                                    class="text-gray-400 dark:text-[#AAAAAA] hover:text-gray-900 dark:hover:text-white transition-colors shrink-0 p-1 -mr-1">
                                                <span class="material-symbols-rounded text-[20px]">more_vert</span>
                                            </button>
                                        </div>
                                        <div class="flex flex-col mt-1 text-[12px] text-gray-500 dark:text-[#AAAAAA]">
                                            <!-- Desktop Channel Avatar + Name Row -->
                                            <div class="hidden lg:flex items-center gap-2 mb-1">
                                                <div class="w-4 h-4 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-800 flex items-center justify-center shrink-0">
                                                    <?php if($related->user->channel && $related->user->channel->avatar): ?>
                                                        <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $related->user->channel->avatar)); ?>" class="w-full h-full object-cover">
                                                    <?php elseif($related->user->image): ?>
                                                        <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $related->user->image)); ?>" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <span class="text-[6px] font-black text-gray-500 dark:text-white"><?php echo e($relInitials); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="hover:text-gray-900 dark:hover:text-white transition-colors line-clamp-1 truncate"><?php echo e($relChannelName); ?></p>
                                            </div>

                                            <!-- Mobile Channel Name + Views + Time Row -->
                                            <p class="lg:hidden text-[12px] text-gray-500 dark:text-gray-400 line-clamp-1">
                                                <?php echo e($relChannelName); ?> &bull; <?php echo e(number_format($related->views_count)); ?> views &bull; <?php echo e($related->created_at->diffForHumans()); ?>

                                            </p>

                                            <!-- Desktop Views & Time -->
                                            <p class="hidden lg:block"><?php echo e(number_format($related->views_count)); ?> views &bull; <?php echo e($related->created_at->diffForHumans()); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            
                            <!-- Loading Spinner (Mobile/Tablet Only) -->
                            <div x-show="loading" class="py-6 flex justify-center lg:hidden">
                                <div class="w-8 h-8 rounded-full border-4 border-gray-200 border-t-orange-500 animate-spin"></div>
                            </div>
                            
                            <!-- Sentinel Element -->
                            <div id="related-sentinel" class="h-4 lg:hidden"></div>
                        </div>
                    </div>
                </div>

                <!-- Removed duplicate mobile comments block -->

            </div>
        </div>

        <!-- Membership Selection Modal -->
        <!-- Membership Selection Modal (Native Android High-Fidelity Look) -->
        <div x-data="{ open: false }" 
             @open-membership-modal.window="open = true" 
             @keydown.escape.window="open = false"
             x-show="open" 
             class="fixed inset-0 z-[200000] flex items-center justify-center lg:p-4" 
             x-cloak>
            
            <!-- Backdrop -->
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-black/80 backdrop-blur-md" 
                 @click="open = false"></div>

            <!-- Content -->
            <div x-show="open" 
                 x-transition:enter="ease-out duration-500 transform" 
                 x-transition:enter-start="translate-y-full opacity-0 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100" 
                 x-transition:leave="ease-in duration-300 transform" 
                 x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100" 
                 x-transition:leave-end="translate-y-full opacity-0 sm:translate-y-0 sm:scale-95" 
                 class="w-full fixed bottom-0 lg:relative lg:bottom-auto lg:max-w-xl bg-[#1A1A1A] rounded-t-[2.5rem] lg:rounded-[2.5rem] border-t border-x lg:border border-white/10 shadow-[0_-24px_48px_-12px_rgba(0,0,0,0.5)] overflow-hidden transition-all duration-500">
                
                <!-- Android Drag Handle (Mobile Only) -->
                <div class="lg:hidden w-12 h-1.5 bg-white/10 rounded-full mx-auto mt-4 mb-2" @click="open = false"></div>

                <div class="p-6 sm:p-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-white tracking-tight uppercase">Join the Channel</h3>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Select your membership tier</p>
                        </div>
                        <button @click="open = false" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white transition-colors">
                            <span class="material-symbols-rounded">close</span>
                        </button>
                    </div>

                    <div class="space-y-4 max-h-[65vh] overflow-y-auto pr-2 custom-scrollbar">
                        <?php if($video->user->channel): ?>
                            <?php $__currentLoopData = $video->user->channel->memberships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-6 rounded-3xl bg-white/5 border border-white/10 hover:border-orange-500/50 transition-all duration-300 group relative overflow-hidden">
                                <div class="absolute -top-12 -right-12 w-24 h-24 bg-orange-500/10 blur-3xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                
                                <div class="flex justify-between items-start mb-6">
                                    <div>
                                        <h4 class="font-black text-lg text-white group-hover:text-orange-500 transition-colors uppercase "><?php echo e($membership->name); ?></h4>
                                        <div class="flex items-baseline gap-1 mt-1">
                                            <span class="text-2xl font-black text-white tracking-tighter">$<?php echo e(number_format($membership->price, 2)); ?></span>
                                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">/ month</span>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                        <span class="material-symbols-rounded">loyalty</span>
                                    </div>
                                </div>

                                <div class="space-y-3 mb-8">
                                    <?php $__currentLoopData = $membership->perks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center gap-3">
                                            <div class="w-5 h-5 rounded-full bg-green-500/20 flex items-center justify-center text-green-500">
                                                <span class="material-symbols-rounded text-[14px] material-symbols-filled">check</span>
                                            </div>
                                            <span class="text-sm font-medium text-gray-400"><?php echo e($perk); ?></span>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <form action="<?php echo e(route('memberships.join', $membership)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="w-full h-14 rounded-2xl gradient-orange text-white font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-orange-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-3 border border-white/10">
                                        Join for $<?php echo e(number_format($membership->price, 2)); ?>

                                    </button>
                                </form>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="py-12 text-center">
                                <span class="material-symbols-rounded text-5xl text-gray-700 mb-4">group_off</span>
                                <p class="text-gray-500 font-bold uppercase tracking-widest text-xs">No active membership tiers</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <p class="mt-8 text-[10px] text-gray-500 dark:text-gray-600 text-center font-black uppercase tracking-[0.2em] leading-loose px-4">
                        Secure checkout via Razorpay &bull; Cancel anytime &bull; Direct creator support
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Native Share Modal (YouTube Mobile/Desktop Hybrid) -->
        <div x-show="shareOpen" 
             class="fixed inset-0 z-[300000] flex items-center justify-center lg:p-4" 
             x-cloak
             @keydown.escape.window="shareOpen = false">
            
            <div x-show="shareOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-black/60 backdrop-blur-md" 
                 @click="shareOpen = false"></div>

            <div x-show="shareOpen" 
                 x-transition:enter="ease-out duration-500 transform" 
                 x-transition:enter-start="translate-y-full lg:translate-y-0 lg:scale-95" 
                 x-transition:enter-end="translate-y-0 lg:scale-100" 
                 x-transition:leave="ease-in duration-300 transform" 
                 x-transition:leave-start="translate-y-0 lg:scale-100" 
                 x-transition:leave-end="translate-y-full lg:translate-y-0 lg:scale-95" 
                 class="w-full fixed bottom-0 lg:relative lg:bottom-auto lg:max-w-md bg-white dark:bg-[#1A1A1A] rounded-t-[2rem] lg:rounded-[2.5rem] shadow-2xl overflow-hidden transition-all duration-500">
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-widest " x-text="shareTitle">Share</h3>
                        <button @click="shareOpen = false" class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-400">
                            <span class="material-symbols-rounded">close</span>
                        </button>
                    </div>

                    <!-- Share Toggle -->
                    <div class="flex bg-gray-100 dark:bg-white/5 p-1 rounded-xl mb-6">
                        <button @click="setShareType('video')" :class="shareType === 'video' ? 'bg-white dark:bg-[#272727] text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white'" class="flex-1 py-2 text-xs font-black uppercase tracking-widest rounded-lg transition-all">Video</button>
                        <button @click="setShareType('channel')" :class="shareType === 'channel' ? 'bg-white dark:bg-[#272727] text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white'" class="flex-1 py-2 text-xs font-black uppercase tracking-widest rounded-lg transition-all">Channel</button>
                    </div>

                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-4 mb-8">
                        <?php
                            $shareLinks = [
                                ['name' => 'WhatsApp', 'icon' => 'fab fa-whatsapp', 'color' => '#25D366', 'url' => 'https://wa.me/?text='],
                                ['name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'color' => '#1877F2', 'url' => 'https://www.facebook.com/sharer/sharer.php?u='],
                                ['name' => 'Twitter', 'icon' => 'fab fa-x-twitter', 'color' => '#000000', 'url' => 'https://twitter.com/intent/tweet?url='],
                                ['name' => 'Email', 'icon' => 'fas fa-envelope', 'color' => '#EA4335', 'url' => 'mailto:?body='],
                                ['name' => 'Reddit', 'icon' => 'fab fa-reddit-alien', 'color' => '#FF4500', 'url' => 'https://www.reddit.com/submit?url='],
                            ];
                        ?>
                        <?php $__currentLoopData = $shareLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a :href="'<?php echo e($link['url']); ?>' + encodeURIComponent(shareUrl)" target="_blank" class="flex flex-col items-center gap-2 group">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white shadow-lg transition-transform group-active:scale-90" style="background-color: <?php echo e($link['color']); ?>">
                                    <i class="<?php echo e($link['icon']); ?> text-xl"></i>
                                </div>
                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-tighter"><?php echo e($link['name']); ?></span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="bg-gray-50 dark:bg-black/40 border border-gray-100 dark:border-white/5 rounded-2xl p-2 flex items-center gap-3">
                        <input type="text" readonly :value="shareUrl" class="flex-1 bg-transparent border-0 focus:ring-0 text-sm text-gray-600 dark:text-gray-300 font-medium px-2">
                        <button @click="copyLink(shareUrl)" class="bg-red-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest active:scale-95 transition-all">Copy</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function copyLink(url) {
                navigator.clipboard.writeText(url).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'bottom',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: 'Link copied to clipboard',
                        background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                    });
                });
            }
        </script>

        <script>
            window.setVideoQuality = function(q) {
                const val = parseInt(q);
                console.log('[QualitySwitch] Target:', val);
                
                if (window.plyrPlayer) {
                    console.log('[QualitySwitch] Updating Plyr property');
                    window.plyrPlayer.quality = val;
                } else {
                    console.error('[QualitySwitch] Plyr instance MISSING');
                }
                
                if (window.hlsPlayer) {
                    if (!window.hlsPlayer.levels || window.hlsPlayer.levels.length === 0) {
                        console.error('[QualitySwitch] HLS levels NOT LOADED');
                    } else if (val === 0) {
                        console.log('[QualitySwitch] Resetting HLS to Auto');
                        window.hlsPlayer.currentLevel = -1;
                        window.hlsPlayer.loadLevel = -1;
                        window.hlsPlayer.nextLevel = -1;
                    } else {
                        const idx = window.hlsPlayer.levels.findIndex(l => l.height === val);
                        console.log('[QualitySwitch] Found HLS index:', idx);
                        if (idx !== -1) {
                            window.hlsPlayer.currentLevel = idx;
                            window.hlsPlayer.loadLevel = idx;
                            window.hlsPlayer.nextLevel = idx;
                        } else {
                            console.warn('[QualitySwitch] Quality not found in HLS levels:', window.hlsPlayer.levels.map(l => l.height));
                        }
                    }
                } else {
                    console.warn('[QualitySwitch] HLS.js instance MISSING (Native playback?)');
                }
                
                window.dispatchEvent(new CustomEvent('quality-updated', { detail: { quality: val } }));
            };
        </script>


        <!-- Description & About Section Modal (Adaptive: Popup on Desktop, Slide-up on Mobile) -->
        <div x-show="showDescription" 
             x-init="$watch('showDescription', v => { if (v) { document.body.style.overflow = 'hidden'; } else { document.body.style.overflow = ''; } })"
             class="fixed inset-0 z-[200000] flex items-end lg:items-center justify-center p-0 lg:p-4"
             x-cloak>
            
            <!-- Overlay -->
            <div x-show="showDescription"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showDescription = false"
                 class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <!-- Modal Content -->
            <div x-show="showDescription" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-y-full lg:translate-y-10 lg:opacity-0"
                 x-transition:enter-end="translate-y-0 lg:translate-y-0 lg:opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-y-0 lg:translate-y-0 lg:opacity-100"
                 x-transition:leave-end="translate-y-full lg:translate-y-10 lg:opacity-0"
                 class="relative w-full h-[85vh] h-[85dvh] lg:h-auto lg:max-h-[85vh] lg:max-w-2xl bg-white dark:bg-[#0F0F0F] rounded-t-[32px] lg:rounded-[32px] overflow-hidden flex flex-col shadow-2xl shadow-black/50 border border-white/5">
                
                <!-- Header -->
                <div class="shrink-0 bg-white/80 dark:bg-[#0F0F0F]/80 backdrop-blur-xl border-b border-gray-100 dark:border-white/5 px-4 lg:px-6 h-14 lg:h-16 flex items-center justify-between">
                    <h2 class="text-lg lg:text-xl font-black tracking-tight text-gray-900 dark:text-white">Description</h2>
                    <button @click="showDescription = false" class="w-8 h-8 lg:w-10 lg:h-10 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-all">
                        <span class="material-symbols-rounded text-xl lg:text-[24px]">close</span>
                    </button>
                </div>

                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto overscroll-contain touch-pan-y no-scrollbar p-4 lg:p-6 space-y-6 lg:space-y-8 pb-8 lg:pb-10">
                    <!-- Video Title & Stats -->
                    <section class="border-b dark:border-white/5 pb-4 lg:pb-6">
                        <h1 class="text-lg lg:text-xl font-black text-gray-900 dark:text-white mb-2 lg:mb-3 leading-tight">
                            <?php echo preg_replace('/#([\p{L}\p{M}\p{N}_]+)/u', '<a href="/search?q=%23$1" class="text-blue-600 dark:text-blue-400 hover:underline">#$1</a>', e($video->title)); ?>

                        </h1>
                        <div class="grid grid-cols-3 text-[11px] lg:text-sm font-bold text-gray-500">
                            <div class="flex flex-col">
                                <span class="text-gray-900 dark:text-white text-base lg:text-lg font-black leading-none mb-0.5" x-text="likes"><?php echo e(number_format($video->likes->count())); ?></span>
                                <span>Likes</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-900 dark:text-white text-base lg:text-lg font-black leading-none mb-0.5"><?php echo e(number_format($video->views_count)); ?></span>
                                <span>Views</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-900 dark:text-white text-base lg:text-lg font-black leading-none mb-0.5"><?php echo e($video->created_at->format('M d')); ?></span>
                                <span><?php echo e($video->created_at->format('Y')); ?></span>
                            </div>
                        </div>
                    </section>

                    <!-- Description Text -->
                    <section class="space-y-3 lg:space-y-4">
                        <div class="text-xs lg:text-sm font-medium text-gray-800 dark:text-neutral-200 leading-relaxed whitespace-pre-wrap"><?php echo nl2br(preg_replace('/#([\p{L}\p{M}\p{N}_]+)/u', '<a href="/search?q=%23$1" class="text-orange-500 hover:underline">#$1</a>', e($video->description))); ?></div>
                        
                        <!-- Hashtags (using categories as requested) -->
                        <?php if($video->categories->count() > 0): ?>
                            <div class="flex flex-wrap gap-1.5 lg:gap-2 pt-1.5 lg:pt-2">
                                <?php $__currentLoopData = $video->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="/search?q=<?php echo e(urlencode(trim($category->name))); ?>" class="text-blue-600 dark:text-blue-400 font-bold text-xs lg:text-sm hover:underline">#<?php echo e(trim($category->name)); ?></a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Video Tags / Hashtags -->
                        <?php if($video->tags->count() > 0): ?>
                            <div class="flex flex-wrap gap-1.5 lg:gap-2 pt-2 lg:pt-3 border-t border-gray-100 dark:border-white/5">
                                <?php $__currentLoopData = $video->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="/search?q=<?php echo e(urlencode('#' . trim($tag->tag))); ?>" class="inline-flex items-center px-2 py-0.5 lg:px-3 lg:py-1 bg-orange-500/10 hover:bg-orange-500/20 text-orange-500 rounded-full text-[10px] lg:text-xs font-bold transition-all hover:scale-105">
                                        #<?php echo e(trim($tag->tag)); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </section>

                    <!-- Channel Info Block (Native Look) -->
                    <section class="bg-gray-50 dark:bg-white/5 rounded-xl lg:rounded-2xl p-3 lg:p-4 flex items-center justify-between gap-2 lg:gap-4">
                        <a href="<?php echo e($video->user?->channel ? route('channels.show', $video->user->channel) : '#'); ?>" class="flex items-center gap-2 lg:gap-3 hover:opacity-90 transition-opacity">
                            <div class="w-9 h-9 lg:w-12 lg:h-12 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-800 shrink-0">
                                 <?php if($video->user->channel && $video->user->channel->avatar): ?>
                                    <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $video->user->channel->avatar)); ?>" class="w-full h-full object-cover">
                                <?php elseif($video->user->image): ?>
                                    <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $video->user->image)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-red-600 text-white font-black text-sm lg:text-lg uppercase">
                                        <?php
                                            $name = $video->user->channel->name ?? $video->user->name;
                                            $words = explode(' ', trim($name));
                                            $initials = count($words) >= 2 
                                                ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1))
                                                : strtoupper(substr($name, 0, 2));
                                        ?>
                                        <?php echo e($initials); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex flex-col text-left">
                                <h3 class="font-black text-xs lg:text-sm text-gray-900 dark:text-white leading-tight"><?php echo e($video->user->channel->name ?? $video->user->name); ?></h3>
                                <p class="text-[10px] lg:text-xs font-bold text-gray-500"><?php echo e(number_format($video->user->channel->subscribers_count ?? 0)); ?> subscribers</p>
                            </div>
                        </a>
                        <div class="flex items-center gap-1.5 lg:gap-2">
                             <?php if(auth()->id() !== $video->user_id): ?>
                                 <button @click="toggleSubscribe" 
                                         class="px-3 py-1.5 lg:px-5 lg:py-2 rounded-full font-black text-[10px] lg:text-[12px] shadow-lg transition-all active:scale-95"
                                         :class="subscribed ? 'bg-gray-200 dark:bg-white/10 text-gray-900 dark:text-white' : 'gradient-orange text-white shadow-orange-500/20'"
                                         x-text="subscribed ? 'Subscribed' : 'Subscribe'">
                                 </button>
                             <?php endif; ?>
                             <a href="<?php echo e($video->user?->channel ? route('channels.show', $video->user->channel) : '#'); ?>" class="px-3 py-1.5 lg:px-4 lg:py-2 bg-gray-100 dark:bg-[#272727] text-gray-900 dark:text-white rounded-full text-[10px] lg:text-[12px] font-black border border-transparent dark:border-white/5 hover:bg-gray-200 dark:hover:bg-[#3F3F3F] transition-all">View Channel</a>
                        </div>
                    </section>
                </div>
            </div>
        </div>

    <!-- Mobile Settings Sheet -->
    <div x-data="{ 
            open: false,
            view: 'main', // 'main', 'speed', 'quality', 'audio'
            speeds: [0.25, 0.5, 0.75, 1, 1.25, 1.5, 1.75, 2],
            isZoomed: false,
            qualities: [],
            activeQuality: 0,
            audioTracks: [],
            activeAudioTrack: 0,
            _speed: 1,
            init() {
                this.dark = document.documentElement.classList.contains('dark');
                window.addEventListener('system-theme', (e) => { this.dark = !!e.detail.dark; });
                window.addEventListener('zoom-changed', (e) => {
                    this.isZoomed = e.detail.isZoomed;
                });
                window.addEventListener('quality-updated', (e) => {
                    this.activeQuality = e.detail.quality;
                });
                window.addEventListener('audio-track-switched', (e) => {
                    this.activeAudioTrack = e.detail.id;
                });
            },
            toggleTheme() {
                this.dark = !this.dark;
                if (window.__setTheme) {
                    window.__setTheme(this.dark);
                } else {
                    try { localStorage.setItem('binteo-theme', this.dark ? 'dark' : 'light'); } catch(e) {}
                    if (this.dark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            },
            dark: (function(){ try { var s = localStorage.getItem('binteo-theme'); if (s === 'dark') return true; if (s === 'light') return false; return window.matchMedia('(prefers-color-scheme: dark)').matches; } catch(e){ return document.documentElement.classList.contains('dark'); } })(),
            handleToggle() {
                console.log('Settings Sheet Toggle Received');
                this.view = 'main';
                this.open = !this.open;
                if (this.open && window.plyrPlayer) {
                    this.qualities = window.plyrPlayer.config.quality.options || [];
                    this.activeQuality = window.plyrPlayer.quality || 0;
                }
                if (this.open && window.hlsPlayer && Array.isArray(window.hlsPlayer.audioTracks)) {
                    this.audioTracks = window.hlsPlayer.audioTracks.map((t, i) => ({
                        id: t.id ?? i,
                        name: t.name || (t.lang ? t.lang.toUpperCase() : 'Audio ' + (i + 1)),
                        lang: t.lang || ''
                    }));
                    this.activeAudioTrack = typeof window.hlsPlayer.audioTrack === 'number' && window.hlsPlayer.audioTrack >= 0
                        ? window.hlsPlayer.audioTrack : 0;
                }
            },
            get hasMultipleAudio() {
                return (this.audioTracks || []).length > 1;
            },
            setAudio(id) {
                if (window.hlsPlayer) {
                    window.hlsPlayer.audioTrack = id;
                    this.activeAudioTrack = id;
                }
                this.open = false;
            },
            get currentSpeed() {
                return this._speed;
            },
            get currentQuality() {
                return this.activeQuality;
            },
            get currentQualityLabel() {
                const q = this.currentQuality;
                if (q === 0 || q === '0') {
                    if (window.hlsPlayer && window.hlsPlayer.currentLevel !== -1) {
                        const level = window.hlsPlayer.levels[window.hlsPlayer.currentLevel];
                        return level ? `Auto (${level.height}p)` : 'Auto';
                    }
                    return 'Auto';
                }
                return q + 'p';
            },
            get qualityOptions() {
                if (window.hlsPlayer && window.hlsPlayer.levels && window.hlsPlayer.levels.length > 0) {
                    return window.hlsPlayer.levels.map(l => l.height).sort((a,b) => b-a);
                }
                if (window.plyrPlayer && window.plyrPlayer.config.quality.options) {
                    return window.plyrPlayer.config.quality.options.filter(q => q !== 0);
                }
                return this.qualities || [];
            },
            setSpeed(s) {
                if(window.plyrPlayer) window.plyrPlayer.speed = s;
                this._speed = s;
                this.open = false;
            },
            setQuality(q) {
                window.setVideoQuality(q);
                this.open = false;
            },
            _originalParent: null,
            _moveToFullscreen() {
                // When in fullscreen, the sheet must be INSIDE the fullscreen element to be visible
                const fsEl = document.fullscreenElement || document.webkitFullscreenElement;
                if (fsEl && !fsEl.contains(this.$el)) {
                    this._originalParent = this.$el.parentElement;
                    fsEl.appendChild(this.$el);
                }
            },
            _restoreFromFullscreen() {
                if (this._originalParent && this._originalParent !== this.$el.parentElement) {
                    this._originalParent.appendChild(this.$el);
                    this._originalParent = null;
                }
            }
         }" 
         @toggle-mobile-settings.window="_moveToFullscreen(); handleToggle()"
         @fullscreenchange.window="if(!document.fullscreenElement && !document.webkitFullscreenElement) { _restoreFromFullscreen(); }"
         x-init="$watch('open', v => { if(!v) { $nextTick(() => _restoreFromFullscreen()); } })"
         class="mobile-settings-sheet" 
         :class="{ 'active': open }" 
         x-show="open"
         x-cloak>
        <div class="sheet-overlay" @click="open = false"></div>
        <div class="sheet-content">
            <div class="w-12 h-1.5 bg-gray-200 dark:bg-white/10 rounded-full mx-auto mb-6 lg:hidden shrink-0"></div>
            
            <!-- Close Button -->
            <button @click="open = false" class="absolute top-4 right-4 w-8 h-8 rounded-full gradient-orange flex items-center justify-center text-white shadow-lg z-[1002] active:scale-95 transition-all">
                <span class="material-symbols-rounded text-lg">close</span>
            </button>
            
            <div class="overflow-y-auto no-scrollbar flex-1 px-2 pb-4">
                <!-- Main View -->
            <div x-show="view === 'main'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="flex items-center gap-4 mb-6 px-2">
                    <h3 class="text-lg font-black text-white uppercase tracking-tight">Settings</h3>
                </div>
                <div class="space-y-1">
                    <div class="sheet-item" @click="toggleTheme()">
                        <div class="w-10 h-10 rounded-2xl bg-white/5 flex items-center justify-center">
                            <span class="material-symbols-rounded text-xl" x-text="dark ? 'light_mode' : 'dark_mode'"></span>
                        </div>
                        <span class="flex-1 font-bold">Appearance</span>
                        <span class="text-sm font-black text-gray-500 uppercase" x-text="dark ? 'Dark' : 'Light'"></span>
                    </div>
                    <div class="sheet-item" @click="if(window.plyrPlayer) window.plyrPlayer.captions.active = !window.plyrPlayer.captions.active; open = false">
                        <div class="w-10 h-10 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                            <span class="material-symbols-rounded text-xl">closed_caption</span>
                        </div>
                        <span class="flex-1 font-bold">Captions</span>
                        <span class="text-sm font-black text-gray-500 uppercase" x-text="window.plyrPlayer?.captions?.active ? 'On' : 'Off'"></span>
                    </div>
                    <div class="sheet-item" @click="view = 'speed'">
                        <div class="w-10 h-10 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                            <span class="material-symbols-rounded text-xl">speed</span>
                        </div>
                        <span class="flex-1 font-bold">Playback Speed</span>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-black text-gray-500 uppercase" x-text="currentSpeed === 1 ? 'Normal' : currentSpeed + 'x'"></span>
                            <span class="material-symbols-rounded text-gray-600">chevron_right</span>
                        </div>
                    </div>
                    <div class="sheet-item" @click="view = 'quality'">
                        <div class="w-10 h-10 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                            <span class="material-symbols-rounded text-xl">high_quality</span>
                        </div>
                        <span class="flex-1 font-bold">Quality</span>
                        <div class="flex items-center gap-2">
                             <span class="text-sm font-black text-gray-500 uppercase" x-text="currentQualityLabel"></span>
                            <span class="material-symbols-rounded text-gray-600">chevron_right</span>
                        </div>
                    </div>
                    <div class="sheet-item" x-show="hasMultipleAudio" @click="view = 'audio'" x-cloak>
                        <div class="w-10 h-10 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                            <span class="material-symbols-rounded text-xl">audiotrack</span>
                        </div>
                        <span class="flex-1 font-bold">Audio</span>
                        <div class="flex items-center gap-2">
                             <span class="text-sm font-black text-gray-500 uppercase" x-text="(audioTracks.find(t => t.id === activeAudioTrack)?.name) || 'Default'"></span>
                            <span class="material-symbols-rounded text-gray-600">chevron_right</span>
                        </div>
                    </div>
                    <div class="sheet-item" @click="window.dispatchEvent(new CustomEvent('toggle-zoom')); open = false">
                        <div class="w-10 h-10 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                            <span class="material-symbols-rounded text-xl">zoom_in_map</span>
                        </div>
                        <span class="flex-1 font-bold">Zoom to fill</span>
                        <span class="text-sm font-black text-gray-500 uppercase" x-text="isZoomed ? 'On' : 'Off'"></span>
                    </div>
                </div>
            </div>

            <!-- Speed View -->
            <div x-show="view === 'speed'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="flex items-center gap-4 mb-6 px-2">
                    <button @click="view = 'main'" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">chevron_left</span>
                    </button>
                    <h3 class="text-lg font-black text-white uppercase tracking-tight">Playback Speed</h3>
                </div>
                <div class="max-h-[40vh] overflow-y-auto no-scrollbar space-y-1">
                    <template x-for="s in speeds">
                        <div class="sheet-item" @click="setSpeed(s)">
                            <span class="flex-1 font-bold" x-text="s === 1 ? 'Normal' : s + 'x'"></span>
                            <span x-show="currentSpeed === s" class="material-symbols-rounded text-red-500 material-symbols-filled">check_circle</span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Quality View -->
            <div x-show="view === 'quality'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="flex items-center gap-4 mb-6 px-2">
                    <button @click="view = 'main'" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">chevron_left</span>
                    </button>
                    <h3 class="text-lg font-black text-white uppercase tracking-tight">Quality</h3>
                </div>
                <div class="max-h-[40vh] overflow-y-auto no-scrollbar space-y-1">
                    <div class="sheet-item" @click="setQuality(0)">
                        <span class="flex-1 font-bold">Auto</span>
                        <span x-show="currentQuality === 'Auto' || currentQuality === 0" class="material-symbols-rounded text-red-500 material-symbols-filled">check_circle</span>
                    </div>
                    <template x-for="q in qualityOptions">
                         <div class="sheet-item" @click="setQuality(q)">
                            <span class="flex-1 font-bold" x-text="q + 'p'"></span>
                            <span x-show="currentQuality == q" class="material-symbols-rounded text-red-500 material-symbols-filled">check_circle</span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Audio View (only when Bunny serves multiple audio tracks) -->
            <div x-show="view === 'audio'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="flex items-center gap-4 mb-6 px-2">
                    <button @click="view = 'main'" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center">
                        <span class="material-symbols-rounded text-lg">chevron_left</span>
                    </button>
                    <h3 class="text-lg font-black text-white uppercase tracking-tight">Audio</h3>
                </div>
                <div class="max-h-[40vh] overflow-y-auto no-scrollbar space-y-1">
                    <template x-for="t in audioTracks" :key="t.id">
                        <div class="sheet-item" @click="setAudio(t.id)">
                            <div class="flex-1 min-w-0">
                                <span class="block font-bold truncate" x-text="t.name"></span>
                                <span x-show="t.lang" class="block text-xs font-bold text-gray-500 uppercase" x-text="t.lang"></span>
                            </div>
                            <span x-show="activeAudioTrack === t.id" class="material-symbols-rounded text-red-500 material-symbols-filled">check_circle</span>
                        </div>
                    </template>
                </div>
            </div>

            </div>
            
            <button @click="open = false" class="w-full py-4 text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest text-[10px] border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#1C1C1C] hover:bg-gray-100 dark:hover:bg-[#2F2F2F] transition-colors rounded-b-3xl shrink-0">Close Settings</button>
        </div>
    </div>
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('infiniteScrollRelated', (url) => ({
            page: 1,
            loading: false,
            hasMore: true,
            url: url,
            
            init() {
                // Setup intersection observer for mobile/tablet only
                if (window.innerWidth < 1024) {
                    const observer = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting && this.hasMore && !this.loading) {
                            this.loadMore();
                        }
                    }, { rootMargin: '200px' });
                    
                    setTimeout(() => {
                        const sentinel = document.getElementById('related-sentinel');
                        if(sentinel) observer.observe(sentinel);
                    }, 500);
                }
            },
            
            loadMore() {
                this.loading = true;
                this.page++;
                
                // Get current filter from URL if it exists
                const urlParams = new URLSearchParams(window.location.search);
                const filter = urlParams.get('filter') || 'all';
                
                fetch(`${this.url}?page=${this.page}&filter=${filter}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.text())
                .then(html => {
                    if (html.trim().length === 0) {
                        this.hasMore = false;
                    } else {
                        document.getElementById('related-videos-container').insertAdjacentHTML('beforeend', html);
                    }
                })
                .catch(err => {
                    console.error(err);
                    this.hasMore = false;
                })
                .finally(() => {
                    this.loading = false;
                });
            }
        }));
    });
    </script>
    <script>
        // ==========================================
        // VIDEO WATCH LOG TELEMETRY
        // ==========================================
                window.triggerBunnyViewPing = function() {
            if (window._bunnyViewPinged) return;
            <?php if($video->isBunnyVideo() && $video->bunny_id): ?>
                try {
                    var embedUrl = '<?php echo $video->getBunnyEmbedUrl(true); ?>&muted=true';
                    if (!embedUrl) return;
                    
                    console.log('[BunnyTelemetry] Triggering view ping...');
                    console.log('[BunnyTelemetry] Embed URL:', embedUrl);
                    
                    window._bunnyViewPinged = true;

                    var iframe = document.createElement('iframe');
                    iframe.style.position = 'fixed';
                    iframe.style.width = '10px';
                    iframe.style.height = '10px';
                    iframe.style.opacity = '0.001';
                    iframe.style.pointerEvents = 'none';
                    iframe.style.bottom = '0';
                    iframe.style.right = '0';
                    iframe.style.zIndex = '-1';
                    iframe.style.border = 'none';
                    iframe.src = embedUrl;
                    iframe.setAttribute('allow', 'autoplay; encrypted-media');
                    iframe.setAttribute('loading', 'eager');
                    
                    iframe.onload = function() {
                        console.log('[BunnyTelemetry] Bunny iframe loaded successfully.');
                        // Send log to backend
                        fetch('/api/telemetry/log-bunny', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                video_id: '<?php echo e($video->id); ?>',
                                bunny_id: '<?php echo e($video->bunny_id); ?>',
                                message: 'Iframe loaded successfully',
                                url: embedUrl
                            })
                        }).catch(e => console.error(e));
                    };

                    document.body.appendChild(iframe);

                    setTimeout(function() {
                        try {
                            if (iframe && iframe.parentNode) {
                                iframe.parentNode.removeChild(iframe);
                                console.log('[BunnyTelemetry] Iframe cleaned up after 30 seconds.');
                            }
                        } catch(e) {}
                    }, 30000);
                } catch (e) {
                    console.error('[BunnyTelemetry] Ping error:', e);
                }
            <?php endif; ?>
        };

        window.pingVideoTelemetry = function(currentTime, totalDuration, isComplete = false) {
            if (currentTime >= 3 && typeof window.triggerBunnyViewPing === 'function') {
                window.triggerBunnyViewPing();
            }

            const sessionToken = sessionStorage.getItem('telemetry_session_token');
            if (!sessionToken || !totalDuration) return;
            
            fetch('/api/telemetry/ping', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    video_id: '<?php echo e($video->id); ?>',
                    session_token: sessionToken,
                    current_time: currentTime,
                    total_duration: totalDuration,
                    watch_increment: isComplete ? 0 : 10,
                    is_complete: isComplete
                }),
                keepalive: true
            }).catch(() => {});
        };
    </script>

    <!-- Channel Membership Modal (Root Level for proper z-index and viewport stacking) -->
    <?php if (isset($component)) { $__componentOriginalf45f692429248001c0aedfcef348f9e5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf45f692429248001c0aedfcef348f9e5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.channel-membership-modal','data' => ['channel' => optional($video->user)->channel,'plans' => $channelPlans ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('channel-membership-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['channel' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(optional($video->user)->channel),'plans' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($channelPlans ?? null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf45f692429248001c0aedfcef348f9e5)): ?>
<?php $attributes = $__attributesOriginalf45f692429248001c0aedfcef348f9e5; ?>
<?php unset($__attributesOriginalf45f692429248001c0aedfcef348f9e5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf45f692429248001c0aedfcef348f9e5)): ?>
<?php $component = $__componentOriginalf45f692429248001c0aedfcef348f9e5; ?>
<?php unset($__componentOriginalf45f692429248001c0aedfcef348f9e5); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>



<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\videos\show.blade.php ENDPATH**/ ?>