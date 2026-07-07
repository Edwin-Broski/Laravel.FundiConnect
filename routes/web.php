<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProviderWebController;
use App\Http\Controllers\Web\JobWebController;
use App\Http\Controllers\Web\AuthWebController;

// public
Route::get('/', [HomeController::class, 'index']);
Route::get('/providers', [ProviderWebController::class, 'index']);
Route::get('/providers/{provider}', [ProviderWebController::class, 'show']);

// auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthWebController::class, 'showLogin']);
    Route::post('/login', [AuthWebController::class, 'login']);
    Route::get('/register', [AuthWebController::class, 'showRegister']);
    Route::post('/register', [AuthWebController::class, 'register']);
});

Route::post('/logout', [AuthWebController::class, 'logout'])
     ->middleware('auth');

// customer (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/jobs', [JobWebController::class, 'index']);
    Route::get('/jobs/create/{provider}', [JobWebController::class, 'create']);
    Route::post('/jobs', [JobWebController::class, 'store']);
    Route::get('/jobs/{job}', [JobWebController::class, 'show']);
    Route::patch('/jobs/{job}/cancel', [JobWebController::class, 'cancel']);
    Route::patch('/jobs/{job}/confirm', [JobWebController::class, 'confirm']);
    Route::post('/jobs/{job}/review', [JobWebController::class, 'review']);
    Route::post('/jobs/{job}/messages', [JobWebController::class, 'sendMessage']);
});