<?php
    $active = false;
    if (Route::has($item['route'])) {
        if (!empty($item['active_patterns'])) {
            $active = request()->routeIs($item['active_patterns']);
        } else {
            $active = request()->routeIs($item['route']);
        }
    }
    $isComingSoon = isset($isComingSoon) && $isComingSoon;
?>

<a href="<?php echo e(Route::has($item['route']) ? route($item['route']) : '#'); ?>" 
   <?php if($isComingSoon): ?> onclick="event.preventDefault(); window.showOttPlansComingSoon(); if(navigator.vibrate) navigator.vibrate(20);" <?php endif; ?>
   class="flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 group relative <?php echo e($active ? 'bg-red-500 text-white shadow-lg shadow-red-500/20 active-menu-item' : 'text-gray-700 dark:text-[#F1F1F1] hover:bg-gray-50 dark:hover:bg-white/5'); ?> <?php echo e($isComingSoon ? 'cursor-pointer' : ''); ?>">
    <span class="material-symbols-rounded text-[22px] <?php echo e($active ? 'text-white' : 'text-gray-400 group-hover:text-red-500'); ?> shrink-0 mr-4">
        <?php echo e($item['icon']); ?>

    </span>
    <div class="flex items-center gap-1.5 min-w-0">
        <span class="font-bold text-[14px] <?php echo e($active ? 'text-white' : ''); ?> whitespace-nowrap"><?php echo e($item['label']); ?></span>
        <?php if($isComingSoon): ?>
            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-[#ff571a] text-white text-[7px] font-black uppercase tracking-wider leading-none shrink-0 whitespace-nowrap">Soon</span>
        <?php endif; ?>
    </div>

    <?php if(!$isComingSoon && $active): ?>
        <div class="ml-auto shrink-0 w-1.5 h-1.5 bg-white rounded-full"></div>
    <?php endif; ?>
</a>

<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\partials\studio_sidebar_item.blade.php ENDPATH**/ ?>