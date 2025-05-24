<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
    
    Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:2,3,4,5,6')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');

        Route::get('/product', [App\Http\Controllers\ProductController::class, 'index'])->name('admin.product');
        Route::post('/product', [App\Http\Controllers\ProductController::class, 'store'])->name('admin.product.store');
        Route::put('/product/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('admin.product.edit');
        Route::delete('/product/{id}', [App\Http\Controllers\ProductController::class, 'delete'])->name('admin.product.delete');

        Route::get('/report', [App\Http\Controllers\ReportController::class, 'index'])->name('admin.report');
    });
    
    Route::middleware('role:1')->group(function () {
        Route::get('/home', [App\Http\Controllers\CustomerController::class, 'index'])->name('customer.home');
    
        Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('customer.cart');
        Route::post('/cart', [App\Http\Controllers\CartController::class, 'addToCart'])->name('customer.cart.store');
        Route::put('/cart/decrease', [App\Http\Controllers\CartController::class, 'decreaseQuantity'])->name('customer.cart.decrease');
        Route::put('/cart/increase', [App\Http\Controllers\CartController::class, 'increaseQuantity'])->name('customer.cart.increase');
        Route::get('/cart/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('customer.cart.checkout');

        Route::get('/history', [App\Http\Controllers\HistoryController::class, 'index'])->name('customer.history');
    });
});