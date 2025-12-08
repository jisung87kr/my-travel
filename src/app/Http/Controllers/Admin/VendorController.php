<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VendorStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
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
                $q->where('company_name', 'like', "%{$search}%")
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

    public function create(): View
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
            'business_number' => ['nullable', 'string', 'max:50'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'contact_email' => ['nullable', 'string', 'email', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(VendorStatus::values())],
        ]);

        DB::transaction(function () use ($validated) {
            // 사용자 생성
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'is_active' => true,
            ]);

            $user->assignRole('vendor');

            // 제공자 생성
            Vendor::create([
                'user_id' => $user->id,
                'company_name' => $validated['company_name'],
                'business_number' => $validated['business_number'] ?? null,
                'contact_phone' => $validated['contact_phone'] ?? null,
                'contact_email' => $validated['contact_email'] ?? null,
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
                'approved_at' => $validated['status'] === 'approved' ? now() : null,
            ]);
        });

        return redirect()->route('admin.vendors.index')
            ->with('success', '제공자가 등록되었습니다.');
    }

    public function show(Vendor $vendor): View
    {
        $vendor->load(['user', 'products' => fn ($q) => $q->with('translations')->latest()->limit(10)]);

        return view('admin.vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor): View
    {
        $vendor->load('user');
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($vendor->user_id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
            'business_number' => ['nullable', 'string', 'max:50'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'contact_email' => ['nullable', 'string', 'email', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(VendorStatus::values())],
        ]);

        DB::transaction(function () use ($validated, $vendor) {
            // 사용자 정보 업데이트
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $vendor->user->update($userData);

            // 제공자 정보 업데이트
            $vendorData = [
                'company_name' => $validated['company_name'],
                'business_number' => $validated['business_number'] ?? null,
                'contact_phone' => $validated['contact_phone'] ?? null,
                'contact_email' => $validated['contact_email'] ?? null,
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
            ];

            // 상태가 승인으로 변경되었고, 이전에 승인되지 않았다면
            if ($validated['status'] === 'approved' && $vendor->status->value !== 'approved') {
                $vendorData['approved_at'] = now();
                $vendorData['rejection_reason'] = null;
            }

            $vendor->update($vendorData);
        });

        return redirect()->route('admin.vendors.show', $vendor)
            ->with('success', '제공자 정보가 수정되었습니다.');
    }

    public function destroy(Vendor $vendor): RedirectResponse
    {
        DB::transaction(function () use ($vendor) {
            // 관련 상품이 있으면 삭제 불가
            if ($vendor->products()->count() > 0) {
                throw new \Exception('등록된 상품이 있는 제공자는 삭제할 수 없습니다.');
            }

            // 사용자도 함께 soft delete
            $vendor->user->delete();
            $vendor->delete();
        });

        return redirect()->route('admin.vendors.index')
            ->with('success', '제공자가 탈퇴 처리되었습니다.');
    }

    public function approve(Vendor $vendor): RedirectResponse
    {
        $vendor->approve();

        return redirect()->back()->with('success', '제공자가 승인되었습니다.');
    }

    public function reject(Request $request, Vendor $vendor): RedirectResponse
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $vendor->reject($request->reason ?? '');

        return redirect()->back()->with('success', '제공자가 거절되었습니다.');
    }

    public function suspend(Vendor $vendor): RedirectResponse
    {
        $vendor->update(['status' => VendorStatus::SUSPENDED]);

        return redirect()->back()->with('success', '제공자가 정지되었습니다.');
    }
}
