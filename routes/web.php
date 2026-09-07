<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
})->name('home');

Route::get('/product', function () {
    return view('product.index');
})->name('product');

Route::get('/about', function () {
    return view('about.index');
})->name('about');

Route::get('/contact', function () {
    return view('contact.index');
})->name('contact');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::prefix('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->middleware('throttle:6,1');
    Route::post('/signup', [AuthController::class, 'signup'])->middleware('guest');
    Route::post('/forgot-password', [AuthController::class, 'forgetPassword'])->middleware('guest');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest');

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'getProfile']);
        Route::patch('/profile', [AuthController::class, 'updateProfile']);
        Route::delete('/profile', [AuthController::class, 'deleteAccount']);
        Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
            ->middleware('throttle:6,1');
    });

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware(['auth', 'signed', 'throttle:6,1'])
        ->name('verification.verify');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.Dashboard')->name('admin.dashboard');
    Route::view('/category', 'admin.Category')->name('admin.category');
    Route::view('/product', 'admin.Product')->name('admin.product');
    Route::view('/rating', 'admin.Rating')->name('admin.rating');
    Route::view('/user', 'admin.User')->name('admin.user');
    Route::view('/vendor', 'admin.Vendor')->name('admin.vendor');
    Route::view('/contact', 'admin.Contact')->name('admin.contact');
});
