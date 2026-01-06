@props([
    'compact' => false,
])

<div {{ $attributes->merge(['class' => $compact ? 'bg-white rounded-xl shadow-xl border border-gray-100 p-4 z-50 min-w-[300px]' : 'bg-white rounded-3xl shadow-[0_8px_28px_rgba(0,0,0,0.28)] border border-gray-100 p-6 z-50 overflow-hidden min-w-[340px]']) }}
     x-data="{
         currentDate: new Date(),
         weekdays: {!! app()->getLocale() == 'ko' ? "['일', '월', '화', '수', '목', '금', '토']" : "['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" !!},
         get monthName() {
             return this.currentDate.toLocaleString('{{ app()->getLocale() == 'ko' ? 'ko-KR' : 'en-US' }}', { month: 'long', year: 'numeric' });
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

    <!-- Weekdays -->
    <div class="grid grid-cols-7 mb-2">
        <template x-for="day in weekdays">
            <div class="text-center text-xs font-bold text-gray-400 py-1" x-text="day"></div>
        </template>
    </div>

    <!-- Days Grid -->
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
                        class="{{ $compact ? 'w-8 h-8' : 'w-10 h-10' }} rounded-full flex items-center justify-center text-sm font-semibold transition-all relative z-10"
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

    <!-- Hidden Input -->
    <input type="hidden" name="date" x-model="date">
</div>
