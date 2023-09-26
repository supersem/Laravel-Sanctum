<?php

use App\Http\Controllers\AdminSubscriptionController;
use App\Http\Controllers\UserSubscriptionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');;

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::apiResource('subscriptions', AdminSubscriptionController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('subscriptions', [UserSubscriptionController::class, 'index']);
    Route::post('subscriptions/choose-subscription/{subscriptionId}', [UserSubscriptionController::class, 'chooseSubscription']);
});
