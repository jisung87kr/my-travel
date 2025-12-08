{{--
    Alert Component

    Props:
    - type: 'success' | 'error' | 'warning' | 'info' (default: 'info')
    - dismissible: boolean (default: false)
    - title: string (optional)

    Usage:
    <x-ui.alert type="success" title="성공" dismissible>
        작업이 성공적으로 완료되었습니다.
    </x-ui.alert>
--}}

@props([
    'type' => 'info',
    'dismissible' => false,
    'title' => null,
])

@php
$typeClasses = [
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'error' => 'bg-red-50 border-red-200 text-red-800',
    'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
    'info' => 'bg-blue-50 border-blue-200 text-blue-800',
];

$iconBgClasses = [
    'success' => 'bg-green-100',
    'error' => 'bg-red-100',
    'warning' => 'bg-amber-100',
    'info' => 'bg-blue-100',
];

$iconClasses = [
    'success' => 'text-green-600',
    'error' => 'text-red-600',
    'warning' => 'text-amber-600',
    'info' => 'text-blue-600',
];

$iconPaths = [
    'success' => 'M4.5 12.75l6 6 9-13.5',
    'error' => 'M6 18L18 6M6 6l12 12',
    'warning' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
    'info' => 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',
];

$classes = $typeClasses[$type];
$iconBg = $iconBgClasses[$type];
$iconColor = $iconClasses[$type];
$iconPath = $iconPaths[$type];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    {{ $attributes->merge(['class' => "rounded-2xl border $classes p-4"]) }}
    role="alert"
>
    <div class="flex items-start gap-3">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl {{ $iconBg }} flex items-center justify-center">
            <svg class="h-5 w-5 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
            </svg>
        </div>

        <div class="flex-1 pt-1">
            @if($title)
                <h3 class="text-sm font-semibold mb-1">{{ $title }}</h3>
            @endif
            <div class="text-sm">
                {{ $slot }}
            </div>
        </div>

        @if($dismissible)
            <button
                type="button"
                @click="show = false"
                class="flex-shrink-0 p-1.5 rounded-lg hover:bg-black/5 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 cursor-pointer"
                aria-label="닫기"
            >
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </div>
</div>
