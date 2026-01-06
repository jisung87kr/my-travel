@props([
    'compact' => false,
])

<div {{ $attributes->merge(['class' => $compact ? 'w-56 bg-white rounded-xl shadow-xl border border-gray-100 p-4 z-50' : 'w-[350px] bg-white rounded-3xl shadow-[0_8px_28px_rgba(0,0,0,0.28)] border border-gray-100 p-8 z-50 cursor-auto']) }}>

    <input type="hidden" name="adults" :value="adults">
    <input type="hidden" name="children" :value="children">

    <!-- Adults -->
    <div class="flex items-center justify-between {{ $compact ? 'pb-3' : 'pb-6' }} border-b border-gray-100">
        <div>
            <span class="{{ $compact ? 'text-sm font-medium' : 'text-base font-bold' }} text-gray-900 block">{{ __('home.adults_label') }}</span>
            <span class="{{ $compact ? 'text-xs' : 'text-sm' }} text-gray-500">{{ __('home.adults_age') }}</span>
        </div>
        <div class="flex items-center {{ $compact ? 'gap-2' : 'gap-4' }}">
            <button type="button"
                    @click="adults = Math.max(1, adults - 1)"
                    class="{{ $compact ? 'w-7 h-7' : 'w-8 h-8' }} rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-800 hover:text-gray-800 transition-colors disabled:opacity-30 disabled:hover:border-gray-300 disabled:hover:text-gray-500"
                    :disabled="adults <= 1">
                <svg class="{{ $compact ? 'w-3 h-3' : 'w-4 h-4' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" /></svg>
            </button>
            <span class="{{ $compact ? 'text-sm font-medium w-5' : 'text-base font-semibold w-6' }} text-center" x-text="adults"></span>
            <button type="button"
                    @click="adults = Math.min(20, adults + 1)"
                    class="{{ $compact ? 'w-7 h-7' : 'w-8 h-8' }} rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-800 hover:text-gray-800 transition-colors">
                <svg class="{{ $compact ? 'w-3 h-3' : 'w-4 h-4' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            </button>
        </div>
    </div>

    <!-- Children -->
    <div class="flex items-center justify-between {{ $compact ? 'pt-3' : 'pt-6' }}">
        <div>
            <span class="{{ $compact ? 'text-sm font-medium' : 'text-base font-bold' }} text-gray-900 block">{{ __('home.children_label') }}</span>
            <span class="{{ $compact ? 'text-xs' : 'text-sm' }} text-gray-500">{{ __('home.children_age') }}</span>
        </div>
        <div class="flex items-center {{ $compact ? 'gap-2' : 'gap-4' }}">
            <button type="button"
                    @click="children = Math.max(0, children - 1)"
                    class="{{ $compact ? 'w-7 h-7' : 'w-8 h-8' }} rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-800 hover:text-gray-800 transition-colors disabled:opacity-30 disabled:hover:border-gray-300 disabled:hover:text-gray-500"
                    :disabled="children <= 0">
                <svg class="{{ $compact ? 'w-3 h-3' : 'w-4 h-4' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" /></svg>
            </button>
            <span class="{{ $compact ? 'text-sm font-medium w-5' : 'text-base font-semibold w-6' }} text-center" x-text="children"></span>
            <button type="button"
                    @click="children = Math.min(20, children + 1)"
                    class="{{ $compact ? 'w-7 h-7' : 'w-8 h-8' }} rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-800 hover:text-gray-800 transition-colors">
                <svg class="{{ $compact ? 'w-3 h-3' : 'w-4 h-4' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            </button>
        </div>
    </div>
</div>
