<!-- Tab: Gallery -->
<div x-show="tab === 'gallery'" x-cloak x-transition class="space-y-8">
    <div class="bg-white dark:bg-[#111] p-6 md:p-12 rounded-[2rem] md:rounded-[3rem] border border-slate-200 dark:border-white/5 shadow-sm">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter">Portfolio Gallery</h2>
                <p class="text-slate-500 font-medium">Showcase your best work to potential clients.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-10">
            <?php $__currentLoopData = $client->galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative aspect-square rounded-[2rem] overflow-hidden group">
                <img src="<?php echo e(getImage($gallery->market_gallery)); ?>" class="w-full h-full object-cover">

                <div class="absolute inset-0 bg-black/30 flex items-center justify-center transition-all gap-2">
                    <form action="<?php echo e(route('marketplace.gallery.update', $gallery->id)); ?>" method="POST" enctype="multipart/form-data" class="relative">
                        <?php echo csrf_field(); ?>
                        <input type="file" name="market_gallery" class="absolute inset-0 opacity-0 cursor-pointer" onchange="uploadPortfolioImage(this)">
                        <button type="button" class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center">
                            <span class="material-symbols-rounded text-lg">edit</span>
                        </button>
                    </form>
                    <form action="<?php echo e(route('marketplace.gallery.delete', $gallery->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-10 h-10 rounded-full bg-rose-600 text-white flex items-center justify-center hover:scale-110 transition-transform">
                            <span class="material-symbols-rounded text-lg">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <form action="<?php echo e(route('marketplace.gallery.store')); ?>" method="POST" enctype="multipart/form-data" class="relative aspect-square border-2 border-dashed border-slate-200 dark:border-white/10 rounded-[2rem] flex flex-col items-center justify-center gap-2 hover:border-red-600 transition-all group">
                <?php echo csrf_field(); ?>
                <input type="file" name="market_gallery" class="absolute inset-0 opacity-0 cursor-pointer" required onchange="uploadPortfolioImage(this)">
                <span class="material-symbols-rounded text-3xl text-slate-400 group-hover:text-red-600 transition-colors">add_photo_alternate</span>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Add Photo</span>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\marketplace\sections\gallery.blade.php ENDPATH**/ ?>