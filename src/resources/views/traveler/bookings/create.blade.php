<x-layouts.app title="예약하기">
    <!-- Progress Bar -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-center gap-2 sm:gap-4">
                <!-- Step 1 - Active -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl font-semibold shadow-lg shadow-pink-500/25">
                        1
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-900 hidden sm:inline">정보입력</span>
                </div>

                <div class="w-8 sm:w-16 h-0.5 bg-gray-200 rounded-full"></div>

                <!-- Step 2 -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-400 rounded-xl font-semibold">
                        2
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-400 hidden sm:inline">확인</span>
                </div>

                <div class="w-8 sm:w-16 h-0.5 bg-gray-200 rounded-full"></div>

                <!-- Step 3 -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-400 rounded-xl font-semibold">
                        3
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-400 hidden sm:inline">완료</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content - Booking Form -->
                <div class="lg:col-span-2 order-2 lg:order-1">
                    <div id="booking-form-app">
                        <!-- Vue component placeholder - Static form preview -->
                        <div class="space-y-6">
                            <!-- Date Selection -->
                            <div class="bg-white rounded-2xl shadow-sm p-6">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-10 h-10 rounded-xl bg-pink-50 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-bold text-gray-900">날짜 선택</h2>
                                        <p class="text-sm text-gray-500">원하는 체험 날짜를 선택하세요</p>
                                    </div>
                                </div>
                                <div class="p-4 rounded-xl bg-gray-50 border-2 border-dashed border-gray-200 text-center text-gray-500">
                                    캘린더가 여기에 표시됩니다
                                </div>
                            </div>

                            <!-- Guest Count -->
                            <div class="bg-white rounded-2xl shadow-sm p-6">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-bold text-gray-900">인원 선택</h2>
                                        <p class="text-sm text-gray-500">체험에 참여할 인원수를 선택하세요</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <!-- Adults -->
                                    <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50">
                                        <div>
                                            <p class="font-medium text-gray-900">성인</p>
                                            <p class="text-sm text-gray-500">만 13세 이상</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                </svg>
                                            </button>
                                            <span class="w-8 text-center font-semibold text-gray-900">2</span>
                                            <button type="button" class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Children -->
                                    <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50">
                                        <div>
                                            <p class="font-medium text-gray-900">아동</p>
                                            <p class="text-sm text-gray-500">만 3~12세</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                </svg>
                                            </button>
                                            <span class="w-8 text-center font-semibold text-gray-900">0</span>
                                            <button type="button" class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="bg-white rounded-2xl shadow-sm p-6">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-bold text-gray-900">예약자 정보</h2>
                                        <p class="text-sm text-gray-500">연락 가능한 정보를 입력해주세요</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">이름</label>
                                        <input type="text" placeholder="홍길동" class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">이메일</label>
                                        <input type="email" placeholder="email@example.com" class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">연락처</label>
                                        <input type="tel" placeholder="010-0000-0000" class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">요청사항 (선택)</label>
                                        <textarea rows="3" placeholder="특별한 요청사항이 있으시면 입력해주세요" class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors resize-none"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button (Mobile) -->
                            <div class="lg:hidden">
                                <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300 cursor-pointer">
                                    예약하기
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Order Summary (Sticky) -->
                <div class="lg:col-span-1 order-1 lg:order-2">
                    <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-24">
                        @php
                            $product = (object)[
                                'id' => 4,
                                'name' => '전주 한옥마을 당일투어',
                                'image' => 'https://images.unsplash.com/photo-1583417319070-4a69db38a482?w=400',
                                'adultPrice' => 50000,
                                'childPrice' => 30000,
                            ];

                            $options = [
                                (object)['id' => 1, 'name' => '픽업 서비스', 'price' => 20000],
                                (object)['id' => 2, 'name' => '점심 포함', 'price' => 15000],
                            ];
                        @endphp

                        <!-- Product Image -->
                        <div class="relative mb-4 rounded-xl overflow-hidden">
                            <img src="{{ $product->image }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-40 object-cover"
                                 onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                        </div>

                        <!-- Product Name -->
                        <h3 class="text-lg font-bold text-gray-900 mb-4">
                            {{ $product->name }}
                        </h3>

                        <!-- Date & Guest Info -->
                        <div class="mb-4 pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 mb-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <span id="summary-date" class="text-sm font-medium text-gray-900">날짜 선택 필요</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                <span id="summary-guests" class="text-sm font-medium text-gray-900">성인 2, 아동 0</span>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="mb-4 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span id="adult-breakdown" class="text-gray-600">성인 2 × ₩50,000</span>
                                <span id="adult-total" class="font-medium text-gray-900">₩100,000</span>
                            </div>
                            <div id="child-row" class="flex justify-between text-sm" style="display: none;">
                                <span id="child-breakdown" class="text-gray-600">아동 0 × ₩30,000</span>
                                <span id="child-total" class="font-medium text-gray-900">₩0</span>
                            </div>
                            <div id="options-row" class="flex justify-between text-sm" style="display: none;">
                                <span class="text-gray-600">옵션</span>
                                <span id="options-total" class="font-medium text-gray-900">₩0</span>
                            </div>
                        </div>

                        <!-- Total Price -->
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-base font-bold text-gray-900">총 금액</span>
                                <span id="total-price" class="text-2xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">₩100,000</span>
                            </div>

                            <!-- Submit Button (Desktop) -->
                            <button type="submit" class="hidden lg:block w-full py-3.5 px-6 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300 cursor-pointer">
                                예약하기
                            </button>
                        </div>

                        <!-- Trust Badges -->
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>무료 취소 (24시간 전)</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>즉시 확정</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>안전 결제</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const productData = {
            id: {{ $product->id }},
            name: '{{ $product->name }}',
            image: '{{ $product->image }}',
            adultPrice: {{ $product->adultPrice }},
            childPrice: {{ $product->childPrice }}
        };

        const initialDate = '2024-12-25';
        const initialAdults = 2;
        const initialChildren = 0;

        const options = @json($options);
    </script>
    @endpush
</x-layouts.app>
