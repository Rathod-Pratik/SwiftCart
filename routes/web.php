<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorController;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('customer.home.index');
})->name('home');

Route::get('/product', function () {
    return view('customer.product.index');
})->name('product');

Route::get('/about', function () {
    return view('customer.about.index');
})->name('about');

Route::get('/contact', function () {
    return view('customer.contact.index');
})->name('contact');

Route::get('/login', function () {
    return view('customer.auth.login');
})->name('login');

Route::get('/cart', function () {
    return view('customer.cart.index');
})->name('cart');

Route::get('/wishlist', function () {
    return view('customer.wishlist.index');
})->name('wishlist');

Route::get('/profile', function () {
    /** @var User $user */
    $user = auth()->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.profile');
    }

    if ($user->isVendor()) {
        return redirect()->route('vendor.profile');
    }

    $orders = Order::query()
        ->where('user_id', $user->id)
        ->with('items.product')
        ->latest()
        ->get();

    $stats = [
        'orders_count' => $orders->count(),
        'wishlist_count' => Wishlist::where('user_id', $user->id)->count(),
        'cart_count' => Cart::where('user_id', $user->id)->count(),
        'reviews_count' => Review::where('user_id', $user->id)->count(),
    ];

    return view('customer.profile.index', compact('user', 'orders', 'stats'));
})->middleware(['auth'])->name('profile');

Route::get('/logout', [AuthController::class, 'logout'])
    ->middleware(['auth'])
    ->name('logout');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware(['guest', 'throttle:6,1']);

Route::get('/register', function () {
    return view('customer.auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'signup'])
    ->middleware(['guest', 'throttle:6,1']);

// Vendor Registration Flow
Route::get('/become-seller', [VendorController::class, 'create'])->name('vendor.register');
Route::get('/become-vendor', fn () => redirect()->route('vendor.register'));
Route::post('/become-seller', [VendorController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('vendor.register.post');
Route::post('/become-vendor', [VendorController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1']);

Route::view('/admin/category/create', 'admin.Category.Create')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.category.create');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::view('/category', 'admin.Category.Category')->name('admin.category');
    Route::view('/category/details', 'admin.Category.Details')->name('admin.category.details');
    Route::view('/product', 'admin.Product.Product')->name('admin.product');
    Route::view('/product/details', 'admin.Product.Details')->name('admin.product.details');
    Route::view('/rating', 'admin.Rating.Rating')->name('admin.rating');
    Route::view('/user', 'admin.User.User')->name('admin.user');
    Route::view('/user/details', 'admin.User.Details')->name('admin.user.details');
    Route::view('/vendor', 'admin.Vendor.Vendor')->name('admin.vendor');
    Route::view('/vendor/details', 'admin.Vendor.Details')->name('admin.vendor.details');
    Route::view('/vendor/request', 'admin.Vendor.Request')->name('admin.vendor.request');
    Route::view('/vendor/request/details', 'admin.Vendor.RequestDetails')->name('admin.vendor.request.details');
    Route::view('/query', 'admin.Query.Query')->name('admin.query');
    Route::view('/query/details', 'admin.Query.Details')->name('admin.query.details');
    Route::view('/profile', 'admin.Profile.Profile')->name('admin.profile');
    Route::view('/notification', 'admin.Notification.Notification')->name('admin.notification');
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
