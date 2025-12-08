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

// Locale switch route
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['ko', 'en', 'zh', 'ja'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('locale.switch');

// Locale-prefixed routes
Route::prefix('{locale}')->where(['locale' => 'ko|en|zh|ja'])->middleware('locale')->group(function () {
    // Public product routes
    Route::get('/products', [TravelerProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [TravelerProductController::class, 'show'])->name('products.show');

    // Authenticated user routes
    Route::middleware(['auth', 'user.active'])->group(function () {
        // My page routes
        Route::prefix('my')->name('my.')->group(function () {
            Route::get('/profile', [MyController::class, 'profile'])->name('profile');
            Route::put('/profile', [MyController::class, 'updateProfile'])->name('profile.update');
            Route::get('/bookings', [MyController::class, 'bookings'])->name('bookings');
            Route::get('/bookings/{booking}', [MyController::class, 'bookingDetail'])->name('booking.detail');
            Route::post('/bookings/{booking}/cancel', [MyController::class, 'cancelBooking'])->name('booking.cancel');
            Route::get('/wishlist', [MyController::class, 'wishlist'])->name('wishlist');
            Route::get('/reviews', [MyController::class, 'reviews'])->name('reviews');
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
});

// Password Reset (accessible by both guests and authenticated users)
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])
    ->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])
    ->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])
    ->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');

// Authenticated routes
Route::middleware(['auth', 'user.active'])->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Traveler\DashboardController::class, 'index'])->name('dashboard');

    // Booking routes (traveler)
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\BookingController::class, 'index'])->name('index');
        Route::get('/create/{product}', [\App\Http\Controllers\BookingController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\BookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [\App\Http\Controllers\BookingController::class, 'show'])->name('show');
        Route::get('/{booking}/complete', [\App\Http\Controllers\BookingController::class, 'complete'])->name('complete');
        Route::delete('/{booking}', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('cancel');
    });

    // Review routes
    Route::post('/bookings/{booking}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/{review}/report', [\App\Http\Controllers\ReviewController::class, 'report'])->name('reviews.report');

    // Message routes
    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'conversations'])->name('messages.index');
    Route::get('/bookings/{booking}/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.thread');
    Route::post('/bookings/{booking}/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
    Route::patch('/messages/{message}/read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])->name('messages.read');
});

// Vendor routes (API + Web UI)
Route::middleware(['auth', 'user.active', 'role:vendor,admin'])->prefix('vendor')->name('vendor.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Vendor\DashboardController::class, 'index'])->name('dashboard');

    // Product management - API routes (JSON responses)
    Route::apiResource('products', \App\Http\Controllers\Vendor\ProductController::class);

    // Product management - Web routes
    Route::get('products/create', [\App\Http\Controllers\Vendor\ProductController::class, 'createView'])->name('products.create');
    Route::get('products/{product}/edit', [\App\Http\Controllers\Vendor\ProductController::class, 'editView'])->name('products.edit');

    // Product API endpoints
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

    // Schedule management (Web UI + API)
    Route::get('schedules', [\App\Http\Controllers\Vendor\ScheduleController::class, 'indexView'])->name('schedules.index');
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

    // Booking management (API + Web UI)
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

    // Review reply
    Route::post('reviews/{review}/reply', [\App\Http\Controllers\ReviewController::class, 'reply'])
        ->name('reviews.reply');
});

// Admin routes
Route::middleware(['auth', 'user.active', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // User management
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('users/{user}/toggle', [\App\Http\Controllers\Admin\UserController::class, 'toggle'])->name('users.toggle');
    Route::patch('users/{user}/block', [\App\Http\Controllers\Admin\UserController::class, 'block'])->name('users.block');
    Route::patch('users/{user}/unblock', [\App\Http\Controllers\Admin\UserController::class, 'unblock'])->name('users.unblock');
    Route::patch('users/{id}/restore', [\App\Http\Controllers\Admin\UserController::class, 'restore'])->name('users.restore');

    // Vendor management
    Route::get('vendors', [\App\Http\Controllers\Admin\VendorController::class, 'index'])->name('vendors.index');
    Route::get('vendors/create', [\App\Http\Controllers\Admin\VendorController::class, 'create'])->name('vendors.create');
    Route::post('vendors', [\App\Http\Controllers\Admin\VendorController::class, 'store'])->name('vendors.store');
    Route::get('vendors/{vendor}', [\App\Http\Controllers\Admin\VendorController::class, 'show'])->name('vendors.show');
    Route::get('vendors/{vendor}/edit', [\App\Http\Controllers\Admin\VendorController::class, 'edit'])->name('vendors.edit');
    Route::put('vendors/{vendor}', [\App\Http\Controllers\Admin\VendorController::class, 'update'])->name('vendors.update');
    Route::delete('vendors/{vendor}', [\App\Http\Controllers\Admin\VendorController::class, 'destroy'])->name('vendors.destroy');
    Route::patch('vendors/{vendor}/approve', [\App\Http\Controllers\Admin\VendorController::class, 'approve'])->name('vendors.approve');
    Route::patch('vendors/{vendor}/reject', [\App\Http\Controllers\Admin\VendorController::class, 'reject'])->name('vendors.reject');
    Route::patch('vendors/{vendor}/suspend', [\App\Http\Controllers\Admin\VendorController::class, 'suspend'])->name('vendors.suspend');

    // Product management
    Route::get('products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('products.show');
    Route::get('products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('products/{product}/approve', [\App\Http\Controllers\Admin\ProductController::class, 'approve'])->name('products.approve');
    Route::patch('products/{product}/reject', [\App\Http\Controllers\Admin\ProductController::class, 'reject'])->name('products.reject');
    Route::patch('products/{product}/toggle', [\App\Http\Controllers\Admin\ProductController::class, 'toggle'])->name('products.toggle');

    // Booking management
    Route::get('bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{booking}/cancel', [\App\Http\Controllers\Admin\BookingController::class, 'cancel'])->name('bookings.cancel');

    // No-show management
    Route::get('no-shows', [\App\Http\Controllers\Admin\NoShowController::class, 'index'])->name('no-shows.index');
    Route::patch('no-shows/{user}/unblock', [\App\Http\Controllers\Admin\NoShowController::class, 'unblock'])->name('no-shows.unblock');
    Route::patch('no-shows/{user}/reset', [\App\Http\Controllers\Admin\NoShowController::class, 'resetNoShowCount'])->name('no-shows.reset');
});

// Guide routes
Route::middleware(['auth', 'user.active', 'role:guide,admin'])->prefix('guide')->name('guide.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Guide\DashboardController::class, 'index'])->name('dashboard');

    // Schedules
    Route::get('schedules', [\App\Http\Controllers\Guide\ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('schedules/events', [\App\Http\Controllers\Guide\ScheduleController::class, 'events'])->name('schedules.events');
    Route::get('schedules/{booking}', [\App\Http\Controllers\Guide\ScheduleController::class, 'show'])->name('schedules.show');

    // Check-in
    Route::get('checkin', [\App\Http\Controllers\Guide\CheckinController::class, 'index'])->name('checkin.index');
    Route::get('checkin/lookup', [\App\Http\Controllers\Guide\CheckinController::class, 'lookup'])->name('checkin.lookup');
    Route::post('checkin/{booking}', [\App\Http\Controllers\Guide\CheckinController::class, 'checkin'])->name('checkin.store');

    // Booking actions
    Route::patch('bookings/{booking}/start', [\App\Http\Controllers\Guide\BookingController::class, 'start'])->name('bookings.start');
    Route::patch('bookings/{booking}/complete', [\App\Http\Controllers\Guide\BookingController::class, 'complete'])->name('bookings.complete');
    Route::patch('bookings/{booking}/no-show', [\App\Http\Controllers\Guide\BookingController::class, 'noShow'])->name('bookings.no-show');
});
