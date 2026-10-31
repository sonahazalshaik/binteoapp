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
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10">
            
            <!-- Header Section -->
            <div class="flex flex-col items-center text-center mb-6 md:mb-12">
                <div class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-2xl sm:rounded-[2rem] bg-gradient-to-br from-orange-500 to-amber-600 text-white flex items-center justify-center shadow-xl md:shadow-2xl shadow-orange-500/30 mb-3 sm:mb-5 relative group">
                    <span class="material-symbols-rounded text-2xl sm:text-3xl md:text-4xl">loyalty</span>
                </div>
                <h1 class="text-xl sm:text-3xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight mb-1.5 sm:mb-2">
                    Creator Channel Plans
                </h1>
                <p class="text-[11px] sm:text-xs md:text-sm font-semibold text-gray-500 dark:text-gray-400 max-w-2xl leading-relaxed px-2">
                    Set custom subscription prices for <span class="text-orange-600 dark:text-orange-400 font-bold"><?php echo e($channel->name ?? 'your channel'); ?></span>. 
                    Users who buy these plans can watch all premium videos exclusively from your channel.
                </p>
            </div>

            <?php if(!empty($migrationPending)): ?>
                <div class="mb-8 p-5 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-800 dark:text-amber-200 flex items-center gap-4">
                    <span class="material-symbols-rounded text-3xl text-amber-500">info</span>
                    <div>
                        <h4 class="font-bold text-sm">Database Migration Pending</h4>
                        <p class="text-xs opacity-90">Database tables will be activated once the migration command is executed.</p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Channel Stats Overview -->
            <div class="grid grid-cols-3 gap-2 sm:gap-5 mb-10">
                <!-- Active Subscribers -->
                <div class="relative overflow-hidden rounded-xl sm:rounded-3xl p-3 sm:p-6 bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md sm:shadow-xl shadow-blue-500/20">
                    <div class="flex items-start sm:items-center justify-between gap-2 mb-2 sm:mb-4">
                        <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shrink-0">
                            <span class="material-symbols-rounded text-base sm:text-2xl">group</span>
                        </div>
                        <span class="hidden sm:inline-block text-[10px] font-black uppercase tracking-widest bg-white/20 px-3 py-1 rounded-full">Channel Members</span>
                    </div>
                    <div class="text-base sm:text-4xl font-black tracking-tight mb-0.5 sm:mb-1 truncate">
                        <?php echo e(number_format($activeSubscribersCount ?? 0)); ?>

                    </div>
                    <p class="text-[7px] sm:text-xs font-semibold text-white/80 leading-tight">Active Paid Subscribers</p>
                </div>

                <!-- Total Plan Revenue -->
                <div class="relative overflow-hidden rounded-xl sm:rounded-3xl p-3 sm:p-6 bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md sm:shadow-xl shadow-emerald-500/20">
                    <div class="flex items-start sm:items-center justify-between gap-2 mb-2 sm:mb-4">
                        <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shrink-0">
                            <span class="material-symbols-rounded text-base sm:text-2xl">payments</span>
                        </div>
                        <span class="hidden sm:inline-block text-[10px] font-black uppercase tracking-widest bg-white/20 px-3 py-1 rounded-full">Total Earnings</span>
                    </div>
                    <div class="text-base sm:text-4xl font-black tracking-tight mb-0.5 sm:mb-1 truncate">
                        ₹<?php echo e(number_format($totalEarnings ?? 0, 2)); ?>

                    </div>
                    <p class="text-[7px] sm:text-xs font-semibold text-white/80 leading-tight">From Channel Subscriptions</p>
                </div>

                <!-- Active Plans Count -->
                <div class="relative overflow-hidden rounded-xl sm:rounded-3xl p-3 sm:p-6 bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-md sm:shadow-xl shadow-purple-500/20">
                    <div class="flex items-start sm:items-center justify-between gap-2 mb-2 sm:mb-4">
                        <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shrink-0">
                            <span class="material-symbols-rounded text-base sm:text-2xl">sell</span>
                        </div>
                        <span class="hidden sm:inline-block text-[10px] font-black uppercase tracking-widest bg-white/20 px-3 py-1 rounded-full">Plan Tiers</span>
                    </div>
                    <div class="text-base sm:text-4xl font-black tracking-tight mb-0.5 sm:mb-1 truncate">
                        <span id="active-plans-count"><?php echo e($plans->where('status', true)->count()); ?></span> / <?php echo e(max(4, $plans->count())); ?>

                    </div>
                    <p class="text-[7px] sm:text-xs font-semibold text-white/80 leading-tight">Active Plans Offered</p>
                </div>
            </div>

            <!-- Predefined Plan Tiers Section -->
            <div class="mb-14">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Configure Your Plan Tiers</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Set your price for each duration. Only plans toggled <strong>Active</strong> will be visible to viewers.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php
                        $tierVisuals = [
                            '1_month' => [
                                'color' => 'blue', 
                                'gradient' => 'from-blue-500 to-indigo-600', 
                                'svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="3" ry="3"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="8" cy="14" r="1" fill="currentColor"/><circle cx="12" cy="14" r="1" fill="currentColor"/><circle cx="16" cy="14" r="1" fill="currentColor"/><circle cx="8" cy="18" r="1" fill="currentColor"/><circle cx="12" cy="18" r="1" fill="currentColor"/></svg>'
                            ],
                            '3_months' => [
                                'color' => 'teal', 
                                'gradient' => 'from-emerald-500 to-teal-600', 
                                'svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 2 10 5-10 5L2 7z"/><path d="m2 12 10 5 10-5"/><path d="m2 17 10 5 10-5"/></svg>'
                            ],
                            '6_months' => [
                                'color' => 'amber', 
                                'gradient' => 'from-amber-500 to-orange-600', 
                                'svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg>'
                            ],
                            '1_year' => [
                                'color' => 'purple', 
                                'gradient' => 'from-purple-600 to-pink-600', 
                                'svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/></svg>'
                            ],
                        ];
                    ?>

                    <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $visual = $tierVisuals[$plan->duration_type] ?? [
                                'color' => 'orange', 
                                'gradient' => 'from-orange-500 to-amber-600', 
                                'svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>'
                            ];
                        ?>
                        <div class="relative bg-white dark:bg-[#181818] rounded-3xl border border-gray-100 dark:border-white/10 shadow-xl shadow-gray-200/50 dark:shadow-none p-6 flex flex-col justify-between transition-all duration-300 hover:scale-[1.01]"
                             id="plan-card-<?php echo e($plan->id); ?>">
                            
                            <!-- Card Header -->
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br <?php echo e($visual['gradient']); ?> text-white flex items-center justify-center shadow-lg shadow-<?php echo e($visual['color']); ?>-500/20">
                                        <?php echo $visual['svg']; ?>

                                    </div>

                                    <!-- Status Toggle -->
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               id="plan-toggle-<?php echo e($plan->id); ?>"
                                               class="sr-only peer" 
                                               <?php echo e($plan->status ? 'checked' : ''); ?>

                                               onchange="togglePlanStatus(<?php echo e($plan->id); ?>)">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-black text-gray-900 dark:text-white">
                                        <?php echo e($plan->duration_label); ?>

                                    </h3>
                                    <span id="plan-status-badge-<?php echo e($plan->id); ?>" class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full <?php echo e($plan->status ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'bg-gray-500/10 text-gray-500 border border-gray-500/20'); ?>">
                                        <?php echo e($plan->status ? 'Active' : 'Disabled'); ?>

                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-5">Valid for <?php echo e($plan->duration_days); ?> Days</p>

                                <!-- Edit Form -->
                                <form id="form-plan-<?php echo e($plan->id); ?>" onsubmit="savePlan(event, <?php echo e($plan->id); ?>)" class="space-y-4">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="plan_id" value="<?php echo e($plan->id); ?>">
                                    <input type="hidden" name="status" id="form-status-<?php echo e($plan->id); ?>" value="<?php echo e($plan->status ? 1 : 0); ?>">

                                    <!-- Price Input -->
                                    <div>
                                        <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                                            Price (INR)
                                        </label>
                                        <div class="relative">
                                            <span class="absolute top-0 left-0 pl-3.5 flex items-center font-bold text-gray-500 dark:text-gray-400 pointer-events-none select-none z-10" style="height: 44px; line-height: 44px;">₹</span>
                                            <input type="number" 
                                                   step="1" 
                                                   min="0" 
                                                   name="price" 
                                                   value="<?php echo e((int)$plan->price); ?>" 
                                                   required
                                                   class="w-full pl-8 pr-4 py-2.5 h-[44px] rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/80 dark:bg-white/[0.03] text-gray-900 dark:text-white font-bold text-base focus:ring-2 focus:ring-orange-500/40 focus:border-orange-500 focus:bg-white dark:focus:bg-[#1a1a1a] focus:outline-none transition-all shadow-sm">
                                        </div>
                                    </div>

                                    <!-- Plan Title / Label -->
                                    <div>
                                        <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                                            Plan Title
                                        </label>
                                        <input type="text" 
                                               name="name" 
                                               value="<?php echo e($plan->name); ?>" 
                                               placeholder="e.g. <?php echo e($plan->duration_label); ?> Pass"
                                               class="w-full px-3.5 py-2.5 h-[44px] rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50/80 dark:bg-white/[0.03] text-gray-900 dark:text-white text-xs font-semibold focus:ring-2 focus:ring-orange-500/40 focus:border-orange-500 focus:bg-white dark:focus:bg-[#1a1a1a] focus:outline-none transition-all shadow-sm">
                                    </div>

                                    <!-- Description / Perks -->
                                    <div>
                                        <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">
                                            Description & Perks
                                        </label>
                                        <textarea name="description" 
                                                  rows="2" 
                                                  placeholder="e.g. All exclusive premium videos, full HD playback..."
                                                  class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white text-xs font-medium leading-relaxed focus:ring-2 focus:ring-orange-500/40 focus:border-orange-500 focus:bg-white dark:focus:bg-[#1a1a1a] focus:outline-none transition-all resize-none shadow-sm"><?php echo e($plan->description); ?></textarea>
                                    </div>

                                    <!-- Save Button -->
                                    <button type="submit" 
                                            id="btn-save-<?php echo e($plan->id); ?>"
                                            class="w-full mt-2 py-2.5 px-4 rounded-xl bg-orange-500 hover:bg-orange-600 active:scale-[0.98] text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg shadow-orange-500/25 transition-all">
                                        <span class="material-symbols-rounded text-sm">save</span>
                                        <span>Save Changes</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-full py-12 text-center text-gray-500">
                            No plans configured yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Channel Subscribers Table -->
            <div class="bg-white dark:bg-[#181818] rounded-3xl border border-gray-100 dark:border-white/10 shadow-xl shadow-gray-200/50 dark:shadow-none p-6 md:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Recent Subscribed Members</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Users who joined your channel membership plans</p>
                    </div>
                </div>

                <?php if(isset($recentSubscriptions) && $recentSubscriptions->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="uppercase tracking-widest text-[10px] text-gray-400 border-b border-gray-100 dark:border-white/5">
                                <tr>
                                    <th class="py-3 px-4">Member</th>
                                    <th class="py-3 px-4">Plan Tier</th>
                                    <th class="py-3 px-4">Amount Paid</th>
                                    <th class="py-3 px-4">Joined Date</th>
                                    <th class="py-3 px-4">Expires</th>
                                    <th class="py-3 px-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                <?php $__currentLoopData = $recentSubscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-gray-900 dark:text-white flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-orange-500/10 text-orange-600 flex items-center justify-center font-black">
                                                <?php echo e(substr($sub->user->name ?? 'U', 0, 1)); ?>

                                            </div>
                                            <span><?php echo e($sub->user->name ?? 'User'); ?></span>
                                        </td>
                                        <td class="py-3.5 px-4 font-semibold text-gray-700 dark:text-gray-300">
                                            <?php echo e($sub->plan_name ?: ($sub->plan->duration_label ?? 'Standard Pass')); ?>

                                        </td>
                                        <td class="py-3.5 px-4 font-black text-emerald-600 dark:text-emerald-400">
                                            ₹<?php echo e(number_format($sub->amount, 2)); ?>

                                        </td>
                                        <td class="py-3.5 px-4 text-gray-500">
                                            <?php echo e($sub->created_at ? $sub->created_at->format('M d, Y') : '-'); ?>

                                        </td>
                                        <td class="py-3.5 px-4 text-gray-500">
                                            <?php echo e($sub->expires_at ? $sub->expires_at->format('M d, Y') : 'Lifetime'); ?>

                                        </td>
                                        <td class="py-3.5 px-4">
                                            <?php if($sub->isActive()): ?>
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Active</span>
                                            <?php else: ?>
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-gray-500/10 text-gray-500 border border-gray-500/20">Expired</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-3 text-gray-400">
                            <span class="material-symbols-rounded text-3xl">loyalty</span>
                        </div>
                        <h4 class="font-bold text-sm text-gray-700 dark:text-gray-300">No Members Yet</h4>
                        <p class="text-xs text-gray-400 max-w-sm mx-auto mt-1">
                            Set your prices and enable plans above. When viewers subscribe to your channel, they will appear here!
                        </p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>

    <script>
        function showNotification(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `pointer-events-auto px-5 py-3 rounded-2xl shadow-2xl text-xs font-bold flex items-center gap-2 transform transition-all duration-300 translate-y-4 opacity-0 ${
                type === 'success' ? 'bg-emerald-500 text-white shadow-emerald-500/30' : 'bg-rose-500 text-white shadow-rose-500/30'
            }`;
            toast.innerHTML = `
                <span class="material-symbols-rounded text-base">${type === 'success' ? 'check_circle' : 'error'}</span>
                <span>${message}</span>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
            }, 10);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        async function savePlan(e, planId) {
            e.preventDefault();
            const form = document.getElementById(`form-plan-${planId}`);
            const btn = document.getElementById(`btn-save-${planId}`);
            const badge = document.getElementById(`plan-status-badge-${planId}`);
            const toggle = document.getElementById(`plan-toggle-${planId}`);
            const formStatus = document.getElementById(`form-status-${planId}`);
            const counter = document.getElementById('active-plans-count');
            
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `<span class="material-symbols-rounded text-sm animate-spin">refresh</span><span>Saving...</span>`;

            const formData = new FormData(form);

            try {
                const response = await fetch("<?php echo e(route('studio.channel-plans.update')); ?>", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    if (data.plan) {
                        const isAct = !!data.plan.status;
                        if (toggle) toggle.checked = isAct;
                        if (formStatus) formStatus.value = isAct ? '1' : '0';
                        if (badge) {
                            badge.className = isAct 
                                ? 'text-[10px] font-black uppercase px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/20'
                                : 'text-[10px] font-black uppercase px-2.5 py-1 rounded-full bg-gray-500/10 text-gray-500 border border-gray-500/20';
                            badge.innerText = isAct ? 'Active' : 'Disabled';
                        }
                    }
                    if (data.active_count !== undefined && counter) {
                        counter.innerText = data.active_count;
                    }
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message || 'Failed to update plan.', 'error');
                }
            } catch (err) {
                showNotification('An unexpected error occurred.', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        async function togglePlanStatus(planId) {
            const badge = document.getElementById(`plan-status-badge-${planId}`);
            const toggle = document.getElementById(`plan-toggle-${planId}`);
            const formStatus = document.getElementById(`form-status-${planId}`);
            const counter = document.getElementById('active-plans-count');
            
            try {
                const response = await fetch("<?php echo e(route('studio.channel-plans.toggle')); ?>", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({ plan_id: planId })
                });

                const data = await response.json();
                if (data.success) {
                    const isAct = !!data.status;
                    if (toggle) toggle.checked = isAct;
                    if (formStatus) formStatus.value = isAct ? '1' : '0';
                    if (badge) {
                        badge.className = isAct 
                            ? 'text-[10px] font-black uppercase px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/20'
                            : 'text-[10px] font-black uppercase px-2.5 py-1 rounded-full bg-gray-500/10 text-gray-500 border border-gray-500/20';
                        badge.innerText = isAct ? 'Active' : 'Disabled';
                    }
                    if (data.active_count !== undefined && counter) {
                        counter.innerText = data.active_count;
                    }
                    showNotification(data.message, 'success');
                } else {
                    // Revert toggle if activation rejected
                    if (toggle) toggle.checked = false;
                    if (formStatus) formStatus.value = '0';
                    showNotification(data.message || 'Failed to toggle status.', 'error');
                }
            } catch (err) {
                if (toggle) toggle.checked = !toggle.checked;
                showNotification('An unexpected error occurred.', 'error');
            }
        }
    </script>
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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\user\studio\channel_plans.blade.php ENDPATH**/ ?>