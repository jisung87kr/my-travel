{{--
    Button Component

    Props:
    - variant: 'primary' | 'secondary' | 'ghost' | 'danger' (default: 'primary')
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
$baseClasses = 'inline-flex items-center justify-center font-semibold transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer';

$variantClasses = [
    'primary' => 'bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 focus:ring-pink-500',
    'secondary' => 'bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 focus:ring-gray-400',
    'ghost' => 'bg-transparent hover:bg-pink-50 text-gray-700 hover:text-pink-600 focus:ring-pink-400',
    'danger' => 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white shadow-lg shadow-red-500/25 hover:shadow-xl hover:shadow-red-500/30 focus:ring-red-500',
];

$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm rounded-lg gap-1.5',
    'md' => 'px-4 py-2.5 text-base rounded-xl gap-2',
    'lg' => 'px-6 py-3.5 text-lg rounded-xl gap-2',
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
        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif
    {{ $slot }}
</button>
