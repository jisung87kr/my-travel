<!-- Mobile Bottom Navigation - Only visible on mobile devices -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-40"
     role="navigation"
     aria-label="Mobile navigation">
    <div class="grid grid-cols-5 h-16">
        <!-- Home -->
        <a href="{{ route('home') }}"
           class="flex flex-col items-center justify-center space-y-1 transition-colors {{ request()->routeIs('home') ? 'text-primary-600' : 'text-gray-600 hover:text-primary-600' }}"
           aria-label="Home"
           aria-current="{{ request()->routeIs('home') ? 'page' : 'false' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-xs font-medium">{{ __('nav.home') }}</span>
        </a>

        <!-- Search -->
        <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
           class="flex flex-col items-center justify-center space-y-1 transition-colors {{ request()->routeIs('products.*') ? 'text-primary-600' : 'text-gray-600 hover:text-primary-600' }}"
           aria-label="Search"
           aria-current="{{ request()->routeIs('products.*') ? 'page' : 'false' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('products.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="text-xs font-medium">{{ __('nav.search') }}</span>
        </a>

        <!-- Wishlist -->
        @auth
            <a href="{{ route('my.wishlist', ['locale' => app()->getLocale()]) }}"
               class="flex flex-col items-center justify-center space-y-1 transition-colors {{ request()->routeIs('my.wishlist') ? 'text-primary-600' : 'text-gray-600 hover:text-primary-600' }}"
               aria-label="Wishlist"
               aria-current="{{ request()->routeIs('my.wishlist') ? 'page' : 'false' }}">
                <div class="relative">
                    <svg class="w-6 h-6" fill="{{ request()->routeIs('my.wishlist') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <!-- Badge for wishlist count (optional, can be populated with actual count) -->
                    @if(isset($wishlistCount) && $wishlistCount > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-primary-600 text-white text-xs font-bold rounded-full flex items-center justify-center" aria-label="{{ $wishlistCount }} items in wishlist">
                            {{ $wishlistCount > 9 ? '9+' : $wishlistCount }}
                        </span>
                    @endif
                </div>
                <span class="text-xs font-medium">{{ __('nav.wishlist') }}</span>
            </a>
        @else
            <a href="{{ route('login') }}"
               class="flex flex-col items-center justify-center space-y-1 transition-colors text-gray-600 hover:text-primary-600"
               aria-label="Wishlist - Login required">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span class="text-xs font-medium">{{ __('nav.wishlist') }}</span>
            </a>
        @endauth

        <!-- Bookings -->
        @auth
            <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
               class="flex flex-col items-center justify-center space-y-1 transition-colors {{ request()->routeIs('my.bookings') ? 'text-primary-600' : 'text-gray-600 hover:text-primary-600' }}"
               aria-label="Bookings"
               aria-current="{{ request()->routeIs('my.bookings') ? 'page' : 'false' }}">
                <div class="relative">
                    <svg class="w-6 h-6" fill="{{ request()->routeIs('my.bookings') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <!-- Badge for new bookings (optional) -->
                    @if(isset($newBookingsCount) && $newBookingsCount > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-600 text-white text-xs font-bold rounded-full flex items-center justify-center" aria-label="{{ $newBookingsCount }} new bookings">
                            {{ $newBookingsCount > 9 ? '9+' : $newBookingsCount }}
                        </span>
                    @endif
                </div>
                <span class="text-xs font-medium">{{ __('nav.bookings') }}</span>
            </a>
        @else
            <a href="{{ route('login') }}"
               class="flex flex-col items-center justify-center space-y-1 transition-colors text-gray-600 hover:text-primary-600"
               aria-label="Bookings - Login required">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="text-xs font-medium">{{ __('nav.bookings') }}</span>
            </a>
        @endauth

        <!-- Profile -->
        @auth
            <a href="{{ route('my.profile', ['locale' => app()->getLocale()]) }}"
               class="flex flex-col items-center justify-center space-y-1 transition-colors {{ request()->routeIs('my.profile') ? 'text-primary-600' : 'text-gray-600 hover:text-primary-600' }}"
               aria-label="Profile"
               aria-current="{{ request()->routeIs('my.profile') ? 'page' : 'false' }}">
                <div class="relative">
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                             alt="{{ auth()->user()->name }}"
                             class="w-6 h-6 rounded-full object-cover {{ request()->routeIs('my.profile') ? 'ring-2 ring-primary-600' : '' }}">
                    @else
                        <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center {{ request()->routeIs('my.profile') ? 'ring-2 ring-primary-700' : '' }}">
                            <span class="text-xs font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                <span class="text-xs font-medium">{{ __('nav.profile') }}</span>
            </a>
        @else
            <a href="{{ route('login') }}"
               class="flex flex-col items-center justify-center space-y-1 transition-colors text-gray-600 hover:text-primary-600"
               aria-label="Profile - Login required">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs font-medium">{{ __('nav.profile') }}</span>
            </a>
        @endauth
    </div>
</nav>
