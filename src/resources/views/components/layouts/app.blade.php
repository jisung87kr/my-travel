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
            @if(session('success'))
                this.addToast('success', '{{ session('success') }}');
            @endif
            @if(session('error'))
                this.addToast('error', '{{ session('error') }}');
            @endif
            @if(session('warning'))
                this.addToast('warning', '{{ session('warning') }}');
            @endif
            @if(session('info'))
                this.addToast('info', '{{ session('info') }}');
            @endif
        },
        addToast(type, message) {
            const id = Date.now();
            this.toasts.push({ id, type, message, show: false });
            setTimeout(() => {
                const toast = this.toasts.find(t => t.id === id);
                if (toast) toast.show = true;
            }, 100);
            setTimeout(() => this.removeToast(id), 5000);
        },
        removeToast(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) toast.show = false;
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 300);
        }
    }" class="fixed top-20 right-4 z-50 flex flex-col gap-3 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-8"
                 class="pointer-events-auto max-w-sm w-full bg-white rounded-xl shadow-lg ring-1 ring-black/5 overflow-hidden"
                 :class="{
                     'ring-green-500/20': toast.type === 'success',
                     'ring-red-500/20': toast.type === 'error',
                     'ring-yellow-500/20': toast.type === 'warning',
                     'ring-blue-500/20': toast.type === 'info'
                 }">
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <!-- Success Icon -->
                            <template x-if="toast.type === 'success'">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </template>
                            <!-- Error Icon -->
                            <template x-if="toast.type === 'error'">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            </template>
                            <!-- Warning Icon -->
                            <template x-if="toast.type === 'warning'">
                                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                            </template>
                            <!-- Info Icon -->
                            <template x-if="toast.type === 'info'">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </template>
                        </div>
                        <!-- Content -->
                        <div class="flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900" x-text="toast.message"></p>
                        </div>
                        <!-- Close Button -->
                        <button @click="removeToast(toast.id)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Progress Bar -->
                <div class="h-1 w-full"
                     :class="{
                         'bg-green-100': toast.type === 'success',
                         'bg-red-100': toast.type === 'error',
                         'bg-yellow-100': toast.type === 'warning',
                         'bg-blue-100': toast.type === 'info'
                     }">
                    <div class="h-full animate-shrink"
                         :class="{
                             'bg-green-500': toast.type === 'success',
                             'bg-red-500': toast.type === 'error',
                             'bg-yellow-500': toast.type === 'warning',
                             'bg-blue-500': toast.type === 'info'
                         }"></div>
                </div>
            </div>
        </template>
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
        {{ $slot }}
    </main>

    <!-- Footer Component -->
    <x-layouts.footer />

    <!-- Mobile Navigation Component -->
    <x-layouts.mobile-nav />

    @stack('scripts')
</body>
</html>
