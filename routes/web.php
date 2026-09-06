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

route::get('/admin/dashboard', function () {
    return view('admin.Dashboard');
})->name('admin.dashboard');

route::get('/admin/category', function () {
    return view('admin.Category');
})->name('admin.category');

route::get('/admin/product', function () {
    return view('admin.Product');
})->name('admin.product');

route::get('/admin/Rating', function () {
    return view('admin.Rating');
})->name('admin.rating');

route::get('/admin/user', function () {
    return view('admin.User');
})->name('admin.user');

route::get('/admin/vendor', function () {
    return view('admin.Vendor');
})->name('admin.vendor');

route::get('/admin/contact', function () {
    return view('admin.Contact');
})->name('admin.contact');

