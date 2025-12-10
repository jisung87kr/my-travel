<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService
    ) {}

    public function start(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->guide_id !== $request->user()->id) {
            return redirect()->back()->with('error', '권한이 없습니다.');
        }

        if ($booking->status->value !== 'confirmed') {
            return redirect()->back()->with('error', '시작할 수 없는 상태입니다.');
        }

        $booking->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->back()->with('success', '투어가 시작되었습니다.');
    }

    public function complete(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->guide_id !== $request->user()->id) {
            return redirect()->back()->with('error', '권한이 없습니다.');
        }

        if ($booking->status->value !== 'in_progress') {
            return redirect()->back()->with('error', '완료할 수 없는 상태입니다.');
        }

        $this->bookingService->complete($booking);

        return redirect()->back()->with('success', '투어가 완료되었습니다.');
    }

    public function noShow(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->guide_id !== $request->user()->id) {
            return redirect()->back()->with('error', '권한이 없습니다.');
        }

        if (!in_array($booking->status->value, ['confirmed', 'in_progress'])) {
            return redirect()->back()->with('error', '노쇼 처리할 수 없는 상태입니다.');
        }

        $this->bookingService->markNoShow($booking);

        return redirect()->back()->with('success', '노쇼로 처리되었습니다.');
    }
}
