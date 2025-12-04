<x-layouts.app :title="__('nav.my_reviews')">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('nav.my_reviews') }}</h1>

        @php
            // Sample data - hardcoded reviews
            $reviews = collect([
                [
                    'id' => 1,
                    'product_id' => 1,
                    'product_title' => '전주 한옥마을 당일투어',
                    'product_image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=400&h=300&fit=crop',
                    'rating' => 5,
                    'content' => '정말 멋진 투어였습니다! 가이드님이 친절하시고 한옥마을의 역사와 문화를 잘 설명해주셨어요. 맛있는 전주 비빔밥도 먹고 한복 체험도 할 수 있어서 좋았습니다. 가족들과 함께 방문했는데 모두 만족했어요.',
                    'created_at' => '2024-11-20',
                    'booking_id' => 'BK-2024-001234',
                ],
                [
                    'id' => 2,
                    'product_id' => 2,
                    'product_title' => '남이섬 + 강촌 레일바이크',
                    'product_image' => 'https://images.unsplash.com/photo-1583417319070-4a69db38a482?w=400&h=300&fit=crop',
                    'rating' => 4,
                    'content' => '남이섬의 가을 풍경이 너무 아름다웠어요. 레일바이크도 재미있었고, 시간 가는 줄 몰랐습니다. 다만 주말이라 사람이 많아서 조금 붐볐어요. 평일에 가시면 더 좋을 것 같습니다.',
                    'created_at' => '2024-11-15',
                    'booking_id' => 'BK-2024-001189',
                ],
                [
                    'id' => 3,
                    'product_id' => 3,
                    'product_title' => '부산 해운대 해양 레저',
                    'product_image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400&h=300&fit=crop',
                    'rating' => 5,
                    'content' => '바다에서의 액티비티가 정말 최고였습니다! 제트스키, 바나나보트 모두 체험했는데 스릴 넘치고 재미있었어요. 강사님들도 안전하게 잘 지도해주셔서 안심하고 즐길 수 있었습니다.',
                    'created_at' => '2024-10-28',
                    'booking_id' => 'BK-2024-001045',
                ],
            ]);
        @endphp

        @if($reviews->isEmpty())
            <div class="text-center py-16">
                <svg class="mx-auto w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No reviews yet</h3>
                <p class="mt-2 text-gray-600">Write a review after completing your tour!</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($reviews as $review)
                    <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                        <div class="flex flex-col sm:flex-row">
                            <!-- Product Image -->
                            <div class="sm:w-48 h-32 sm:h-auto flex-shrink-0">
                                <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $review['product_id']]) }}">
                                    <img src="{{ $review['product_image'] }}"
                                         alt="{{ $review['product_title'] }}"
                                         class="w-full h-full object-cover hover:opacity-90 transition">
                                </a>
                            </div>

                            <!-- Review Content -->
                            <div class="flex-1 p-4 sm:p-6">
                                <!-- Product Title & Rating -->
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $review['product_id']]) }}"
                                           class="font-semibold text-gray-900 hover:text-indigo-600 transition">
                                            {{ $review['product_title'] }}
                                        </a>
                                        <p class="text-xs text-gray-500 mt-1">{{ $review['booking_id'] }}</p>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex gap-2 ml-4">
                                        <button type="button"
                                                onclick="editReview({{ $review['id'] }})"
                                                class="p-2 text-gray-400 hover:text-indigo-600 transition"
                                                title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button type="button"
                                                onclick="deleteReview({{ $review['id'] }})"
                                                class="p-2 text-gray-400 hover:text-red-600 transition"
                                                title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Star Rating -->
                                <div class="flex items-center mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' }}"
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">{{ $review['rating'] }}.0</span>
                                </div>

                                <!-- Review Text -->
                                <p class="text-gray-700 leading-relaxed">{{ $review['content'] }}</p>

                                <!-- Date -->
                                <p class="mt-3 text-sm text-gray-500">{{ $review['created_at'] }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function editReview(reviewId) {
            if (confirm('Edit review functionality will be implemented.')) {
                console.log('Edit review:', reviewId);
                // TODO: Open modal or redirect to edit page
            }
        }

        function deleteReview(reviewId) {
            if (confirm('Are you sure you want to delete this review?')) {
                fetch(`/my/reviews/${reviewId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }
    </script>
    @endpush
</x-layouts.app>
