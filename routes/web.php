<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorRegistrationController;
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

Route::post('/login', [AuthController::class, 'login'])
    ->middleware(['guest', 'throttle:6,1']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'signup'])
    ->middleware(['guest', 'throttle:6,1']);

// Vendor Registration Flow
Route::get('/become-seller', [VendorRegistrationController::class, 'create'])->name('vendor.register');
Route::get('/become-vendor', fn () => redirect()->route('vendor.register'));
Route::post('/become-seller', [VendorRegistrationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('vendor.register.post');
Route::post('/become-vendor', [VendorRegistrationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1']);

Route::view('/admin/category/create', 'admin.Category.Create')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.category.create');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.Dashboard.Dashboard')->name('admin.dashboard');
    Route::view('/category', 'admin.Category.Category')->name('admin.category');
    Route::view('/category/details', 'admin.Category.Details')->name('admin.category.details');
    Route::view('/product', 'admin.Product.Product')->name('admin.product');
    Route::view('/product/details', 'admin.Product.Details')->name('admin.product.details');
    Route::view('/rating', 'admin.Rating.Rating')->name('admin.rating');
    Route::view('/user', 'admin.User.User')->name('admin.user');
    Route::view('/user/details', 'admin.User.Details')->name('admin.user.details');
    Route::view('/vendor', 'admin.Vendor.Vendor')->name('admin.vendor');
    Route::view('/vendor/details', 'admin.Vendor.Details')->name('admin.vendor.details');
    Route::view('/query', 'admin.Query.Query')->name('admin.query');
    Route::view('/query/details', 'admin.Query.Details')->name('admin.query.details');
    Route::redirect('/contact', '/admin/query')->name('admin.contact');
});

Route::middleware(['auth', 'verified', 'role:vendor'])->prefix('vendor')->group(function () {
    Route::view('/dashboard', 'vendor.Dashboard.Dashboard')->name('vendor.dashboard');
    Route::view('/product', 'vendor.Product.Product')->name('vendor.product');
    Route::view('/product/create', 'vendor.Product.Create')->name('vendor.product.create');
    Route::view('/product/details', 'vendor.Product.Details')->name('vendor.product.details');
    Route::view('/order', 'vendor.Order.Order')->name('vendor.order');
    Route::view('/order/details', 'vendor.Order.Details')->name('vendor.order.details');
    Route::view('/rating', 'vendor.Rating.Rating')->name('vendor.rating');
    Route::view('/rating/details', 'vendor.Rating.Details')->name('vendor.rating.details');
    Route::view('/query', 'vendor.Query.Query')->name('vendor.query');
    Route::view('/query/details', 'vendor.Query.Details')->name('vendor.query.details');
    Route::view('/profile', 'vendor.Profile.Profile')->name('vendor.profile');
});
