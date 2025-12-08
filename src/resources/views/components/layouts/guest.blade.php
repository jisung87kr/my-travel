<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'My Travel') }}</title>

    <!-- Pretendard 웹폰트 -->
    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Aurora Animations */
        @keyframes aurora-1 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -50px) rotate(5deg); }
            66% { transform: translate(-20px, 20px) rotate(-5deg); }
        }
        @keyframes aurora-2 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(-40px, 30px) rotate(-5deg); }
            66% { transform: translate(20px, -40px) rotate(5deg); }
        }
        @keyframes aurora-3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, 30px) scale(1.1); }
        }
        .animate-aurora-1 { animation: aurora-1 20s ease-in-out infinite; }
        .animate-aurora-2 { animation: aurora-2 25s ease-in-out infinite; }
        .animate-aurora-3 { animation: aurora-3 15s ease-in-out infinite; }

        /* Fade In Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .animation-delay-100 { animation-delay: 0.1s; opacity: 0; }
        .animation-delay-200 { animation-delay: 0.2s; opacity: 0; }

        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            .animate-aurora-1,
            .animate-aurora-2,
            .animate-aurora-3,
            .animate-fade-in-up {
                animation: none;
            }
            .animate-fade-in-up,
            .animation-delay-100,
            .animation-delay-200 {
                opacity: 1;
                transform: none;
            }
        }
    </style>
</head>
<body class="min-h-screen font-sans antialiased">
    <!-- Aurora Background -->
    <div class="fixed inset-0 -z-10">
        <!-- Base gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900"></div>
        <!-- Aurora layers -->
        <div class="absolute inset-0 opacity-60">
            <div class="absolute top-0 -left-1/4 w-1/2 h-1/2 bg-gradient-to-br from-cyan-400/40 via-transparent to-transparent rounded-full blur-3xl animate-aurora-1"></div>
            <div class="absolute top-1/4 right-0 w-1/2 h-1/2 bg-gradient-to-bl from-pink-500/40 via-transparent to-transparent rounded-full blur-3xl animate-aurora-2"></div>
            <div class="absolute bottom-0 left-1/3 w-1/2 h-1/2 bg-gradient-to-t from-violet-500/30 via-transparent to-transparent rounded-full blur-3xl animate-aurora-3"></div>
        </div>
        <!-- Subtle noise texture -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMzAwIj48ZmlsdGVyIGlkPSJhIiB4PSIwIiB5PSIwIj48ZmVUdXJidWxlbmNlIGJhc2VGcmVxdWVuY3k9Ii43NSIgc3RpdGNoVGlsZXM9InN0aXRjaCIgdHlwZT0iZnJhY3RhbE5vaXNlIi8+PC9maWx0ZXI+PHJlY3QgZmlsdGVyPSJ1cmwoI2EpIiBoZWlnaHQ9IjEwMCUiIG9wYWNpdHk9Ii4wNSIgd2lkdGg9IjEwMCUiLz48L3N2Zz4=')] opacity-50"></div>
    </div>

    <!-- Content -->
    <div class="relative min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Logo -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md animate-fade-in-up">
            <a href="{{ route('home') }}" class="flex justify-center items-center gap-2 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center shadow-lg shadow-pink-500/25 group-hover:shadow-xl group-hover:shadow-pink-500/30 transition-all duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                </div>
                <span class="text-2xl font-bold text-white">My Travel</span>
            </a>
        </div>

        <!-- Card -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md animate-fade-in-up animation-delay-100">
            <div class="bg-white/95 backdrop-blur-xl py-8 px-6 shadow-2xl shadow-black/10 rounded-2xl sm:px-10 ring-1 ring-white/20">
                {{ $slot }}
            </div>
        </div>

        <!-- Language Switcher -->
        <div class="mt-8 text-center animate-fade-in-up animation-delay-200">
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                <a href="?lang=ko" class="text-sm transition-colors {{ app()->getLocale() === 'ko' ? 'font-semibold text-white' : 'text-white/70 hover:text-white' }}">한국어</a>
                <span class="w-px h-4 bg-white/30"></span>
                <a href="?lang=en" class="text-sm transition-colors {{ app()->getLocale() === 'en' ? 'font-semibold text-white' : 'text-white/70 hover:text-white' }}">English</a>
                <span class="w-px h-4 bg-white/30"></span>
                <a href="?lang=zh" class="text-sm transition-colors {{ app()->getLocale() === 'zh' ? 'font-semibold text-white' : 'text-white/70 hover:text-white' }}">中文</a>
                <span class="w-px h-4 bg-white/30"></span>
                <a href="?lang=ja" class="text-sm transition-colors {{ app()->getLocale() === 'ja' ? 'font-semibold text-white' : 'text-white/70 hover:text-white' }}">日本語</a>
            </div>
        </div>
    </div>
</body>
</html>