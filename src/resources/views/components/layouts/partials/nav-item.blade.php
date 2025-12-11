@props([
    'route',
    'label',
    'theme' => 'blue',
    'badge' => null,
    'activePattern' => null,
])

@php
$isActive = $activePattern ? request()->routeIs($activePattern) : request()->routeIs($route);

$themeClasses = [
    'blue' => [
        'active' => 'bg-blue-50 text-blue-700',
        'activeBg' => 'bg-blue-100',
        'activeIcon' => 'text-blue-600',
    ],
    'teal' => [
        'active' => 'bg-teal-50 text-teal-700',
        'activeBg' => 'bg-teal-100',
        'activeIcon' => 'text-teal-600',
    ],
    'violet' => [
        'active' => 'bg-violet-50 text-violet-700',
        'activeBg' => 'bg-violet-100',
        'activeIcon' => 'text-violet-600',
    ],
];

$colors = $themeClasses[$theme] ?? $themeClasses['blue'];
@endphp

<a href="{{ route($route) }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ $isActive ? $colors['active'] : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
    <span class="flex items-center justify-center w-9 h-9 rounded-lg {{ $isActive ? $colors['activeBg'] : 'bg-slate-100' }} transition-colors">
        <svg class="w-5 h-5 {{ $isActive ? $colors['activeIcon'] : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {{ $icon }}
        </svg>
    </span>
    {{ $label }}
    @if($badge)
        <span class="ml-auto px-2 py-0.5 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full">{{ $badge }}</span>
    @endif
</a>
