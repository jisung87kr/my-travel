{{--
    Button Component

    Props:
    - variant: 'primary' | 'secondary' | 'ghost' (default: 'primary')
    - size: 'sm' | 'md' | 'lg' (default: 'md')
    - type: 'button' | 'submit' | 'reset' (default: 'button')
    - loading: boolean (default: false)
    - disabled: boolean (default: false)

    Usage:
    <x-ui.button variant="primary" size="lg">예약하기</x-ui.button>
    <x-ui.button variant="secondary" :loading="true">처리중...</x-ui.button>
--}}

@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'loading' => false,
    'disabled' => false,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-semibold transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variantClasses = [
    'primary' => 'bg-primary-500 hover:bg-primary-600 text-white focus:ring-primary-500',
    'secondary' => 'bg-gray-100 hover:bg-gray-200 text-gray-900 focus:ring-gray-500',
    'ghost' => 'bg-transparent hover:bg-gray-50 text-gray-700 focus:ring-gray-400',
];

$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm rounded-lg',
    'md' => 'px-4 py-2.5 text-base rounded-xl',
    'lg' => 'px-6 py-3.5 text-lg rounded-xl',
];

$classes = trim($baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size]);
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
    @if($disabled || $loading) disabled @endif
    aria-busy="{{ $loading ? 'true' : 'false' }}"
>
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif
    {{ $slot }}
</button>
