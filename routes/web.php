<?php

use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\VideoController;
use App\Http\Controllers\Web\CommentController;
use App\Http\Controllers\Web\LikeController;
use App\Http\Controllers\Web\SubscriptionController;
use App\Http\Controllers\Web\ChannelController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\PlaylistController;
use App\Http\Controllers\Web\MembershipController;
use App\Http\Controllers\Web\ReelController;
use App\Http\Controllers\Web\BunnyUploadController;
use App\Http\Controllers\AnalyticsEventController;
use Illuminate\Support\Facades\Route;

Route::get('cron', [\App\Http\Controllers\CronController::class, 'index'])->name('cron');
Route::get('captcha-reload', [\App\Http\Controllers\Web\SiteController::class, 'captchaReload'])->name('captcha.reload');
Route::get('placeholder-image/{size}', [\App\Http\Controllers\Web\SiteController::class, 'placeholderImage'])->name('placeholder.image');
Route::get('manifest.json', [\App\Http\Controllers\Web\SiteController::class, 'pwaManifest'])->name('pwa.manifest');

// Google OAuth Routes
Route::get('auth/google', [\App\Http\Controllers\Web\GoogleController::class, 'googlePage'])->name('frontend.googlePage');
Route::get('auth/google/callback', [\App\Http\Controllers\Web\GoogleController::class, 'googleCallBack'])->name('frontend.googleCallBack');
Route::get('auth/google-signup', [\App\Http\Controllers\Web\GoogleController::class, 'googlePage'])->name('frontend.googleSignUpPage');
Route::get('auth/google-signup/callback', [\App\Http\Controllers\Web\GoogleController::class, 'googleCallBack'])->name('frontend.googleSignUp');


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category.show');
Route::get('/fetch-videos', [HomeController::class, 'fetchVideos'])->name('video.get');
Route::get('/trending', [VideoController::class, 'trending'])->name('trending');

Route::get('/ad/redirect/{slug}', [\App\Http\Controllers\Web\SiteController::class, 'bannerRedirect'])->name('banner.redirect');

// Video and Reel Status Polling (MUST be before videos.show to prevent route collision!)
Route::get('videos/{video}/status', [\App\Http\Controllers\Web\BunnyUploadController::class, 'checkStatus'])->name('videos.check_status');
Route::get('reels/{reel}/status', [\App\Http\Controllers\Web\BunnyUploadController::class, 'checkReelStatus'])->name('reels.check_status');

// Route::get('/videos/{video:id}/download', [VideoController::class, 'download'])->name('videos.download');
Route::post('/api/videos/{video}/impression', [VideoController::class, 'recordImpression']);
Route::post('/api/videos/{video}/ad-impression', [VideoController::class, 'recordAdImpression']);
Route::get('/videos/{video}/related-pagination', [VideoController::class, 'related'])->name('videos.related');
Route::get('/videos/{video}/{playlist?}', [VideoController::class, 'show'])->name('videos.show')->middleware('throttle_views');
Route::post('/videos/{video:id}/report', [VideoController::class, 'report'])->name('video.report')->middleware('auth');
Route::get('/playlists/{username}/{playlist}/watch/{video}', [VideoController::class, 'showInPlaylist'])->name('videos.show.in_playlist');
Route::post('/analytics/log-event', [AnalyticsEventController::class, 'logVideoEvent'])->name('analytics.log_event');
Route::post('/analytics/update-progress', [AnalyticsEventController::class, 'updateWatchProgress'])->name('analytics.update_progress');
Route::get('/playlists/v/{virtualSlug}/{video}', [VideoController::class, 'showVirtualPlaylist'])->name('videos.show.virtual');




Route::get('/live/{slug}', [\App\Http\Controllers\Web\LiveStreamController::class, 'show'])->name('live.show');

// Public Access Routes (Moved from auth group)
Route::get('/channels/create', [ChannelController::class, 'create'])->name('channels.create')->middleware('auth');
Route::post('/channels/check-availability', [ChannelController::class, 'checkAvailability'])->name('channels.check-availability')->middleware('auth');
Route::get('/channels/{channel}', [ChannelController::class, 'show'])->name('channels.show');
Route::post('/channels/{channel:id}/report', [ChannelController::class, 'report'])->name('channel.report')->middleware('auth');
Route::get('/@{username}', [ChannelController::class, 'showByUsername'])->name('channels.show_by_username');
Route::get('/ott-plans', [\App\Http\Controllers\User\OttPlanController::class, 'index'])->name('user.ott-plans.index');
Route::get('/mini-ott', [\App\Http\Controllers\User\PlanController::class, 'ott'])->name('premium');
Route::get('/mini-ott/all', [\App\Http\Controllers\User\PlanController::class, 'allPremiumVideos'])->name('premium.all');
Route::get('/mini-ott/trending', [\App\Http\Controllers\User\PlanController::class, 'trendingPremiumVideos'])->name('premium.trending');

// Phase 4 Monetization & Portfolio Preview Mapping
Route::controller(\App\Http\Controllers\Web\PreviewController::class)->prefix('preview')->name('preview.')->group(function () {
    Route::get('channel/{slug?}', 'channel')->name('channel');
    Route::get('playlist/{slug?}', 'playlist')->name('playlist');
    Route::get('playlist/videos/{playlistSlug?}/{userSlug?}', 'playlistVideos')->name('playlist.videos');
    Route::get('shorts/{slug?}', 'shorts')->name('shorts');
    Route::get('about/{slug?}', 'about')->name('about');
    Route::get('monthly-plan/{slug?}', 'monthlyPlan')->name('monthly.plan');
});

Route::controller(\App\Http\Controllers\User\PlanController::class)->prefix('plan')->name('plan.')->group(function () {
    Route::get('videos/{id}', 'viewPlanVideos')->name('videos');
    Route::get('playlist/videos/{id}', 'viewPlaylistVideos')->name('playlist.videos');
    Route::get('playlists/{id}', 'viewPlanPlaylists')->name('playlists');
});

Route::get('/search', [SearchController::class, 'index'])->name('search')->middleware('throttle:search');
Route::get('/api/search/suggestions', [\App\Http\Controllers\Api\SearchController::class, 'suggestions'])->name('api.search.suggestions');
Route::get('/api/search/mentions', [\App\Http\Controllers\Api\SearchController::class, 'mentions'])->name('api.search.mentions');
Route::get('/notifications', [HomeController::class, 'notifications'])->middleware('auth')->name('notifications');
Route::post('/notifications/read-all', [HomeController::class, 'readAllNotifications'])->middleware('auth')->name('notifications.read_all');

// ── Public Reels Feed ──
Route::get('/reels', [ReelController::class, 'index'])->name('reels.index');
Route::get('/reels/upload', [ReelController::class, 'create'])->middleware('auth')->name('reels.create');
Route::get('/reels/music/search', [ReelController::class, 'searchMusic'])->middleware('auth')->name('reels.music.search');
Route::get('/reels/{reel}', [ReelController::class, 'show'])->name('reels.show')->where('reel', '^(?!upload$|music).*$');
// Public Reel telemetry - guest accessible (ReelService supports user_id null, IP-based)
Route::post('/reels/{reel}/view', [ReelController::class, 'recordView'])->name('reels.record_view');
Route::post('/reels/{reel}/dwell', [ReelController::class, 'logDwell'])->name('reels.dwell');

Route::middleware('auth')->group(function () {
    Route::post('/user/block', [\App\Http\Controllers\Web\CommentModerationController::class, 'blockUser'])->name('user.block');
    Route::post('/comment/report', [\App\Http\Controllers\Web\CommentModerationController::class, 'reportComment'])->name('comment.report');
    
    // Phase 3 Channel Creation
    
    // Live Streaming
    Route::get('/live/start', [\App\Http\Controllers\Web\LiveStreamController::class, 'goLive'])->name('live.start');
    Route::post('/live/{stream}/chat', [\App\Http\Controllers\Web\LiveStreamController::class, 'sendMessage'])->name('live.chat');

    Route::get('/upload', [VideoController::class, 'create'])->name('videos.create');
    Route::post('/upload', [VideoController::class, 'store'])->name('videos.store');

    // Bunny Stream Direct Upload (TUS Protocol)
    Route::post('videos/auto-save-draft', [BunnyUploadController::class, 'autoSaveDraft'])->name('videos.auto_save_draft');
    Route::get('videos/active-drafts', [BunnyUploadController::class, 'activeDrafts'])->name('videos.active_drafts');
    Route::post('videos/prepare-upload', [BunnyUploadController::class, 'prepareUpload'])->name('videos.prepare_upload')->middleware('throttle:upload');
    Route::post('videos/{video:slug}/thumbnail', [BunnyUploadController::class, 'uploadThumbnail'])->name('videos.upload_thumbnail');
    Route::post('upload/log-error', [BunnyUploadController::class, 'logError'])->name('upload.log_error');
    Route::post('videos/log-draft-event', [BunnyUploadController::class, 'logDraftEvent'])->name('videos.log_draft_event');

    // Bunny Stream TUS Upload Routes for Reels
    Route::post('reels/prepare-upload', [BunnyUploadController::class, 'prepareReelUpload'])->name('reels.prepare_upload')->middleware('throttle:upload');
    Route::post('reels/{reel:slug}/thumbnail', [BunnyUploadController::class, 'uploadReelThumbnail'])->name('reels.upload_thumbnail');

    // Direct Upload (server-side forwarding to Bunny — works on all devices) — LEGACY kept for rollback
    Route::post('videos/direct-upload', [\App\Http\Controllers\Web\DirectUploadController::class, 'uploadVideo'])->name('videos.direct_upload');
    Route::post('reels/direct-upload', [\App\Http\Controllers\Web\DirectUploadController::class, 'uploadReel'])->name('reels.direct_upload');

    // ── S3 Presigned (Reels only) - Browser -> Bunny S3 direct, no Worker/cPanel bytes ──
    // Throttled, auth required, CSRF via web middleware. Browser never sees AccessKey.
    Route::post('reels/{reel}/presign', [\App\Http\Controllers\Api\ReelStorageAuthController::class, 'presign'])->name('reels.presign')->middleware('throttle:20,1');
    Route::post('reels/{reel}/confirm', [\App\Http\Controllers\Api\ReelStorageAuthController::class, 'confirm'])->name('reels.confirm')->middleware('throttle:20,1');
    Route::get('reels/{reel}/duet-token', [\App\Http\Controllers\Api\ReelStorageAuthController::class, 'duetToken'])->name('reels.duet_token')->middleware('throttle:30,1');
    
    Route::get('ffmpeg-proxy/{file}', function($file) {
        if (!in_array($file, ['ffmpeg.js', '814.ffmpeg.js'])) abort(404);
        $content = Cache::rememberForever('ffmpeg_proxy_' . $file, function() use ($file) {
            return Http::timeout(10)->get('https://unpkg.com/@ffmpeg/ffmpeg@0.12.10/dist/umd/' . $file)->body();
        });
        return response($content, 200, ['Content-Type' => 'application/javascript']);
    });
    Route::post('reels/{reel}/delete-temp', [\App\Http\Controllers\Api\ReelStorageAuthController::class, 'deleteTemp'])->name('reels.delete_temp')->middleware('throttle:20,1');
    Route::post('reels/wasm-status', [\App\Http\Controllers\Api\ReelStorageAuthController::class, 'wasmStatus'])->name('reels.wasm_status')->middleware('throttle:30,1');
    // Legacy Worker auth kept for rollback
    Route::post('reels/{reel}/storage-authorize', [\App\Http\Controllers\Api\ReelStorageAuthController::class, 'storageAuthorize'])->name('reels.storage_authorize')->middleware('throttle:20,1');


    // OTT Plans (Action routes remain protected)
    Route::controller(\App\Http\Controllers\User\OttPlanController::class)->prefix('ott-plans')->name('user.ott-plans.')->group(function () {
        Route::post('buy/{id}', 'buy')->name('buy');
        Route::post('verify', 'verify')->name('verify');
    });
    
    // Phase 3 Channel Creation
    Route::post('/channels', [ChannelController::class, 'store'])->name('channels.store');
    Route::post('/channels/{channel:id}/report', [ChannelController::class, 'report'])->name('channels.report');
    Route::post('/user/data-consent', [\App\Http\Controllers\User\UserController::class, 'saveConsent'])->name('user.save_consent');
    
    Route::get('/videos/{video}/comments/fetch', [CommentController::class, 'index'])->name('comments.fetch');
    Route::post('/videos/{video}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/like', [CommentController::class, 'toggleLike'])->name('comments.like');
    Route::post('/comments/{comment}/toggle-pin', [CommentController::class, 'togglePin'])->name('comments.toggle_pin');
    
    Route::post('/videos/{video}/like', [LikeController::class, 'toggle'])->name('videos.like');
    Route::delete('/videos/{video}/like', [LikeController::class, 'remove'])->name('videos.unlike');
    Route::post('/channel/{channel:id}/subscribe', [SubscriptionController::class, 'toggle'])->name('channels.subscribe');
    Route::post('/channel/{channel:id}/subscription-preference', [SubscriptionController::class, 'updatePreference'])->name('channels.subscribe.preference');


    Route::get('/playlists', [PlaylistController::class, 'index'])->name('playlists.index');
    Route::get('/playlists/{username}/{playlist}', [PlaylistController::class, 'show'])->name('playlists.show');
    Route::post('/playlists/toggle-video/{video}', [PlaylistController::class, 'toggleVideo'])->name('playlists.toggle-video');
    Route::post('/playlists/toggle-reel/{reel}', [PlaylistController::class, 'toggleReel'])->name('playlists.toggle-reel');
    Route::post('/watch-later/{id}', [PlaylistController::class, 'watchLater'])->name('watch-later.toggle');
    Route::delete('/watch-later/{id}', [PlaylistController::class, 'removeWatchLater'])->name('watch-later.remove');
    Route::post('/watch-later-reel/{reel}', [ReelController::class, 'watchLater'])->name('watch-later-reel.toggle');
    Route::get('/playlist-membership/{id}', [PlaylistController::class, 'getMembershipStatus'])->name('playlist-membership');


    Route::post('/playlists', [PlaylistController::class, 'store'])->name('playlists.store');
    Route::delete('/playlists/{playlist}', [PlaylistController::class, 'destroy'])->name('playlists.destroy');

    Route::post('/videos/{video:id}/playlist-toggle', [PlaylistController::class, 'toggleVideo'])->name('videos.playlist.toggle');
    Route::post('/videos/{video:id}/watch-later', [PlaylistController::class, 'watchLater'])->name('videos.watch-later');
    Route::post('/videos/{video:id}/not-interested', [VideoController::class, 'notInterested'])->name('videos.not-interested');
    Route::get('/videos/{id}/membership-status', [PlaylistController::class, 'getMembershipStatus']);




    // Memberships
    Route::post('/memberships/{membership}/join', [MembershipController::class, 'join'])->name('memberships.join');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Creator Studio
    Route::prefix('studio')->name('studio.')->group(function () {
        // Analytics Endpoints
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/overview', [\App\Http\Controllers\Web\StudioAnalyticsController::class, 'overview'])->name('overview');
            Route::get('/reach', [\App\Http\Controllers\Web\StudioAnalyticsController::class, 'reach'])->name('reach');
            Route::get('/audience', [\App\Http\Controllers\Web\StudioAnalyticsController::class, 'audience'])->name('audience');
            Route::get('/realtime', [\App\Http\Controllers\Web\StudioAnalyticsController::class, 'realtime'])->name('realtime');
        });

        Route::get('/dashboard', [\App\Http\Controllers\Web\StudioController::class, 'dashboard'])->name('dashboard');
        Route::get('/analytics', [\App\Http\Controllers\Web\StudioController::class, 'analytics'])->name('analytics');
        Route::get('/videos', [\App\Http\Controllers\Web\StudioController::class, 'videos'])->name('videos');
        Route::get('/videos/{video}/edit', [\App\Http\Controllers\Web\StudioController::class, 'edit'])->name('videos.edit');
        Route::put('/videos/{video}', [\App\Http\Controllers\Web\StudioController::class, 'update'])->name('videos.update');
        Route::delete('/videos/{video}/thumbnail', [\App\Http\Controllers\Web\StudioController::class, 'deleteThumbnail'])->name('videos.thumbnail.destroy');
        Route::delete('/videos/{video}', [\App\Http\Controllers\Web\StudioController::class, 'destroy'])->name('videos.destroy');
        Route::post('/videos/{video}/make-premium', [\App\Http\Controllers\Web\StudioController::class, 'makePremium'])->name('videos.make-premium');
        Route::post('/videos/{video}/toggle-featured', [\App\Http\Controllers\Web\StudioController::class, 'toggleFeatured'])->name('videos.toggle-featured');
        
        // Monetization
        Route::get('/monetization', [\App\Http\Controllers\Web\StudioController::class, 'monetization'])->name('monetization');
        Route::post('/monetization/buy', [\App\Http\Controllers\Web\StudioController::class, 'buyMonetization'])->name('monetization.buy');
        Route::post('/monetization/verify-payment', [\App\Http\Controllers\Web\StudioController::class, 'verifyMonetizationPayment'])->name('monetization.verify-payment');
        Route::post('/monetization/membership', [\App\Http\Controllers\Web\StudioController::class, 'storeMembership'])->name('memberships.store');
        Route::put('/monetization/membership/{membership}', [\App\Http\Controllers\Web\StudioController::class, 'updateMembership'])->name('memberships.update');
        Route::delete('/monetization/membership/{membership}', [\App\Http\Controllers\Web\StudioController::class, 'destroyMembership'])->name('memberships.destroy');

        // Reels
        Route::get('/reels', [\App\Http\Controllers\Web\StudioController::class, 'reels'])->name('reels');
        Route::get('/reels/{reel}/edit', [\App\Http\Controllers\Web\StudioController::class, 'editReel'])->name('reels.edit');
        Route::put('/reels/{reel}', [\App\Http\Controllers\Web\StudioController::class, 'updateReel'])->name('reels.update');
        Route::delete('/reels/{reel}', [\App\Http\Controllers\Web\StudioController::class, 'destroyReel'])->name('reels.destroy');
        Route::delete('/reels/{reel}/thumbnail', [\App\Http\Controllers\Web\StudioController::class, 'deleteReelThumbnail'])->name('reels.thumbnail.destroy');
        Route::get('/purchased-videos', [\App\Http\Controllers\Web\StudioController::class, 'purchasedVideos'])->name('purchased-videos');
        Route::get('/saved-audios', [\App\Http\Controllers\Web\StudioController::class, 'savedAudios'])->name('saved-audios');
        Route::get('/duets', [\App\Http\Controllers\Web\StudioController::class, 'duets'])->name('duets');
    });



    // Personal Features
    Route::get('/history', [VideoController::class, 'history'])->name('history');
    Route::post('/history/remove/{id}', [VideoController::class, 'removeHistory'])->name('history.remove');
    Route::post('/history/remove/reel/{id}', [VideoController::class, 'removeReelHistory'])->name('history.remove.reel');
    Route::post('/history/clear', [VideoController::class, 'clearHistory'])->name('history.clear');
    
    Route::get('/liked-videos', [VideoController::class, 'liked'])->name('liked-videos');
    Route::get('/watch-later', [VideoController::class, 'watchLater'])->name('watch-later');
    Route::post('/watch-later/clear', [\App\Http\Controllers\Web\PlaylistController::class, 'clearWatchLater'])->name('watch-later.clear');

    // ── Reel Actions ──
    Route::get('/reels/upload', [ReelController::class, 'create'])->name('reels.create');
    Route::post('/reels/upload', [ReelController::class, 'store'])->name('reels.store');
    Route::get('/reels/audio/{id}', [ReelController::class, 'audio'])->name('reels.audio');
    Route::get('/reels/{id}/info', [ReelController::class, 'info'])->name('reels.info');
    Route::get('/reels/{reel}', [ReelController::class, 'show'])->name('reels.show');
    Route::post('/reels/{reel}/like', [ReelController::class, 'like'])->name('reels.like');
    Route::delete('/reels/{reel}/like', [ReelController::class, 'removeLike'])->name('reels.unlike');
    Route::post('/reels/{slug}/save-audio', [\App\Http\Controllers\Web\ReelActionController::class, 'saveAudio'])->name('reels.save_audio');
    Route::get('/reels/{slug}/use-audio', [\App\Http\Controllers\Web\ReelActionController::class, 'useAudio'])->name('reels.use_audio');
    Route::get('/reels/{slug}/duet', [\App\Http\Controllers\Web\ReelActionController::class, 'duet'])->name('reels.duet');
    Route::post('/reels/{reel}/comment', [ReelController::class, 'comment'])->name('reels.comment');
    Route::put('/reels/comments/{comment}', [ReelController::class, 'updateComment'])->name('reels.comment.update');
    Route::delete('/reels/comments/{comment}', [ReelController::class, 'deleteComment'])->name('reels.comment.delete');
    Route::get('reels/{reel}/comments', [ReelController::class, 'getComments'])->name('reels.comments');
    Route::post('/reels/{reel}/report', [ReelController::class, 'report'])->name('reels.report');
    Route::post('/reels/{reel}/watch-later', [ReelController::class, 'watchLater'])->name('reels.watch-later');
    Route::post('/reels/{reel}/not-interested', [ReelController::class, 'notInterested'])->name('reels.not-interested');
    Route::post('/reels/comments/{comment}/like', [ReelController::class, 'likeComment'])->name('reels.comment.like');
    Route::post('/reels/comments/{comment}/toggle-pin', [ReelController::class, 'togglePinComment'])->name('reels.comment.toggle_pin');

    // ── Reel Action Logic ──
    Route::post('/reels/{slug}/save-audio', [\App\Http\Controllers\Web\ReelActionController::class, 'saveAudio'])->name('reels.save-audio');
    Route::post('/reels/save-audio-direct/{id}', [\App\Http\Controllers\Web\ReelActionController::class, 'saveAudioDirect'])->name('reels.save-audio-direct');
    Route::get('/reels/{slug}/use-audio', [\App\Http\Controllers\Web\ReelActionController::class, 'useAudio'])->name('reels.use-audio');
    Route::get('/reels/{slug}/duet', [\App\Http\Controllers\Web\ReelActionController::class, 'duet'])->name('reels.duet');

    // Video Purchase
    Route::post('/videos/{video}/create-order', [VideoController::class, 'createOrder'])->name('videos.create-order');
    Route::post('/videos/{video}/purchase', [VideoController::class, 'purchase'])->name('videos.purchase');
    
    // Firebase Token Updates
    Route::post('/update-fcm-token', [\App\Http\Controllers\Web\StudioController::class, 'updateFcmToken'])->name('update.fcm.token');
    Route::post('/profile/messages/firebase-token', [\App\Http\Controllers\Web\StudioController::class, 'updateFcmToken'])->name('profile.messages.firebase_token');



    Route::post('/moderation/notice/{type}/{id}/read', function($type, $id) {
        if ($type === 'Video') {
            \App\Models\Report::where('id', $id)->where('reported_user_id', auth()->id())->update(['is_notified' => true]);
        } else {
            \App\Models\ReelReport::where('id', $id)->whereHas('reel', function($q) {
                $q->where('user_id', auth()->id());
            })->update(['is_notified' => true]);
        }
        return response()->json(['success' => true]);
    })->name('moderation.notice.read');

    Route::post('/moderation/strike/{id}/read', function($id) {
        \App\Models\CopyrightStrike::where('id', $id)->where('user_id', auth()->id())->update(['is_read' => true]);
        return response()->json(['success' => true]);
    })->name('moderation.strike.read');

});

// Admin Panel
// Admin Dashboard
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('telemetry', [\App\Http\Controllers\Admin\AnalyticsController::class, 'telemetry'])->name('telemetry');
        Route::post('profile/firebase-token', [\App\Http\Controllers\Admin\DashboardController::class, 'updateFirebaseToken'])->name('profile.firebase_token');
        
        // Onboarding Slides Management
        Route::delete('onboarding-slides/destroy', [\App\Http\Controllers\Admin\OnboardingController::class, 'massDestroy'])->name('onboarding-slides.massDestroy');
        Route::resource('onboarding-slides', \App\Http\Controllers\Admin\OnboardingController::class);
        Route::get('chart/deposit-withdraw', [\App\Http\Controllers\Admin\DashboardController::class, 'depositAndWithdrawReport'])->name('chart.deposit.withdraw');
        Route::get('chart/transaction', [\App\Http\Controllers\Admin\DashboardController::class, 'transactionReport'])->name('chart.transaction');
        Route::get('check/space', [\App\Http\Controllers\Admin\DashboardController::class, 'checkSpace'])->name('check.space');
        Route::get('check/ffmpeg', [\App\Http\Controllers\Admin\GeneralSettingController::class, 'checkFFmpegInstallation'])->name('setting.check.ffmpeg');
        Route::match(['get', 'post'], '/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
        
        // User Management
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Advanced Analytics
        Route::get('/analytics/revenue', [\App\Http\Controllers\Admin\GrowthController::class, 'revenueReports'])->name('analytics.advanced.revenue');
        Route::get('/analytics/videos', [\App\Http\Controllers\Admin\GrowthController::class, 'videoPerformance'])->name('analytics.advanced.videos');
        Route::get('/analytics/audience', [\App\Http\Controllers\Admin\GrowthController::class, 'audienceAnalytics'])->name('analytics.advanced.audience');

        // Trust & Safety
        Route::get('/moderation/reports/videos', [\App\Http\Controllers\Admin\ModerationController::class, 'reportedVideos'])->name('moderation.reports.videos');
        Route::get('/moderation/reports/users', [\App\Http\Controllers\Admin\ModerationController::class, 'reportedUsers'])->name('moderation.reports.users');
        Route::get('/moderation/appeals', [\App\Http\Controllers\Admin\ModerationController::class, 'appeals'])->name('moderation.appeals');

        // Video Management
        Route::get('/video-commissions', [\App\Http\Controllers\Admin\VideoCommissionController::class, 'index'])->name('video.commissions');
        Route::get('/videos', [\App\Http\Controllers\Admin\VideoController::class, 'index'])->name('videos.index');
        Route::post('/videos/bulk', [\App\Http\Controllers\Admin\VideoController::class, 'bulk'])->name('videos.bulk');
        Route::get('/videos/draft', [\App\Http\Controllers\Admin\VideoController::class, 'draft'])->name('videos.draft');
        Route::get('/videos/featured', [\App\Http\Controllers\Admin\VideoController::class, 'featured'])->name('videos.featured');
        Route::get('/videos/premium', [\App\Http\Controllers\Admin\VideoController::class, 'premium'])->name('videos.premium');
        Route::get('/videos/liked', [\App\Http\Controllers\Admin\VideoController::class, 'liked'])->name('videos.liked');
        Route::get('/videos/trending', [\App\Http\Controllers\Admin\VideoController::class, 'trending'])->name('videos.trending');
        Route::get('/videos/public', [\App\Http\Controllers\Admin\VideoController::class, 'public'])->name('videos.public');
        Route::get('/videos/private', [\App\Http\Controllers\Admin\VideoController::class, 'private'])->name('videos.private');
        Route::post('/videos/{video:id}/toggle-trending', [\App\Http\Controllers\Admin\VideoController::class, 'toggleTrending'])->name('videos.toggle-trending');
        Route::get('/videos/create', [\App\Http\Controllers\Admin\VideoController::class, 'create'])->name('videos.create');
        Route::post('/videos/store', [\App\Http\Controllers\Admin\VideoController::class, 'store'])->name('videos.store');
        // Admin TUS flow (browser -> Bunny, mirrors frontend videos.prepare_upload)
        Route::post('/videos/prepare-upload', [\App\Http\Controllers\Admin\VideoController::class, 'prepareUpload'])->name('videos.prepare_upload');
        Route::post('/videos/direct-upload', [\App\Http\Controllers\Admin\VideoController::class, 'directUpload'])->name('videos.direct_upload');
        Route::post('/videos/{video:slug}/thumbnail', [\App\Http\Controllers\Admin\VideoController::class, 'uploadThumbnail'])->name('videos.upload_thumbnail');
        Route::post('/videos/log-error', [\App\Http\Controllers\Admin\VideoController::class, 'logError'])->name('videos.log_error');
        Route::get('/videos/{video:id}/show', [\App\Http\Controllers\Admin\VideoController::class, 'show'])->name('videos.show');
        Route::get('/videos/{video:id}/edit', [\App\Http\Controllers\Admin\VideoController::class, 'edit'])->name('videos.edit');
        Route::post('/videos/{video:id}/update', [\App\Http\Controllers\Admin\VideoController::class, 'update'])->name('videos.update');
        Route::post('/videos/{video:id}/approve', [\App\Http\Controllers\Admin\VideoController::class, 'approve'])->name('videos.approve');
        Route::post('/videos/{video:id}/reject', [\App\Http\Controllers\Admin\VideoController::class, 'reject'])->name('videos.reject');
        Route::delete('/videos/{video:id}', [\App\Http\Controllers\Admin\VideoController::class, 'destroy'])->name('videos.destroy');
        Route::post('/videos/{video:id}/toggle-featured', [\App\Http\Controllers\Admin\VideoController::class, 'toggleFeatured'])->name('videos.toggle-featured');
        Route::post('/videos/{video:id}/toggle-age-restricted', [\App\Http\Controllers\Admin\VideoController::class, 'toggleAgeRestriction'])->name('videos.toggle-age-restricted');
        Route::get('/not-interested', [\App\Http\Controllers\Admin\NotInterestedController::class, 'index'])->name('videos.not-interested.index');
        Route::post('/not-interested/destroy/{id}', [\App\Http\Controllers\Admin\NotInterestedController::class, 'destroy'])->name('videos.not-interested.destroy');

        // Video Interaction Management
        Route::controller(\App\Http\Controllers\Admin\VideoInteractionController::class)->prefix('videos')->name('videos.')->group(function () {
            Route::get('{video:id}/manage-likes', 'likes')->name('likes');
            Route::delete('likes/{id}/remove', 'destroyLike')->name('likes.destroy');
            Route::get('{video:id}/manage-comments', 'comments')->name('manage.comments');
            Route::get('{video:id}/manage-playlists', 'playlists')->name('manage.playlists');
            Route::post('{video:id}/playlist/remove', 'removeFromPlaylist')->name('playlist.remove');
            Route::get('{video:id}/manage-watch-later', 'watchLater')->name('manage.watch-later');
        });

        // Channel Management
        Route::get('/channels', [\App\Http\Controllers\Admin\ChannelController::class, 'index'])->name('channels.index');
        Route::post('/channels/bulk', [\App\Http\Controllers\Admin\ChannelController::class, 'bulk'])->name('channels.bulk');
        Route::get('/channels/create', [\App\Http\Controllers\Admin\ChannelController::class, 'create'])->name('channels.create');
        Route::post('/channels/store', [\App\Http\Controllers\Admin\ChannelController::class, 'store'])->name('channels.store');
        Route::get('/channels/{channel}/show', [\App\Http\Controllers\Admin\ChannelController::class, 'show'])->name('channels.show');
        Route::get('/channels/{channel}/edit', [\App\Http\Controllers\Admin\ChannelController::class, 'edit'])->name('channels.edit');
        Route::post('/channels/{channel}/update', [\App\Http\Controllers\Admin\ChannelController::class, 'update'])->name('channels.update');
        Route::post('/channels/{channel}/toggle-status', [\App\Http\Controllers\Admin\ChannelController::class, 'toggleStatus'])->name('channels.status');
        Route::delete('/channels/{channel}', [\App\Http\Controllers\Admin\ChannelController::class, 'destroy'])->name('channels.destroy');

        // Comments Management
        Route::get('/comments', [\App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
        Route::get('/comments/create', [\App\Http\Controllers\Admin\CommentController::class, 'create'])->name('comments.create');
        Route::post('/comments/store', [\App\Http\Controllers\Admin\CommentController::class, 'store'])->name('comments.store');
        Route::get('/comments/{comment}/show', [\App\Http\Controllers\Admin\CommentController::class, 'show'])->name('comments.show');
        Route::get('/comments/{comment}/edit', [\App\Http\Controllers\Admin\CommentController::class, 'edit'])->name('comments.edit');
        Route::post('/comments/{comment}/update', [\App\Http\Controllers\Admin\CommentController::class, 'update'])->name('comments.update');
        Route::delete('/comments/{comment}', [\App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy');

        // Reports Management
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/{report}/strike', [\App\Http\Controllers\Admin\ReportController::class, 'strike'])->name('reports.strike');
        Route::post('/reports/{report}/resolve', [\App\Http\Controllers\Admin\ReportController::class, 'resolve'])->name('reports.resolve');
        Route::delete('/reports/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'destroy'])->name('reports.destroy');
        Route::delete('/reports/{report}/content', [\App\Http\Controllers\Admin\ReportController::class, 'destroyContent'])->name('reports.content.destroy');

        // Analytics Dashboard
        Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');

        // System Settings
        Route::get('/settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'update'])->name('settings.update');

        // Security & Announcements
        Route::get('/security', [\App\Http\Controllers\Admin\SecurityController::class, 'index'])->name('security.index');
        Route::post('/security/announcements', [\App\Http\Controllers\Admin\SecurityController::class, 'storeAnnouncement'])->name('security.announcements.store');
        Route::post('/security/announcements/{announcement}/toggle', [\App\Http\Controllers\Admin\SecurityController::class, 'toggleAnnouncement'])->name('security.announcements.toggle');
        Route::delete('/security/announcements/{announcement}', [\App\Http\Controllers\Admin\SecurityController::class, 'destroyAnnouncement'])->name('security.announcements.destroy');
        Route::post('/security/ip-block', [\App\Http\Controllers\Admin\SecurityController::class, 'blockIp'])->name('security.ip.block');
        Route::delete('/security/ip-unblock/{id}', [\App\Http\Controllers\Admin\SecurityController::class, 'unblockIp'])->name('security.ip.unblock');
        Route::get('/security/strikes', [\App\Http\Controllers\Admin\SecurityController::class, 'strikes'])->name('security.strikes.index');
        Route::post('/security/strikes', [\App\Http\Controllers\Admin\SecurityController::class, 'issueStrike'])->name('security.strikes.store');
        Route::post('/security/strikes/{id}/resolve', [\App\Http\Controllers\Admin\SecurityController::class, 'resolveStrike'])->name('security.strikes.resolve');

        // Blacklist Management
        Route::get('/security/blacklist', [\App\Http\Controllers\Admin\SecurityController::class, 'blacklist'])->name('security.blacklist.index');
        Route::post('/security/blacklist', [\App\Http\Controllers\Admin\SecurityController::class, 'storeBlacklist'])->name('security.blacklist.store');
        Route::delete('/security/blacklist/{id}', [\App\Http\Controllers\Admin\SecurityController::class, 'destroyBlacklist'])->name('security.blacklist.destroy');

        // Growth & Acquisition
        Route::get('/growth', [\App\Http\Controllers\Admin\GrowthController::class, 'index'])->name('growth.index');

        // Advanced Analytics
        Route::get('/analytics/revenue', [\App\Http\Controllers\Admin\GrowthController::class, 'revenueReports'])->name('analytics.advanced.revenue');
        Route::get('/analytics/videos', [\App\Http\Controllers\Admin\GrowthController::class, 'videoPerformance'])->name('analytics.advanced.videos');
        Route::get('/analytics/audience', [\App\Http\Controllers\Admin\GrowthController::class, 'audienceAnalytics'])->name('analytics.advanced.audience');

        // Trust & Safety
        Route::get('/moderation/reports/videos', [\App\Http\Controllers\Admin\ModerationController::class, 'reportedVideos'])->name('moderation.reports.videos');
        Route::get('/moderation/reports/users', [\App\Http\Controllers\Admin\ModerationController::class, 'reportedUsers'])->name('moderation.reports.users');
        Route::get('/moderation/reports/reels', [\App\Http\Controllers\Admin\ModerationController::class, 'reportedReels'])->name('moderation.reports.reels');
        Route::post('/moderation/reports/reels/{id}/handle', [\App\Http\Controllers\Admin\ModerationController::class, 'handleReelReport'])->name('moderation.reports.reels.handle');
        Route::get('/moderation/strikes', [\App\Http\Controllers\Admin\ModerationController::class, 'struckUsers'])->name('moderation.strikes');
        Route::get('/moderation/strikes/remove/{id}', [\App\Http\Controllers\Admin\ModerationController::class, 'removeStrike'])->name('moderation.strikes.remove');
        Route::get('/moderation/appeals', [\App\Http\Controllers\Admin\ModerationController::class, 'appeals'])->name('moderation.appeals');
        Route::post('/moderation/reports/{id}/handle', [\App\Http\Controllers\Admin\ModerationController::class, 'handleReport'])->name('moderation.reports.handle');

        // Comment Reports
        Route::controller(\App\Http\Controllers\Admin\CommentReportController::class)->prefix('comment-reports')->name('comment.reports.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{id}/destroy', 'destroy')->name('destroy');
            Route::post('/{id}/delete-comment', 'deleteComment')->name('delete.comment');
        });

        // General Setting Extensions
        Route::controller(\App\Http\Controllers\Admin\GeneralSettingController::class)->prefix('settings')->name('setting.')->group(function () {
            Route::get('keyword-blacklist', 'keywordBlacklist')->name('keyword.blacklist');
            Route::post('keyword-blacklist', 'storeKeyword')->name('keyword.store');
            Route::delete('keyword-blacklist/{id}', 'deleteKeyword')->name('keyword.delete');
        });

        // Video Processing Management
        Route::get('/processing', [\App\Http\Controllers\Admin\VideoProcessingController::class, 'index'])->name('processing.index');
        Route::get('/processing/create', [\App\Http\Controllers\Admin\VideoProcessingController::class, 'create'])->name('processing.create');
        Route::get('/processing/edit/{id}', [\App\Http\Controllers\Admin\VideoProcessingController::class, 'edit'])->name('processing.edit');
        Route::get('/processing/show/{id}', [\App\Http\Controllers\Admin\VideoProcessingController::class, 'show'])->name('processing.show');
        Route::post('/processing/options/{option}/toggle', [\App\Http\Controllers\Admin\VideoProcessingController::class, 'toggleOption'])->name('processing.toggle-option');
        Route::post('/processing/jobs/{job}/retry', [\App\Http\Controllers\Admin\VideoProcessingController::class, 'retryJob'])->name('processing.retry-job');
        Route::delete('/processing/jobs/{job}', [\App\Http\Controllers\Admin\VideoProcessingController::class, 'destroyJob'])->name('processing.destroy-job');

        // Monetization Management
        Route::get('/monetization', [\App\Http\Controllers\Admin\MonetizationController::class, 'index'])->name('monetization.index');
        Route::post('/monetization/toggle-global', [\App\Http\Controllers\Admin\MonetizationController::class, 'toggleGlobal'])->name('monetization.toggle-global');
        Route::post('/monetization/update-settings', [\App\Http\Controllers\Admin\MonetizationController::class, 'updateSettings'])->name('monetization.update-settings');
        Route::post('/monetization/ads', [\App\Http\Controllers\Admin\MonetizationController::class, 'storeAd'])->name('monetization.ads.store');
        Route::post('/monetization/ads/{ad}/toggle', [\App\Http\Controllers\Admin\MonetizationController::class, 'toggleAd'])->name('monetization.ads.toggle');
        Route::delete('/monetization/ads/{ad}', [\App\Http\Controllers\Admin\MonetizationController::class, 'destroyAd'])->name('monetization.ads.destroy');

        // Plan Management
        Route::get('/plans', [\App\Http\Controllers\Admin\PlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/create', [\App\Http\Controllers\Admin\PlanController::class, 'create'])->name('plans.create');
        Route::post('/plans/store', [\App\Http\Controllers\Admin\PlanController::class, 'store'])->name('plans.store');
        Route::get('/plans/{plan}/edit', [\App\Http\Controllers\Admin\PlanController::class, 'edit'])->name('plans.edit');
        Route::post('/plans/{plan}/update', [\App\Http\Controllers\Admin\PlanController::class, 'update'])->name('plans.update');
        Route::post('/plans/{plan}/toggle-status', [\App\Http\Controllers\Admin\PlanController::class, 'toggleStatus'])->name('plans.status');
        Route::delete('/plans/{plan}', [\App\Http\Controllers\Admin\PlanController::class, 'destroy'])->name('plans.destroy');

        // OTT Plan Management
        Route::controller(\App\Http\Controllers\Admin\OttPlanController::class)->prefix('ott-plans')->name('ott-plans.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
                    Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
            Route::delete('delete/{id}', 'delete')->name('delete');
            Route::get('subscriptions', 'subscriptions')->name('subscriptions');
        });

        // Banner Ads Management
        Route::controller(\App\Http\Controllers\Admin\BannerAdController::class)->prefix('banner-ads')->name('banners.')->group(function () {
            Route::get('/{slot}', 'index')->name('index');
            Route::get('/{slot}/create', 'create')->name('create');
            Route::post('/{slot}/store', 'store')->name('store');
            Route::get('/{slot}/edit/{id}', 'edit')->name('edit');
            Route::post('/{slot}/update/{id}', 'update')->name('update');
            Route::post('/status/{id}', 'status')->name('status');
            Route::delete('/destroy/{id}', 'destroy')->name('destroy');
        });
        
        // Reel Management
        Route::prefix('reels')->name('reels.')->group(function () {
            Route::controller(\App\Http\Controllers\Admin\ManageReelController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/bulk', 'bulk')->name('bulk');
                Route::get('/published', 'published')->name('published');
                Route::get('/draft', 'draft')->name('draft');
                Route::get('/rejected', 'rejected')->name('rejected');
                Route::get('/trending', 'trending')->name('trending');
                Route::get('/liked', 'liked')->name('liked');
                Route::get('/duets', 'duets')->name('duets');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                // Admin Reel TUS/S3/ffmpeg flow (mirrors frontend reels.prepare_upload)
                Route::post('/prepare-upload', 'prepareUpload')->name('prepare_upload');
                Route::post('/direct-upload', 'directUpload')->name('direct_upload');
                Route::post('/{reel}/presign', 'presign')->name('presign');
                Route::post('/{reel}/confirm', 'confirm')->name('confirm');
                Route::post('/{reel}/thumbnail', 'uploadThumbnail')->name('upload_thumbnail');
                Route::post('/log-error', 'logError')->name('log_error');
                Route::post('/wasm-status', 'wasmStatus')->name('wasm_status');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::post('/{id}/update', 'update')->name('update');
                Route::post('/{id}/approve', 'approve')->name('approve');
                Route::post('/{id}/reject', 'reject')->name('reject');
                Route::delete('/{id}', 'destroy')->name('destroy');
                Route::delete('/{id}/thumbnail', 'deleteThumbnail')->name('thumbnail.destroy');
                Route::post('/{id}/toggle-trending', 'toggleTrending')->name('toggle-trending');
            });

            Route::controller(\App\Http\Controllers\Admin\ManageSavedAudioController::class)->prefix('saved-audios')->name('saved-audios.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        });

        // Export Routes
        Route::get('/export/{module}/{format}', [\App\Http\Controllers\Admin\ExportController::class, 'export'])->name('export');

        // Load Legacy Admin Routes
        Route::namespace('App\Http\Controllers\Admin')->group(function () {
            require __DIR__.'/admin-legacy.php';
        });

    });
});

// Admin Auth
Route::prefix('admin')->name('admin.')->middleware(['guest:admin'])->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [\App\Http\Controllers\Admin\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Admin\AuthController::class, 'register'])->middleware('throttle:5,1');
    
    Route::get('/forgot-password', [\App\Http\Controllers\Admin\AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Admin\AuthController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:5,1');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Admin\AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Admin\AuthController::class, 'resetPassword'])->name('password.store')->middleware('throttle:5,1');
});

// Support and Static Pages
Route::get('/settings', function() { return view('frontend.static.settings'); })->name('settings');
Route::get('/help', function() { return view('frontend.static.help'); })->name('help');

Route::controller(\App\Http\Controllers\Web\SiteController::class)->group(function () {
    Route::get('/policy/{id}/{slug}', 'policyPages')->name('policy.pages');
    Route::get('/pages/{slug}', 'pages')->name('pages');
});

Route::get('/maintenance', function () {
    if (gs('maintenance_mode') == 0) return redirect()->route('home');
    return view('frontend.maintenance');
})->name('maintenance');

Route::get('/update-required', function () {
    return view('frontend.update_required');
})->name('update.required');

Route::get('/update-now', function () {
    session(['app_version' => gs('app_version')]);
    return redirect()->route('home');
})->name('update.now');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// Talent Marketplace
Route::prefix('marketplace')->name('marketplace.')->group(function () {
    Route::get('/', [\App\Http\Controllers\MarketPlaceController::class, 'index'])->name('index');
    Route::get('/all', [\App\Http\Controllers\MarketPlaceController::class, 'all'])->name('all');
    Route::get('/featured', [\App\Http\Controllers\MarketPlaceController::class, 'featured'])->name('featured');
    Route::get('/portfolio/{slug}', [\App\Http\Controllers\MarketPlaceController::class, 'portfolio'])->name('portfolio');
    Route::get('/terms', function() {
        $pageTitle = "Marketplace Terms & Conditions";
        $terms = \App\Models\Frontend::where('data_keys', 'marketplace_terms.content')->first();
        
        // Auto-seed if not exists to save the user from re-typing
        if (!$terms) {
            $terms = new \App\Models\Frontend();
            $terms->data_keys = 'marketplace_terms.content';
            $terms->data_values = [
                'title' => 'Marketplace Terms & Conditions',
                'content' => '<h3>01. Introduction</h3><p>The Binteo Profile Marketplace is a specialized platform designed to bridge the gap between creative talent (Actors, Influencers) and strategic partners (Investors, Producers). By creating a profile on this marketplace, users agree to abide by the following terms and professional standards.</p><h3>02. Role-Specific Guidelines</h3><h4>A. Actors & Influencers</h4><ul><li><strong>Accuracy:</strong> All information provided in the profile, including work experience, portfolio links, and social media metrics, must be authentic. Any misrepresentation will lead to immediate profile suspension.</li><li><strong>Content Ownership:</strong> Users must hold the legal rights to all photos, videos, and media uploaded to their portfolio. Intellectual property violations are strictly prohibited.</li></ul><h4>B. Investors & Producers</h4><ul><li><strong>Professional Conduct:</strong> Investors are expected to maintain professional decorum when contacting talent.</li><li><strong>Transparency:</strong> When offering projects or collaborations, investors must provide clear terms regarding the scope of work and expectations.</li></ul><h3>03. General Terms of Use</h3><ul><li><strong>Verification:</strong> Binteo reserves the right to verify any profile. We may request identification documents or proof of work to maintain the integrity of the marketplace.</li><li><strong>Direct Transactions:</strong> Binteo acts as a discovery platform. Any agreements or financial transactions made outside of the platform are at the users\' own risk; Binteo is not liable for external disputes.</li><li><strong>Prohibited Content:</strong> Profiles must not contain nudity, violence, hate speech, or politically inflammatory content.</li><li><strong>Data Privacy:</strong> Users must respect the privacy of others. Scraping data or using contact information for unsolicited spam is strictly forbidden.</li></ul><h3>04. Payments & Service Fees</h3><p>Any fees paid for premium marketplace listings or subscription plans are non-refundable. Binteo serves as a facilitator and does not guarantee employment or investment for any user.</p><h3>05. Profile Termination</h3><p>Binteo reserves the right to terminate or shadow-ban profiles without prior notice in cases of: creation of fake or duplicate profiles, harassment of other community members, or repeated violations of platform guidelines.</p>'
            ];
            $terms->save();
        }
        
        return view('frontend.marketplace.terms', compact('pageTitle', 'terms'));
    })->name('terms');
});

// Short Portfolio URL (Stand-alone to match requested /m/slug style)
Route::get('/m/{slug}', [\App\Http\Controllers\MarketPlaceController::class, 'portfolio'])->name('marketplace.portfolio.short');
Route::get('/marketplace/gallery', [\App\Http\Controllers\MarketPlaceController::class, 'gallery'])->name('marketplace.gallery');

Route::prefix('marketplace')->name('marketplace.')->group(function () {
    
    // Auth (No guest middleware to allow standard users to also log in as talent)
    Route::group(['middleware' => 'throttle:5,1'], function () {
        Route::get('/register', [\App\Http\Controllers\MarketPlaceController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [\App\Http\Controllers\MarketPlaceController::class, 'register']);
        Route::get('/login', [\App\Http\Controllers\MarketPlaceController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [\App\Http\Controllers\MarketPlaceController::class, 'login']);
        Route::post('/check-availability', [\App\Http\Controllers\MarketPlaceController::class, 'checkAvailability'])->name('check-availability');
    });

    // Protected Marketplace Routes
    Route::middleware([\App\Http\Middleware\MarketplaceAuth::class])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\MarketPlaceController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [\App\Http\Controllers\MarketPlaceController::class, 'logout'])->name('logout');
        
        // Asset Management
        Route::post('/gallery', [\App\Http\Controllers\MarketPlaceController::class, 'galleryStore'])->name('gallery.store')->middleware('throttle:upload');
        Route::post('/services', [\App\Http\Controllers\MarketPlaceController::class, 'serviceStore'])->name('service.store')->middleware('throttle:upload');
        Route::post('/avatar', [\App\Http\Controllers\MarketPlaceController::class, 'updateAvatar'])->name('avatar.update')->middleware('throttle:upload');
        Route::post('/profile/update', [\App\Http\Controllers\MarketPlaceController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/delete', [\App\Http\Controllers\MarketPlaceController::class, 'destroyProfile'])->name('profile.delete');
        
        // Portfolio Management
        Route::post('/portfolio', [\App\Http\Controllers\MarketPlaceController::class, 'portfolioStore'])->name('portfolio.store');
        Route::match(['post', 'delete'], '/portfolio/delete/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'portfolioDelete'])->name('portfolio.delete');
        Route::match(['post', 'delete'], '/portfolio/image/delete/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'portfolioImageDelete'])->name('portfolio.image.delete');
        
        // Subscriptions
        Route::get('/plan/{id}/preview', [\App\Http\Controllers\MarketPlaceController::class, 'previewPlan'])->name('plan.preview');
        Route::post('/plan/buy/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'buyPlan'])->name('plan.buy');
        Route::post('/plan/verify', [\App\Http\Controllers\MarketPlaceController::class, 'verifyPlanPayment'])->name('plan.verify');

        // Content Management
        Route::post('/gallery/update/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'updateGallery'])->name('gallery.update');
        Route::post('/gallery/delete/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'deleteGallery'])->name('gallery.delete');
        Route::post('/service/update/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'updateService'])->name('service.update');
        Route::post('/service/delete/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'deleteService'])->name('service.delete');
        Route::post('/contact/delete/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'deleteContact'])->name('contact.delete');
        Route::post('/message/reply/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'replyMessage'])->name('message.reply');
        Route::post('/message/update/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'updateMessage'])->name('message.update');
        Route::post('/message/delete/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'deleteMessage'])->name('message.delete');
        Route::post('/messages/read', [\App\Http\Controllers\MarketPlaceController::class, 'markMessagesRead'])->name('messages.read');
    });
});

Route::post('/marketplace/contact/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'storeContact'])->name('market.contact.store');
Route::post('/marketplace/message/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'sendMessage'])->name('marketplace.message.send');
Route::post('/marketplace/user-message/update/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'userUpdateMessage'])->name('marketplace.user_message.update');
Route::post('/marketplace/user-message/delete/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'userDeleteMessage'])->name('marketplace.user_message.delete');
Route::post('/marketplace/rating/{id}', [\App\Http\Controllers\MarketPlaceController::class, 'rateMarketplace'])->name('marketplace.rating.submit')->middleware('auth');

// Payment Infrastructure
Route::controller(\App\Http\Controllers\Gateway\PaymentController::class)->group(function () {
    Route::get('/deposit', 'deposit')->name('user.deposit');
    Route::post('/deposit', 'depositInsert')->name('user.deposit.insert');
    Route::get('/deposit/confirm', 'depositConfirm')->name('user.deposit.confirm');
});

// Gateway IPN Hooks
Route::post('/ipn/razorpay', [\App\Http\Controllers\Gateway\Razorpay\ProcessController::class, 'ipn'])->name('ipn.Razorpay');

// Bunny Stream Webhook (public - called by Bunny servers)
Route::match(['get', 'post'], '/webhooks/bunny', [\App\Http\Controllers\BunnyWebhookController::class, 'handle'])->name('webhooks.bunny');

// TUS Upload Proxy (same-origin proxy for mobile compatibility)
Route::prefix('tus-proxy')->middleware(['auth'])->withoutMiddleware([\App\Http\Middleware\XssSanitization::class])->group(function () {
    Route::options('/{videoId?}', [\App\Http\Controllers\TusProxyController::class, 'options']);
    Route::post('/', [\App\Http\Controllers\TusProxyController::class, 'create']);
    Route::match(['patch'], '/{videoId}', [\App\Http\Controllers\TusProxyController::class, 'patch']);
    Route::match(['head'], '/{videoId}', [\App\Http\Controllers\TusProxyController::class, 'head']);
});

// Telemetry Endpoints
Route::prefix('api/telemetry')->name('telemetry.')->group(function () {
    Route::post('/ping', [\App\Http\Controllers\AnalyticsController::class, 'pingVideo'])->name('ping');
    Route::post('/session', [\App\Http\Controllers\AnalyticsController::class, 'pingSession'])->name('session');
    Route::post('/log-bunny', function (\Illuminate\Http\Request $request) {
        $logData = sprintf("[%s] VideoID: %s, BunnyID: %s, Msg: %s, URL: %s\n",
            now()->toDateTimeString(),
            $request->video_id,
            $request->bunny_id,
            $request->message,
            $request->url
        );
        \Illuminate\Support\Facades\File::append(storage_path('logs/bunny_ping.log'), $logData);
        return response()->json(['success' => true]);
    })->name('log_bunny');
});
Route::get('/auth/bridge', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'bridgeLogin'])->name('auth.bridge');

require __DIR__.'/auth.php';
require __DIR__.'/user-legacy.php';


