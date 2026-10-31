
<?php $__env->startSection('panel'); ?>
    <div class="bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-sm transition-all duration-300">
                <div class="overflow-x-auto scrollbar-hide"><table class="w-full text-left border-collapse">
                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('User'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Email-Mobile'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Country'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Joined At'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Balance'); ?></th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap"><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    <span class="fw-bold"><?php echo e($user->fullname); ?></span>
                                    <br>
                                    <span class="small">
                                    <a href="<?php echo e(route('admin.users.detail', $user->id)); ?>"><span>@</span><?php echo e($user->username); ?></a>
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    <?php echo e($user->email); ?><br><?php echo e($user->mobileNumber); ?>

                                </td>
                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    <span class="fw-bold" title="<?php echo e(@$user->country_name); ?>"><?php echo e($user->country_code); ?></span>
                                </td>



                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    <?php echo e(showDateTime($user->created_at)); ?> <br> <?php echo e(diffForHumans($user->created_at)); ?>

                                </td>


                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    <span class="fw-bold">

                                    <?php echo e(showAmount($user->balance)); ?>

                                    </span>
                                </td>

                                <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">
                                    <div class="button--group">
                                        <a href="<?php echo e(route('admin.users.detail', $user->id)); ?>" class="btn btn-sm btn-outline--primary">
                                            <i class="las la-desktop"></i> <?php echo app('translator')->get('Details'); ?>
                                        </a>
                                        <?php if(request()->routeIs('admin.users.kyc.pending')): ?>
                                        <a href="<?php echo e(route('admin.users.kyc.details', $user->id)); ?>" target="_blank" class="btn btn-sm btn-outline--dark">
                                            <i class="las la-user-check"></i><?php echo app('translator')->get('KYC Data'); ?>
                                        </a>
                                        <?php endif; ?>
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
                </div>
                <?php if($users->hasPages()): ?>
                <div class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01]">
                    <?php echo e(paginateLinks($users)); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>


    </div>
<?php $__env->stopSection(); ?>



<?php $__env->startPush('breadcrumb-plugins'); ?>
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
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\users\list.blade.php ENDPATH**/ ?>