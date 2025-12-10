<x-traveler.my.layout :title="__('nav.wishlist')">
    @if($wishlists->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-pink-50 flex items-center justify-center">
                <svg class="w-10 h-10 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">위시리스트가 비어있습니다</h3>
            <p class="text-gray-500 mb-6">마음에 드는 체험을 저장해보세요!</p>
            <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                체험 둘러보기
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($wishlists as $item)
                <article class="group relative bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300">
                    <!-- Remove Button -->
                    <button type="button"
                            onclick="removeFromWishlist({{ $item['product_id'] }}, this)"
                            class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center text-pink-500 hover:text-pink-600 transition-all duration-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                        </svg>
                    </button>

                    <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $item['slug'] ?? $item['product_id']]) }}"
                       class="block cursor-pointer">
                        <!-- Image -->
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ $item['image'] }}"
                                 alt="{{ $item['title'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                            <span class="absolute bottom-3 left-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-sm text-xs font-medium text-gray-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                {{ $item['region'] }}
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-pink-50 text-pink-700 text-xs font-medium">
                                {{ $item['type'] }}
                            </span>
                            <h3 class="mt-2 font-semibold text-gray-900 line-clamp-2 group-hover:text-pink-600 transition-colors">
                                {{ $item['title'] }}
                            </h3>

                            @if($item['review_count'] > 0)
                                <div class="mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">{{ number_format($item['rating'], 1) }}</span>
                                    <span class="text-sm text-gray-500">({{ $item['review_count'] }})</span>
                                </div>
                            @endif

                            @if($item['formatted_price'])
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <span class="text-sm text-gray-500">{{ __('home.from_price') }}</span>
                                    <span class="text-lg font-bold text-gray-900 ml-1">{{ $item['formatted_price'] }}</span>
                                </div>
                            @endif
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $wishlists->links() }}
        </div>
    @endif

    @push('scripts')
    <script>
        function removeFromWishlist(productId, button) {
            api.wishlist.toggle(productId)
                .then(() => {
                    // Add fade-out animation before removing
                    const card = button.closest('article');
                    card.style.transition = 'opacity 0.3s, transform 0.3s';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 300);
                });
        }
    </script>
    @endpush
</x-traveler.my.layout>
