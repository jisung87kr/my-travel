{{--
    Badge Component

    Props:
    - variant: 'success' | 'warning' | 'error' | 'info' (default: 'info')
    - size: 'sm' | 'md' | 'lg' (default: 'md')

    Usage:
    <x-ui.badge variant="success">승인됨</x-ui.badge>
    <x-ui.badge variant="error">거절됨</x-ui.badge>
--}}

@props([
    'variant' => 'info',
    'size' => 'md',
])

@php
$baseClasses = 'inline-flex items-center font-semibold rounded-lg';

$variantClasses = [
    'success' => 'bg-green-100 text-green-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'error' => 'bg-red-100 text-red-800',
    'info' => 'bg-blue-100 text-blue-800',
];

$sizeClasses = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-1 text-sm',
    'lg' => 'px-3 py-1.5 text-base',
];

$classes = trim($baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size]);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
