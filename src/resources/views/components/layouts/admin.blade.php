<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? '관리자' }} - {{ config('app.name', 'My Travel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-indigo-900 text-white flex-shrink-0 hidden lg:block">
            <div class="p-4 border-b border-indigo-800">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">
                    {{ config('app.name', 'My Travel') }}
                </a>
                <p class="text-sm text-indigo-300 mt-1">관리자 대시보드</p>
            </div>

            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-800' : 'hover:bg-indigo-800' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            대시보드
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-indigo-800' : 'hover:bg-indigo-800' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            사용자 관리
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.vendors.index') }}"
                           class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.vendors.*') ? 'bg-indigo-800' : 'hover:bg-indigo-800' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            제공자 관리
                            @if(isset($pendingVendorsCount) && $pendingVendorsCount > 0)
                                <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingVendorsCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}"
                           class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-indigo-800' : 'hover:bg-indigo-800' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            상품 관리
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.bookings.index') }}"
                           class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.bookings.*') ? 'bg-indigo-800' : 'hover:bg-indigo-800' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            예약 관리
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.no-shows.index') }}"
                           class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.no-shows.*') ? 'bg-indigo-800' : 'hover:bg-indigo-800' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            노쇼 관리
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="absolute bottom-0 w-64 p-4 border-t border-indigo-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-indigo-700 flex items-center justify-center">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-indigo-300">관리자</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-indigo-300 hover:text-white">
                        로그아웃
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-900">
                        {{ $header ?? '관리자 대시보드' }}
                    </h1>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            사이트 보기
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
