<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // 오늘 일정 (가이드에게 배정된 예약)
        $todayBookings = Booking::with(['user', 'product.translations', 'product.vendor'])
            ->where('guide_id', $user->id)
            ->whereDate('booking_date', today())
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->orderBy('booking_date')
            ->get();

        // 이번 주 일정 개수
        $weeklyCount = Booking::where('guide_id', $user->id)
            ->whereBetween('booking_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->count();

        // 이번 달 완료 건수
        $monthlyCompleted = Booking::where('guide_id', $user->id)
            ->whereMonth('booking_date', now()->month)
            ->where('status', 'completed')
            ->count();

        // 다가오는 일정 (오늘 제외, 7일 내)
        $upcomingBookings = Booking::with(['user', 'product.translations'])
            ->where('guide_id', $user->id)
            ->whereDate('booking_date', '>', today())
            ->whereDate('booking_date', '<=', now()->addDays(7))
            ->whereIn('status', ['confirmed'])
            ->orderBy('booking_date')
            ->limit(5)
            ->get();

        return view('guide.dashboard', compact(
            'todayBookings',
            'weeklyCount',
            'monthlyCompleted',
            'upcomingBookings'
        ));
    }
}
