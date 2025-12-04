<?php

namespace App\Http\Controllers\Traveler;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService
    ) {}

    public function profile(): View
    {
        $user = auth()->user();

        return view('traveler.my.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'preferred_language' => 'required|in:ko,en,zh,ja',
        ]);

        $user = auth()->user();
        $user->update($validated);

        return back()->with('success', __('profile.updated'));
    }

    public function bookings(Request $request): View
    {
        $user = auth()->user();
        $locale = app()->getLocale();

        $filters = [
            'status' => $request->get('status'),
            'per_page' => 10,
        ];

        $bookings = $this->bookingService->getUserBookings($user, $filters);

        // Transform for view
        $bookings->getCollection()->transform(function ($booking) use ($locale) {
            $translation = $booking->product->getTranslation($locale);

            return [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'product_title' => $translation?->title ?? $booking->product->getTranslation('ko')?->title ?? '',
                'product_image' => $booking->product->images->first()?->url ?? '/images/placeholder.jpg',
                'date' => $booking->schedule->date->format('Y-m-d'),
                'formatted_date' => $booking->schedule->date->format('M d, Y'),
                'adult_count' => $booking->adult_count,
                'child_count' => $booking->child_count,
                'total_price' => $booking->total_price,
                'formatted_price' => number_format($booking->total_price),
                'status' => $booking->status->value,
                'status_label' => $booking->status->label(),
                'status_color' => $booking->status->color(),
                'can_cancel' => $booking->canBeCancelled(),
                'created_at' => $booking->created_at->format('Y-m-d H:i'),
            ];
        });

        return view('traveler.my.bookings', compact('bookings'));
    }

    public function bookingDetail(string $locale, int $bookingId): View
    {
        $user = auth()->user();
        $booking = $user->bookings()
            ->with(['product.translations', 'product.images', 'schedule', 'product.vendor.user'])
            ->findOrFail($bookingId);

        $translation = $booking->product->getTranslation($locale);

        return view('traveler.my.booking-detail', compact('booking', 'translation'));
    }

    public function wishlist(): View
    {
        $user = auth()->user();
        $locale = app()->getLocale();

        $wishlists = $user->wishlists()
            ->with(['product.translations', 'product.images', 'product.prices'])
            ->latest()
            ->paginate(12);

        // Transform for view
        $wishlists->getCollection()->transform(function ($wishlist) use ($locale) {
            $product = $wishlist->product;
            $translation = $product->getTranslation($locale);
            $lowestPrice = $product->prices->where('is_active', true)->min('price');

            return [
                'id' => $wishlist->id,
                'product_id' => $product->id,
                'slug' => $product->slug,
                'title' => $translation?->title ?? $product->getTranslation('ko')?->title ?? '',
                'image' => $product->images->first()?->url ?? '/images/placeholder.jpg',
                'region' => $product->region->label(),
                'type' => $product->type->label(),
                'price' => $lowestPrice,
                'formatted_price' => $lowestPrice ? number_format($lowestPrice) : null,
                'rating' => $product->average_rating,
                'review_count' => $product->review_count,
                'added_at' => $wishlist->created_at->format('Y-m-d'),
            ];
        });

        return view('traveler.my.wishlist', compact('wishlists'));
    }

    public function reviews(): View
    {
        $user = auth()->user();
        $locale = app()->getLocale();

        $reviews = $user->reviews()
            ->with(['product.translations', 'product.images', 'booking'])
            ->latest()
            ->paginate(10);

        // Transform for view
        $reviews->getCollection()->transform(function ($review) use ($locale) {
            $product = $review->product;
            $translation = $product->getTranslation($locale);

            return [
                'id' => $review->id,
                'product_id' => $product->id,
                'product_title' => $translation?->title ?? $product->getTranslation('ko')?->title ?? '',
                'product_image' => $product->images->first()?->url ?? '/images/placeholder.jpg',
                'booking_code' => $review->booking?->booking_code,
                'rating' => $review->rating,
                'content' => $review->content,
                'created_at' => $review->created_at->format('Y-m-d'),
                'formatted_date' => $review->created_at->format('M d, Y'),
            ];
        });

        return view('traveler.my.reviews', compact('reviews'));
    }
}
