<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService
    ) {}

    public function start(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->guide_id !== $request->user()->id) {
            return Response::forbidden('권한이 없습니다.');
        }

        if ($booking->status->value !== 'confirmed') {
            return Response::error('시작할 수 없는 상태입니다.', 400);
        }

        $booking->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return Response::success($booking, '투어가 시작되었습니다.');
    }

    public function complete(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->guide_id !== $request->user()->id) {
            return Response::forbidden('권한이 없습니다.');
        }

        if ($booking->status->value !== 'in_progress') {
            return Response::error('완료할 수 없는 상태입니다.', 400);
        }

        $this->bookingService->complete($booking);

        return Response::success($booking->fresh(), '투어가 완료되었습니다.');
    }

    public function noShow(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->guide_id !== $request->user()->id) {
            return Response::forbidden('권한이 없습니다.');
        }

        if (!in_array($booking->status->value, ['confirmed', 'in_progress'])) {
            return Response::error('노쇼 처리할 수 없는 상태입니다.', 400);
        }

        $this->bookingService->markNoShow($booking);

        return Response::success($booking->fresh(), '노쇼로 처리되었습니다.');
    }
}
