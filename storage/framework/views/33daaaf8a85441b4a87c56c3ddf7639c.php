
<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-black flex justify-center">
    <div class="w-full max-w-lg relative">
        <!-- Video -->
        <div class="aspect-[9/16] max-h-[90vh] relative mx-auto bg-black rounded-3xl overflow-hidden shadow-2xl">
            <video src="<?php echo e(asset(getFilePath('reel') . '/' . ($reel->compressed_video_path ?? $reel->video_path))); ?>" class="w-full h-full object-cover" controls loop autoplay></video>
        </div>
        <!-- Info Panel -->
        <div class="bg-white dark:bg-[#121212] rounded-3xl mt-4 p-6 mx-4 border border-slate-100 dark:border-white/5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center text-purple-600 font-black"><?php echo e(substr($reel->user->fullname, 0, 1)); ?></div>
                <div><p class="font-black text-sm text-slate-900 dark:text-white"><?php echo e($reel->user->username ?? $reel->user->fullname); ?></p><p class="text-[10px] text-slate-400"><?php echo e($reel->created_at->diffForHumans()); ?></p></div>
                <div class="ml-auto flex gap-2">
                    <form action="<?php echo e(route('reels.like', $reel->slug)); ?>" method="POST"><?php echo csrf_field(); ?><button class="flex items-center gap-1 px-3 py-1.5 bg-red-50 dark:bg-red-500/10 text-red-500 rounded-xl text-xs font-bold"><span class="material-symbols-rounded text-sm">favorite</span><?php echo e($reel->likes_count); ?></button></form>
                    <form action="<?php echo e(route('reels.share', $reel->slug)); ?>" method="POST"><?php echo csrf_field(); ?><input type="hidden" name="platform" value="copy_link"><button class="flex items-center gap-1 px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-500 rounded-xl text-xs font-bold"><span class="material-symbols-rounded text-sm">share</span><?php echo e($reel->shares_count); ?></button></form>
                </div>
            </div>
            <h2 class="font-black text-lg text-slate-900 dark:text-white mb-2"><?php echo e($reel->title); ?></h2>
            <?php if($reel->description): ?><p class="text-sm text-slate-600 dark:text-slate-400 mb-4"><?php echo nl2br(preg_replace('/#([\p{L}\p{M}\p{N}_]+)/u', '<a href="/search?q=%23$1" class="text-orange-500 hover:underline">#$1</a>', e($reel->description))); ?></p><?php endif; ?>
            <?php if($reel->hashtags->count()): ?>
            <div class="flex flex-wrap gap-2 mb-4">
                <?php $__currentLoopData = $reel->hashtags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ht): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="/search?q=%23<?php echo e(urlencode(trim($ht->hashtag))); ?>" class="px-2 py-1 bg-purple-50 dark:bg-purple-500/10 text-purple-600 hover:bg-purple-100 dark:hover:bg-purple-500/20 rounded-lg text-xs font-bold">#<?php echo e($ht->hashtag); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
            <!-- Comments -->
            <div class="border-t border-slate-100 dark:border-white/5 pt-4 mt-4">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4">Comments (<?php echo e($reel->comments_count); ?>)</h3>
                <?php if(auth()->guard()->check()): ?>
                <?php if($reel->allow_comments): ?>
                <form action="<?php echo e(route('reels.comment', $reel->slug)); ?>" method="POST" class="flex gap-2 mb-4"><?php echo csrf_field(); ?>
                    <input type="text" name="comment" required placeholder="Add a comment... @mention users" class="flex-1 h-10 px-4 bg-slate-50 dark:bg-white/5 border-0 rounded-xl text-sm font-medium focus:ring-2 focus:ring-purple-500 outline-none">
                    <button class="h-10 px-4 bg-purple-600 text-white rounded-xl text-xs font-bold">Post</button>
                </form>
                <?php endif; ?>
                <?php endif; ?>
                <div class="space-y-3 max-h-60 overflow-y-auto">
                    <?php $__currentLoopData = $reel->comments->where('parent_id', null)->take(20); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/10 flex items-center justify-center text-xs font-black text-slate-500 shrink-0"><?php echo e(substr($comment->user->fullname, 0, 1)); ?></div>
                        <div>
                            <p class="text-xs"><span class="font-bold text-slate-900 dark:text-white"><?php echo e($comment->user->username ?? $comment->user->fullname); ?></span> <span class="text-slate-400"><?php echo e($comment->created_at->diffForHumans()); ?></span></p>
                            <p class="text-sm text-slate-700 dark:text-slate-300"><?php echo e($comment->content); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\frontend\reels\show.blade.php ENDPATH**/ ?>