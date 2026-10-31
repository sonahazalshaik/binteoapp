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
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Sold Playlists</h1>
                    <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Revenue from your playlist sales</p>
                </div>
                <div class="flex items-center gap-4">
                     <form action="" method="GET" class="relative group">
                        <input type="text" name="search" value="<?php echo e(request()->search); ?>" placeholder="Search sales..." class="pl-12 pr-6 py-3.5 bg-white dark:bg-[#1A1A1A] border border-gray-100 dark:border-white/5 rounded-2xl text-xs font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none w-64 transition-all">
                        <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-red-500 transition-colors">search</span>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="hidden md:block">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 dark:bg-white/2 border-b border-gray-100 dark:border-white/5">
                            <tr>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Playlist</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Buyer</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Transacted</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">TRX ID</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            <?php $__empty_1 = true; $__currentLoopData = $sellPlaylists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-white/2 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-red-500/10 flex items-center justify-center">
                                            <span class="material-symbols-rounded text-red-600">playlist_play</span>
                                        </div>
                                        <span class="font-black text-sm text-gray-900 dark:text-white"><?php echo e(__($sell->playlist->title)); ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center">
                                            <span class="text-[8px] font-black text-gray-500"><?php echo e(substr($sell->user->username ?? 'U', 0, 1)); ?></span>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300"><?php echo e($sell->user->username ?? 'Anonymous'); ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-bold text-gray-700 dark:text-gray-300"><?php echo e(showDateTime($sell->created_at, 'd M Y')); ?></div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase mt-0.5"><?php echo e(diffForHumans($sell->created_at)); ?></div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="font-bold text-xs text-gray-500 dark:text-white/40"><?php echo e($sell->trx); ?></span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span class="font-black text-sm text-emerald-600">+<?php echo e(showAmount($sell->amount)); ?></span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <span class="material-symbols-rounded text-6xl text-gray-200 dark:text-white/5 mb-6">point_of_sale</span>
                                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No playlist sales yet</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-gray-50 dark:divide-white/5">
                    <?php $__empty_1 = true; $__currentLoopData = $sellPlaylists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between items-start">
                            <div class="min-w-0">
                                <h4 class="font-black text-gray-900 dark:text-white text-sm line-clamp-1"><?php echo e(__($sell->playlist->title)); ?></h4>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">Buyer: <?php echo e($sell->user->username ?? 'Anonymous'); ?></p>
                            </div>
                            <span class="font-black text-sm text-emerald-600">+<?php echo e(showAmount($sell->amount)); ?></span>
                        </div>
                        <div class="flex justify-between items-center text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                            <span><?php echo e($sell->trx); ?></span>
                            <span><?php echo e(diffForHumans($sell->created_at)); ?></span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-16 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No sales yet</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($sellPlaylists->hasPages()): ?>
            <div class="mt-12">
                <?php echo e($sellPlaylists->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\client\playlists\sell.blade.php ENDPATH**/ ?>