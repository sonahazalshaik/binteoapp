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
    <div class="py-10 bg-[#FAFAFA] dark:bg-[#0F0F0F] min-h-screen">
        <div class="max-w-[800px] mx-auto px-6">
            <div class="mb-12">
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Account Security</h1>
                <p class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-2">Manage passwords and two-factor authentication</p>
            </div>

            <div class="space-y-8">
                <!-- Password Change -->
                <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-10 border border-gray-100 dark:border-white/5 shadow-sm">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-red-500/10 flex items-center justify-center">
                            <span class="material-symbols-rounded text-2xl text-red-500">lock</span>
                        </div>
                        <div>
                            <h3 class="font-black text-lg text-gray-900 dark:text-white uppercase tracking-widest">Change Password</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Update your account password</p>
                        </div>
                    </div>

                    <form action="<?php echo e(route('user.password.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Current Password</label>
                                <input type="password" name="current_password" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none" required>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">New Password</label>
                                    <input type="password" name="password" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none" required>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none" required>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-4 bg-gray-900 dark:bg-white text-white dark:text-black rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:opacity-90 transition-all active:scale-95">Update Password</button>
                        </div>
                    </form>
                </div>

                <!-- Two-Factor Authentication -->
                <div class="bg-white dark:bg-[#1A1A1A] rounded-[2.5rem] p-10 border border-gray-100 dark:border-white/5 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center">
                                <span class="material-symbols-rounded text-2xl text-blue-500">security</span>
                            </div>
                            <div>
                                <h3 class="font-black text-lg text-gray-900 dark:text-white uppercase tracking-widest">Two-Factor Auth</h3>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Google Authenticator</p>
                            </div>
                        </div>
                        <?php if(auth()->user()->ts): ?>
                            <span class="px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-500/10">Enabled</span>
                        <?php else: ?>
                            <span class="px-4 py-1.5 rounded-full bg-gray-100 dark:bg-white/5 text-gray-400 text-[10px] font-black uppercase tracking-widest">Disabled</span>
                        <?php endif; ?>
                    </div>

                    <div class="p-6 bg-gray-50/50 dark:bg-white/2 rounded-2xl border border-gray-100 dark:border-white/5 mb-8">
                        <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-relaxed">
                            Two-factor authentication adds an extra layer of security to your account. When enabled, you'll be asked for a verification code from your authenticator app during login.
                        </p>
                    </div>

                    <?php if(auth()->user()->ts): ?>
                        <form action="<?php echo e(route('user.twofactor.disable')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Enter Auth Code to Disable</label>
                                    <input type="text" name="code" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none" required>
                                </div>
                                <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-red-500/20 hover:bg-red-700 transition-all active:scale-95">Disable 2FA</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('user.twofactor')); ?>" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all active:scale-95 block text-center">Enable 2FA</a>
                    <?php endif; ?>
                </div>
            </div>
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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\client\settings\security.blade.php ENDPATH**/ ?>