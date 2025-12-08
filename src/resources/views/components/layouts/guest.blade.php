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
        /* Fade In Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        .animation-delay-100 { animation-delay: 0.1s; opacity: 0; }
        .animation-delay-200 { animation-delay: 0.2s; opacity: 0; }
        .animation-delay-300 { animation-delay: 0.3s; opacity: 0; }

        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            .animate-fade-in-up {
                animation: none;
            }
            .animate-fade-in-up,
            .animation-delay-100,
            .animation-delay-200,
            .animation-delay-300 {
                opacity: 1;
                transform: none;
            }
        }
    </style>
</head>
<body class="min-h-screen font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left Panel - Branding (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 xl:w-[55%] bg-gradient-to-br from-pink-500 via-rose-500 to-rose-600 relative overflow-hidden">
            <!-- Subtle Pattern Overlay -->
{{--            <div class="absolute inset-0 opacity-10">--}}
{{--                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">--}}
{{--                    <defs>--}}
{{--                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">--}}
{{--                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>--}}
{{--                        </pattern>--}}
{{--                    </defs>--}}
{{--                    <rect width="100%" height="100%" fill="url(#grid)" />--}}
{{--                </svg>--}}
{{--            </div>--}}

            <!-- Decorative Circles -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 w-[500px] h-[500px] bg-rose-400/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-pink-300/15 rounded-full blur-2xl"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-between p-12 xl:p-16 w-full">
                <!-- Logo -->
                <div class="animate-fade-in-up">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:bg-white/30 transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-white">My Travel</span>
                    </a>
                </div>

                <!-- Center Content -->
                <div class="flex-1 flex flex-col justify-center max-w-lg animate-fade-in-up animation-delay-100">
                    <h1 class="text-4xl xl:text-5xl font-bold text-white leading-tight mb-6">
                        특별한 여행 경험을<br>시작하세요
                    </h1>
                    <p class="text-lg text-rose-100 leading-relaxed mb-8">
                        전 세계의 현지 가이드와 함께하는 프리미엄 투어.
                        당신만의 여행 스토리를 만들어 보세요.
                    </p>

                    <!-- Feature List -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-rose-50">검증된 현지 전문 가이드</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-rose-50">안전하고 신뢰할 수 있는 결제</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-rose-50">24시간 고객 지원 서비스</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Stats -->
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-white/20 animate-fade-in-up animation-delay-200">
                    <div>
                        <div class="text-3xl font-bold text-white">500+</div>
                        <div class="text-sm text-rose-200 mt-1">투어 상품</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white">10K+</div>
                        <div class="text-sm text-rose-200 mt-1">만족 고객</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white">4.9</div>
                        <div class="text-sm text-rose-200 mt-1">평균 평점</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="w-full lg:w-1/2 xl:w-[45%] flex flex-col bg-white">
            <!-- Mobile Logo (visible only on mobile) -->
            <div class="lg:hidden px-6 pt-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shadow-lg shadow-pink-500/25">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">My Travel</span>
                </a>
            </div>

            <!-- Form Container -->
            <div class="flex-1 flex items-center justify-center px-6 py-12 lg:px-12 xl:px-16">
                <div class="w-full max-w-md animate-fade-in-up">
                    {{ $slot }}
                </div>
            </div>

            <!-- Language Switcher & Footer -->
            <div class="px-6 pb-8 lg:px-12 xl:px-16 animate-fade-in-up animation-delay-300">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                    <!-- Language Switcher -->
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        <div class="flex items-center gap-1 text-sm">
                            <a href="?lang=ko" class="px-2 py-1 rounded transition-colors cursor-pointer {{ app()->getLocale() === 'ko' ? 'text-rose-600 font-medium bg-rose-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">한국어</a>
                            <a href="?lang=en" class="px-2 py-1 rounded transition-colors cursor-pointer {{ app()->getLocale() === 'en' ? 'text-rose-600 font-medium bg-rose-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">EN</a>
                            <a href="?lang=zh" class="px-2 py-1 rounded transition-colors cursor-pointer {{ app()->getLocale() === 'zh' ? 'text-rose-600 font-medium bg-rose-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">中文</a>
                            <a href="?lang=ja" class="px-2 py-1 rounded transition-colors cursor-pointer {{ app()->getLocale() === 'ja' ? 'text-rose-600 font-medium bg-rose-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">日本語</a>
                        </div>
                    </div>

                    <!-- Copyright -->
                    <p class="text-sm text-gray-400">
                        © {{ date('Y') }} My Travel. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
