<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckinController extends Controller
{
    public function index(): View
    {
        return view('guide.checkin.index');
    }

    public function lookup(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $booking = Booking::with(['user', 'product.translations'])
            ->where('booking_code', $request->code)
            ->where('guide_id', $request->user()->id)
            ->whereDate('booking_date', today())
            ->first();

        if (! $booking) {
            return response()->json([
                'success' => false,
                'message' => '해당 예약을 찾을 수 없습니다.',
            ], 404);
        }

        if ($booking->status->value === 'completed') {
            return response()->json([
                'success' => false,
                'message' => '이미 완료된 예약입니다.',
            ], 400);
        }

        if (! in_array($booking->status->value, ['confirmed', 'in_progress'])) {
            return response()->json([
                'success' => false,
                'message' => '체크인할 수 없는 상태입니다.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'booking' => [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'customer_name' => $booking->user->name,
                'product_name' => $booking->product->getTranslation('ko')?->title ?? '투어',
                'quantity' => $booking->quantity,
                'adult_count' => $booking->adult_count,
                'child_count' => $booking->child_count,
                'status' => $booking->status->value,
                'booking_date' => $booking->booking_date->format('Y-m-d'),
            ],
        ]);
    }

    public function checkin(Request $request, Booking $booking): JsonResponse|RedirectResponse
    {
        if ($booking->guide_id !== $request->user()->id) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => '권한이 없습니다.'], 403);
            }

            return redirect()->back()->with('error', '권한이 없습니다.');
        }

        if (! in_array($booking->status->value, ['confirmed', 'in_progress'])) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => '체크인할 수 없는 상태입니다.'], 400);
            }

            return redirect()->back()->with('error', '체크인할 수 없는 상태입니다.');
        }

        // 체크인 처리 (in_progress로 변경)
        if ($booking->status->value === 'confirmed') {
            $booking->update([
                'status' => 'in_progress',
                'checked_in_at' => now(),
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '체크인이 완료되었습니다.',
            ]);
        }

        return redirect()->back()->with('success', '체크인이 완료되었습니다.');
    }
}
