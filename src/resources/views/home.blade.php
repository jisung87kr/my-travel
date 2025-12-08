<x-layouts.app title="한국의 특별한 순간을 만나다">
    <!-- Hero Section - Aurora UI with Glassmorphism -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Aurora Animated Background -->
        <div class="absolute inset-0">
            <!-- Base gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900"></div>
            <!-- Aurora layers -->
            <div class="absolute inset-0 opacity-60">
                <div class="absolute top-0 -left-1/4 w-1/2 h-1/2 bg-gradient-to-br from-cyan-400/40 via-transparent to-transparent rounded-full blur-3xl animate-aurora-1"></div>
                <div class="absolute top-1/4 right-0 w-1/2 h-1/2 bg-gradient-to-bl from-pink-500/40 via-transparent to-transparent rounded-full blur-3xl animate-aurora-2"></div>
                <div class="absolute bottom-0 left-1/3 w-1/2 h-1/2 bg-gradient-to-t from-violet-500/30 via-transparent to-transparent rounded-full blur-3xl animate-aurora-3"></div>
            </div>
            <!-- Subtle noise texture -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMzAwIj48ZmlsdGVyIGlkPSJhIiB4PSIwIiB5PSIwIj48ZmVUdXJidWxlbmNlIGJhc2VGcmVxdWVuY3k9Ii43NSIgc3RpdGNoVGlsZXM9InN0aXRjaCIgdHlwZT0iZnJhY3RhbE5vaXNlIi8+PC9maWx0ZXI+PHJlY3QgZmlsdGVyPSJ1cmwoI2EpIiBoZWlnaHQ9IjEwMCUiIG9wYWNpdHk9Ii4wNSIgd2lkdGg9IjEwMCUiLz48L3N2Zz4=')] opacity-50"></div>
        </div>

        <!-- Floating particles -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-white/20 rounded-full animate-float-slow"></div>
            <div class="absolute top-1/3 right-1/3 w-1.5 h-1.5  bg-cyan-300/30 rounded-full animate-float-medium"></div>
            <div class="absolute bottom-1/3 left-1/2 w-1 h-1 bg-pink-300/30 rounded-full animate-float-fast"></div>
            <div class="absolute top-1/2 right-1/4 w-2 h-2 bg-violet-300/20 rounded-full animate-float-slow"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center pt-24 pb-12">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 py-2 px-4 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-sm font-medium mb-8 animate-fade-in-up">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-400"></span>
                </span>
                새로운 여행의 시작
            </div>

            <!-- Main Heading -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 tracking-tight leading-[1.1] animate-fade-in-up animation-delay-100">
                한국의
                <span class="relative inline-block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-pink-300 to-violet-300 animate-gradient-text">특별한 순간</span>
                </span>
                을<br class="hidden sm:block" />
                만나다
            </h1>

            <!-- Subheading -->
            <p class="text-lg sm:text-xl md:text-2xl text-white/80 mb-12 max-w-2xl mx-auto font-light animate-fade-in-up animation-delay-200">
                현지 가이드와 함께하는 프리미엄 투어 & 액티비티
            </p>

            <!-- Search Widget - Glassmorphism -->
            <div class="w-full max-w-4xl mx-auto animate-fade-in-up animation-delay-300 relative z-30">
                <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl shadow-black/10 p-2 ring-1 ring-white/20">
                    <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET">
                        <div class="flex flex-col md:flex-row md:items-center gap-1">

                            <!-- Location Input -->
                            <div class="relative flex-1 group">
                                <div class="flex items-center gap-3 h-14 px-5 rounded-xl hover:bg-gray-50 transition-colors duration-200 cursor-text">
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">여행지</span>
                                        <input type="text"
                                               name="location"
                                               placeholder="어디로 떠나시나요?"
                                               class="w-full bg-transparent border-none p-0 text-gray-900 placeholder-gray-400 text-sm font-medium focus:ring-0 focus:outline-none truncate">
                                    </div>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="hidden md:block w-px h-8 bg-gray-200"></div>
                            <div class="md:hidden h-px bg-gray-100 mx-4"></div>

                            <!-- Date Input -->
                            <div class="relative md:w-[28%] group">
                                <div class="flex items-center gap-3 h-14 px-5 rounded-xl hover:bg-gray-50 transition-colors duration-200 cursor-pointer">
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">일정</span>
                                        <input type="date"
                                               name="date"
                                               class="w-full bg-transparent border-none p-0 text-gray-900 text-sm font-medium focus:ring-0 focus:outline-none cursor-pointer">
                                    </div>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="hidden md:block w-px h-8 bg-gray-200"></div>
                            <div class="md:hidden h-px bg-gray-100 mx-4"></div>

                            <!-- Guests + Button -->
                            <div class="relative md:w-[32%] flex items-center gap-2 pr-1">
                                <div class="flex items-center gap-3 h-14 px-5 rounded-xl hover:bg-gray-50 transition-colors duration-200 cursor-pointer flex-1 min-w-0">
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">여행자</span>
                                        <select name="guests"
                                                class="w-full bg-transparent border-none p-0 text-gray-900 text-sm font-medium focus:ring-0 focus:outline-none cursor-pointer appearance-none">
                                            <option value="">인원 선택</option>
                                            <option value="1">1명</option>
                                            <option value="2">2명</option>
                                            <option value="3">3명</option>
                                            <option value="4">4명</option>
                                            <option value="5">5명 이상</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Search Button (Desktop) -->
                                <button type="submit"
                                        class="hidden md:flex w-12 h-12 rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white items-center justify-center shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300 hover:scale-105 active:scale-95 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Mobile Search Button -->
                            <div class="md:hidden p-2">
                                <button type="submit"
                                        class="w-full py-3.5 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/25 transition-all duration-300 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    검색하기
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Quick suggestions -->
                <div class="flex flex-wrap justify-center gap-2 mt-6 animate-fade-in-up animation-delay-400">
                    @php $quickTags = ['서울', '부산', '제주도', '경주', '전주']; @endphp
                    @foreach($quickTags as $tag)
                    <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'location' => $tag]) }}"
                       class="px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white/90 text-sm font-medium hover:bg-white/20 hover:border-white/30 transition-all duration-200 cursor-pointer">
                        {{ $tag }}
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Scroll indicator -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
                <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </div>
        </div>
    </section>

    <!-- Category Navigation - Modern Pill Style -->
    <section class="sticky top-16 lg:top-[72px] z-30 bg-white/95 backdrop-blur-xl border-b border-gray-100/80 transition-all duration-300" id="category-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-3 overflow-x-auto scrollbar-hide py-4 -mx-4 px-4 sm:mx-0 sm:px-0">
                @php
                    $categories = [
                        ['name' => '전체', 'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z', 'active' => true],
                        ['name' => '투어', 'icon' => 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418'],
                        ['name' => '액티비티', 'icon' => 'M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z'],
                        ['name' => '문화체험', 'icon' => 'M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z'],
                        ['name' => '식도락', 'icon' => 'M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513m-3-4.87v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.38a48.474 48.474 0 00-6-.37c-2.032 0-4.034.125-6 .37m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.17c0 .62-.504 1.124-1.125 1.124H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 016 13.12M12.265 3.11a.375.375 0 11-.53 0L12 2.845l.265.265zm-3 0a.375.375 0 11-.53 0L9 2.845l.265.265zm6 0a.375.375 0 11-.53 0L15 2.845l.265.265z'],
                        ['name' => '자연', 'icon' => 'M6.115 5.19l.319 1.913A6 6 0 008.11 10.36L9.75 12l-.387.775c-.217.433-.132.956.21 1.298l1.348 1.348c.21.21.329.497.329.795v1.089c0 .426.24.815.622 1.006l.153.076c.433.217.956.132 1.298-.21l.723-.723a8.7 8.7 0 002.288-4.042 1.087 1.087 0 00-.358-1.099l-1.33-1.108c-.251-.21-.582-.299-.905-.245l-1.17.195a1.125 1.125 0 01-.98-.314l-.295-.295a1.125 1.125 0 010-1.591l.13-.132a1.125 1.125 0 011.3-.21l.603.302a.809.809 0 001.086-1.086L14.25 7.5l1.256-.837a4.5 4.5 0 001.528-1.732l.146-.292M6.115 5.19A9 9 0 1017.18 4.64M6.115 5.19A8.965 8.965 0 0112 3c1.929 0 3.716.607 5.18 1.64'],
                        ['name' => '야간투어', 'icon' => 'M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z'],
                        ['name' => '티켓', 'icon' => 'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z'],
                        ['name' => '패키지', 'icon' => 'm21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9'],
                    ];
                @endphp

                @foreach($categories as $index => $category)
                <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'category' => $index === 0 ? null : strtolower($category['name'])]) }}"
                   class="group flex items-center gap-2 px-4 py-2.5 rounded-full border transition-all duration-200 cursor-pointer flex-shrink-0
                          {{ isset($category['active']) && $category['active']
                              ? 'bg-slate-900 border-slate-900 text-white shadow-lg shadow-slate-900/20'
                              : 'bg-white border-gray-200 text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4 {{ isset($category['active']) && $category['active'] ? 'text-white' : 'text-gray-500 group-hover:text-gray-700' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $category['icon'] }}" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap">{{ $category['name'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Recommended Products -->
    <section class="py-16 sm:py-24 bg-gradient-to-b from-white to-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-10">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-50 text-pink-600 text-xs font-semibold mb-3">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        이달의 추천
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">특별한 체험을 만나보세요</h2>
                    <p class="text-gray-500 mt-2">여행자들이 가장 만족한 경험</p>
                </div>
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                   class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-gray-900 hover:text-pink-600 transition-colors group">
                    전체보기
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($recommendedProducts as $product)
                    <div class="group">
                        <x-product.card :product="(object) $product" :showWishlist="true" />
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-400 bg-white rounded-2xl border border-gray-100">
                        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">등록된 상품이 없습니다</p>
                        <p class="text-gray-400 text-sm mt-1">곧 새로운 체험이 추가됩니다</p>
                    </div>
                @endforelse
            </div>

            <!-- Mobile View All -->
            <div class="sm:hidden mt-8 text-center">
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition-colors">
                    전체 상품 보기
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Regions - Modern Bento Grid -->
    <section class="py-16 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-50 text-cyan-600 text-xs font-semibold mb-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    인기 여행지
                </div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight">어디로 떠나볼까요?</h2>
                <p class="text-gray-500 mt-3">대한민국 구석구석, 매력적인 도시들을 만나보세요</p>
            </div>

            <!-- Bento Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 auto-rows-[140px] sm:auto-rows-[180px] gap-3 sm:gap-4">
                @foreach($regions as $region)
                    @php
                        // Bento Layout: First item large, others varied
                        $gridClasses = match($loop->index) {
                            0 => 'col-span-2 row-span-2 md:col-span-2 md:row-span-2',
                            1 => 'col-span-1 row-span-1 md:col-span-2 md:row-span-1',
                            2 => 'col-span-1 row-span-1 md:col-span-2 md:row-span-1',
                            3 => 'col-span-2 row-span-1 md:col-span-2 md:row-span-1',
                            default => 'col-span-1 row-span-1 md:col-span-2 lg:col-span-1 md:row-span-1'
                        };
                    @endphp

                    <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'region' => $region['value']]) }}"
                       class="relative group overflow-hidden rounded-2xl sm:rounded-3xl cursor-pointer {{ $gridClasses }}">

                        <!-- Image with Overlay -->
                        <div class="absolute inset-0">
                            <img src="{{ $region['image'] }}"
                                 alt="{{ $region['name'] }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                 loading="lazy"
                                 onerror="this.src='https://placehold.co/600x600/6366f1/white?text={{ urlencode($region['name']) }}'">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        </div>

                        <!-- Content -->
                        <div class="absolute inset-0 p-4 sm:p-5 flex flex-col justify-end">
                            <div class="transform transition-all duration-300 group-hover:-translate-y-1">
                                <h3 class="text-white font-bold text-lg sm:text-xl lg:text-2xl mb-1">{{ $region['name'] }}</h3>
                                <div class="flex items-center gap-1.5 text-white/80 text-xs sm:text-sm">
                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                                    {{ $region['count'] }}개의 체험
                                </div>
                            </div>
                        </div>

                        <!-- Hover Arrow -->
                        <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Popular Experiences -->
    <section class="py-16 sm:py-24 bg-gradient-to-b from-gray-50 to-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-10">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-50 text-violet-600 text-xs font-semibold mb-3">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z" />
                        </svg>
                        실시간 인기
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">지금 가장 HOT한 체험</h2>
                    <p class="text-gray-500 mt-2">여행자들이 지금 가장 많이 예약하는 베스트셀러</p>
                </div>

                <!-- Carousel Navigation -->
                <div class="hidden sm:flex items-center gap-2">
                    <button type="button" id="popular-prev"
                            class="w-10 h-10 rounded-full border border-gray-200 bg-white flex items-center justify-center text-gray-600 hover:border-gray-300 hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <button type="button" id="popular-next"
                            class="w-10 h-10 rounded-full border border-gray-200 bg-white flex items-center justify-center text-gray-600 hover:border-gray-300 hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Horizontal Scroll Container -->
            <div id="popular-carousel" class="flex gap-5 overflow-x-auto scrollbar-hide pb-4 -mx-4 px-4 sm:mx-0 sm:px-0 snap-x snap-mandatory scroll-smooth">
                @forelse($popularProducts as $product)
                    <div class="flex-shrink-0 w-[280px] sm:w-[300px] snap-start">
                        <x-product.card :product="(object) $product" :showWishlist="true" />
                    </div>
                @empty
                    <div class="w-full flex flex-col items-center justify-center py-16 text-gray-400 bg-white rounded-2xl border border-gray-100">
                        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">인기 상품이 없습니다</p>
                    </div>
                @endforelse
            </div>

            <!-- Scroll Indicator -->
            <div class="flex justify-center gap-1.5 mt-6 sm:hidden">
                @for($i = 0; $i < min(count($popularProducts ?? []), 5); $i++)
                <div class="w-1.5 h-1.5 rounded-full {{ $i === 0 ? 'bg-gray-900' : 'bg-gray-300' }}"></div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Styles & Scripts -->
    @push('head')
    <style>
        /* Scrollbar Hide */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

        /* Smooth Scroll */
        html { scroll-behavior: smooth; }

        /* Aurora Animations */
        @keyframes aurora-1 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -50px) rotate(5deg); }
            66% { transform: translate(-20px, 20px) rotate(-5deg); }
        }
        @keyframes aurora-2 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(-40px, 30px) rotate(-5deg); }
            66% { transform: translate(20px, -40px) rotate(5deg); }
        }
        @keyframes aurora-3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, 30px) scale(1.1); }
        }
        .animate-aurora-1 { animation: aurora-1 20s ease-in-out infinite; }
        .animate-aurora-2 { animation: aurora-2 25s ease-in-out infinite; }
        .animate-aurora-3 { animation: aurora-3 15s ease-in-out infinite; }

        /* Floating Particles */
        @keyframes float-slow {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0.2; }
            50% { transform: translateY(-20px) translateX(10px); opacity: 0.4; }
        }
        @keyframes float-medium {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0.3; }
            50% { transform: translateY(-15px) translateX(-8px); opacity: 0.5; }
        }
        @keyframes float-fast {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0.2; }
            50% { transform: translateY(-10px) translateX(5px); opacity: 0.4; }
        }
        .animate-float-slow { animation: float-slow 8s ease-in-out infinite; }
        .animate-float-medium { animation: float-medium 6s ease-in-out infinite; }
        .animate-float-fast { animation: float-fast 4s ease-in-out infinite; }

        /* Gradient Text Animation */
        @keyframes gradient-text {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .animate-gradient-text {
            background-size: 200% auto;
            animation: gradient-text 4s linear infinite;
        }

        /* Fade In Up Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        .animation-delay-100 { animation-delay: 0.1s; }
        .animation-delay-200 { animation-delay: 0.2s; }
        .animation-delay-300 { animation-delay: 0.3s; }
        .animation-delay-400 { animation-delay: 0.4s; }

        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            .animate-aurora-1,
            .animate-aurora-2,
            .animate-aurora-3,
            .animate-float-slow,
            .animate-float-medium,
            .animate-float-fast,
            .animate-gradient-text,
            .animate-fade-in-up {
                animation: none;
            }
            .animate-fade-in-up {
                opacity: 1;
                transform: none;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Wishlist Toggle
        function toggleWishlist(productId, button) {
            const icon = button.querySelector('.wishlist-icon');
            const isWishlisted = button.dataset.wishlisted === 'true';

            // Optimistic UI update
            button.classList.add('pointer-events-none');

            fetch(`/wishlist/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const nowWishlisted = data.added;
                    button.dataset.wishlisted = nowWishlisted ? 'true' : 'false';

                    if (nowWishlisted) {
                        button.classList.remove('text-gray-500');
                        button.classList.add('text-pink-500');
                        icon.setAttribute('fill', 'currentColor');
                    } else {
                        button.classList.remove('text-pink-500');
                        button.classList.add('text-gray-500');
                        icon.setAttribute('fill', 'none');
                    }

                    // Add heart animation
                    icon.classList.add('scale-125');
                    setTimeout(() => icon.classList.remove('scale-125'), 200);
                }
            })
            .catch(error => {
                console.error('Wishlist error:', error);
            })
            .finally(() => {
                button.classList.remove('pointer-events-none');
            });
        }

        // Carousel Navigation
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('popular-carousel');
            const prevBtn = document.getElementById('popular-prev');
            const nextBtn = document.getElementById('popular-next');

            if (carousel && prevBtn && nextBtn) {
                const scrollAmount = 320;

                prevBtn.addEventListener('click', () => {
                    carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                });

                nextBtn.addEventListener('click', () => {
                    carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                });

                // Update button states
                carousel.addEventListener('scroll', () => {
                    prevBtn.disabled = carousel.scrollLeft <= 0;
                    nextBtn.disabled = carousel.scrollLeft >= carousel.scrollWidth - carousel.clientWidth - 10;
                });
            }

            // Category nav scroll shadow
            const categoryNav = document.getElementById('category-nav');
            if (categoryNav) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 100) {
                        categoryNav.classList.add('shadow-sm');
                    } else {
                        categoryNav.classList.remove('shadow-sm');
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.app>
