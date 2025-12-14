<x-layouts.app>
    <x-slot:title>{{ __('support.faq') }} - My Travel</x-slot:title>

    <!-- Hero Section -->
    <x-support.hero badge="자주 묻는 질문" :title="__('support.faq')" :subtitle="__('support.faq_description')">
        <x-slot:icon>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
            </svg>
        </x-slot:icon>
    </x-support.hero>

    <!-- FAQ Content -->
    <section class="py-16 sm:py-20 bg-slate-50" x-data="{ activeCategory: 'booking', activeItem: null }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Category Tabs -->
            <div class="flex flex-wrap justify-center gap-3 mb-12">
                <button @click="activeCategory = 'booking'; activeItem = null"
                        :class="activeCategory === 'booking' ? 'bg-gradient-to-r from-pink-500 to-rose-500 text-white shadow-lg shadow-pink-500/25' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                        class="px-6 py-3 rounded-full font-medium transition-all duration-300 cursor-pointer">
                    {{ __('support.category_booking') }}
                </button>
                <button @click="activeCategory = 'payment'; activeItem = null"
                        :class="activeCategory === 'payment' ? 'bg-gradient-to-r from-pink-500 to-rose-500 text-white shadow-lg shadow-pink-500/25' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                        class="px-6 py-3 rounded-full font-medium transition-all duration-300 cursor-pointer">
                    {{ __('support.category_payment') }}
                </button>
                <button @click="activeCategory = 'cancellation'; activeItem = null"
                        :class="activeCategory === 'cancellation' ? 'bg-gradient-to-r from-pink-500 to-rose-500 text-white shadow-lg shadow-pink-500/25' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                        class="px-6 py-3 rounded-full font-medium transition-all duration-300 cursor-pointer">
                    {{ __('support.category_cancellation') }}
                </button>
                <button @click="activeCategory = 'account'; activeItem = null"
                        :class="activeCategory === 'account' ? 'bg-gradient-to-r from-pink-500 to-rose-500 text-white shadow-lg shadow-pink-500/25' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                        class="px-6 py-3 rounded-full font-medium transition-all duration-300 cursor-pointer">
                    {{ __('support.category_account') }}
                </button>
            </div>

            <!-- Booking FAQs -->
            <div id="booking" x-show="activeCategory === 'booking'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:shadow-slate-900/5 transition-shadow">
                    <button @click="activeItem = activeItem === 'b1' ? null : 'b1'"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer">
                        <span class="font-semibold text-slate-900 pr-4">{{ __('support.faq_b1_q') }}</span>
                        <div :class="activeItem === 'b1' ? 'bg-pink-500 text-white' : 'bg-slate-100 text-slate-400'"
                             class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 transition-transform duration-300" :class="activeItem === 'b1' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="activeItem === 'b1'" x-collapse class="px-6 pb-5">
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-slate-600 leading-relaxed pt-4">{{ __('support.faq_b1_a') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:shadow-slate-900/5 transition-shadow">
                    <button @click="activeItem = activeItem === 'b2' ? null : 'b2'"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer">
                        <span class="font-semibold text-slate-900 pr-4">{{ __('support.faq_b2_q') }}</span>
                        <div :class="activeItem === 'b2' ? 'bg-pink-500 text-white' : 'bg-slate-100 text-slate-400'"
                             class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 transition-transform duration-300" :class="activeItem === 'b2' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="activeItem === 'b2'" x-collapse class="px-6 pb-5">
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-slate-600 leading-relaxed pt-4">{{ __('support.faq_b2_a') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:shadow-slate-900/5 transition-shadow">
                    <button @click="activeItem = activeItem === 'b3' ? null : 'b3'"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer">
                        <span class="font-semibold text-slate-900 pr-4">{{ __('support.faq_b3_q') }}</span>
                        <div :class="activeItem === 'b3' ? 'bg-pink-500 text-white' : 'bg-slate-100 text-slate-400'"
                             class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 transition-transform duration-300" :class="activeItem === 'b3' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="activeItem === 'b3'" x-collapse class="px-6 pb-5">
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-slate-600 leading-relaxed pt-4">{{ __('support.faq_b3_a') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment FAQs -->
            <div id="payment" x-show="activeCategory === 'payment'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:shadow-slate-900/5 transition-shadow">
                    <button @click="activeItem = activeItem === 'p1' ? null : 'p1'"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer">
                        <span class="font-semibold text-slate-900 pr-4">{{ __('support.faq_p1_q') }}</span>
                        <div :class="activeItem === 'p1' ? 'bg-pink-500 text-white' : 'bg-slate-100 text-slate-400'"
                             class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 transition-transform duration-300" :class="activeItem === 'p1' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="activeItem === 'p1'" x-collapse class="px-6 pb-5">
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-slate-600 leading-relaxed pt-4">{{ __('support.faq_p1_a') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:shadow-slate-900/5 transition-shadow">
                    <button @click="activeItem = activeItem === 'p2' ? null : 'p2'"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer">
                        <span class="font-semibold text-slate-900 pr-4">{{ __('support.faq_p2_q') }}</span>
                        <div :class="activeItem === 'p2' ? 'bg-pink-500 text-white' : 'bg-slate-100 text-slate-400'"
                             class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 transition-transform duration-300" :class="activeItem === 'p2' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="activeItem === 'p2'" x-collapse class="px-6 pb-5">
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-slate-600 leading-relaxed pt-4">{{ __('support.faq_p2_a') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cancellation FAQs -->
            <div id="cancellation" x-show="activeCategory === 'cancellation'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:shadow-slate-900/5 transition-shadow">
                    <button @click="activeItem = activeItem === 'c1' ? null : 'c1'"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer">
                        <span class="font-semibold text-slate-900 pr-4">{{ __('support.faq_c1_q') }}</span>
                        <div :class="activeItem === 'c1' ? 'bg-pink-500 text-white' : 'bg-slate-100 text-slate-400'"
                             class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 transition-transform duration-300" :class="activeItem === 'c1' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="activeItem === 'c1'" x-collapse class="px-6 pb-5">
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-slate-600 leading-relaxed pt-4">{{ __('support.faq_c1_a') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:shadow-slate-900/5 transition-shadow">
                    <button @click="activeItem = activeItem === 'c2' ? null : 'c2'"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer">
                        <span class="font-semibold text-slate-900 pr-4">{{ __('support.faq_c2_q') }}</span>
                        <div :class="activeItem === 'c2' ? 'bg-pink-500 text-white' : 'bg-slate-100 text-slate-400'"
                             class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 transition-transform duration-300" :class="activeItem === 'c2' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="activeItem === 'c2'" x-collapse class="px-6 pb-5">
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-slate-600 leading-relaxed pt-4">{{ __('support.faq_c2_a') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Cancellation Policy Link -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-900 mb-1">더 자세한 정보가 필요하신가요?</h4>
                            <p class="text-sm text-slate-600">전체 취소/환불 정책을 확인해 보세요.</p>
                        </div>
                        <a href="{{ route('support.cancellation-policy') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-amber-600 font-medium rounded-xl border border-amber-200 hover:bg-amber-50 transition-colors cursor-pointer">
                            자세히
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account FAQs -->
            <div id="account" x-show="activeCategory === 'account'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:shadow-slate-900/5 transition-shadow">
                    <button @click="activeItem = activeItem === 'a1' ? null : 'a1'"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer">
                        <span class="font-semibold text-slate-900 pr-4">{{ __('support.faq_a1_q') }}</span>
                        <div :class="activeItem === 'a1' ? 'bg-pink-500 text-white' : 'bg-slate-100 text-slate-400'"
                             class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 transition-transform duration-300" :class="activeItem === 'a1' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="activeItem === 'a1'" x-collapse class="px-6 pb-5">
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-slate-600 leading-relaxed pt-4">{{ __('support.faq_a1_a') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:shadow-slate-900/5 transition-shadow">
                    <button @click="activeItem = activeItem === 'a2' ? null : 'a2'"
                            class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer">
                        <span class="font-semibold text-slate-900 pr-4">{{ __('support.faq_a2_q') }}</span>
                        <div :class="activeItem === 'a2' ? 'bg-pink-500 text-white' : 'bg-slate-100 text-slate-400'"
                             class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 transition-transform duration-300" :class="activeItem === 'a2' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="activeItem === 'a2'" x-collapse class="px-6 pb-5">
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-slate-600 leading-relaxed pt-4">{{ __('support.faq_a2_a') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-16 bg-white border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-4">{{ __('support.cant_find_answer') }}</h2>
            <p class="text-slate-500 mb-8 max-w-lg mx-auto">{{ __('support.contact_team_description') }}</p>
            <a href="{{ route('support.contact') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-pink-500 text-white font-semibold rounded-xl hover:bg-pink-600 transition-colors cursor-pointer">
                {{ __('support.contact_us') }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </section>
</x-layouts.app>
