<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Vendor::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $vendors = $query->latest()->paginate(20);

        $stats = [
            'pending' => Vendor::where('status', 'pending')->count(),
            'approved' => Vendor::where('status', 'approved')->count(),
            'rejected' => Vendor::where('status', 'rejected')->count(),
        ];

        return view('admin.vendors.index', compact('vendors', 'stats'));
    }

    public function show(Vendor $vendor): View
    {
        $vendor->load(['user', 'products' => fn ($q) => $q->latest()->limit(10)]);

        return view('admin.vendors.show', compact('vendor'));
    }

    public function approve(Vendor $vendor): RedirectResponse
    {
        $vendor->update(['status' => 'approved']);

        return redirect()->back()->with('success', '제공자가 승인되었습니다.');
    }

    public function reject(Request $request, Vendor $vendor): RedirectResponse
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $vendor->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', '제공자가 거절되었습니다.');
    }
}
