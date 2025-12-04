<x-layouts.app :title="$title ?? __('nav.mypage')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Title -->
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('nav.mypage') }}</h1>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="-mb-px flex space-x-8 overflow-x-auto">
                <!-- Bookings Tab -->
                <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
                   class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('my.bookings') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    {{ __('nav.my_bookings') }}
                </a>

                <!-- Wishlist Tab -->
                <a href="{{ route('my.wishlist', ['locale' => app()->getLocale()]) }}"
                   class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('my.wishlist') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    {{ __('nav.wishlist') }}
                </a>

                <!-- Reviews Tab -->
                <a href="{{ route('my.reviews', ['locale' => app()->getLocale()]) }}"
                   class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('my.reviews') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    {{ __('nav.my_reviews') }}
                </a>

                <!-- Profile Tab -->
                <a href="{{ route('my.profile', ['locale' => app()->getLocale()]) }}"
                   class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('my.profile') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('nav.profile') }}
                </a>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            {{ $slot }}
        </div>
    </div>
</x-layouts.app>
