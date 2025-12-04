<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoShowController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['user', 'product.translations'])
            ->where('status', 'no_show');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        $noShows = $query->latest()->paginate(20);

        $blockedUsers = User::where('is_blocked', true)
            ->where('no_show_count', '>=', 3)
            ->withCount(['bookings as no_show_bookings_count' => fn ($q) => $q->where('status', 'no_show')])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.no-shows.index', compact('noShows', 'blockedUsers'));
    }

    public function unblock(User $user): RedirectResponse
    {
        $user->update([
            'is_blocked' => false,
            'no_show_count' => 0,
        ]);

        return redirect()->back()->with('success', '사용자 차단이 해제되었습니다.');
    }

    public function resetNoShowCount(User $user): RedirectResponse
    {
        $user->update(['no_show_count' => 0]);

        return redirect()->back()->with('success', '노쇼 횟수가 초기화되었습니다.');
    }
}
