<?php $__env->startSection('title', 'Channel Overview'); ?>
<?php $__env->startSection('header_title', 'Channel Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <!-- Channel Header Identity -->
    <div class="relative rounded-[3.5rem] overflow-hidden bg-white dark:bg-[#121212] border border-slate-200 dark:border-white/10 shadow-2xl h-[450px]">
        <!-- Banner -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            <?php if($channel->banner): ?>
                <img src="<?php echo e(getImage(getFilePath('channelBanner') . '/' . $channel->banner)); ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <div class="w-full h-full bg-[#f8fafc] dark:bg-[#0a0a0a] flex items-center justify-center p-12">
                    <div class="w-full h-full rounded-[3.5rem] border-4 border-dashed border-slate-200 dark:border-white/10 flex flex-col items-center justify-center relative overflow-hidden group">
                        <div class="absolute inset-0 opacity-10 bg-[url('https://grainy-gradients.vercel.app/noise.svg')]"></div>
                        <div class="relative z-10 flex flex-col items-center gap-6 mb-20">
                            <div class="w-24 h-24 rounded-[2.5rem] bg-white dark:bg-white/5 shadow-2xl border border-white/20 dark:border-white/10 flex items-center justify-center">
                                <span class="material-symbols-rounded text-slate-300 dark:text-white/10 text-5xl">landscape</span>
                            </div>
                            <div class="text-center">
                                <p class="text-[11px] font-black text-slate-400 dark:text-white/20 uppercase tracking-[0.4em] ">No cover photo available</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-[#121212] via-white/40 dark:via-[#121212]/40 to-transparent"></div>
        </div>

        <!-- Identity Bar -->
        <div class="absolute bottom-0 inset-x-0 p-12 flex flex-col sm:flex-row items-end justify-between gap-8 z-10">
            <div class="flex items-center gap-8">
                <div class="w-28 h-28 rounded-3xl overflow-hidden border-4 border-white dark:border-white/10 shadow-2xl relative group">
                    <?php if($channel->avatar): ?>
                        <img src="<?php echo e(getImage(getFilePath('channelAvatar') . '/' . $channel->avatar)); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400">
                            <span class="material-symbols-rounded text-4xl">tv</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <h3 class="text-4xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none"><?php echo e($channel->name); ?></h3>
                    <div class="flex items-center gap-4 mt-4">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                             @<span><?php echo e($channel->user->username); ?></span>
                        </span>
                        <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                        <span class="text-[10px] font-black text-orange-500 uppercase tracking-[0.2em] "><?php echo e(number_format($channel->subscribers_count)); ?> Subscribers</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4 mb-4">
                <a href="<?php echo e(route('admin.channels.edit', $channel)); ?>" class="h-14 px-10 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white font-black text-[11px] uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center gap-3">
                    <span class="material-symbols-rounded text-lg">edit_note</span>
                    Edit Channel
                </a>
                <form action="<?php echo e(route('admin.channels.status', $channel)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="h-14 px-10 rounded-2xl <?php echo e($channel->is_active ? 'bg-rose-500' : 'bg-emerald-500'); ?> text-white font-black text-[11px] uppercase tracking-widest hover:scale-105 transition-all shadow-xl active:scale-95 flex items-center gap-3 ">
                        <span class="material-symbols-rounded text-lg"><?php echo e($channel->is_active ? 'power_settings_new' : 'bolt'); ?></span>
                        <?php echo e($channel->is_active ? 'Disable Channel' : 'Enable Channel'); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Analytics & Narrative -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] p-10 border border-slate-200 dark:border-white/10 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-4 mb-8">
                    <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest ">Channel Description</span>
                    <div class="h-[1px] flex-grow bg-slate-100 dark:bg-white/5"></div>
                </div>
                <p class="text-[13px] font-bold text-slate-500 dark:text-white/40 leading-relaxed ">
                    <?php echo e($channel->description ?? 'No Channel description initialized for this creator yet...'); ?>

                </p>
            </div>

            <!-- Latest Videos -->
            <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] p-10 border border-slate-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest text-orange-500">Latest Videos</span>
                        <div class="h-[1px] w-20 bg-orange-500/10"></div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <?php $__empty_1 = true; $__currentLoopData = $channel->videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group space-y-4">
                        <a href="<?php echo e(route('videos.show', $video)); ?>" target="_blank" class="block relative aspect-video rounded-3xl overflow-hidden border border-slate-100 dark:border-white/5 shadow-sm bg-slate-50 dark:bg-white/[0.02] cursor-pointer">
                            <img src="<?php echo e($video->getThumbnailUrl()); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 scale-75 group-hover:scale-100 transition-transform duration-500">
                                    <span class="material-symbols-rounded text-white text-4xl fill-1">play_arrow</span>
                                </div>
                            </div>
                            <div class="absolute bottom-3 right-3 px-2 py-1 bg-black/80 backdrop-blur-md rounded-lg text-[9px] font-bold text-white">
                                <?php echo e($video->duration ?? '00:00'); ?>

                            </div>
                        </a>
                        <div>
                            <a href="<?php echo e(route('videos.show', $video)); ?>" target="_blank" class="text-[11px] font-black text-slate-800 dark:text-white truncate uppercase tracking-tighter hover:text-orange-500 transition-colors block"><?php echo e($video->title); ?></a>
                            <p class="text-[9px] text-slate-400 font-bold mt-1 uppercase"><?php echo e(number_format($video->views_count)); ?> Views • <?php echo e($video->created_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full py-20 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-3xl">
                        <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-5xl">smart_display</span>
                        <p class="mt-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No videos broadcast yet</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Latest Reels -->
            <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] p-10 border border-slate-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <span class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest text-rose-500">Latest Reels</span>
                        <div class="h-[1px] w-20 bg-rose-500/10"></div>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php $__empty_1 = true; $__currentLoopData = $channel->reels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group space-y-3">
                        <a href="<?php echo e(route('reels.show', $reel)); ?>" target="_blank" class="block relative aspect-[9/16] rounded-2xl overflow-hidden border border-slate-100 dark:border-white/5 shadow-sm bg-slate-50 dark:bg-white/[0.02] cursor-pointer">
                            <img src="<?php echo e($reel->getThumbnailUrl()); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="w-12 h-12 rounded-full bg-rose-500/80 backdrop-blur-sm flex items-center justify-center text-white scale-75 group-hover:scale-100 transition-transform duration-500 shadow-xl">
                                    <span class="material-symbols-rounded text-2xl fill-1">play_arrow</span>
                                </div>
                            </div>
                            <div class="absolute bottom-3 left-3 flex items-center gap-1.5 text-white">
                                <span class="material-symbols-rounded text-sm">play_arrow</span>
                                <span class="text-[9px] font-black tracking-widest uppercase"><?php echo e(number_format($reel->views_count)); ?></span>
                            </div>
                        </a>
                        <a href="<?php echo e(route('reels.show', $reel)); ?>" target="_blank" class="text-[9px] font-black text-slate-800 dark:text-white truncate uppercase tracking-tighter hover:text-rose-500 transition-colors block"><?php echo e($reel->title ?? 'Untitled Reel'); ?></a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full py-20 text-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-3xl">
                        <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-5xl">movie_edit</span>
                        <p class="mt-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No reels published yet</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Components -->
        <div class="space-y-8">
            <!-- Connectivity -->
            <div class="bg-white dark:bg-[#121212] rounded-[2.5rem] p-8 border border-slate-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest ">Social Links</span>
                    <div class="h-[1px] flex-grow bg-slate-100 dark:bg-white/5"></div>
                </div>
                <div class="space-y-4">
                    <?php $socials = $channel->social_links ?? []; ?>
                    <?php $__currentLoopData = ['facebook', 'twitter', 'instagram', 'youtube']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-white/[0.02] border border-transparent hover:border-slate-200 dark:hover:border-white/10 transition-all cursor-default group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white dark:bg-white/5 flex items-center justify-center text-slate-400 group-hover:text-orange-500 transition-colors shadow-sm">
                                <span class="material-symbols-rounded text-lg">share</span>
                            </div>
                            <span class="text-[9px] font-black uppercase text-slate-600 dark:text-white/40 tracking-widest"><?php echo e($key); ?></span>
                        </div>
                        <?php if(isset($socials[$key])): ?>
                            <span class="material-symbols-rounded text-emerald-500 text-base">verified</span>
                        <?php else: ?>
                            <span class="material-symbols-rounded text-slate-200 dark:text-white/5 text-base">block</span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Channel Owner -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-500 opacity-10 blur-[80px] group-hover:opacity-20 transition-opacity"></div>
                <div class="flex items-center gap-4 mb-6 relative z-10">
                    <span class="text-[10px] font-black text-white/40 uppercase tracking-widest ">Channel Owner</span>
                </div>
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 p-0.5">
                        <div class="w-full h-full rounded-[0.9rem] overflow-hidden">
                            <?php if($channel->user->image): ?>
                                <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $channel->user->image)); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-orange-500 flex items-center justify-center text-white font-black">
                                    <?php echo e(substr($channel->user->name, 0, 1)); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div class="font-black text-white tracking-tighter text-[15px] "><?php echo e($channel->user->fullname); ?></div>
                        <div class="text-[9px] text-white/30 font-black uppercase tracking-widest mt-1 ">UID: #<?php echo e($channel->user->id); ?></div>
                    </div>
                </div>
                <div class="mt-8 relative z-10">
                    <a href="<?php echo e(route('admin.users.detail', $channel->user->id)); ?>" class="w-full h-12 rounded-xl bg-white/10 hover:bg-white/20 text-white text-[9px] font-black uppercase tracking-[0.2em] flex items-center justify-center transition-all border border-white/5">
                        View User Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\admin\channels\view.blade.php ENDPATH**/ ?>