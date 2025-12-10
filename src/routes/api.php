<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\Traveler\WishlistController;
use App\Http\Controllers\Api\Guide\BookingController as GuideBookingController;
use App\Http\Controllers\Api\Guide\CheckinController as GuideCheckinController;
use App\Http\Controllers\Api\Guide\ScheduleController as GuideScheduleController;
use App\Http\Controllers\Api\Vendor\BookingController as VendorBookingController;
use App\Http\Controllers\Api\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\Api\Vendor\ScheduleController as VendorScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes in this file are prefixed with /api and return JSON responses.
|
*/

// Public API routes (no authentication)
Route::name('api.public.')->group(function () {
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
});

// Authenticated user API routes
Route::middleware(['auth:sanctum', 'user.active'])->name('api.')->group(function () {
    // Booking API
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::delete('bookings/{booking}', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Review API
    Route::post('bookings/{booking}/review', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('reviews/{review}/report', [ReviewController::class, 'report'])->name('reviews.report');

    // Message API
    Route::get('messages', [MessageController::class, 'conversations'])->name('messages.index');
    Route::get('bookings/{booking}/messages', [MessageController::class, 'index'])->name('messages.thread');
    Route::post('bookings/{booking}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::patch('messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');

    // Wishlist API
    Route::post('wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

// Vendor API routes
Route::middleware(['auth:sanctum', 'user.active', 'role:vendor,admin'])->prefix('vendor')->name('api.vendor.')->group(function () {
    // Product API
    Route::apiResource('products', VendorProductController::class);

    Route::post('products/{product}/images', [VendorProductController::class, 'uploadImages'])
        ->name('products.images.upload');
    Route::put('products/{product}/images/reorder', [VendorProductController::class, 'reorderImages'])
        ->name('products.images.reorder');
    Route::delete('products/{product}/images/{image}', [VendorProductController::class, 'deleteImage'])
        ->name('products.images.destroy');
    Route::post('products/{product}/submit', [VendorProductController::class, 'submitForReview'])
        ->name('products.submit');
    Route::post('products/{product}/activate', [VendorProductController::class, 'activate'])
        ->name('products.activate');
    Route::post('products/{product}/deactivate', [VendorProductController::class, 'deactivate'])
        ->name('products.deactivate');

    // Schedule API
    Route::get('products/{product}/schedules', [VendorScheduleController::class, 'index'])
        ->name('products.schedules.index');
    Route::post('products/{product}/schedules', [VendorScheduleController::class, 'store'])
        ->name('products.schedules.store');
    Route::put('products/{product}/schedules', [VendorScheduleController::class, 'update'])
        ->name('products.schedules.update');
    Route::post('products/{product}/schedules/bulk', [VendorScheduleController::class, 'bulkCreate'])
        ->name('products.schedules.bulk');
    Route::post('products/{product}/schedules/close', [VendorScheduleController::class, 'close'])
        ->name('products.schedules.close');
    Route::post('products/{product}/schedules/open', [VendorScheduleController::class, 'open'])
        ->name('products.schedules.open');

    // Booking API
    Route::get('bookings', [VendorBookingController::class, 'index'])
        ->name('bookings.index');
    Route::get('bookings/{booking}', [VendorBookingController::class, 'show'])
        ->name('bookings.show');
    Route::patch('bookings/{booking}/approve', [VendorBookingController::class, 'approve'])
        ->name('bookings.approve');
    Route::patch('bookings/{booking}/reject', [VendorBookingController::class, 'reject'])
        ->name('bookings.reject');
    Route::patch('bookings/{booking}/complete', [VendorBookingController::class, 'complete'])
        ->name('bookings.complete');
    Route::patch('bookings/{booking}/no-show', [VendorBookingController::class, 'markNoShow'])
        ->name('bookings.no-show');

    // Review reply (vendor)
    Route::post('reviews/{review}/reply', [ReviewController::class, 'reply'])
        ->name('reviews.reply');
});

// Guide API routes
Route::middleware(['auth:sanctum', 'user.active', 'role:guide,admin'])->prefix('guide')->name('api.guide.')->group(function () {
    // Schedule API
    Route::get('schedules/events', [GuideScheduleController::class, 'events'])
        ->name('schedules.events');

    // Checkin API
    Route::post('checkin/lookup', [GuideCheckinController::class, 'lookup'])
        ->name('checkin.lookup');
    Route::post('checkin/{booking}', [GuideCheckinController::class, 'checkin'])
        ->name('checkin.store');

    // Booking API
    Route::post('bookings/{booking}/start', [GuideBookingController::class, 'start'])
        ->name('bookings.start');
    Route::post('bookings/{booking}/complete', [GuideBookingController::class, 'complete'])
        ->name('bookings.complete');
    Route::post('bookings/{booking}/no-show', [GuideBookingController::class, 'noShow'])
        ->name('bookings.no-show');
});

// Admin API routes (placeholder for future)
Route::middleware(['auth:sanctum', 'user.active', 'role:admin'])->prefix('admin')->name('api.admin.')->group(function () {
    // Add admin API endpoints here if needed
});
