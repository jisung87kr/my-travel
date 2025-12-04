{{--
    Toast Component (Alpine.js required)

    Props:
    - type: 'success' | 'error' | 'warning' | 'info' (default: 'info')
    - message: string (required)
    - duration: number (default: 3000) - milliseconds before auto-dismiss

    Usage:
    <x-ui.toast />

    Triggering toast:
    <button @click="$dispatch('show-toast', { type: 'success', message: '저장되었습니다!' })">저장</button>
--}}

@props([
    'duration' => 3000,
])

<div
    x-data="{
        show: false,
        type: 'info',
        message: '',
        duration: {{ $duration }},
        timeout: null,
        showToast(event) {
            this.type = event.detail.type || 'info';
            this.message = event.detail.message || '';
            this.duration = event.detail.duration || {{ $duration }};
            this.show = true;

            if (this.timeout) clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                this.show = false;
            }, this.duration);
        },
        getClasses() {
            const types = {
                'success': 'bg-green-50 border-green-200 text-green-800',
                'error': 'bg-red-50 border-red-200 text-red-800',
                'warning': 'bg-yellow-50 border-yellow-200 text-yellow-800',
                'info': 'bg-blue-50 border-blue-200 text-blue-800'
            };
            return types[this.type] || types.info;
        },
        getIcon() {
            const icons = {
                'success': '<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\" />',
                'error': '<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z\" />',
                'warning': '<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z\" />',
                'info': '<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\" />'
            };
            return icons[this.type] || icons.info;
        }
    }"
    @show-toast.window="showToast($event)"
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-4 right-4 z-50 max-w-sm w-full"
    style="display: none;"
    role="alert"
    aria-live="assertive"
>
    <div
        :class="getClasses()"
        class="rounded-xl border shadow-card p-4 flex items-start gap-3"
    >
        <div class="flex-shrink-0">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-html="getIcon()"></svg>
        </div>

        <div class="flex-1">
            <p class="text-sm font-medium" x-text="message"></p>
        </div>

        <button
            type="button"
            @click="show = false"
            class="flex-shrink-0 rounded-lg p-1 hover:bg-black hover:bg-opacity-10 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            aria-label="Close"
        >
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
