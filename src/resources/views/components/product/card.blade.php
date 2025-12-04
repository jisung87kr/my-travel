@props([
    'product' => null,
    'showWishlist' => true,
])

<article class="group relative flex flex-col">
    <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product->slug ?? $product->id]) }}"
       class="block">
        <!-- Image Container -->
        <div class="relative aspect-[4/3] overflow-hidden rounded-xl mb-3">
            <img src="{{ $product->image ?? '/images/placeholder.jpg' }}"
                 alt="{{ $product->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 onerror="this.src='/images/placeholder.jpg'">

            <!-- Wishlist Button -->
            @if($showWishlist)
                @auth
                    <button type="button"
                            class="absolute top-3 right-3 p-2 bg-white/90 hover:bg-white rounded-full shadow-sm hover:shadow-md transition-all duration-200 wishlist-btn z-10"
                            data-product-id="{{ $product->id }}"
                            onclick="event.preventDefault(); toggleWishlist({{ $product->id }})">
                        <svg class="w-5 h-5 text-gray-700 hover:text-red-500 transition-colors wishlist-icon"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                @endauth
            @endif
        </div>

        <!-- Content -->
        <div class="flex flex-col gap-1">
            <!-- Location & Title -->
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 truncate group-hover:text-gray-700 transition-colors">
                        {{ $product->title }}
                    </h3>
                    @if(isset($product->location))
                        <p class="text-sm text-gray-500 truncate">
                            {{ $product->location }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Rating -->
            @if(isset($product->rating) && $product->reviewCount > 0)
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-900">
                        {{ number_format($product->rating, 2) }}
                    </span>
                    <span class="text-sm text-gray-500">
                        ({{ number_format($product->reviewCount) }})
                    </span>
                </div>
            @endif

            <!-- Price -->
            <div class="mt-1">
                <span class="text-base font-semibold text-gray-900">
                    ₩{{ number_format($product->price) }}
                </span>
                <span class="text-sm text-gray-600">
                    / 인
                </span>
            </div>
        </div>
    </a>
</article>
