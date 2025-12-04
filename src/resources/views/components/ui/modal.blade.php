{{--
    Modal Component (Alpine.js required)

    Props:
    - name: string (required) - unique identifier for the modal
    - title: string (optional) - modal title
    - maxWidth: 'sm' | 'md' | 'lg' | 'xl' | '2xl' (default: 'md')
    - closeable: boolean (default: true)

    Usage:
    <x-ui.modal name="login-modal" title="로그인">
        <p>로그인 폼 내용</p>
    </x-ui.modal>

    Opening the modal:
    <button @click="$dispatch('open-modal', 'login-modal')">모달 열기</button>
--}}

@props([
    'name',
    'title' => null,
    'maxWidth' => 'md',
    'closeable' => true,
])

@php
$maxWidthClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
];
@endphp

<div
    x-data="{
        show: false,
        name: '{{ $name }}',
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...this.$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1 }
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-hidden');
            setTimeout(() => firstFocusable().focus(), 100);
        } else {
            document.body.classList.remove('overflow-hidden');
        }
    })"
    @open-modal.window="show = ($event.detail === name)"
    @close-modal.window="show = false"
    @keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
    style="display: none;"
    role="dialog"
    aria-modal="true"
    :aria-labelledby="name + '-title'"
>
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"
        @click="@if($closeable) show = false @endif"
    ></div>

    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative bg-white rounded-xl shadow-modal overflow-hidden transform transition-all sm:w-full sm:mx-auto {{ $maxWidthClasses[$maxWidth] }}"
        @click.stop
    >
        @if($title || $closeable)
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                @if($title)
                    <h3 class="text-lg font-semibold text-gray-900" :id="name + '-title'">
                        {{ $title }}
                    </h3>
                @endif

                @if($closeable)
                    <button
                        type="button"
                        @click="show = false"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-lg p-1.5 transition duration-200"
                        aria-label="Close modal"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        @endif

        <div class="px-6 py-4">
            {{ $slot }}
        </div>
    </div>
</div>
