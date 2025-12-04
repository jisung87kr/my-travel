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

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService
    ) {}

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
