<?php

use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $user = Auth::user();
        return $user ? redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'customer.dashboard') : redirect('/login');
    })->name('dashboard');

    Route::get('products', function () {
        $user = Auth::user();

        if (! $user) {
            return redirect('/login');
        }

        return redirect()->route($user->role === 'admin' ? 'admin.products.index' : 'customer.products');
    })->name('products');
});

require __DIR__.'/admin.php';
require __DIR__.'/customer.php';

Route::get('auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

require __DIR__.'/settings.php';
