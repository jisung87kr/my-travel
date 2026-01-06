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

                    <!-- Dropdown -->
                    <div x-show="showDestination"
                         @click.away="showDestination = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         class="absolute top-full left-0 mt-4 w-full md:w-[350px] bg-white rounded-3xl shadow-[0_8px_28px_rgba(0,0,0,0.28)] border border-gray-100 py-6 px-6 z-50 overflow-hidden"
                         style="display: none;">

                        <div class="relative mb-4">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                            <input type="text"
                                   name="keyword"
                                   x-model="destination"
                                   placeholder="{{ __('home.search_placeholder') }}"
                                   class="w-full pl-12 pr-4 py-3 text-base bg-gray-100 border-none rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:bg-white transition-all font-medium"
                                   @keydown.enter="showDestination = false; showDate = true">
                        </div>

                        @if($regions->count() > 0)
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 ml-1">{{ __('home.popular_destinations') }}</p>
                            <div class="space-y-1">
                                @foreach($regions->take(5) as $region)
                                <button type="button"
                                        @click="destination = '{{ $region['name'] }}'; showDestination = false; showDate = true"
                                        class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-white group-hover:shadow-sm transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-gray-700 group-hover:text-gray-900">{{ $region['name'] }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
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
                                             class="absolute top-full left-1/2 -translate-x-1/2 mt-4 bg-white rounded-3xl shadow-[0_8px_28px_rgba(0,0,0,0.28)] border border-gray-100 p-6 z-50 overflow-hidden min-w-[340px]"
                                             style="display: none;"
                                             x-data="{
                                                 currentDate: new Date(),
                                                 weekdays: ['일', '월', '화', '수', '목', '금', '토'],
                                                 get monthName() {
                                                     return this.currentDate.toLocaleString('ko-KR', { month: 'long', year: 'numeric' });
                                                 },
                                                 get daysInMonth() {
                                                     return new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 0).getDate();
                                                 },
                                                 get firstDayOfMonth() {
                                                     return new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1).getDay();
                                                 },
                                                 prevMonth() {
                                                     this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1);
                                                 },
                                                 nextMonth() {
                                                     this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
                                                 },
                                                 isSelected(day) {
                                                     if (!date) return false;
                                                     const d = new Date(date);
                                                     return d.getDate() === day &&
                                                            d.getMonth() === this.currentDate.getMonth() &&
                                                            d.getFullYear() === this.currentDate.getFullYear();
                                                 },
                                                 isToday(day) {
                                                     const today = new Date();
                                                     return day === today.getDate() &&
                                                            this.currentDate.getMonth() === today.getMonth() &&
                                                            this.currentDate.getFullYear() === today.getFullYear();
                                                 },
                                                 isPast(day) {
                                                     const today = new Date();
                                                     today.setHours(0, 0, 0, 0);
                                                     const checkDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), day);
                                                     return checkDate < today;
                                                 },
                                                 selectDate(day) {
                                                     const selected = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), day);
                                                     // Simple YYYY-MM-DD formatting
                                                     const offset = selected.getTimezoneOffset();
                                                     const localDate = new Date(selected.getTime() - (offset*60*1000));
                                                     date = localDate.toISOString().split('T')[0];
                                                     showDate = false;
                                                     showGuests = true;
                                                 }
                                             }">

                                            <!-- Header -->
                                            <div class="flex items-center justify-between mb-4">
                                                <button type="button" @click="prevMonth()" class="p-2 rounded-full hover:bg-gray-100 text-gray-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                                </button>
                                                <span class="text-base font-bold text-gray-900" x-text="monthName"></span>
                                                <button type="button" @click="nextMonth()" class="p-2 rounded-full hover:bg-gray-100 text-gray-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </button>
                                            </div>

                                            <!-- Grid -->
                                            <div class="grid grid-cols-7 mb-2">
                                                <template x-for="day in weekdays">
                                                    <div class="text-center text-xs font-bold text-gray-400 py-1" x-text="day"></div>
                                                </template>
                                            </div>
                                            <div class="grid grid-cols-7 gap-y-1">
                                                <!-- Empty slots -->
                                                <template x-for="i in firstDayOfMonth">
                                                    <div></div>
                                                </template>
                                                <!-- Days -->
                                                <template x-for="day in daysInMonth">
                                                    <div class="aspect-square flex items-center justify-center relative">
                                                        <button type="button"
                                                                @click="!isPast(day) && selectDate(day)"
                                                                class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all relative z-10"
                                                                :class="{
                                                                    'bg-black text-white hover:bg-gray-800': isSelected(day),
                                                                    'hover:bg-gray-100 text-gray-900': !isSelected(day) && !isPast(day),
                                                                    'text-gray-300 cursor-not-allowed': isPast(day),
                                                                    'ring-1 ring-black': isToday(day) && !isSelected(day)
                                                                }"
                                                                :disabled="isPast(day)">
                                                            <span x-text="day"></span>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Hidden Input for Form Submission -->
                                            <input type="hidden" name="date" x-model="date">
                                        </div>                </div>


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
                         class="absolute top-full right-0 mt-4 w-[350px] bg-white rounded-3xl shadow-[0_8px_28px_rgba(0,0,0,0.28)] border border-gray-100 p-8 z-50 cursor-auto"
                         style="display: none;">

                        <input type="hidden" name="adults" :value="adults">
                        <input type="hidden" name="children" :value="children">

                        <!-- Adults -->
                        <div class="flex items-center justify-between pb-6 border-b border-gray-100">
                            <div>
                                <span class="text-base font-bold text-gray-900 block">{{ __('home.adults_label') }}</span>
                                <span class="text-sm text-gray-500">{{ __('home.adults_age') }}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <button type="button"
                                        @click="adults = Math.max(1, adults - 1)"
                                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-800 hover:text-gray-800 transition-colors disabled:opacity-30 disabled:hover:border-gray-300 disabled:hover:text-gray-500"
                                        :disabled="adults <= 1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" /></svg>
                                </button>
                                <span class="text-base font-semibold w-6 text-center" x-text="adults"></span>
                                <button type="button"
                                        @click="adults = Math.min(20, adults + 1)"
                                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-800 hover:text-gray-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Children -->
                        <div class="flex items-center justify-between pt-6">
                            <div>
                                <span class="text-base font-bold text-gray-900 block">{{ __('home.children_label') }}</span>
                                <span class="text-sm text-gray-500">{{ __('home.children_age') }}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <button type="button"
                                        @click="children = Math.max(0, children - 1)"
                                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-800 hover:text-gray-800 transition-colors disabled:opacity-30 disabled:hover:border-gray-300 disabled:hover:text-gray-500"
                                        :disabled="children <= 0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" /></svg>
                                </button>
                                <span class="text-base font-semibold w-6 text-center" x-text="children"></span>
                                <button type="button"
                                        @click="children = Math.min(20, children + 1)"
                                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-gray-800 hover:text-gray-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Large Search Button -->
                    <button type="submit"
                            class="hidden md:flex items-center justify-center gap-2 h-12 px-6 rounded-full bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white shadow-lg transition-all hover:scale-105 active:scale-95 ml-2">
                        <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <span class="font-bold text-sm">{{ __('home.search_button') }}</span>
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
