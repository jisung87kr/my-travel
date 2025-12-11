@props([
    'title' => '대시보드',
    'theme' => 'blue',
    'dashboardRoute',
    'roleLabel' => '관리자',
    'roleSubLabel' => null,
])

@php
$themeColors = [
    'blue' => [
        'gradient' => 'from-blue-600 to-blue-700',
        'shadow' => 'shadow-blue-600/20',
        'profileGradient' => 'from-blue-500 to-blue-600',
        'profileShadow' => 'shadow-blue-500/20',
    ],
    'teal' => [
        'gradient' => 'from-teal-600 to-teal-700',
        'shadow' => 'shadow-teal-600/20',
        'profileGradient' => 'from-teal-500 to-teal-600',
        'profileShadow' => 'shadow-teal-500/20',
    ],
    'violet' => [
        'gradient' => 'from-violet-600 to-violet-700',
        'shadow' => 'shadow-violet-600/20',
        'profileGradient' => 'from-violet-500 to-violet-600',
        'profileShadow' => 'shadow-violet-500/20',
    ],
];
$colors = $themeColors[$theme] ?? $themeColors['blue'];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} - {{ config('app.name', 'My Travel') }}</title>

    <!-- Noto Sans KR -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Noto Sans KR', sans-serif; }
    </style>
</head>
<body class="antialiased bg-slate-50" x-data="{ sidebarOpen: false, profileOpen: false }">
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden"
             style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-200 z-50 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:z-auto flex flex-col">

            <!-- Logo -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100">
                <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br {{ $colors['gradient'] }} rounded-xl flex items-center justify-center shadow-lg {{ $colors['shadow'] }}">
                        {{ $logoIcon ?? '' }}
                    </div>
                    <div>
                        <span class="text-lg font-bold text-slate-900">My Travel</span>
                        <span class="block text-[10px] text-slate-400 font-medium tracking-wider uppercase">{{ $roleLabel }}</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 overflow-y-auto">
                {{ $navigation }}
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-slate-100">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $colors['profileGradient'] }} flex items-center justify-center text-white font-semibold shadow-lg {{ $colors['profileShadow'] }}">
                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ $roleSubLabel ?? $roleLabel }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition-colors" title="로그아웃">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-0">
            <!-- Top Header -->
            <header class="sticky top-0 z-30 h-16 bg-white/80 backdrop-blur-xl border-b border-slate-200/80">
                <div class="h-full px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <!-- Mobile Menu Button -->
                        <button @click="sidebarOpen = true"
                                class="lg:hidden p-2 -ml-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                            </svg>
                        </button>

                        <!-- Page Title -->
                        <div>
                            <h1 class="text-lg font-bold text-slate-900">{{ $header ?? '대시보드' }}</h1>
                        </div>
                    </div>

                    <!-- Header Actions -->
                    <div class="flex items-center gap-2">
                        {{ $headerActions ?? '' }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <!-- Flash Messages -->
                <x-flash-message type="success" />
                <x-flash-message type="error" />
                <x-validation-errors />

                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
