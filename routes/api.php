<?php

use App\Http\Controllers\AdminSubscriptionController;
use App\Http\Controllers\UserSubscriptionController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    // User-specific routes
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // User subscription routes
    Route::get('/subscriptions', [UserSubscriptionController::class, 'index']);
    Route::post('/subscriptions/choose-subscription/{subscriptionId}', [UserSubscriptionController::class, 'chooseSubscription']);

    // Publication routes
    Route::apiResource('publications', PublicationController::class);
    Route::patch('/publications/{publication}/activate', [PublicationController::class, 'activate']);

    // Admin routes (require admin middleware)
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Admin subscription routes
        Route::apiResource('subscriptions', AdminSubscriptionController::class);
    });
});
