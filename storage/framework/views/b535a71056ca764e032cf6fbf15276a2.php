<div class="row">
    <div class="col-md-12">
        <div class="card overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive table-responsive--sm">
                    <table class="table align-items-center table--light">
                        <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Short Code'); ?></th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Description'); ?></th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            <?php $__currentLoopData = $template->shortcodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shortcode => $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><span class="short-codes"><?php echo "{{". $shortcode ."}}"  ?></span></td>
                                
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(__($key)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = gs('global_shortcodes'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shortCode => $codeDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><span class="short-codes">{{<?php echo $shortCode ?>}}</span></td>
                                
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(__($codeDetails)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- card end -->

    </div>
</div>

<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\notification\template\shortcodes.blade.php ENDPATH**/ ?>