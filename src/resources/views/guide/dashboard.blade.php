<x-layouts.guide>
    <x-slot name="header">대시보드</x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Today's Schedule -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 hover:shadow-lg hover:shadow-slate-200/50 transition-shadow duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">오늘 일정</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $todayBookings->count() }}<span class="text-lg font-medium text-slate-400">건</span></p>
                    <p class="text-xs text-slate-400 mt-3">{{ now()->format('Y년 m월 d일') }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-lg shadow-teal-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Weekly Schedule -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 hover:shadow-lg hover:shadow-slate-200/50 transition-shadow duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">이번 주 일정</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $weeklyCount }}<span class="text-lg font-medium text-slate-400">건</span></p>
                    <p class="text-xs text-slate-400 mt-3">{{ now()->startOfWeek()->format('m/d') }} ~ {{ now()->endOfWeek()->format('m/d') }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Monthly Completed -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 hover:shadow-lg hover:shadow-slate-200/50 transition-shadow duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">이번 달 완료</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $monthlyCompleted }}<span class="text-lg font-medium text-slate-400">건</span></p>
                    <p class="text-xs text-slate-400 mt-3">{{ now()->format('Y년 m월') }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Today's Schedule List -->
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">오늘 일정</h2>
                <span class="text-xs text-slate-500">{{ now()->format('Y년 m월 d일') }}</span>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($todayBookings as $booking)
                    <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $booking->product->getTranslation('ko')?->title ?? '투어' }}</p>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $booking->user->name }} ·
                                    성인 {{ $booking->adult_count }}명
                                    @if($booking->child_count > 0)
                                        · 아동 {{ $booking->child_count }}명
                                    @endif
                                </p>
                                <p class="text-xs text-slate-400 mt-1">{{ $booking->product->vendor->business_name ?? '' }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-2 ml-4">
                                @php
                                    $statusColors = [
                                        'confirmed' => 'bg-amber-100 text-amber-700',
                                        'in_progress' => 'bg-blue-100 text-blue-700',
                                        'completed' => 'bg-emerald-100 text-emerald-700',
                                    ];
                                    $statusValue = $booking->status->value ?? $booking->status;
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$statusValue] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $booking->status->label() }}
                                </span>
                                <div class="flex gap-1.5">
                                    @if($booking->status->value === 'confirmed')
                                        <form method="POST" action="{{ route('guide.bookings.start', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2.5 py-1 text-xs font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                시작
                                            </button>
                                        </form>
                                    @endif
                                    @if($booking->status->value === 'in_progress')
                                        <form method="POST" action="{{ route('guide.bookings.complete', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2.5 py-1 text-xs font-medium text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">
                                                완료
                                            </button>
                                        </form>
                                    @endif
                                    @if(in_array($booking->status->value, ['confirmed', 'in_progress']))
                                        <form method="POST" action="{{ route('guide.bookings.no-show', $booking) }}"
                                              onsubmit="return confirm('노쇼로 처리하시겠습니까?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2.5 py-1 text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                                노쇼
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500">오늘 예정된 일정이 없습니다</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Schedule -->
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">다가오는 일정</h2>
                <a href="{{ route('guide.schedules.index') }}" class="text-xs font-medium text-teal-600 hover:text-teal-700 transition-colors">
                    전체보기
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($upcomingBookings as $booking)
                    <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $booking->product->getTranslation('ko')?->title ?? '투어' }}</p>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $booking->user->name }} ·
                                    성인 {{ $booking->adult_count }}명
                                    @if($booking->child_count > 0)
                                        · 아동 {{ $booking->child_count }}명
                                    @endif
                                </p>
                            </div>
                            <div class="text-right ml-4">
                                <p class="text-sm font-medium text-teal-600">{{ $booking->schedule?->date?->format('m/d') }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $booking->schedule?->date?->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500">다가오는 일정이 없습니다</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
        <h2 class="text-sm font-semibold text-slate-900 mb-4">빠른 작업</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('guide.checkin.index') }}"
               class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 hover:border-teal-300 hover:bg-teal-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-lg shadow-teal-500/20 group-hover:shadow-teal-500/30 transition-shadow">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-slate-900">QR 체크인</p>
                    <p class="text-xs text-slate-500 mt-0.5">고객 QR코드를 스캔하여 체크인</p>
                </div>
            </a>

            <a href="{{ route('guide.schedules.index') }}"
               class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 hover:border-teal-300 hover:bg-teal-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/30 transition-shadow">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-slate-900">일정 캘린더</p>
                    <p class="text-xs text-slate-500 mt-0.5">전체 일정을 캘린더로 확인</p>
                </div>
            </a>
        </div>
    </div>
</x-layouts.guide>
