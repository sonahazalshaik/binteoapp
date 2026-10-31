<?php $__env->startSection('title', 'Add New Rating'); ?>
<?php $__env->startSection('header_title', 'Add New Rating'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-white/10 flex justify-between items-center bg-slate-50 dark:bg-white/5">
            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-wider">Add New Rating</h3>
            <a href="<?php echo e(route('admin.marketplace.ratings.index')); ?>" class="text-slate-400 hover:text-indigo-500 transition-colors flex items-center gap-1 text-[10px] font-black uppercase tracking-widest">
                <span class="material-symbols-rounded text-sm">arrow_back</span>
                Back
            </a>
        </div>
        <form action="<?php echo e(route('admin.marketplace.ratings.store')); ?>" method="POST" class="p-8">
            <?php echo csrf_field(); ?>
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Select Talent (Reviewed)</label>
                    <select name="marketplace_id" required class="w-full bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">-- Choose Talent --</option>
                        <?php $__currentLoopData = $talents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $talent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($talent->id); ?>" <?php echo e(old('marketplace_id') == $talent->id ? 'selected' : ''); ?>><?php echo e($talent->name); ?> (<?php echo e(ucfirst($talent->type)); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Select User (Reviewer)</label>
                    <select name="user_id" class="w-full bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">-- Choose Reviewer / User (Optional) --</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>><?php echo e($user->username); ?> (<?php echo e($user->email); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Rating (1-5 Stars)</label>
                    <input type="number" name="rating" min="1" max="5" step="0.5" value="<?php echo e(old('rating', 5)); ?>" required class="w-full bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="e.g. 5">
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-4 border-t border-slate-100 dark:border-white/10 pt-6">
                <button type="submit" class="px-8 py-3.5 rounded-xl text-[11px] font-black uppercase tracking-widest text-white bg-indigo-500 hover:bg-indigo-600 shadow-lg shadow-indigo-500/30 transition-all active:scale-95">Save Rating</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\marketplace\ratings_create.blade.php ENDPATH**/ ?>