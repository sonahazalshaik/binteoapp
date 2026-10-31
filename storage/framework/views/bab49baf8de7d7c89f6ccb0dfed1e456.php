<?php
    $isVirtual = !is_numeric($playlistData->id);
    $itemRoute = $isVirtual 
        ? route('videos.show.virtual', ['virtualSlug' => $playlistData->id, 'video' => $item->slug])
        : route('videos.show.in_playlist', ['video' => $item->slug, 'username' => optional($playlistData->user->channel)->slug ?? $playlistData->user->username ?? 'user', 'playlist' => $playlistData->slug]);
?>

<a href="<?php echo e($itemRoute); ?>" 
   class="flex items-center gap-3 p-3 hover:bg-gray-200/50 dark:hover:bg-white/5 transition-colors border-l-4 <?php echo e($isActive ? 'border-red-600 bg-red-50/50 dark:bg-red-600/5' : 'border-transparent'); ?>">
    <div class="flex-shrink-0 w-6 text-center text-[10px] font-black <?php echo e($isActive ? 'text-red-600' : 'text-gray-400'); ?>">
        <?php if($isActive): ?>
            <span class="material-symbols-rounded text-sm fill-1">play_arrow</span>
        <?php else: ?>
            <?php echo e($index + 1); ?>

        <?php endif; ?>
    </div>
    <div class="relative w-24 aspect-video rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-800">
        <img src="<?php echo e($item->getThumbnailUrl()); ?>" class="w-full h-full object-cover">
        <div class="absolute bottom-1 right-1 px-1 py-0.5 bg-black/80 rounded text-[8px] font-bold text-white">
            <?php echo e($item->duration ?? '0:00'); ?>

        </div>
    </div>
    <div class="flex-grow min-w-0">
        <h4 class="text-[12px] font-bold <?php echo e($isActive ? 'text-red-600' : 'text-gray-900 dark:text-[#F1F1F1]'); ?> line-clamp-2 leading-tight">
            <?php echo e($item->title); ?>

        </h4>
        <p class="text-[10px] text-gray-500 dark:text-[#AAAAAA] mt-1"><?php echo e($item->user->channel->name ?? $item->user->name); ?></p>
    </div>
    <button @click.prevent.stop="window.openVideoOptions('<?php echo e($item->id); ?>', '<?php echo e(addslashes($item->title)); ?>', 'video', <?php echo e($item->isLikedBy(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($item->isInWatchLater(auth()->user()) ? 'true' : 'false'); ?>, <?php echo e($item->isInAnyPlaylist(auth()->user()) ? 'true' : 'false'); ?>, <?php echo json_encode($item->getPlaylistMembershipIds(auth()->user() ?? null), 15, 512) ?>, <?php echo e($item->user_id); ?>)" 
            class="flex-shrink-0 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-900 dark:hover:text-white active:scale-90 transition-transform">
        <span class="material-symbols-rounded text-lg">more_vert</span>
    </button>
</a>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\partials\playlist_item.blade.php ENDPATH**/ ?>