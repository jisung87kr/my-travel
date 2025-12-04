<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $header ?? '가이드' }} - 가이드 대시보드</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-teal-800 text-white flex-shrink-0 hidden lg:block">
            <div class="p-6">
                <h1 class="text-xl font-bold">가이드 대시보드</h1>
                <p class="text-teal-200 text-sm mt-1">{{ auth()->user()->name }}</p>
            </div>

            <nav class="mt-6">
                <a href="{{ route('guide.dashboard') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('guide.dashboard') ? 'bg-teal-900 border-r-4 border-teal-400' : 'hover:bg-teal-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    대시보드
                </a>

                <a href="{{ route('guide.schedules.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('guide.schedules.*') ? 'bg-teal-900 border-r-4 border-teal-400' : 'hover:bg-teal-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    일정 관리
                </a>

                <a href="{{ route('guide.checkin.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('guide.checkin.*') ? 'bg-teal-900 border-r-4 border-teal-400' : 'hover:bg-teal-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    QR 체크인
                </a>
            </nav>

            <div class="absolute bottom-0 left-0 right-0 p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center text-teal-300 hover:text-white">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        로그아웃
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Mobile Header -->
            <header class="lg:hidden bg-teal-800 text-white p-4 flex items-center justify-between">
                <h1 class="text-lg font-bold">가이드 대시보드</h1>
                <button id="mobileMenuBtn" class="p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </header>

            <!-- Page Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $header ?? '대시보드' }}</h2>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-auto">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden lg:hidden">
        <aside class="w-64 bg-teal-800 text-white h-full">
            <div class="p-6 flex justify-between items-center">
                <h1 class="text-xl font-bold">가이드 대시보드</h1>
                <button id="closeMobileMenu" class="p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="mt-6">
                <a href="{{ route('guide.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-teal-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    대시보드
                </a>

                <a href="{{ route('guide.schedules.index') }}" class="flex items-center px-6 py-3 hover:bg-teal-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    일정 관리
                </a>

                <a href="{{ route('guide.checkin.index') }}" class="flex items-center px-6 py-3 hover:bg-teal-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    QR 체크인
                </a>
            </nav>
        </aside>
    </div>

    <script>
        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.remove('hidden');
        });
        document.getElementById('closeMobileMenu')?.addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.add('hidden');
        });
        document.getElementById('mobileMenu')?.addEventListener('click', (e) => {
            if (e.target === e.currentTarget) {
                e.currentTarget.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
