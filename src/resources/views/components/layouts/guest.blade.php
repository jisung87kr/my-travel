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
</head>
<body class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans antialiased">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <a href="{{ route('home') }}" class="flex justify-center">
            <span class="text-3xl font-bold text-indigo-600">My Travel</span>
        </a>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            {{ $slot }}
        </div>
    </div>

    <!-- Language Switcher -->
    <div class="mt-8 text-center">
        <div class="inline-flex space-x-4 text-sm text-gray-500">
            <a href="?lang=ko" class="{{ app()->getLocale() === 'ko' ? 'font-bold text-indigo-600' : 'hover:text-gray-700' }}">한국어</a>
            <a href="?lang=en" class="{{ app()->getLocale() === 'en' ? 'font-bold text-indigo-600' : 'hover:text-gray-700' }}">English</a>
            <a href="?lang=zh" class="{{ app()->getLocale() === 'zh' ? 'font-bold text-indigo-600' : 'hover:text-gray-700' }}">中文</a>
            <a href="?lang=ja" class="{{ app()->getLocale() === 'ja' ? 'font-bold text-indigo-600' : 'hover:text-gray-700' }}">日本語</a>
        </div>
    </div>
</body>
</html>
