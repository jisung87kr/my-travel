<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    public function start(Request $request, Booking $booking): JsonResponse|RedirectResponse
    {
        if ($booking->guide_id !== $request->user()->id) {
            return $this->errorResponse($request, '권한이 없습니다.', 403);
        }

        if ($booking->status->value !== 'confirmed') {
            return $this->errorResponse($request, '시작할 수 없는 상태입니다.', 400);
        }

        $booking->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return $this->successResponse($request, '투어가 시작되었습니다.');
    }

    public function complete(Request $request, Booking $booking): JsonResponse|RedirectResponse
    {
        if ($booking->guide_id !== $request->user()->id) {
            return $this->errorResponse($request, '권한이 없습니다.', 403);
        }

        if ($booking->status->value !== 'in_progress') {
            return $this->errorResponse($request, '완료할 수 없는 상태입니다.', 400);
        }

        $this->bookingService->complete($booking);

        return $this->successResponse($request, '투어가 완료되었습니다.');
    }

    public function noShow(Request $request, Booking $booking): JsonResponse|RedirectResponse
    {
        if ($booking->guide_id !== $request->user()->id) {
            return $this->errorResponse($request, '권한이 없습니다.', 403);
        }

        if (! in_array($booking->status->value, ['confirmed', 'in_progress'])) {
            return $this->errorResponse($request, '노쇼 처리할 수 없는 상태입니다.', 400);
        }

        $this->bookingService->markNoShow($booking);

        return $this->successResponse($request, '노쇼로 처리되었습니다.');
    }

    private function successResponse(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    private function errorResponse(Request $request, string $message, int $status): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => $message], $status);
        }

        return redirect()->back()->with('error', $message);
    }
}
