<x-layouts.app>
    <x-slot:title>{{ __('support.terms_of_service') }} - My Travel</x-slot:title>

    <!-- Hero Section -->
    <x-support.hero badge="Legal" :title="__('support.terms_of_service')">
        <x-slot:icon>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
        </x-slot:icon>
        <div class="inline-flex items-center gap-2 text-white/60">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ __('support.last_updated') }}: 2024-01-01
        </div>
    </x-support.hero>

    <!-- Content -->
    <section class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Table of Contents -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl shadow-slate-900/5 mb-8">
                <h3 class="font-bold text-slate-900 mb-4">목차</h3>
                <nav class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <a href="#section-1" class="flex items-center gap-2 text-slate-600 hover:text-pink-500 transition-colors cursor-pointer">
                        <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-500 text-xs flex items-center justify-center">1</span>
                        {{ __('support.terms_section_1') }}
                    </a>
                    <a href="#section-2" class="flex items-center gap-2 text-slate-600 hover:text-pink-500 transition-colors cursor-pointer">
                        <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-500 text-xs flex items-center justify-center">2</span>
                        {{ __('support.terms_section_2') }}
                    </a>
                    <a href="#section-3" class="flex items-center gap-2 text-slate-600 hover:text-pink-500 transition-colors cursor-pointer">
                        <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-500 text-xs flex items-center justify-center">3</span>
                        {{ __('support.terms_section_3') }}
                    </a>
                    <a href="#section-4" class="flex items-center gap-2 text-slate-600 hover:text-pink-500 transition-colors cursor-pointer">
                        <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-500 text-xs flex items-center justify-center">4</span>
                        {{ __('support.terms_section_4') }}
                    </a>
                    <a href="#section-5" class="flex items-center gap-2 text-slate-600 hover:text-pink-500 transition-colors cursor-pointer">
                        <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-500 text-xs flex items-center justify-center">5</span>
                        {{ __('support.terms_section_5') }}
                    </a>
                    <a href="#section-6" class="flex items-center gap-2 text-slate-600 hover:text-pink-500 transition-colors cursor-pointer">
                        <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-500 text-xs flex items-center justify-center">6</span>
                        {{ __('support.terms_section_6') }}
                    </a>
                    <a href="#section-7" class="flex items-center gap-2 text-slate-600 hover:text-pink-500 transition-colors cursor-pointer">
                        <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-500 text-xs flex items-center justify-center">7</span>
                        {{ __('support.terms_section_7') }}
                    </a>
                    <a href="#section-8" class="flex items-center gap-2 text-slate-600 hover:text-pink-500 transition-colors cursor-pointer">
                        <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-500 text-xs flex items-center justify-center">8</span>
                        {{ __('support.terms_section_8') }}
                    </a>
                </nav>
            </div>

            <!-- Terms Sections -->
            <div class="space-y-6">
                <!-- Section 1 -->
                <div id="section-1" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl shadow-slate-900/5">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                            1
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 pt-1.5">{{ __('support.terms_section_1') }}</h2>
                    </div>
                    <p class="text-slate-600 leading-relaxed pl-14">{{ __('support.terms_section_1_content') }}</p>
                </div>

                <!-- Section 2 -->
                <div id="section-2" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl shadow-slate-900/5">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                            2
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 pt-1.5">{{ __('support.terms_section_2') }}</h2>
                    </div>
                    <p class="text-slate-600 leading-relaxed pl-14">{{ __('support.terms_section_2_content') }}</p>
                </div>

                <!-- Section 3: User Obligations -->
                <div id="section-3" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl shadow-slate-900/5">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                            3
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 pt-1.5">{{ __('support.terms_section_3') }}</h2>
                    </div>
                    <div class="pl-14">
                        <p class="text-slate-600 leading-relaxed mb-4">{{ __('support.terms_section_3_intro') }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="flex items-start gap-3 p-3 bg-emerald-50 rounded-xl">
                                <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-slate-700 text-sm">{{ __('support.terms_user_1') }}</span>
                            </div>
                            <div class="flex items-start gap-3 p-3 bg-emerald-50 rounded-xl">
                                <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-slate-700 text-sm">{{ __('support.terms_user_2') }}</span>
                            </div>
                            <div class="flex items-start gap-3 p-3 bg-emerald-50 rounded-xl">
                                <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-slate-700 text-sm">{{ __('support.terms_user_3') }}</span>
                            </div>
                            <div class="flex items-start gap-3 p-3 bg-emerald-50 rounded-xl">
                                <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-slate-700 text-sm">{{ __('support.terms_user_4') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Booking & Payment -->
                <div id="section-4" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl shadow-slate-900/5">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                            4
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 pt-1.5">{{ __('support.terms_section_4') }}</h2>
                    </div>
                    <div class="pl-14">
                        <p class="text-slate-600 leading-relaxed mb-4">{{ __('support.terms_section_4_intro') }}</p>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3 p-4 bg-amber-50 rounded-xl border border-amber-100">
                                <div class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs flex items-center justify-center flex-shrink-0 mt-0.5">1</div>
                                <span class="text-slate-700">{{ __('support.terms_booking_1') }}</span>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-amber-50 rounded-xl border border-amber-100">
                                <div class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs flex items-center justify-center flex-shrink-0 mt-0.5">2</div>
                                <span class="text-slate-700">{{ __('support.terms_booking_2') }}</span>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-amber-50 rounded-xl border border-amber-100">
                                <div class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs flex items-center justify-center flex-shrink-0 mt-0.5">3</div>
                                <span class="text-slate-700">{{ __('support.terms_booking_3') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5 -->
                <div id="section-5" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl shadow-slate-900/5">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-violet-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                            5
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 pt-1.5">{{ __('support.terms_section_5') }}</h2>
                    </div>
                    <p class="text-slate-600 leading-relaxed pl-14">{{ __('support.terms_section_5_content') }}</p>
                </div>

                <!-- Section 6 -->
                <div id="section-6" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl shadow-slate-900/5">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                            6
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 pt-1.5">{{ __('support.terms_section_6') }}</h2>
                    </div>
                    <p class="text-slate-600 leading-relaxed pl-14">{{ __('support.terms_section_6_content') }}</p>
                </div>

                <!-- Section 7 -->
                <div id="section-7" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl shadow-slate-900/5">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                            7
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 pt-1.5">{{ __('support.terms_section_7') }}</h2>
                    </div>
                    <p class="text-slate-600 leading-relaxed pl-14">{{ __('support.terms_section_7_content') }}</p>
                </div>

                <!-- Section 8: Contact -->
                <div id="section-8" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 font-bold flex-shrink-0">
                            8
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 pt-1.5">{{ __('support.terms_section_8') }}</h2>
                    </div>
                    <div class="pl-14">
                        <p class="text-slate-600 leading-relaxed mb-6">{{ __('support.terms_section_8_content') }}</p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="mailto:legal@mytravel.com" class="inline-flex items-center gap-3 px-5 py-3 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors cursor-pointer">
                                <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                                <span class="text-slate-700">legal@mytravel.com</span>
                            </a>
                            <a href="tel:1588-0000" class="inline-flex items-center gap-3 px-5 py-3 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors cursor-pointer">
                                <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                                <span class="text-slate-700">1588-0000</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
