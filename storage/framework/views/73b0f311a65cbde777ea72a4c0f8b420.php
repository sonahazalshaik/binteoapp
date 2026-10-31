

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <?php if(request()->routeIs('admin.withdraw.data.all') || request()->routeIs('admin.withdraw.method') || request()->routeIs('admin.users.withdrawals') || request()->routeIs('admin.users.withdrawals.method')): ?>
            <div class="col-12">
                <?php echo $__env->make('admin.withdraw.widget', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php endif; ?>
        <div class="col-lg-12">
            <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                <?php echo $__env->make('admin.components.header-toolbar', [
                    'title' => 'Withdrawals',
                    'createRoute' => route('admin.withdraw.data.create'),
                    'createLabel' => 'Add Withdrawal'
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="px-6 pt-6">
                    <?php echo $__env->make('admin.components.table-toolbar', ['module' => 'withdrawals', 'exportTotal' => count($withdrawals)], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="overflow-x-auto scrollbar-hide" x-show="!$store.viewMode || $store.viewMode.mode === 'table'">
                    <table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Method | Transaction'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Initiated'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('User'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Amount'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Conversion'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Status'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Action'); ?></th>

                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                <?php $__empty_1 = true; $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdraw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $details = $withdraw->withdraw_information != null ? json_encode($withdraw->withdraw_information) : null;
                                    ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <span class="fw-bold"><a href="<?php echo e(appendQuery('method', @$withdraw->method->id)); ?>"> <?php echo e(__(@$withdraw->method->name)); ?></a></span>
                                            <br>
                                            <small><?php echo e($withdraw->trx); ?></small>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(showDateTime($withdraw->created_at)); ?> <br> <?php echo e(diffForHumans($withdraw->created_at)); ?>

                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <span class="fw-bold"><?php echo e($withdraw->user->fullname); ?></span>
                                            <br>
                                            <span class="small"> <a href="<?php echo e(appendQuery('search', @$withdraw->user->username)); ?>"><span>@</span><?php echo e($withdraw->user->username); ?></a> </span>
                                        </td>


                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(showAmount($withdraw->amount, 0)); ?> - <span class="text--danger" title="<?php echo app('translator')->get('charge'); ?>"><?php echo e(showAmount($withdraw->charge, 0)); ?> </span>
                                            <br>
                                            <strong title="<?php echo app('translator')->get('Amount after charge'); ?>">
                                                <?php echo e(showAmount($withdraw->amount - $withdraw->charge, 0)); ?>

                                            </strong>
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(showAmount(1)); ?> = <?php echo e(showAmount($withdraw->rate, currencyFormat: false)); ?> <?php echo e(__($withdraw->currency)); ?>

                                            <br>
                                            <strong><?php echo e(showAmount($withdraw->final_amount, currencyFormat: false)); ?> <?php echo e(__($withdraw->currency)); ?></strong>
                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo $withdraw->statusBadge ?>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="grid grid-cols-2 gap-2 w-max">
                                                <?php if($withdraw->status == Status::PAYMENT_PENDING): ?>
                                                    <button class="px-3 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center gap-2 transition-all hover:scale-105 shadow-sm" 
                                                            @click="$dispatch('open-withdraw-approve-modal', { id: <?php echo e($withdraw->id); ?>, amount: '<?php echo e(showAmount($withdraw->final_amount, currencyFormat: false)); ?>', currency: '<?php echo e($withdraw->currency); ?>' })">
                                                        <span class="material-symbols-rounded text-sm">check_circle</span>
                                                        <span class="text-[9px] font-black uppercase tracking-widest"><?php echo app('translator')->get('Approve'); ?></span>
                                                    </button>
                                                    <button class="px-3 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center gap-2 transition-all hover:scale-105 shadow-sm" 
                                                            @click="$dispatch('open-withdraw-reject-modal', { id: <?php echo e($withdraw->id); ?>, trx: '<?php echo e($withdraw->trx); ?>' })">
                                                        <span class="material-symbols-rounded text-sm">cancel</span>
                                                        <span class="text-[9px] font-black uppercase tracking-widest"><?php echo app('translator')->get('Reject'); ?></span>
                                                    </button>
                                                <?php endif; ?>
                                                <a href="<?php echo e(route('admin.withdraw.data.details', $withdraw->id)); ?>" class="px-3 h-8 rounded-lg bg-indigo-500 text-white flex items-center justify-center gap-2 transition-all hover:scale-105 shadow-sm <?php echo e($withdraw->status != Status::PAYMENT_PENDING ? 'col-span-2' : ''); ?>">
                                                    <span class="material-symbols-rounded text-sm">visibility</span>
                                                    <span class="text-[9px] font-black uppercase tracking-widest"><?php echo app('translator')->get('Details'); ?></span>
                                                </a>
                                                <button class="px-3 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center gap-2 transition-all hover:scale-105 shadow-sm confirmationBtn <?php echo e($withdraw->status != Status::PAYMENT_PENDING ? 'col-span-2' : ''); ?>" data-action="<?php echo e(Route::has('admin.withdraw.destroy') ? route('admin.withdraw.destroy', $withdraw->id ?? 0) : (Route::has('admin.withdraw.delete') ? route('admin.withdraw.delete', $withdraw->id ?? 0) : 'javascript:void(0)')); ?>" data-question="<?php echo app('translator')->get('Are you sure you want to delete this record?'); ?>">
                                                    <span class="material-symbols-rounded text-sm">delete</span>
                                                    <span class="text-[9px] font-black uppercase tracking-widest"><?php echo app('translator')->get('Delete'); ?></span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="text-muted text-center" colspan="100%"><?php echo e(__($emptyMessage)); ?></td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table><!-- table end -->
                    </div>

                    <!-- App View -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-6" x-show="$store.viewMode && $store.viewMode.mode === 'app'" style="display: none;">
                        <?php $__empty_1 = true; $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdraw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="group bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-3xl p-6 hover:shadow-xl hover:border-primary-500/30 transition-all duration-300">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <span class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest block mb-1">TRX: <?php echo e($withdraw->trx); ?></span>
                                        <div class="text-[14px] font-black text-slate-800 dark:text-white leading-tight">
                                            <?php echo e(__(@$withdraw->method->name)); ?>

                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <?php echo $withdraw->statusBadge; ?>
                                    </div>
                                </div>

                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500">
                                            <i class="las la-user"></i>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest block">User</span>
                                            <a href="<?php echo e(route('admin.users.detail', $withdraw->user->id)); ?>" class="text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(@$withdraw->user->fullname); ?></a>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center bg-slate-50 dark:bg-white/[0.02] rounded-2xl p-3">
                                        <div>
                                            <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest block">Amount</span>
                                            <span class="text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(showAmount($withdraw->amount - $withdraw->charge)); ?></span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[9px] font-black text-slate-400 dark:text-white/30 uppercase tracking-widest block">Initiated</span>
                                            <span class="text-[11px] font-bold text-slate-700 dark:text-white/70"><?php echo e(diffForHumans($withdraw->created_at)); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-slate-100 dark:border-white/5">
                                    <?php if($withdraw->status == Status::PAYMENT_PENDING): ?>
                                        <button class="flex-1 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all shadow-sm group/btn" 
                                                @click="$dispatch('open-withdraw-approve-modal', { id: <?php echo e($withdraw->id); ?>, amount: '<?php echo e(showAmount($withdraw->final_amount, currencyFormat: false)); ?>', currency: '<?php echo e($withdraw->currency); ?>' })">
                                            <span class="material-symbols-rounded text-sm group-hover/btn:scale-110 transition-transform">check_circle</span>
                                            Approve
                                        </button>
                                        <button class="flex-1 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center gap-2 text-[9px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-sm group/btn" 
                                                @click="$dispatch('open-withdraw-reject-modal', { id: <?php echo e($withdraw->id); ?>, trx: '<?php echo e($withdraw->trx); ?>' })">
                                            <span class="material-symbols-rounded text-sm group-hover/btn:scale-110 transition-transform">cancel</span>
                                            Reject
                                        </button>
                                    <?php endif; ?>
                                    
                                    <div class="flex items-center gap-2 w-full">
                                        <a href="<?php echo e(route('admin.withdraw.data.details', $withdraw->id)); ?>" class="flex-grow h-10 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-white text-[9px] font-black uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all flex items-center justify-center gap-2 group/btn">
                                            <span class="material-symbols-rounded text-sm group-hover/btn:translate-x-1 transition-transform">visibility</span>
                                            View Details
                                        </a>
                                        <button class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all confirmationBtn" data-action="<?php echo e(Route::has('admin.withdraw.destroy') ? route('admin.withdraw.destroy', $withdraw->id ?? 0) : (Route::has('admin.withdraw.delete') ? route('admin.withdraw.delete', $withdraw->id ?? 0) : 'javascript:void(0)')); ?>" data-question="<?php echo app('translator')->get('Are you sure you want to delete this record?'); ?>" title="<?php echo app('translator')->get('Delete'); ?>">
                                            <span class="material-symbols-rounded text-sm">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-full flex flex-col items-center justify-center p-12 text-center">
                                <div class="w-24 h-24 bg-slate-100 dark:bg-white/5 rounded-full flex items-center justify-center mb-4">
                                    <i class="las la-hand-holding-usd text-4xl text-slate-400 dark:text-white/20"></i>
                                </div>
                                <h4 class="text-lg font-black text-slate-700 dark:text-white uppercase tracking-widest mb-1"><?php echo e(__($emptyMessage)); ?></h4>
                                <p class="text-[11px] font-bold text-slate-400 dark:text-white/40">No records found for this query.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
                <?php if($withdrawals->hasPages()): ?>
                    <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                        <?php echo e(paginateLinks($withdrawals)); ?>

                    </div>
                <?php endif; ?>
            </div><!-- card end -->
        </div>
    </div>
    <?php if (isset($component)) { $__componentOriginalbd5922df145d522b37bf664b524be380 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbd5922df145d522b37bf664b524be380 = $attributes; } ?>
<?php $component = App\View\Components\ConfirmationModal::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\ConfirmationModal::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbd5922df145d522b37bf664b524be380)): ?>
<?php $attributes = $__attributesOriginalbd5922df145d522b37bf664b524be380; ?>
<?php unset($__attributesOriginalbd5922df145d522b37bf664b524be380); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbd5922df145d522b37bf664b524be380)): ?>
<?php $component = $__componentOriginalbd5922df145d522b37bf664b524be380; ?>
<?php unset($__componentOriginalbd5922df145d522b37bf664b524be380); ?>
<?php endif; ?>
    <!-- Withdraw Approve Modal -->
    <div x-data="{ 
            show: false, 
            withdrawId: '', 
            amount: '',
            currency: '',
            init() {
                window.addEventListener('open-withdraw-approve-modal', (e) => {
                    this.withdrawId = e.detail.id;
                    this.amount = e.detail.amount;
                    this.currency = e.detail.currency;
                    this.show = true;
                });
            }
        }" 
        x-show="show" 
        class="fixed inset-0 z-[100] flex items-center justify-center" 
        x-cloak>
        
        <!-- Backdrop -->
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-0"
             x-transition:enter-end="opacity-100 backdrop-blur-sm"
             class="absolute inset-0 bg-black/60" 
             @click="show = false"></div>

        <!-- Modal Content -->
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-md bg-white dark:bg-[#1a1a1a] rounded-[2rem] shadow-2xl border border-white/20 dark:border-white/10 overflow-hidden z-10 m-4">
            
            <form action="<?php echo e(route('admin.withdraw.data.approve')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" :value="withdrawId">
                <!-- Header -->
                <div class="h-24 bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center px-8 relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/10 mix-blend-overlay"></div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-black text-white uppercase tracking-tighter shadow-sm">Approve Withdrawal</h3>
                        <p class="text-[10px] font-bold text-white/80 uppercase tracking-widest mt-1">Amount: <span x-text="`${amount} ${currency}`"></span></p>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8 space-y-6">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest ml-2">Transaction Details</label>
                        <textarea name="details" required class="w-full h-32 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl p-4 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" placeholder="Provide transaction number or payment details..."></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex gap-3">
                    <button type="button" @click="show = false" class="flex-1 h-11 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/10 transition-all ">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 h-11 rounded-xl bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20 active:scale-95 flex items-center justify-center gap-2">
                        <span class="material-symbols-rounded text-sm">check_circle</span> Approve
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdraw Reject Modal -->
    <div x-data="{ 
            show: false, 
            withdrawId: '', 
            trx: '',
            init() {
                window.addEventListener('open-withdraw-reject-modal', (e) => {
                    this.withdrawId = e.detail.id;
                    this.trx = e.detail.trx;
                    this.show = true;
                });
            }
        }" 
        x-show="show" 
        class="fixed inset-0 z-[100] flex items-center justify-center" 
        x-cloak>
        
        <!-- Backdrop -->
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-0"
             x-transition:enter-end="opacity-100 backdrop-blur-sm"
             class="absolute inset-0 bg-black/60" 
             @click="show = false"></div>

        <!-- Modal Content -->
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-md bg-white dark:bg-[#1a1a1a] rounded-[2rem] shadow-2xl border border-white/20 dark:border-white/10 overflow-hidden z-10 m-4">
            
            <form action="<?php echo e(route('admin.withdraw.data.reject')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" :value="withdrawId">
                <!-- Header -->
                <div class="h-24 bg-gradient-to-r from-rose-500 to-red-600 flex items-center px-8 relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/10 mix-blend-overlay"></div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-black text-white uppercase tracking-tighter shadow-sm">Reject Withdrawal</h3>
                        <p class="text-[10px] font-bold text-white/80 uppercase tracking-widest mt-1">TRX: <span x-text="trx"></span></p>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8 space-y-6">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest ml-2">Rejection Reason</label>
                        <textarea name="details" required class="w-full h-32 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl p-4 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="Provide the reason for rejection..."></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex gap-3">
                    <button type="button" @click="show = false" class="flex-1 h-11 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-white/10 transition-all ">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 h-11 rounded-xl bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all shadow-lg shadow-rose-500/20 active:scale-95 flex items-center justify-center gap-2">
                        <span class="material-symbols-rounded text-sm">cancel</span> Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>




<?php $__env->startPush('breadcrumb-plugins'); ?>
    <?php if (isset($component)) { $__componentOriginale48b4598ffc2f41a085f001458a956d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale48b4598ffc2f41a085f001458a956d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['dateSearch' => 'yes','placeholder' => 'Username / Email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['dateSearch' => 'yes','placeholder' => 'Username / Email']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale48b4598ffc2f41a085f001458a956d1)): ?>
<?php $attributes = $__attributesOriginale48b4598ffc2f41a085f001458a956d1; ?>
<?php unset($__attributesOriginale48b4598ffc2f41a085f001458a956d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale48b4598ffc2f41a085f001458a956d1)): ?>
<?php $component = $__componentOriginale48b4598ffc2f41a085f001458a956d1; ?>
<?php unset($__componentOriginale48b4598ffc2f41a085f001458a956d1); ?>
<?php endif; ?>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/global/js/moment.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/daterangepicker.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style-lib'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/global/css/daterangepicker.css')); ?>">
<?php $__env->stopPush(); ?>



<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\withdraw\withdrawals.blade.php ENDPATH**/ ?>