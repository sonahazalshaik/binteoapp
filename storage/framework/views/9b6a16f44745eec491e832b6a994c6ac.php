
<?php $__env->startSection('title', 'Advance Ads'); ?>
<?php $__env->startSection('header_title', 'Ad Network'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        use Carbon\Carbon;
    ?>

    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
        <?php echo $__env->make('admin.components.header-toolbar', [
            'title' => $pageTitle ?? 'Advance Ads',
            'items' => $advertisements,
            'createRoute' => route('admin.advertisement.create'),
            'createLabel' => 'Initialize Advertisement'
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="px-6 pt-6">
            <?php echo $__env->make('admin.components.table-toolbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('User'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Ad Title'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Campaign Title'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Ad Reached'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Ad Engagement'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Daily Budget | Total Costs'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Ad Schedule Type'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Ad Type'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Status'); ?></th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                <?php $__empty_1 = true; $__currentLoopData = $advertisements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $advertisement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $totalAdDays = 0;

                                        if ($advertisement->schedule_type == 1) {
                                            $start = Carbon::parse($advertisement->start_date);
                                            $end = Carbon::parse($advertisement->end_date);

                                            $totalAdDays += $start->diffInDays($end) + 1;
                                        }

                                        if ($advertisement->schedule_type == 2 && $advertisement->schedules) {
                                            foreach ($advertisement->schedules as $schedule) {
                                                $start = Carbon::parse($schedule->custom_start_date);
                                                $end = Carbon::parse($schedule->custom_end_date);

                                                $totalAdDays += $start->diffInDays($end) + 1;
                                            }
                                        }

                                    ?>


                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(__($advertisement->user?->fullname)); ?> <br>

                                            <a
                                                href="<?php echo e(route('admin.users.detail', $advertisement->user_id)); ?>"><span>@</span><?php echo e($advertisement->user?->username); ?></a>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="campaign-item">
                                                <div class="campaign-item__content">
                                                    <p class="campaign-item__title "><?php echo e(__($advertisement->title)); ?></p>

                                                    <small class="text--primary"><?php echo app('translator')->get('For '); ?><?php echo e(round($totalAdDays)); ?>

                                                        <?php echo app('translator')->get('Days'); ?></small>


                                                </div>
                                            </div>
                                        </td>


                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(__($advertisement->campaign?->title)); ?>

                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(formatNumber($advertisement->ad_reached)); ?> <br>
                                            <small class="text--success"><?php echo app('translator')->get('Ads Reached'); ?>:
                                                <?php echo e(formatNumber($advertisement->adReaches()->count())); ?> </small>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(formatNumber($advertisement->ad_engagement)); ?> <br>
                                            <small class="text--success"><?php echo app('translator')->get('Ads Engagement'); ?>:
                                                <?php echo e(formatNumber($advertisement->advertisementAnalytics()->count())); ?>

                                            </small>

                                        </td>


                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php echo e(showAmount($advertisement->daily_costs)); ?> <br>
                                            <?php echo e(showAmount($advertisement->total_amount)); ?>

                                        </td>

                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php if($advertisement->schedule_type == 1): ?>
                                                <?php echo app('translator')->get('Daily'); ?>
                                            <?php elseif($advertisement->schedule_type == 2): ?>
                                                <?php echo app('translator')->get('Custom'); ?>
                                            <?php endif; ?>
                                        </td>



                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php
                                                echo $advertisement->adTypeBadge;
                                            ?>

                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <?php
                                                echo $advertisement->statusBadge;
                                            ?>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                            <div class="button--group">
                                                <a href="<?php echo e(route('admin.advance.ads.detail', $advertisement->id)); ?>" class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i><?php echo app('translator')->get('Detail'); ?>
                                                </a>

                                                <?php if($advertisement->status == Status::ADVERTISEMENT_PENDING): ?>
                                                    <form action="<?php echo e(route('admin.advance.ads.approved', $advertisement->id)); ?>" method="POST" class="inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline--success">
                                                            <i class="las la-check"></i><?php echo app('translator')->get('Approve'); ?>
                                                        </button>
                                                    </form>
                                                    <button type="button" @click="$dispatch('open-ad-reject-modal', { id: <?php echo e($advertisement->id); ?>, title: '<?php echo e($advertisement->title); ?>' })" class="btn btn-sm btn-outline--danger">
                                                        <i class="las la-times"></i><?php echo app('translator')->get('Reject'); ?>
                                                    </button>
                                                <?php endif; ?>

                                                <?php if($advertisement->status == Status::RUNNING): ?>
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="<?php echo e(route('admin.advance.ads.status', $advertisement->id)); ?>"
                                                        data-question="<?php echo app('translator')->get('Are you sure want to pause this advertisement?'); ?>">
                                                        <i class="las la-pause"></i><?php echo app('translator')->get('Pause'); ?>
                                                    </button>
                                                <?php elseif($advertisement->status == Status::PAUSE): ?>
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-action="<?php echo e(route('admin.advance.ads.status', $advertisement->id)); ?>"
                                                        data-question="<?php echo app('translator')->get('Are you sure want to start this advertisement?'); ?>">
                                                        <i class="las la-play"></i><?php echo app('translator')->get('Running'); ?>
                                                    </button>
                                                <?php endif; ?>

                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" 
                                                        data-action="<?php echo e(route('admin.advance.ads.delete', $advertisement->id)); ?>" 
                                                        data-method="DELETE"
                                                        data-question="<?php echo app('translator')->get('Are you sure you want to delete this advertisement?'); ?>">
                                                    <i class="las la-trash"></i><?php echo app('translator')->get('Delete'); ?>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                        <td class="text-muted text-center" colspan="100%"><?php echo e(__($emptyMessage ?? 'No data found')); ?></td>
                                    </tr>
                                <?php endif; ?>



                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <?php if($advertisements->hasPages()): ?>
                    <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                        <?php echo e(paginateLinks($advertisements)); ?>

                    </div>
                <?php endif; ?>
            </div>
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
<!-- Ad Rejection Modal -->
<div x-data="{ 
        show: false, 
        adId: '', 
        adTitle: '',
        init() {
            window.addEventListener('open-ad-reject-modal', (e) => {
                this.adId = e.detail.id;
                this.adTitle = e.detail.title;
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
        
        <form :action="`<?php echo e(url('admin/advance-ads/reject')); ?>/${adId}`" method="POST">
            <?php echo csrf_field(); ?>
            <!-- Header -->
            <div class="h-24 bg-gradient-to-r from-rose-500 to-red-600 flex items-center px-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 mix-blend-overlay"></div>
                <div class="relative z-10">
                    <h3 class="text-xl font-black text-white uppercase tracking-tighter shadow-sm">Reject Ad</h3>
                    <p class="text-[10px] font-bold text-white/80 uppercase tracking-widest mt-1">Title: <span x-text="adTitle"></span></p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-8 space-y-6">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 dark:text-white/40 uppercase tracking-widest ml-2">Rejection Reason</label>
                    <textarea name="message" required class="w-full h-32 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl p-4 text-xs font-bold text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="Provide the reason for rejection..."></textarea>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'Username / Title']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Username / Title']); ?>
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


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\advance_ads\index.blade.php ENDPATH**/ ?>