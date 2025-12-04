<x-layouts.app :title="__('home.hero_title')">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                    {{ __('home.hero_title') }}
                </h1>
                <p class="text-xl md:text-2xl text-indigo-100 mb-8 max-w-3xl mx-auto">
                    {{ __('home.hero_subtitle') }}
                </p>

                <!-- Search Bar -->
                <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET"
                      class="max-w-2xl mx-auto">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <input type="text"
                               name="keyword"
                               placeholder="{{ __('home.search_placeholder') }}"
                               class="flex-1 px-6 py-4 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <button type="submit"
                                class="px-8 py-4 bg-indigo-800 hover:bg-indigo-900 rounded-lg font-semibold transition">
                            {{ __('home.search_button') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">{{ __('home.types_title') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($productTypes as $type)
                    <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'type' => $type['value']]) }}"
                       class="flex flex-col items-center p-6 bg-gray-50 rounded-xl hover:bg-indigo-50 hover:shadow-md transition group">
                        <div class="w-16 h-16 mb-4 flex items-center justify-center bg-indigo-100 rounded-full group-hover:bg-indigo-200 transition">
                            @switch($type['value'])
                                @case('tour')
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                    </svg>
                                    @break
                                @case('activity')
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @break
                                @case('ticket')
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                    @break
                                @default
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                            @endswitch
                        </div>
                        <span class="font-medium text-gray-900">{{ $type['label'] }}</span>
                        <span class="text-sm text-gray-500">{{ $type['count'] }} {{ __('nav.products') }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Popular Products Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('home.popular_title') }}</h2>
                    <p class="text-gray-600 mt-1">{{ __('home.popular_subtitle') }}</p>
                </div>
                <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'sort' => 'popular']) }}"
                   class="text-indigo-600 hover:text-indigo-700 font-medium">
                    {{ __('home.view_all') }} &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($popularProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    <!-- Regions Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl font-bold text-gray-900">{{ __('home.regions_title') }}</h2>
                <p class="text-gray-600 mt-1">{{ __('home.regions_subtitle') }}</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($productsByRegion as $regionKey => $region)
                    <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'region' => $regionKey]) }}"
                       class="relative group overflow-hidden rounded-xl aspect-square">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10"></div>
                        <img src="/images/regions/{{ $regionKey }}.jpg"
                             alt="{{ $region['name'] }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                             onerror="this.src='/images/placeholder-region.jpg'">
                        <div class="absolute bottom-0 left-0 right-0 p-4 z-20">
                            <h3 class="text-white font-bold text-lg">{{ $region['name'] }}</h3>
                            <p class="text-white/80 text-sm">{{ $region['count'] }} {{ __('nav.products') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
