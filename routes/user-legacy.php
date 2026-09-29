<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\WithdrawController;
use App\Http\Controllers\User\PlanController;
use App\Http\Controllers\Web\TicketController;
use App\Http\Controllers\Gateway\PaymentController;

use App\Http\Controllers\User\AdvertiserController;

Route::middleware('auth')->prefix('client')->name('user.')->group(function () {
    
    // Advertiser Section
    Route::controller(AdvertiserController::class)->prefix('advertiser')->name('advertiser.')->group(function () {
        Route::get('dashboard', 'home')->name('dashboard');
        Route::get('chart-data', 'adsChart')->name('chart.data');
        Route::post('data-submit', 'dataSubmit')->name('data.submit');
        
        Route::prefix('ad')->name('ad.')->group(function () {
            Route::get('list', 'adList')->name('list');
            Route::get('advance-list', 'advanceAdList')->name('advance.list');
            Route::get('create', 'createAd')->name('create');
            Route::post('upload-video', 'uploadAdVideo')->name('upload.video');
            Route::post('checkout/{id}', 'processedCheckout')->name('checkout');
            Route::post('status/{id}', 'status')->name('status');
        });

        Route::get('payment-history', 'paymentHistory')->name('payment.history');
    });

    // Dashboard & Home (Removed)
    // Monetization
    Route::controller(UserController::class)->prefix('monetization')->name('monetization.')->group(function () {
        Route::get('/', 'monetizationSetting')->name('index');
        Route::post('/apply', 'applyForMonetization')->name('apply');
    });

    // Plans / Subscriptions
    Route::controller(PlanController::class)->prefix('plans')->name('plans.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/buy/{id}', 'buy')->name('buy');
        Route::get('/purchased-plans', 'purchasedPlanLists')->name('purchased');
        Route::get('/invoice/{id}', 'invoice')->name('invoice');
        Route::post('/verify-payment', 'verifyPayment')->name('verify.payment');
    });

    // Withdrawals
    Route::controller(WithdrawController::class)->prefix('withdraw')->name('withdraw.')->group(function () {
        Route::get('/methods', 'withdrawMethod')->name('methods');
        Route::post('/submit', 'withdrawMethodSubmit')->name('submit');
        Route::get('/log', 'withdrawLog')->name('log');
    });

    // Deposits
    Route::controller(PaymentController::class)->prefix('deposit')->name('deposit.')->group(function () {
        Route::get('/history', 'depositHistory')->name('history');
    });

    // Tickets
    Route::controller(TicketController::class)->prefix('ticket')->name('ticket.')->group(function () {
        Route::get('/', 'ticketIndex')->name('index');
        Route::get('/create', 'ticketCreate')->name('create');
        Route::post('/store', 'ticketStore')->name('store');
        Route::get('/view/{id}', 'ticketView')->name('view');
        Route::post('/reply/{id}', 'ticketReply')->name('reply');
        Route::post('/close/{id}', 'ticketClose')->name('close');
        Route::get('/download/{id}', 'ticketDownload')->name('download');
        Route::get('/hub-data/{id}', 'ticketHubData')->name('hub.data');
    });

    // Earnings & Reports
    Route::controller(UserController::class)->group(function () {
        Route::get('/earnings', 'earnings')->name('earnings');
        Route::get('/notifications', 'notifications')->name('notifications');
        Route::get('/transactions', 'transactions')->name('transactions');
        
        // KYC
        Route::get('kyc-form', 'kycForm')->name('kyc.form');
        Route::get('kyc-data', 'kycData')->name('kyc.data');
        Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');
        Route::get('kyc-bank-edit', 'kycBankEdit')->name('kyc.bank.edit');
        Route::post('kyc-bank-update', 'kycBankUpdate')->name('kyc.bank.update');
        Route::post('kyc-check-availability', 'checkKycAvailability')->name('kyc.check-availability');
    });

});
