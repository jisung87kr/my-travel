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

                                        {{-- Review Images --}}
                                        @if($review->images->count() > 0)
                                            <div class="mt-4 flex flex-wrap gap-2">
                                                @foreach($review->images as $image)
                                                    <button type="button"
                                                            onclick="openReviewImageModal('{{ $image->path }}')"
                                                            class="relative group overflow-hidden rounded-xl w-20 h-20 sm:w-24 sm:h-24 cursor-pointer focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                                                        <img src="{{ $image->path }}"
                                                             alt="리뷰 이미지"
                                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                                             loading="lazy">
                                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300 flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
                                                            </svg>
                                                        </div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif

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

                            <!-- Booking Form -->
                            <div x-data="{
                                selectedDate: '',
                                adults: 1,
                                children: 0,
                                showDatePicker: false,
                                showGuestPicker: false,
                                schedules: {{ Js::from($schedules) }},
                                adultPrice: {{ $adultPrice?->price ?? 0 }},
                                childPrice: {{ $childPrice?->price ?? 0 }},
                                get selectedSchedule() {
                                    return this.schedules.find(s => s.date === this.selectedDate);
                                },
                                get maxPersons() {
                                    return this.selectedSchedule?.available_count ?? 99;
                                },
                                get totalPersons() {
                                    return this.adults + this.children;
                                },
                                get totalPrice() {
                                    return (this.adults * this.adultPrice) + (this.children * this.childPrice);
                                },
                                get canBook() {
                                    return this.selectedDate && this.adults > 0 && this.totalPersons <= this.maxPersons;
                                },
                                formatDate(dateStr) {
                                    const date = new Date(dateStr);
                                    const days = ['일', '월', '화', '수', '목', '금', '토'];
                                    return `${date.getMonth() + 1}월 ${date.getDate()}일 (${days[date.getDay()]})`;
                                },
                                formatPrice(price) {
                                    return price.toLocaleString();
                                }
                            }" class="space-y-4">
                                <!-- Date Selection -->
                                <div class="relative">
                                    <button type="button"
                                            @click="showDatePicker = !showDatePicker; showGuestPicker = false"
                                            class="w-full flex items-center gap-3 px-4 py-3.5 border border-gray-200 rounded-xl hover:border-gray-300 transition-colors text-left"
                                            :class="showDatePicker ? 'border-pink-500 ring-2 ring-pink-500/20' : ''">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-100 to-teal-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <span class="block text-[10px] font-bold text-cyan-600 uppercase tracking-wider">일정</span>
                                            <span class="block text-sm font-medium truncate" :class="selectedDate ? 'text-gray-900' : 'text-gray-400'" x-text="selectedDate ? formatDate(selectedDate) : '날짜를 선택하세요'"></span>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="showDatePicker ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                    <!-- Date Dropdown -->
                                    <div x-show="showDatePicker"
                                         @click.away="showDatePicker = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 translate-y-2"
                                         class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 py-2 z-50 max-h-64 overflow-y-auto"
                                         style="display: none;">
                                        <template x-if="schedules.filter(s => s.is_active && s.available_count > 0).length === 0">
                                            <p class="px-4 py-3 text-sm text-gray-500 text-center">예약 가능한 날짜가 없습니다</p>
                                        </template>
                                        <template x-for="schedule in schedules.filter(s => s.is_active && s.available_count > 0)" :key="schedule.id">
                                            <button type="button"
                                                    @click="selectedDate = schedule.date; showDatePicker = false; if(totalPersons > schedule.available_count) { adults = 1; children = 0; }"
                                                    class="w-full flex items-center justify-between px-4 py-3 text-sm hover:bg-gray-50 transition-colors"
                                                    :class="selectedDate === schedule.date ? 'bg-pink-50 text-pink-600' : 'text-gray-700'">
                                                <span class="font-medium" x-text="formatDate(schedule.date)"></span>
                                                <span class="text-xs px-2 py-1 rounded-full" :class="schedule.available_count <= 5 ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-600'" x-text="schedule.available_count + '명 가능'"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Guest Selection -->
                                <div class="relative">
                                    <button type="button"
                                            @click="showGuestPicker = !showGuestPicker; showDatePicker = false"
                                            class="w-full flex items-center gap-3 px-4 py-3.5 border border-gray-200 rounded-xl hover:border-gray-300 transition-colors text-left"
                                            :class="showGuestPicker ? 'border-pink-500 ring-2 ring-pink-500/20' : ''">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <span class="block text-[10px] font-bold text-orange-500 uppercase tracking-wider">인원</span>
                                            <span class="block text-sm font-medium text-gray-900" x-text="'성인 ' + adults + '명' + (children > 0 ? ', 아동 ' + children + '명' : '')"></span>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="showGuestPicker ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                    <!-- Guest Dropdown -->
                                    <div x-show="showGuestPicker"
                                         @click.away="showGuestPicker = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 translate-y-2"
                                         class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 p-5 z-50"
                                         style="display: none;">
                                        <!-- Adults -->
                                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                                            <div>
                                                <span class="block text-sm font-medium text-gray-900">성인</span>
                                                <span class="block text-xs text-gray-500">만 13세 이상</span>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <button type="button"
                                                        @click="if(adults > 1) adults--"
                                                        class="w-9 h-9 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:border-gray-400 hover:text-gray-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                                                        :disabled="adults <= 1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                    </svg>
                                                </button>
                                                <span class="text-base font-semibold w-6 text-center" x-text="adults"></span>
                                                <button type="button"
                                                        @click="if(totalPersons < maxPersons) adults++"
                                                        class="w-9 h-9 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:border-gray-400 hover:text-gray-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                                                        :disabled="totalPersons >= maxPersons">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Children -->
                                        <div class="flex items-center justify-between pt-4">
                                            <div>
                                                <span class="block text-sm font-medium text-gray-900">아동</span>
                                                <span class="block text-xs text-gray-500">만 2~12세</span>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <button type="button"
                                                        @click="if(children > 0) children--"
                                                        class="w-9 h-9 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:border-gray-400 hover:text-gray-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                                                        :disabled="children <= 0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                    </svg>
                                                </button>
                                                <span class="text-base font-semibold w-6 text-center" x-text="children"></span>
                                                <button type="button"
                                                        @click="if(totalPersons < maxPersons) children++"
                                                        class="w-9 h-9 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:border-gray-400 hover:text-gray-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                                                        :disabled="totalPersons >= maxPersons">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Max persons warning -->
                                        <template x-if="selectedDate && totalPersons >= maxPersons">
                                            <p class="mt-4 text-xs text-orange-600 bg-orange-50 px-3 py-2 rounded-lg">
                                                선택한 날짜의 최대 예약 인원에 도달했습니다.
                                            </p>
                                        </template>
                                    </div>
                                </div>

                                <!-- Price Summary -->
                                <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">성인 <span x-text="adults"></span>명 × ₩<span x-text="formatPrice(adultPrice)"></span></span>
                                        <span class="font-medium text-gray-900">₩<span x-text="formatPrice(adults * adultPrice)"></span></span>
                                    </div>
                                    <template x-if="children > 0">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">아동 <span x-text="children"></span>명 × ₩<span x-text="formatPrice(childPrice)"></span></span>
                                            <span class="font-medium text-gray-900">₩<span x-text="formatPrice(children * childPrice)"></span></span>
                                        </div>
                                    </template>
                                    <div class="flex justify-between pt-2 border-t border-gray-200">
                                        <span class="font-semibold text-gray-900">총 금액</span>
                                        <span class="text-lg font-bold text-pink-600">₩<span x-text="formatPrice(totalPrice)"></span></span>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                @auth
                                <form action="{{ route('bookings.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="date" :value="selectedDate">
                                    <input type="hidden" name="adult_count" :value="adults">
                                    <input type="hidden" name="child_count" :value="children">
                                    <button type="submit"
                                            :disabled="!canBook"
                                            class="w-full py-4 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/30 hover:shadow-xl hover:shadow-pink-500/40 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                                        <span x-text="selectedDate ? '예약하기' : '날짜를 선택하세요'"></span>
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('login', ['redirect' => url()->current()]) }}"
                                   class="block w-full py-4 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/30 hover:shadow-xl hover:shadow-pink-500/40 transition-all duration-300 text-center">
                                    로그인 후 예약하기
                                </a>
                                @endauth

                                @if($product->booking_type->value === 'request')
                                <p class="text-xs text-gray-500 text-center mt-2">
                                    이 상품은 판매자 승인 후 예약이 확정됩니다.
                                </p>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <div class="flex gap-3">
                                    @auth
                                        @php
                                            $isWishlisted = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                                        @endphp
                                        <button type="button"
                                                id="wishlist-btn"
                                                onclick="toggleMainWishlist({{ $product->id }}, this)"
                                                data-wishlisted="{{ $isWishlisted ? 'true' : 'false' }}"
                                                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 border rounded-xl font-medium transition-all duration-200 cursor-pointer {{ $isWishlisted ? 'border-pink-300 bg-pink-50 text-pink-600' : 'border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300' }}">
                                            <svg id="wishlist-icon" class="w-5 h-5 transition-transform duration-200" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                            </svg>
                                            <span id="wishlist-text" class="hidden sm:inline">{{ $isWishlisted ? '위시리스트에 추가됨' : __('product.add_to_wishlist') }}</span>
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}"
                                           class="flex-1 flex items-center justify-center gap-2 px-4 py-3 border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                            </svg>
                                            <span class="hidden sm:inline">{{ __('product.add_to_wishlist') }}</span>
                                        </a>
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

    {{-- Review Image Modal --}}
    <div id="review-image-modal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 backdrop-blur-sm"
         onclick="closeReviewImageModal()">
        <button type="button"
                onclick="closeReviewImageModal()"
                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="review-image-modal-img"
             src=""
             alt="리뷰 이미지"
             class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg shadow-2xl"
             onclick="event.stopPropagation()">
    </div>

    @push('scripts')
    <script>
        // Review Image Modal
        function openReviewImageModal(imageSrc) {
            const modal = document.getElementById('review-image-modal');
            const img = document.getElementById('review-image-modal-img');
            img.src = imageSrc;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeReviewImageModal() {
            const modal = document.getElementById('review-image-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeReviewImageModal();
            }
        });

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

        // Wishlist - Main product button
        function toggleMainWishlist(productId, button) {
            const icon = document.getElementById('wishlist-icon');
            const text = document.getElementById('wishlist-text');

            button.disabled = true;
            button.classList.add('opacity-50');

            fetch(`/wishlist/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const nowWishlisted = data.added;
                    button.dataset.wishlisted = nowWishlisted ? 'true' : 'false';

                    if (nowWishlisted) {
                        button.className = 'flex-1 flex items-center justify-center gap-2 px-4 py-3 border rounded-xl font-medium transition-all duration-200 cursor-pointer border-pink-300 bg-pink-50 text-pink-600';
                        if (icon) icon.setAttribute('fill', 'currentColor');
                        if (text) text.textContent = '위시리스트에 추가됨';
                    } else {
                        button.className = 'flex-1 flex items-center justify-center gap-2 px-4 py-3 border rounded-xl font-medium transition-all duration-200 cursor-pointer border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300';
                        if (icon) icon.setAttribute('fill', 'none');
                        if (text) text.textContent = '위시리스트 추가';
                    }

                    if (icon) {
                        icon.style.transform = 'scale(1.25)';
                        setTimeout(() => { icon.style.transform = 'scale(1)'; }, 200);
                    }
                }
            })
            .catch(error => console.error('Wishlist error:', error))
            .finally(() => {
                button.disabled = false;
                button.classList.remove('opacity-50');
            });
        }

        // Wishlist - Product card buttons (related products)
        function toggleWishlist(productId, button) {
            const icon = button.querySelector('.wishlist-icon');

            fetch(`/wishlist/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const nowWishlisted = data.added;
                    button.dataset.wishlisted = nowWishlisted ? 'true' : 'false';

                    if (nowWishlisted) {
                        button.classList.remove('text-gray-500', 'hover:text-pink-500');
                        button.classList.add('text-pink-500');
                        if (icon) icon.setAttribute('fill', 'currentColor');
                    } else {
                        button.classList.remove('text-pink-500');
                        button.classList.add('text-gray-500', 'hover:text-pink-500');
                        if (icon) icon.setAttribute('fill', 'none');
                    }

                    // Heart animation
                    if (icon) {
                        icon.classList.add('scale-125');
                        setTimeout(() => icon.classList.remove('scale-125'), 200);
                    }
                }
            })
            .catch(error => console.error('Wishlist error:', error));
        }
    </script>
    @endpush

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-layouts.app>
