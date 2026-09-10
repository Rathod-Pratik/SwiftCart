<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->middleware('throttle:6,1');
    Route::post('/signup', [AuthController::class, 'signup'])->middleware('guest');
    Route::post('/forgot-password', [AuthController::class, 'forgetPassword'])->middleware('guest');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest');

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    Route::middleware(['auth', 'role:admin'])->group(function (): void {
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    });

    Route::middleware(['auth', 'role:admin,vendor'])->group(function (): void {
        Route::post('/products', [ProductController::class, 'store']);
        Route::match(['put', 'patch'], '/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });

    Route::middleware(['auth', 'role:vendor'])->group(function (): void {
        Route::patch('/vendor/bank-details', [AuthController::class, 'updateBankDetails']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/conversations', [ConversationController::class, 'index']);
        Route::post('/conversations', [ConversationController::class, 'store']);

        Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
        Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);
    });

    Route::middleware('auth')->group(function (): void {
        Route::get('/profile', [AuthController::class, 'getProfile']);
        Route::patch('/profile', [AuthController::class, 'updateProfile']);
        Route::delete('/profile', [AuthController::class, 'deleteAccount']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::prefix('wishlists')->group(function (): void {
            Route::get('/', [WishlistController::class, 'index']);
            Route::post('/', [WishlistController::class, 'store']);
            Route::delete('/{wishlist}', [WishlistController::class, 'destroy']);
        });

        Route::prefix('payments')->group(function (): void {
            Route::get('/', [PaymentController::class, 'index']);
            Route::post('/create-order', [PaymentController::class, 'createOrder']);
            Route::post('/verify', [PaymentController::class, 'verify']);
            Route::get('/{payment}', [PaymentController::class, 'show']);
        });

        Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
            ->middleware('throttle:6,1');
    });

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware(['auth', 'signed', 'throttle:6,1'])
        ->name('verification.verify');
});
