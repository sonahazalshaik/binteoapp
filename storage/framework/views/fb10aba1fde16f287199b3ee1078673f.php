<div class="space-y-4">
	<?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<div class="bg-slate-50 dark:bg-white/[0.02] p-5 rounded-2xl border border-slate-100 dark:border-white/5">
			<?php if(is_object($val) || is_array($val)): ?>
				<h6 class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-4"><?php echo e(keyToTitle($k)); ?></h6>
				<div class="pl-4 border-l-2 border-slate-200 dark:border-white/10 space-y-4">
					<?php echo $__env->make('admin.deposit.gateway_data',['details'=>$val], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
				</div>
			<?php else: ?>
				<span class="block text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.2em] mb-2"><?php echo e(keyToTitle($k)); ?></span>
				<p class="text-[13px] font-bold text-slate-700 dark:text-white/80 leading-relaxed"><?php echo e(@$val); ?></p>
			<?php endif; ?>
		</div>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\deposit\gateway_data.blade.php ENDPATH**/ ?>