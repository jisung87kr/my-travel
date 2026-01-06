@props([
    'regions' => collect(),
    'compact' => false,
])

<div class="{{ $compact ? 'max-w-3xl' : 'max-w-4xl' }} mx-auto">
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200 p-2"
         x-data="{
             destination: '',
             date: '',
             adults: 1,
             children: 0,
             showDestination: false,
             showDate: false,
             showGuests: false,
             get totalGuests() { return this.adults + this.children }
         }">
        <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET">
            <div class="flex flex-col md:flex-row md:items-center">
                <!-- Location Input -->
                <div class="relative flex-1 group">
                    <button type="button"
                            @click="showDestination = !showDestination; showDate = false; showGuests = false"
                            class="w-full flex items-center gap-3 h-14 px-4 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer text-left">
                        <svg class="w-5 h-5 text-pink-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <div class="flex flex-col flex-1 min-w-0">
                            <span class="text-xs font-medium text-slate-400">{{ __('home.where_to') }}</span>
                            <span class="text-sm font-medium truncate" :class="destination ? 'text-slate-900' : 'text-slate-400'" x-text="destination || '{{ __('home.search_placeholder') }}'"></span>
                        </div>
                    </button>
                    <!-- Destination Dropdown -->
                    <div x-show="showDestination"
                         @click.away="showDestination = false"
                         x-transition
                         class="absolute top-full left-0 mt-2 w-full md:w-72 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50"
                         style="display: none;">
                        <div class="px-3 pb-2">
                            <input type="text"
                                   name="keyword"
                                   x-model="destination"
                                   placeholder="{{ __('home.search_placeholder') }}"
                                   class="w-full px-3 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500"
                                   @keydown.enter="showDestination = false">
                        </div>
                        @if($regions->count() > 0)
                        <div class="border-t border-slate-100 pt-2">
                            <p class="px-3 py-1.5 text-xs font-medium text-slate-400">{{ __('home.popular_destinations') }}</p>
                            @php
                                $iconColors = ['text-pink-500', 'text-blue-500', 'text-orange-500', 'text-emerald-500', 'text-violet-500', 'text-cyan-500', 'text-rose-500'];
                            @endphp
                            @foreach($regions->take(5) as $index => $region)
                            <button type="button"
                                    @click="destination = '{{ $region['name'] }}'; showDestination = false"
                                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                                <svg class="w-4 h-4 {{ $iconColors[$index % count($iconColors)] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                <span class="font-medium">{{ $region['name'] }}</span>
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Divider -->
                <div class="hidden md:block w-px h-8 bg-slate-200"></div>
                <div class="md:hidden h-px bg-slate-100 mx-4"></div>

                <!-- Date Input -->
                <div class="relative md:w-[28%] group">
                    <button type="button"
                            @click="showDate = !showDate; showDestination = false; showGuests = false"
                            class="w-full flex items-center gap-3 h-14 px-4 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer text-left">
                        <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <div class="flex flex-col flex-1 min-w-0">
                            <span class="text-xs font-medium text-slate-400">{{ __('home.add_dates') }}</span>
                            <span class="text-sm font-medium truncate" :class="date ? 'text-slate-900' : 'text-slate-400'" x-text="date || '{{ __('home.select_date') }}'"></span>
                        </div>
                    </button>
                    <!-- Date Dropdown -->
                    <div x-show="showDate"
                         @click.away="showDate = false"
                         x-transition
                         class="absolute top-full left-1/2 -translate-x-1/2 mt-2 bg-white rounded-xl shadow-xl border border-slate-200 p-3 z-50"
                         style="display: none;">
                        <input type="date"
                               name="date"
                               x-model="date"
                               @change="showDate = false"
                               class="px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500"
                               min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Divider -->
                <div class="hidden md:block w-px h-8 bg-slate-200"></div>
                <div class="md:hidden h-px bg-slate-100 mx-4"></div>

                <!-- Guests + Button -->
                <div class="relative md:w-[32%] flex items-center gap-2 pr-1">
                    <button type="button"
                            @click="showGuests = !showGuests; showDestination = false; showDate = false"
                            class="flex items-center gap-3 h-14 px-4 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer flex-1 min-w-0 text-left">
                        <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <div class="flex flex-col flex-1 min-w-0">
                            <span class="text-xs font-medium text-slate-400">{{ __('home.travelers') }}</span>
                            <span class="text-sm font-medium" :class="totalGuests > 1 ? 'text-slate-900' : 'text-slate-400'" x-text="totalGuests > 1 ? '{{ __('home.adults_label') }} ' + adults + (children > 0 ? ', {{ __('home.children_label') }} ' + children : '') : '{{ __('home.add_guests') }}'"></span>
                        </div>
                    </button>
                    <!-- Guests Dropdown -->
                    <div x-show="showGuests"
                         @click.away="showGuests = false"
                         x-transition
                         class="absolute top-full right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-slate-200 p-4 z-50"
                         style="display: none;">
                        <input type="hidden" name="adults" :value="adults">
                        <input type="hidden" name="children" :value="children">
                        <!-- Adults -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <span class="text-sm font-medium text-slate-900">{{ __('home.adults_label') }}</span>
                                <p class="text-xs text-slate-500 mt-0.5">{{ __('home.adults_age') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button"
                                        @click="adults = Math.max(1, adults - 1)"
                                        class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-500 hover:border-slate-400 transition-colors disabled:opacity-40 cursor-pointer"
                                        :disabled="adults <= 1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                    </svg>
                                </button>
                                <span class="text-sm font-semibold w-5 text-center" x-text="adults"></span>
                                <button type="button"
                                        @click="adults = Math.min(20, adults + 1)"
                                        class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-500 hover:border-slate-400 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <!-- Children -->
                        <div class="flex items-center justify-between pt-3">
                            <div>
                                <span class="text-sm font-medium text-slate-900">{{ __('home.children_label') }}</span>
                                <p class="text-xs text-slate-500 mt-0.5">{{ __('home.children_age') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button"
                                        @click="children = Math.max(0, children - 1)"
                                        class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-500 hover:border-slate-400 transition-colors disabled:opacity-40 cursor-pointer"
                                        :disabled="children <= 0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                    </svg>
                                </button>
                                <span class="text-sm font-semibold w-5 text-center" x-text="children"></span>
                                <button type="button"
                                        @click="children = Math.min(20, children + 1)"
                                        class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-500 hover:border-slate-400 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <button type="submit"
                            class="hidden md:flex w-12 h-12 rounded-xl bg-pink-500 hover:bg-pink-600 text-white items-center justify-center transition-colors flex-shrink-0 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Search Button -->
                <div class="md:hidden p-2">
                    <button type="submit"
                            class="w-full py-3.5 bg-pink-500 hover:bg-pink-600 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        {{ __('home.search_button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
