<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $request->role));
        }

        if ($request->filled('status')) {
            if ($request->status === 'blocked') {
                $query->where('is_blocked', true);
            } elseif ($request->status === 'active') {
                $query->where('is_active', true)->where('is_blocked', false);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['roles', 'vendor', 'bookings' => fn ($q) => $q->latest()->limit(10)]);

        return view('admin.users.show', compact('user'));
    }

    public function toggle(User $user): RedirectResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? '활성화' : '비활성화';

        return redirect()->back()->with('success', "사용자가 {$status}되었습니다.");
    }

    public function block(User $user): RedirectResponse
    {
        $user->update(['is_blocked' => true]);

        return redirect()->back()->with('success', '사용자가 차단되었습니다.');
    }

    public function unblock(User $user): RedirectResponse
    {
        $user->update([
            'is_blocked' => false,
            'no_show_count' => 0,
        ]);

        return redirect()->back()->with('success', '사용자 차단이 해제되었습니다.');
    }
}
