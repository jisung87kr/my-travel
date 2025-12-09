<x-layouts.vendor>
    <x-slot name="header">대시보드</x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 hover:shadow-lg hover:shadow-slate-200/50 transition-shadow duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">전체 상품</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['total_products']) }}</p>
                    <div class="flex items-center gap-2 mt-3">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                            {{ $stats['active_products'] }} 활성
                        </span>
                        @if($stats['pending_products'] > 0)
                            <span class="text-xs text-amber-600">{{ $stats['pending_products'] }} 검토중</span>
                        @endif
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Bookings -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 hover:shadow-lg hover:shadow-slate-200/50 transition-shadow duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">대기중 예약</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['pending_bookings']) }}</p>
                    @if($stats['pending_bookings'] > 0)
                        <a href="{{ route('vendor.bookings.index', ['status' => 'pending']) }}"
                           class="inline-flex items-center gap-1 mt-3 text-xs font-medium text-amber-600 hover:text-amber-700 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            처리하기
                        </a>
                    @else
                        <p class="text-xs text-slate-400 mt-3">모두 처리완료</p>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Bookings -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 hover:shadow-lg hover:shadow-slate-200/50 transition-shadow duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">오늘 예약</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['today_bookings']) }}</p>
                    <p class="text-xs text-slate-400 mt-3">{{ now()->format('Y년 m월 d일') }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 hover:shadow-lg hover:shadow-slate-200/50 transition-shadow duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">이번달 매출</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['monthly_revenue'] / 10000) }}<span class="text-lg font-medium text-slate-400">만원</span></p>
                    <p class="text-xs text-slate-400 mt-3">{{ $stats['monthly_bookings'] }}건 예약</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center shadow-lg shadow-violet-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">최근 예약</h2>
                <a href="{{ route('vendor.bookings.index') }}" class="text-xs font-medium text-violet-600 hover:text-violet-700 transition-colors">
                    전체보기
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentBookings as $booking)
                    <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $booking->product->getTranslation('ko')?->title ?? '상품' }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $booking->user->name }} · {{ $booking->schedule?->date?->format('Y-m-d') }}</p>
                                <div class="mt-2 text-xs text-slate-600">
                                    성인 {{ $booking->adult_count }}명
                                    @if($booking->child_count > 0)
                                        · 아동 {{ $booking->child_count }}명
                                    @endif
                                    · <span class="font-medium">{{ number_format($booking->total_price) }}원</span>
                                </div>
                            </div>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'confirmed' => 'bg-blue-100 text-blue-700',
                                    'in_progress' => 'bg-indigo-100 text-indigo-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'cancelled' => 'bg-slate-100 text-slate-600',
                                    'no_show' => 'bg-red-100 text-red-700',
                                ];
                                $statusValue = $booking->status->value ?? $booking->status;
                            @endphp
                            <span class="ml-3 px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$statusValue] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $booking->status->label() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500">아직 예약이 없습니다</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">다가오는 예약</h2>
                <a href="{{ route('vendor.schedules.index') }}" class="text-xs font-medium text-violet-600 hover:text-violet-700 transition-colors">
                    일정 관리
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($upcomingBookings as $booking)
                    <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-violet-100 text-violet-700 text-xs font-medium">
                                        {{ $booking->schedule?->date?->format('m/d') }}
                                    </span>
                                    <p class="text-sm font-medium text-slate-900 truncate">{{ $booking->product->getTranslation('ko')?->title ?? '상품' }}</p>
                                </div>
                                <p class="text-xs text-slate-500 mt-1.5">
                                    {{ $booking->user->name }}
                                    · 성인 {{ $booking->adult_count }}명
                                    @if($booking->child_count > 0)
                                        · 아동 {{ $booking->child_count }}명
                                    @endif
                                </p>
                            </div>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'confirmed' => 'bg-blue-100 text-blue-700',
                                    'in_progress' => 'bg-indigo-100 text-indigo-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'cancelled' => 'bg-slate-100 text-slate-600',
                                    'no_show' => 'bg-red-100 text-red-700',
                                ];
                                $statusValue = $booking->status->value ?? $booking->status;
                            @endphp
                            <span class="ml-3 px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$statusValue] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $booking->status->label() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500">다가오는 예약이 없습니다</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
        <h2 class="text-sm font-semibold text-slate-900 mb-4">빠른 작업</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('vendor.products.create') }}"
               class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 hover:border-violet-300 hover:bg-violet-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/30 transition-shadow">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-slate-900">새 상품 등록</p>
                    <p class="text-xs text-slate-500 mt-0.5">새로운 관광 상품을 등록합니다</p>
                </div>
            </a>

            <a href="{{ route('vendor.bookings.index', ['status' => 'pending']) }}"
               class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 hover:border-violet-300 hover:bg-violet-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20 group-hover:shadow-amber-500/30 transition-shadow">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-slate-900">예약 승인 대기</p>
                    <p class="text-xs text-slate-500 mt-0.5">승인 대기중인 예약을 확인합니다</p>
                </div>
            </a>

            <a href="{{ route('vendor.schedules.index') }}"
               class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 hover:border-violet-300 hover:bg-violet-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:shadow-emerald-500/30 transition-shadow">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-slate-900">일정/재고 관리</p>
                    <p class="text-xs text-slate-500 mt-0.5">날짜별 재고를 설정합니다</p>
                </div>
            </a>
        </div>
    </div>
</x-layouts.vendor>
