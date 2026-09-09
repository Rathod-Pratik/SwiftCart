<?php

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

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.Dashboard')->name('admin.dashboard');
    Route::view('/category', 'admin.Category')->name('admin.category');
    Route::view('/product', 'admin.Product')->name('admin.product');
    Route::view('/rating', 'admin.Rating')->name('admin.rating');
    Route::view('/user', 'admin.User')->name('admin.user');
    Route::view('/vendor', 'admin.Vendor')->name('admin.vendor');
    Route::view('/contact', 'admin.Contact')->name('admin.contact');
});
