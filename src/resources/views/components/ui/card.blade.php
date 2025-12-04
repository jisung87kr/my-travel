{{--
    Card Component

    Props:
    - hoverable: boolean (default: true) - whether to show hover effect
    - padding: 'none' | 'sm' | 'md' | 'lg' (default: 'md')

    Usage:
    <x-ui.card>
        <h3>카드 제목</h3>
        <p>카드 내용</p>
    </x-ui.card>
--}}

@props([
    'hoverable' => true,
    'padding' => 'md',
])

@php
$baseClasses = 'bg-white rounded-xl shadow-card transition duration-200 ease-in-out';

if ($hoverable) {
    $baseClasses .= ' hover:shadow-card-hover';
}

$paddingClasses = [
    'none' => '',
    'sm' => 'p-4',
    'md' => 'p-6',
    'lg' => 'p-8',
];

$classes = trim($baseClasses . ' ' . $paddingClasses[$padding]);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
