<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $locale = app()->getLocale();

        // Get recent bookings
        $recentBookings = $user->bookings()
            ->with(['product.translations', 'product.images', 'schedule'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($booking) use ($locale) {
                $translation = $booking->product->getTranslation($locale);
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'product_title' => $translation?->name ?? $booking->product->getTranslation('ko')?->name ?? '',
                    'product_image' => $booking->product->images->first()?->path ?? 'https://placehold.co/300x300?text=NO+IMAGE',
                    'date' => $booking->schedule->date->format('Y.m.d'),
                    'status' => $booking->status,
                    'total_price' => $booking->total_price,
                ];
            });

        // Get wishlist count
        $wishlistCount = $user->wishlists()->count();

        // Get reviews count
        $reviewsCount = $user->reviews()->count();

        // Get bookings stats
        $bookingsStats = [
            'total' => $user->bookings()->count(),
            'pending' => $user->bookings()->where('status', 'pending')->count(),
            'confirmed' => $user->bookings()->where('status', 'confirmed')->count(),
            'completed' => $user->bookings()->where('status', 'completed')->count(),
        ];

        // Get upcoming bookings (confirmed bookings with future dates)
        $upcomingBookings = $user->bookings()
            ->with(['product.translations', 'product.images', 'schedule'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereHas('schedule', function ($query) {
                $query->where('date', '>=', today());
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($booking) use ($locale) {
                $translation = $booking->product->getTranslation($locale);
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'product_title' => $translation?->name ?? $booking->product->getTranslation('ko')?->name ?? '',
                    'product_image' => $booking->product->images->first()?->path ?? 'https://placehold.co/300x300?text=NO+IMAGE',
                    'product_slug' => $booking->product->slug,
                    'date' => $booking->schedule->date,
                    'status' => $booking->status,
                    'total_persons' => $booking->total_persons,
                ];
            });

        return view('dashboard', compact(
            'user',
            'recentBookings',
            'wishlistCount',
            'reviewsCount',
            'bookingsStats',
            'upcomingBookings'
        ));
    }
}
