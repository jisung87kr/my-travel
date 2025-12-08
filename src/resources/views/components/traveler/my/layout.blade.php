<x-layouts.app :title="$title ?? __('nav.mypage')">
    <div class="bg-gray-50 min-h-screen">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                    <!-- User Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-pink-500/20">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <!-- User Info -->
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h1>
                        <p class="text-gray-500 mt-1">{{ auth()->user()->email }}</p>
                        <div class="flex items-center gap-4 mt-3">
                            <span class="inline-flex items-center gap-1.5 text-sm text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                {{ auth()->user()->created_at->format('Y.m') }} 가입
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white border-b border-gray-100 sticky top-16 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex gap-1 overflow-x-auto scrollbar-hide py-2 -mx-4 px-4 sm:mx-0 sm:px-0">
                    <!-- Bookings Tab -->
                    <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200
                              {{ request()->routeIs('my.bookings') || request()->routeIs('my.booking.detail')
                                  ? 'bg-pink-50 text-pink-700'
                                  : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        {{ __('nav.my_bookings') }}
                    </a>

                    <!-- Wishlist Tab -->
                    <a href="{{ route('my.wishlist', ['locale' => app()->getLocale()]) }}"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200
                              {{ request()->routeIs('my.wishlist')
                                  ? 'bg-pink-50 text-pink-700'
                                  : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        {{ __('nav.wishlist') }}
                    </a>

                    <!-- Reviews Tab -->
                    <a href="{{ route('my.reviews', ['locale' => app()->getLocale()]) }}"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200
                              {{ request()->routeIs('my.reviews')
                                  ? 'bg-pink-50 text-pink-700'
                                  : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                        {{ __('nav.my_reviews') }}
                    </a>

                    <!-- Profile Tab -->
                    <a href="{{ route('my.profile', ['locale' => app()->getLocale()]) }}"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200
                              {{ request()->routeIs('my.profile')
                                  ? 'bg-pink-50 text-pink-700'
                                  : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        {{ __('nav.profile') }}
                    </a>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-layouts.app>
