<x-layouts.app title="예약 완료">
    <div class="min-h-[calc(100vh-200px)] flex items-center justify-center py-16">
        <div class="max-w-2xl w-full mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Icon -->
            <div class="flex justify-center mb-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            <!-- Success Message -->
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-2">
                예약이 완료되었습니다!
            </h1>
            <p class="text-gray-600 text-center mb-8">
                예약 확인 메일을 발송했습니다.
            </p>

            @php
                // Sample booking data - replace with actual booking data
                $booking = (object)[
                    'id' => 1234,
                    'booking_number' => 'BK-2024-001234',
                    'product_name' => '전주 한옥마을 당일투어',
                    'date' => '2024-12-25',
                    'day_of_week' => '수',
                    'adult_count' => 2,
                    'child_count' => 1,
                    'total_price' => 150000,
                    'status' => 'confirmed', // or 'pending'
                    'status_label' => '확정', // or '승인대기'
                ];
            @endphp

            <!-- Booking Details Card -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <div class="space-y-4">
                    <!-- Booking Number -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-500">예약번호</span>
                        <span class="text-lg font-bold text-gray-900">{{ $booking->booking_number }}</span>
                    </div>

                    <!-- Product Name -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-500">상품</span>
                        <span class="text-base font-semibold text-gray-900">{{ $booking->product_name }}</span>
                    </div>

                    <!-- Date -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-500">날짜</span>
                        <span class="text-base font-medium text-gray-900">
                            {{ date('Y.m.d', strtotime($booking->date)) }} ({{ $booking->day_of_week }})
                        </span>
                    </div>

                    <!-- Guest Count -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-500">인원</span>
                        <span class="text-base font-medium text-gray-900">
                            성인 {{ $booking->adult_count }}@if($booking->child_count > 0), 아동 {{ $booking->child_count }}@endif
                        </span>
                    </div>

                    <!-- Total Price -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-500">총 금액</span>
                        <span class="text-2xl font-bold text-indigo-600">₩{{ number_format($booking->total_price) }}</span>
                    </div>

                    <!-- Status -->
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">상태</span>
                        @if($booking->status === 'confirmed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                {{ $booking->status_label }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                {{ $booking->status_label }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Additional Info for Pending Status -->
            @if($booking->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800 mb-1">승인 대기 중</h3>
                            <p class="text-sm text-yellow-700">
                                가이드가 예약을 확인하는 중입니다. 승인 완료 시 알림을 보내드립니다.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('my.bookings.show', ['locale' => app()->getLocale(), 'booking' => $booking->id]) }}"
                   class="inline-flex items-center justify-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    예약 내역 보기
                </a>

                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}"
                   class="inline-flex items-center justify-center px-8 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    홈으로
                </a>
            </div>

            <!-- Tips Section -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">다음 단계</h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-indigo-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">예약 확인 이메일을 확인해주세요</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-indigo-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">체험 당일 예약 상세 정보를 다시 확인해주세요</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-indigo-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">문의사항이 있으시면 언제든지 가이드에게 메시지를 보내주세요</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-layouts.app>
