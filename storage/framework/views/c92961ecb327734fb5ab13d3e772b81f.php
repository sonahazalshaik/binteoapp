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
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="mb-12">
                <a href="<?php echo e(route('studio.videos')); ?>" class="inline-flex items-center space-x-2 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Back to Content</span>
                </a>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mt-6">Edit Video Details</h1>
            </div>

            <form action="<?php echo e(route('studio.update', $video)); ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-10 border border-gray-100 dark:border-white/5 shadow-sm space-y-8">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-2">Video Title</label>
                            <input type="text" name="title" value="<?php echo e($video->title); ?>" class="w-full bg-gray-50 dark:bg-white/2 border-gray-100 dark:border-white/5 rounded-[1.5rem] p-4 text-[15px] font-bold focus:ring-red-500 focus:border-red-500 transition-all dark:text-white">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-2">Description</label>
                            <textarea name="description" rows="8" class="w-full bg-gray-50 dark:bg-white/2 border-gray-100 dark:border-white/5 rounded-[1.5rem] p-6 text-[15px] font-medium leading-relaxed focus:ring-red-500 focus:border-red-500 transition-all dark:text-white"><?php echo e($video->description); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Sidebar (Thumbnail & Actions) -->
                <div class="space-y-8">
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-10 border border-gray-100 dark:border-white/5 shadow-sm">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 ml-2 text-center">Thumbnail</label>
                        
                        <div class="relative group aspect-video rounded-3xl overflow-hidden border-2 border-dashed border-gray-100 dark:border-white/10 mb-8" x-data="{ preview: null }">
                            <?php if($video->thumbnail_path): ?>
                                <img src="<?php echo e($video->getThumbnailUrl()); ?>" class="w-full h-full object-cover">


                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer">
                                <svg class="w-8 h-8 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                <span class="text-[10px] font-black text-white uppercase tracking-widest">Change Photo</span>
                            </div>
                            <input type="file" name="thumbnail" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>

                        <div class="space-y-4">
                            <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-red-200 dark:shadow-none hover:bg-red-700 transition-all active:scale-95">Save Changes</button>
                            <a href="<?php echo e(route('studio.videos')); ?>" class="block w-full py-4 bg-gray-50 dark:bg-white/5 text-gray-500 dark:text-gray-400 text-center rounded-2xl font-black text-sm uppercase tracking-widest hover:text-red-500 transition-all">Cancel</a>
                        </div>
                    </div>

                    <div class="bg-red-50 dark:bg-red-500/5 rounded-[2.5rem] p-10 border border-red-100 dark:border-red-500/10 shadow-sm text-center">
                        <h4 class="text-red-600 dark:text-red-500 text-[10px] font-black uppercase tracking-widest mb-4">Danger Zone</h4>
                        <p class="text-xs font-bold text-red-400 dark:text-red-900/40 mb-8 leading-relaxed px-4">Deleting this video is irreversible. All comments and views will be lost.</p>
                        <form action="<?php echo e(route('studio.destroy', $video)); ?>" method="POST" onsubmit="return confirm('Kill this video forever?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-xs font-black text-red-600 dark:text-red-500 uppercase tracking-widest hover:underline decoration-2">Delete Permanently</button>
                        </form>
                    </div>
                </div>
            </form>
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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\client\studio\edit.blade.php ENDPATH**/ ?>