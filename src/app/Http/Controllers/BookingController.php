<?php

namespace App\Http\Controllers;

use App\Exceptions\BookingExpiredException;
use App\Exceptions\BookingNotAllowedException;
use App\Exceptions\InsufficientInventoryException;
use App\Exceptions\InvalidBookingStatusException;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use App\Models\Product;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService
    ) {}

    /**
     * 예약 생성 페이지 표시
     */
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

    /**
     * 예약 완료 페이지 표시
     */
    public function complete(Booking $booking): View
    {
        Gate::authorize('view', $booking);

        $booking->load(['product.translations', 'product.images', 'schedule']);

        $locale = app()->getLocale();
        $translation = $booking->product->getTranslation($locale) ?? $booking->product->getTranslation('ko');

        return view('traveler.bookings.complete', compact('booking', 'translation'));
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'per_page']);
        $bookings = $this->bookingService->getUserBookings($request->user(), $filters);

        return ApiResponse::paginated($bookings);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->create(
                $request->validated(),
                $request->user()
            );

            return ApiResponse::created($booking, '예약이 완료되었습니다.');
        } catch (BookingNotAllowedException $e) {
            return ApiResponse::forbidden($e->getMessage());
        } catch (BookingExpiredException $e) {
            return ApiResponse::error($e->getMessage(), 400);
        } catch (InsufficientInventoryException $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    public function show(Booking $booking): JsonResponse
    {
        Gate::authorize('view', $booking);

        $booking->load(['product.translations', 'product.images', 'schedule', 'user']);

        return ApiResponse::success($booking);
    }

    public function cancel(Request $request, Booking $booking): JsonResponse
    {
        Gate::authorize('cancel', $booking);

        try {
            $request->validate([
                'reason' => 'nullable|string|max:500',
            ]);

            $booking = $this->bookingService->cancel(
                $booking,
                $request->user(),
                $request->reason
            );

            return ApiResponse::success($booking, '예약이 취소되었습니다.');
        } catch (InvalidBookingStatusException $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }
}
