<x-layouts.guide>
    <x-slot name="header">대시보드</x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-teal-100 text-teal-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">오늘 일정</p>
                    <p class="text-2xl font-semibold">{{ $todayBookings->count() }}건</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">이번 주 일정</p>
                    <p class="text-2xl font-semibold">{{ $weeklyCount }}건</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">이번 달 완료</p>
                    <p class="text-2xl font-semibold">{{ $monthlyCompleted }}건</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Schedule -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold">오늘 일정</h2>
                <span class="text-sm text-gray-500">{{ now()->format('Y년 m월 d일') }}</span>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($todayBookings as $booking)
                    <div class="px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">{{ $booking->product->getTranslation('ko')?->title ?? '투어' }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->user->name }} · {{ $booking->quantity }}명</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $booking->product->vendor->business_name }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="px-2 py-1 text-xs rounded-full {{ $booking->status->color() }}">
                                    {{ $booking->status->label() }}
                                </span>
                                <div class="flex gap-2">
                                    @if($booking->status->value === 'confirmed')
                                        <form method="POST" action="{{ route('guide.bookings.start', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs text-blue-600 hover:underline">시작</button>
                                        </form>
                                    @endif
                                    @if($booking->status->value === 'in_progress')
                                        <form method="POST" action="{{ route('guide.bookings.complete', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs text-green-600 hover:underline">완료</button>
                                        </form>
                                    @endif
                                    @if(in_array($booking->status->value, ['confirmed', 'in_progress']))
                                        <form method="POST" action="{{ route('guide.bookings.no-show', $booking) }}"
                                              onsubmit="return confirm('노쇼로 처리하시겠습니까?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs text-red-600 hover:underline">노쇼</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-500">
                        오늘 예정된 일정이 없습니다.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Schedule -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold">다가오는 일정</h2>
                <a href="{{ route('guide.schedules.index') }}" class="text-sm text-teal-600 hover:underline">전체보기</a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($upcomingBookings as $booking)
                    <div class="px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">{{ $booking->product->getTranslation('ko')?->title ?? '투어' }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->user->name }} · {{ $booking->quantity }}명</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-teal-600">{{ $booking->booking_date->format('m/d') }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->booking_date->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-500">
                        다가오는 일정이 없습니다.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6">
        <a href="{{ route('guide.checkin.index') }}"
           class="inline-flex items-center px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
            QR 체크인
        </a>
    </div>
</x-layouts.guide>
