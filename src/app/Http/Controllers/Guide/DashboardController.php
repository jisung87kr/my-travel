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
        $todayBookings = Booking::with(['user', 'product.translations', 'product.vendor', 'schedule'])
            ->where('guide_id', $user->id)
            ->whereHas('schedule', function ($query) {
                $query->whereDate('date', today());
            })
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->get()
            ->sortBy(fn ($booking) => $booking->schedule?->date);

        // 이번 주 일정 개수
        $weeklyCount = Booking::where('guide_id', $user->id)
            ->whereHas('schedule', function ($query) {
                $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
            })
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->count();

        // 이번 달 완료 건수
        $monthlyCompleted = Booking::where('guide_id', $user->id)
            ->whereHas('schedule', function ($query) {
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
            })
            ->where('status', 'completed')
            ->count();

        // 다가오는 일정 (오늘 제외, 7일 내)
        $upcomingBookings = Booking::with(['user', 'product.translations', 'schedule'])
            ->where('guide_id', $user->id)
            ->whereHas('schedule', function ($query) {
                $query->whereDate('date', '>', today())
                      ->whereDate('date', '<=', now()->addDays(7));
            })
            ->whereIn('status', ['confirmed'])
            ->get()
            ->sortBy(fn ($booking) => $booking->schedule?->date)
            ->take(5);

        return view('guide.dashboard', compact(
            'todayBookings',
            'weeklyCount',
            'monthlyCompleted',
            'upcomingBookings'
        ));
    }
}
