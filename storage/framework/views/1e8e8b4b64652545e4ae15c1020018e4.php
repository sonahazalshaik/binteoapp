<?php $__env->startSection('title', $pageTitle ?? 'Onboarding Slides'); ?>
<?php $__env->startSection('header_title', $pageTitle ?? 'Onboarding Slides'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $query = \App\Models\OnboardingSlide::query();
    if (request('sort') === 'id_desc') {
        $query->orderBy('id', 'desc');
    } elseif (request('sort') === 'id_asc') {
        $query->orderBy('id', 'asc');
    } else {
        $query->orderBy('sort_order', 'asc');
    }
    $slidesList = $onboardingSlides ?? $query->get();
?>
<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        <?php echo $__env->make('admin.components.header-toolbar', [
            'title' => 'Onboarding Slides',
            'items' => $slidesList,
            'createRoute' => \App\Models\OnboardingSlide::count() < 3 ? route('admin.onboarding-slides.create') : 'javascript:void(0)',
            'createLabel' => \App\Models\OnboardingSlide::count() < 3 ? 'Add New Slide' : 'Limit Exceeded (Max 3)',
            'createDisabled' => \App\Models\OnboardingSlide::count() >= 3,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="px-6 pt-6">
            <?php echo $__env->make('admin.components.table-toolbar', ['showBulkActions' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/[0.02]">
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">Image</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">Title</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">Description</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">Sort Order</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    <?php $__empty_1 = true; $__currentLoopData = $slidesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-4">
                            <?php if($slide->image_path): ?>
                                <img src="<?php echo e($slide->image_url); ?>" alt="<?php echo e($slide->title); ?>" class="w-20 h-20 rounded-xl object-cover bg-slate-100 dark:bg-white/5">
                            <?php else: ?>
                                <div class="w-20 h-20 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400">
                                    <span class="material-symbols-rounded text-2xl">image</span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-sm text-slate-800 dark:text-white"><?php echo e($slide->title); ?></td>
                        <td class="px-6 py-4 text-xs text-slate-500 dark:text-white/50 max-w-xs truncate"><?php echo e($slide->description); ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-500 dark:text-white/50"><?php echo e($slide->sort_order ?? '-'); ?></span>
                                <button type="button" @click="$dispatch('open-sort-modal', { id: '<?php echo e($slide->id); ?>', title: '<?php echo e(addslashes($slide->title)); ?>', desc: '<?php echo e(addslashes($slide->description)); ?>', sort: '<?php echo e($slide->sort_order ?? 0); ?>' })" class="text-rose-500 hover:text-rose-600 transition-colors" title="Change Sort Order">
                                    <span class="material-symbols-rounded text-[14px]">edit</span>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php echo $__env->make('admin.onboarding-slides.actions', ['row' => $slide], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <span class="material-symbols-rounded text-4xl text-slate-300 dark:text-white/10">slideshow</span>
                                <p class="text-sm font-bold text-slate-400 dark:text-white/30">No onboarding slides yet.</p>
                                <?php if(\App\Models\OnboardingSlide::count() < 3): ?>
                                <a href="<?php echo e(route('admin.onboarding-slides.create')); ?>" class="px-6 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl text-[10px] font-black uppercase tracking-widest">Create First Slide</a>
                                <?php else: ?>
                                <p class="text-xs font-bold text-slate-400 dark:text-white/30">Maximum limit of 3 slides reached. Delete one to add another.</p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Global Alpine Modal for Sort Update -->
<div x-data="{ sortModalOpen: false, currentId: null, currentTitle: '', currentDesc: '', currentSort: 0 }" 
     @open-sort-modal.window="sortModalOpen = true; currentId = $event.detail.id; currentTitle = $event.detail.title; currentDesc = $event.detail.desc; currentSort = $event.detail.sort"
     x-show="sortModalOpen" x-transition.opacity x-cloak 
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
    <div @click.away="sortModalOpen = false" class="bg-white dark:bg-[#1a1a1a] p-6 rounded-2xl shadow-2xl w-80 border border-slate-200 dark:border-white/10">
        <h3 class="text-lg font-black tracking-tighter text-slate-900 dark:text-white mb-4">Update Sort Order</h3>
        <form :action="'<?php echo e(route('admin.onboarding-slides.index')); ?>/' + currentId" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="hidden" name="title" :value="currentTitle">
            <input type="hidden" name="description" :value="currentDesc">
            <input type="number" name="sort_order" x-model="currentSort" class="w-full h-10 px-3 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:border-rose-500 focus:ring-1 focus:ring-rose-500 outline-none mb-4">
            <div class="flex justify-end gap-2">
                <button type="button" @click="sortModalOpen = false" class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 dark:hover:text-white transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-rose-500 text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-rose-600 transition-colors">Update</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\onboarding-slides\index.blade.php ENDPATH**/ ?>