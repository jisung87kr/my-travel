@props([
    'badge' => '',
    'title' => '',
    'subtitle' => '',
    'icon' => null,
])

<section class="bg-white py-16 sm:py-24 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @if($badge)
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 text-slate-600 text-sm mb-6">
            @if($icon)
                {{ $icon }}
            @endif
            {{ $badge }}
        </div>
        @endif

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 text-slate-900">
            {{ $title }}
        </h1>

        @if($subtitle)
        <p class="text-lg sm:text-xl text-slate-500 max-w-2xl mx-auto">{{ $subtitle }}</p>
        @endif

        {{ $slot }}
    </div>
</section>
