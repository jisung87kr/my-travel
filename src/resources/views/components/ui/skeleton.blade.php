{{--
    Skeleton Component

    Props:
    - type: 'text' | 'circle' | 'rectangle' (default: 'rectangle')
    - width: string (default: 'w-full')
    - height: string (default: 'h-4')

    Usage:
    <x-ui.skeleton />
    <x-ui.skeleton type="circle" width="w-12" height="h-12" />
    <x-ui.skeleton type="text" width="w-3/4" />
--}}

@props([
    'type' => 'rectangle',
    'width' => 'w-full',
    'height' => 'h-4',
])

@php
$baseClasses = 'animate-pulse bg-gray-200';

$typeClasses = [
    'text' => 'rounded',
    'circle' => 'rounded-full',
    'rectangle' => 'rounded-xl',
];

$classes = trim($baseClasses . ' ' . $typeClasses[$type] . ' ' . $width . ' ' . $height);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} aria-busy="true" aria-live="polite">
    <span class="sr-only">Loading...</span>
</div>
