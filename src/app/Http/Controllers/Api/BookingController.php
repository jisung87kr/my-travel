<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\BookingExpiredException;
use App\Exceptions\BookingNotAllowedException;
use App\Exceptions\InsufficientInventoryException;
use App\Exceptions\InvalidBookingStatusException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'per_page']);
        $bookings = $this->bookingService->getUserBookings($request->user(), $filters);

        return Response::paginated($bookings);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->create(
                $request->validated(),
                $request->user()
            );

            return Response::created($booking, '예약이 완료되었습니다.');
        } catch (BookingNotAllowedException $e) {
            return Response::forbidden($e->getMessage());
        } catch (BookingExpiredException|InsufficientInventoryException $e) {
            return Response::error($e->getMessage(), 400);
        }
    }

    public function show(Booking $booking): JsonResponse
    {
        Gate::authorize('view', $booking);

        $booking->load(['product.translations', 'product.images', 'schedule', 'user']);

        return Response::success($booking);
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

            return Response::success($booking, '예약이 취소되었습니다.');
        } catch (InvalidBookingStatusException $e) {
            return Response::error($e->getMessage(), 400);
        }
    }
}
