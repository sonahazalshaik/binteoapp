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
    <div class="py-8 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between mb-10 gap-6">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Deposit Ledger</h1>
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        History of all added funds
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('user.deposit')); ?>" class="px-8 py-4 bg-red-600 text-white rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-2xl shadow-red-500/30 hover:bg-red-700 hover:-translate-y-1 transition-all active:scale-95 flex items-center gap-2">
                        <span class="material-symbols-rounded text-lg">add_card</span>
                        New Deposit
                    </a>
                </div>
            </div>

            <!-- List Section -->
            <div class="bg-white dark:bg-[#181818] rounded-[3rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden transition-all duration-500">
                <div class="px-8 py-6 border-b border-gray-50 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Recent Activity</h2>
                        <form action="" method="GET" class="relative group max-w-sm w-full">
                            <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-red-500 transition-colors">search</span>
                            <input type="text" name="search" value="<?php echo e(request()->search); ?>" placeholder="Search TRX ID..." 
                                   class="w-full bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl pl-12 pr-6 py-2.5 text-xs font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all">
                        </form>
                    </div>
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/10 dark:bg-white/[0.01]">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Gateway</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">TRX ID</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Amount</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            <?php $__empty_1 = true; $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-white/5 flex items-center justify-center">
                                            <span class="material-symbols-rounded text-xl text-gray-400">payments</span>
                                        </div>
                                        <span class="text-sm font-black text-gray-900 dark:text-white"><?php echo e(__($deposit->gateway->name ?? 'Manual')); ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">#<?php echo e($deposit->trx); ?></span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-xs font-bold text-gray-700 dark:text-gray-300"><?php echo e(showDateTime($deposit->created_at, 'd M, Y')); ?></div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase mt-1"><?php echo e(diffForHumans($deposit->created_at)); ?></div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="text-sm font-black text-emerald-600">+ <?php echo e(showAmount($deposit->amount)); ?></div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">+ <?php echo e(showAmount($deposit->charge)); ?> charge</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center">
                                        <?php if($deposit->status == 1): ?>
                                            <span class="px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-600 text-[9px] font-black uppercase tracking-widest">Success</span>
                                        <?php elseif($deposit->status == 2): ?>
                                            <span class="px-4 py-1.5 rounded-full bg-amber-500/10 text-amber-600 text-[9px] font-black uppercase tracking-widest">Pending</span>
                                        <?php elseif($deposit->status == 3): ?>
                                            <span class="px-4 py-1.5 rounded-full bg-red-500/10 text-red-600 text-[9px] font-black uppercase tracking-widest">Rejected</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <span class="material-symbols-rounded text-6xl text-gray-200 dark:text-white/5 mb-6">credit_card_off</span>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No deposit history found</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="md:hidden divide-y divide-gray-50 dark:divide-white/5">
                    <?php $__empty_1 = true; $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-white/5 flex items-center justify-center">
                                    <span class="material-symbols-rounded text-xl text-gray-400">payments</span>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 dark:text-white text-sm"><?php echo e(__($deposit->gateway->name ?? 'Manual')); ?></h4>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">#<?php echo e($deposit->trx); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-black text-emerald-600">+<?php echo e(showAmount($deposit->amount)); ?></span>
                                <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5"><?php echo e(showDateTime($deposit->created_at, 'd M, Y')); ?></p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center bg-gray-50/50 dark:bg-white/2 p-3 rounded-xl border border-gray-100 dark:border-white/5">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Status:</span>
                            <?php if($deposit->status == 1): ?>
                                <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Success</span>
                            <?php elseif($deposit->status == 2): ?>
                                <span class="text-[9px] font-black text-amber-500 uppercase tracking-widest">Pending</span>
                            <?php else: ?>
                                <span class="text-[9px] font-black text-red-500 uppercase tracking-widest">Rejected</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-16 text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No results found</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($deposits->hasPages()): ?>
                <div class="mt-12">
                    <?php echo e($deposits->links()); ?>

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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\client\deposit\history.blade.php ENDPATH**/ ?>