<?php

namespace App\Http\Controllers\Vendor;

use App\Exceptions\InvalidBookingStatusException;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'status',
            'product_id',
            'date',
            'date_from',
            'date_to',
            'per_page',
        ]);

        $bookings = $this->bookingService->getVendorBookings($request->user(), $filters);

        return ApiResponse::paginated($bookings);
    }

    public function indexView(Request $request): View
    {
        $user = $request->user();
        $vendor = $user->vendor;

        $filters = $request->only([
            'status',
            'product',
            'date_from',
            'date_to',
            'search',
        ]);

        if (!empty($filters['product'])) {
            $filters['product_id'] = $filters['product'];
        }

        $bookings = $this->bookingService->getVendorBookings($user, $filters);

        // Get vendor's products for filter dropdown
        $products = $vendor->products()->with('translations')->get();

        // Calculate stats
        $stats = [
            'total' => $bookings->total(),
            'pending' => Booking::whereHas('product', fn($q) => $q->where('vendor_id', $vendor->id))
                ->where('status', 'pending')->count(),
            'confirmed' => Booking::whereHas('product', fn($q) => $q->where('vendor_id', $vendor->id))
                ->where('status', 'confirmed')->count(),
            'completed' => Booking::whereHas('product', fn($q) => $q->where('vendor_id', $vendor->id))
                ->where('status', 'completed')->count(),
            'cancelled' => Booking::whereHas('product', fn($q) => $q->where('vendor_id', $vendor->id))
                ->where('status', 'cancelled')->count(),
            'no_show' => Booking::whereHas('product', fn($q) => $q->where('vendor_id', $vendor->id))
                ->where('status', 'no_show')->count(),
        ];

        return view('vendor.bookings.index', compact('bookings', 'products', 'stats'));
    }

    public function show(Booking $booking): JsonResponse
    {
        Gate::authorize('viewAsVendor', $booking);

        $booking->load(['product.translations', 'schedule', 'user', 'review']);

        return ApiResponse::success($booking);
    }

    public function showView(Booking $booking): View
    {
        Gate::authorize('viewAsVendor', $booking);

        $booking->load(['product.translations', 'product.images', 'schedule', 'user', 'review']);

        return view('vendor.bookings.show', compact('booking'));
    }

    public function approve(Booking $booking): JsonResponse
    {
        Gate::authorize('manage', $booking);

        try {
            $booking = $this->bookingService->approve($booking);

            return ApiResponse::success($booking, '예약이 승인되었습니다.');
        } catch (InvalidBookingStatusException $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    public function reject(Request $request, Booking $booking): JsonResponse
    {
        Gate::authorize('manage', $booking);

        try {
            $request->validate([
                'reason' => 'nullable|string|max:500',
            ]);

            $booking = $this->bookingService->reject($booking, $request->reason);

            return ApiResponse::success($booking, '예약이 거절되었습니다.');
        } catch (InvalidBookingStatusException $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    public function complete(Booking $booking): JsonResponse
    {
        Gate::authorize('manage', $booking);

        try {
            $booking = $this->bookingService->complete($booking);

            return ApiResponse::success($booking, '예약이 완료 처리되었습니다.');
        } catch (InvalidBookingStatusException $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    public function markNoShow(Booking $booking): JsonResponse
    {
        Gate::authorize('manage', $booking);

        try {
            $booking = $this->bookingService->markNoShow($booking);

            return ApiResponse::success($booking, '노쇼 처리되었습니다.');
        } catch (InvalidBookingStatusException $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }
}
