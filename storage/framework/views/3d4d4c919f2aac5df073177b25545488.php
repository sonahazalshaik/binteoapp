<!-- MOBILE NAVIGATION BAR -->
<div id="mobile-bottom-nav" class="lg:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-[#0F0F0F] border-t border-gray-100 dark:border-white/5 px-4 pb-safe z-[90] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] <?php echo e(request()->routeIs('reels.index') ? 'hidden' : ''); ?>">
    <div class="flex items-center justify-between h-16 max-w-md mx-auto relative">
        <?php
            $miniOttStatus = gs('mini_ott_status');
            $isMiniOttHidden = $miniOttStatus !== null && (int) $miniOttStatus === 2;
            $miniOttItem = $isMiniOttHidden
                ? ['url' => route('home'), 'pattern' => 'videos*', 'label' => 'Watch Now', 'icon' => 'play', 'badge' => null]
                : ['url' => route('premium'), 'pattern' => 'premium*', 'label' => 'Mini OTT', 'icon' => 'crown', 'badge' => null];

            $navItems = [
                ['url' => route('home'), 'pattern' => 'home*', 'label' => 'Home', 'icon' => 'home', 'badge' => null],
                ['url' => route('reels.index'), 'pattern' => 'reels.index', 'label' => 'Reels', 'icon' => 'reels', 'badge' => null],
                $miniOttItem,
                ['url' => route('marketplace.index'), 'pattern' => 'marketplace*', 'label' => 'Market Hub', 'icon' => 'bag', 'badge' => null],
            ];
        ?>

        <!-- First 2 items -->
        <?php $__currentLoopData = array_slice($navItems, 0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $isActive = request()->routeIs($item['pattern']); ?>
            <a href="<?php echo e($item['url']); ?>" class="relative flex flex-col items-center justify-center flex-1 h-full min-w-[40px] group transition-all duration-300">
                <?php if($isActive): ?>
                    <div class="absolute inset-y-1.5 inset-x-0.5 border border-orange-200/60 rounded-[1.5rem] z-0 shadow-sm" style="background: linear-gradient(135deg, #FFF5ED 0%, #FFE4E1 50%, #FFD3C4 100%);"></div>
                <?php endif; ?>
                <div class="relative z-10 transition-all duration-300 <?php echo e($isActive ? 'scale-105' : 'group-hover:scale-110'); ?>">
                    <?php if($item['icon'] === 'home'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($isActive ? '#FF4B2B' : 'currentColor'); ?>" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="<?php echo e($isActive ? '' : 'text-gray-400 dark:text-gray-500'); ?>"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    <?php elseif($item['icon'] === 'reels'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($isActive ? '#FF4B2B' : 'currentColor'); ?>" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="<?php echo e($isActive ? '' : 'text-gray-400 dark:text-gray-500'); ?>"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M7 3v18"></path><path d="M3 7.5h4"></path><path d="M3 12h18"></path><path d="M3 16.5h4"></path><path d="M17 3v18"></path><path d="M17 7.5h4"></path><path d="M17 16.5h4"></path></svg>
                    <?php endif; ?>

                    <?php if($item['badge']): ?>
                        <span class="absolute select-none pointer-events-none text-[10px] -top-2.5 -right-2"><?php echo e($item['badge']); ?></span>
                    <?php endif; ?>
                </div>
                <span class="relative z-10 text-[11px] font-black mt-1 transition-colors duration-300 <?php echo e($isActive ? 'text-[#FF4B2B]' : 'text-gray-400 dark:text-gray-500'); ?> tracking-tight"><?php echo e($item['label']); ?></span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Center Action Button (Floating Squircle Design) -->
        <div class="px-1 flex flex-col items-center justify-end h-full relative min-w-[72px] pb-[4px]">
            <?php if(auth()->guard()->check()): ?>
                <?php if(Auth::user()->isCreator() && Auth::user()->channel): ?>
                    <div class="absolute -top-[24px] left-1/2 -translate-x-1/2 z-[110]">
                        <button onclick="toggleMobileUploadPopup(event)" type="button" class="block w-[54px] h-[54px] rounded-[20px] p-[2.5px] shadow-lg shadow-orange-500/20 active:scale-95 transition-all gradient-orange">
                            <div class="w-full h-full bg-white rounded-[18px] flex items-center justify-center">
                                <span id="mobile_upload_icon_global" class="text-gray-900 transition-transform duration-500 flex items-center justify-center" style="visibility:visible !important;"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg></span>
                            </div>
                        </button>
                    </div>
                    <span class="text-[10px] font-bold text-gray-900 dark:text-gray-100 z-10 mt-auto">Create</span>
                <?php elseif(Auth::user()->isCreator() && !Auth::user()->channel): ?>
                    <div class="absolute -top-[24px] left-1/2 -translate-x-1/2 z-[110]">
                        <a href="<?php echo e(route('channels.create')); ?>" class="block w-[54px] h-[54px] rounded-[20px] p-[2.5px] shadow-lg shadow-orange-500/20 active:scale-95 transition-all gradient-orange">
                            <div class="w-full h-full bg-white rounded-[18px] flex items-center justify-center">
                                <span class="material-symbols-rounded text-[30px] font-medium text-gray-900">add_circle</span>
                            </div>
                        </a>
                    </div>
                    <span class="text-[10px] font-bold text-gray-900 dark:text-gray-100 z-10 mt-auto">Channel</span>
                <?php else: ?>
                    <div class="absolute -top-[24px] left-1/2 -translate-x-1/2 z-[110]">
                        <a href="<?php echo e(route('channels.create')); ?>" class="block w-[54px] h-[54px] rounded-[20px] p-[2.5px] shadow-lg shadow-orange-500/20 active:scale-95 transition-all gradient-orange">
                            <div class="w-full h-full bg-white rounded-[18px] flex items-center justify-center overflow-hidden p-2">
                                <img src="<?php echo e(asset('assets/images/logo_icon/be-creator-logo.png')); ?>" width="128" height="128" decoding="async" fetchpriority="high" class="w-full h-full object-contain">
                            </div>
                        </a>
                    </div>
                    <span class="text-[10px] font-bold text-gray-900 dark:text-gray-100 z-10 mt-auto whitespace-nowrap">Be a Creator</span>
                <?php endif; ?>
            <?php else: ?>
                <div class="absolute -top-[22px] left-1/2 -translate-x-1/2 z-[110]">
                    <button onclick="window.showLoginAlert('upload videos')" type="button" class="block w-[54px] h-[54px] rounded-[20px] p-[2.5px] shadow-lg shadow-orange-500/20 active:scale-95 transition-all gradient-orange">
                        <div class="w-full h-full bg-white rounded-[18px] flex items-center justify-center">
                            <span class="material-symbols-rounded text-[32px] font-medium text-gray-900">add</span>
                        </div>
                    </button>
                </div>
                <span class="text-[10px] font-bold text-gray-900 dark:text-gray-100 z-10 mt-auto">Upload</span>
            <?php endif; ?>
        </div>

        <!-- Last 2 items -->
        <?php $__currentLoopData = array_slice($navItems, 2, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php 
                $isActive = request()->routeIs($item['pattern']);
                $isMiniOttComingSoon = $item['label'] === 'Mini OTT' && gs('mini_ott_status') !== null && (int) gs('mini_ott_status') === 0;
            ?>
            <a href="<?php echo e($item['url']); ?>" <?php if($isMiniOttComingSoon): ?> onclick="event.preventDefault(); window.showMiniOttComingSoon(); if(navigator.vibrate) navigator.vibrate(20);" <?php endif; ?> class="relative flex flex-col items-center justify-center flex-1 h-full min-w-[40px] group transition-all duration-300 cursor-pointer">
                <?php if($isActive): ?>
                    <div class="absolute inset-y-1.5 inset-x-0.5 border border-orange-200/60 rounded-[1.5rem] z-0 shadow-sm" style="background: linear-gradient(135deg, #FFF5ED 0%, #FFE4E1 50%, #FFD3C4 100%);"></div>
                <?php endif; ?>
                <div class="relative z-10 transition-all duration-300 <?php echo e($isActive ? 'scale-105' : 'group-hover:scale-110'); ?>">
                    <?php if($item['icon'] === 'crown'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($isActive ? '#FF4B2B' : 'currentColor'); ?>" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="<?php echo e($isActive ? '' : 'text-gray-400 dark:text-gray-500'); ?>"><path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7Z"/></svg>
                    <?php elseif($item['icon'] === 'play'): ?>
                        
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($isActive ? '#FF4B2B' : 'currentColor'); ?>" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" class="<?php echo e($isActive ? '' : 'text-gray-400 dark:text-gray-500'); ?>">
                            
                            <path d="m8 3 4 4 4-4"/>
                            
                            <rect width="20" height="14" x="2" y="7" rx="4"/>
                            
                            <polygon points="10 11 15 14 10 17 10 11" fill="<?php echo e($isActive ? '#FF4B2B' : 'currentColor'); ?>" stroke="none"/>
                        </svg>
                    <?php elseif($item['icon'] === 'bag'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="<?php echo e($isActive ? '#FF4B2B' : 'currentColor'); ?>" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="<?php echo e($isActive ? '' : 'text-gray-400 dark:text-gray-500'); ?>"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <?php endif; ?>

                    <?php $miniOttBn = gs('mini_ott_status'); ?>
                    <?php if($item['label'] === 'Mini OTT' && $miniOttBn !== null && (int) $miniOttBn === 0): ?>
                        <span class="absolute -top-1.5 -right-3 px-1 py-0.5 rounded-full bg-[#ff571a] text-white text-[7px] font-black uppercase tracking-widest leading-none">Soon</span>
                    <?php elseif($item['badge']): ?>
                        <span class="absolute select-none pointer-events-none text-[10px] -top-2.5 -right-2"><?php echo e($item['badge']); ?></span>
                    <?php endif; ?>
                </div>
                <span class="relative z-10 text-[11px] font-black mt-1 transition-colors duration-300 <?php echo e($isActive ? 'text-[#FF4B2B]' : 'text-gray-400 dark:text-gray-500'); ?> tracking-tight whitespace-nowrap">
                    <?php echo e($item['label']); ?>

                    <?php $miniOttBn2 = gs('mini_ott_status'); ?>
                    <?php if($item['label'] === 'Mini OTT' && $miniOttBn2 !== null && (int) $miniOttBn2 === 0): ?>
                        <span class="hidden sm:inline-flex px-1 py-0.5 rounded-full bg-[#ff571a] text-white text-[7px] font-black uppercase ml-1">Soon</span>
                    <?php endif; ?>
                </span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<!-- FLOATING POPUP (OUTSIDE NAV BAR TO PREVENT CLIPPING) - Only for Creators -->
<?php if(auth()->guard()->check()): ?>
<?php if(Auth::user()->isCreator() && Auth::user()->channel): ?>
<div id="mobile_upload_popup_global" 
     style="display: none; position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%) translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 9999;"
     class="w-[230px] bg-white dark:bg-[#212121] rounded-[24px] shadow-[0_10px_50px_rgba(0,0,0,0.2)] dark:shadow-[0_10px_50px_rgba(0,0,0,0.5)] overflow-hidden">
     
    <!-- Header -->
    <div class="px-5 py-3 flex items-center justify-between border-b border-gray-50 dark:border-white/5">
        <h3 class="text-[15px] font-bold text-gray-900 dark:text-white">Create</h3>
        <button onclick="toggleMobileUploadPopup(event)" class="w-7 h-7 flex items-center justify-center rounded-full bg-gradient-to-tr from-orange-500 to-[#ff571a] shadow-md shadow-orange-500/20 hover:scale-105 active:scale-95 transition-transform">
            <span class="material-symbols-rounded text-[18px] text-white font-bold">close</span>
        </button>
    </div>

    <!-- Options -->
    <div class="py-2">
        <a href="<?php echo e(route('reels.create')); ?>" class="flex items-center gap-4 px-5 py-2.5 hover:bg-gray-50 dark:hover:bg-white/5 active:bg-gray-100 dark:active:bg-white/10 transition-colors">
            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-purple-600 to-fuchsia-500 flex items-center justify-center shadow-md shadow-purple-500/20">
                <span class="material-symbols-rounded text-[20px] text-white font-light">slow_motion_video</span>
            </div>
            <span class="text-[15px] font-medium text-gray-900 dark:text-white tracking-tight">Create a Reel</span>
        </a>
        
        <a href="<?php echo e(route('videos.create')); ?>" class="flex items-center gap-4 px-5 py-2.5 hover:bg-gray-50 dark:hover:bg-white/5 active:bg-gray-100 dark:active:bg-white/10 transition-colors">
            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-red-500 to-rose-600 flex items-center justify-center shadow-md shadow-red-500/20">
                <span class="material-symbols-rounded text-[20px] text-white font-light">upload</span>
            </div>
            <span class="text-[15px] font-medium text-gray-900 dark:text-white tracking-tight">Upload a video</span>
        </a>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<script>
    // Create-popup toggle: locked during the 300ms open/close animation so
    // rapid taps can't flicker it. Plain popup links are covered by the
    // single global nav guard in layouts/app.blade.php.
    window.__mobileUploadToggleAt = 0;

    function toggleMobileUploadPopup(event) {
        if (event) event.stopPropagation();
        const now = Date.now();
        if (now - window.__mobileUploadToggleAt < 300) return;
        window.__mobileUploadToggleAt = now;
        const popup = document.getElementById('mobile_upload_popup_global');
        const icon = document.getElementById('mobile_upload_icon_global');
        
        if (!popup) return;

        if (popup.style.display === 'none') {
            // Show
            popup.style.display = 'block';
            setTimeout(() => {
                popup.style.opacity = '1';
                popup.style.transform = 'translateX(-50%) translateY(0)';
                if (icon) icon.style.transform = 'rotate(135deg)';
            }, 10);
        } else {
            // Hide
            popup.style.opacity = '0';
            popup.style.transform = 'translateX(-50%) translateY(20px)';
            if (icon) icon.style.transform = 'rotate(0deg)';
            setTimeout(() => {
                popup.style.display = 'none';
            }, 300);
        }
    }

    // Global click listener to close popup (outside click hides directly
    // so it is not blocked by the 300ms toggle lock).
    document.addEventListener('click', function(e) {
        const popup = document.getElementById('mobile_upload_popup_global');
        if (!popup || popup.style.display !== 'block' || popup.contains(e.target)) return;
        // Ignore taps on the Create toggle button itself.
        const t = e.target.closest ? e.target.closest('button') : null;
        if (t && t.getAttribute('onclick') && t.getAttribute('onclick').indexOf('toggleMobileUploadPopup') !== -1) return;
        const icon = document.getElementById('mobile_upload_icon_global');
        popup.style.opacity = '0';
        popup.style.transform = 'translateX(-50%) translateY(20px)';
        if (icon) icon.style.transform = 'rotate(0deg)';
        setTimeout(() => { popup.style.display = 'none'; }, 300);
    });
</script>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\layouts\bottom-nav.blade.php ENDPATH**/ ?>