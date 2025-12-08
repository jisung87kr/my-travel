@props([
    'product' => null,
    'showWishlist' => true,
])

@php
// Support both array and object access
$p = is_array($product) ? (object) $product : $product;
@endphp

<article class="group relative flex flex-col bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300">
    <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $p->slug ?? $p->id]) }}"
       class="block cursor-pointer">
        <!-- Image Container -->
        <div class="relative aspect-[4/3] overflow-hidden">
            <img src="{{ $p->image ?? 'https://placehold.co/300x300?text=NO+IMAGE' }}"
                 alt="{{ $p->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 onerror="this.src='https://placehold.co/300x300?text=NO+IMAGE'">
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>

            <!-- Location Badge -->
            @if(isset($p->location))
                <span class="absolute bottom-3 left-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-sm text-xs font-medium text-gray-700">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    {{ $p->location }}
                </span>
            @endif
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Title -->
            <h3 class="font-semibold text-gray-900 line-clamp-2 group-hover:text-pink-600 transition-colors mb-2">
                {{ $p->title }}
            </h3>

            <!-- Rating -->
            @if(isset($p->rating) && ($p->reviewCount ?? 0) > 0)
                <div class="flex items-center gap-1 mb-2">
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-900">
                        {{ number_format($p->rating, 1) }}
                    </span>
                    <span class="text-sm text-gray-500">
                        ({{ number_format($p->reviewCount) }})
                    </span>
                </div>
            @endif

            <!-- Price -->
            <div class="pt-2 border-t border-gray-100">
                <span class="text-sm text-gray-500">{{ __('home.from_price') }}</span>
                <span class="text-lg font-bold text-gray-900 ml-1">
                    ₩{{ number_format($p->price) }}
                </span>
            </div>
        </div>
    </a>

    <!-- Wishlist Button -->
    @if($showWishlist)
        @auth
            @php $isWishlisted = $p->isWishlisted ?? false; @endphp
            <button type="button"
                    class="absolute top-3 right-3 w-9 h-9 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center transition-all duration-200 cursor-pointer wishlist-btn z-10 {{ $isWishlisted ? 'text-pink-500' : 'text-gray-500 hover:text-pink-500' }}"
                    data-product-id="{{ $p->id }}"
                    data-wishlisted="{{ $isWishlisted ? 'true' : 'false' }}"
                    onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $p->id }}, this)">
                <svg class="w-5 h-5 wishlist-icon transition-transform hover:scale-110"
                     fill="{{ $isWishlisted ? 'currentColor' : 'none' }}"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     stroke-width="1.5">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </button>
        @endauth
    @endif
</article>
