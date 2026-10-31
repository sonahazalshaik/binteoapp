<footer class="relative hidden lg:block border-t border-gray-100 dark:border-white/5 bg-white dark:bg-[#0F0F0F] py-12 transition-colors duration-500 <?php echo e(request()->routeIs('reels.index') || request()->routeIs('marketplace.dashboard') || request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('password.*') ? '' : 'lg:ml-72'); ?>">

    <div class="relative max-w-7xl mx-auto px-6 z-10">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 gradient-orange rounded-xl p-[1.5px] shadow-xl overflow-hidden">
                    <div class="w-full h-full bg-white rounded-[11px] flex items-center justify-center p-0 relative">
                        <img src="<?php echo e(siteLogo()); ?>" width="128" height="128" decoding="async" class="w-full h-full object-contain relative z-[1] scale-110" alt="<?php echo e(gs('site_name')); ?>" loading="eager">
                    </div>
                </div>
                <span class="text-2xl font-black text-[#2D2D2D] dark:text-white leading-none tracking-tight uppercase"><?php echo e(gs('site_name')); ?></span>
            </div>

            <div class="text-[11px] font-black text-gray-500 dark:text-white/60 uppercase tracking-[0.4em]">
                &copy; <?php echo e(date('Y')); ?> <?php echo e(gs('site_name')); ?>. Native OTT Experience.
            </div>

            <div class="flex space-x-8 text-[11px] font-black uppercase tracking-widest text-gray-500 dark:text-white/60">
                <a href="<?php echo e(route('marketplace.terms')); ?>" class="hover:text-gray-900 dark:hover:text-white transition-all">Marketplace Terms</a>
                <?php
                    $footerPolicies = getContent('policy_pages.element')->filter(function($p) {
                        $title = strtolower($p->data_values->title);
                        return str_contains($title, 'terms') || str_contains($title, 'privacy');
                    });
                ?>
                <?php $__currentLoopData = $footerPolicies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $policy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('policy.pages', [$policy->id, slug($policy->data_values->title)])); ?>" class="hover:text-gray-900 dark:hover:text-white transition-all">
                        <?php echo e(__($policy->data_values->title)); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\layouts\footer.blade.php ENDPATH**/ ?>