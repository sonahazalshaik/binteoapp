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
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <!-- Studio Header -->
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Monetization</h1>
                    <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Manage Revenue Streams & Channel Memberships</p>
                </div>
            </div>

             <!-- Validation Errors/Success -->
             <?php if(session('success')): ?>
                <div class="mb-8 p-4 bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 rounded-2xl font-bold">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 rounded-2xl font-bold">
                    <ul class="list-disc pl-5">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Revenue Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <!-- Lifetime Ad Revenue -->
                <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-8 border border-gray-100 dark:border-white/5 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-green-500 to-emerald-600 flex items-center justify-center text-white shadow-lg">
                            <span class="material-symbols-outlined rounded">attach_money</span>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-1">$<?php echo e(number_format($totalEarnings, 2)); ?></h3>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Lifetime Ad Revenue</p>
                </div>

                <!-- Monthly Ad Revenue -->
                <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-8 border border-gray-100 dark:border-white/5 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                            <span class="material-symbols-outlined rounded">leaderboard</span>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-1">$<?php echo e(number_format($thisMonthEarnings, 2)); ?></h3>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Est. Ads This Month</p>
                </div>

                <!-- Recurring VIP Revenue (Monthly) -->
                <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-8 border border-gray-100 dark:border-white/5 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-500 to-violet-600 flex items-center justify-center text-white shadow-lg">
                            <span class="material-symbols-outlined rounded">stars</span>
                        </div>
                        <span class="text-[10px] font-black text-green-500 bg-green-50 dark:bg-green-500/10 px-3 py-1 rounded-full">Recurring</span>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-1">$<?php echo e(number_format($monthlyMembershipRevenue, 2)); ?></h3>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Tier Subscriptions / mo</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                
                <!-- Channel Memberships Configuration -->
                <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-8 lg:p-10 border border-gray-100 dark:border-white/5 shadow-sm">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600">
                            <span class="material-symbols-outlined text-[20px] rounded">workspace_premium</span>
                        </div>
                        <h2 class="text-xl font-black text-gray-900 dark:text-white">Create Membership Tier</h2>
                    </div>

                    <form action="<?php echo e(route('studio.memberships.store')); ?>" method="POST" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Tier Name</label>
                            <input type="text" name="name" required placeholder="e.g. Bronze Fan" class="w-full bg-gray-50 dark:bg-black/20 border-0 ring-1 ring-gray-200 dark:ring-white/5 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-red-500 text-sm font-bold dark:text-white placeholder-gray-400 dark:placeholder-gray-600 transition-all">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Monthly Price ($)</label>
                            <input type="number" step="0.01" name="price" required placeholder="4.99" class="w-full bg-gray-50 dark:bg-black/20 border-0 ring-1 ring-gray-200 dark:ring-white/5 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-red-500 text-sm font-bold dark:text-white placeholder-gray-400 dark:placeholder-gray-600 transition-all">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Perks (Comma Separated)</label>
                            <textarea name="perks" rows="3" required placeholder="Custom Emotes, Exclusive Videos, Loyalty Badges" class="w-full bg-gray-50 dark:bg-black/20 border-0 ring-1 ring-gray-200 dark:ring-white/5 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-red-500 text-sm font-bold dark:text-white placeholder-gray-400 dark:placeholder-gray-600 transition-all resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-red-600 text-white rounded-2xl py-4 font-black text-sm uppercase tracking-widest hover:bg-red-700 active:scale-[0.98] transition-all shadow-xl shadow-red-500/20">
                            Launch Tier
                        </button>
                    </form>
                </div>

                <!-- Existing Tiers List -->
                <div class="space-y-6">
                    <h2 class="text-xl font-black text-gray-900 dark:text-white px-2 mb-4">Active Memberships</h2>
                    
                    <?php $__empty_1 = true; $__currentLoopData = $memberships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-8 border border-gray-100 dark:border-white/5 shadow-sm group hover:border-purple-500/50 transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white"><?php echo e($tier->name); ?></h3>
                                <p class="text-[12px] font-bold text-gray-400 mt-1"><?php echo e(number_format($tier->subscribers()->where('status', 'active')->count())); ?> Active Fans</p>
                            </div>
                            <div class="bg-gray-100 dark:bg-white/5 px-4 py-2 rounded-xl text-md font-black text-gray-900 dark:text-white">
                                $<?php echo e($tier->price); ?> <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">/mo</span>
                            </div>
                        </div>

                        <?php if($tier->perks): ?>
                        <div class="space-y-2 mt-6">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Included Perks:</p>
                            <ul class="space-y-2">
                                <?php $__currentLoopData = $tier->perks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="material-symbols-outlined text-[16px] text-green-500">check_circle</span>
                                    <span><?php echo e($perk); ?></span>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-12 border border-gray-100 dark:border-white/5 text-center flex flex-col items-center justify-center border-dashed">
                        <div class="w-16 h-16 bg-gray-50 dark:bg-white/5 rounded-full flex items-center justify-center text-gray-400 mb-4">
                            <span class="material-symbols-outlined text-3xl">sentiment_dissatisfied</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">No tiers launched yet</h3>
                        <p class="text-sm font-bold text-gray-400">Create your first fan subscription level to start earning recurring revenue.</p>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\client\studio\monetization.blade.php ENDPATH**/ ?>