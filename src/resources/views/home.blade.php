<x-layouts.app title="한국의 특별한 순간을 만나다">
    <!-- Hero Section - Full Screen -->
    <section class="relative h-screen min-h-[600px] flex items-center justify-center">
        <!-- Background with gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500">
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 tracking-tight">
                한국의 특별한 순간을 만나다
            </h1>
            <p class="text-xl sm:text-2xl text-white/90 mb-12 max-w-2xl mx-auto">
                현지 가이드와 함께하는 프리미엄 투어 & 액티비티
            </p>

            <!-- Search Widget -->
            <div class="bg-white rounded-2xl shadow-2xl p-4 sm:p-6 max-w-3xl mx-auto">
                <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Location -->
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">위치</label>
                            <input type="text"
                                   name="location"
                                   placeholder="어디로 가시나요?"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        </div>

                        <!-- Date -->
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">날짜</label>
                            <input type="date"
                                   name="date"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        </div>

                        <!-- Guests -->
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">인원</label>
                            <select name="guests"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                                <option value="">인원 선택</option>
                                <option value="1">1명</option>
                                <option value="2">2명</option>
                                <option value="3">3명</option>
                                <option value="4">4명</option>
                                <option value="5">5명+</option>
                            </select>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <button type="submit"
                            class="mt-6 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-4 rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl">
                        검색하기
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Category Navigation - Horizontal Scroll -->
    <section class="py-8 bg-white border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-6 overflow-x-auto scrollbar-hide pb-2">
                <!-- Tour -->
                <a href="#" class="flex flex-col items-center gap-2 flex-shrink-0 group">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-600 transition">투어</span>
                </a>

                <!-- Activity -->
                <a href="#" class="flex flex-col items-center gap-2 flex-shrink-0 group">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-600 transition">액티비티</span>
                </a>

                <!-- Culture -->
                <a href="#" class="flex flex-col items-center gap-2 flex-shrink-0 group">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-600 transition">문화체험</span>
                </a>

                <!-- Food -->
                <a href="#" class="flex flex-col items-center gap-2 flex-shrink-0 group">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-600 transition">식도락</span>
                </a>

                <!-- Nature -->
                <a href="#" class="flex flex-col items-center gap-2 flex-shrink-0 group">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-600 transition">자연</span>
                </a>

                <!-- Night -->
                <a href="#" class="flex flex-col items-center gap-2 flex-shrink-0 group">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-600 transition">야간투어</span>
                </a>

                <!-- Ticket -->
                <a href="#" class="flex flex-col items-center gap-2 flex-shrink-0 group">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-600 transition">티켓</span>
                </a>

                <!-- Package -->
                <a href="#" class="flex flex-col items-center gap-2 flex-shrink-0 group">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-600 transition">패키지</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Recommended Products - 4 Column Grid -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">추천 체험</h2>
                <p class="text-gray-600">여행자들이 사랑하는 인기 체험</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($recommendedProducts as $product)
                    <x-product.card :product="(object) $product" :showWishlist="true" />
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        등록된 상품이 없습니다.
                    </div>
                @endforelse
            </div>

            <!-- View All Button -->
            <div class="mt-10 text-center">
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                   class="inline-flex items-center gap-2 px-8 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-semibold rounded-lg transition">
                    <span>모든 체험 보기</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Regions - 6 Column Grid -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">지역별 탐색</h2>
                <p class="text-gray-600">한국의 매력적인 여행지를 만나보세요</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($regions as $region)
                    <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'region' => $region['value']]) }}"
                       class="relative group overflow-hidden rounded-xl aspect-square shadow-md hover:shadow-xl transition-all duration-300">
                        <!-- Background Image -->
                        <div class="absolute inset-0">
                            <img src="{{ $region['image'] }}"
                                 alt="{{ $region['name'] }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                 onerror="this.src='https://placehold.co/400x400?text={{ urlencode($region['name']) }}'">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                        </div>

                        <!-- Content -->
                        <div class="relative h-full flex flex-col justify-end p-4">
                            <h3 class="text-white font-bold text-xl mb-1">{{ $region['name'] }}</h3>
                            <p class="text-white/90 text-sm">{{ $region['count'] }}개의 체험</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Popular Experiences - Horizontal Scroll -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">인기 체험</h2>
                <p class="text-gray-600">최고 평점을 받은 특별한 체험들</p>
            </div>

            <div class="flex gap-6 overflow-x-auto scrollbar-hide pb-4">
                @forelse($popularProducts as $product)
                    <div class="flex-shrink-0 w-80">
                        <x-product.card :product="(object) $product" :showWishlist="true" />
                    </div>
                @empty
                    <div class="w-full text-center py-12 text-gray-500">
                        등록된 상품이 없습니다.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Custom Styles for Scrollbar Hide -->
    @push('head')
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @endpush

    <!-- Wishlist Toggle Script -->
    @push('scripts')
    <script>
        function toggleWishlist(productId) {
            // TODO: Implement wishlist toggle functionality
            console.log('Toggle wishlist for product:', productId);

            // Example AJAX call (to be implemented)
            // fetch(`/wishlist/toggle/${productId}`, {
            //     method: 'POST',
            //     headers: {
            //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            //         'Content-Type': 'application/json',
            //     }
            // }).then(response => response.json())
            //   .then(data => {
            //       // Update UI
            //   });
        }
    </script>
    @endpush
</x-layouts.app>
