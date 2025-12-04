<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'My Travel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <span class="text-xl font-bold text-indigo-600">My Travel</span>
                    </a>

                    <!-- Main Navigation -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-700 hover:text-indigo-600">
                            {{ __('nav.products') }}
                        </a>
                    </div>
                </div>

                <!-- Right Navigation -->
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div id="language-switcher"></div>

                    @auth
                        <a href="{{ route('my.wishlist', ['locale' => app()->getLocale()]) }}"
                           class="text-gray-600 hover:text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </a>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                                <span>{{ auth()->user()->name }}</span>
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('my.profile', ['locale' => app()->getLocale()]) }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('nav.profile') }}
                                </a>
                                <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('nav.my_bookings') }}
                                </a>
                                <a href="{{ route('my.wishlist', ['locale' => app()->getLocale()]) }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('nav.wishlist') }}
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('nav.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm font-medium text-gray-700 hover:text-indigo-600">
                            {{ __('nav.login') }}
                        </a>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            {{ __('nav.register') }}
                        </a>
                    @endauth

                    <!-- Mobile menu button -->
                    <button type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100"
                            x-data @click="$dispatch('toggle-mobile-menu')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden" x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                   class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">
                    {{ __('nav.products') }}
                </a>
                @auth
                    <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
                       class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">
                        {{ __('nav.my_bookings') }}
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase">{{ __('footer.about') }}</h3>
                    <p class="mt-4 text-sm text-gray-600">
                        {{ __('footer.about_text') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase">{{ __('footer.support') }}</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-sm text-gray-600 hover:text-indigo-600">{{ __('footer.faq') }}</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-indigo-600">{{ __('footer.contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase">{{ __('footer.legal') }}</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-sm text-gray-600 hover:text-indigo-600">{{ __('footer.privacy') }}</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-indigo-600">{{ __('footer.terms') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase">{{ __('footer.follow_us') }}</h3>
                    <div class="mt-4 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-indigo-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-indigo-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-400 text-center">
                    &copy; {{ date('Y') }} My Travel. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
