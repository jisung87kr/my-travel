<x-layouts.app :title="__('nav.wishlist')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('nav.wishlist') }}</h1>

        @if($wishlists->isEmpty())
            <div class="text-center py-16">
                <svg class="mx-auto w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Your wishlist is empty</h3>
                <p class="mt-2 text-gray-600">Save products you like to your wishlist!</p>
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                   class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    {{ __('nav.products') }}
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($wishlists as $item)
                    <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition group relative">
                        <!-- Remove Button -->
                        <button type="button"
                                onclick="removeFromWishlist({{ $item['product_id'] }}, this)"
                                class="absolute top-3 right-3 z-10 p-2 bg-white rounded-full shadow hover:bg-red-50 transition">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>

                        <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $item['slug'] ?? $item['product_id']]) }}">
                            <!-- Image -->
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img src="{{ $item['image'] }}"
                                     alt="{{ $item['title'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <span class="absolute bottom-3 left-3 px-2 py-1 bg-white/90 text-xs font-medium text-gray-700 rounded">
                                    {{ $item['region'] }}
                                </span>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <span class="text-xs font-medium text-indigo-600">{{ $item['type'] }}</span>
                                <h3 class="mt-1 font-semibold text-gray-900 line-clamp-2 group-hover:text-indigo-600 transition">
                                    {{ $item['title'] }}
                                </h3>

                                @if($item['review_count'] > 0)
                                    <div class="mt-2 flex items-center text-sm">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="ml-1 font-medium text-gray-900">{{ number_format($item['rating'], 1) }}</span>
                                        <span class="ml-1 text-gray-500">({{ $item['review_count'] }})</span>
                                    </div>
                                @endif

                                @if($item['formatted_price'])
                                    <div class="mt-3">
                                        <span class="text-sm text-gray-500">{{ __('home.from_price') }}</span>
                                        <span class="text-lg font-bold text-gray-900">₩{{ $item['formatted_price'] }}</span>
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
    </div>

    @push('scripts')
    <script>
        function removeFromWishlist(productId, button) {
            fetch(`/wishlist/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Remove the card from DOM
                button.closest('article').remove();
            });
        }
    </script>
    @endpush
</x-layouts.app>
