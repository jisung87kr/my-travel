@props([
    'regions' => collect(),
    'compact' => false,
])

<div class="{{ $compact ? 'max-w-3xl' : 'max-w-5xl' }} mx-auto relative z-20">
    <div class="bg-white rounded-full shadow-[0_6px_16px_rgba(0,0,0,0.12)] border border-gray-200"
         x-data="{
             destination: '',
             date: '',
             adults: 1,
             children: 0,
             showDestination: false,
             showDate: false,
             showGuests: false,
             hoveredSection: null,
             get totalGuests() { return this.adults + this.children }
         }">
        <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET" class="relative">
            <div class="flex flex-col md:flex-row md:items-center h-auto md:h-16">

                <!-- 1. Destination Input -->
                <div class="relative flex-1 group h-full">
                    <button type="button"
                            @click="showDestination = !showDestination; showDate = false; showGuests = false"
                            @mouseenter="hoveredSection = 'destination'"
                            @mouseleave="hoveredSection = null"
                            class="w-full h-full flex flex-col justify-center px-8 rounded-full transition-colors cursor-pointer text-left relative z-10"
                            :class="{ 'bg-gray-100': hoveredSection === 'destination' || showDestination }">
                        <label class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-0.5 cursor-pointer">{{ __('home.where_to') }}</label>
                        <input type="text"
                               readonly
                               class="bg-transparent border-none p-0 text-sm font-medium text-gray-600 placeholder-gray-400 focus:ring-0 focus:outline-none focus-visible:ring-0 !focus-visible:outline-none cursor-pointer w-full truncate"
                               :class="{ 'text-gray-900': destination }"
                               :value="destination"
                               placeholder="{{ __('home.search_placeholder') }}">
                    </button>

                    <!-- Destination Dropdown -->
                    <div x-show="showDestination"
                         @click.away="showDestination = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         class="absolute top-full left-0 mt-4 z-50"
                         style="display: none;">
                        <x-search.destination-dropdown :regions="$regions" />
                    </div>
                </div>

                <!-- 2. Date Input -->
                <div class="relative flex-1 group h-full">
                    <button type="button"
                            @click="showDate = !showDate; showDestination = false; showGuests = false"
                            @mouseenter="hoveredSection = 'date'"
                            @mouseleave="hoveredSection = null"
                            class="w-full h-full flex flex-col justify-center px-8 rounded-full transition-colors cursor-pointer text-left relative z-10"
                            :class="{ 'bg-gray-100': hoveredSection === 'date' || showDate }">
                        <label class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-0.5 cursor-pointer">{{ __('home.add_dates') }}</label>
                         <input type="text"
                               readonly
                               class="bg-transparent border-none p-0 text-sm font-medium text-gray-600 placeholder-gray-400 focus:ring-0 focus:outline-none focus-visible:ring-0 focus-visible:outline-none cursor-pointer w-full truncate"
                               :class="{ 'text-gray-900': date }"
                               :value="date ? date : ''"
                               placeholder="{{ __('home.select_date') }}">
                    </button>

                    <!-- Date Picker Dropdown -->
                    <div x-show="showDate"
                         @click.away="showDate = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-1/2 -translate-x-1/2 mt-4 z-50"
                         style="display: none;">
                        <x-search.date-picker-dropdown />
                    </div>
                </div>

                <!-- 3. Guests Input + Search Button -->
                <div class="relative flex-1 group h-full flex items-center pr-2">
                    <button type="button"
                            @click="showGuests = !showGuests; showDestination = false; showDate = false"
                            @mouseenter="hoveredSection = 'guests'"
                            @mouseleave="hoveredSection = null"
                            class="flex-1 h-full flex flex-col justify-center px-8 rounded-full transition-colors cursor-pointer text-left relative z-10"
                            :class="{ 'bg-gray-100': hoveredSection === 'guests' || showGuests }">
                        <label class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-0.5 cursor-pointer">{{ __('home.travelers') }}</label>
                        <div class="text-sm font-medium text-gray-600 truncate" :class="{ 'text-gray-900': totalGuests > 1 }">
                             <span x-text="totalGuests > 1 ? '{{ __('home.adults_label') }} ' + adults + (children > 0 ? ', {{ __('home.children_label') }} ' + children : '') : '{{ __('home.add_guests') }}'"></span>
                        </div>
                    </button>

                    <!-- Guests Dropdown -->
                    <div x-show="showGuests"
                         @click.away="showGuests = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full right-0 mt-4 z-50"
                         style="display: none;">
                        <x-search.guests-dropdown />
                    </div>

                    <!-- Large Search Button -->
                    <button type="submit"
                            class="hidden md:flex items-center justify-center gap-2 h-12 px-6 rounded-full bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white shadow-lg transition-all hover:scale-105 active:scale-95 ml-2">
                        <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <span class="font-bold text-sm shrink-0">{{ __('home.search_button') }}</span>
                    </button>
                </div>

                <!-- Mobile Search Button -->
                <div class="md:hidden p-4 pt-2">
                    <button type="submit"
                            class="w-full py-4 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-bold text-lg rounded-2xl shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        {{ __('home.search_button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
