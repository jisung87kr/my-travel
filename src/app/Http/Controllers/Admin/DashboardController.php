<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            // Users
            'total_users' => User::count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'blocked_users' => User::where('is_blocked', true)->count(),

            // Vendors
            'total_vendors' => Vendor::count(),
            'pending_vendors' => Vendor::where('status', 'pending')->count(),
            'approved_vendors' => Vendor::where('status', 'approved')->count(),

            // Products
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'pending_products' => Product::where('status', 'pending_review')->count(),

            // Bookings
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'no_show_bookings' => Booking::where('status', 'no_show')->count(),

            // Revenue
            'total_revenue' => Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_price'),
            'monthly_revenue' => Booking::whereIn('status', ['confirmed', 'completed'])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_price'),
        ];

        // Recent activities
        $recentBookings = Booking::with(['user', 'product.translations'])
            ->latest()
            ->limit(10)
            ->get();

        $pendingVendors = Vendor::with('user')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        $pendingProducts = Product::with(['vendor.user', 'translations'])
            ->where('status', 'pending_review')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'pendingVendors', 'pendingProducts'));
    }
}
