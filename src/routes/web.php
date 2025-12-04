<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Traveler\MyController;
use App\Http\Controllers\Traveler\ProductController as TravelerProductController;
use App\Http\Controllers\Traveler\WishlistController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Locale-prefixed routes
Route::prefix('{locale}')->where(['locale' => 'ko|en|zh|ja'])->middleware('locale')->group(function () {
    // Public product routes
    Route::get('/products', [TravelerProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product:slug}', [TravelerProductController::class, 'show'])->name('products.show');

    // Authenticated user routes
    Route::middleware(['auth', 'user.active'])->group(function () {
        // My page routes
        Route::prefix('my')->name('my.')->group(function () {
            Route::get('/profile', [MyController::class, 'profile'])->name('profile');
            Route::put('/profile', [MyController::class, 'updateProfile'])->name('profile.update');
            Route::get('/bookings', [MyController::class, 'bookings'])->name('bookings');
            Route::get('/bookings/{booking}', [MyController::class, 'bookingDetail'])->name('booking.detail');
            Route::get('/wishlist', [MyController::class, 'wishlist'])->name('wishlist');
        });
    });
});

// Wishlist toggle (AJAX)
Route::middleware(['auth', 'user.active'])->group(function () {
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

// Legacy product routes (without locale prefix, for backward compatibility)
Route::prefix('products')->name('products.legacy.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ProductController::class, 'index'])->name('index');
    Route::get('/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('show');
});

// Guest routes (unauthenticated users only)
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Login
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Social Login
    Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])
        ->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback'])
        ->name('social.callback');

    // Password Reset
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])
        ->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])
        ->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// Authenticated routes
Route::middleware(['auth', 'user.active'])->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Dashboard placeholder (will be implemented in later tasks)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Booking routes (traveler)
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\BookingController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\BookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [\App\Http\Controllers\BookingController::class, 'show'])->name('show');
        Route::delete('/{booking}', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('cancel');
    });
});

// Vendor routes
Route::middleware(['auth', 'user.active', 'role:vendor,admin'])->prefix('vendor')->name('vendor.')->group(function () {
    // Product management
    Route::apiResource('products', \App\Http\Controllers\Vendor\ProductController::class);
    Route::post('products/{product}/images', [\App\Http\Controllers\Vendor\ProductController::class, 'uploadImages'])
        ->name('products.images.upload');
    Route::put('products/{product}/images/reorder', [\App\Http\Controllers\Vendor\ProductController::class, 'reorderImages'])
        ->name('products.images.reorder');
    Route::delete('products/{product}/images/{image}', [\App\Http\Controllers\Vendor\ProductController::class, 'deleteImage'])
        ->name('products.images.destroy');
    Route::post('products/{product}/submit', [\App\Http\Controllers\Vendor\ProductController::class, 'submitForReview'])
        ->name('products.submit');
    Route::post('products/{product}/activate', [\App\Http\Controllers\Vendor\ProductController::class, 'activate'])
        ->name('products.activate');
    Route::post('products/{product}/deactivate', [\App\Http\Controllers\Vendor\ProductController::class, 'deactivate'])
        ->name('products.deactivate');

    // Schedule management
    Route::get('products/{product}/schedules', [\App\Http\Controllers\Vendor\ScheduleController::class, 'index'])
        ->name('products.schedules.index');
    Route::post('products/{product}/schedules', [\App\Http\Controllers\Vendor\ScheduleController::class, 'store'])
        ->name('products.schedules.store');
    Route::put('products/{product}/schedules', [\App\Http\Controllers\Vendor\ScheduleController::class, 'update'])
        ->name('products.schedules.update');
    Route::post('products/{product}/schedules/bulk', [\App\Http\Controllers\Vendor\ScheduleController::class, 'bulkCreate'])
        ->name('products.schedules.bulk');
    Route::post('products/{product}/schedules/close', [\App\Http\Controllers\Vendor\ScheduleController::class, 'close'])
        ->name('products.schedules.close');
    Route::post('products/{product}/schedules/open', [\App\Http\Controllers\Vendor\ScheduleController::class, 'open'])
        ->name('products.schedules.open');

    // Booking management
    Route::get('bookings', [\App\Http\Controllers\Vendor\BookingController::class, 'index'])
        ->name('bookings.index');
    Route::get('bookings/{booking}', [\App\Http\Controllers\Vendor\BookingController::class, 'show'])
        ->name('bookings.show');
    Route::patch('bookings/{booking}/approve', [\App\Http\Controllers\Vendor\BookingController::class, 'approve'])
        ->name('bookings.approve');
    Route::patch('bookings/{booking}/reject', [\App\Http\Controllers\Vendor\BookingController::class, 'reject'])
        ->name('bookings.reject');
    Route::patch('bookings/{booking}/complete', [\App\Http\Controllers\Vendor\BookingController::class, 'complete'])
        ->name('bookings.complete');
    Route::patch('bookings/{booking}/no-show', [\App\Http\Controllers\Vendor\BookingController::class, 'markNoShow'])
        ->name('bookings.no-show');
});

// Admin routes
Route::middleware(['auth', 'user.active', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin routes will be added in Task 010
});
