<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/forgot-password', [AuthController::class, 'forgetPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/reviews', [ReviewController::class, 'index']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function (): void {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::match(['put', 'patch'], '/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:vendor'])->group(function (): void {
    Route::post('/products', [ProductController::class, 'store']);
    Route::match(['put', 'patch'], '/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:vendor'])->group(function (): void {
    Route::patch('/vendor/bank-details', [AuthController::class, 'updateBankDetails']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function (): void {
    Route::get('/coupons', [CouponController::class, 'index']);
    Route::post('/coupons', [CouponController::class, 'store']);
    Route::match(['put', 'patch'], '/coupons/{coupon}', [CouponController::class, 'update']);
    Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::post('/conversations', [ConversationController::class, 'store']);

    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);
});

Route::middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::patch('/profile', [AuthController::class, 'updateProfile']);
    Route::delete('/profile', [AuthController::class, 'deleteAccount']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/verify-coupon', [CouponController::class, 'VerifyCoupon']);
    Route::prefix('wishlists')->group(function (): void {
        Route::get('/', [WishlistController::class, 'index']);
        Route::post('/', [WishlistController::class, 'store']);
        Route::get('/{wishlist}', [WishlistController::class, 'show']);
        Route::delete('/{wishlist}', [WishlistController::class, 'destroy']);
    });
    Route::prefix('reviews')->group(function (): void {
        Route::post('/', [ReviewController::class, 'store']);
        Route::match(['put', 'patch'], '/{review}', [ReviewController::class, 'update']);
        Route::delete('/{review}', [ReviewController::class, 'destroy']);
    });
    Route::prefix('cart')->group(function (): void {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::patch('/{cart}', [CartController::class, 'update']);
        Route::delete('/{cart}', [CartController::class, 'destroy']);
    });
    Route::prefix('orders')->group(function (): void {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::delete('/{order}', [OrderController::class, 'destroy']);
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus']);
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
    ->middleware(['auth:sanctum', 'signed', 'throttle:6,1'])
    ->name('verification.verify');
