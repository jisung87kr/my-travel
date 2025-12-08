<x-traveler.my.layout :title="__('nav.my_reviews')">
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

    <!-- Stats Summary -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-pink-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $reviews->count() }}</p>
                    <p class="text-xs text-gray-500">총 리뷰</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($reviews->avg('rating'), 1) }}</p>
                    <p class="text-xs text-gray-500">평균 평점</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $reviews->where('rating', '>=', 4)->count() }}</p>
                    <p class="text-xs text-gray-500">좋은 평가</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $reviews->where('rating', 5)->count() }}</p>
                    <p class="text-xs text-gray-500">5점 리뷰</p>
                </div>
            </div>
        </div>
    </div>

    @if($reviews->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-amber-50 flex items-center justify-center">
                <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">작성한 리뷰가 없습니다</h3>
            <p class="text-gray-500 mb-6">체험을 완료하고 첫 리뷰를 남겨보세요!</p>
            <a href="{{ route('my.bookings', ['locale' => app()->getLocale(), 'status' => 'completed']) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                리뷰 작성하기
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reviews as $review)
                <article class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-all duration-200 group">
                    <div class="flex flex-col sm:flex-row">
                        <!-- Product Image -->
                        <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $review['product_id']]) }}"
                           class="sm:w-52 h-40 sm:h-auto flex-shrink-0 relative overflow-hidden cursor-pointer">
                            <img src="{{ $review['product_image'] }}"
                                 alt="{{ $review['product_title'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                            <!-- Rating Badge on Image (Mobile) -->
                            <div class="sm:hidden absolute bottom-3 left-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-sm text-xs font-semibold text-gray-900">
                                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    {{ $review['rating'] }}.0
                                </span>
                            </div>
                        </a>

                        <!-- Review Content -->
                        <div class="flex-1 p-5 sm:p-6">
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div class="flex-1">
                                    <!-- Star Rating (Desktop) -->
                                    <div class="hidden sm:flex items-center gap-1 mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $review['rating'] ? 'text-amber-400' : 'text-gray-200' }}"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                        <span class="ml-1 text-sm font-medium text-gray-900">{{ $review['rating'] }}.0</span>
                                    </div>
                                    <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $review['product_id']]) }}"
                                       class="font-bold text-gray-900 hover:text-pink-600 transition-colors cursor-pointer text-lg">
                                        {{ $review['product_title'] }}
                                    </a>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                        <span>{{ $review['booking_id'] }}</span>
                                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                        <span>{{ $review['created_at'] }}</span>
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-1">
                                    <button type="button"
                                            onclick="editReview({{ $review['id'] }})"
                                            class="p-2.5 rounded-xl text-gray-400 hover:text-pink-600 hover:bg-pink-50 transition-all cursor-pointer"
                                            title="수정">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                    <button type="button"
                                            onclick="deleteReview({{ $review['id'] }})"
                                            class="p-2.5 rounded-xl text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all cursor-pointer"
                                            title="삭제">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Review Text -->
                            <p class="text-gray-700 leading-relaxed line-clamp-3">{{ $review['content'] }}</p>

                            <!-- Helpful Badge -->
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-xs text-gray-500">이 리뷰가 도움이 되셨나요?</span>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                                        </svg>
                                        12
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination (if needed) -->
        {{-- <div class="mt-8">
            {{ $reviews->links() }}
        </div> --}}
    @endif

    @push('scripts')
    <script>
        function editReview(reviewId) {
            if (confirm('리뷰 수정 기능은 준비 중입니다.')) {
                console.log('Edit review:', reviewId);
            }
        }

        function deleteReview(reviewId) {
            if (confirm('정말로 이 리뷰를 삭제하시겠습니까?')) {
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
</x-traveler.my.layout>
