<?php
    $cardUrl = route('channels.premium', $channel->slug ?? $channel->id);
    $activePlans = $channel->plans ? $channel->plans->filter(fn($p) => $p->status == 1 && $p->price > 0) : collect();
    $minPrice = $activePlans->where('price', '>', 0)->min('price');
    $premiumVideosCount = $channel->premium_videos_count ?? ($channel->user ? $channel->user->videos()->where('is_premium', 1)->count() : 0);
?>

<a href="<?php echo e($cardUrl); ?>" 
   class="<?php echo e(($fullWidth ?? false) ? 'w-full' : 'flex-shrink-0 w-[280px] sm:w-[320px] md:w-[350px]'); ?> bg-white dark:bg-[#1A1A1A] rounded-[1.5rem] md:rounded-[2rem] p-4 md:p-5 border border-amber-500/30 shadow-xl shadow-gray-200/50 dark:shadow-none group hover:scale-[1.02] transition-all duration-500 relative overflow-hidden flex items-center gap-4 md:gap-6">
    
    <!-- Premium Channel Badge Overlay (Top Left) -->
    <div class="absolute top-0 left-0 z-20">
        <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 text-white px-3 py-1 rounded-br-2xl text-[7px] font-black uppercase tracking-[0.2em] shadow-lg flex items-center gap-1">
            <span class="material-symbols-rounded text-[8px] fill-1">workspace_premium</span>
            Premium
        </div>
    </div>

    <!-- Active Plans Price Badge Overlay (Top Right) -->
    <?php if($minPrice && $minPrice > 0): ?>
    <div class="absolute top-0 right-0 z-20">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-3 py-1 rounded-bl-2xl text-[7px] font-black uppercase tracking-[0.2em] shadow-lg flex items-center gap-1">
            <span class="material-symbols-rounded text-[9px] fill-1">payments</span>
            <span>From <?php echo e(gs('cur_sym')); ?><?php echo e(showAmount($minPrice, 0, currencyFormat: false)); ?></span>
        </div>
    </div>
    <?php elseif($channel->is_active): ?>
    <!-- Dynamic Activity Signal -->
    <div class="absolute top-4 right-4 z-10 flex items-center gap-1.5 px-2 py-1 bg-white/10 dark:bg-black/20 rounded-lg border border-white/10 backdrop-blur-md group/signal">
        <div class="flex items-end gap-[1px] h-2">
            <div class="w-[2px] bg-emerald-500 rounded-full animate-[pulse_1s_infinite]"></div>
            <div class="w-[2px] bg-emerald-500 rounded-full h-[60%] animate-[pulse_1.2s_infinite]"></div>
            <div class="w-[2px] bg-emerald-500 rounded-full h-[80%] animate-[pulse_1.4s_infinite]"></div>
        </div>
        <span class="text-[6px] font-black text-emerald-500 uppercase tracking-widest opacity-80 group-hover/signal:opacity-100 transition-opacity">Live</span>
    </div>
    <?php endif; ?>
    
    <!-- Profile Image (Left Side) -->
    <div class="relative flex-shrink-0">
        <div class="absolute inset-0 bg-gradient-to-tr from-amber-500 to-rose-500 blur-xl opacity-0 group-hover:opacity-40 transition-opacity"></div>
        <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl md:rounded-[1.5rem] p-0.5 bg-gradient-to-tr from-amber-500 via-orange-500 to-rose-500 relative">
            <div class="w-full h-full rounded-2xl md:rounded-[1.4rem] border-2 border-white dark:border-[#1A1A1A] overflow-hidden bg-gray-100 dark:bg-white/5 flex items-center justify-center text-slate-300 font-black text-xl">
                <?php
                    $channelName = $channel->name ?? $channel->channel_name;
                    $words = explode(' ', $channelName);
                    $initials = strtoupper(substr($words[0], 0, 1) . (count($words) > 1 ? substr(end($words), 0, 1) : ''));
                ?>
                <?php if($channel->avatar): ?>
                    <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $channel->avatar)); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <?php elseif($channel->image): ?>
                    <img src="<?php echo e(getImage(getFilePath('channelProfile').'/'.$channel->image)); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <?php else: ?>
                    <?php echo e($initials); ?>

                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Info Section (Right Side) -->
    <div class="flex-1 min-w-0 flex flex-col justify-center">
        <h3 class="text-[12px] md:text-[15px] font-black text-gray-900 dark:text-white uppercase tracking-tighter line-clamp-2 leading-tight mb-1 group-hover:text-amber-500 transition-colors">
            <?php echo e($channel->name); ?>

        </h3>
        
        <div class="flex items-center gap-2 mb-2">
            <div class="flex items-center gap-0.5">
                <span class="material-symbols-rounded channel-count-icon text-amber-500 dark:text-white text-[12px] fill-1">group</span>
                <span class="text-[10px] font-black dark:text-white"><?php echo e(number_format($channel->subscribers_count)); ?></span>
            </div>
            <span class="text-[10px] text-gray-400 font-bold">•</span>
            <span class="text-[9px] font-black text-amber-500 uppercase tracking-widest">@<span><?php echo e($channel->user->username ?? 'creator'); ?></span></span>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1.5 text-amber-500">
                <span class="material-symbols-rounded text-[13px]">workspace_premium</span>
                <span class="text-[8px] font-black uppercase tracking-widest truncate max-w-[110px]">
                    <?php echo e($premiumVideosCount); ?> <?php echo e(Str::plural('Premium Video', $premiumVideosCount)); ?>

                </span>
            </div>
            <div class="w-6 h-6 rounded-lg bg-gradient-to-tr from-amber-500 to-orange-500 text-white flex items-center justify-center group-hover:translate-x-1 group-hover:scale-110 transition-all shadow-sm">
                <span class="material-symbols-rounded text-xs">arrow_forward</span>
            </div>
        </div>
    </div>
</a>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\partials\channel_plan_card_small.blade.php ENDPATH**/ ?>