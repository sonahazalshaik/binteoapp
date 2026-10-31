

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-slate-50 dark:bg-[#0A0A0A] py-20 px-6 transition-colors duration-500">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white tracking-tight"><?php echo e($pageTitle); ?></h1>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-2">Choose a secure payment provider to complete your transaction</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $gatewayCurrency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white dark:bg-[#151515] border border-slate-100 dark:border-white/5 p-10 rounded-[3rem] shadow-sm hover:shadow-2xl transition-all group">
                <div class="text-center">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-red-50 dark:group-hover:bg-red-600/10 transition-all">
                        <span class="material-symbols-rounded text-3xl text-slate-400 group-hover:text-red-600">account_balance_wallet</span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight mb-2"><?php echo e(__($data->name)); ?></h3>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-8">Secured Gateway</p>
                    
                    <div class="py-6 px-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5 mb-10">
                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Transaction Limits</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200"><?php echo e(showAmount($data->min_amount)); ?> - <?php echo e(showAmount($data->max_amount)); ?> <?php echo e(__($data->currency)); ?></p>
                    </div>

                    <form action="<?php echo e(route('user.deposit.insert')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="gateway" value="<?php echo e($data->method_code); ?>">
                        <input type="hidden" name="currency" value="<?php echo e($data->currency); ?>">
                        <input type="hidden" name="plan_id" value="<?php echo e(request()->plan_id); ?>">
                        <input type="hidden" name="amount" value="<?php echo e(request()->amount); ?>">
                        
                        <button type="submit" class="w-full py-4 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white rounded-2xl font-bold text-sm hover:bg-red-600 hover:text-white hover:border-transparent transition-all shadow-sm active:scale-95">
                            Select Provider
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-20 text-center opacity-30">
            <div class="flex items-center justify-center gap-6 text-slate-400">
                <span class="material-symbols-rounded text-4xl">verified_user</span>
                <span class="material-symbols-rounded text-4xl">lock</span>
                <span class="material-symbols-rounded text-4xl">shield</span>
            </div>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] mt-6">Secure and Encrypted Payment Standards</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\payment\deposit.blade.php ENDPATH**/ ?>