<x-layouts.app title="예약하기">
    <!-- Progress Bar -->
    <div class="bg-white border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-center gap-4">
                <!-- Step 1 - Active -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-full font-semibold">
                        1
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-900">정보입력</span>
                </div>

                <div class="w-16 h-1 bg-gray-200"></div>

                <!-- Step 2 -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-200 text-gray-500 rounded-full font-semibold">
                        2
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">확인</span>
                </div>

                <div class="w-16 h-1 bg-gray-200"></div>

                <!-- Step 3 -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-200 text-gray-500 rounded-full font-semibold">
                        3
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">완료</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content - Booking Form -->
            <div class="lg:col-span-2">
                <div id="booking-form-app">
                    <!-- Vue component will mount here -->
                </div>
            </div>

            <!-- Sidebar - Order Summary (Sticky) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-card p-6 sticky top-24">
                    @php
                        // Sample product data - replace with actual product data
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
                    <div class="mb-4">
                        <img src="{{ $product->image }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover rounded-lg"
                             onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                    </div>

                    <!-- Product Name -->
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        {{ $product->name }}
                    </h3>

                    <!-- Date & Guest Info (Dynamic) -->
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span id="summary-date">날짜 선택 필요</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span id="summary-guests">성인 2, 아동 0</span>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="mb-4 space-y-2">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span id="adult-breakdown">성인 2 × ₩50,000</span>
                            <span id="adult-total">₩100,000</span>
                        </div>
                        <div id="child-row" class="flex justify-between text-sm text-gray-600" style="display: none;">
                            <span id="child-breakdown">아동 0 × ₩30,000</span>
                            <span id="child-total">₩0</span>
                        </div>
                        <div id="options-row" class="flex justify-between text-sm text-gray-600" style="display: none;">
                            <span>옵션</span>
                            <span id="options-total">₩0</span>
                        </div>
                    </div>

                    <!-- Total Price -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">총 금액</span>
                            <span id="total-price" class="text-2xl font-bold text-indigo-600">₩100,000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Sample data - Replace with actual data from backend
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

        // This would be replaced with Vue component initialization
        // import { createApp } from 'vue';
        // import BookingForm from '@/components/booking/BookingForm.vue';
        //
        // createApp(BookingForm, {
        //     product: productData,
        //     initialDate: initialDate,
        //     initialAdults: initialAdults,
        //     initialChildren: initialChildren,
        //     options: options
        // }).mount('#booking-form-app');
    </script>
    @endpush
</x-layouts.app>
