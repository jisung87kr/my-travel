<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-lg border-t border-gray-100 shadow-lg z-40"
     role="navigation"
     aria-label="Mobile navigation">
    <div class="grid grid-cols-5 h-16 max-w-lg mx-auto">
        <!-- Home -->
        <a href="{{ route('home') }}"
           class="flex flex-col items-center justify-center gap-0.5 transition-colors cursor-pointer {{ request()->routeIs('home') ? 'text-pink-600' : 'text-gray-500 hover:text-pink-600' }}"
           aria-label="Home"
           aria-current="{{ request()->routeIs('home') ? 'page' : 'false' }}">
            <div class="relative">
                @if(request()->routeIs('home'))
                    <div class="absolute -inset-1 bg-pink-100 rounded-lg"></div>
                @endif
                <svg class="w-6 h-6 relative" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </div>
            <span class="text-[10px] font-medium">{{ __('nav.home') }}</span>
        </a>

        <!-- Search -->
        <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
           class="flex flex-col items-center justify-center gap-0.5 transition-colors cursor-pointer {{ request()->routeIs('products.*') ? 'text-pink-600' : 'text-gray-500 hover:text-pink-600' }}"
           aria-label="Search">
            <div class="relative">
                @if(request()->routeIs('products.*'))
                    <div class="absolute -inset-1 bg-pink-100 rounded-lg"></div>
                @endif
                <svg class="w-6 h-6 relative" fill="{{ request()->routeIs('products.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <span class="text-[10px] font-medium">{{ __('nav.search') }}</span>
        </a>

        <!-- Wishlist -->
        @auth
            <a href="{{ route('my.wishlist', ['locale' => app()->getLocale()]) }}"
               class="flex flex-col items-center justify-center gap-0.5 transition-colors cursor-pointer {{ request()->routeIs('my.wishlist') ? 'text-pink-600' : 'text-gray-500 hover:text-pink-600' }}"
               aria-label="Wishlist">
                <div class="relative">
                    @if(request()->routeIs('my.wishlist'))
                        <div class="absolute -inset-1 bg-pink-100 rounded-lg"></div>
                    @endif
                    <svg class="w-6 h-6 relative" fill="{{ request()->routeIs('my.wishlist') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                    @if(isset($wishlistCount) && $wishlistCount > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-pink-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                            {{ $wishlistCount > 9 ? '9+' : $wishlistCount }}
                        </span>
                    @endif
                </div>
                <span class="text-[10px] font-medium">{{ __('nav.wishlist') }}</span>
            </a>
        @else
            <a href="{{ route('login') }}"
               class="flex flex-col items-center justify-center gap-0.5 transition-colors cursor-pointer text-gray-500 hover:text-pink-600"
               aria-label="Wishlist">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
                <span class="text-[10px] font-medium">{{ __('nav.wishlist') }}</span>
            </a>
        @endauth

        <!-- Bookings -->
        @auth
            <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
               class="flex flex-col items-center justify-center gap-0.5 transition-colors cursor-pointer {{ request()->routeIs('my.bookings*') ? 'text-pink-600' : 'text-gray-500 hover:text-pink-600' }}"
               aria-label="Bookings">
                <div class="relative">
                    @if(request()->routeIs('my.bookings*'))
                        <div class="absolute -inset-1 bg-pink-100 rounded-lg"></div>
                    @endif
                    <svg class="w-6 h-6 relative" fill="{{ request()->routeIs('my.bookings*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                    @if(isset($pendingBookingsCount) && $pendingBookingsCount > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-amber-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                            {{ $pendingBookingsCount > 9 ? '9+' : $pendingBookingsCount }}
                        </span>
                    @endif
                </div>
                <span class="text-[10px] font-medium">{{ __('nav.bookings') }}</span>
            </a>
        @else
            <a href="{{ route('login') }}"
               class="flex flex-col items-center justify-center gap-0.5 transition-colors cursor-pointer text-gray-500 hover:text-pink-600"
               aria-label="Bookings">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
                <span class="text-[10px] font-medium">{{ __('nav.bookings') }}</span>
            </a>
        @endauth

        <!-- Profile -->
        @auth
            <a href="{{ route('my.profile', ['locale' => app()->getLocale()]) }}"
               class="flex flex-col items-center justify-center gap-0.5 transition-colors cursor-pointer {{ request()->routeIs('my.profile') ? 'text-pink-600' : 'text-gray-500 hover:text-pink-600' }}"
               aria-label="Profile">
                <div class="relative">
                    @if(request()->routeIs('my.profile'))
                        <div class="absolute -inset-1 bg-pink-100 rounded-lg"></div>
                    @endif
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                             alt="{{ auth()->user()->name }}"
                             class="w-6 h-6 rounded-full object-cover relative {{ request()->routeIs('my.profile') ? 'ring-2 ring-pink-500 ring-offset-1' : '' }}">
                    @else
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center relative {{ request()->routeIs('my.profile') ? 'ring-2 ring-pink-500 ring-offset-1' : '' }}">
                            <span class="text-[10px] font-semibold text-white">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                <span class="text-[10px] font-medium">{{ __('nav.profile') }}</span>
            </a>
        @else
            <a href="{{ route('login') }}"
               class="flex flex-col items-center justify-center gap-0.5 transition-colors cursor-pointer text-gray-500 hover:text-pink-600"
               aria-label="Profile">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <span class="text-[10px] font-medium">{{ __('nav.profile') }}</span>
            </a>
        @endauth
    </div>
</nav>
