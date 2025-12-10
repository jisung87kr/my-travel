<?php

namespace App\Http\Controllers;

use App\Exceptions\BookingExpiredException;
use App\Exceptions\BookingNotAllowedException;
use App\Exceptions\InsufficientInventoryException;
use App\Exceptions\InvalidBookingStatusException;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Product;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'per_page']);
        $bookings = $this->bookingService->getUserBookings($request->user(), $filters);

        return view('traveler.bookings.index', compact('bookings'));
    }

    public function create(Product $product): View
    {
        $product->load(['translations', 'images', 'prices', 'schedules' => function ($query) {
            $query->where('date', '>=', now()->toDateString())
                  ->where('is_closed', false)
                  ->orderBy('date');
        }]);

        $locale = app()->getLocale();
        $translation = $product->getTranslation($locale) ?? $product->getTranslation('ko');

        // 가격 정보
        $adultPrice = $product->prices->where('is_active', true)->where('type', 'adult')->first()?->price ?? 0;
        $childPrice = $product->prices->where('is_active', true)->where('type', 'child')->first()?->price ?? 0;

        // 옵션 (있다면)
        $options = $product->options ?? [];

        return view('traveler.bookings.create', compact(
            'product',
            'translation',
            'adultPrice',
            'childPrice',
            'options'
        ));
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        try {
            $booking = $this->bookingService->create(
                $request->validated(),
                $request->user()
            );

            return redirect()->route('bookings.complete', ['booking' => $booking->id])
                ->with('success', '예약이 완료되었습니다.');
        } catch (BookingNotAllowedException|BookingExpiredException|InsufficientInventoryException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Booking $booking): View
    {
        Gate::authorize('view', $booking);

        $booking->load(['product.translations', 'product.images', 'schedule', 'user']);

        $locale = app()->getLocale();
        $translation = $booking->product->getTranslation($locale) ?? $booking->product->getTranslation('ko');

        return view('traveler.bookings.show', compact('booking', 'translation'));
    }

    public function complete(Booking $booking): View
    {
        Gate::authorize('view', $booking);

        $booking->load(['product.translations', 'product.images', 'schedule']);

        $locale = app()->getLocale();
        $translation = $booking->product->getTranslation($locale) ?? $booking->product->getTranslation('ko');

        return view('traveler.bookings.complete', compact('booking', 'translation'));
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        Gate::authorize('cancel', $booking);

        try {
            $request->validate([
                'reason' => 'nullable|string|max:500',
            ]);

            $this->bookingService->cancel(
                $booking,
                $request->user(),
                $request->reason
            );

            return redirect()->route('bookings.index')
                ->with('success', '예약이 취소되었습니다.');
        } catch (InvalidBookingStatusException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
