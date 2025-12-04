<x-layouts.admin>
    <x-slot name="header">대시보드</x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">전체 사용자</p>
                    <p class="text-2xl font-semibold">{{ number_format($stats['total_users']) }}</p>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-green-600">+{{ $stats['new_users_this_month'] }}</span> 이번 달
                @if($stats['blocked_users'] > 0)
                    <span class="text-red-600 ml-2">{{ $stats['blocked_users'] }} 차단</span>
                @endif
            </div>
        </div>

        <!-- Vendors -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">제공자</p>
                    <p class="text-2xl font-semibold">{{ number_format($stats['approved_vendors']) }}</p>
                </div>
            </div>
            @if($stats['pending_vendors'] > 0)
                <div class="mt-4">
                    <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}" class="text-sm text-yellow-600 hover:underline">
                        {{ $stats['pending_vendors'] }}건 승인 대기 &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">상품</p>
                    <p class="text-2xl font-semibold">{{ number_format($stats['active_products']) }}</p>
                </div>
            </div>
            @if($stats['pending_products'] > 0)
                <div class="mt-4">
                    <a href="{{ route('admin.products.index', ['status' => 'pending_review']) }}" class="text-sm text-yellow-600 hover:underline">
                        {{ $stats['pending_products'] }}건 검토 대기 &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">이번 달 매출</p>
                    <p class="text-2xl font-semibold">{{ number_format($stats['monthly_revenue']) }}원</p>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                총 매출: {{ number_format($stats['total_revenue']) }}원
            </div>
        </div>
    </div>

    <!-- Booking Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_bookings']) }}</p>
            <p class="text-sm text-gray-500">전체 예약</p>
        </div>
        <div class="bg-yellow-50 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_bookings']) }}</p>
            <p class="text-sm text-gray-500">대기중</p>
        </div>
        <div class="bg-green-50 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['completed_bookings']) }}</p>
            <p class="text-sm text-gray-500">완료</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-gray-600">{{ number_format($stats['cancelled_bookings']) }}</p>
            <p class="text-sm text-gray-500">취소</p>
        </div>
        <div class="bg-red-50 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['no_show_bookings']) }}</p>
            <p class="text-sm text-gray-500">노쇼</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Pending Vendors -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold">승인 대기 제공자</h2>
                <a href="{{ route('admin.vendors.index') }}" class="text-sm text-indigo-600 hover:underline">전체보기</a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($pendingVendors as $vendor)
                    <div class="px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">{{ $vendor->business_name }}</p>
                                <p class="text-sm text-gray-500">{{ $vendor->user->name }} · {{ $vendor->user->email }}</p>
                            </div>
                            <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-sm text-indigo-600 hover:underline">
                                검토
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        대기중인 제공자가 없습니다.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pending Products -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold">검토 대기 상품</h2>
                <a href="{{ route('admin.products.index') }}" class="text-sm text-indigo-600 hover:underline">전체보기</a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($pendingProducts as $product)
                    <div class="px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">{{ $product->getTranslation('ko')?->title ?? '상품' }}</p>
                                <p class="text-sm text-gray-500">{{ $product->vendor->user->name }}</p>
                            </div>
                            <a href="{{ route('admin.products.show', $product) }}" class="text-sm text-indigo-600 hover:underline">
                                검토
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        대기중인 상품이 없습니다.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold">최근 예약</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-sm text-indigo-600 hover:underline">전체보기</a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentBookings->take(5) as $booking)
                    <div class="px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-sm">{{ $booking->product->getTranslation('ko')?->title ?? '상품' }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->user->name }} · {{ $booking->created_at->format('m/d H:i') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $booking->status->color() }}">
                                {{ $booking->status->label() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        예약이 없습니다.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
