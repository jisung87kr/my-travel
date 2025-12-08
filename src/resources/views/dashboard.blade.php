<x-layouts.app :title="__('nav.dashboard')">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-rose-50/30 to-gray-50">
        <!-- Header Section -->
        <div class="bg-gradient-to-br from-rose-950 via-pink-950 to-fuchsia-950 relative overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute top-0 left-0 w-[50%] h-[50%] bg-gradient-to-br from-pink-500/30 to-transparent rounded-full blur-[80px]"></div>
                <div class="absolute bottom-0 right-0 w-[40%] h-[40%] bg-gradient-to-tl from-fuchsia-500/20 to-transparent rounded-full blur-[60px]"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">
                            안녕하세요, {{ $user->name }}님!
                        </h1>
                        <p class="text-rose-100/80 text-lg">오늘도 특별한 여행을 계획해보세요</p>
                    </div>
                    <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-gray-900 font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 cursor-pointer">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        새로운 체험 찾기
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 -mt-8 relative z-10">
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                <!-- Total Bookings -->
                <div class="bg-white rounded-2xl shadow-sm p-5 sm:p-6 border border-gray-100 hover:shadow-md hover:border-pink-100 transition-all duration-300 cursor-pointer group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-100 to-rose-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">{{ $bookingsStats['total'] }}</p>
                    <p class="text-sm text-gray-500">전체 예약</p>
                </div>

                <!-- Pending -->
                <div class="bg-white rounded-2xl shadow-sm p-5 sm:p-6 border border-gray-100 hover:shadow-md hover:border-amber-100 transition-all duration-300 cursor-pointer group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-amber-500 uppercase tracking-wider">대기중</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">{{ $bookingsStats['pending'] }}</p>
                    <p class="text-sm text-gray-500">확정 대기</p>
                </div>

                <!-- Confirmed -->
                <div class="bg-white rounded-2xl shadow-sm p-5 sm:p-6 border border-gray-100 hover:shadow-md hover:border-cyan-100 transition-all duration-300 cursor-pointer group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-100 to-teal-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-cyan-500 uppercase tracking-wider">확정</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">{{ $bookingsStats['confirmed'] }}</p>
                    <p class="text-sm text-gray-500">예약 확정</p>
                </div>

                <!-- Completed -->
                <div class="bg-white rounded-2xl shadow-sm p-5 sm:p-6 border border-gray-100 hover:shadow-md hover:border-green-100 transition-all duration-300 cursor-pointer group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-green-500 uppercase tracking-wider">완료</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">{{ $bookingsStats['completed'] }}</p>
                    <p class="text-sm text-gray-500">체험 완료</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Upcoming Bookings -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-100 to-rose-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900">다가오는 예약</h2>
                                    <p class="text-sm text-gray-500">예정된 여행 일정</p>
                                </div>
                            </div>
                            <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
                               class="text-sm font-medium text-pink-600 hover:text-pink-700 transition-colors cursor-pointer">
                                전체보기
                            </a>
                        </div>

                        @if($upcomingBookings->count() > 0)
                            <div class="divide-y divide-gray-100">
                                @foreach($upcomingBookings as $booking)
                                    <a href="{{ route('my.booking.detail', ['locale' => app()->getLocale(), 'booking' => $booking['id']]) }}"
                                       class="flex items-center gap-4 p-5 hover:bg-gray-50/50 transition-colors cursor-pointer group">
                                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                                            <img src="{{ $booking['product_image'] }}"
                                                 alt="{{ $booking['product_title'] }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                                      style="background-color: {{ $booking['status']->color() }}15; color: {{ $booking['status']->color() }}">
                                                    {{ $booking['status']->label() }}
                                                </span>
                                            </div>
                                            <h3 class="font-semibold text-gray-900 truncate group-hover:text-pink-600 transition-colors">
                                                {{ $booking['product_title'] }}
                                            </h3>
                                            <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                    </svg>
                                                    {{ $booking['date']->format('Y.m.d') }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                    </svg>
                                                    {{ $booking['total_persons'] }}명
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            @php
                                                $daysLeft = now()->startOfDay()->diffInDays($booking['date'], false);
                                            @endphp
                                            @if($daysLeft == 0)
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-pink-100 text-pink-700">
                                                    D-Day
                                                </span>
                                            @elseif($daysLeft > 0)
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                                    D-{{ $daysLeft }}
                                                </span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-1">예정된 예약이 없습니다</h3>
                                <p class="text-sm text-gray-500 mb-6">새로운 여행을 계획해보세요!</p>
                                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 text-white text-sm font-semibold hover:shadow-lg hover:shadow-pink-500/25 transition-all cursor-pointer">
                                    체험 둘러보기
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Recent Bookings -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-100 to-teal-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900">최근 예약 내역</h2>
                                    <p class="text-sm text-gray-500">최근 5건의 예약</p>
                                </div>
                            </div>
                        </div>

                        @if($recentBookings->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50/50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">상품</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">예약번호</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">일정</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">금액</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">상태</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($recentBookings as $booking)
                                            <tr class="hover:bg-gray-50/50 transition-colors cursor-pointer"
                                                onclick="window.location.href='{{ route('my.booking.detail', ['locale' => app()->getLocale(), 'booking' => $booking['id']]) }}'">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                                                            <img src="{{ $booking['product_image'] }}"
                                                                 alt="{{ $booking['product_title'] }}"
                                                                 class="w-full h-full object-cover">
                                                        </div>
                                                        <span class="font-medium text-gray-900 truncate max-w-[150px]">{{ $booking['product_title'] }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 hidden sm:table-cell">
                                                    <span class="text-sm text-gray-600 font-mono">{{ $booking['booking_code'] }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-sm text-gray-600">{{ $booking['date'] }}</span>
                                                </td>
                                                <td class="px-6 py-4 hidden md:table-cell">
                                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($booking['total_price']) }}원</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                                                          style="background-color: {{ $booking['status']->color() }}15; color: {{ $booking['status']->color() }}">
                                                        {{ $booking['status']->label() }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-1">예약 내역이 없습니다</h3>
                                <p class="text-sm text-gray-500">첫 번째 여행을 예약해보세요!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Links -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-900 mb-4">빠른 메뉴</h3>
                        <div class="space-y-2">
                            <a href="{{ route('my.profile', ['locale' => app()->getLocale()]) }}"
                               class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group">
                                <div class="w-10 h-10 rounded-lg bg-pink-50 flex items-center justify-center group-hover:bg-pink-100 transition-colors">
                                    <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 group-hover:text-pink-600 transition-colors">내 프로필</p>
                                    <p class="text-xs text-gray-500">프로필 정보 관리</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-pink-400 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>

                            <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
                               class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group">
                                <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center group-hover:bg-cyan-100 transition-colors">
                                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 group-hover:text-cyan-600 transition-colors">예약 관리</p>
                                    <p class="text-xs text-gray-500">전체 예약 내역 보기</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-cyan-400 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>

                            <a href="{{ route('my.wishlist', ['locale' => app()->getLocale()]) }}"
                               class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group">
                                <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center group-hover:bg-rose-100 transition-colors">
                                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 group-hover:text-rose-600 transition-colors">위시리스트</p>
                                    <p class="text-xs text-gray-500">{{ $wishlistCount }}개 저장됨</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-rose-400 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>

                            <a href="{{ route('my.reviews', ['locale' => app()->getLocale()]) }}"
                               class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group">
                                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 group-hover:text-amber-600 transition-colors">내 리뷰</p>
                                    <p class="text-xs text-gray-500">{{ $reviewsCount }}개 작성</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-amber-400 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Account Info -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-900 mb-4">계정 정보</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 space-y-3">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">가입일</span>
                                    <span class="font-medium text-gray-900">{{ $user->created_at->format('Y.m.d') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">선호 언어</span>
                                    <span class="font-medium text-gray-900">
                                        @switch($user->preferred_language->value ?? 'ko')
                                            @case('ko') 한국어 @break
                                            @case('en') English @break
                                            @case('zh') 中文 @break
                                            @case('ja') 日本語 @break
                                            @default 한국어
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="bg-gradient-to-br from-pink-500 to-rose-500 rounded-2xl shadow-lg shadow-pink-500/20 p-6 text-white">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                                </svg>
                            </div>
                            <h3 class="font-bold">도움이 필요하신가요?</h3>
                        </div>
                        <p class="text-white/80 text-sm mb-4">
                            여행 관련 문의사항이 있으시면 언제든지 연락주세요.
                        </p>
                        <a href="#"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white text-pink-600 text-sm font-semibold hover:bg-white/90 transition-colors cursor-pointer">
                            고객센터 문의
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
