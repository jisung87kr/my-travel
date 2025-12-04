{{--
    Avatar Component

    Props:
    - src: string (optional) - image URL
    - name: string (optional) - user name for initials fallback
    - size: 'sm' | 'md' | 'lg' | 'xl' (default: 'md')
    - alt: string (optional) - alt text for image

    Usage:
    <x-ui.avatar src="/path/to/image.jpg" alt="User name" />
    <x-ui.avatar name="John Doe" size="lg" />
--}}

@props([
    'src' => null,
    'name' => null,
    'size' => 'md',
    'alt' => null,
])

@php
$sizeClasses = [
    'sm' => 'w-8 h-8 text-xs',
    'md' => 'w-10 h-10 text-sm',
    'lg' => 'w-12 h-12 text-base',
    'xl' => 'w-16 h-16 text-xl',
];

$baseClasses = 'inline-flex items-center justify-center rounded-full overflow-hidden';
$classes = trim($baseClasses . ' ' . $sizeClasses[$size]);

// Get initials from name
$initials = '';
if ($name) {
    $words = explode(' ', $name);
    if (count($words) >= 2) {
        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
    } else {
        $initials = strtoupper(substr($name, 0, 2));
    }
}

// Generate background color based on name
$colors = [
    'bg-red-500',
    'bg-orange-500',
    'bg-yellow-500',
    'bg-green-500',
    'bg-teal-500',
    'bg-blue-500',
    'bg-indigo-500',
    'bg-purple-500',
    'bg-pink-500',
];
$colorIndex = $name ? (ord(substr($name, 0, 1)) % count($colors)) : 0;
$bgColor = $colors[$colorIndex];
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($src)
        <img
            src="{{ $src }}"
            alt="{{ $alt ?? $name ?? 'Avatar' }}"
            class="w-full h-full object-cover"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        />
        @if($name)
            <span class="w-full h-full {{ $bgColor }} text-white font-semibold flex items-center justify-center" style="display: none;">
                {{ $initials }}
            </span>
        @else
            <span class="w-full h-full bg-gray-300 text-gray-600 font-semibold flex items-center justify-center" style="display: none;">
                <svg class="w-1/2 h-1/2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            </span>
        @endif
    @elseif($name)
        <span class="w-full h-full {{ $bgColor }} text-white font-semibold flex items-center justify-center">
            {{ $initials }}
        </span>
    @else
        <span class="w-full h-full bg-gray-300 text-gray-600 flex items-center justify-center">
            <svg class="w-1/2 h-1/2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
        </span>
    @endif
</div>
