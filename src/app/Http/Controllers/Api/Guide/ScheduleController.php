<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function events(Request $request): JsonResponse
    {
        $user = $request->user();

        $start = $request->input('start');
        $end = $request->input('end');

        $bookings = Booking::with(['user', 'product.translations', 'schedule'])
            ->where('guide_id', $user->id)
            ->whereHas('schedule', function ($query) use ($start, $end) {
                if ($start) {
                    $query->whereDate('date', '>=', $start);
                }
                if ($end) {
                    $query->whereDate('date', '<=', $end);
                }
            })
            ->whereIn('status', ['confirmed', 'in_progress', 'completed'])
            ->get();

        $events = $bookings->map(function ($booking) {
            $title = $booking->product->getTranslation('ko')?->title ?? '투어';

            return [
                'id' => $booking->id,
                'title' => "{$title} ({$booking->user->name})",
                'start' => $booking->schedule?->date?->format('Y-m-d'),
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
