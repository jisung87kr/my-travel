<x-layouts.app :title="$translation?->title ?? $product->slug">
    <div class="bg-white min-h-screen pb-20">
        
        <!-- 1. Header Section (Title & Meta) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-6">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-2 leading-tight">
                {{ $translation?->title ?? '' }}
            </h1>
            <div class="flex flex-wrap items-center justify-between gap-4 text-sm text-slate-600">
                <div class="flex items-center gap-4">
                    @if($product->review_count > 0)
                        <div class="flex items-center gap-1 font-medium text-slate-900">
                            <svg class="w-4 h-4 text-slate-900" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span>{{ number_format($product->average_rating, 1) }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-400 mx-1"></span>
                            <span class="underline cursor-pointer">{{ __('product.reviews_count', ['count' => $product->review_count]) }}</span>
                        </div>
                    @endif
                    @if($product->region)
                    <div class="flex items-center gap-1">
                        <span class="font-medium underline cursor-pointer text-slate-900">{{ $product->region->getName(app()->getLocale()) }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="flex items-center gap-4">
                     <button onclick="shareProduct()" class="flex items-center gap-2 hover:bg-slate-100 px-3 py-2 rounded-lg transition-colors font-medium underline decoration-transparent hover:decoration-slate-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" /></svg>
                        {{ __('product.share') }}
                    </button>
                    @auth
                        @php $isWishlisted = auth()->user()->wishlists()->where('product_id', $product->id)->exists(); @endphp
                        <button onclick="toggleMainWishlist({{ $product->id }}, this)" 
                                data-wishlisted="{{ $isWishlisted ? 'true' : 'false' }}"
                                class="flex items-center gap-2 hover:bg-slate-100 px-3 py-2 rounded-lg transition-colors font-medium underline decoration-transparent hover:decoration-slate-900 {{ $isWishlisted ? 'text-pink-600' : '' }}">
                            <svg class="w-4 h-4 {{ $isWishlisted ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                            <span id="wishlist-text">{{ $isWishlisted ? __('product.saved') : __('product.save') }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-2 hover:bg-slate-100 px-3 py-2 rounded-lg transition-colors font-medium underline decoration-transparent hover:decoration-slate-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                            {{ __('product.save') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- 2. Image Gallery (Grid on Desktop, Swipe on Mobile) -->
        <div class="max-w-7xl mx-auto px-0 sm:px-6 lg:px-8 mb-12">
            <div class="relative rounded-2xl overflow-hidden" x-data="{ currentIndex: 0, images: {{ $product->images->pluck('url')->toJson() }} }">
                <!-- Desktop Grid -->
                <div class="hidden sm:grid grid-cols-4 grid-rows-2 gap-2 h-[400px] lg:h-[480px]">
                    @if($product->images->count() > 0)
                        <div class="col-span-2 row-span-2 relative cursor-pointer group" @click="openGallery(0)">
                            <img src="{{ $product->images[0]->url }}" class="w-full h-full object-cover hover:opacity-95 transition-opacity" alt="">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                        </div>
                        @foreach($product->images->skip(1)->take(4) as $index => $image)
                            <div class="col-span-1 row-span-1 relative cursor-pointer group" @click="openGallery({{ $index + 1 }})">
                                <img src="{{ $image->url }}" class="w-full h-full object-cover hover:opacity-95 transition-opacity" alt="">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-4 row-span-2 bg-gray-100 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ __('product.no_image') }}
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Mobile Carousel -->
                <div class="sm:hidden relative aspect-[4/3] bg-gray-100">
                    <template x-if="images.length > 0">
                        <img :src="images[currentIndex]" class="w-full h-full object-cover transition-opacity duration-300" alt="">
                    </template>
                    <div class="absolute bottom-4 right-4 bg-black/70 text-white text-xs px-2 py-1 rounded-md font-medium" x-show="images.length > 0">
                        <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                    </div>
                    <!-- Arrows -->
                    <button x-show="images.length > 1" @click="currentIndex = (currentIndex - 1 + images.length) % images.length" class="absolute left-2 top-1/2 -translate-y-1/2 p-1.5 rounded-full bg-white/90 shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                    <button x-show="images.length > 1" @click="currentIndex = (currentIndex + 1) % images.length" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 rounded-full bg-white/90 shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                </div>

                <button class="absolute bottom-6 right-6 hidden sm:flex items-center gap-2 bg-white border border-slate-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 transition-colors shadow-sm" x-show="images.length > 5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    {{ __('product.show_all_photos') }}
                </button>
            </div>
        </div>

        <!-- 3. Main Layout -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-12">
                
                <!-- Left Column (Content) -->
                <div class="space-y-10">
                    
                    <!-- Host/Type Info -->
                    <div class="border-b border-gray-200 pb-8 flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 mb-1">{{ $product->type->label() }} {{ __('product.hosted_by') }} MyTravel</h2>
                            <p class="text-slate-500 text-sm">
                                {{ __('product.max_guests') }} {{ $product->max_guests ?? '10+' }} • {{ $product->region->label() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-slate-900 flex items-center justify-center text-white font-bold text-lg">M</div>
                    </div>

                    <!-- Highlights (Horizontal Scroll) -->
                    <div class="border-b border-gray-200 pb-8">
                        <div class="flex gap-6 overflow-x-auto no-scrollbar py-1">
                            <div class="flex gap-4 items-start min-w-[200px]">
                                <svg class="w-6 h-6 text-slate-900 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <div>
                                    <p class="font-bold text-slate-900">{{ __('product.instant_confirm') }}</p>
                                    <p class="text-sm text-slate-500">{{ __('product.instant_confirm_desc') ?? 'Book without waiting for approval.' }}</p>
                                </div>
                            </div>
                            <div class="flex gap-4 items-start min-w-[200px]">
                                <svg class="w-6 h-6 text-slate-900 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" /></svg>
                                <div>
                                    <p class="font-bold text-slate-900">{{ __('product.mobile_ticket') }}</p>
                                    <p class="text-sm text-slate-500">Use your phone to enter.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 items-start min-w-[200px]">
                                <svg class="w-6 h-6 text-slate-900 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" /></svg>
                                <div>
                                    <p class="font-bold text-slate-900">{{ __('product.korean_support') }}</p>
                                    <p class="text-sm text-slate-500">We speak your language.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="border-b border-gray-200 pb-8">
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $translation?->description ?? '' }}
                        </div>
                    </div>

                    <!-- What's Included (Grid) -->
                     <div class="border-b border-gray-200 pb-8">
                        <h2 class="text-xl font-bold text-slate-900 mb-6">{{ __('product.what_included') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($translation?->includes)
                                @foreach(explode("\n", $translation->includes) as $item)
                                    @if(trim($item))
                                        <div class="flex gap-3 items-start">
                                            <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                            <span class="text-slate-700">{{ trim($item) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            @if($translation?->excludes)
                                @foreach(explode("\n", $translation->excludes) as $item)
                                    @if(trim($item))
                                        <div class="flex gap-3 items-start opacity-70">
                                            <svg class="w-5 h-5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            <span class="text-slate-500 line-through decoration-slate-400">{{ trim($item) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Reviews -->
                    @if($product->reviews->count() > 0)
                    <div class="pb-8">
                         <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                             <svg class="w-5 h-5 text-slate-900" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                             {{ number_format($product->average_rating, 1) }} · {{ $product->review_count }} {{ __('product.reviews') }}
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                             @foreach($product->reviews->take(4) as $review)
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600 text-sm">
                                            {{ mb_substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $review->user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $review->created_at->format('F Y') }}</p>
                                        </div>
                                    </div>
                                    <p class="text-slate-600 text-sm leading-relaxed line-clamp-4">{{ $review->content }}</p>
                                </div>
                            @endforeach
                        </div>
                         @if($product->review_count > 4)
                            <button class="mt-8 px-6 py-3 border border-slate-900 rounded-lg font-semibold text-slate-900 hover:bg-slate-50 transition-colors">
                                Show all {{ $product->review_count }} reviews
                            </button>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Right Column (Sticky Sidebar) -->
                <div class="relative">
                    <div class="sticky top-24 w-full">
                         <!-- Booking Widget -->
                        <div class="bg-white rounded-2xl shadow-[0_6px_16px_rgba(0,0,0,0.12)] border border-gray-200 p-6">
                            @php
                                $adultPrice = $product->prices->where('type', 'adult')->first();
                                $childPrice = $product->prices->where('type', 'child')->first();
                            @endphp
                            
                            <!-- Price Header -->
                            <div class="flex items-baseline justify-between mb-6">
                                <div>
                                    <span class="text-2xl font-bold text-slate-900">₩{{ number_format($adultPrice?->price ?? 0) }}</span>
                                    <span class="text-slate-500 text-sm"> / {{ __('booking.person') }}</span>
                                </div>
                                <div class="flex items-center gap-1 text-sm font-medium">
                                    <svg class="w-3 h-3 text-slate-900" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    <span class="text-slate-900">{{ number_format($product->average_rating, 1) }}</span>
                                    <span class="text-slate-400">·</span>
                                    <span class="text-slate-500 underline">{{ $product->review_count }} reviews</span>
                                </div>
                            </div>

                            <!-- Form -->
                            <div x-data="{
                                selectedScheduleId: null,
                                adults: 1,
                                children: 0,
                                showDatePicker: false,
                                showGuestPicker: false,
                                schedules: {{ Js::from($schedules) }},
                                adultPrice: {{ $adultPrice?->price ?? 0 }},
                                childPrice: {{ $childPrice?->price ?? 0 }},
                                get selectedSchedule() {
                                    return this.schedules.find(s => s.id === this.selectedScheduleId);
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
                                formatPrice(price) {
                                    return price.toLocaleString();
                                },
                                selectSchedule(schedule) {
                                    this.selectedScheduleId = schedule.id;
                                    showDatePicker = false;
                                }
                            }" class="space-y-4">
                                
                                <!-- Inputs Container -->
                                <div class="border border-gray-400 rounded-xl overflow-hidden divide-y divide-gray-400">
                                    <!-- Date Picker Trigger -->
                                    <div class="relative">
                                        <button type="button" 
                                                @click="showDatePicker = !showDatePicker; showGuestPicker = false"
                                                class="w-full p-3 text-left hover:bg-gray-50 transition-colors">
                                            <div class="text-[10px] font-bold uppercase text-slate-800 tracking-wider">{{ __('product.date') }}</div>
                                            <div class="text-sm font-medium text-slate-600 truncate" x-text="selectedSchedule?.starts_at_human ?? '{{ __('product.select_date') }}'"></div>
                                        </button>
                                         <!-- Date Dropdown -->
                                         <div x-show="showDatePicker" 
                                             @click.away="showDatePicker = false"
                                             class="absolute top-0 right-0 mt-12 bg-white rounded-xl shadow-xl border border-gray-200 p-2 z-50 w-full max-h-60 overflow-y-auto">
                                            <template x-for="schedule in schedules" :key="schedule.id">
                                                <button @click="selectSchedule(schedule)" 
                                                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 rounded-lg disabled:opacity-50"
                                                        :disabled="schedule.available_count <= 0">
                                                    <div class="font-medium" x-text="schedule.starts_at_human"></div>
                                                    <div class="text-xs text-gray-500" x-text="schedule.available_count + ' spots left'"></div>
                                                </button>
                                            </template>
                                            <div x-show="schedules.length === 0" class="p-3 text-sm text-gray-500 text-center">{{ __('product.no_available_dates') }}</div>
                                         </div>
                                    </div>
                                    
                                    <!-- Guest Picker Trigger -->
                                    <div class="relative">
                                        <button type="button" 
                                                @click="showGuestPicker = !showGuestPicker; showDatePicker = false"
                                                class="w-full p-3 text-left hover:bg-gray-50 transition-colors">
                                            <div class="text-[10px] font-bold uppercase text-slate-800 tracking-wider">{{ __('product.guests') }}</div>
                                            <div class="text-sm font-medium text-slate-600">
                                                <span x-text="adults"></span> {{ __('product.adults') }}
                                                <template x-if="children > 0">
                                                    <span>, <span x-text="children"></span> {{ __('product.children') }}</span>
                                                </template>
                                            </div>
                                        </button>
                                         <!-- Guest Dropdown -->
                                        <div x-show="showGuestPicker" 
                                             @click.away="showGuestPicker = false"
                                             class="absolute top-0 left-0 w-full mt-12 bg-white rounded-xl shadow-xl border border-gray-200 p-4 z-50">
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="text-sm font-medium">{{ __('product.adults') }}</div>
                                                <div class="flex items-center gap-3">
                                                    <button @click="if(adults > 1) adults--" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center disabled:opacity-50">-</button>
                                                    <span x-text="adults"></span>
                                                    <button @click="adults++" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center">+</button>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div class="text-sm font-medium">{{ __('product.children') }}</div>
                                                <div class="flex items-center gap-3">
                                                    <button @click="if(children > 0) children--" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center disabled:opacity-50">-</button>
                                                    <span x-text="children"></span>
                                                    <button @click="children++" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reserve Button -->
                                @auth
                                    <a :href="`{{ route('bookings.create', ['product' => $product->id]) }}?schedule_id=${selectedScheduleId || ''}&adults=${adults}&children=${children}`"
                                       class="block w-full py-3.5 rounded-xl bg-gradient-to-r from-pink-500 to-rose-600 text-white font-bold text-lg text-center shadow-md hover:shadow-lg transform active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                       :class="{'pointer-events-none opacity-50': !selectedScheduleId}">
                                        {{ __('product.reserve') }}
                                    </a>
                                @else
                                     <a :href="`{{ route('login') }}?redirect={{ urlencode(route('bookings.create', ['product' => $product->id])) }}%3Fschedule_id=${selectedScheduleId || ''}&adults=${adults}&children=${children}`"
                                       class="block w-full py-3.5 rounded-xl bg-gradient-to-r from-pink-500 to-rose-600 text-white font-bold text-lg text-center shadow-md hover:shadow-lg transform active:scale-[0.98] transition-all">
                                        {{ __('product.login_to_book') }}
                                    </a>
                                @endauth
                                
                                <div class="text-center text-xs text-slate-500 mt-2">{{ __('product.wont_be_charged') }}</div>

                                <!-- Pricing Breakdown -->
                                <div class="pt-4 border-t border-gray-100 space-y-3" x-show="totalPrice > 0">
                                    <div class="flex justify-between text-slate-600">
                                        <span class="underline">₩<span x-text="formatPrice(adultPrice)"></span> x <span x-text="adults"></span> {{ __('product.adults') }}</span>
                                        <span>₩<span x-text="formatPrice(adults * adultPrice)"></span></span>
                                    </div>
                                    <template x-if="children > 0">
                                        <div class="flex justify-between text-slate-600">
                                            <span class="underline">₩<span x-text="formatPrice(childPrice)"></span> x <span x-text="children"></span> {{ __('product.children') }}</span>
                                            <span>₩<span x-text="formatPrice(children * childPrice)"></span></span>
                                        </div>
                                    </template>
                                    <div class="flex justify-between font-bold text-slate-900 pt-3 border-t border-gray-100">
                                        <span>{{ __('product.total') }}</span>
                                        <span>₩<span x-text="formatPrice(totalPrice)"></span></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-center gap-2 items-center text-slate-500 text-sm font-medium">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd" /></svg>
                            <span class="underline">{{ __('product.report_this_listing') }}</span>
                        </div>
                    </div>
                </div>

            </div>
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
             alt="{{ __('product.review_image') }}"
             class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg shadow-2xl"
             onclick="event.stopPropagation()">
    </div>

    @push('scripts')
    <script>
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

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeReviewImageModal();
        });

        function shareProduct() {
            if (navigator.share) {
                navigator.share({ title: document.title, url: window.location.href });
            } else {
                navigator.clipboard.writeText(window.location.href);
                // In a real app, show a toast here
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @endpush
</x-layouts.app>