<x-layouts.app title="예약 완료">
    <!-- Progress Bar -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-center gap-2 sm:gap-4">
                <!-- Step 1 - Completed -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-500 text-white rounded-xl font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500 hidden sm:inline">정보입력</span>
                </div>

                <div class="w-8 sm:w-16 h-0.5 bg-green-500 rounded-full"></div>

                <!-- Step 2 - Completed -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-500 text-white rounded-xl font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500 hidden sm:inline">확인</span>
                </div>

                <div class="w-8 sm:w-16 h-0.5 bg-green-500 rounded-full"></div>

                <!-- Step 3 - Active -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl font-semibold shadow-lg shadow-pink-500/25">
                        3
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-900 hidden sm:inline">완료</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 min-h-[calc(100vh-200px)]">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @php
                $bookingDate = $booking->schedule?->date ?? $booking->created_at;
                if (is_string($bookingDate)) {
                    $bookingDate = \Carbon\Carbon::parse($bookingDate);
                }
                $days = ['일', '월', '화', '수', '목', '금', '토'];
                $dayOfWeek = $days[$bookingDate->dayOfWeek];

                // 상품명 가져오기
                $locale = app()->getLocale();
                $productTitle = $booking->product->translations->where('locale', $locale)->first()?->title
                    ?? $booking->product->translations->where('locale', 'ko')->first()?->title
                    ?? $booking->product->slug;
            @endphp

            <!-- Success Animation -->
            <div class="text-center mb-8">
                <div class="relative inline-block">
                    <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center shadow-xl shadow-green-500/30 animate-bounce-slow">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <!-- Confetti dots -->
                    <div class="absolute -top-2 -left-4 w-3 h-3 bg-pink-400 rounded-full animate-ping"></div>
                    <div class="absolute -top-4 right-0 w-2 h-2 bg-amber-400 rounded-full animate-ping animation-delay-200"></div>
                    <div class="absolute top-0 -right-6 w-3 h-3 bg-blue-400 rounded-full animate-ping animation-delay-400"></div>
                </div>
            </div>

            <!-- Success Message -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    예약이 완료되었습니다!
                </h1>
                <p class="text-gray-500">
                    예약 확인 메일을 {{ auth()->user()->email ?? 'email@example.com' }}로 발송했습니다.
                </p>
            </div>

            <!-- Booking Details Card -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-pink-500 to-rose-500 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="text-white">
                            <p class="text-sm opacity-90">예약번호</p>
                            <p class="text-xl font-bold">{{ $booking->booking_code }}</p>
                        </div>
                        @if($booking->status->value === 'confirmed')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-white/20 text-white backdrop-blur-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                {{ $booking->status->label() }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-amber-100 text-amber-800">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                {{ $booking->status->label() }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6 space-y-4">
                    <!-- Product Name -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-pink-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500 mb-1">상품</p>
                            <p class="font-semibold text-gray-900">{{ $productTitle }}</p>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500 mb-1">날짜</p>
                            <p class="font-semibold text-gray-900">
                                {{ $bookingDate->format('Y년 m월 d일') }} ({{ $dayOfWeek }})
                            </p>
                        </div>
                    </div>

                    <!-- Guest Count -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500 mb-1">인원</p>
                            <p class="font-semibold text-gray-900">
                                성인 {{ $booking->adult_count }}명@if($booking->child_count > 0), 아동 {{ $booking->child_count }}명@endif
                            </p>
                        </div>
                    </div>

                    <!-- Total Price -->
                    <div class="pt-4 mt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <span class="text-base font-medium text-gray-700">총 결제 금액</span>
                            <span class="text-2xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">
                                ₩{{ number_format($booking->total_price) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Status Warning -->
            @if($booking->status->value === 'pending')
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-amber-800 mb-1">승인 대기 중</h3>
                            <p class="text-sm text-amber-700">
                                가이드가 예약을 확인하는 중입니다. 승인 완료 시 알림을 보내드립니다.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 mb-8">
                <a href="{{ route('my.booking.detail', ['locale' => app()->getLocale(), 'booking' => $booking->id]) }}"
                   class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                    예약 내역 보기
                </a>

                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}"
                   class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    홈으로
                </a>
            </div>

            <!-- Next Steps -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    다음 단계
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-pink-100 flex items-center justify-center mt-0.5">
                            <span class="text-xs font-semibold text-pink-600">1</span>
                        </div>
                        <span class="text-gray-700">예약 확인 이메일을 확인해주세요</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-pink-100 flex items-center justify-center mt-0.5">
                            <span class="text-xs font-semibold text-pink-600">2</span>
                        </div>
                        <span class="text-gray-700">체험 당일 예약 상세 정보를 다시 확인해주세요</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-pink-100 flex items-center justify-center mt-0.5">
                            <span class="text-xs font-semibold text-pink-600">3</span>
                        </div>
                        <span class="text-gray-700">문의사항이 있으시면 언제든지 가이드에게 메시지를 보내주세요</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        @keyframes bounce-slow {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        .animate-bounce-slow {
            animation: bounce-slow 2s ease-in-out infinite;
        }
        .animation-delay-200 {
            animation-delay: 0.2s;
        }
        .animation-delay-400 {
            animation-delay: 0.4s;
        }
    </style>
</x-layouts.app>
