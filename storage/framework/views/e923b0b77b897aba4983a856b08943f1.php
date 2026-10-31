
<?php $__env->startSection('title', 'Advertisers'); ?>
<?php $__env->startSection('header_title', 'Advertiser Database'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
    <?php echo $__env->make('admin.components.header-toolbar', [
        'title' => $pageTitle ?? 'Advertiser Accounts',
        'items' => $advertisers,
        'createRoute' => route('admin.users.create'),
        'createLabel' => 'Add New Advertiser'
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="px-6 pt-6">
        <?php echo $__env->make('admin.components.table-toolbar', ['showBulkActions' => true, 'bulkActions' => ['approve', 'reject', 'delete']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                        </label>
                    </th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('User'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Email & Mobile'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Country'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Joined'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Balance'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 truncate"><?php echo app('translator')->get('Status'); ?></th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 text-right truncate"><?php echo app('translator')->get('Action'); ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                <?php $__empty_1 = true; $__currentLoopData = $advertisers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                        <td class="px-6 py-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="<?php echo e($user->id); ?>" class="bulk-select-item w-4 h-4 rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                            </label>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[12px] font-black text-slate-900 dark:text-white uppercase leading-none mb-1"><?php echo e(__($user->fullname)); ?></div>
                            <a href="<?php echo e(route('admin.users.detail', $user->id)); ?>" class="text-[10px] font-bold text-slate-500 dark:text-white/50 tracking-tight"><span>@</span><?php echo e($user->username); ?></a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[11px] font-black text-slate-900 dark:text-white mb-1"><?php echo e($user->email); ?></div>
                            <div class="text-[10px] font-bold text-slate-400 tracking-wider"><?php echo e($user->mobileNumber); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[11px] font-black text-slate-700 dark:text-white/80" title="<?php echo e(@$user->country_name); ?>"><?php echo e($user->country_code); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[11px] font-bold text-slate-900 dark:text-white mb-1"><?php echo e(showDateTime($user->created_at)); ?></div>
                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest "><?php echo e(diffForHumans($user->created_at)); ?></div>
                        </td>
                        <td class="px-6 py-4 text-[13px] font-black text-emerald-600 dark:text-emerald-400">
                            <?php echo e(showAmount($user->balance)); ?>

                        </td>
                        <td class="px-6 py-4">
                            <?php echo $user->advertiseStatus; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <?php if($user->advertiser_status == Status::ADVERTISER_PENDING): ?>
                                    <form action="<?php echo e(route('admin.advertiser.data.approve', $user->id)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="h-8 px-3 rounded-xl bg-emerald-500 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all active:scale-95 shadow-sm">
                                            <span class="material-symbols-rounded text-[14px]">check_circle</span> <?php echo app('translator')->get('Approve'); ?>
                                        </button>
                                    </form>
                                    <button type="button" @click="$dispatch('open-advertiser-reject-modal', { id: <?php echo e($user->id); ?>, name: '<?php echo e($user->username); ?>' })" class="h-8 px-3 rounded-xl bg-rose-500 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all active:scale-95 shadow-sm">
                                        <span class="material-symbols-rounded text-[14px]">cancel</span> <?php echo app('translator')->get('Reject'); ?>
                                    </button>
                                <?php endif; ?>
                                <a href="<?php echo e(route('admin.advertiser.detail', $user->id)); ?>" class="h-8 px-3 rounded-xl bg-indigo-500 text-white flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all active:scale-95 shadow-sm">
                                    <span class="material-symbols-rounded text-[14px]">desktop_windows</span> Details
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-10 py-32 text-center text-slate-400 font-black uppercase tracking-widest text-[9px] opacity-20"><?php echo e(__($emptyMessage ?? 'No advertisers found')); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($advertisers->hasPages()): ?>
        <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
            <?php echo e(paginateLinks($advertisers)); ?>

        </div>
    <?php endif; ?>
</div>

<!-- Advertiser Rejection Modal -->
<div x-data="{ 
        show: false, 
        userId: '', 
        userName: '',
        init() {
            window.addEventListener('open-advertiser-reject-modal', (e) => {
                this.userId = e.detail.id;
                this.userName = e.detail.name;
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
        
        <form :action="`<?php echo e(url('admin/advertiser/data-reject')); ?>/${userId}`" method="POST">
            <?php echo csrf_field(); ?>
            <!-- Header -->
            <div class="h-24 bg-gradient-to-r from-rose-500 to-red-600 flex items-center px-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 mix-blend-overlay"></div>
                <div class="relative z-10">
                    <h3 class="text-xl font-black text-white uppercase tracking-tighter shadow-sm">Reject Advertiser</h3>
                    <p class="text-[10px] font-bold text-white/80 uppercase tracking-widest mt-1">Node: <span x-text="userName"></span></p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-8 space-y-6">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest ml-2">Rejection Reason</label>
                    <textarea name="reason" required class="w-full h-32 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl p-4 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="Provide the reason for rejection..."></textarea>
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
    <div class="flex items-center gap-3">
        <?php if (isset($component)) { $__componentOriginale48b4598ffc2f41a085f001458a956d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale48b4598ffc2f41a085f001458a956d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'Username / Email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Username / Email']); ?>
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
    </div>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\advertiser\index.blade.php ENDPATH**/ ?>