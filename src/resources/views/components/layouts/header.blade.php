<header x-data="{ scrolled: false, mobileMenuOpen: false }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
        :class="scrolled ? 'bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-100' : 'bg-white/80 backdrop-blur-md'"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-18">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group" aria-label="Home">
                    <!-- Modern Logo Icon -->
                    <div class="relative">
                        <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shadow-lg shadow-pink-500/25 group-hover:shadow-pink-500/40 transition-shadow">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-lg lg:text-xl font-bold text-gray-900">
                        My Travel
                    </span>
                </a>
            </div>

            <!-- Desktop Search Bar -->
            <div class="hidden lg:flex flex-1 max-w-2xl mx-8"
                 x-data="{
                     destination: '',
                     date: '',
                     guests: 1,
                     showDestination: false,
                     showDate: false,
                     showGuests: false
                 }">
                <div class="w-full relative transition-all duration-300"
                     x-show="scrolled"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="display: none;">
                    <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET"
                          class="flex items-center bg-white border border-gray-200 hover:border-gray-300 rounded-full transition-all duration-200 shadow-sm hover:shadow-md">
                        <!-- Destination -->
                        <div class="relative flex-1 min-w-0">
                            <button type="button"
                                    @click="showDestination = !showDestination; showDate = false; showGuests = false"
                                    class="w-full flex items-center gap-2 px-4 py-2 text-left rounded-l-full hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                <span class="text-sm truncate" :class="destination ? 'text-gray-900 font-medium' : 'text-gray-500'" x-text="destination || '{{ __('home.where_to') }}'"></span>
                            </button>
                            <!-- Destination Dropdown -->
                            <div x-show="showDestination"
                                 @click.away="showDestination = false"
                                 x-transition
                                 class="absolute top-full left-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                                <input type="text"
                                       name="search"
                                       x-model="destination"
                                       placeholder="{{ __('home.search_placeholder') }}"
                                       class="w-full px-4 py-2 text-sm border-b border-gray-100 focus:outline-none"
                                       @keydown.enter="showDestination = false">
                                <div class="py-1">
                                    @php
                                        $regions = [
                                            'seoul' => '서울',
                                            'busan' => '부산',
                                            'jeju' => '제주',
                                            'gyeonggi' => '경기',
                                        ];
                                    @endphp
                                    @foreach($regions as $code => $name)
                                    <button type="button"
                                            @click="destination = '{{ $name }}'; showDestination = false"
                                            class="w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                            </svg>
                                        </span>
                                        {{ $name }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="w-px h-6 bg-gray-200"></div>

                        <!-- Date -->
                        <div class="relative flex-1 min-w-0">
                            <button type="button"
                                    @click="showDate = !showDate; showDestination = false; showGuests = false"
                                    class="w-full flex items-center gap-2 px-4 py-2 text-left hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <span class="text-sm truncate" :class="date ? 'text-gray-900 font-medium' : 'text-gray-500'" x-text="date || '{{ __('home.add_dates') }}'"></span>
                            </button>
                            <!-- Date Dropdown -->
                            <div x-show="showDate"
                                 @click.away="showDate = false"
                                 x-transition
                                 class="absolute top-full left-1/2 -translate-x-1/2 mt-2 bg-white rounded-xl shadow-xl border border-gray-100 p-4 z-50">
                                <input type="date"
                                       name="date"
                                       x-model="date"
                                       @change="showDate = false"
                                       class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                       min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="w-px h-6 bg-gray-200"></div>

                        <!-- Guests -->
                        <div class="relative flex-shrink-0">
                            <button type="button"
                                    @click="showGuests = !showGuests; showDestination = false; showDate = false"
                                    class="flex items-center gap-2 px-4 py-2 text-left hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <span class="text-sm whitespace-nowrap" :class="guests > 1 ? 'text-gray-900 font-medium' : 'text-gray-500'" x-text="guests > 1 ? guests + '{{ __('home.guests_count') }}' : '{{ __('home.add_guests') }}'"></span>
                            </button>
                            <!-- Guests Dropdown -->
                            <div x-show="showGuests"
                                 @click.away="showGuests = false"
                                 x-transition
                                 class="absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 p-4 z-50">
                                <input type="hidden" name="guests" :value="guests">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700">{{ __('home.travelers') }}</span>
                                    <div class="flex items-center gap-3">
                                        <button type="button"
                                                @click="guests = Math.max(1, guests - 1)"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:border-gray-400 transition-colors disabled:opacity-50"
                                                :disabled="guests <= 1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                            </svg>
                                        </button>
                                        <span class="text-sm font-medium w-6 text-center" x-text="guests"></span>
                                        <button type="button"
                                                @click="guests = Math.min(20, guests + 1)"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:border-gray-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Search Button -->
                        <button type="submit"
                                class="m-1.5 p-2.5 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 rounded-full text-white shadow-md hover:shadow-lg transition-all duration-200"
                                aria-label="Search">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Side Navigation -->
            <div class="flex items-center gap-1 sm:gap-2">
                <!-- Language Selector -->
                <div x-data="{ open: false }" class="relative hidden md:block">
                    <button @click="open = !open"
                            type="button"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-full transition-colors text-gray-700 hover:bg-gray-100"
                            :aria-expanded="open"
                            aria-label="Select language">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        <span class="text-sm font-medium">{{ strtoupper(app()->getLocale()) }}</span>
                    </button>

                    <!-- Language Dropdown -->
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-xl border border-gray-100 py-1 overflow-hidden"
                         role="menu"
                         style="display: none;">
                        @php
                            $languages = [
                                'en' => 'English',
                                'ko' => '한국어',
                                'ja' => '日本語',
                                'zh' => '中文'
                            ];
                        @endphp
                        @foreach($languages as $code => $name)
                        <a href="{{ route('locale.switch', ['locale' => $code]) }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors {{ app()->getLocale() === $code ? 'bg-pink-50 text-pink-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}"
                           role="menuitem">
                            @if(app()->getLocale() === $code)
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            @else
                            <span class="w-4"></span>
                            @endif
                            {{ $name }}
                        </a>
                        @endforeach
                    </div>
                </div>

                @auth
                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                type="button"
                                class="flex items-center gap-2 p-1.5 pr-3 rounded-full border border-gray-200 hover:border-gray-300 hover:shadow-md bg-white transition-all"
                                :aria-expanded="open"
                                aria-label="User menu">
                            <!-- Menu Icon -->
                            <div class="p-1">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </div>
                            <!-- Avatar -->
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center ring-2 ring-white">
                                <span class="text-sm font-semibold text-white">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        </button>

                        <!-- User Dropdown Menu -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-xl border border-gray-100 py-2 overflow-hidden"
                             role="menu"
                             style="display: none;">
                            <!-- User Info -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->email }}</p>
                            </div>

                            <div class="py-1">
                                <a href="{{ route('my.profile', ['locale' => app()->getLocale()]) }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                   role="menuitem">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                    {{ __('nav.profile') }}
                                </a>
                                <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                   role="menuitem">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    {{ __('nav.my_bookings') }}
                                </a>
                                <a href="{{ route('my.wishlist', ['locale' => app()->getLocale()]) }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                   role="menuitem">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                    {{ __('nav.wishlist') }}
                                </a>
                            </div>

                            <div class="border-t border-gray-100 py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                            role="menuitem">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                        </svg>
                                        {{ __('nav.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Login/Register Buttons -->
                    <a href="{{ route('login') }}"
                       class="hidden md:inline-flex items-center px-4 py-2 text-sm font-medium rounded-full transition-colors text-gray-700 hover:bg-gray-100">
                        {{ __('nav.login') }}
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full transition-all bg-gray-900 hover:bg-gray-800 text-white shadow-lg hover:shadow-xl">
                        {{ __('nav.register') }}
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        type="button"
                        class="lg:hidden inline-flex items-center justify-center p-2 rounded-full transition-colors text-gray-700 hover:bg-gray-100"
                        :aria-expanded="mobileMenuOpen"
                        aria-label="Toggle mobile menu">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Panel -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         @click.away="mobileMenuOpen = false"
         class="lg:hidden bg-white border-t border-gray-100 shadow-xl"
         style="display: none;">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-3">
            <!-- Mobile Search -->
            <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
               class="flex items-center gap-3 px-4 py-3.5 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <span class="text-sm font-medium text-gray-900">{{ __('search.search_accommodations') }}</span>
            </a>

            @guest
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a href="{{ route('login') }}"
                       class="flex items-center justify-center px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        {{ __('nav.login') }}
                    </a>
                    <a href="{{ route('register') }}"
                       class="flex items-center justify-center px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 rounded-xl transition-colors shadow-lg">
                        {{ __('nav.register') }}
                    </a>
                </div>
            @endguest

            <!-- Language Selector (Mobile) -->
            <div class="pt-3 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-1">{{ __('nav.language') }}</p>
                <div class="grid grid-cols-4 gap-2">
                    @php
                        $mobileLanguages = [
                            'en' => 'EN',
                            'ko' => '한',
                            'ja' => '日',
                            'zh' => '中'
                        ];
                    @endphp
                    @foreach($mobileLanguages as $code => $label)
                    <a href="{{ route('locale.switch', ['locale' => $code]) }}"
                       class="flex items-center justify-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ app()->getLocale() === $code ? 'bg-pink-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Spacer for fixed header -->
<div class="h-16 lg:h-18" aria-hidden="true"></div>
