<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Message;
use App\Models\Product;
use App\Models\Review;
use App\Policies\BookingPolicy;
use App\Policies\MessagePolicy;
use App\Policies\ProductPolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Booking::class, BookingPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
        Gate::policy(Message::class, MessagePolicy::class);
    }
}
