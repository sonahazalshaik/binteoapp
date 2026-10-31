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
    <div id="client-plans" class="py-6 md:py-10 bg-[#F8F9FA] dark:bg-[#0F0F0F] min-h-screen transition-colors duration-500" x-data="planPurchase()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Professional Header -->
            <div class="text-center max-w-3xl mx-auto mb-6 md:mb-10">
                <h2 class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.3em] text-red-600 mb-2 md:mb-2">Pricing Plans</h2>
                <h1 class="text-lg md:text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-2 md:mb-3 leading-tight">Choose Your Membership</h1>
                <p class="text-[9px] md:text-xs text-gray-500 dark:text-gray-400 font-medium leading-relaxed px-4 md:px-0">
                    Select the perfect plan to unlock premium features, ad-free viewing, and exclusive content access across our platform.
                </p>
            </div>

            <!-- Active Subscription Badge -->
            <?php if(isset($activeSubscription) && $activeSubscription): ?>
                <?php
                    $isLifetime = empty($activeSubscription->expired_date);
                    $daysRemaining = 0;
                    $progressPercentage = 100;
                    
                    if (!$isLifetime) {
                        $endDate = \Carbon\Carbon::parse($activeSubscription->expired_date);
                        $startDate = \Carbon\Carbon::parse($activeSubscription->created_at);
                        $totalDays = max(1, $startDate->diffInDays($endDate));
                        $daysRemaining = max(0, round(now()->diffInDays($endDate, false)));
                        $progressPercentage = max(0, min(100, (($totalDays - $daysRemaining) / $totalDays) * 100));
                    }
                ?>
                <div class="mb-6 md:mb-8 max-w-sm sm:max-w-md md:max-w-lg mx-auto relative group">
                    <div class="absolute inset-0 bg-orange-500/10 rounded-2xl blur-md group-hover:blur-lg transition-all duration-500"></div>
                    <div class="relative bg-white dark:bg-[#1A1A1A] border border-orange-500/30 rounded-2xl p-3 pb-4 flex items-center justify-between shadow-lg overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-16 h-16 bg-orange-500/10 rounded-full blur-xl"></div>
                        
                        <div class="flex items-center gap-2.5 sm:gap-3 z-10 flex-1 min-w-0 pr-2 sm:pr-3">
                            <div class="w-8 h-8 rounded-full bg-orange-500/10 text-orange-500 flex items-center justify-center flex-shrink-0 relative">
                                <div class="absolute inset-0 border border-orange-400/30 rounded-full animate-ping"></div>
                                <span class="material-symbols-rounded text-lg">stars</span>
                            </div>
                            <div class="text-left min-w-0 flex-1">
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <span class="text-[8px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest whitespace-nowrap">Active Plan</span>
                                    <?php if(!$isLifetime && $daysRemaining <= 7): ?>
                                        <span class="px-1.5 py-0.5 rounded text-[6px] font-black bg-red-500/10 text-red-600 uppercase tracking-widest animate-pulse whitespace-nowrap">Expiring Soon</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider truncate" title="<?php echo e($activeSubscription->plan->name ?? $activeSubscription->plan_name ?? 'Premium'); ?>"><?php echo e($activeSubscription->plan->name ?? $activeSubscription->plan_name ?? 'Premium'); ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center z-10 ml-auto flex-shrink-0">
                            <div class="text-right pr-2 sm:pr-4">
                                <p class="text-[6px] sm:text-[7px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-0.5 whitespace-nowrap">Purchased</p>
                                <p class="text-[9px] sm:text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-wider whitespace-nowrap">
                                    <?php echo e(\Carbon\Carbon::parse($activeSubscription->created_at)->format('d M y')); ?>

                                </p>
                            </div>
                            <div class="text-right pl-2 sm:pl-4 border-l border-gray-200 dark:border-white/10">
                                <p class="text-[6px] sm:text-[7px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-0.5 whitespace-nowrap">
                                    <?php echo e($isLifetime ? 'Status' : $daysRemaining . ' Days Left'); ?>

                                </p>
                                <p class="text-[9px] sm:text-[10px] font-black text-orange-500 uppercase tracking-wider whitespace-nowrap">
                                    <?php echo e($isLifetime ? 'LIFETIME' : \Carbon\Carbon::parse($activeSubscription->expired_date)->format('d M y')); ?>

                                </p>
                            </div>
                        </div>

                        <!-- Dynamic Progress Bar -->
                        <div class="absolute bottom-0 left-0 w-full h-[3px] bg-gray-100 dark:bg-white/5">
                            <div class="h-full bg-gradient-to-r from-orange-400 to-rose-500 relative" style="width: <?php echo e($progressPercentage); ?>%">
                                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-white rounded-full shadow-[0_0_5px_#f97316]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Modern Card Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-5 px-1 md:px-0 max-w-4xl mx-auto">
                <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $isPopular = $loop->index == 1; ?>
                
                <div class="relative group h-full">
                    <div class="bg-white dark:bg-[#1A1A1A] rounded-[1.25rem] md:rounded-[1.5rem] border shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full <?php echo e($isPopular ? 'border-red-300 dark:border-red-600 shadow-md' : 'border-gray-100 dark:border-white/5'); ?>">
                        
                            <div class="p-3 md:p-4 flex flex-col flex-grow">
                                <?php if($isPopular): ?>
                                <div class="self-center md:self-start bg-red-500 text-white text-center py-0.5 md:py-1 px-2 md:px-3 rounded-full text-[7px] md:text-[9px] font-black uppercase tracking-[0.1em] shadow-md mb-2">
                                    ⭐ Most Popular
                                </div>
                                <?php endif; ?>

                                <!-- Plan Name & Icon -->
                                <div class="flex flex-col md:flex-row items-center md:justify-between mb-1.5 md:mb-5">
                                    <div class="hidden md:flex w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 items-center justify-center">
                                        <span class="material-symbols-rounded text-red-600 text-[20px]">
                                            <?php echo e($loop->index == 0 ? 'auto_awesome_motion' : ($loop->index == 1 ? 'rocket_launch' : 'diamond')); ?>

                                        </span>
                                    </div>
                                    <span class="mb-1.5 md:mb-0 px-2 py-0.5 md:py-1 bg-gray-100 dark:bg-white/5 rounded-md text-[7px] md:text-[9px] font-black text-gray-500 dark:text-gray-400 mt-1 md:mt-0 uppercase tracking-widest">
                                        <?php
                                            if($plan->duration == 0) echo 'Lifetime';
                                            else if($plan->duration == 12 || $plan->duration == 365) echo '1 Year';
                                            else if($plan->duration == 1) echo '1 Month';
                                            else if($plan->duration >= 30 && $plan->duration % 30 == 0) {
                                                $m = $plan->duration / 30;
                                                echo $m . ' Month' . ($m > 1 ? 's' : '');
                                            } else {
                                                echo $plan->duration . ' Months';
                                            }
                                        ?>
                                    </span>
                                </div>

                                <a href="<?php echo e(route('user.plans.show', $plan->id)); ?>" class="block group-hover:text-red-600 transition-colors">
                                    <h3 class="text-[11px] md:text-lg font-black text-gray-900 dark:text-white break-words text-center md:text-left mb-0.5 group-hover:text-red-600 transition-colors flex items-center justify-center md:justify-start gap-1 flex-wrap leading-snug">
                                        <?php echo e(__($plan->name)); ?>

                                        <span class="material-symbols-rounded text-xs text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">arrow_forward</span>
                                    </h3>
                                </a>
                                <p class="text-[8px] md:text-xs text-gray-500 dark:text-gray-400 font-medium mb-2 md:mb-5 line-clamp-2 md:line-clamp-2 text-center md:text-left px-1">
                                    <?php echo e(__($plan->description ?? 'Premium features access.')); ?>

                                </p>

                                <!-- Pricing Section -->
                                <div class="mb-2 md:mb-6 flex flex-col md:block text-center md:text-left">
                                    <div class="flex items-baseline justify-center md:justify-start gap-1 flex-wrap">
                                        <span class="text-sm md:text-xl font-extrabold text-gray-900 dark:text-white"><?php echo e(showAmount($plan->price, 0)); ?></span>
                                    </div>
                                    <span class="text-[7px] md:text-xs font-medium text-gray-500 mt-0.5">One-time payment</span>
                                </div>

                            <!-- Features List -->
                            <div class="space-y-1.5 md:space-y-1.5 mb-3 md:mb-4 flex-grow">
                                <?php if($plan->is_featured_plan): ?>
                                <div class="flex items-center gap-1.5 md:gap-2 p-1.5 md:p-2 rounded-lg bg-orange-50 dark:bg-orange-500/10 border border-orange-100 dark:border-orange-500/10">
                                    <div class="w-4 h-4 md:w-4 md:h-4 rounded bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-rounded text-[10px] md:text-[12px] font-bold">stars</span>
                                    </div>
                                    <span class="text-[8px] md:text-[9px] font-black uppercase tracking-wider text-orange-700 dark:text-orange-400">Featured Tier Access</span>
                                </div>
                                <?php endif; ?>

                                <!-- Premium Content Access -->
                                <div class="flex items-center gap-1.5 md:gap-2 p-1.5 md:p-2 rounded-lg bg-gray-50 dark:bg-white/[0.03] border border-gray-100 dark:border-white/5">
                                    <div class="w-4 h-4 md:w-4 md:h-4 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-rounded text-[10px] md:text-[12px] font-bold"><?php echo e($plan->video_access ? 'check_circle' : 'cancel'); ?></span>
                                    </div>
                                    <span class="text-[8px] md:text-[9px] font-black uppercase tracking-wider <?php echo e($plan->video_access ? 'text-gray-600 dark:text-white/80' : 'text-gray-400 line-through'); ?>">Premium Content</span>
                                </div>

                                <!-- Contact Access -->
                                <div class="flex items-center gap-1.5 md:gap-2 p-1.5 md:p-2 rounded-lg bg-gray-50 dark:bg-white/[0.03] border border-gray-100 dark:border-white/5">
                                    <div class="w-4 h-4 md:w-4 md:h-4 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-rounded text-[10px] md:text-[12px] font-bold"><?php echo e($plan->contact_access ? 'check_circle' : 'cancel'); ?></span>
                                    </div>
                                    <span class="text-[8px] md:text-[9px] font-black uppercase tracking-wider <?php echo e($plan->contact_access ? 'text-gray-600 dark:text-white/80' : 'text-gray-400 line-through'); ?>">Contact Access</span>
                                </div>

                                <?php 
                                    $planFeatures = array_filter(array_map('trim', explode("\n", $plan->description)));
                                    if(empty($planFeatures)) $planFeatures = ['Priority Support', 'HD Streaming'];
                                ?>

                                <?php $__currentLoopData = $planFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-1.5 md:gap-2 p-1.5 md:p-2 rounded-lg bg-gray-50 dark:bg-white/[0.03] border border-gray-100 dark:border-white/5 hidden md:flex <?php echo e($loop->index == 0 ? '!flex' : ''); ?>">
                                    <div class="w-4 h-4 md:w-4 md:h-4 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-rounded text-[10px] md:text-[12px] font-bold">check_circle</span>
                                    </div>
                                    <span class="text-[8px] md:text-[9px] font-black uppercase tracking-wider text-gray-600 dark:text-white/80 truncate"><?php echo e($feature); ?></span>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <!-- Action Button -->
                            <div class="mt-auto">
                                <?php if(auth()->check() && in_array($plan->id, $userPlanIds)): ?>
                                    <button disabled class="w-full py-1.5 md:py-3 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg md:rounded-xl font-bold text-[9px] md:text-xs tracking-wide flex items-center justify-center gap-1 cursor-default">
                                        <span class="material-symbols-rounded text-[12px] md:text-[16px]">verified</span>
                                        Subscribed
                                    </button>
                                <?php else: ?>
                                    <button @click="initiatePurchase('<?php echo e($plan->id); ?>')" 
                                            :disabled="loading === '<?php echo e($plan->id); ?>'"
                                            class="w-full py-2 md:py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg md:rounded-xl font-bold text-[9px] md:text-xs tracking-wide shadow-md hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-1 disabled:opacity-50">
                                        <span x-show="loading !== '<?php echo e($plan->id); ?>'">Subscribe</span>
                                        <span x-show="loading === '<?php echo e($plan->id); ?>'" class="flex items-center gap-1">
                                            <svg class="animate-spin h-2.5 w-2.5 md:h-3.5 md:w-3.5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Wait...
                                        </span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-20 text-center">
                    <h3 class="text-xl font-bold text-gray-500">No active plans available.</h3>
                </div>
                <?php endif; ?>
            </div>

            <!-- Policy Footer -->
            <div class="mt-20 pt-10 pb-8 md:pb-4 border-t border-gray-100 dark:border-white/5 text-center">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Trusted by over <?php echo e($totalUsers); ?>+ global users</p>
                <div class="flex justify-center gap-4 text-[9px] font-bold uppercase tracking-widest text-gray-500">
                    <?php
                        $footerPolicies = getContent('policy_pages.element');
                        $privacyPolicy = $footerPolicies->filter(fn($p) => str_contains(strtolower($p->data_values->title), 'privacy'))->first();
                        $termsPolicy = $footerPolicies->filter(fn($p) => str_contains(strtolower($p->data_values->title), 'terms'))->first();
                    ?>
                    <?php if($privacyPolicy): ?>
                    <a href="<?php echo e(route('policy.pages', [$privacyPolicy->id, slug($privacyPolicy->data_values->title)])); ?>" class="hover:text-red-600 transition-colors">Privacy Policy</a>
                    <span class="text-gray-300">•</span>
                    <?php endif; ?>
                    <?php if($termsPolicy): ?>
                    <a href="<?php echo e(route('policy.pages', [$termsPolicy->id, slug($termsPolicy->data_values->title)])); ?>" class="hover:text-red-600 transition-colors">Terms of Service</a>
                    <span class="text-gray-300">•</span>
                    <?php endif; ?>
                    <a href="<?php echo e(route('user.ticket.index')); ?>" class="hover:text-red-600 transition-colors">Contact Support</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <style>
        /* Page-scoped icon visibility fix (this page only).
           The layout globally forces `.material-symbols-rounded` to `color: inherit`,
           which beats Tailwind's text-color utilities and leaves icons dark-on-dark
           in dark mode. Light mode restores the original color; dark mode renders white. */
        html:not(.dark) #client-plans span.material-symbols-rounded.text-red-600 { color: #dc2626 !important; }
        .dark #client-plans span.material-symbols-rounded.text-red-600 { color: #ffffff !important; }
    </style>
    <script>
        function planPurchase() {
            return {
                loading: null,
                initiatePurchase(planId) {
                    console.log('--- Purchase Flow Started ---');
                    console.log('Initiating purchase for Plan ID:', planId);
                    this.loading = planId;
                    const redirectParam = new URLSearchParams(window.location.search).get('redirect') || '<?php echo e(session('plan_redirect', '')); ?>';
                    fetch(`/client/plans/buy/${planId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            redirect: redirectParam
                        })
                    })
                    .then(res => {
                        console.log('Fetch response received:', res.status, res.statusText);
                        return res.json();
                    })
                    .then(data => {
                        console.log('Parsed JSON data:', data);
                        
                        if (data.error) {
                            console.error('Server returned error:', data.error);
                            Swal.fire('Error', data.error, 'error');
                            this.loading = null;
                            return;
                        }

                        if (data.redirect) {
                            console.log('Redirecting to:', data.redirect);
                            const targetUrl = redirectParam || data.redirect;
                            Swal.fire('Success', data.message || 'Action completed successfully', 'success').then(() => {
                                window.location.href = targetUrl;
                            });
                            return;
                        }

                        if (!data.key) {
                            console.error('Razorpay Key is missing in the payload!', data);
                            Swal.fire('Configuration Error', 'Razorpay Key is missing.', 'error');
                            this.loading = null;
                            return;
                        }

                        console.log('Initializing Razorpay with options...', {
                            key_length: data.key ? data.key.length : 0,
                            amount: data.amount,
                            order_id: data.order_id
                        });

                        const options = {
                            key: data.key,
                            amount: data.amount,
                            currency: data.currency,
                            name: "Premium Subscription",
                            description: `Upgrade to ${data.plan_name}`,
                            order_id: data.order_id,
                            handler: (response) => {
                                console.log('Razorpay payment handler triggered!', response);
                                this.verifyPayment(response, data.trx);
                            },
                            prefill: {
                                name: data.name,
                                email: data.email,
                                contact: data.contact
                            },
                            theme: { color: "#DC2626" },
                            modal: {
                                ondismiss: () => { 
                                    console.log('Razorpay modal dismissed by user.');
                                    this.loading = null; 
                                }
                            }
                        };
                        const rzp = new Razorpay(options);
                        rzp.on('payment.failed', function (response){
                            console.error('Razorpay Payment Failed Event:', response.error);
                        });
                        rzp.open();
                        console.log('Razorpay modal opened.');
                    })
                    .catch(err => {
                        console.error('Caught exception during fetch or setup:', err);
                        this.loading = null;
                        Swal.fire('Error', 'Gateway Initialization Failed. Check console logs.', 'error');
                    });
                },
                verifyPayment(response, trx) {
                    console.log('--- Verification Flow Started ---');
                    console.log('Verifying payment with TRX:', trx);
                    console.log('Razorpay Response Payload:', response);
                    
                    const redirectParam = new URLSearchParams(window.location.search).get('redirect') || '<?php echo e(session('plan_redirect', '')); ?>';
                    fetch('<?php echo e(route('user.plans.verify.payment')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_signature: response.razorpay_signature,
                            trx: trx,
                            redirect: redirectParam
                        })
                    })
                    .then(res => {
                        console.log('Verification fetch response:', res.status, res.statusText);
                        return res.json();
                    })
                    .then(data => {
                        console.log('Verification data received:', data);
                        if (data.success) {
                            console.log('Payment successfully verified!');
                            const returnUrl = data.redirect || redirectParam || '<?php echo e(route('user.plans.purchased', ['creator_page' => 1])); ?>';
                            Swal.fire('Success', data.message || data.success || 'Action completed successfully', 'success').then(() => {
                                window.location.href = returnUrl;
                            });
                        } else {
                            console.error('Payment verification failed:', data.error);
                            Swal.fire('Error', data.error, 'error');
                        }
                        this.loading = null;
                    })
                    .catch(err => {
                        console.error('Exception during verifyPayment fetch:', err);
                        this.loading = null;
                        Swal.fire('Error', 'Verification request failed. Check console.', 'error');
                    });
                }
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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\client\plans\index.blade.php ENDPATH**/ ?>