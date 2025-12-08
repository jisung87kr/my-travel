<x-layouts.app :title="$translation?->title ?? $product->slug">
    <div class="bg-gray-50 min-h-screen">
        <!-- Breadcrumb -->
        <div class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex items-center gap-2 text-sm">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </a>
                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                    <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}" class="text-gray-500 hover:text-gray-700 transition-colors">{{ __('nav.products') }}</a>
                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                    <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'region' => $product->region->value]) }}" class="text-gray-500 hover:text-gray-700 transition-colors">{{ $product->region->label() }}</a>
                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-gray-900 font-medium truncate max-w-[200px]">{{ $translation?->title ?? '' }}</span>
                </nav>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Image Gallery -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden" x-data="{ currentIndex: 0, images: {{ $product->images->pluck('url')->toJson() }} }">
                        @if($product->images->count() > 0)
                            <!-- Main Image -->
                            <div class="relative aspect-[16/9] bg-gray-100">
                                <img :src="images[currentIndex]"
                                     alt="{{ $translation?->title }}"
                                     class="w-full h-full object-cover transition-opacity duration-300">

                                <!-- Navigation Buttons -->
                                @if($product->images->count() > 1)
                                    <button @click="currentIndex = (currentIndex - 1 + images.length) % images.length"
                                            class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center text-gray-700 hover:text-gray-900 transition-all duration-200 cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                        </svg>
                                    </button>
                                    <button @click="currentIndex = (currentIndex + 1) % images.length"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center text-gray-700 hover:text-gray-900 transition-all duration-200 cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </button>

                                    <!-- Image Counter -->
                                    <div class="absolute bottom-4 right-4 px-3 py-1.5 rounded-full bg-black/60 text-white text-sm font-medium">
                                        <span x-text="currentIndex + 1"></span> / {{ $product->images->count() }}
                                    </div>
                                @endif
                            </div>

                            <!-- Thumbnails -->
                            @if($product->images->count() > 1)
                                <div class="p-4 flex gap-2 overflow-x-auto scrollbar-hide">
                                    @foreach($product->images as $index => $image)
                                        <button @click="currentIndex = {{ $index }}"
                                                :class="currentIndex === {{ $index }} ? 'ring-2 ring-pink-500 ring-offset-2' : 'opacity-70 hover:opacity-100'"
                                                class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden transition-all duration-200 cursor-pointer">
                                            <img src="{{ $image->url }}" alt="" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="aspect-[16/9] bg-gray-100 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    <p class="text-gray-400 text-sm">이미지가 없습니다</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Product Info Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 sm:p-8">
                        <!-- Tags -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-pink-50 text-pink-700 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                </svg>
                                {{ $product->type->label() }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-cyan-50 text-cyan-700 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                {{ $product->region->label() }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4 leading-tight">
                            {{ $translation?->title ?? '' }}
                        </h1>

                        <!-- Rating -->
                        @if($product->review_count > 0)
                            <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= round($product->average_rating) ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-lg font-semibold text-gray-900">{{ number_format($product->average_rating, 1) }}</span>
                                <span class="text-gray-500">({{ __('product.reviews_count', ['count' => $product->review_count]) }})</span>
                            </div>
                        @endif

                        <!-- Description -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                {{ __('product.description') }}
                            </h2>
                            <div class="text-gray-600 leading-relaxed whitespace-pre-line">
                                {{ $translation?->description ?? '' }}
                            </div>
                        </div>

                        <!-- Includes -->
                        @if($translation?->includes)
                            <div class="mb-8">
                                <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('product.includes') }}
                                </h2>
                                <ul class="space-y-2">
                                    @foreach(explode("\n", $translation->includes) as $item)
                                        @if(trim($item))
                                            <li class="flex items-start gap-2 text-gray-600">
                                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                {{ trim($item) }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Excludes -->
                        @if($translation?->excludes)
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('product.excludes') }}
                                </h2>
                                <ul class="space-y-2">
                                    @foreach(explode("\n", $translation->excludes) as $item)
                                        @if(trim($item))
                                            <li class="flex items-start gap-2 text-gray-600">
                                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                                {{ trim($item) }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Reviews Section -->
                    @if($product->reviews->count() > 0)
                        <div class="bg-white rounded-2xl shadow-sm p-6 sm:p-8">
                            <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                {{ __('product.reviews') }} ({{ $product->review_count }})
                            </h2>

                            <div class="space-y-6">
                                @foreach($product->reviews as $review)
                                    <div class="pb-6 border-b border-gray-100 last:border-0 last:pb-0">
                                        <div class="flex items-start justify-between gap-4 mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-rose-400 flex items-center justify-center text-white font-medium">
                                                    {{ mb_substr($review->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $review->user->name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $review->created_at->format('Y.m.d') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-gray-600 leading-relaxed">{{ $review->content }}</p>

                                        @if($review->reply)
                                            <div class="mt-4 ml-4 pl-4 border-l-2 border-pink-200 bg-pink-50/50 rounded-r-lg p-4">
                                                <p class="text-sm font-medium text-pink-700 mb-1">판매자 답변</p>
                                                <p class="text-gray-600 text-sm">{{ $review->reply }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar - Booking Form -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <!-- Price -->
                            <div class="mb-6 pb-6 border-b border-gray-100">
                                @php
                                    $adultPrice = $product->prices->where('type', 'adult')->first();
                                    $childPrice = $product->prices->where('type', 'child')->first();
                                @endphp
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-bold text-gray-900">
                                        ₩{{ number_format($adultPrice?->price ?? 0) }}
                                    </span>
                                    <span class="text-gray-500">/ {{ __('booking.adults') }}</span>
                                </div>
                                @if($childPrice)
                                    <p class="text-sm text-gray-500 mt-1">
                                        어린이: ₩{{ number_format($childPrice->price) }}
                                    </p>
                                @endif
                            </div>

                            <!-- Booking Form Component -->
                            <div id="booking-form-container"
                                 data-product-id="{{ $product->id }}"
                                 data-booking-type="{{ $product->booking_type->value }}"
                                 data-adult-price="{{ $adultPrice?->price ?? 0 }}"
                                 data-child-price="{{ $childPrice?->price ?? 0 }}"
                                 data-schedules='@json($schedules)'>
                                <!-- Vue component will mount here -->
                                <div class="animate-pulse space-y-4">
                                    <div class="h-12 bg-gray-100 rounded-xl"></div>
                                    <div class="h-12 bg-gray-100 rounded-xl"></div>
                                    <div class="h-12 bg-gray-100 rounded-xl"></div>
                                </div>
                                <noscript>
                                    <p class="text-center text-gray-500 py-4">JavaScript를 활성화해주세요</p>
                                </noscript>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <div class="flex gap-3">
                                    @auth
                                        <button type="button"
                                                onclick="toggleWishlist({{ $product->id }})"
                                                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                            </svg>
                                            <span class="hidden sm:inline">{{ __('product.add_to_wishlist') }}</span>
                                        </button>
                                    @endauth
                                    <button type="button"
                                            onclick="shareProduct()"
                                            class="flex items-center justify-center gap-2 px-4 py-3 border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Trust Badges -->
                        <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-600">즉시확정</p>
                                </div>
                                <div>
                                    <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-600">모바일티켓</p>
                                </div>
                                <div>
                                    <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-purple-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802" />
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-600">한국어지원</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <section class="mt-16">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">비슷한 체험</h2>
                        <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'region' => $product->region->value]) }}"
                           class="text-sm font-medium text-pink-600 hover:text-pink-500 transition-colors">
                            더보기
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $related)
                            <x-product.card :product="$related" :showWishlist="true" />
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Share
        function shareProduct() {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                });
            } else {
                navigator.clipboard.writeText(window.location.href);
                alert('링크가 복사되었습니다!');
            }
        }

        // Wishlist
        function toggleWishlist(productId) {
            fetch(`/wishlist/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update UI based on response
            });
        }
    </script>
    @endpush

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-layouts.app>
