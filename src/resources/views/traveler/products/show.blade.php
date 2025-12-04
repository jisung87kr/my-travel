<x-layouts.app :title="$translation?->title ?? $product->slug">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm">
            <ol class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-indigo-600">{{ __('nav.products') }}</a></li>
                <li>/</li>
                <li><a href="{{ route('products.index', ['locale' => app()->getLocale(), 'region' => $product->region->value]) }}" class="hover:text-indigo-600">{{ $product->region->label() }}</a></li>
                <li>/</li>
                <li class="text-gray-900 font-medium">{{ $translation?->title ?? '' }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Image Gallery -->
                <div id="image-gallery" class="mb-8">
                    @if($product->images->count() > 0)
                        <div class="relative aspect-[16/9] rounded-xl overflow-hidden mb-4">
                            <img id="main-image"
                                 src="{{ $product->images->first()->url }}"
                                 alt="{{ $translation?->title }}"
                                 class="w-full h-full object-cover">

                            <!-- Navigation buttons -->
                            @if($product->images->count() > 1)
                                <button onclick="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 p-2 bg-white/80 rounded-full hover:bg-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 p-2 bg-white/80 rounded-full hover:bg-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endif
                        </div>

                        <!-- Thumbnails -->
                        @if($product->images->count() > 1)
                            <div class="flex gap-2 overflow-x-auto pb-2">
                                @foreach($product->images as $index => $image)
                                    <button onclick="setImage({{ $index }})"
                                            class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-indigo-500' : 'border-transparent' }} thumbnail">
                                        <img src="{{ $image->url }}" alt="" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="aspect-[16/9] bg-gray-200 rounded-xl flex items-center justify-center">
                            <span class="text-gray-400">No image available</span>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-medium rounded-full">
                            {{ $product->type->label() }}
                        </span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                            {{ $product->region->label() }}
                        </span>
                    </div>

                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                        {{ $translation?->title ?? '' }}
                    </h1>

                    <!-- Rating -->
                    @if($product->review_count > 0)
                        <div class="flex items-center gap-2 mb-6">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="font-medium">{{ number_format($product->average_rating, 1) }}</span>
                            <span class="text-gray-500">({{ __('product.reviews_count', ['count' => $product->review_count]) }})</span>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="prose max-w-none">
                        <h2 class="text-lg font-semibold mb-3">{{ __('product.description') }}</h2>
                        <div class="text-gray-700 leading-relaxed">
                            {!! nl2br(e($translation?->description ?? '')) !!}
                        </div>
                    </div>

                    <!-- Includes -->
                    @if($translation?->includes)
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold mb-3">{{ __('product.includes') }}</h2>
                            <ul class="space-y-2">
                                @foreach(explode("\n", $translation->includes) as $item)
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">{{ trim($item) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Excludes -->
                    @if($translation?->excludes)
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold mb-3">{{ __('product.excludes') }}</h2>
                            <ul class="space-y-2">
                                @foreach(explode("\n", $translation->excludes) as $item)
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-red-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">{{ trim($item) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Reviews -->
                @if($product->reviews->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold mb-6">{{ __('product.reviews') }} ({{ $product->review_count }})</h2>

                        <div class="space-y-6">
                            @foreach($product->reviews as $review)
                                <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                <span class="text-indigo-600 font-medium">{{ substr($review->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $review->user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $review->created_at->format('Y-m-d') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-gray-700">{{ $review->content }}</p>

                                    @if($review->reply)
                                        <div class="mt-3 pl-4 border-l-2 border-indigo-200">
                                            <p class="text-sm text-gray-500 mb-1">Provider reply:</p>
                                            <p class="text-gray-700">{{ $review->reply }}</p>
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
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-4">
                    <!-- Price -->
                    <div class="mb-6">
                        @php
                            $adultPrice = $product->prices->where('type', 'adult')->first();
                            $childPrice = $product->prices->where('type', 'child')->first();
                        @endphp
                        <div class="text-sm text-gray-500">{{ __('home.from_price') }}</div>
                        <div class="text-3xl font-bold text-gray-900">
                            ₩{{ number_format($adultPrice?->price ?? 0) }}
                        </div>
                        <div class="text-sm text-gray-500">/ {{ __('booking.adults') }}</div>
                    </div>

                    <!-- Booking Form Component -->
                    <div id="booking-form-container"
                         data-product-id="{{ $product->id }}"
                         data-booking-type="{{ $product->booking_type->value }}"
                         data-adult-price="{{ $adultPrice?->price ?? 0 }}"
                         data-child-price="{{ $childPrice?->price ?? 0 }}"
                         data-schedules='@json($schedules)'>
                        <!-- Vue component will mount here -->
                        <noscript>
                            <p class="text-center text-gray-500">Please enable JavaScript to book</p>
                        </noscript>
                    </div>

                    <!-- Wishlist & Share -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex gap-4">
                            @auth
                                <button type="button"
                                        onclick="toggleWishlist({{ $product->id }})"
                                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span>{{ __('product.add_to_wishlist') }}</span>
                                </button>
                            @endauth
                            <button type="button"
                                    onclick="shareProduct()"
                                    class="flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <section class="mt-16">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        @include('partials.product-card', ['product' => $related])
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    @push('scripts')
    <script>
        // Image Gallery
        const images = @json($product->images->pluck('url'));
        let currentIndex = 0;

        function setImage(index) {
            currentIndex = index;
            document.getElementById('main-image').src = images[index];
            document.querySelectorAll('.thumbnail').forEach((el, i) => {
                el.classList.toggle('border-indigo-500', i === index);
                el.classList.toggle('border-transparent', i !== index);
            });
        }

        function nextImage() {
            setImage((currentIndex + 1) % images.length);
        }

        function prevImage() {
            setImage((currentIndex - 1 + images.length) % images.length);
        }

        // Share
        function shareProduct() {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                });
            } else {
                navigator.clipboard.writeText(window.location.href);
                alert('Link copied!');
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
                // Update UI
            });
        }
    </script>
    @endpush
</x-layouts.app>
