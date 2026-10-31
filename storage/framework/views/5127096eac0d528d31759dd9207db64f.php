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
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Your Reels</h1>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-2">Manage your short-form content</p>
                </div>
                <a href="<?php echo e(route('reels.create')); ?>" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-500 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl hover:shadow-2xl transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-rounded text-lg">add</span> New Reel
                </a>
            </div>

            <?php if($reels->count()): ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-6">
                <?php $__currentLoopData = $reels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white dark:bg-[#1A1A1A] rounded-[2rem] overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-xl transition-all group">
                    <!-- Thumbnail / Preview -->
                    <div class="aspect-[9/16] max-h-[220px] bg-black relative overflow-hidden">
                        <?php if($reel->thumbnail_path): ?>
                            <img src="<?php echo e(asset(getFilePath('reelThumbnail') . '/' . $reel->thumbnail_path)); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-900 to-pink-900">
                                <span class="material-symbols-rounded text-5xl text-white/30">slow_motion_video</span>
                            </div>
                        <?php endif; ?>
                        <div class="absolute bottom-3 right-3 px-2 py-1 bg-black/70 backdrop-blur rounded-lg text-[10px] font-black text-white"><?php echo e(gmdate('i:s', $reel->duration)); ?></div>
                        <div class="absolute top-3 left-3">
                            <?php if($reel->status == 1): ?>
                                <span class="px-2 py-1 bg-green-500 text-white rounded-lg text-[9px] font-black uppercase">Published</span>
                            <?php elseif($reel->status == 0): ?>
                                <span class="px-2 py-1 bg-yellow-500 text-white rounded-lg text-[9px] font-black uppercase">Draft</span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-red-500 text-white rounded-lg text-[9px] font-black uppercase">Rejected</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="p-5">
                        <h3 class="font-black text-sm text-gray-900 dark:text-white line-clamp-1 mb-2"><?php echo e($reel->title); ?></h3>
                        <div class="flex items-center gap-3 text-xs text-gray-400 font-bold mb-4">
                            <span>👁 <?php echo e($reel->views_count); ?></span>
                            <span>❤ <?php echo e($reel->likes_count); ?></span>
                            <span>💬 <?php echo e($reel->comments_count); ?></span>
                        </div>
                        <div class="flex gap-2">
                            <a href="<?php echo e(route('studio.reels.edit', $reel)); ?>" class="flex-1 h-10 bg-blue-50 dark:bg-blue-500/10 text-blue-600 rounded-xl flex items-center justify-center text-xs font-bold gap-1 hover:bg-blue-100 transition-colors">
                                <span class="material-symbols-rounded text-sm">edit</span> Edit
                            </a>
                            <form action="<?php echo e(route('studio.reels.destroy', $reel)); ?>" method="POST" class="flex-1" onsubmit="return confirm('Delete this reel?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="w-full h-10 bg-red-50 dark:bg-red-500/10 text-red-600 rounded-xl flex items-center justify-center text-xs font-bold gap-1 hover:bg-red-100 transition-colors">
                                    <span class="material-symbols-rounded text-sm">delete</span> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($reels->hasPages()): ?>
            <div class="mt-8"><?php echo e($reels->links()); ?></div>
            <?php endif; ?>

            <?php else: ?>
            <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-16 text-center border border-gray-100 dark:border-white/5">
                <span class="material-symbols-rounded text-6xl text-gray-200 dark:text-white/10 mb-4 block">slow_motion_video</span>
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">No Reels Yet</h3>
                <p class="text-gray-400 text-sm mb-6">Create your first short-form video!</p>
                <a href="<?php echo e(route('reels.create')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-500 text-white rounded-xl font-bold text-sm">
                    <span class="material-symbols-rounded">add</span> Upload Reel
                </a>
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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\studio\reels.blade.php ENDPATH**/ ?>