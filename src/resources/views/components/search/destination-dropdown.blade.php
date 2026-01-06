@props([
    'regions' => collect(),
    'compact' => false,
])

<div {{ $attributes->merge(['class' => $compact ? 'w-64 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50' : 'w-full md:w-[350px] bg-white rounded-3xl shadow-[0_8px_28px_rgba(0,0,0,0.28)] border border-gray-100 py-6 px-6 z-50 overflow-hidden']) }}>
    @if(!$compact)
    <div class="relative mb-4">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
        <input type="text"
               name="keyword"
               x-model="destination"
               placeholder="{{ __('home.search_placeholder') }}"
               class="w-full pl-12 pr-4 py-3 text-base bg-gray-100 border-none rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:bg-white transition-all font-medium"
               @keydown.enter="showDestination = false; showDate = true">
    </div>
    @else
    <input type="text"
           name="keyword"
           x-model="destination"
           placeholder="{{ __('home.search_placeholder') }}"
           class="w-full px-4 py-2 text-sm border-b border-gray-100 focus:outline-none"
           @keydown.enter="showDestination = false; showDate = true">
    @endif

    @if($regions->count() > 0)
    <div class="{{ $compact ? 'py-1' : '' }}">
        @if(!$compact)
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 ml-1">{{ __('home.popular_destinations') }}</p>
        @endif
        <div class="{{ $compact ? '' : 'space-y-1' }}">
            @foreach($regions->take(5) as $region)
            @php
                $regionName = is_array($region) ? ($region['name'] ?? $region['label'] ?? '') : $region->getName(app()->getLocale());
            @endphp
            <button type="button"
                    @click="destination = '{{ $regionName }}'; showDestination = false; showDate = true"
                    class="{{ $compact ? 'w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors' : 'w-full flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group' }}">
                <div class="{{ $compact ? 'w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center' : 'w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-white group-hover:shadow-sm transition-all' }}">
                    <svg class="{{ $compact ? 'w-4 h-4 text-gray-500' : 'w-5 h-5' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ $compact ? '1.5' : '2' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <span class="{{ $compact ? '' : 'font-semibold text-gray-700 group-hover:text-gray-900' }}">{{ $regionName }}</span>
            </button>
            @endforeach
        </div>
    </div>
    @endif
</div>
