<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

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

    public function create(): View
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'],
            'is_active' => ['boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_active' => $validated['is_active'] ?? true,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', '사용자가 등록되었습니다.');
    }

    public function show(User $user): View
    {
        $user->load(['roles', 'vendor', 'bookings' => fn ($q) => $q->with('product.translations')->latest()->limit(10)]);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $user->load('roles');
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'],
            'is_active' => ['boolean'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $validated['is_active'] ?? false,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // 역할 변경
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', '사용자 정보가 수정되었습니다.');
    }

    public function destroy(User $user): RedirectResponse
    {
        // 관리자는 삭제 불가
        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', '관리자 계정은 삭제할 수 없습니다.');
        }

        // Soft delete
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', '사용자가 탈퇴 처리되었습니다.');
    }

    public function restore(int $id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.show', $user)
            ->with('success', '사용자가 복구되었습니다.');
    }

    public function toggle(User $user): RedirectResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? '활성화' : '비활성화';

        return redirect()->back()->with('success', "사용자가 {$status}되었습니다.");
    }

    public function block(User $user): RedirectResponse
    {
        $user->update([
            'is_blocked' => true,
            'blocked_at' => now(),
        ]);

        return redirect()->back()->with('success', '사용자가 차단되었습니다.');
    }

    public function unblock(User $user): RedirectResponse
    {
        $user->update([
            'is_blocked' => false,
            'blocked_at' => null,
            'no_show_count' => 0,
        ]);

        return redirect()->back()->with('success', '사용자 차단이 해제되었습니다.');
    }
}
