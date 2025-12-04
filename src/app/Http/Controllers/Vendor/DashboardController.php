<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $vendor = auth()->user()->vendor;

        if (! $vendor) {
            abort(403, 'Vendor profile not found');
        }

        $productIds = $vendor->products()->pluck('id');

        $stats = [
            'total_products' => $vendor->products()->count(),
            'active_products' => $vendor->products()->where('status', 'active')->count(),
            'pending_products' => $vendor->products()->where('status', 'pending_review')->count(),
            'pending_bookings' => Booking::whereIn('product_id', $productIds)
                ->where('status', 'pending')
                ->count(),
            'today_bookings' => Booking::whereIn('product_id', $productIds)
                ->whereHas('schedule', fn ($q) => $q->whereDate('date', today()))
                ->count(),
            'monthly_bookings' => Booking::whereIn('product_id', $productIds)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'monthly_revenue' => Booking::whereIn('product_id', $productIds)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->whereIn('status', ['confirmed', 'completed'])
                ->sum('total_price'),
        ];

        $recentBookings = Booking::with(['user', 'product.translations', 'schedule'])
            ->whereIn('product_id', $productIds)
            ->latest()
            ->limit(5)
            ->get();

        $upcomingBookings = Booking::with(['user', 'product.translations', 'schedule'])
            ->whereIn('product_id', $productIds)
            ->whereIn('status', ['confirmed', 'pending'])
            ->whereHas('schedule', fn ($q) => $q->where('date', '>=', today()))
            ->orderBy(
                \DB::raw('(SELECT date FROM product_schedules WHERE product_schedules.id = bookings.schedule_id)'),
                'asc'
            )
            ->limit(5)
            ->get();

        return view('vendor.dashboard', compact('stats', 'recentBookings', 'upcomingBookings'));
    }
}
