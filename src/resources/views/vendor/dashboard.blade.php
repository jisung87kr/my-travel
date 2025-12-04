<x-layouts.vendor>
    <x-slot name="header">대시보드</x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">전체 상품</p>
                    <p class="text-2xl font-semibold">{{ $stats['total_products'] }}</p>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-green-600">{{ $stats['active_products'] }} 활성</span>
                @if($stats['pending_products'] > 0)
                    <span class="text-yellow-600 ml-2">{{ $stats['pending_products'] }} 검토중</span>
                @endif
            </div>
        </div>

        <!-- Pending Bookings -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">대기중 예약</p>
                    <p class="text-2xl font-semibold">{{ $stats['pending_bookings'] }}</p>
                </div>
            </div>
            @if($stats['pending_bookings'] > 0)
                <div class="mt-4">
                    <a href="{{ route('vendor.bookings.index', ['status' => 'pending']) }}" class="text-sm text-blue-600 hover:underline">
                        처리하기 &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- Today's Bookings -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">오늘 예약</p>
                    <p class="text-2xl font-semibold">{{ $stats['today_bookings'] }}</p>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">이번달 매출</p>
                    <p class="text-2xl font-semibold">{{ number_format($stats['monthly_revenue']) }}원</p>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                {{ $stats['monthly_bookings'] }}건 예약
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold">최근 예약</h2>
                <a href="{{ route('vendor.bookings.index') }}" class="text-sm text-blue-600 hover:underline">
                    전체보기
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentBookings as $booking)
                    <div class="px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">{{ $booking->product->getTranslation('ko')?->title ?? '상품' }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->user->name }} · {{ $booking->schedule?->date?->format('Y-m-d') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $booking->status->color() }}">
                                {{ $booking->status->label() }}
                            </span>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            성인 {{ $booking->adult_count }}명
                            @if($booking->child_count > 0)
                                · 아동 {{ $booking->child_count }}명
                            @endif
                            · {{ number_format($booking->total_price) }}원
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        아직 예약이 없습니다.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold">다가오는 예약</h2>
                <a href="{{ route('vendor.schedules.index') }}" class="text-sm text-blue-600 hover:underline">
                    일정 관리
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($upcomingBookings as $booking)
                    <div class="px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">
                                    {{ $booking->schedule?->date?->format('m/d') }}
                                    <span class="text-gray-400 mx-1">·</span>
                                    {{ $booking->product->getTranslation('ko')?->title ?? '상품' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $booking->user->name }}
                                    · 성인 {{ $booking->adult_count }}명
                                    @if($booking->child_count > 0)
                                        · 아동 {{ $booking->child_count }}명
                                    @endif
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $booking->status->color() }}">
                                {{ $booking->status->label() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        다가오는 예약이 없습니다.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h2 class="text-lg font-semibold mb-4">빠른 작업</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('vendor.products.create') }}"
               class="flex items-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-medium">새 상품 등록</p>
                    <p class="text-sm text-gray-500">새로운 관광 상품을 등록합니다</p>
                </div>
            </a>

            <a href="{{ route('vendor.bookings.index', ['status' => 'pending']) }}"
               class="flex items-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-medium">예약 승인 대기</p>
                    <p class="text-sm text-gray-500">승인 대기중인 예약을 확인합니다</p>
                </div>
            </a>

            <a href="{{ route('vendor.schedules.index') }}"
               class="flex items-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-medium">일정/재고 관리</p>
                    <p class="text-sm text-gray-500">날짜별 재고를 설정합니다</p>
                </div>
            </a>
        </div>
    </div>
</x-layouts.vendor>
