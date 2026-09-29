<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1/auth')->group(function () {
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/google-login', [ApiAuthController::class, 'googleLogin']);
    Route::post('/forgot-password', [ApiAuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [ApiAuthController::class, 'resetPassword']);
});

Route::get('/v1/onboarding', [App\Http\Controllers\OnboardingController::class, 'getSlides']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/v1/auth/logout', [ApiAuthController::class, 'logout']);
    Route::post('/v1/update-fcm-token', [\App\Http\Controllers\Web\StudioController::class, 'updateFcmToken']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

