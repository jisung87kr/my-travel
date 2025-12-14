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

    @stack('head')
</head>
<body class="min-h-screen bg-gray-50 antialiased font-sans">
    <!-- Header Component -->
    <x-layouts.header />

    <!-- Toast Notifications -->
    <div x-data="{
            toasts: [],
            init() {
            },
        }" class="fixed top-20 right-4 z-50 flex flex-col gap-3 pointer-events-none">
    </div>

    <style>
        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
        .animate-shrink {
            animation: shrink 5s linear forwards;
        }
    </style>

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Flash Messages -->
        <x-flash-message type="success" />
        <x-flash-message type="error" />
        <x-validation-errors />

        {{ $slot }}
    </main>

    <!-- Footer Component -->
    <x-layouts.footer />

    <!-- Mobile Navigation Component -->
    <x-layouts.mobile-nav />

    @stack('scripts')
</body>
</html>
