<x-traveler.my.layout :title="__('nav.booking_detail')">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span class="text-sm font-medium">목록으로</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Status Card -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">예약번호</p>
                            <p class="text-lg font-bold text-gray-900">{{ $booking->booking_code }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold"
                              style="background-color: {{ $booking->status->color() }}15; color: {{ $booking->status->color() }}">
                            {{ $booking->status->label() }}
                        </span>
                    </div>

                    <!-- Timeline -->
                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-2 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>예약일: {{ $booking->created_at->format('Y.m.d H:i') }}</span>
                            </div>
                            @if($booking->confirmed_at)
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>확정일: {{ $booking->confirmed_at->format('Y.m.d H:i') }}</span>
                                </div>
                            @endif
                            @if($booking->cancelled_at)
                                <div class="flex items-center gap-2 text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>취소일: {{ $booking->cancelled_at->format('Y.m.d H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="flex flex-col sm:flex-row">
                    <!-- Product Image -->
                    <div class="sm:w-48 h-48 sm:h-auto flex-shrink-0">
                        <img src="{{ $booking->product->images->first()?->url ?? 'https://placehold.co/300x300?text=NO+IMAGE' }}"
                             alt="{{ $translation?->title }}"
                             class="w-full h-full object-cover">
                    </div>
                    <!-- Product Details -->
                    <div class="flex-1 p-6">
                        <div class="flex items-start gap-2 mb-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-pink-50 text-pink-700">
                                {{ $booking->product->type->label() }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $booking->product->region->label() }}
                            </span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            {{ $translation?->title ?? $booking->product->getTranslation('ko')?->title }}
                        </h2>
                        <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $booking->product->slug]) }}"
                           class="inline-flex items-center gap-1 text-sm text-pink-600 hover:text-pink-700 font-medium">
                            상품 상세보기
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Schedule Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">일정 정보</h3>
                        <p class="text-sm text-gray-500">예약하신 일정입니다</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">날짜</p>
                            <p class="font-semibold text-gray-900">{{ $booking->schedule->date->format('Y년 m월 d일') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">시간</p>
                            <p class="font-semibold text-gray-900">{{ $booking->schedule->start_time?->format('H:i') ?? '시간 미정' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participant Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">참여 인원</h3>
                        <p class="text-sm text-gray-500">총 {{ $booking->total_persons }}명</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-2xl font-bold text-gray-900">{{ $booking->adult_count }}</p>
                        <p class="text-sm text-gray-500">{{ __('booking.adults') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-2xl font-bold text-gray-900">{{ $booking->child_count }}</p>
                        <p class="text-sm text-gray-500">{{ __('booking.children') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-2xl font-bold text-gray-900">{{ $booking->infant_count }}</p>
                        <p class="text-sm text-gray-500">{{ __('booking.infants') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">예약자 정보</h3>
                        <p class="text-sm text-gray-500">예약 시 입력한 정보입니다</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">이름</p>
                            <p class="font-medium text-gray-900">{{ $booking->contact_name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">이메일</p>
                            <p class="font-medium text-gray-900">{{ $booking->contact_email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">연락처</p>
                            <p class="font-medium text-gray-900">{{ $booking->contact_phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Special Request -->
            @if($booking->special_request)
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">특별 요청사항</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 bg-gray-50 p-4 rounded-xl">{{ $booking->special_request }}</p>
                </div>
            @endif

            <!-- Cancellation Info -->
            @if($booking->isCancelled() && $booking->cancellation_reason)
                <div class="bg-red-50 rounded-2xl p-6 border border-red-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-red-900">취소 사유</h3>
                        </div>
                    </div>
                    <p class="text-red-700">{{ $booking->cancellation_reason }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-32 space-y-6">
                <!-- Price Summary -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">결제 정보</h3>

                    <div class="space-y-3 pb-4 border-b border-gray-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">성인 {{ $booking->adult_count }}명</span>
                            <span class="text-gray-900">-</span>
                        </div>
                        @if($booking->child_count > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">아동 {{ $booking->child_count }}명</span>
                                <span class="text-gray-900">-</span>
                            </div>
                        @endif
                        @if($booking->infant_count > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">유아 {{ $booking->infant_count }}명</span>
                                <span class="text-gray-900">무료</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center pt-4">
                        <span class="font-bold text-gray-900">총 결제금액</span>
                        <span class="text-2xl font-bold text-pink-600">{{ number_format($booking->total_price) }}원</span>
                    </div>
                </div>

                <!-- Vendor Info -->
                @if($booking->product->vendor)
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">판매자 정보</h3>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center text-white font-bold">
                                {{ mb_substr($booking->product->vendor->user->name ?? 'V', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $booking->product->vendor->company_name ?? $booking->product->vendor->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->product->vendor->user->email ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-3">
                    @if($booking->canBeCancelled())
                        <button type="button"
                                onclick="confirmCancel()"
                                class="w-full px-4 py-3 bg-white border border-red-200 text-red-600 rounded-xl font-semibold hover:bg-red-50 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            예약 취소
                        </button>
                    @endif

                    @if($booking->isCompleted() && !$booking->review)
                        <a href="{{ route('my.reviews', ['locale' => app()->getLocale()]) }}"
                           class="w-full px-4 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-pink-500/25 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                            리뷰 작성하기
                        </a>
                    @endif

                    <a href="{{ route('messages.thread', ['locale' => app()->getLocale(), 'booking' => $booking->id]) }}"
                       class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                        </svg>
                        문의하기
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmCancel() {
            if (confirm('정말로 예약을 취소하시겠습니까?\n취소 후에는 복구할 수 없습니다.')) {
                // TODO: Implement cancel booking logic
                alert('예약 취소 기능은 준비 중입니다.');
            }
        }
    </script>
</x-traveler.my.layout>