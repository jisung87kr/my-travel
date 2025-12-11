<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['user', 'product.translations', 'product.vendor', 'schedule']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('product.translations', fn ($q) => $q->where('title', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('date_from')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->whereDate('date', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->whereDate('date', '<=', $request->date_to);
            });
        }

        $bookings = $query->latest()->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['user', 'product.translations', 'product.vendor', 'schedule']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', '예약이 취소되었습니다.');
    }
}
