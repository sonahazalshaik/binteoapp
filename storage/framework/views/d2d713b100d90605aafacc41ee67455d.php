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
    <div class="py-20 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[1000px] mx-auto px-6">
            <div class="text-center mb-16">
                 <div class="w-20 h-20 rounded-[2rem] bg-red-500/10 flex items-center justify-center mx-auto mb-8">
                    <span class="material-symbols-rounded text-4xl text-red-600">cookie</span>
                </div>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-4"><?php echo e(__($pageTitle)); ?></h1>
                <p class="text-gray-500 dark:text-gray-400 font-bold text-xs uppercase tracking-[0.2em]">Privacy & Transparency Policy</p>
            </div>

            <div class="bg-white dark:bg-[#1A1A1A] rounded-[3rem] p-12 border border-gray-100 dark:border-white/5 shadow-sm text-gray-700 dark:text-gray-300 leading-[1.8] font-medium article-content">
                <?php
                    echo $cookie->data_values->description;
                ?>
            </div>
            
            <div class="mt-12 text-center">
                <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">
                    <span class="material-symbols-rounded text-lg">arrow_back</span>
                    Return to Home
                </a>
            </div>
        </div>
    </div>
    
    <?php $__env->startPush('style'); ?>
    <style>
        .article-content h1, .article-content h2, .article-content h3 {
            color: #111;
            font-weight: 900;
            margin-top: 2.5rem;
            margin-bottom: 1.25rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .dark .article-content h1, .dark .article-content h2, .dark .article-content h3 {
            color: #fff;
        }
        .article-content p {
            margin-bottom: 1.5rem;
        }
    </style>
    <?php $__env->stopPush(); ?>
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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\cookie.blade.php ENDPATH**/ ?>