<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        return view('guide.schedules.index');
    }

    public function events(Request $request): JsonResponse
    {
        $user = $request->user();

        $start = $request->input('start');
        $end = $request->input('end');

        $bookings = Booking::with(['user', 'product.translations'])
            ->where('guide_id', $user->id)
            ->when($start, fn ($q) => $q->whereDate('booking_date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('booking_date', '<=', $end))
            ->whereIn('status', ['confirmed', 'in_progress', 'completed'])
            ->get();

        $events = $bookings->map(function ($booking) {
            $title = $booking->product->getTranslation('ko')?->title ?? '투어';

            return [
                'id' => $booking->id,
                'title' => "{$title} ({$booking->user->name})",
                'start' => $booking->booking_date->format('Y-m-d'),
                'backgroundColor' => $this->getStatusColor($booking->status->value),
                'borderColor' => $this->getStatusColor($booking->status->value),
                'extendedProps' => [
                    'status' => $booking->status->value,
                    'customer' => $booking->user->name,
                    'quantity' => $booking->quantity,
                ],
            ];
        });

        return response()->json($events);
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load(['user', 'product.translations', 'product.vendor', 'product.images']);

        return view('guide.schedules.show', compact('booking'));
    }

    private function getStatusColor(string $status): string
    {
        return match ($status) {
            'confirmed' => '#f59e0b',
            'in_progress' => '#3b82f6',
            'completed' => '#10b981',
            default => '#6b7280',
        };
    }
}
