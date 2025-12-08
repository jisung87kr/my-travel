<x-traveler.my.layout :title="__('nav.my_bookings')">
    <!-- Status Filter -->
    <div class="mb-6">
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all duration-200 cursor-pointer
                      {{ !request('status') ? 'bg-gray-900 text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                전체
            </a>
            @php
                $statusConfig = [
                    'pending' => ['label' => '대기중', 'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'confirmed' => ['label' => '확정', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'completed' => ['label' => '완료', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z'],
                    'cancelled' => ['label' => '취소', 'icon' => 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
            @endphp
            @foreach($statusConfig as $status => $config)
                <a href="{{ route('my.bookings', ['locale' => app()->getLocale(), 'status' => $status]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all duration-200 cursor-pointer
                          {{ request('status') === $status ? 'bg-gray-900 text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}" />
                    </svg>
                    {{ $config['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gray-100 flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">예약 내역이 없습니다</h3>
            <p class="text-gray-500 mb-6">새로운 여행을 시작해보세요!</p>
            <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                체험 둘러보기
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <a href="{{ route('my.booking.detail', ['locale' => app()->getLocale(), 'booking' => $booking['id']]) }}"
                   class="block bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-all duration-200 cursor-pointer group">
                    <div class="flex flex-col sm:flex-row">
                        <!-- Image -->
                        <div class="sm:w-52 h-40 sm:h-auto flex-shrink-0 relative overflow-hidden">
                            <img src="{{ $booking['product_image'] }}"
                                 alt="{{ $booking['product_title'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <!-- Status Badge on Image (Mobile) -->
                            <div class="sm:hidden absolute top-3 left-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold"
                                      style="background-color: {{ $booking['status_color'] }}15; color: {{ $booking['status_color'] }}">
                                    {{ $booking['status_label'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 p-5 sm:p-6">
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div class="flex-1">
                                    <!-- Status Badge (Desktop) -->
                                    <span class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold mb-2"
                                          style="background-color: {{ $booking['status_color'] }}15; color: {{ $booking['status_color'] }}">
                                        {{ $booking['status_label'] }}
                                    </span>
                                    <h3 class="font-bold text-gray-900 text-lg group-hover:text-pink-600 transition-colors">
                                        {{ $booking['product_title'] }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $booking['booking_code'] }}</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xl font-bold text-gray-900">{{ $booking['formatted_price'] }}</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                    </svg>
                                    {{ $booking['formatted_date'] }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    {{ $booking['adult_count'] }} {{ __('booking.adults') }}
                                    @if($booking['child_count'] > 0)
                                        , {{ $booking['child_count'] }} {{ __('booking.children') }}
                                    @endif
                                </div>
                            </div>

                            <!-- Arrow indicator -->
                            <div class="hidden sm:flex items-center justify-end mt-4">
                                <span class="text-sm text-gray-400 group-hover:text-pink-500 transition-colors flex items-center gap-1">
                                    상세보기
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @endif

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-traveler.my.layout>
