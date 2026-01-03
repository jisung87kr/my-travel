<x-layouts.app :title="__('booking.title')">
    <!-- Progress Bar -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-center gap-2 sm:gap-4">
                <!-- Step 1 - Active -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl font-semibold shadow-lg shadow-pink-500/25">
                        1
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-900 hidden sm:inline">{{ __('booking.step_booking_info') }}</span>
                </div>

                <div class="w-12 sm:w-24 h-0.5 bg-gray-200 rounded-full"></div>

                <!-- Step 2 -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-400 rounded-xl font-semibold">
                        2
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-400 hidden sm:inline">{{ __('booking.step_complete') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 min-h-screen" x-data="{
        schedules: {{ Js::from($product->schedules) }},
        selectedScheduleId: {{ request()->query('schedule_id') ? (int) request()->query('schedule_id') : 'null' }},
        adults: {{ (int) request()->query('adults', 1) ?: 1 }},
        children: {{ (int) request()->query('children', 0) }},
        adultPrice: {{ $adultPrice }},
        childPrice: {{ $childPrice }},
        name: '{{ auth()->user()->name ?? '' }}',
        email: '{{ auth()->user()->email ?? '' }}',
        phone: '{{ auth()->user()->phone ?? '' }}',
        notes: '',
        submitting: false,
        translations: {
            spotsAvailable: '{{ __('booking.spots_available') }}',
            dateRequired: '{{ __('booking.date_required') }}',
            adults: '{{ __('booking.adults') }}',
            children: '{{ __('booking.children') }}',
            processing: '{{ __('booking.processing') }}',
            submit: '{{ __('booking.submit') }}'
        },

        get selectedSchedule() {
            return this.schedules.find(s => s.id === this.selectedScheduleId);
        },
        get maxPersons() {
            return this.selectedSchedule?.available_count ?? 99;
        },
        get totalPersons() {
            return this.adults + this.children;
        },
        get totalPrice() {
            return (this.adults * this.adultPrice) + (this.children * this.childPrice);
        },
        get canSubmit() {
            return this.selectedScheduleId && this.adults > 0 && this.totalPersons <= this.maxPersons && this.name && this.email && this.phone && !this.submitting;
        },
        formatPrice(price) {
            return price.toLocaleString();
        },
        selectSchedule(schedule) {
            this.selectedScheduleId = schedule.id;
            if (this.totalPersons > schedule.available_count) {
                this.adults = 1;
                this.children = 0;
            }
        },
        getGuestText() {
            let text = this.translations.adults + ' ' + this.adults + '{{ __('product.persons_unit') }}';
            if (this.children > 0) {
                text += ', ' + this.translations.children + ' ' + this.children + '{{ __('product.persons_unit') }}';
            }
            return text;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form action="{{ route('bookings.store') }}" method="POST" @submit="submitting = true">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="schedule_id" :value="selectedScheduleId">
                <input type="hidden" name="adult_count" :value="adults">
                <input type="hidden" name="child_count" :value="children">
                <input type="hidden" name="notes" :value="notes">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content - Booking Form -->
                    <div class="lg:col-span-2 order-2 lg:order-1">
                        <div class="space-y-6">
                            <!-- Date Selection -->
                            <div class="bg-white rounded-2xl shadow-sm p-6">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-10 h-10 rounded-xl bg-pink-50 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-bold text-gray-900">{{ __('booking.select_date') }}</h2>
                                        <p class="text-sm text-gray-500">{{ __('booking.select_date_desc') }}</p>
                                    </div>
                                </div>

                                <template x-if="schedules.length === 0">
                                    <div class="p-4 rounded-xl bg-gray-50 border-2 border-dashed border-gray-200 text-center text-gray-500">
                                        {{ __('booking.no_available_dates') }}
                                    </div>
                                </template>

                                <template x-if="schedules.length > 0">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <template x-for="schedule in schedules" :key="schedule.id">
                                            <button type="button"
                                                    @click="selectSchedule(schedule)"
                                                    class="p-4 rounded-xl border-2 text-left transition-all duration-200"
                                                    :class="selectedScheduleId === schedule.id
                                                        ? 'border-pink-500 bg-pink-50 ring-2 ring-pink-500/20'
                                                        : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-semibold text-gray-900" x-text="schedule.starts_at_human"></span>
                                                    <span class="text-xs px-2 py-1 rounded-full"
                                                          :class="schedule.available_count <= 5 ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-600'"
                                                          x-text="schedule.available_count + ' ' + translations.spotsAvailable"></span>
                                                </div>
                                            </button>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <!-- Guest Count -->
                            <div class="bg-white rounded-2xl shadow-sm p-6">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-bold text-gray-900">{{ __('booking.select_persons') }}</h2>
                                        <p class="text-sm text-gray-500">{{ __('booking.select_persons_desc') }}</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <!-- Adults -->
                                    <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ __('booking.adults') }}</p>
                                            <p class="text-sm text-gray-500">{{ __('booking.adults_age') }}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button"
                                                    @click="if(adults > 1) adults--"
                                                    :disabled="adults <= 1"
                                                    class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer flex items-center justify-center disabled:opacity-40 disabled:cursor-not-allowed">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                </svg>
                                            </button>
                                            <span class="w-8 text-center font-semibold text-gray-900" x-text="adults"></span>
                                            <button type="button"
                                                    @click="if(totalPersons < maxPersons) adults++"
                                                    :disabled="totalPersons >= maxPersons"
                                                    class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer flex items-center justify-center disabled:opacity-40 disabled:cursor-not-allowed">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Children -->
                                    <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ __('booking.children') }}</p>
                                            <p class="text-sm text-gray-500">{{ __('booking.children_age') }}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button"
                                                    @click="if(children > 0) children--"
                                                    :disabled="children <= 0"
                                                    class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer flex items-center justify-center disabled:opacity-40 disabled:cursor-not-allowed">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                </svg>
                                            </button>
                                            <span class="w-8 text-center font-semibold text-gray-900" x-text="children"></span>
                                            <button type="button"
                                                    @click="if(totalPersons < maxPersons) children++"
                                                    :disabled="totalPersons >= maxPersons"
                                                    class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer flex items-center justify-center disabled:opacity-40 disabled:cursor-not-allowed">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Max persons warning -->
                                    <template x-if="selectedScheduleId && totalPersons >= maxPersons">
                                        <p class="text-xs text-orange-600 bg-orange-50 px-3 py-2 rounded-lg">
                                            {{ __('booking.max_capacity_warning') }}
                                        </p>
                                    </template>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="bg-white rounded-2xl shadow-sm p-6">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-bold text-gray-900">{{ __('booking.booker_info') }}</h2>
                                        <p class="text-sm text-gray-500">{{ __('booking.booker_info_desc') }}</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('booking.contact_name') }} <span class="text-red-500">*</span></label>
                                        <input type="text"
                                               name="name"
                                               x-model="name"
                                               placeholder="{{ __('booking.name_placeholder') }}"
                                               required
                                               class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('booking.contact_email') }} <span class="text-red-500">*</span></label>
                                        <input type="email"
                                               name="email"
                                               x-model="email"
                                               placeholder="email@example.com"
                                               required
                                               class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('booking.contact_phone') }} <span class="text-red-500">*</span></label>
                                        <input type="tel"
                                               name="phone"
                                               x-model="phone"
                                               placeholder="{{ __('booking.phone_placeholder') }}"
                                               required
                                               class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('booking.special_request') }}</label>
                                        <textarea rows="3"
                                                  x-model="notes"
                                                  placeholder="{{ __('booking.special_request_placeholder') }}"
                                                  class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors resize-none"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button (Mobile) -->
                            <div class="lg:hidden">
                                <button type="submit"
                                        :disabled="!canSubmit"
                                        class="w-full py-4 px-6 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                                    <span x-text="submitting ? translations.processing : translations.submit"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar - Order Summary (Sticky) -->
                    <div class="lg:col-span-1 order-1 lg:order-2">
                        <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-24">
                            <!-- Product Image -->
                            <div class="relative mb-4 rounded-xl overflow-hidden">
                                @if($product->images->first())
                                    <img src="{{ $product->images->first()->url }}"
                                         alt="{{ $translation?->title }}"
                                         class="w-full h-40 object-cover">
                                @else
                                    <div class="w-full h-40 bg-gray-100 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            </div>

                            <!-- Product Name -->
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                {{ $translation?->title ?? $product->slug }}
                            </h3>

                            <!-- Date & Guest Info -->
                            <div class="mb-4 pb-4 border-b border-gray-100">
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 mb-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    <span class="text-sm font-medium" :class="selectedSchedule ? 'text-gray-900' : 'text-gray-400'" x-text="selectedSchedule?.starts_at_human ?? translations.dateRequired"></span>
                                </div>
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900" x-text="getGuestText()"></span>
                                </div>
                            </div>

                            <!-- Price Breakdown -->
                            <div class="mb-4 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600" x-text="translations.adults + ' ' + adults + '{{ __('product.persons_unit') }} x ₩' + formatPrice(adultPrice)"></span>
                                    <span class="font-medium text-gray-900">₩<span x-text="formatPrice(adults * adultPrice)"></span></span>
                                </div>
                                <template x-if="children > 0">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600" x-text="translations.children + ' ' + children + '{{ __('product.persons_unit') }} x ₩' + formatPrice(childPrice)"></span>
                                        <span class="font-medium text-gray-900">₩<span x-text="formatPrice(children * childPrice)"></span></span>
                                    </div>
                                </template>
                            </div>

                            <!-- Total Price -->
                            <div class="pt-4 border-t border-gray-100">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-base font-bold text-gray-900">{{ __('booking.total_amount') }}</span>
                                    <span class="text-2xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">₩<span x-text="formatPrice(totalPrice)"></span></span>
                                </div>

                                <!-- Submit Button (Desktop) -->
                                <button type="submit"
                                        :disabled="!canSubmit"
                                        class="hidden lg:block w-full py-3.5 px-6 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
                                    <span x-text="submitting ? translations.processing : translations.submit"></span>
                                </button>
                            </div>

                            <!-- Trust Badges -->
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ __('booking.free_cancellation') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ __('booking.instant_confirmation') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ __('booking.secure_payment') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
