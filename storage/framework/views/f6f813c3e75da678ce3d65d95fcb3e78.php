

<?php $__env->startSection('title', 'Inquiry Details'); ?>
<?php $__env->startSection('header_title', 'View Inquiry'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-700">
    <div class="flex items-center justify-between px-4 lg:px-0">
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('admin.marketplace.contacts.index')); ?>" class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-colors shadow-sm">
                <span class="material-symbols-rounded">arrow_back</span>
            </a>
            <div>
                <h3 class="text-3xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Inquiry Details</h3>
                <p class="text-[10px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] mt-3">Viewing contact inquiry #<?php echo e($contact->id); ?></p>
            </div>
        </div>
    </div>

    <!-- Inquiry Details Card -->
    <div class="relative bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[3.5rem] p-12 shadow-2xl overflow-hidden group ring-1 ring-white/5">
        <div class="absolute -top-40 -right-40 w-[40rem] h-[40rem] bg-orange-500/5 rounded-full blur-[150px] pointer-events-none group-hover:bg-orange-500/10 transition-all duration-1000"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row items-start gap-12">
            <div class="w-24 h-24 rounded-3xl bg-orange-500/10 flex items-center justify-center text-orange-500 shrink-0">
                <span class="material-symbols-rounded text-5xl">contact_support</span>
            </div>

            <div class="flex-grow space-y-10">
                <div>
                    <h2 class="text-4xl lg:text-5xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none mb-6"><?php echo e($contact->subject); ?></h2>
                     <div class="flex flex-wrap items-center gap-8">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <p class="text-[14px] font-black text-slate-600 dark:text-white uppercase tracking-widest"><?php echo e($contact->name); ?></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-rounded text-slate-400 text-lg">alternate_email</span>
                            <p class="text-[12px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest "><?php echo e($contact->email); ?></p>
                        </div>
                        <?php if($contact->phone): ?>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-rounded text-slate-400 text-lg">call</span>
                            <p class="text-[12px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest "><?php echo e($contact->phone); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="p-10 rounded-[3rem] bg-slate-50 dark:bg-white/[0.01] border border-slate-100 dark:border-white/5 relative">
                    <span class="material-symbols-rounded absolute -top-4 -left-4 text-6xl text-slate-200 dark:text-white/5">format_quote</span>
                    <p class="text-xl font-bold text-slate-600 dark:text-white/50 leading-relaxed relative z-10">
                        <?php echo e($contact->message); ?>

                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-10 pt-6">
                    <div class="flex flex-col gap-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Target Talent</span>
                        <a href="<?php echo e(route('admin.marketplace.show', $contact->marketplace_id)); ?>" class="flex items-center gap-3 group/talent">
                             <div class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></div>
                             <span class="text-[15px] font-black text-slate-900 dark:text-white uppercase tracking-tighter group-hover/talent:text-orange-500 transition-colors"><?php echo e(@$contact->marketplace->name ?? 'Unassigned'); ?></span>
                        </a>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Sent Date</span>
                        <span class="text-[13px] font-black text-slate-600 dark:text-white/60 uppercase tracking-tighter"><?php echo e($contact->created_at->format('M d, Y - H:i')); ?></span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4 pt-4">
                    <a href="<?php echo e(route('admin.marketplace.contacts.edit', $contact->id)); ?>" class="h-14 px-10 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-black flex items-center gap-3 text-[11px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-xl active:scale-95 ">
                        <span class="material-symbols-rounded text-2xl">edit_note</span>
                        Edit Inquiry
                    </a>
                    <form action="<?php echo e(route('admin.marketplace.contacts.destroy', $contact->id)); ?>" method="POST" data-swal-question="Immediately delete this inquiry?">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="h-14 px-10 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-500 backdrop-blur-md flex items-center gap-3 text-[11px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all shadow-xl active:scale-95 ">
                            <span class="material-symbols-rounded text-2xl">delete_forever</span>
                            Delete Inquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\marketplace\contact\show.blade.php ENDPATH**/ ?>