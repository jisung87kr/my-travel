<x-layouts.app :title="__('home.page_title')">
    <!-- Hero Section -->
    <section class="mt-6 bg-gradient-to-b from-pink-50/80 via-white to-white pt-12 pb-16 sm:pt-16 sm:pb-24">
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

            <!-- Search Widget - Clean Design -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200 p-2"
                     x-data="{
                         destination: '',
                         date: '',
                         guests: 1,
                         showDestination: false,
                         showDate: false,
                         showGuests: false
                     }">
                    <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET">
                        <div class="flex flex-col md:flex-row md:items-center">
                            <!-- Location Input -->
                            <div class="relative flex-1 group">
                                <button type="button"
                                        @click="showDestination = !showDestination; showDate = false; showGuests = false"
                                        class="w-full flex items-center gap-3 h-14 px-4 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer text-left">
                                    <svg class="w-5 h-5 text-pink-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="text-xs font-medium text-slate-400">{{ __('home.where_to') }}</span>
                                        <span class="text-sm font-medium truncate" :class="destination ? 'text-slate-900' : 'text-slate-400'" x-text="destination || '{{ __('home.search_placeholder') }}'"></span>
                                    </div>
                                </button>
                                <!-- Destination Dropdown -->
                                <div x-show="showDestination"
                                     @click.away="showDestination = false"
                                     x-transition
                                     class="absolute top-full left-0 mt-2 w-full md:w-72 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50"
                                     style="display: none;">
                                    <div class="px-3 pb-2">
                                        <input type="text"
                                               name="search"
                                               x-model="destination"
                                               placeholder="{{ __('home.search_placeholder') }}"
                                               class="w-full px-3 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500"
                                               @keydown.enter="showDestination = false">
                                    </div>
                                    <div class="border-t border-slate-100 pt-2">
                                        <p class="px-3 py-1.5 text-xs font-medium text-slate-400">{{ __('home.popular_destinations') }}</p>
                                        @php
                                            $searchRegions = [
                                                ['name' => __('home.region_seoul'), 'icon' => 'text-pink-500'],
                                                ['name' => __('home.region_busan'), 'icon' => 'text-blue-500'],
                                                ['name' => __('home.region_jeju'), 'icon' => 'text-orange-500'],
                                                ['name' => __('home.region_gyeonggi'), 'icon' => 'text-emerald-500'],
                                                ['name' => __('home.region_gangwon'), 'icon' => 'text-violet-500'],
                                            ];
                                        @endphp
                                        @foreach($searchRegions as $searchRegion)
                                        <button type="button"
                                                @click="destination = '{{ $searchRegion['name'] }}'; showDestination = false"
                                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4 {{ $searchRegion['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                            </svg>
                                            <span class="font-medium">{{ $searchRegion['name'] }}</span>
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="hidden md:block w-px h-8 bg-slate-200"></div>
                            <div class="md:hidden h-px bg-slate-100 mx-4"></div>

                            <!-- Date Input -->
                            <div class="relative md:w-[28%] group">
                                <button type="button"
                                        @click="showDate = !showDate; showDestination = false; showGuests = false"
                                        class="w-full flex items-center gap-3 h-14 px-4 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer text-left">
                                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="text-xs font-medium text-slate-400">{{ __('home.add_dates') }}</span>
                                        <span class="text-sm font-medium truncate" :class="date ? 'text-slate-900' : 'text-slate-400'" x-text="date || '{{ __('home.select_date') }}'"></span>
                                    </div>
                                </button>
                                <!-- Date Dropdown -->
                                <div x-show="showDate"
                                     @click.away="showDate = false"
                                     x-transition
                                     class="absolute top-full left-1/2 -translate-x-1/2 mt-2 bg-white rounded-xl shadow-xl border border-slate-200 p-3 z-50"
                                     style="display: none;">
                                    <input type="date"
                                           name="date"
                                           x-model="date"
                                           @change="showDate = false"
                                           class="px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500"
                                           min="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="hidden md:block w-px h-8 bg-slate-200"></div>
                            <div class="md:hidden h-px bg-slate-100 mx-4"></div>

                            <!-- Guests + Button -->
                            <div class="relative md:w-[32%] flex items-center gap-2 pr-1">
                                <button type="button"
                                        @click="showGuests = !showGuests; showDestination = false; showDate = false"
                                        class="flex items-center gap-3 h-14 px-4 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer flex-1 min-w-0 text-left">
                                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="text-xs font-medium text-slate-400">{{ __('home.travelers') }}</span>
                                        <span class="text-sm font-medium" :class="guests > 1 ? 'text-slate-900' : 'text-slate-400'" x-text="guests > 1 ? guests + '{{ __('home.guests_count') }}' : '{{ __('home.add_guests') }}'"></span>
                                    </div>
                                </button>
                                <!-- Guests Dropdown -->
                                <div x-show="showGuests"
                                     @click.away="showGuests = false"
                                     x-transition
                                     class="absolute top-full right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-200 p-4 z-50"
                                     style="display: none;">
                                    <input type="hidden" name="guests" :value="guests">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-sm font-medium text-slate-900">{{ __('home.travelers') }}</span>
                                            <p class="text-xs text-slate-500 mt-0.5">{{ __('home.travelers_age_info') }}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button"
                                                    @click="guests = Math.max(1, guests - 1)"
                                                    class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-500 hover:border-slate-400 transition-colors disabled:opacity-40 cursor-pointer"
                                                    :disabled="guests <= 1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                </svg>
                                            </button>
                                            <span class="text-sm font-semibold w-5 text-center" x-text="guests"></span>
                                            <button type="button"
                                                    @click="guests = Math.min(20, guests + 1)"
                                                    class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-500 hover:border-slate-400 transition-colors cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Search Button -->
                                <button type="submit"
                                        class="hidden md:flex w-12 h-12 rounded-xl bg-pink-500 hover:bg-pink-600 text-white items-center justify-center transition-colors flex-shrink-0 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Mobile Search Button -->
                            <div class="md:hidden p-2">
                                <button type="submit"
                                        class="w-full py-3.5 bg-pink-500 hover:bg-pink-600 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    {{ __('home.search_button') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>

    <!-- Category Navigation - Simple Pills -->
    <section class="top-16 lg:top-[72px] z-30 bg-white border-b border-slate-200" id="category-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-3 overflow-x-auto scrollbar-hide py-4 -mx-4 px-4 sm:mx-0 sm:px-0 justify-center">
                @php
                    $categories = [
                        ['name' => __('home.category_tour'), 'key' => 'tour', 'icon' => 'M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z'],
                        ['name' => __('home.category_activity'), 'key' => 'activity', 'icon' => 'M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z'],
                        ['name' => __('home.category_culture'), 'key' => 'culture', 'icon' => 'M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z'],
                        ['name' => __('home.category_food'), 'key' => 'food', 'icon' => 'M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.379a48.474 48.474 0 00-6-.371c-2.032 0-4.034.126-6 .371m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.169c0 .621-.504 1.125-1.125 1.125H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 016 13.12M12.265 3.11a.375.375 0 11-.53 0L12 2.845l.265.265zm-3 0a.375.375 0 11-.53 0L9 2.845l.265.265zm6 0a.375.375 0 11-.53 0L15 2.845l.265.265z'],
                        ['name' => __('home.category_nature'), 'key' => 'nature', 'icon' => 'M12.75 3.03v.568c0 .334.148.65.405.864l1.068.89c.442.369.535 1.01.216 1.49l-.51.766a2.25 2.25 0 01-1.161.886l-.143.048a1.107 1.107 0 00-.57 1.664c.369.555.169 1.307-.427 1.605L9 13.125l.423 1.059a.956.956 0 01-1.652.928l-.679-.906a1.125 1.125 0 00-1.906.172L4.5 15.75l-.612.153M12.75 3.031a9 9 0 00-8.862 12.872M12.75 3.031a9 9 0 016.69 14.036m0 0l-.177-.529A2.25 2.25 0 0017.128 15H16.5l-.324-.324a1.453 1.453 0 00-2.328.377l-.036.073a1.586 1.586 0 01-.982.816l-.99.282c-.55.157-.894.702-.8 1.267l.073.438c.08.474.49.821.97.821.846 0 1.598.542 1.865 1.345l.215.643m5.276-3.67a9.012 9.012 0 01-5.276 3.67m0 0a9 9 0 01-10.275-4.835M15.75 9c0 .896-.393 1.7-1.016 2.25'],
                        ['name' => __('home.category_night'), 'key' => 'night', 'icon' => 'M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z'],
                        ['name' => __('home.category_ticket'), 'key' => 'ticket', 'icon' => 'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z'],
                        ['name' => __('home.category_package'), 'key' => 'package', 'icon' => 'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9'],
                    ];
                @endphp

                @foreach($categories as $index => $category)
                <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'category' => $index === 0 ? null : $category['key']]) }}"
                   class="flex flex-col items-center justify-center gap-2.5 w-20 h-20 rounded-2xl text-xs font-medium transition-all cursor-pointer flex-shrink-0
                          {{ isset($category['active']) && $category['active']
                              ? 'bg-slate-900 text-white'
                              : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $category['icon'] }}" />
                    </svg>
                    <span>{{ $category['name'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

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
