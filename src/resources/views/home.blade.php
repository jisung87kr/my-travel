<x-layouts.app :title="__('home.page_title')">
    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-pink-50/80 via-white to-white pt-12 pb-16 sm:pt-16 sm:pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Content -->
            <div class="text-center max-w-4xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-pink-100 text-pink-600 text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    {{ __('home.hero_badge') }}
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-900 tracking-tight leading-tight mb-6">
                    {{ __('home.hero_title') }}<br class="sm:hidden" />
                    <span class="text-pink-500">{{ __('home.hero_title_highlight') }}</span>{{ __('home.hero_title_suffix') }}
                </h1>
                <p class="text-lg sm:text-xl text-slate-500 max-w-2xl mx-auto">
                    {{ __('home.hero_subtitle') }}
                </p>
            </div>

            <!-- Search Widget -->
            <x-search-widget :regions="$regions" />
        </div>
    </section>

    <!-- Category Navigation - Simple Pills -->
    @if($categories->count() > 0)
    <section class="top-16 lg:top-[72px] z-30 bg-white border-b border-slate-200" id="category-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-3 overflow-x-auto scrollbar-hide py-4 -mx-4 px-4 sm:mx-0 sm:px-0 justify-center">
                @foreach($categories as $category)
                <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'category' => $category->slug]) }}"
                   class="flex flex-col items-center justify-center gap-2.5 w-20 h-20 rounded-2xl text-xs font-medium transition-all cursor-pointer flex-shrink-0 bg-slate-100 text-slate-600 hover:bg-slate-200">
                    @if($category->hasIcon())
                        {!! $category->getIconHtml('w-7 h-7') !!}
                    @else
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                    @endif
                    <span>{{ $category->getName() }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Recommended Products -->
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">{{ __('home.recommended_title') }}</h2>
                    <p class="text-slate-500 mt-1">{{ __('home.recommended_subtitle') }}</p>
                </div>
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                   class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-slate-600 hover:text-pink-500 transition-colors cursor-pointer">
                    {{ __('home.view_all') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($recommendedProducts as $product)
                    <x-product.card :product="(object) $product" :showWishlist="true" class="aspect-square" />
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16 text-slate-400">
                        <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <p class="text-slate-600 font-medium">{{ __('home.no_products') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- Mobile View All -->
            <div class="sm:hidden mt-8 text-center">
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-pink-500 text-white text-sm font-semibold hover:bg-pink-600 transition-colors cursor-pointer">
                    {{ __('home.view_all_products') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Regions - Clean Grid -->
    <section class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">{{ __('home.regions_title') }}</h2>
                <p class="text-slate-500 mt-2">{{ __('home.regions_subtitle') }}</p>
            </div>

            <!-- Regions Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
                @foreach($regions as $region)
                <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'region' => $region['value']]) }}"
                   class="group relative aspect-[4/5] rounded-2xl overflow-hidden cursor-pointer">
                    <img src="{{ $region['image'] }}"
                         alt="{{ $region['name'] }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                         loading="lazy"
                         onerror="this.src='https://placehold.co/400x500/ec4899/white?text={{ urlencode($region['name']) }}'">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        <h3 class="text-white font-bold text-lg">{{ $region['name'] }}</h3>
                        <p class="text-white/70 text-sm">{{ $region['count'] }}{{ __('home.experiences_count') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Popular Products -->
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">
                        {{ __('home.popular_title') }} <span class="text-pink-500">{{ __('home.popular_title_highlight') }}</span>{{ __('home.popular_title_suffix') }}
                    </h2>
                    <p class="text-slate-500 mt-1">{{ __('home.popular_subtitle') }}</p>
                </div>
            </div>

            <!-- Product Grid -->
            @if($popularProducts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($popularProducts as $index => $product)
                        <div class="relative">
                            <x-product.card :product="(object) $product" :showWishlist="true" />
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16 text-slate-400">
                    <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                    </svg>
                    <p class="text-slate-600 font-medium">{{ __('home.no_popular_products') }}</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Featured Collections -->
    <section class="py-16 sm:py-24 bg-gradient-to-b from-slate-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-3">
                    {{ __('home.featured_title') }} <span class="text-pink-500">{{ __('home.featured_title_highlight') }}</span>
                </h2>
                <p class="text-slate-500 max-w-2xl mx-auto">{{ __('home.featured_subtitle') }}</p>
            </div>

            <!-- Featured Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Main Featured -->
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}" class="group relative block overflow-hidden rounded-3xl aspect-[4/3] lg:aspect-auto lg:row-span-2">
                    <img src="https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=1200&q=80"
                         alt="{{ __('home.featured_seoul_title') }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
                        <span class="inline-block px-3 py-1 bg-pink-500 text-white text-xs font-semibold rounded-full mb-3">NEW</span>
                        <h3 class="text-2xl sm:text-3xl font-bold text-white mb-2">{{ __('home.featured_seoul_title') }}</h3>
                        <p class="text-white/80 text-sm sm:text-base mb-4">{{ __('home.featured_seoul_desc') }}</p>
                        <span class="inline-flex items-center text-white font-medium group-hover:gap-3 gap-2 transition-all">
                            {{ __('home.view_collection') }}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                            </svg>
                        </span>
                    </div>
                </a>

                <!-- Sub Featured 1 -->
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}" class="group relative block overflow-hidden rounded-3xl aspect-[4/3]">
                    <img src="https://images.unsplash.com/photo-1596178065887-1198b6148b2b?w=800&q=80"
                         alt="{{ __('home.featured_busan_title') }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <span class="inline-block px-3 py-1 bg-amber-500 text-white text-xs font-semibold rounded-full mb-3">HOT</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-white mb-2">{{ __('home.featured_busan_title') }}</h3>
                        <p class="text-white/80 text-sm">{{ __('home.featured_busan_desc') }}</p>
                    </div>
                </a>

                <!-- Sub Featured 2 -->
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}" class="group relative block overflow-hidden rounded-3xl aspect-[4/3]">
                    <img src="https://images.unsplash.com/photo-1544377193-33dcf4d68fb5?w=800&q=80"
                         alt="{{ __('home.featured_jeju_title') }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <span class="inline-block px-3 py-1 bg-emerald-500 text-white text-xs font-semibold rounded-full mb-3">BEST</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-white mb-2">{{ __('home.featured_jeju_title') }}</h3>
                        <p class="text-white/80 text-sm">{{ __('home.featured_jeju_desc') }}</p>
                    </div>
                </a>
            </div>

            <!-- Bottom Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}" class="group relative block overflow-hidden rounded-2xl aspect-[3/2]">
                    <img src="https://images.unsplash.com/photo-1517154421773-0529f29ea451?w=600&q=80"
                         alt="{{ __('home.featured_gyeongju_title') }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5">
                        <h3 class="text-lg font-bold text-white mb-1">{{ __('home.featured_gyeongju_title') }}</h3>
                        <p class="text-white/70 text-sm">{{ __('home.featured_gyeongju_desc') }}</p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}" class="group relative block overflow-hidden rounded-2xl aspect-[3/2]">
                    <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=600&q=80"
                         alt="{{ __('home.featured_gangwon_title') }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5">
                        <h3 class="text-lg font-bold text-white mb-1">{{ __('home.featured_gangwon_title') }}</h3>
                        <p class="text-white/70 text-sm">{{ __('home.featured_gangwon_desc') }}</p>
                    </div>
                </a>

                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}" class="group relative block overflow-hidden rounded-2xl aspect-[3/2]">
                    <img src="https://images.unsplash.com/photo-1538485399081-7191377e8241?w=600&q=80"
                         alt="{{ __('home.featured_jeonju_title') }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5">
                        <h3 class="text-lg font-bold text-white mb-1">{{ __('home.featured_jeonju_title') }}</h3>
                        <p class="text-white/70 text-sm">{{ __('home.featured_jeonju_desc') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Features - Simple Stats -->
    <section class="py-16 sm:py-20 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                <div class="text-center">
                    <div class="text-4xl sm:text-5xl font-bold text-pink-500 mb-2">500+</div>
                    <div class="text-slate-600 font-medium">{{ __('home.stats_guides') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl sm:text-5xl font-bold text-pink-500 mb-2">10K+</div>
                    <div class="text-slate-600 font-medium">{{ __('home.stats_customers') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl sm:text-5xl font-bold text-pink-500 mb-2">4.9</div>
                    <div class="text-slate-600 font-medium">{{ __('home.stats_rating') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl sm:text-5xl font-bold text-pink-500 mb-2">24/7</div>
                    <div class="text-slate-600 font-medium">{{ __('home.stats_support') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews - Simple -->
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">{{ __('home.reviews_title') }}</h2>
                <p class="text-slate-500 mt-2">{{ __('home.reviews_subtitle') }}</p>
            </div>

            <!-- Reviews Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($reviews as $review)
                @php
                    $locale = app()->getLocale();
                    $productTranslation = $review->product->getTranslation($locale) ?? $review->product->getTranslation('ko');
                    $userName = $review->user->name ?? __('home.anonymous');
                    $userInitials = mb_substr($userName, 0, 2);
                @endphp
                <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-slate-300 transition-colors">
                    <!-- Rating -->
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < $review->rating; $i++)
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @endfor
                        @for($i = $review->rating; $i < 5; $i++)
                        <svg class="w-4 h-4 text-slate-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @endfor
                    </div>

                    <!-- Comment -->
                    <p class="text-slate-600 leading-relaxed mb-4 line-clamp-3">"{{ $review->content }}"</p>

                    <!-- Product Tag -->
                    <a href="{{ route('products.show', ['locale' => $locale, 'product' => $review->product_id]) }}"
                       class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors mb-4 cursor-pointer">
                        {{ $productTranslation?->name ?? $review->product->name }}
                    </a>

                    <!-- Author -->
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        @if($review->user->avatar)
                        <img src="{{ $review->user->avatar }}" alt="{{ $userName }}" class="w-10 h-10 rounded-full object-cover">
                        @else
                        <div class="w-10 h-10 rounded-full bg-pink-500 flex items-center justify-center text-white font-bold text-sm">
                            {{ $userInitials }}
                        </div>
                        @endif
                        <div class="flex-1">
                            <div class="font-medium text-slate-900 text-sm">{{ $userName }}</div>
                            <div class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                    </svg>
                    <p class="text-slate-500">{{ __('home.no_reviews') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA Section - Simple -->
    <section class="py-16 sm:py-20 bg-slate-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                {{ __('home.cta_title') }}
            </h2>
            <p class="text-lg text-slate-400 mb-8 max-w-2xl mx-auto">
                {{ __('home.cta_subtitle') }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                   class="w-full sm:w-auto px-8 py-4 rounded-full bg-white text-slate-900 font-semibold hover:bg-slate-100 transition-colors cursor-pointer">
                    {{ __('home.cta_browse') }}
                </a>
                <a href="{{ route('register') }}"
                   class="w-full sm:w-auto px-8 py-4 rounded-full border border-slate-700 text-white font-semibold hover:bg-slate-800 transition-colors cursor-pointer">
                    {{ __('home.cta_register') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Minimal Styles -->
    @push('head')
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @endpush
</x-layouts.app>
