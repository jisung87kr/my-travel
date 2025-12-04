<article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition group">
    <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product['slug'] ?? $product['id']]) }}">
        <!-- Image -->
        <div class="relative aspect-[4/3] overflow-hidden">
            <img src="{{ $product['image'] }}"
                 alt="{{ $product['title'] }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                 onerror="this.src='/images/placeholder.jpg'">

            <!-- Wishlist Button -->
            @auth
                <button type="button"
                        class="absolute top-3 right-3 p-2 bg-white/80 rounded-full hover:bg-white transition wishlist-btn"
                        data-product-id="{{ $product['id'] }}"
                        onclick="event.preventDefault(); toggleWishlist({{ $product['id'] }})">
                    <svg class="w-5 h-5 text-gray-600 wishlist-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>
            @endauth

            <!-- Region Badge -->
            <span class="absolute bottom-3 left-3 px-2 py-1 bg-white/90 text-xs font-medium text-gray-700 rounded">
                {{ $product['region'] }}
            </span>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Type -->
            <span class="text-xs font-medium text-indigo-600">{{ $product['type'] }}</span>

            <!-- Title -->
            <h3 class="mt-1 font-semibold text-gray-900 line-clamp-2 group-hover:text-indigo-600 transition">
                {{ $product['title'] }}
            </h3>

            <!-- Rating -->
            @if($product['review_count'] > 0)
                <div class="mt-2 flex items-center text-sm">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="ml-1 font-medium text-gray-900">{{ number_format($product['rating'], 1) }}</span>
                    <span class="ml-1 text-gray-500">({{ $product['review_count'] }})</span>
                </div>
            @endif

            <!-- Price -->
            @if($product['formatted_price'])
                <div class="mt-3">
                    <span class="text-sm text-gray-500">{{ __('home.from_price') }}</span>
                    <span class="text-lg font-bold text-gray-900">₩{{ $product['formatted_price'] }}</span>
                </div>
            @endif
        </div>
    </a>
</article>
