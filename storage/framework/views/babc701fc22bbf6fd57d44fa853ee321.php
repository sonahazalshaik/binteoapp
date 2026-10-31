<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="text-center mb-8">
        <!-- Logo -->
        <div class="inline-block mb-4">
            <div class="w-16 h-16 bg-white dark:bg-white rounded-2xl flex items-center justify-center p-2 shadow-[0_0_30px_rgba(0,0,0,0.05)] dark:shadow-[0_0_30px_rgba(255,255,255,0.1)]">
                <img src="<?php echo e(siteLogo()); ?>" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div class="mt-2 text-[10px] font-black text-gray-400 dark:text-white/40 uppercase tracking-[0.3em] font-['Space_Grotesk']"><?php echo e(gs('site_name')); ?></div>
        </div>

        <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mb-2 uppercase tracking-tight ">Recover Access</h1>
        <p class="text-gray-500 dark:text-[#e6beb2]/60 text-[10px] leading-relaxed px-4 uppercase tracking-widest font-bold">
            <?php echo e(__('No problem. We will email you a password reset link to choose a new key.')); ?>

        </p>
    </div>

    <!-- Session Status -->
    <?php if (isset($component)) { $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'mb-4','status' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $attributes = $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $component = $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>

    <form method="POST" action="<?php echo e(route('password.email')); ?>" class="space-y-6 w-full" x-data="{ emailFocus: false, loading: false }" @submit="loading = true">
        <?php echo csrf_field(); ?>

        <!-- Email Address -->
        <div class="relative group">
            <div class="absolute top-0 left-0 h-14 flex items-center pl-4 pointer-events-none transition-all duration-300 z-10" 
                 :class="emailFocus ? 'text-[#ff571a] scale-110' : 'text-gray-400 dark:text-white/20'">
                <span class="material-symbols-rounded text-[22px]">alternate_email</span>
            </div>
            <input type="email" name="email" id="email" required autofocus
                   @focus="emailFocus = true" @blur="emailFocus = false"
                   class="peer w-full h-14 pl-12 pr-4 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white placeholder-transparent focus:bg-gray-50 dark:focus:bg-white/10 focus:border-[#ff571a] focus:ring-4 focus:ring-[#ff571a]/10 transition-all outline-none"
                   placeholder="Email Address" value="<?php echo e(old('email')); ?>" />
            <label for="email" class="absolute left-12 -top-2.5 px-2 text-[9px] font-black uppercase tracking-[0.2em] text-[#ff571a] transition-all peer-placeholder-shown:text-[11px] peer-placeholder-shown:text-gray-400 dark:peer-placeholder-shown:text-white/20 peer-placeholder-shown:top-4 peer-placeholder-shown:bg-transparent peer-focus:-top-2.5 peer-focus:text-[9px] peer-focus:text-[#ff571a] font-['Space_Grotesk'] pointer-events-none">
                Email Address
            </label>
            <div class="mt-1.5 flex items-center gap-1.5 px-1">
                <span class="material-symbols-rounded text-[10px] text-[#ff571a]">info</span>
                <p class="text-[8px] font-black text-gray-400 dark:text-white/30 uppercase tracking-widest">Enter a valid email address for account security.</p>
            </div>
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('email'),'class' => 'mt-1 pl-4 text-[9px] text-rose-500 font-bold uppercase tracking-widest']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('email')),'class' => 'mt-1 pl-4 text-[9px] text-rose-500 font-bold uppercase tracking-widest']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
        </div>

        <div class="pt-2">
            <button type="submit" :disabled="loading"
                class="relative w-full h-14 flex items-center justify-center bg-gradient-to-r from-[#ff571a] to-[#ae3200] overflow-hidden rounded-xl text-white font-black text-[13px] uppercase tracking-[0.2em] shadow-[0_10px_40px_rgba(255,87,26,0.4)] hover:shadow-[0_15px_50px_rgba(255,87,26,0.6)] active:scale-[0.98] transition-all group disabled:opacity-60 disabled:cursor-not-allowed disabled:active:scale-100">
                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                <template x-if="!loading">
                    <span>Send Reset Link</span>
                </template>
                <template x-if="loading">
                    <span class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sending...
                    </span>
                </template>
            </button>
        </div>

        <div class="text-center pt-2">
            <a href="<?php echo e(route('login')); ?>" class="text-[10px] font-black text-gray-400 dark:text-white/30 uppercase tracking-[0.2em] hover:text-[#ff571a] transition-colors font-['Space_Grotesk']">
                Return to Sign In
            </a>
        </div>
    </form>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>

<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>