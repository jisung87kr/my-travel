<x-layouts.app :title="__('nav.products')">
    <div class="min-h-screen bg-gray-50" x-data="productFilters()">
        <!-- Hero Section -->
        <div class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 text-center">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">{{ __('nav.products') }}</h1>
                <p class="text-lg text-slate-500 max-w-2xl mx-auto leading-relaxed">{{ __('product.list_subtitle') }}</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col lg:flex-row gap-10">
                <!-- Sidebar Filters (Desktop) -->
                <aside class="hidden lg:block w-72 flex-shrink-0">
                    <div class="sticky top-24 space-y-8">
                        <!-- Search -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('product.search') }}</h3>
                            <div class="relative group">
                                <input type="text"
                                       x-model="filters.keyword"
                                       @keydown.enter="applyFilters()"
                                       placeholder="{{ __('product.search_placeholder') }}"
                                       class="w-full pl-10 pr-4 py-3 bg-white rounded-xl border-gray-200 border shadow-sm focus:border-pink-500 focus:ring-pink-500 transition-all group-hover:border-pink-300">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Date Filter -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('product.date') }}</h3>
                                <button x-show="filters.date" 
                                        @click="filters.date = ''; applyFilters()"
                                        class="text-xs text-pink-600 hover:text-pink-700 font-medium">
                                    {{ __('product.reset') }}
                                </button>
                            </div>
                            <input type="date"
                                   x-model="filters.date"
                                   @change="applyFilters()"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 bg-white rounded-xl border-gray-200 border shadow-sm focus:border-pink-500 focus:ring-pink-500 cursor-pointer">
                        </div>

                        <!-- Guests Filter -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('product.guests') }}</h3>
                            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm space-y-4">
                                <!-- Adults -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-medium text-slate-700 block">{{ __('product.adults') }}</span>
                                        <span class="text-xs text-slate-400">{{ __('product.adults_age') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="button"
                                                @click="if(filters.adults > 1) { filters.adults--; applyFilters(); }"
                                                class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:border-gray-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                :disabled="filters.adults <= 1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                            </svg>
                                        </button>
                                        <span class="w-4 text-center font-semibold text-slate-900" x-text="filters.adults"></span>
                                        <button type="button"
                                                @click="filters.adults++; applyFilters()"
                                                class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:border-gray-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <!-- Children -->
                                <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                                    <div>
                                        <span class="text-sm font-medium text-slate-700 block">{{ __('product.children') }}</span>
                                        <span class="text-xs text-slate-400">{{ __('product.children_age') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="button"
                                                @click="if(filters.children > 0) { filters.children--; applyFilters(); }"
                                                class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:border-gray-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                :disabled="filters.children <= 0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                            </svg>
                                        </button>
                                        <span class="w-4 text-center font-semibold text-slate-900" x-text="filters.children"></span>
                                        <button type="button"
                                                @click="filters.children++; applyFilters()"
                                                class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:border-gray-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Region Filter -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('product.region') }}</h3>
                            <div class="flex flex-wrap gap-2">
                                <button type="button"
                                        @click="filters.region = ''; applyFilters()"
                                        class="px-3 py-1.5 text-sm rounded-lg border transition-all duration-200"
                                        :class="!filters.region ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-gray-200 hover:border-slate-300'">
                                    {{ __('product.all') }}
                                </button>
                                @foreach($regions as $region)
                                <button type="button"
                                        @click="filters.region = '{{ $region['label'] }}'; applyFilters()"
                                        class="px-3 py-1.5 text-sm rounded-lg border transition-all duration-200"
                                        :class="filters.region === '{{ $region['label'] }}' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-gray-200 hover:border-slate-300'">
                                    {{ $region['label'] }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('product.category') }}</h3>
                            <div class="flex flex-wrap gap-2">
                                <button type="button"
                                        @click="filters.category = ''; applyFilters()"
                                        class="px-3 py-1.5 text-sm rounded-lg border transition-all duration-200"
                                        :class="!filters.category ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-gray-200 hover:border-slate-300'">
                                    {{ __('product.all') }}
                                </button>
                                @foreach($categories as $category)
                                <button type="button"
                                        @click="filters.category = '{{ $category['value'] }}'; applyFilters()"
                                        class="px-3 py-1.5 text-sm rounded-lg border transition-all duration-200"
                                        :class="filters.category === '{{ $category['value'] }}' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-gray-200 hover:border-slate-300'">
                                    {{ $category['label'] }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Reset Filters -->
                        <button @click="resetFilters()" class="w-full py-3 px-4 rounded-xl border border-gray-200 text-slate-600 hover:bg-white hover:border-gray-300 hover:shadow-sm transition-all text-sm font-semibold flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            {{ __('product.reset_filters') }}
                        </button>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="flex-1 min-w-0">
                    <!-- Mobile Filter Toggle & Sort -->
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                        <!-- Mobile Filter Button -->
                        <button @click="showMobileFilters = true" class="lg:hidden flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-slate-700 hover:border-gray-300 transition-colors shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                            </svg>
                            <span class="font-medium">{{ __('product.filter') }}</span>
                            <span x-show="activeFilterCount > 0" x-text="activeFilterCount" class="ml-1 w-5 h-5 rounded-full bg-pink-500 text-white text-xs flex items-center justify-center font-bold"></span>
                        </button>

                        <!-- Results Count -->
                        <p class="text-slate-500 font-medium">
                            {{ __('product.total_products', ['count' => $products->total()]) }}
                        </p>

                        <!-- Sort Dropdown -->
                        <div class="relative ml-auto" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-slate-700 hover:border-gray-300 transition-colors shadow-sm min-w-[160px] justify-between">
                                <span class="text-sm font-medium" x-text="sortLabels[filters.sort]"></span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-20">
                                <template x-for="(label, value) in sortLabels" :key="value">
                                    <button @click="filters.sort = value; applyFilters(); open = false" 
                                            class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 transition-colors flex items-center justify-between group" 
                                            :class="filters.sort === value ? 'text-pink-600 font-medium bg-pink-50/50' : 'text-slate-600'">
                                        <span x-text="label"></span>
                                        <svg x-show="filters.sort === value" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Active Filters Badges -->
                    <div x-show="activeFilterCount > 0" class="flex flex-wrap gap-2 mb-8" x-transition>
                        <template x-if="filters.keyword">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-900 text-white text-sm shadow-sm">
                                <span x-text="'{{ __('product.search_tag') }}: ' + filters.keyword"></span>
                                <button @click="filters.keyword = ''; applyFilters()" class="hover:text-pink-300 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <template x-if="filters.region">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-900 text-white text-sm shadow-sm">
                                <span x-text="getRegionLabel(filters.region)"></span>
                                <button @click="filters.region = ''; applyFilters()" class="hover:text-pink-300 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <template x-if="filters.category">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-900 text-white text-sm shadow-sm">
                                <span x-text="getCategoryLabel(filters.category)"></span>
                                <button @click="filters.category = ''; applyFilters()" class="hover:text-pink-300 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                    </div>

                    <!-- Products Grid -->
                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">
                            @foreach($products as $product)
                                <x-product.card :product="$product" :showWishlist="true" />
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="mt-12 flex justify-center">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm">
                            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-50 flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('product.no_results') }}</h3>
                            <p class="text-slate-500 mb-8">{{ __('product.try_different') }}</p>
                            <button @click="resetFilters()" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-slate-900 text-white font-medium hover:bg-slate-800 transition-all hover:shadow-lg">
                                {{ __('product.reset_filters') }}
                            </button>
                        </div>
                    @endif
                </main>
            </div>
        </div>

        <!-- Mobile Filters Drawer -->
        <div x-show="showMobileFilters" 
             style="display: none;"
             class="relative z-50 lg:hidden" 
             aria-labelledby="slide-over-title" 
             role="dialog" 
             aria-modal="true">
            
            <div x-show="showMobileFilters" 
                 x-transition:enter="ease-in-out duration-500" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in-out duration-500" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="showMobileFilters = false"></div>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <div x-show="showMobileFilters"
                             x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                             x-transition:enter-start="translate-x-full"
                             x-transition:enter-end="translate-x-0"
                             x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                             x-transition:leave-start="translate-x-0"
                             x-transition:leave-end="translate-x-full"
                             class="pointer-events-auto w-screen max-w-md">
                            <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                                <!-- Header -->
                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                                    <h2 class="text-lg font-bold text-slate-900">{{ __('product.filter') }}</h2>
                                    <button @click="showMobileFilters = false" class="rounded-full p-2 hover:bg-gray-100 transition-colors">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Body -->
                                <div class="relative flex-1 px-6 py-6 space-y-8">
                                    <!-- Search -->
                                    <div class="space-y-3">
                                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('product.search') }}</h3>
                                        <input type="text" x-model="filters.keyword" placeholder="{{ __('product.search_placeholder') }}" class="w-full px-4 py-3 bg-gray-50 rounded-xl border-gray-200 focus:bg-white focus:border-pink-500 focus:ring-pink-500 transition-colors">
                                    </div>

                                    <!-- Date -->
                                    <div class="space-y-3">
                                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('product.date') }}</h3>
                                        <input type="date"
                                               x-model="filters.date"
                                               min="{{ date('Y-m-d') }}"
                                               class="w-full px-4 py-3 bg-gray-50 rounded-xl border-gray-200 focus:bg-white focus:border-pink-500 focus:ring-pink-500 cursor-pointer">
                                    </div>

                                    <!-- Guests -->
                                    <div class="space-y-4">
                                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('product.guests') }}</h3>
                                        <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-medium text-slate-700">{{ __('product.adults') }}</span>
                                                <div class="flex items-center gap-3">
                                                    <button @click="if(filters.adults > 1) filters.adults--" :disabled="filters.adults <= 1" class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-600 disabled:opacity-50">-</button>
                                                    <span class="w-4 text-center font-semibold" x-text="filters.adults"></span>
                                                    <button @click="filters.adults++" class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-600">+</button>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-medium text-slate-700">{{ __('product.children') }}</span>
                                                <div class="flex items-center gap-3">
                                                    <button @click="if(filters.children > 0) filters.children--" :disabled="filters.children <= 0" class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-600 disabled:opacity-50">-</button>
                                                    <span class="w-4 text-center font-semibold" x-text="filters.children"></span>
                                                    <button @click="filters.children++" class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-600">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Region -->
                                    <div class="space-y-3">
                                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('product.region') }}</h3>
                                        <div class="flex flex-wrap gap-2">
                                            <button @click="filters.region = ''" class="px-4 py-2 rounded-lg text-sm transition-colors" :class="!filters.region ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-600'">{{ __('product.all') }}</button>
                                            @foreach($regions as $region)
                                            <button @click="filters.region = '{{ $region['label'] }}'" class="px-4 py-2 rounded-lg text-sm transition-colors" :class="filters.region === '{{ $region['label'] }}' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-600'">{{ $region['label'] }}</button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Category -->
                                    <div class="space-y-3">
                                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('product.category') }}</h3>
                                        <div class="flex flex-wrap gap-2">
                                            <button @click="filters.category = ''" class="px-4 py-2 rounded-lg text-sm transition-colors" :class="!filters.category ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-600'">{{ __('product.all') }}</button>
                                            @foreach($categories as $category)
                                            <button @click="filters.category = '{{ $category['value'] }}'" class="px-4 py-2 rounded-lg text-sm transition-colors" :class="filters.category === '{{ $category['value'] }}' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-600'">{{ $category['label'] }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="border-t border-gray-100 px-6 py-6 bg-gray-50">
                                    <div class="flex gap-3">
                                        <button @click="resetFilters(); showMobileFilters = false" class="flex-1 py-3.5 rounded-xl border border-gray-200 bg-white text-slate-700 font-semibold hover:bg-gray-50 transition-colors">
                                            {{ __('product.reset') }}
                                        </button>
                                        <button @click="applyFilters(); showMobileFilters = false" class="flex-1 py-3.5 rounded-xl bg-pink-500 text-white font-semibold hover:bg-pink-600 shadow-lg shadow-pink-500/30 transition-all">
                                            {{ __('product.apply') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function productFilters() {
            return {
                showMobileFilters: false,
                filters: {
                    keyword: @json(request('keyword', '')),
                    date: @json(request('date', '')),
                    adults: parseInt(@json(request('adults', '1'))) || 1,
                    children: parseInt(@json(request('children', '0'))) || 0,
                    region: @json(request('region', '')),
                    category: @json(request('category', '')),
                    sort: @json(request('sort', 'newest'))
                },
                regions: @json($regions),
                categories: @json($categories),
                sortLabels: {
                    'newest': @json(__('product.sort_newest')),
                    'popular': @json(__('product.sort_popular')),
                    'rating': @json(__('product.sort_rating')),
                    'price_low': @json(__('product.sort_price_low')),
                    'price_high': @json(__('product.sort_price_high'))
                },
                get activeFilterCount() {
                    let count = 0;
                    if (this.filters.keyword) count++;
                    if (this.filters.date) count++;
                    if (this.filters.adults > 1 || this.filters.children > 0) count++;
                    if (this.filters.region) count++;
                    if (this.filters.category) count++;
                    return count;
                },
                getRegionLabel(value) {
                    const region = this.regions.find(r => r.label === value);
                    return region ? region.label : value;
                },
                getCategoryLabel(value) {
                    const category = this.categories.find(c => c.value === value);
                    return category ? category.label : value;
                },
                applyFilters() {
                    const params = new URLSearchParams();
                    if (this.filters.keyword) params.set('keyword', this.filters.keyword);
                    if (this.filters.date) params.set('date', this.filters.date);
                    if (this.filters.adults > 1) params.set('adults', this.filters.adults);
                    if (this.filters.children > 0) params.set('children', this.filters.children);
                    if (this.filters.region) params.set('region', this.filters.region);
                    if (this.filters.category) params.set('category', this.filters.category);
                    if (this.filters.sort && this.filters.sort !== 'newest') params.set('sort', this.filters.sort);

                    const baseUrl = '{{ route('products.index', ['locale' => app()->getLocale()]) }}';
                    const queryString = params.toString();
                    window.location.href = queryString ? `${baseUrl}?${queryString}` : baseUrl;
                },
                resetFilters() {
                    window.location.href = '{{ route('products.index', ['locale' => app()->getLocale()]) }}';
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>