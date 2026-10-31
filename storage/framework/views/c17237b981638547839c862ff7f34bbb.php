<?php
    $relChannelName = $related->user->channel->name ?? $related->user->name ?? 'Creator';
    $relWords = explode(' ', trim($relChannelName));
    $relInitials = count($relWords) >= 2 
        ? strtoupper(substr($relWords[0], 0, 1) . substr($relWords[count($relWords)-1], 0, 1))
        : strtoupper(substr($relChannelName, 0, 2));
?>
<!-- Thumbnail & Text Flex Block -->
<a href="<?php echo e(route('videos.show', $related)); ?>" 
   class="flex flex-col lg:flex-row gap-3 lg:gap-2.5 group cursor-pointer"
   onmouseenter="this.querySelector('video')?.play()?.catch(()=>{})"
   onmouseleave="const v = this.querySelector('video'); if(v) { v.pause(); v.currentTime = 0; }">
    <div class="relative w-full lg:w-[168px] aspect-video flex-shrink-0 rounded-2xl lg:rounded-xl overflow-hidden bg-gray-200 dark:bg-[#272727]">
        <?php if($related->isBunnyVideo()): ?>
            <img src="<?php echo e($related->getPreviewUrl()); ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy" />
        <?php else: ?>
            <video src="<?php echo e($related->getVideoUrl()); ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" preload="metadata" muted playsinline></video>
        <?php endif; ?>
        <img src="<?php echo e($related->getThumbnailUrl()); ?>" 
             class="absolute inset-0 w-full h-full object-cover group-hover:opacity-0 transition-opacity duration-500 z-10"
             onerror="this.src='<?php echo e($related->getPreviewUrl()); ?>'; this.onerror=null;">
        <span class="absolute bottom-2 right-2 lg:bottom-1 lg:right-1 bg-black/80 text-white text-[12px] px-1.5 py-0.5 rounded font-medium z-20"><?php echo e($related->formatted_duration); ?></span>
    </div>
    <div class="flex flex-1 items-start gap-3 lg:gap-0 lg:flex-col py-0.5 lg:py-0.5 min-w-0 px-1 lg:px-0">
        <!-- Mobile-Only Channel Avatar (Left of Title & Meta) -->
        <div class="lg:hidden w-10 h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-800 flex-shrink-0 mt-0.5">
            <?php if($related->user->channel && $related->user->channel->avatar): ?>
                <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $related->user->channel->avatar)); ?>" class="w-full h-full object-cover">
            <?php elseif($related->user->image): ?>
                <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $related->user->image)); ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <span class="text-[10px] font-black text-gray-500 dark:text-white flex items-center justify-center w-full h-full"><?php echo e($relInitials); ?></span>
            <?php endif; ?>
        </div>

        <!-- Content Column -->
        <div class="flex-1 min-w-0">
            <div class="flex justify-between items-start gap-2">
                <h4 class="font-bold text-[14px] sm:text-[15px] lg:text-[14px] leading-snug line-clamp-2 text-gray-900 dark:text-[#F1F1F1]"><?php echo e($related->title); ?></h4>
                <button onclick="event.preventDefault(); event.stopPropagation(); window.openVideoOptions('<?php echo e($related->id); ?>', '<?php echo e(addslashes($related->title)); ?>', 'video', <?php echo e($related->isLikedBy(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($related->isInWatchLater(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($related->isInAnyPlaylist(auth()->user()) ? 'true' : 'false'); ?>, <?php echo json_encode($related->getPlaylistMembershipIds(auth()->user() ?? null), 15, 512) ?>, <?php echo e($related->user_id); ?>)" 
                        class="text-gray-400 dark:text-[#AAAAAA] hover:text-gray-900 dark:hover:text-white transition-colors shrink-0 p-1 -mr-1">
                    <span class="material-symbols-rounded text-[20px]">more_vert</span>
                </button>
            </div>
            <div class="flex flex-col mt-1 text-[12px] text-gray-500 dark:text-[#AAAAAA]">
                <!-- Desktop Channel Avatar + Name Row -->
                <div class="hidden lg:flex items-center gap-2 mb-1">
                    <div class="w-4 h-4 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-800 flex items-center justify-center shrink-0">
                        <?php if($related->user->channel && $related->user->channel->avatar): ?>
                            <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $related->user->channel->avatar)); ?>" class="w-full h-full object-cover">
                        <?php elseif($related->user->image): ?>
                            <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $related->user->image)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-[6px] font-black text-gray-500 dark:text-white"><?php echo e($relInitials); ?></span>
                        <?php endif; ?>
                    </div>
                    <p class="hover:text-gray-900 dark:hover:text-white transition-colors line-clamp-1 truncate"><?php echo e($relChannelName); ?></p>
                </div>

                <!-- Mobile Channel Name + Views + Time Row -->
                <p class="lg:hidden text-[12px] text-gray-500 dark:text-gray-400 line-clamp-1">
                    <?php echo e($relChannelName); ?> &bull; <?php echo e(number_format($related->views_count)); ?> views &bull; <?php echo e($related->created_at->diffForHumans()); ?>

                </p>

                <!-- Desktop Views & Time -->
                <p class="hidden lg:block"><?php echo e(number_format($related->views_count)); ?> views &bull; <?php echo e($related->created_at->diffForHumans()); ?></p>
            </div>
        </div>
    </div>
</a>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\partials\video_card_list.blade.php ENDPATH**/ ?>