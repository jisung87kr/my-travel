<x-layouts.app>
    <x-slot:title>{{ __('support.about_us') }} - My Travel</x-slot:title>

    <!-- Hero Section -->
    <x-support.hero badge="About My Travel" :title="__('support.about_us')" :subtitle="__('support.about_description')">
        <x-slot:icon>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
        </x-slot:icon>
    </x-support.hero>

    <!-- Mission Section -->
    <section class="py-16 sm:py-24 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-pink-100 rounded-full blur-3xl opacity-50 -translate-y-1/2 translate-x-1/2"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center gap-3 justify-center mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-8 text-center">{{ __('support.our_mission') }}</h2>
                <p class="text-xl text-slate-600 leading-relaxed text-center">{{ __('support.mission_description') }}</p>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-16 sm:py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">{{ __('support.our_values') }}</h2>
                <p class="text-lg text-slate-600">우리가 추구하는 핵심 가치</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Value 1: Trust -->
                <div class="group relative bg-white rounded-3xl p-8 border border-slate-200 hover:border-pink-200 hover:shadow-2xl hover:shadow-pink-500/10 transition-all duration-500">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-pink-500/10 to-transparent rounded-bl-full"></div>
                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-pink-500/30">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">{{ __('support.value_trust') }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ __('support.value_trust_desc') }}</p>
                    </div>
                </div>

                <!-- Value 2: Community -->
                <div class="group relative bg-white rounded-3xl p-8 border border-slate-200 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full"></div>
                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-blue-500/30">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">{{ __('support.value_community') }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ __('support.value_community_desc') }}</p>
                    </div>
                </div>

                <!-- Value 3: Local Experience -->
                <div class="group relative bg-white rounded-3xl p-8 border border-slate-200 hover:border-emerald-200 hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-bl-full"></div>
                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-emerald-500/30">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">{{ __('support.value_local') }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ __('support.value_local_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 sm:py-20 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <!-- Stat 1 -->
                <div class="text-center">
                    <div class="text-5xl sm:text-6xl font-bold bg-gradient-to-r from-pink-500 to-rose-500 bg-clip-text text-transparent mb-3">500+</div>
                    <div class="text-slate-600 font-medium">{{ __('support.stat_tours') }}</div>
                </div>

                <!-- Stat 2 -->
                <div class="text-center">
                    <div class="text-5xl sm:text-6xl font-bold bg-gradient-to-r from-blue-500 to-indigo-500 bg-clip-text text-transparent mb-3">10K+</div>
                    <div class="text-slate-600 font-medium">{{ __('support.stat_customers') }}</div>
                </div>

                <!-- Stat 3 -->
                <div class="text-center">
                    <div class="text-5xl sm:text-6xl font-bold bg-gradient-to-r from-emerald-500 to-teal-500 bg-clip-text text-transparent mb-3">100+</div>
                    <div class="text-slate-600 font-medium">{{ __('support.stat_guides') }}</div>
                </div>

                <!-- Stat 4 -->
                <div class="text-center">
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <span class="text-5xl sm:text-6xl font-bold bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">4.9</span>
                        <svg class="w-8 h-8 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div class="text-slate-600 font-medium">{{ __('support.stat_rating') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner CTA -->
    <section class="py-16 sm:py-24 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-xl shadow-slate-900/5">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-pink-100 to-purple-100 rounded-full blur-3xl opacity-50 translate-x-1/2 -translate-y-1/2"></div>
                <div class="relative text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500 mb-6 shadow-lg shadow-pink-500/30">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-4">{{ __('support.want_to_partner') }}</h2>
                    <p class="text-lg text-slate-600 mb-8 max-w-2xl mx-auto">{{ __('support.partner_description') }}</p>
                    <a href="{{ route('support.contact') }}"
                       class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-pink-500 to-rose-500 text-white font-semibold rounded-xl hover:from-pink-600 hover:to-rose-600 transition-all shadow-lg shadow-pink-500/25 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                        </svg>
                        {{ __('support.contact_us') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
