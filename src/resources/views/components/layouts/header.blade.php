<header x-data="{ scrolled: false, mobileMenuOpen: false }"
        @scroll.window="scrolled = window.scrollY > 50"
        :class="{ 'bg-white shadow-md': scrolled, 'bg-transparent': !scrolled }"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center space-x-2" aria-label="Home">
                    <svg class="w-8 h-8 lg:w-10 lg:h-10 text-primary-600" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.86-.95-6.77-4.67-6.99-9h13.98c-.22 4.33-3.13 8.05-6.99 9zm7-11H5V7.3l7-3.5 7 3.5V9z"/>
                    </svg>
                    <span class="text-xl lg:text-2xl font-bold text-primary-600">My Travel</span>
                </a>
            </div>

            <!-- Desktop Search Bar (Hidden on Mobile/Tablet) -->
            <div class="hidden lg:flex flex-1 max-w-2xl mx-8">
                <div class="w-full bg-white rounded-lg shadow-card hover:shadow-card-hover transition-shadow border border-gray-200">
                    <div class="flex items-center divide-x divide-gray-200">
                        <!-- Location Button -->
                        <button type="button"
                                class="flex-1 px-4 py-3 text-left hover:bg-gray-50 rounded-l-lg transition-colors"
                                aria-label="Select location">
                            <div class="text-xs font-semibold text-gray-900">{{ __('search.location') }}</div>
                            <div class="text-sm text-gray-500">{{ __('search.where_to') }}</div>
                        </button>

                        <!-- Date Button -->
                        <button type="button"
                                class="flex-1 px-4 py-3 text-left hover:bg-gray-50 transition-colors"
                                aria-label="Select dates">
                            <div class="text-xs font-semibold text-gray-900">{{ __('search.dates') }}</div>
                            <div class="text-sm text-gray-500">{{ __('search.add_dates') }}</div>
                        </button>

                        <!-- Guests Button -->
                        <button type="button"
                                class="flex-1 px-4 py-3 text-left hover:bg-gray-50 transition-colors"
                                aria-label="Select number of guests">
                            <div class="text-xs font-semibold text-gray-900">{{ __('search.guests') }}</div>
                            <div class="text-sm text-gray-500">{{ __('search.add_guests') }}</div>
                        </button>

                        <!-- Search Button -->
                        <button type="button"
                                class="px-4 py-3 bg-primary-600 hover:bg-primary-700 rounded-r-lg transition-colors"
                                aria-label="Search">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Side Navigation -->
            <div class="flex items-center space-x-3 lg:space-x-4">
                <!-- Language Selector -->
                <div x-data="{ open: false }" class="relative hidden md:block">
                    <button @click="open = !open"
                            type="button"
                            class="flex items-center space-x-1 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"
                            :aria-expanded="open"
                            aria-label="Select language">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">{{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Language Dropdown -->
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-dropdown border border-gray-200 py-1"
                         role="menu"
                         aria-orientation="vertical"
                         style="display: none;">
                        <a href="{{ route('locale.switch', ['locale' => 'en']) }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                           role="menuitem">
                            English
                        </a>
                        <a href="{{ route('locale.switch', ['locale' => 'ko']) }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                           role="menuitem">
                            한국어
                        </a>
                        <a href="{{ route('locale.switch', ['locale' => 'ja']) }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                           role="menuitem">
                            日本語
                        </a>
                        <a href="{{ route('locale.switch', ['locale' => 'zh']) }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                           role="menuitem">
                            中文
                        </a>
                    </div>
                </div>

                <!-- Auth Buttons / User Menu -->
                @auth
                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                type="button"
                                class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"
                                :aria-expanded="open"
                                aria-label="User menu">
                            <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden lg:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- User Dropdown Menu -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-dropdown border border-gray-200 py-1"
                             role="menu"
                             aria-orientation="vertical"
                             style="display: none;">
                            <a href="{{ route('my.profile', ['locale' => app()->getLocale()]) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                               role="menuitem">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ __('nav.profile') }}</span>
                                </div>
                            </a>
                            <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                               role="menuitem">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span>{{ __('nav.my_bookings') }}</span>
                                </div>
                            </a>
                            <a href="{{ route('my.wishlist', ['locale' => app()->getLocale()]) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                               role="menuitem">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span>{{ __('nav.wishlist') }}</span>
                                </div>
                            </a>
                            <hr class="my-1 border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                        role="menuitem">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>{{ __('nav.logout') }}</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Login/Register Buttons -->
                    <a href="{{ route('login') }}"
                       class="hidden md:inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                        {{ __('nav.login') }}
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 transition-colors shadow-sm">
                        {{ __('nav.register') }}
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        type="button"
                        class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors"
                        :aria-expanded="mobileMenuOpen"
                        aria-label="Toggle mobile menu">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
         class="lg:hidden bg-white border-t border-gray-200 shadow-lg"
         style="display: none;">
        <div class="px-4 py-4 space-y-2">
            <!-- Mobile Search Button -->
            <button type="button"
                    class="w-full flex items-center justify-between px-4 py-3 text-left bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                <span class="text-sm font-medium text-gray-900">{{ __('search.search_accommodations') }}</span>
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>

            @guest
                <div class="pt-2 space-y-2">
                    <a href="{{ route('login') }}"
                       class="block px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                        {{ __('nav.login') }}
                    </a>
                    <a href="{{ route('register') }}"
                       class="block px-4 py-3 text-sm font-medium text-center text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors">
                        {{ __('nav.register') }}
                    </a>
                </div>
            @endguest

            <!-- Language Selector (Mobile) -->
            <div class="md:hidden pt-2 border-t border-gray-200">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ __('nav.language') }}</div>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('locale.switch', ['locale' => 'en']) }}"
                       class="px-4 py-2 text-sm text-center text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors {{ app()->getLocale() === 'en' ? 'ring-2 ring-primary-600 bg-primary-50' : '' }}">
                        English
                    </a>
                    <a href="{{ route('locale.switch', ['locale' => 'ko']) }}"
                       class="px-4 py-2 text-sm text-center text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors {{ app()->getLocale() === 'ko' ? 'ring-2 ring-primary-600 bg-primary-50' : '' }}">
                        한국어
                    </a>
                    <a href="{{ route('locale.switch', ['locale' => 'ja']) }}"
                       class="px-4 py-2 text-sm text-center text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors {{ app()->getLocale() === 'ja' ? 'ring-2 ring-primary-600 bg-primary-50' : '' }}">
                        日本語
                    </a>
                    <a href="{{ route('locale.switch', ['locale' => 'zh']) }}"
                       class="px-4 py-2 text-sm text-center text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors {{ app()->getLocale() === 'zh' ? 'ring-2 ring-primary-600 bg-primary-50' : '' }}">
                        中文
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Spacer to prevent content from hiding under fixed header -->
<div class="h-16 lg:h-20" aria-hidden="true"></div>
