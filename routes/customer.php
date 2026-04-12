<?php

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\TrackingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');

        Route::get('products', [ProductController::class, 'index'])->name('products');
        Route::get('products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

        Route::get('cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('cart', [CartController::class, 'store'])->name('cart.store');
        Route::patch('cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

        Route::get('tracking', [TrackingController::class, 'index'])->name('tracking.index');

        Route::resource('orders', OrderController::class)->only(['index', 'show', 'store']);
    });
