<?php

use App\Http\Controllers\Admin\MarketController;
use App\Http\Controllers\Admin\UserPlanController;
use Illuminate\Support\Facades\Route;

// All routes here will be grouped under the 'admin' prefix, 'auth|admin' middleware, and 'App\Http\Controllers\Admin' namespace

    Route::controller(\App\Http\Controllers\Admin\AdminController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard.legacy');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications', 'notifications')->name('notifications');
        Route::get('notification/read/{id}', 'notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'readAllNotification')->name('notifications.read.all');
        Route::post('notifications/delete-all', 'deleteAllNotification')->name('notifications.delete.all');
        Route::post('notifications/delete-single/{id}', 'deleteSingleNotification')->name('notifications.delete.single');
        // Direct GET visits to POST-only delete URLs go back to the list
        // instead of a 405 crash page (deletes never run on GET).
        Route::get('notifications/delete-all', function () {
            return redirect()->route('admin.notifications');
        });
        Route::get('notifications/delete-single/{id}', function () {
            return redirect()->route('admin.notifications');
        });

        //Report Bugs
        Route::get('request-report', 'requestReport')->name('request.report');
        Route::post('request-report', 'reportSubmit');

        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
    });

    // Users Manager
    Route::controller(\App\Http\Controllers\Admin\ManageUsersController::class)
        ->name('users.')
        ->prefix('users')
        ->group(function () {
            Route::get('/', 'allUsers')->name('all');
            Route::get('regular', 'regularUsers')->name('regular');
            Route::get('creators', 'creators')->name('creators');
            Route::get('active', 'activeUsers')->name('active');
            Route::get('banned', 'bannedUsers')->name('banned');
            Route::get('policy-violators', 'policyViolators')->name('policy.violators');
            Route::get('email-verified', 'emailVerifiedUsers')->name('email.verified');
            Route::get('email-unverified', 'emailUnverifiedUsers')->name('email.unverified');
            Route::get('mobile-unverified', 'mobileUnverifiedUsers')->name('mobile.unverified');
            Route::get('kyc-unverified', 'kycUnverifiedUsers')->name('kyc.unverified');
            Route::get('kyc-pending', 'kycPendingUsers')->name('kyc.pending');
            Route::get('mobile-verified', 'mobileVerifiedUsers')->name('mobile.verified');
            Route::get('with-balance', 'usersWithBalance')->name('with.balance');
            Route::get('monetization-request', 'monetizationRequest')->name('monetization.request');
            Route::get('monetization-chart/{id}', 'monetizationChart')->name('monetization.chart');
            Route::post('monetization-approve/{id}', 'monetizationApprove')->name('monetization.approve');
            Route::post('monetization-reject/{id}', 'monetizationReject')->name('monetization.reject');

            Route::get('detail/{id}', 'detail')->name('detail');
            Route::get('monetization-detail/{id}', 'monetizationDetail')->name('monetization.detail');
            Route::get('kyc-data/{id}', 'kycDetails')->name('kyc.details');
            Route::get('kyc-edit/{id}', 'kycEdit')->name('kyc.edit');
            Route::post('kyc-update/{id}', 'kycUpdate')->name('kyc.update');
            Route::post('kyc-approve/{id}', 'kycApprove')->name('kyc.approve');
            Route::post('kyc-reject/{id}', 'kycReject')->name('kyc.reject');
            Route::post('kyc-delete/{id}', 'kycDelete')->name('kyc.delete');

            Route::get('kyc-all', 'kycAll')->name('kyc.all');
            Route::get('kyc-create', 'kycCreate')->name('kyc.create');
            Route::post('kyc-store', 'kycStore')->name('kyc.store');
            Route::get('kyc-pending-list', 'kycPendingList')->name('kyc.pending.list');
            Route::get('kyc-approved-list', 'kycApprovedList')->name('kyc.approved.list');
            Route::get('kyc-rejected-list', 'kycRejectedList')->name('kyc.rejected.list');
            Route::post('kyc-check-availability', 'checkKycAvailability')->name('kyc.check-availability');

            Route::get('create', 'add')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            // Direct GET visits to the POST-only update URL (refresh/paste)
            // go back to the edit form instead of a 405 crash page.
            Route::get('update/{id}', function ($id) {
                return redirect()->route('admin.users.edit', $id);
            });

            Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single.form');
            Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single.send');
            Route::get('login/{id}', 'login')->name('login');
            Route::post('status/{id}', 'status')->name('status');
            Route::post('bulk', 'bulk')->name('bulk');
            Route::post('add-sub-balance/{id}', 'addSubBalance')->name('add.sub.balance');
            Route::post('add-plan/{id}', 'addPlan')->name('add.plan');

            Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
            Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');
            Route::get('list', 'list')->name('list');
            Route::get('count-by-segment/{methodName}', 'countBySegment')->name('segment.count');
            Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');

            // Deletion requests
            Route::get('deletion/requests', 'deletionRequests')->name('deletion.requests');
            Route::post('deletion/approve/{id}', 'approveDeletion')->name('deletion.approve');
            Route::post('deletion/reject/{id}', 'rejectDeletion')->name('deletion.reject');
            Route::get('deletion/logs', 'deletionLogs')->name('deletion.logs');
        });

    // category
    Route::controller(\App\Http\Controllers\Admin\CategoryController::class)->prefix('categories')->name('category.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('save/{id?}', 'save')->name('save');
        Route::post('status/{id}', 'status')->name('status');
        Route::delete('delete/{id}', 'destroy')->name('delete');
        Route::get('check-slug', 'checkSlug')->name('check.slug');
        // Direct GET visits to the POST-only status URL go back to the list
        // instead of a 405 crash page (nothing is toggled on GET).
        Route::get('status/{id}', function () {
            return redirect()->route('admin.category.index');
        });
    });






        Route::controller(UserPlanController::class)
    ->prefix('userplans')
    ->name('userplans.')
    ->group(function () {
        // Display a list of all user plans
        Route::get('/', 'index')->name('index');

        // Show the form to create a new user plan
        Route::get('create', 'create')->name('create');

        // Store a new user plan in the database
        Route::post('store', 'store')->name('store');

        // Show the form to edit a specific user plan
        Route::get('edit/{userplan}', 'edit')->name('edit');

        // Update an existing user plan in the database
        Route::post('update/{userplan}', 'update')->name('update');

        // Delete a user plan from the database
        Route::post('delete/{userplan}', 'destroy')->name('destroy');
    });


    // category
    Route::controller(\App\Http\Controllers\Admin\VideoResolutionController::class)
        ->prefix('resolutions')
        ->name('resolutions.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('save/{id?}', 'save')->name('save');
            Route::post('status/{id}', 'status')->name('status');
            Route::delete('delete/{id}', 'destroy')->name('delete');
        });

    // manage video
    Route::controller(\App\Http\Controllers\Admin\ManageVideoController::class)
        ->prefix('videos')
        ->name('videos.')
        ->group(function () {
            Route::get('all/{user_id?}', 'index')->name('index_legacy');
            // Route::get('create', 'create')->name('create');
            // Route::post('store', 'store')->name('store');
            Route::get('regular/{user_id?}', 'regular')->name('regular');
            Route::get('shorts/{user_id?}', 'shorts')->name('shorts');
            Route::get('published', 'published')->name('published');
            Route::get('rejected', 'rejected')->name('rejected_legacy');

            Route::get('stock/{user_id?}', 'stock')->name('stock');
            Route::get('free/{user_id?}', 'free')->name('free');

            // Route::get('public/{user_id?}', 'public')->name('public');
            // Route::get('private/{user_id?}', 'private')->name('private');

            // Route::get('edit/{id}', 'edit')->name('edit');
            // Route::post('update/{id}', 'update')->name('update');
            Route::get('check-slug', 'checkSlug')->name('check.slug');

            Route::get('analytics/{id?}', 'analytics')->name('analytics');
            Route::get('filter-data/{id?}', 'filterData')->name('filter.data');

            Route::get('featured', 'featured')->name('featured');
            Route::get('trending', 'trending')->name('trending');
            Route::post('toggle-featured/{id}', 'toggleFeatured')->name('toggle.featured');
            Route::post('toggle-trending/{id}', 'toggleTrending')->name('toggle.trending');
        });

    // manage Channel
    Route::controller(\App\Http\Controllers\Admin\ChannelController::class)
        ->prefix('channels')
        ->name('channels.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('featured', 'featured')->name('featured');
            Route::get('trending', 'trending')->name('trending');
            Route::post('toggle-featured/{id}', 'toggleFeatured')->name('toggle.featured');
            Route::post('toggle-trending/{id}', 'toggleTrending')->name('toggle.trending');
        });

    // Analytics Dashboard
    Route::controller(\App\Http\Controllers\Admin\AnalyticsController::class)
        ->prefix('analytics')
        ->name('analytics.')
        ->group(function () {
            Route::get('dashboard', 'dashboard')->name('dashboard');
            Route::get('monetization', 'monetization')->name('monetization');
            Route::get('creators', 'creators')->name('creators');
            Route::get('content', 'content')->name('content');
            Route::get('retention', 'retention')->name('retention');
            Route::get('technical', 'technical')->name('technical');
            Route::get('google', 'google')->name('google');
        });

    // not interested
    Route::controller(\App\Http\Controllers\Admin\NotInterestedController::class)
        ->prefix('not-interested')
        ->name('videos.not-interested.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('destroy/{id}', 'destroy')->name('destroy');
        });


    // manage Playlist
    Route::controller(\App\Http\Controllers\Admin\ManagePlaylistController::class)
        ->prefix('playlist')
        ->name('playlist.')
        ->group(function () {
            Route::get('all', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('show/{id}', 'show')->name('show');
            Route::delete('destroy/{id}', 'destroy')->name('destroy');
            Route::get('videos-list/{id}', 'videosList')->name('videos.list');
            Route::get('user-videos/{userId}', 'getUserVideos')->name('user-videos');
        });

    // manage Playlist
    Route::controller(\App\Http\Controllers\Admin\ManagePlanController::class)->prefix('plan')->name('plan.')->group(function () {
        Route::get('all', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('videos-list/{id}', 'videosList')->name('videos.list');
        Route::get('playlist-list/{id}', 'playlistList')->name('playlist.list');
        Route::post('status/{id}', 'status')->name('status');
    });

    /*
    Route::controller(\App\Http\Controllers\Admin\ManageAdvertiserController::class)
        ->name('advertiser.')
        ->prefix('advertiser')
        ->group(function () {
            Route::get('pending', 'pending')->name('pending');
            Route::get('approved', 'approved')->name('approved');
            Route::get('rejected', 'rejected')->name('rejected');
            Route::get('detail/{id}', 'detail')->name('detail');
            Route::get('report/{id}', 'report')->name('report');
            Route::post('data-reject/{id}', 'dataReject')->name('data.reject');
            Route::post('data-approve/{id}', 'dataApprove')->name('data.approve');

            Route::get('advertiser-setting', 'setting')->name('setting');
            Route::post('advertiser-setting', 'settingUpdate');
        });
    */

    /*
    Route::controller(\App\Http\Controllers\Admin\ManageAdvertisementController::class)
        ->name('advertisement.')
        ->prefix('advertisement')
        ->group(function () {
            Route::get('all/{id?}', 'index')->name('index');
            Route::get('type/Impression/{id?}', 'impression')->name('impression');
            Route::get('type/click/{id?}', 'click')->name('click');
            Route::get('type/both/{id?}', 'both')->name('both');
            Route::get('running/{id?}', 'running')->name('running');
            Route::get('pause/{id?}', 'pause')->name('pause');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id?}', 'edit')->name('edit');
            Route::get('show/{id}', 'show')->name('show');
            Route::post('update/{id?}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
            Route::delete('delete/{id}', 'destroy')->name('delete');
        });
    */


    Route::controller(\App\Http\Controllers\Admin\ManageStorageController::class)->prefix('storage')->name('storage.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('wasabi-form/{id?}', 'wasabiForm')->name('wasabi.form');
        Route::post('save-wasabi/{id?}', 'saveWasabi')->name('save.wasabi');

        Route::get('digital-ocean', 'digitalOcean')->name('digital.ocean');
        Route::get('digital-ocean-form/{id?}', 'digitalOceanForm')->name('digital.ocean.form');
        Route::post('save-digital-ocean/{id?}', 'saveDigitalOcean')->name('save.digital.ocean');

        Route::get('ftp', 'ftp')->name('ftp');
        Route::get('ftp-from/{id?}', 'ftpForm')->name('ftp.form');
        Route::post('save-ftp/{id?}', 'saveFtp')->name('save.ftp');

        Route::post('status/{id}', 'status')->name('status');

        Route::get('check-config/{id}', 'checkConfig')->name('check.config');
    });

    /*
    Route::controller(\App\Http\Controllers\Admin\CampaignController::class)
        ->prefix('campaign')
        ->name('campaign.')
        ->group(function () {
            Route::get('active', 'active')->name('active');
            Route::get('inactive', 'inactive')->name('inactive');
            Route::get('detail/{id}', 'detail')->name('detail');
            Route::get('create', 'create')->name('create');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('status/{id}', 'status')->name('status');
            Route::get('/{id?}', 'index')->name('index');
        });
    */


    /*
        Route::controller(\App\Http\Controllers\Admin\AdvanceAdsController::class)->prefix('advance-ads')->name('advance.ads.')->group(function () {
            Route::get('index/{id?}', 'index')->name('index');
            Route::get('pending/{id?}', 'pending')->name('pending');
            Route::get('rejected/{id?}', 'rejected')->name('rejected');
            Route::get('running/{id?}', 'running')->name('running');
            Route::get('pause/{id?}', 'pause')->name('pause');
            Route::get('expired/{id?}', 'expired')->name('expired');
            Route::get('detail/{id}', 'detail')->name('detail');
            Route::post('status/{id}', 'status')->name('status');
            Route::post('approved/{id?}', 'approved')->name('approved');
            Route::post('reject/{id?}', 'reject')->name('reject');
            Route::delete('delete/{id}', 'destroy')->name('delete');
        });
    */



    // Subscriber
    Route::controller(\App\Http\Controllers\Admin\SubscriberController::class)
        ->prefix('subscriber')
        ->name('subscriber.')
        ->group(function () {
            Route::get('/{id?}', 'index')->name('index');
        });

    // Deposit Gateway
    Route::name('gateway.')
        ->prefix('gateway')
        ->group(function () {
            // Automatic Gateway
            Route::controller(\App\Http\Controllers\Admin\AutomaticGatewayController::class)
                ->prefix('automatic')
                ->name('automatic.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('edit/{alias}', 'edit')->name('edit');
                    Route::post('update/{code}', 'update')->name('update');
                    Route::post('remove/{id}', 'remove')->name('remove');
                    Route::post('status/{id}', 'status')->name('status');
                });

            // Manual Methods
            Route::controller(\App\Http\Controllers\Admin\ManualGatewayController::class)
                ->prefix('manual')
                ->name('manual.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('new', 'create')->name('create');
                    Route::post('new', 'store')->name('store');
                    Route::get('edit/{alias}', 'edit')->name('edit');
                    Route::post('update/{id}', 'update')->name('update');
                    Route::post('status/{id}', 'status')->name('status');
                });
        });

    // DEPOSIT SYSTEM
    Route::controller(\App\Http\Controllers\Admin\DepositController::class)
        ->prefix('deposit')
        ->name('deposit.')
        ->group(function () {
            Route::get('all/{user_id?}', 'deposit')->name('list');
            Route::get('pending/{user_id?}', 'pending')->name('pending');
            Route::get('rejected/{user_id?}', 'rejected')->name('rejected');
            Route::get('approved/{user_id?}', 'approved')->name('approved');
            Route::get('successful/{user_id?}', 'successful')->name('successful');
            Route::get('initiated/{user_id?}', 'initiated')->name('initiated');
            Route::get('details/{id}', 'details')->name('details');
            Route::post('reject', 'reject')->name('reject');
            Route::post('approve/{id}', 'approve')->name('approve');
            Route::delete('destroy/{id}', 'destroy')->name('destroy');
        });

    // WITHDRAW SYSTEM
    Route::name('withdraw.')
        ->prefix('withdraw')
        ->group(function () {
        Route::controller(\App\Http\Controllers\Admin\WithdrawalController::class)
                ->name('data.')
                ->group(function () {

                    Route::get('pending/{user_id?}', 'pending')->name('pending');
                    Route::get('approved/{user_id?}', 'approved')->name('approved');
                    Route::get('rejected/{user_id?}', 'rejected')->name('rejected');
                    Route::get('all/{user_id?}', 'all')->name('all');
                    Route::get('create', 'create')->name('create');
                    Route::post('store', 'store')->name('store');
                    Route::get('details/{id}', 'details')->name('details');
                    Route::post('approve', 'approve')->name('approve');
                    Route::post('reject', 'reject')->name('reject');
                });

            Route::delete('destroy/{id}', [\App\Http\Controllers\Admin\WithdrawalController::class, 'destroy'])->name('destroy');

            // Withdraw Method
            Route::controller(\App\Http\Controllers\Admin\WithdrawMethodController::class)
                ->prefix('method')
                ->name('method.')
                ->group(function () {
                    Route::get('/', 'methods')->name('index');
                    Route::get('create', 'create')->name('create');
                    Route::post('create', 'store')->name('store');
                    Route::get('edit/{id}', 'edit')->name('edit');
                    Route::get('image/{id}', 'image')->name('image');
                    Route::post('edit/{id}', 'update')->name('update');
                    Route::post('status/{id}', 'status')->name('status');
                    Route::delete('delete/{id}', 'destroy')->name('delete');
                });
        });

    // Report
    Route::controller(\App\Http\Controllers\Admin\ReportController::class)->prefix('report')->name('report.')->group(function () {
        Route::get('transaction/{user_id?}', 'transaction')->name('transaction');
        Route::get('playlist-purchased-history', 'playlistPurchasedHistory')->name('playlist.history');
        Route::get('plan-purchased-history', 'planPurchasedHistory')->name('plan.history');
        Route::get('watch-history', 'watchHistory')->name('watch.history');
        Route::get('login/history', 'loginHistory')->name('login.history');
        Route::get('login/ipHistory/{ip}', 'loginIpHistory')->name('login.ipHistory');
        Route::get('notification/history', 'notificationHistory')->name('notification.history');
        Route::get('email/detail/{id}', 'emailDetails')->name('email.details');
    });

    // Admin Support
    Route::controller(\App\Http\Controllers\Admin\SupportTicketController::class)
        ->prefix('ticket')
        ->name('ticket.')
        ->group(function () {
            Route::get('/', 'tickets')->name('index');
            Route::get('pending', 'pendingTicket')->name('pending');
            Route::get('closed', 'closedTicket')->name('closed');
            Route::get('answered', 'answeredTicket')->name('answered');
            Route::get('view/{id}', 'ticketReply')->name('view');
            Route::post('reply/{id}', 'replyTicket')->name('reply');
            Route::post('close/{id}', 'closeTicket')->name('close');
            Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
            Route::match(['POST', 'DELETE'], 'delete/{id}', 'ticketDelete')->name('delete');
            Route::match(['POST', 'DELETE'], 'message/delete/{id}', 'ticketMessageDelete')->name('message.delete');
            Route::get('hub-data/{id}', 'ticketHubData')->name('hub.data');
            Route::post('hub-status/{id}', 'ticketUpdateStatus')->name('hub.status');
        });

    // Language Manager
    Route::controller(\App\Http\Controllers\Admin\LanguageController::class)
        ->prefix('language')
        ->name('language.')
        ->group(function () {
            Route::get('/', 'langManage')->name('manage');
            Route::post('/', 'langStore')->name('manage.store');
            Route::post('delete/{id}', 'langDelete')->name('manage.delete');
            Route::post('update/{id}', 'langUpdate')->name('manage.update');
            Route::get('edit/{id}', 'langEdit')->name('key');
            Route::post('import', 'langImport')->name('import.lang');
            Route::post('store/key/{id}', 'storeLanguageJson')->name('store.key');
            Route::post('delete/key/{id}', 'deleteLanguageJson')->name('delete.key');
            Route::post('update/key/{id}', 'updateLanguageJson')->name('update.key');
            Route::get('get-keys', 'getKeys')->name('get.key');
        });

    Route::controller(\App\Http\Controllers\Admin\GeneralSettingController::class)->group(function () {
        Route::get('system-setting', 'systemSetting')->name('setting.system');

        // General Setting
        Route::get('general-setting', 'general')->name('setting.general');
        Route::post('general-setting', 'generalUpdate');

        Route::get('ad-setting', 'adSetting')->name('setting.ad');
        Route::post('ad-setting', 'adSettingUpdate');

        Route::get('setting/social/credentials', 'socialiteCredentials')->name('setting.socialite.credentials');
        Route::post('setting/social/credentials/update/{key}', 'updateSocialiteCredential')->name('setting.socialite.credentials.update');
        Route::post('setting/social/credentials/status/{key}', 'updateSocialiteCredentialStatus')->name('setting.socialite.credentials.status.update');

        //configuration
        Route::get('setting/system-configuration', 'systemConfiguration')->name('setting.system.configuration');
        Route::post('setting/system-configuration', 'systemConfigurationSubmit');

        // Logo-Icon
        Route::get('setting/logo-icon', 'logoIcon')->name('setting.logo.icon.form');
        Route::post('setting/logo-icon', 'logoIconUpdate')->name('setting.logo.icon.update');

        //Custom CSS
        Route::get('custom-css', 'customCss')->name('setting.custom.css');
        Route::post('custom-css', 'customCssSubmit');

        Route::get('sitemap', 'sitemap')->name('setting.sitemap');
        Route::post('sitemap', 'sitemapSubmit');

        Route::get('robot', 'robot')->name('setting.robot');
        Route::post('robot', 'robotSubmit');

        //Cookie
        Route::get('cookie', 'cookie')->name('setting.cookie');
        Route::post('cookie', 'cookieSubmit');

        //maintenance_mode
        Route::get('maintenance-mode', 'maintenanceMode')->name('maintenance.mode');
        Route::post('maintenance-mode', 'maintenanceModeSubmit')->name('maintenance.mode.submit');

        //Holiday
        Route::get('holiday-setting', 'holiday')->name('setting.holiday');
        Route::post('holiday-setting/submit', 'holidaySubmit')->name('setting.holiday.submit');
        Route::post('holiday-remove/{id}', 'remove')->name('setting.remove');

        //Charge
        Route::get('charge-setting', 'charge')->name('setting.charge');
        Route::post('charge-setting', 'chargeSetting')->name('setting.charge.submit');

        //Offday
        Route::post('offday-setting', 'offDaySubmit')->name('setting.offday');
    });

    Route::controller(\App\Http\Controllers\Admin\CronConfigurationController::class)
        ->name('cron.')
        ->prefix('cron')
        ->group(function () {
            Route::get('index', 'cronJobs')->name('index');
            Route::post('store', 'cronJobStore')->name('store');
            Route::post('update', 'cronJobUpdate')->name('update');
            Route::post('delete/{id}', 'cronJobDelete')->name('delete');
            Route::get('schedule', 'schedule')->name('schedule');
            Route::post('schedule/store', 'scheduleStore')->name('schedule.store');
            Route::post('schedule/status/{id}', 'scheduleStatus')->name('schedule.status');
            Route::get('schedule/pause/{id}', 'schedulePause')->name('schedule.pause');
            Route::get('schedule/logs/{id}', 'scheduleLogs')->name('schedule.logs');
            Route::post('schedule/log/resolved/{id}', 'scheduleLogResolved')->name('schedule.log.resolved');
            Route::post('schedule/log/flush/{id}', 'logFlush')->name('log.flush');
        });

    //KYC setting
    Route::controller(\App\Http\Controllers\Admin\KycController::class)->group(function () {
        Route::get('kyc-setting', 'setting')->name('kyc.setting');
        Route::post('kyc-setting', 'settingUpdate');
    });

    //Notification Setting
    Route::name('setting.notification.')
        ->controller(\App\Http\Controllers\Admin\NotificationController::class)
        ->prefix('notification')
        ->group(function () {
            //Template Setting
            Route::get('global/email', 'globalEmail')->name('global.email');
            Route::post('global/email/update', 'globalEmailUpdate')->name('global.email.update');

            Route::get('global/sms', 'globalSms')->name('global.sms');
            Route::post('global/sms/update', 'globalSmsUpdate')->name('global.sms.update');

            Route::get('global/push', 'globalPush')->name('global.push');
            Route::post('global/push/update', 'globalPushUpdate')->name('global.push.update');

            Route::get('templates', 'templates')->name('templates');
            Route::get('template/edit/{type}/{id}', 'templateEdit')->name('template.edit');
            Route::post('template/update/{type}/{id}', 'templateUpdate')->name('template.update');

            //Email Setting
            Route::get('email/setting', 'emailSetting')->name('email');
            Route::post('email/setting', 'emailSettingUpdate');
            Route::post('email/test', 'emailTest')->name('email.test');

            //SMS Setting
            Route::get('sms/setting', 'smsSetting')->name('sms');
            Route::post('sms/setting', 'smsSettingUpdate');
            Route::post('sms/test', 'smsTest')->name('sms.test');

            Route::get('notification/push/setting', 'pushSetting')->name('push');
            Route::post('notification/push/setting', 'pushSettingUpdate');
            Route::post('notification/push/setting/upload', 'pushSettingUpload')->name('push.upload');
            Route::get('notification/push/setting/download', 'pushSettingDownload')->name('push.download');

            //ftp

        });

    // Plugin
    Route::controller(\App\Http\Controllers\Admin\ExtensionController::class)
        ->prefix('extensions')
        ->name('extensions.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
        });

    //System Information
    Route::controller(\App\Http\Controllers\Admin\SystemController::class)
        ->name('system.')
        ->prefix('system')
        ->group(function () {
            Route::get('info', 'systemInfo')->name('info');
            Route::get('server-info', 'systemServerInfo')->name('server.info');
            Route::get('optimize', 'optimize')->name('optimize');
            Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
            Route::get('system-update', 'systemUpdate')->name('update');
            Route::post('system-update', 'systemUpdateProcess')->name('update.process');
            Route::get('system-update/log', 'systemUpdateLog')->name('update.log');
        });

    // SEO
    Route::get('seo', [\App\Http\Controllers\Admin\FrontendController::class, 'seoEdit'])->name('seo');

    // Frontend
    Route::name('frontend.')
        ->prefix('frontend')
        ->group(function () {
            Route::controller(\App\Http\Controllers\Admin\FrontendController::class)->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('templates', 'templates')->name('templates');
                Route::post('templates', 'templatesActive')->name('templates.active');
                Route::get('frontend-sections/{key?}', 'frontendSections')->name('sections');
                Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
                Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
                Route::get('frontend-slug-check/{key}/{id?}', 'frontendElementSlugCheck')->name('sections.element.slug.check');
                Route::get('frontend-element-seo/{key}/{id}', 'frontendSeo')->name('sections.element.seo');
                Route::post('frontend-element-seo/{key}/{id}', 'frontendSeoUpdate');
                Route::post('remove/{id}', 'remove')->name('remove');
            });

            // Page Builder
            Route::controller(\App\Http\Controllers\Admin\PageBuilderController::class)->group(function () {
                Route::get('manage-pages', 'managePages')->name('manage.pages');
                Route::get('manage-pages/check-slug/{id?}', 'checkSlug')->name('manage.pages.check.slug');
                Route::post('manage-pages', 'managePagesSave')->name('manage.pages.save');
                Route::post('manage-pages/update', 'managePagesUpdate')->name('manage.pages.update');
                Route::post('manage-pages/delete/{id}', 'managePagesDelete')->name('manage.pages.delete');
                Route::get('manage-section/{id}', 'manageSection')->name('manage.section');
                Route::post('manage-section/{id}', 'manageSectionUpdate')->name('manage.section.update');

                Route::get('manage-seo/{id}', 'manageSeo')->name('manage.pages.seo');
                Route::post('manage-seo/{id}', 'manageSeoStore');
            });
        });


    // manage Marketplace
    Route::controller(MarketController::class)
        ->prefix('marketplace')
        ->name('marketplace.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('bulk', 'bulk')->name('bulk');
            Route::get('actors', 'actors')->name('actors');
            Route::get('influencers', 'influencers')->name('influencers');
            Route::get('investors', 'investors')->name('investors');
            
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('show/{id}', 'show')->name('show');
            Route::post('status/{id}', 'status')->name('status');
            Route::delete('delete/{id}', 'destroy')->name('destroy');
            Route::post('featured/{id}', 'toggleFeatured')->name('toggle-featured');
            Route::get('login/{id}', 'loginAsTalent')->name('login.as.talent');

            // Sub-modules
            Route::controller(\App\Http\Controllers\Admin\MarketGalleryController::class)->name('gallery.')->prefix('gallery')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('update/{id}', 'update')->name('update');
                Route::delete('delete/{id}', 'destroy')->name('destroy');
            });

            Route::controller(\App\Http\Controllers\Admin\MarketServiceController::class)->name('services.')->prefix('services')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('update/{id}', 'update')->name('update');
                Route::delete('delete/{id}', 'destroy')->name('destroy');
            });
            Route::get('transactions', [\App\Http\Controllers\Admin\MarketTransactionController::class, 'index'])->name('transactions');
            Route::get('ratings', [MarketController::class, 'ratings'])->name('ratings.index');
            Route::get('ratings/create', [MarketController::class, 'createRating'])->name('ratings.create');
            Route::post('ratings/store', [MarketController::class, 'storeRating'])->name('ratings.store');
            Route::get('ratings/edit/{id}', [MarketController::class, 'editRating'])->name('ratings.edit');
            Route::post('ratings/update/{id}', [MarketController::class, 'updateRating'])->name('ratings.update');
            Route::delete('ratings/delete/{id}', [MarketController::class, 'deleteRating'])->name('ratings.delete');

            // Portfolios
            Route::controller(\App\Http\Controllers\Admin\MarketPortfolioController::class)->name('portfolio.')->prefix('portfolio')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('update/{id}', 'update')->name('update');
                Route::delete('delete/{id}', 'destroy')->name('destroy');
                Route::post('status/{id}', 'status')->name('status');
                Route::delete('image/delete/{id}', 'deleteImage')->name('image.delete');
            });

            Route::get('contacts', [\App\Http\Controllers\Admin\MarketContactController::class, 'index'])->name('contacts.index');
            Route::get('contacts/create', [\App\Http\Controllers\Admin\MarketContactController::class, 'create'])->name('contacts.create');
            Route::post('contacts/store', [\App\Http\Controllers\Admin\MarketContactController::class, 'store'])->name('contacts.store');
            Route::get('contacts/show/{id}', [\App\Http\Controllers\Admin\MarketContactController::class, 'show'])->name('contacts.show');
            Route::get('contacts/edit/{id}', [\App\Http\Controllers\Admin\MarketContactController::class, 'edit'])->name('contacts.edit');
            Route::post('contacts/update/{id}', [\App\Http\Controllers\Admin\MarketContactController::class, 'update'])->name('contacts.update');
            Route::delete('contacts/delete/{id}', [\App\Http\Controllers\Admin\MarketContactController::class, 'destroy'])->name('contacts.destroy');
        });

