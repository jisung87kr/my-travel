<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public product routes
Route::prefix('products')->name('products.')->group(function () {
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
});

// Vendor routes
Route::middleware(['auth', 'user.active', 'role:vendor,admin'])->prefix('vendor')->name('vendor.')->group(function () {
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
});

// Admin routes
Route::middleware(['auth', 'user.active', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin routes will be added in Task 010
});
