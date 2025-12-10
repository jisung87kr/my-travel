<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CheckinController extends Controller
{
    public function lookup(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $booking = Booking::with(['user', 'product.translations', 'schedule'])
            ->where('booking_code', $request->code)
            ->where('guide_id', $request->user()->id)
            ->whereHas('schedule', function ($query) {
                $query->whereDate('date', today());
            })
            ->first();

        if (!$booking) {
            return Response::notFound('해당 예약을 찾을 수 없습니다.');
        }

        if ($booking->status->value === 'completed') {
            return Response::error('이미 완료된 예약입니다.', 400);
        }

        if (!in_array($booking->status->value, ['confirmed', 'in_progress'])) {
            return Response::error('체크인할 수 없는 상태입니다.', 400);
        }

        return Response::success([
            'id' => $booking->id,
            'booking_code' => $booking->booking_code,
            'customer_name' => $booking->user->name,
            'product_name' => $booking->product->getTranslation('ko')?->title ?? '투어',
            'quantity' => $booking->quantity,
            'adult_count' => $booking->adult_count,
            'child_count' => $booking->child_count,
            'status' => $booking->status->value,
            'booking_date' => $booking->schedule?->date?->format('Y-m-d'),
        ]);
    }

    public function checkin(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->guide_id !== $request->user()->id) {
            return Response::forbidden('권한이 없습니다.');
        }

        if (!in_array($booking->status->value, ['confirmed', 'in_progress'])) {
            return Response::error('체크인할 수 없는 상태입니다.', 400);
        }

        // 체크인 처리 (in_progress로 변경)
        if ($booking->status->value === 'confirmed') {
            $booking->update([
                'status' => 'in_progress',
                'checked_in_at' => now(),
            ]);
        }

        return Response::success($booking->fresh(), '체크인이 완료되었습니다.');
    }
}
