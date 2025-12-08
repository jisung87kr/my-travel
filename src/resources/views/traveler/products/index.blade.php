<x-layouts.app :title="__('nav.products')">
    <div class="min-h-screen bg-gray-50" x-data="productFilters()">
        <!-- Hero Section -->
        <div class="bg-gradient-to-br from-rose-950 via-pink-950 to-fuchsia-950 relative overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute top-0 left-0 w-[50%] h-[50%] bg-gradient-to-br from-pink-500/30 to-transparent rounded-full blur-[80px]"></div>
                <div class="absolute bottom-0 right-0 w-[40%] h-[40%] bg-gradient-to-tl from-fuchsia-500/20 to-transparent rounded-full blur-[60px]"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3">{{ __('nav.products') }}</h1>
                <p class="text-rose-100/80 text-lg">현지 가이드와 함께하는 특별한 여행 경험을 찾아보세요</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filters (Desktop) -->
                <aside class="hidden lg:block w-72 flex-shrink-0">
                    <div class="sticky top-24 space-y-6">
                        <!-- Search -->
                        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                                검색
                            </h3>
                            <div class="relative">
                                <input type="text"
                                       x-model="filters.keyword"
                                       @keydown.enter="applyFilters()"
                                       placeholder="검색어를 입력하세요"
                                       class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 focus:border-pink-300 focus:ring focus:ring-pink-200/50 text-sm">
                                <button type="button" @click="applyFilters()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Date Filter -->
                        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                일정
                            </h3>
                            <input type="date"
                                   x-model="filters.date"
                                   @change="applyFilters()"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-pink-300 focus:ring focus:ring-pink-200/50 text-sm">
                            <button x-show="filters.date"
                                    @click="filters.date = ''; applyFilters()"
                                    class="mt-2 text-xs text-gray-500 hover:text-pink-500 transition-colors">
                                날짜 초기화
                            </button>
                        </div>

                        <!-- Guests Filter -->
                        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                인원
                            </h3>
                            <div class="space-y-3">
                                <!-- Adults -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">성인</span>
                                        <span class="text-xs text-gray-500 block">만 13세 이상</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="button"
                                                @click="if(filters.adults > 1) { filters.adults--; applyFilters(); }"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-400 transition-colors disabled:opacity-40"
                                                :disabled="filters.adults <= 1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                            </svg>
                                        </button>
                                        <span class="w-6 text-center font-medium text-gray-900" x-text="filters.adults"></span>
                                        <button type="button"
                                                @click="filters.adults++; applyFilters()"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <!-- Children -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">아동</span>
                                        <span class="text-xs text-gray-500 block">만 2~12세</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="button"
                                                @click="if(filters.children > 0) { filters.children--; applyFilters(); }"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-400 transition-colors disabled:opacity-40"
                                                :disabled="filters.children <= 0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                            </svg>
                                        </button>
                                        <span class="w-6 text-center font-medium text-gray-900" x-text="filters.children"></span>
                                        <button type="button"
                                                @click="filters.children++; applyFilters()"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Region Filter -->
                        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                지역
                            </h3>
                            <div class="space-y-2">
                                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="region_filter" value="" x-model="filters.region" @change="applyFilters()" class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                                    <span class="text-sm text-gray-700">전체</span>
                                </label>
                                @foreach($regions as $region)
                                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="region_filter" value="{{ $region['value'] }}" x-model="filters.region" @change="applyFilters()" class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                                    <span class="text-sm text-gray-700">{{ $region['label'] }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Type Filter -->
                        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                </svg>
                                카테고리
                            </h3>
                            <div class="space-y-2">
                                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="type_filter" value="" x-model="filters.type" @change="applyFilters()" class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                                    <span class="text-sm text-gray-700">전체</span>
                                </label>
                                @foreach($types as $type)
                                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="type_filter" value="{{ $type['value'] }}" x-model="filters.type" @change="applyFilters()" class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                                    <span class="text-sm text-gray-700">{{ $type['label'] }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Reset Filters -->
                        <button @click="resetFilters()" class="w-full py-3 px-4 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-colors text-sm font-medium">
                            필터 초기화
                        </button>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="flex-1 min-w-0">
                    <!-- Mobile Filter Toggle & Sort -->
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <!-- Mobile Filter Button -->
                        <button @click="showMobileFilters = true" class="lg:hidden flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 hover:border-gray-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                            </svg>
                            필터
                            <span x-show="activeFilterCount > 0" x-text="activeFilterCount" class="ml-1 w-5 h-5 rounded-full bg-pink-500 text-white text-xs flex items-center justify-center"></span>
                        </button>

                        <!-- Results Count -->
                        <p class="text-sm text-gray-500">
                            총 <span class="font-semibold text-gray-900">{{ $products->total() }}</span>개의 상품
                        </p>

                        <!-- Sort Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 hover:border-gray-300 transition-colors text-sm">
                                <span x-text="sortLabels[filters.sort]"></span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-20">
                                <template x-for="(label, value) in sortLabels" :key="value">
                                    <button @click="filters.sort = value; applyFilters(); open = false" class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 transition-colors" :class="filters.sort === value ? 'text-pink-600 font-medium' : 'text-gray-700'" x-text="label"></button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Active Filters -->
                    <div x-show="activeFilterCount > 0" class="flex flex-wrap gap-2 mb-6">
                        <template x-if="filters.keyword">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-pink-50 text-pink-700 text-sm">
                                <span x-text="'검색: ' + filters.keyword"></span>
                                <button @click="filters.keyword = ''; applyFilters()" class="hover:text-pink-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <template x-if="filters.region">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-cyan-50 text-cyan-700 text-sm">
                                <span x-text="'지역: ' + getRegionLabel(filters.region)"></span>
                                <button @click="filters.region = ''; applyFilters()" class="hover:text-cyan-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <template x-if="filters.type">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-orange-50 text-orange-700 text-sm">
                                <span x-text="'카테고리: ' + getTypeLabel(filters.type)"></span>
                                <button @click="filters.type = ''; applyFilters()" class="hover:text-orange-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                    </div>

                    <!-- Products Grid -->
                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <x-product.card :product="$product" :showWishlist="true" />
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="mt-10">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gray-100 flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">검색 결과가 없습니다</h3>
                            <p class="text-gray-500 mb-6">다른 검색어나 필터를 시도해보세요</p>
                            <button @click="resetFilters()" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-pink-500 text-white font-medium hover:bg-pink-600 transition-colors">
                                필터 초기화
                            </button>
                        </div>
                    @endif
                </main>
            </div>
        </div>

        <!-- Mobile Filters Drawer -->
        <div x-show="showMobileFilters" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 z-50 lg:hidden" @click="showMobileFilters = false"></div>

        <div x-show="showMobileFilters" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed right-0 top-0 bottom-0 w-full max-w-sm bg-white z-50 lg:hidden overflow-y-auto">
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b border-gray-100 px-5 py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">필터</h2>
                <button @click="showMobileFilters = false" class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-5 space-y-6">
                <!-- Search -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">검색</h3>
                    <input type="text" x-model="filters.keyword" placeholder="검색어를 입력하세요" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-pink-300 focus:ring focus:ring-pink-200/50 text-sm">
                </div>

                <!-- Date -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">일정</h3>
                    <input type="date"
                           x-model="filters.date"
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-pink-300 focus:ring focus:ring-pink-200/50 text-sm">
                </div>

                <!-- Guests -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">인원</h3>
                    <div class="space-y-3">
                        <!-- Adults -->
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-sm font-medium text-gray-700">성인</span>
                                <span class="text-xs text-gray-500 block">만 13세 이상</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button"
                                        @click="if(filters.adults > 1) filters.adults--"
                                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-400 transition-colors"
                                        :disabled="filters.adults <= 1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                    </svg>
                                </button>
                                <span class="w-6 text-center font-medium text-gray-900" x-text="filters.adults"></span>
                                <button type="button"
                                        @click="filters.adults++"
                                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Children -->
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-sm font-medium text-gray-700">아동</span>
                                <span class="text-xs text-gray-500 block">만 2~12세</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button"
                                        @click="if(filters.children > 0) filters.children--"
                                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-400 transition-colors"
                                        :disabled="filters.children <= 0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                    </svg>
                                </button>
                                <span class="w-6 text-center font-medium text-gray-900" x-text="filters.children"></span>
                                <button type="button"
                                        @click="filters.children++"
                                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Region -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">지역</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="mobile_region" value="" x-model="filters.region" class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                            <span class="text-sm text-gray-700">전체</span>
                        </label>
                        @foreach($regions as $region)
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="mobile_region" value="{{ $region['value'] }}" x-model="filters.region" class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                            <span class="text-sm text-gray-700">{{ $region['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Type -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">카테고리</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="mobile_type" value="" x-model="filters.type" class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                            <span class="text-sm text-gray-700">전체</span>
                        </label>
                        @foreach($types as $type)
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="mobile_type" value="{{ $type['value'] }}" x-model="filters.type" class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                            <span class="text-sm text-gray-700">{{ $type['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="sticky bottom-0 bg-white border-t border-gray-100 p-5 flex gap-3">
                <button @click="resetFilters(); showMobileFilters = false" class="flex-1 py-3 rounded-xl border border-gray-200 text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    초기화
                </button>
                <button @click="applyFilters(); showMobileFilters = false" class="flex-1 py-3 rounded-xl bg-pink-500 text-white font-medium hover:bg-pink-600 transition-colors">
                    적용하기
                </button>
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
                    type: @json(request('type', '')),
                    sort: @json(request('sort', 'newest'))
                },
                regions: @json($regions),
                types: @json($types),
                sortLabels: {
                    'newest': '최신순',
                    'popular': '인기순',
                    'rating': '평점순',
                    'price_low': '가격 낮은순',
                    'price_high': '가격 높은순'
                },
                get activeFilterCount() {
                    let count = 0;
                    if (this.filters.keyword) count++;
                    if (this.filters.date) count++;
                    if (this.filters.adults > 1 || this.filters.children > 0) count++;
                    if (this.filters.region) count++;
                    if (this.filters.type) count++;
                    return count;
                },
                getRegionLabel(value) {
                    const region = this.regions.find(r => r.value === value);
                    return region ? region.label : value;
                },
                getTypeLabel(value) {
                    const type = this.types.find(t => t.value === value);
                    return type ? type.label : value;
                },
                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const date = new Date(dateStr);
                    const days = ['일', '월', '화', '수', '목', '금', '토'];
                    return `${date.getMonth() + 1}월 ${date.getDate()}일 (${days[date.getDay()]})`;
                },
                applyFilters() {
                    const params = new URLSearchParams();
                    if (this.filters.keyword) params.set('keyword', this.filters.keyword);
                    if (this.filters.date) params.set('date', this.filters.date);
                    if (this.filters.adults > 1) params.set('adults', this.filters.adults);
                    if (this.filters.children > 0) params.set('children', this.filters.children);
                    if (this.filters.region) params.set('region', this.filters.region);
                    if (this.filters.type) params.set('type', this.filters.type);
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

        // Wishlist toggle function
        async function toggleWishlist(productId, button) {
            try {
                const response = await fetch(`/wishlist/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    if (response.status === 401) {
                        window.location.href = '/login';
                        return;
                    }
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                if (data.success) {
                    const isNowWishlisted = data.added;
                    const icon = button.querySelector('.wishlist-icon');

                    // Update button state
                    button.dataset.wishlisted = isNowWishlisted ? 'true' : 'false';

                    if (isNowWishlisted) {
                        button.classList.remove('text-gray-500', 'hover:text-pink-500');
                        button.classList.add('text-pink-500');
                        icon.setAttribute('fill', 'currentColor');
                    } else {
                        button.classList.remove('text-pink-500');
                        button.classList.add('text-gray-500', 'hover:text-pink-500');
                        icon.setAttribute('fill', 'none');
                    }

                    // Add animation
                    icon.classList.add('scale-125');
                    setTimeout(() => icon.classList.remove('scale-125'), 200);
                }
            } catch (error) {
                console.error('Wishlist error:', error);
            }
        }
    </script>
    @endpush
</x-layouts.app>
