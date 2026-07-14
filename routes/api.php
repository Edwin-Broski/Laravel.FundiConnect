<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DisputeController;
use App\Http\Controllers\Api\JobRequestController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\TradeController;
use Illuminate\Support\Facades\Route;

// public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/trades', [TradeController::class, 'index']);
Route::get('/providers', [ProviderController::class, 'index']);
Route::get('/providers/{provider}', [ProviderController::class, 'show']);
Route::get('/providers/{provider}/reviews', [ProviderController::class, 'reviews']);

// authenticated routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // customer
    Route::post('/jobs', [JobRequestController::class, 'store']);
    Route::get('/jobs', [JobRequestController::class, 'customerJobs']);
    Route::get('/jobs/{jobRequest}', [JobRequestController::class, 'show']);
    Route::patch('/jobs/{jobRequest}/cancel', [JobRequestController::class, 'cancel']);
    Route::patch('/jobs/{jobRequest}/confirm', [JobRequestController::class, 'confirmComplete']);
    Route::post('/jobs/{jobRequest}/review', [ReviewController::class, 'store']);
    Route::post('/jobs/{jobRequest}/dispute', [DisputeController::class, 'store']);
    Route::get('/jobs/{jobRequest}/messages', [MessageController::class, 'index']);
    Route::post('/jobs/{jobRequest}/messages', [MessageController::class, 'store']);
    // customer - post open job (no provider selected)
    Route::post('/jobs/open', [JobRequestController::class, 'storeOpen']);

    // customer - view applications on their job
    Route::get('/jobs/{jobRequest}/applications', [JobRequestController::class, 'applications']);

    // customer - accept a provider's application
    Route::patch('/jobs/{jobRequest}/applications/{application}/accept', [JobRequestController::class, 'acceptApplication']);

    // provider - see all open jobs in their trade area
    Route::middleware('is.provider')->group(function () {
        Route::get('/provider/open-jobs', [JobRequestController::class, 'openJobs']);
        Route::post('/provider/jobs/{jobRequest}/apply', [JobRequestController::class, 'apply']);
    });
    // customer profile
    Route::get('/customer/profile', [ProviderController::class, 'myProfile']);
    Route::patch('/customer/profile', [ProviderController::class, 'updateProfile']);

    // provider routes
    Route::middleware('is.provider')->prefix('provider')->group(function () {
        Route::get('/profile', [ProviderController::class, 'myProfile']);
        Route::patch('/profile', [ProviderController::class, 'updateProfile']);
        Route::patch('/availability', [ProviderController::class, 'toggleAvailability']);
        Route::get('/stats', [ProviderController::class, 'stats']);
        Route::get('/jobs', [JobRequestController::class, 'providerJobs']);
        Route::patch('/jobs/{jobRequest}/accept', [JobRequestController::class, 'accept']);
        Route::patch('/jobs/{jobRequest}/decline', [JobRequestController::class, 'decline']);
        Route::patch('/jobs/{jobRequest}/start', [JobRequestController::class, 'start']);
        Route::patch('/jobs/{jobRequest}/complete', [JobRequestController::class, 'complete']);
        Route::get('/jobs/{jobRequest}/messages', [MessageController::class, 'index']);
        Route::post('/jobs/{jobRequest}/messages', [MessageController::class, 'store']);
    });

    // admin routes
    Route::middleware('is.admin')->prefix('admin')->group(function () {
        Route::get('/providers/pending', [AdminController::class, 'pendingProviders']);
        Route::patch('/providers/{provider}/approve', [AdminController::class, 'approveProvider']);
        Route::patch('/providers/{provider}/reject', [AdminController::class, 'rejectProvider']);
        Route::patch('/providers/{provider}/suspend', [AdminController::class, 'suspendProvider']);
        Route::get('/disputes', [AdminController::class, 'disputes']);
        Route::patch('/disputes/{dispute}/resolve', [AdminController::class, 'resolveDispute']);
        Route::get('/stats', [AdminController::class, 'stats']);
    });
});
