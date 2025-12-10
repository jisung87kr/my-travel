<x-layouts.app title="한국의 특별한 순간을 만나다">
    <!-- Hero Section - Modern Aurora Style -->
    <section class="relative min-h-[85vh] lg:min-h-[90vh] flex items-center justify-center overflow-hidden">
        <!-- Background - Pink/Rose Aurora Theme -->
        <div class="absolute inset-0">
            <!-- Base gradient - Travel Pink Theme -->
            <div class="absolute inset-0 bg-gradient-to-br from-rose-950 via-pink-950 to-fuchsia-950"></div>

            <!-- Aurora Mesh Gradient Layers -->
            <div class="absolute inset-0">
                <!-- Primary Pink Glow -->
                <div class="absolute top-0 left-0 w-[70%] h-[60%] bg-gradient-to-br from-pink-500/40 via-rose-500/20 to-transparent rounded-full blur-[100px] animate-aurora-1"></div>
                <!-- Cyan Accent -->
                <div class="absolute top-1/4 right-0 w-[50%] h-[50%] bg-gradient-to-bl from-cyan-400/30 via-teal-500/15 to-transparent rounded-full blur-[80px] animate-aurora-2"></div>
                <!-- Magenta Bottom -->
                <div class="absolute bottom-0 left-1/4 w-[60%] h-[50%] bg-gradient-to-t from-fuchsia-500/25 via-pink-500/15 to-transparent rounded-full blur-[90px] animate-aurora-3"></div>
                <!-- Orange/Coral Highlight -->
                <div class="absolute top-1/3 left-1/2 w-[40%] h-[40%] bg-gradient-to-r from-orange-400/20 via-rose-400/15 to-transparent rounded-full blur-[70px] animate-aurora-4"></div>
            </div>

            <!-- Gradient Mesh Overlay -->
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-transparent via-rose-950/50 to-rose-950/80"></div>
            <!-- Subtle Grid Pattern -->
{{--            <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22 width=%2232%22 height=%2232%22 fill=%22none%22 stroke=%22white%22%3E%3Cpath d=%22M0 .5H31.5V32%22/%3E%3C/svg%3E');"></div>--}}
        </div>

        <!-- Animated Orbs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[20%] left-[15%] w-3 h-3 bg-pink-400/60 rounded-full blur-sm animate-float-slow"></div>
            <div class="absolute top-[30%] right-[20%] w-2 h-2 bg-cyan-400/50 rounded-full blur-sm animate-float-medium"></div>
            <div class="absolute bottom-[35%] left-[40%] w-2.5 h-2.5 bg-rose-300/50 rounded-full blur-sm animate-float-fast"></div>
            <div class="absolute top-[50%] right-[30%] w-1.5 h-1.5 bg-fuchsia-400/40 rounded-full blur-sm animate-float-slow"></div>
            <div class="absolute bottom-[25%] right-[15%] w-2 h-2 bg-orange-300/40 rounded-full blur-sm animate-float-medium"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center pt-20 pb-16">
            <!-- Badge with Glow -->
            <div class="inline-flex items-center gap-2.5 py-2.5 px-5 rounded-full bg-white/10 backdrop-blur-xl border border-white/20 text-white text-sm font-medium mb-10 animate-fade-in-up shadow-lg shadow-pink-500/10">
                <span class="flex h-2.5 w-2.5 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-gradient-to-r from-pink-400 to-rose-400"></span>
                </span>
                <span class="bg-gradient-to-r from-white to-pink-100 bg-clip-text text-transparent font-semibold">새로운 여행의 시작</span>
            </div>

            <!-- Main Heading - Modern Typography -->
            <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-bold text-white mb-8 tracking-tight leading-[1.05] animate-fade-in-up animation-delay-100">
                <span class="block">여행의</span>
                <span class="relative inline-block mt-2">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-300 via-rose-200 to-orange-200 animate-gradient-text">특별한 순간</span>
                    <span class="absolute -bottom-2 left-0 right-0 h-1 bg-gradient-to-r from-pink-500 via-rose-400 to-orange-400 rounded-full opacity-60 blur-sm"></span>
                </span>
            </h1>

            <!-- Subheading -->
            <p class="text-xl sm:text-2xl md:text-3xl text-white/70 mb-14 max-w-3xl mx-auto font-light leading-relaxed animate-fade-in-up animation-delay-200">
                현지 가이드와 함께하는 <span class="text-white/90 font-normal">프리미엄 투어</span> & <span class="text-white/90 font-normal">액티비티</span>
            </p>

            <!-- Search Widget - Modern Glass Card -->
            <div class="w-full max-w-4xl mx-auto animate-fade-in-up animation-delay-300 relative z-30">
                <!-- Glow Effect Behind Card -->
                <div class="absolute -inset-4 bg-gradient-to-r from-pink-500/20 via-rose-500/20 to-orange-500/20 rounded-3xl blur-2xl opacity-60"></div>

                <div class="relative bg-white/95 backdrop-blur-2xl rounded-2xl shadow-2xl shadow-black/20 p-2.5 ring-1 ring-white/30"
                     x-data="{
                         destination: '',
                         date: '',
                         guests: 1,
                         showDestination: false,
                         showDate: false,
                         showGuests: false
                     }">
                    <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET">
                        <div class="flex flex-col md:flex-row md:items-center gap-1">

                            <!-- Location Input -->
                            <div class="relative flex-1 group">
                                <button type="button"
                                        @click="showDestination = !showDestination; showDate = false; showGuests = false"
                                        class="w-full flex items-center gap-3 h-16 px-5 rounded-xl hover:bg-rose-50/50 transition-all duration-200 cursor-pointer text-left">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-pink-100 to-rose-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="text-[10px] font-bold text-rose-400 uppercase tracking-wider">{{ __('home.where_to') }}</span>
                                        <span class="text-sm font-medium truncate" :class="destination ? 'text-gray-900' : 'text-gray-400'" x-text="destination || '{{ __('home.search_placeholder') }}'"></span>
                                    </div>
                                </button>
                                <!-- Destination Dropdown -->
                                <div x-show="showDestination"
                                     @click.away="showDestination = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-2"
                                     class="absolute top-full left-0 mt-2 w-full md:w-80 bg-white rounded-xl shadow-2xl border border-gray-100 py-3 z-50"
                                     style="display: none;">
                                    <div class="px-4 pb-3">
                                        <input type="text"
                                               name="search"
                                               x-model="destination"
                                               placeholder="{{ __('home.search_placeholder') }}"
                                               class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                               @keydown.enter="showDestination = false">
                                    </div>
                                    <div class="border-t border-gray-100 pt-2">
                                        <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">인기 여행지</p>
                                        @php
                                            $searchRegions = [
                                                ['name' => '서울', 'icon' => 'from-pink-100 to-rose-100', 'iconColor' => 'text-rose-500'],
                                                ['name' => '부산', 'icon' => 'from-blue-100 to-cyan-100', 'iconColor' => 'text-cyan-500'],
                                                ['name' => '제주', 'icon' => 'from-orange-100 to-amber-100', 'iconColor' => 'text-orange-500'],
                                                ['name' => '경기', 'icon' => 'from-green-100 to-emerald-100', 'iconColor' => 'text-emerald-500'],
                                                ['name' => '강원', 'icon' => 'from-purple-100 to-violet-100', 'iconColor' => 'text-violet-500'],
                                            ];
                                        @endphp
                                        @foreach($searchRegions as $searchRegion)
                                        <button type="button"
                                                @click="destination = '{{ $searchRegion['name'] }}'; showDestination = false"
                                                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <span class="w-10 h-10 rounded-lg bg-gradient-to-br {{ $searchRegion['icon'] }} flex items-center justify-center">
                                                <svg class="w-5 h-5 {{ $searchRegion['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                                </svg>
                                            </span>
                                            <span class="font-medium">{{ $searchRegion['name'] }}</span>
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="hidden md:block w-px h-10 bg-gradient-to-b from-transparent via-gray-200 to-transparent"></div>
                            <div class="md:hidden h-px bg-gray-100 mx-4"></div>

                            <!-- Date Input -->
                            <div class="relative md:w-[28%] group">
                                <button type="button"
                                        @click="showDate = !showDate; showDestination = false; showGuests = false"
                                        class="w-full flex items-center gap-3 h-16 px-5 rounded-xl hover:bg-rose-50/50 transition-all duration-200 cursor-pointer text-left">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-100 to-teal-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">{{ __('home.add_dates') }}</span>
                                        <span class="text-sm font-medium truncate" :class="date ? 'text-gray-900' : 'text-gray-400'" x-text="date || '날짜 선택'"></span>
                                    </div>
                                </button>
                                <!-- Date Dropdown -->
                                <div x-show="showDate"
                                     @click.away="showDate = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-2"
                                     class="absolute top-full left-1/2 -translate-x-1/2 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 p-4 z-50"
                                     style="display: none;">
                                    <input type="date"
                                           name="date"
                                           x-model="date"
                                           @change="showDate = false"
                                           class="px-4 py-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                           min="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="hidden md:block w-px h-10 bg-gradient-to-b from-transparent via-gray-200 to-transparent"></div>
                            <div class="md:hidden h-px bg-gray-100 mx-4"></div>

                            <!-- Guests + Button -->
                            <div class="relative md:w-[32%] flex items-center gap-2 pr-1">
                                <button type="button"
                                        @click="showGuests = !showGuests; showDestination = false; showDate = false"
                                        class="flex items-center gap-3 h-16 px-5 rounded-xl hover:bg-rose-50/50 transition-all duration-200 cursor-pointer flex-1 min-w-0 text-left">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span class="text-[10px] font-bold text-orange-400 uppercase tracking-wider">{{ __('home.travelers') }}</span>
                                        <span class="text-sm font-medium" :class="guests > 1 ? 'text-gray-900' : 'text-gray-400'" x-text="guests > 1 ? guests + '{{ __('home.guests_count') }}' : '{{ __('home.add_guests') }}'"></span>
                                    </div>
                                </button>
                                <!-- Guests Dropdown -->
                                <div x-show="showGuests"
                                     @click.away="showGuests = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-2"
                                     class="absolute top-full right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-100 p-5 z-50"
                                     style="display: none;">
                                    <input type="hidden" name="guests" :value="guests">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900">{{ __('home.travelers') }}</span>
                                            <p class="text-xs text-gray-500 mt-0.5">만 13세 이상</p>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <button type="button"
                                                    @click="guests = Math.max(1, guests - 1)"
                                                    class="w-9 h-9 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:border-gray-400 hover:text-gray-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                                                    :disabled="guests <= 1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                </svg>
                                            </button>
                                            <span class="text-base font-semibold w-6 text-center" x-text="guests"></span>
                                            <button type="button"
                                                    @click="guests = Math.min(20, guests + 1)"
                                                    class="w-9 h-9 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:border-gray-400 hover:text-gray-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Search Button (Desktop) -->
                                <button type="submit"
                                        class="hidden md:flex w-14 h-14 rounded-xl bg-gradient-to-r from-pink-500 via-rose-500 to-pink-500 bg-[length:200%_auto] hover:bg-right text-white items-center justify-center shadow-lg shadow-pink-500/30 hover:shadow-xl hover:shadow-pink-500/40 transition-all duration-500 hover:scale-105 active:scale-95 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Mobile Search Button -->
                            <div class="md:hidden p-2">
                                <button type="submit"
                                        class="w-full py-4 bg-gradient-to-r from-pink-500 via-rose-500 to-pink-500 bg-[length:200%_auto] hover:bg-right text-white font-semibold rounded-xl shadow-lg shadow-pink-500/30 transition-all duration-500 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    검색하기
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Quick suggestions - Pill Style -->
                <div class="flex flex-wrap justify-center gap-2.5 mt-8 animate-fade-in-up animation-delay-400">
                    @php $quickTags = ['서울', '부산', '제주도', '경주', '전주']; @endphp
                    @foreach($quickTags as $index => $tag)
                    <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'location' => $tag]) }}"
                       class="group px-5 py-2.5 rounded-full bg-white/10 backdrop-blur-xl border border-white/20 text-white text-sm font-medium hover:bg-white/20 hover:border-white/40 transition-all duration-300 cursor-pointer flex items-center gap-2 hover:scale-105">
                        <svg class="w-3.5 h-3.5 text-pink-300 group-hover:text-pink-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        {{ $tag }}
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Stats Row -->
            <div class="flex flex-wrap justify-center gap-8 md:gap-16 mt-16 animate-fade-in-up animation-delay-500">
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-white mb-1">500+</div>
                    <div class="text-sm text-white/60">투어 상품</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-white mb-1">10K+</div>
                    <div class="text-sm text-white/60">만족 고객</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-white mb-1">4.9</div>
                    <div class="text-sm text-white/60">평균 평점</div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 rounded-full border-2 border-white/30 flex items-start justify-center p-2">
                <div class="w-1 h-2 bg-white/60 rounded-full animate-scroll-indicator"></div>
            </div>
        </div>
    </section>

    <!-- Category Navigation - Modern Glass Style -->
    <section class="sticky top-16 lg:top-[72px] z-30 bg-white/80 backdrop-blur-xl border-b border-gray-100/50 transition-all duration-300" id="category-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-2 sm:gap-3 overflow-x-auto scrollbar-hide py-4 -mx-4 px-4 sm:mx-0 sm:px-0">
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
                   class="group flex items-center gap-2 px-4 py-2.5 rounded-full border transition-all duration-300 cursor-pointer flex-shrink-0
                          {{ isset($category['active']) && $category['active']
                              ? 'bg-gradient-to-r from-pink-500 to-rose-500 border-transparent text-white shadow-lg shadow-pink-500/25'
                              : 'bg-white border-gray-200 text-gray-700 hover:border-pink-200 hover:bg-pink-50/50 hover:text-pink-600' }}">
                    <svg class="w-4 h-4 transition-colors {{ isset($category['active']) && $category['active'] ? 'text-white' : 'text-gray-400 group-hover:text-pink-500' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $category['icon'] }}" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap">{{ $category['name'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Recommended Products - Enhanced -->
    <section class="py-20 sm:py-28 bg-gradient-to-b from-white via-rose-50/30 to-white relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-pink-100/50 to-transparent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-tr from-cyan-100/40 to-transparent rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-6 mb-12">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-pink-500/10 to-rose-500/10 text-pink-600 text-xs font-bold uppercase tracking-wider mb-4 border border-pink-200/50">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        이달의 추천
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight">
                        특별한 체험을<br class="sm:hidden" /> 만나보세요
                    </h2>
                    <p class="text-gray-500 mt-3 text-lg">여행자들이 가장 만족한 프리미엄 경험</p>
                </div>
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                   class="hidden sm:inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition-all duration-300 group shadow-lg shadow-gray-900/10 hover:shadow-xl hover:shadow-gray-900/20">
                    전체보기
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                @forelse($recommendedProducts as $product)
                    <div class="group">
                        <x-product.card :product="(object) $product" :showWishlist="true" />
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-24 text-gray-400 bg-white rounded-3xl border border-gray-100 shadow-sm">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center mb-5">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <p class="text-gray-600 font-semibold text-lg">등록된 상품이 없습니다</p>
                        <p class="text-gray-400 text-sm mt-1">곧 새로운 체험이 추가됩니다</p>
                    </div>
                @endforelse
            </div>

            <!-- Mobile View All -->
            <div class="sm:hidden mt-10 text-center">
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full bg-gradient-to-r from-pink-500 to-rose-500 text-white text-sm font-semibold shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300">
                    전체 상품 보기
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Regions - Modern Bento Grid -->
    <section class="py-20 sm:py-28 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-14">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-cyan-500/10 to-teal-500/10 text-cyan-600 text-xs font-bold uppercase tracking-wider mb-4 border border-cyan-200/50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    인기 여행지
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight">어디로 떠나볼까요?</h2>
                <p class="text-gray-500 mt-4 text-lg">대한민국 구석구석, 매력적인 도시들을 만나보세요</p>
            </div>

            <!-- Bento Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 auto-rows-[140px] sm:auto-rows-[200px] gap-3 sm:gap-5">
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
                       class="relative group overflow-hidden rounded-2xl sm:rounded-3xl cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500 {{ $gridClasses }}">

                        <!-- Image with Overlay -->
                        <div class="absolute inset-0">
                            <img src="{{ $region['image'] }}"
                                 alt="{{ $region['name'] }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                 loading="lazy"
                                 onerror="this.src='https://placehold.co/600x600/ec4899/white?text={{ urlencode($region['name']) }}'">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/10 group-hover:from-black/70 transition-all duration-500"></div>
                        </div>

                        <!-- Content -->
                        <div class="absolute inset-0 p-5 sm:p-6 flex flex-col justify-end">
                            <div class="transform transition-all duration-300 group-hover:-translate-y-2">
                                <h3 class="text-white font-bold text-xl sm:text-2xl lg:text-3xl mb-2">{{ $region['name'] }}</h3>
                                <div class="flex items-center gap-2 text-white/80 text-sm">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/20 backdrop-blur-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-pink-400"></span>
                                        {{ $region['count'] }}개의 체험
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Hover Arrow -->
                        <div class="absolute top-5 right-5 w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 border border-white/30">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Popular Experiences - Enhanced -->
    <section class="py-20 sm:py-28 bg-gradient-to-b from-gray-50 via-white to-orange-50/30 overflow-hidden relative">
        <!-- Background Decoration -->
        <div class="absolute top-1/2 left-0 w-72 h-72 bg-gradient-to-r from-orange-200/40 to-transparent rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-l from-pink-100/30 to-transparent rounded-full blur-3xl translate-x-1/3 translate-y-1/3"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-6 mb-12">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-orange-500/10 to-amber-500/10 text-orange-600 text-xs font-bold uppercase tracking-wider mb-4 border border-orange-200/50">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" />
                        </svg>
                        실시간 인기
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight">
                        지금 가장 <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-500">HOT</span>한 체험
                    </h2>
                    <p class="text-gray-500 mt-3 text-lg">여행자들이 지금 가장 많이 예약하는 베스트셀러</p>
                </div>

                <!-- Carousel Navigation -->
                <div class="hidden sm:flex items-center gap-3">
                    <button type="button" id="popular-prev"
                            class="w-12 h-12 rounded-full border-2 border-gray-200 bg-white flex items-center justify-center text-gray-600 hover:border-orange-300 hover:bg-orange-50 hover:text-orange-600 transition-all duration-300 disabled:opacity-40 disabled:cursor-not-allowed shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <button type="button" id="popular-next"
                            class="w-12 h-12 rounded-full border-2 border-gray-200 bg-white flex items-center justify-center text-gray-600 hover:border-orange-300 hover:bg-orange-50 hover:text-orange-600 transition-all duration-300 disabled:opacity-40 disabled:cursor-not-allowed shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Swiper Carousel -->
            <div class="swiper popular-swiper pt-6 pb-8 -mx-4 px-4 sm:mx-0 sm:px-0">
                <div class="swiper-wrapper">
                    @forelse($popularProducts as $index => $product)
                        <div class="swiper-slide !w-[280px] sm:!w-[320px]">
                            <div class="relative">
                                @if($index < 3)
                                <!-- Ranking Badge -->
                                <div class="absolute -top-3 -left-1 z-20 w-9 h-9 rounded-full bg-gradient-to-br {{ $index === 0 ? 'from-amber-400 to-orange-500' : ($index === 1 ? 'from-gray-300 to-gray-400' : 'from-orange-300 to-amber-400') }} flex items-center justify-center shadow-lg ring-2 ring-white">
                                    <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                                </div>
                                @endif
                                <x-product.card :product="(object) $product" :showWishlist="true" />
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide !w-full">
                            <div class="w-full flex flex-col items-center justify-center py-20 text-gray-400 bg-white rounded-3xl border border-gray-100 shadow-sm">
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-orange-50 to-amber-50 flex items-center justify-center mb-5">
                                    <svg class="w-10 h-10 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                                    </svg>
                                </div>
                                <p class="text-gray-600 font-semibold text-lg">인기 상품이 없습니다</p>
                                <p class="text-gray-400 text-sm mt-1">곧 새로운 인기 체험이 추가됩니다</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="hidden sm:block mt-8">
                <div class="h-1 bg-gray-100 rounded-full overflow-hidden max-w-md mx-auto">
                    <div id="popular-progress" class="h-full bg-gradient-to-r from-orange-400 to-amber-400 rounded-full transition-all duration-300" style="width: 20%"></div>
                </div>
            </div>

            <!-- Swiper Pagination (Mobile) -->
            <div class="popular-pagination flex justify-center gap-2 mt-8 sm:hidden"></div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20 sm:py-28 bg-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-[0.02]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23000000\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-pink-500/10 to-rose-500/10 text-pink-600 text-xs font-bold uppercase tracking-wider mb-4 border border-pink-200/50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                    WHY CHOOSE US
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight">
                    왜 <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-500">My Travel</span>인가요?
                </h2>
                <p class="text-gray-500 mt-4 text-lg">10,000명 이상의 여행자가 선택한 신뢰할 수 있는 여행 파트너</p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <!-- Feature 1: 검증된 가이드 -->
                <div class="group relative bg-gradient-to-b from-gray-50 to-white rounded-3xl p-8 border border-gray-100 hover:border-pink-200 transition-all duration-500 hover:shadow-xl hover:shadow-pink-500/5 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-50/50 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-100 to-pink-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">검증된 가이드</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-6">엄격한 심사를 통과한 현지 전문 가이드가 함께합니다</p>
                        <div class="pt-6 border-t border-gray-100">
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-bold text-pink-500">500+</span>
                                <span class="text-sm text-gray-400">등록 가이드</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feature 2: 안전한 결제 -->
                <div class="group relative bg-gradient-to-b from-gray-50 to-white rounded-3xl p-8 border border-gray-100 hover:border-cyan-200 transition-all duration-500 hover:shadow-xl hover:shadow-cyan-500/5 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50/50 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-100 to-cyan-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">안전한 결제</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-6">100% 안전한 결제 시스템과 투명한 가격 정책</p>
                        <div class="pt-6 border-t border-gray-100">
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-bold text-cyan-500">100%</span>
                                <span class="text-sm text-gray-400">환불 보장</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feature 3: 24시간 지원 -->
                <div class="group relative bg-gradient-to-b from-gray-50 to-white rounded-3xl p-8 border border-gray-100 hover:border-orange-200 transition-all duration-500 hover:shadow-xl hover:shadow-orange-500/5 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-50/50 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-100 to-orange-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">24시간 지원</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-6">언제 어디서든 도움이 필요하면 즉시 연결됩니다</p>
                        <div class="pt-6 border-t border-gray-100">
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-bold text-orange-500">24/7</span>
                                <span class="text-sm text-gray-400">고객 지원</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feature 4: 높은 만족도 -->
                <div class="group relative bg-gradient-to-b from-gray-50 to-white rounded-3xl p-8 border border-gray-100 hover:border-amber-200 transition-all duration-500 hover:shadow-xl hover:shadow-amber-500/5 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-50/50 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-100 to-amber-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">높은 만족도</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-6">실제 여행자들의 생생한 후기로 검증된 품질</p>
                        <div class="pt-6 border-t border-gray-100">
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-bold text-amber-500">4.9</span>
                                <span class="text-sm text-gray-400">평균 평점</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 sm:py-28 bg-gradient-to-b from-rose-50/50 via-pink-50/30 to-white relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute top-0 left-1/2 w-[800px] h-[800px] bg-gradient-to-br from-pink-200/20 to-transparent rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-pink-600 text-xs font-bold uppercase tracking-wider mb-4 border border-pink-200/50 shadow-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    TESTIMONIALS
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight">
                    여행자들의 <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-500">생생한 후기</span>
                </h2>
                <p class="text-gray-500 mt-4 text-lg">실제 여행자들이 직접 경험하고 남긴 솔직한 이야기</p>
            </div>

            <!-- Testimonials Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @php
                    $testimonials = [
                        [
                            'name' => '김지현',
                            'avatar' => 'JH',
                            'location' => '서울',
                            'rating' => 5,
                            'product' => '제주 자연 힐링 투어',
                            'comment' => '가이드님이 정말 친절하시고 숨겨진 명소들을 많이 알려주셔서 너무 좋았어요! 사진도 예쁘게 찍어주시고, 현지인만 아는 맛집도 소개해 주셨어요.',
                            'date' => '2주 전'
                        ],
                        [
                            'name' => '이승민',
                            'avatar' => 'SM',
                            'location' => '부산',
                            'rating' => 5,
                            'product' => '경주 역사 문화 투어',
                            'comment' => '역사에 대한 해박한 지식과 재미있는 설명 덕분에 지루할 틈이 없었습니다. 아이들도 너무 재미있어 했어요. 가족 여행으로 강력 추천합니다!',
                            'date' => '1달 전'
                        ],
                        [
                            'name' => '박소연',
                            'avatar' => 'SY',
                            'location' => '인천',
                            'rating' => 5,
                            'product' => '전주 한옥마을 & 맛집 투어',
                            'comment' => '한복 체험부터 전통 음식까지, 한국의 멋을 제대로 느낄 수 있었어요. 외국인 친구와 함께 갔는데 정말 만족스러웠습니다!',
                            'date' => '3주 전'
                        ],
                    ];
                @endphp

                @foreach($testimonials as $index => $testimonial)
                <div class="group bg-white rounded-3xl p-8 shadow-lg shadow-gray-900/5 border border-gray-100 hover:shadow-xl hover:shadow-pink-500/10 hover:border-pink-100 transition-all duration-500 hover:-translate-y-1 {{ $index === 1 ? 'lg:-translate-y-4' : '' }}">
                    <!-- Quote Icon -->
                    <div class="mb-6">
                        <svg class="w-10 h-10 text-pink-200" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                        </svg>
                    </div>

                    <!-- Rating -->
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < $testimonial['rating']; $i++)
                        <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @endfor
                    </div>

                    <!-- Comment -->
                    <p class="text-gray-600 leading-relaxed mb-6">"{{ $testimonial['comment'] }}"</p>

                    <!-- Product Tag -->
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-pink-50 text-pink-600 text-xs font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            {{ $testimonial['product'] }}
                        </span>
                    </div>

                    <!-- Author -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center text-white font-bold text-sm">
                                {{ $testimonial['avatar'] }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $testimonial['name'] }}</div>
                                <div class="text-sm text-gray-400">{{ $testimonial['location'] }}</div>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400">{{ $testimonial['date'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- View All Reviews Button -->
            <div class="text-center mt-12">
                <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'sort' => 'rating']) }}"
                   class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-white text-gray-700 font-semibold shadow-lg shadow-gray-900/5 border border-gray-200 hover:border-pink-200 hover:text-pink-600 hover:shadow-xl hover:shadow-pink-500/10 transition-all duration-300 group">
                    더 많은 후기 보기
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 sm:py-28 relative overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-rose-950 via-pink-950 to-fuchsia-950"></div>

        <!-- Aurora Effects -->
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-[60%] h-[60%] bg-gradient-to-br from-pink-500/30 to-transparent rounded-full blur-[100px] animate-aurora-1"></div>
            <div class="absolute bottom-0 right-0 w-[50%] h-[50%] bg-gradient-to-tl from-fuchsia-500/25 to-transparent rounded-full blur-[80px] animate-aurora-2"></div>
            <div class="absolute top-1/2 left-1/2 w-[40%] h-[40%] bg-gradient-to-r from-cyan-400/20 to-transparent rounded-full blur-[60px] -translate-x-1/2 -translate-y-1/2"></div>
        </div>

        <!-- Grid Pattern -->

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2.5 py-2.5 px-5 rounded-full bg-white/10 backdrop-blur-xl border border-white/20 text-white text-sm font-medium mb-8">
                <span class="flex h-2.5 w-2.5 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-gradient-to-r from-pink-400 to-rose-400"></span>
                </span>ㅇ
                <span class="text-white/90">지금 바로 시작하세요</span>
            </div>

            <!-- Heading -->
            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight mb-6">
                특별한 여행을<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-300 via-rose-200 to-orange-200">시작할 준비 되셨나요?</span>
            </h2>

            <!-- Subheading -->
            <p class="text-xl text-white/60 max-w-2xl mx-auto mb-10">
                현지 전문 가이드와 함께하는 잊을 수 없는 여행 경험.<br class="hidden sm:block">
                지금 My Travel과 함께 떠나세요.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full bg-white text-gray-900 font-semibold shadow-xl shadow-black/20 hover:shadow-2xl hover:shadow-black/30 transition-all duration-300 hover:scale-105 group">
                    투어 둘러보기
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                <a href="{{ route('register') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full bg-white/10 backdrop-blur-xl border border-white/20 text-white font-semibold hover:bg-white/20 transition-all duration-300 group">
                    무료 회원가입
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                </a>
            </div>

            <!-- Trust Badges -->
            <div class="flex flex-wrap items-center justify-center gap-8 mt-16 pt-10 border-t border-white/10">
                <div class="flex items-center gap-3 text-white/60">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    <span class="text-sm">안전한 결제</span>
                </div>
                <div class="flex items-center gap-3 text-white/60">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">24시간 지원</span>
                </div>
                <div class="flex items-center gap-3 text-white/60">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">100% 환불 보장</span>
                </div>
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
        @keyframes aurora-4 {
            0%, 100% { transform: translate(0, 0) scale(1) rotate(0deg); }
            25% { transform: translate(20px, -30px) scale(1.05) rotate(3deg); }
            75% { transform: translate(-15px, 25px) scale(0.95) rotate(-3deg); }
        }
        .animate-aurora-1 { animation: aurora-1 20s ease-in-out infinite; }
        .animate-aurora-2 { animation: aurora-2 25s ease-in-out infinite; }
        .animate-aurora-3 { animation: aurora-3 15s ease-in-out infinite; }
        .animate-aurora-4 { animation: aurora-4 18s ease-in-out infinite; }

        /* Scroll Indicator Animation */
        @keyframes scroll-indicator {
            0%, 100% { transform: translateY(0); opacity: 1; }
            50% { transform: translateY(4px); opacity: 0.5; }
        }
        .animate-scroll-indicator { animation: scroll-indicator 1.5s ease-in-out infinite; }

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
        .animation-delay-500 { animation-delay: 0.5s; }

        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            .animate-aurora-1,
            .animate-aurora-2,
            .animate-aurora-3,
            .animate-aurora-4,
            .animate-float-slow,
            .animate-float-medium,
            .animate-float-fast,
            .animate-gradient-text,
            .animate-fade-in-up,
            .animate-scroll-indicator {
                animation: none;
            }
            .animate-fade-in-up {
                opacity: 1;
                transform: none;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        /* Swiper custom styles - override default overflow:hidden */
        .popular-swiper,
        .popular-swiper .swiper-wrapper,
        .popular-swiper .swiper-slide {
            overflow: visible !important;
        }
        .popular-swiper .swiper-slide {
            height: auto;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Wishlist Toggle
        function toggleWishlist(productId, button) {
            const icon = button.querySelector('.wishlist-icon');
            // Optimistic UI update
            button.classList.add('pointer-events-none');

            api.wishlist.toggle(productId)
                .then(data => {
                    if (data.success) {
                        const nowWishlisted = data.data?.added;
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

        // Popular Products Swiper
        document.addEventListener('DOMContentLoaded', function() {
            const progressBar = document.getElementById('popular-progress');

            const popularSwiper = new Swiper('.popular-swiper', {
                slidesPerView: 'auto',
                spaceBetween: 24,
                freeMode: true,
                grabCursor: true,
                navigation: {
                    nextEl: '#popular-next',
                    prevEl: '#popular-prev',
                },
                pagination: {
                    el: '.popular-pagination',
                    clickable: true,
                    bulletClass: 'w-2 h-2 rounded-full bg-gray-300 cursor-pointer transition-all duration-300',
                    bulletActiveClass: '!bg-orange-500 !w-6',
                },
                on: {
                    progress: function(swiper, progress) {
                        if (progressBar) {
                            const percentage = Math.max(20, Math.min(100, progress * 80 + 20));
                            progressBar.style.width = percentage + '%';
                        }
                    },
                    slideChange: function(swiper) {
                        const prevBtn = document.getElementById('popular-prev');
                        const nextBtn = document.getElementById('popular-next');
                        if (prevBtn) prevBtn.disabled = swiper.isBeginning;
                        if (nextBtn) nextBtn.disabled = swiper.isEnd;
                    },
                },
            });

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
