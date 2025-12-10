<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckinController extends Controller
{
    public function index(): View
    {
        return view('guide.checkin.index');
    }

    public function checkin(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->guide_id !== $request->user()->id) {
            return redirect()->back()->with('error', '권한이 없습니다.');
        }

        if (!in_array($booking->status->value, ['confirmed', 'in_progress'])) {
            return redirect()->back()->with('error', '체크인할 수 없는 상태입니다.');
        }

        // 체크인 처리 (in_progress로 변경)
        if ($booking->status->value === 'confirmed') {
            $booking->update([
                'status' => 'in_progress',
                'checked_in_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', '체크인이 완료되었습니다.');
    }
}
