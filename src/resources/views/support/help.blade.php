<x-layouts.app>
    <x-slot:title>{{ __('support.help_center') }} - My Travel</x-slot:title>

    <!-- Hero Section -->
    <x-support.hero badge="24시간 지원 가능" :title="__('support.help_center')" :subtitle="__('support.help_description')">
        <x-slot:icon>
            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
        </x-slot:icon>

    </x-support.hero>

    <!-- Bento Grid Quick Links -->
    <section class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-3">무엇을 도와드릴까요?</h2>
                <p class="text-slate-500">카테고리를 선택하면 관련 도움말을 확인할 수 있습니다</p>
            </div>

            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
                <!-- Large Card - Booking -->
                <a href="{{ route('support.faq') }}#booking"
                   class="group relative md:col-span-2 lg:col-span-2 lg:row-span-2 bg-gradient-to-br from-pink-500 to-rose-600 rounded-3xl p-8 overflow-hidden cursor-pointer hover:shadow-2xl hover:shadow-pink-500/25 transition-all duration-500">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative h-full flex flex-col justify-between min-h-[280px]">
                        <div>
                            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-3">{{ __('support.booking_help') }}</h3>
                            <p class="text-white/80 text-lg">{{ __('support.booking_help_desc') }}</p>
                        </div>
                        <div class="flex items-center gap-2 text-white/70 group-hover:text-white transition-colors">
                            <span>자세히 보기</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Payment Card -->
                <a href="{{ route('support.faq') }}#payment"
                   class="group relative bg-white rounded-3xl p-6 border border-slate-200 hover:border-green-200 hover:shadow-xl hover:shadow-green-500/10 transition-all duration-300 cursor-pointer">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">{{ __('support.payment_help') }}</h3>
                    <p class="text-slate-500 text-sm">{{ __('support.payment_help_desc') }}</p>
                </a>

                <!-- Account Card -->
                <a href="{{ route('support.faq') }}#account"
                   class="group relative bg-white rounded-3xl p-6 border border-slate-200 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 cursor-pointer">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">{{ __('support.account_help') }}</h3>
                    <p class="text-slate-500 text-sm">{{ __('support.account_help_desc') }}</p>
                </a>

                <!-- Cancellation Card - Wide -->
                <a href="{{ route('support.cancellation-policy') }}"
                   class="group relative lg:col-span-2 bg-gradient-to-r from-amber-50 to-orange-50 rounded-3xl p-6 border border-amber-100 hover:border-amber-200 hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-300 cursor-pointer">
                    <div class="flex items-start gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-900 text-lg mb-2">{{ __('support.cancellation_help') }}</h3>
                            <p class="text-slate-500 text-sm mb-3">{{ __('support.cancellation_help_desc') }}</p>
                            <div class="flex items-center gap-4 text-sm">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    7일 전 100% 환불
                                </span>
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-100 text-amber-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    3-6일 전 70%
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Popular Topics with Modern Cards -->
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-2">{{ __('support.popular_topics') }}</h2>
                    <p class="text-slate-500">가장 많이 찾는 질문들을 모았습니다</p>
                </div>
                <a href="{{ route('support.faq') }}" class="hidden sm:inline-flex items-center gap-2 text-pink-500 font-medium hover:text-pink-600 transition-colors cursor-pointer">
                    전체 보기
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $topics = [
                        ['icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5', 'text' => __('support.faq_how_to_book'), 'link' => '#booking', 'color' => 'pink'],
                        ['icon' => 'M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3', 'text' => __('support.faq_cancellation'), 'link' => 'cancellation', 'color' => 'amber'],
                        ['icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z', 'text' => __('support.faq_payment_methods'), 'link' => '#payment', 'color' => 'green'],
                        ['icon' => 'M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z', 'text' => __('support.faq_change_password'), 'link' => '#account', 'color' => 'blue'],
                    ];
                @endphp

                @foreach($topics as $topic)
                <a href="{{ str_starts_with($topic['link'], '#') ? route('support.faq') . $topic['link'] : route('support.cancellation-policy') }}"
                   class="group flex items-center gap-4 p-5 bg-slate-50 rounded-2xl hover:bg-{{ $topic['color'] }}-50 border border-transparent hover:border-{{ $topic['color'] }}-100 transition-all duration-300 cursor-pointer">
                    <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center group-hover:shadow-md transition-shadow">
                        <svg class="w-6 h-6 text-{{ $topic['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $topic['icon'] }}" />
                        </svg>
                    </div>
                    <span class="flex-1 font-medium text-slate-700 group-hover:text-slate-900 transition-colors">{{ $topic['text'] }}</span>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-{{ $topic['color'] }}-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
                @endforeach
            </div>

            <!-- Mobile View All -->
            <div class="mt-6 text-center sm:hidden">
                <a href="{{ route('support.faq') }}" class="inline-flex items-center gap-2 text-pink-500 font-medium cursor-pointer">
                    전체 FAQ 보기
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-16 sm:py-20 bg-white border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-14 h-14 rounded-2xl bg-pink-100 flex items-center justify-center mx-auto mb-6">
                <svg class="w-7 h-7 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                </svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-4">{{ __('support.still_need_help') }}</h2>
            <p class="text-slate-500 mb-8 max-w-lg mx-auto">{{ __('support.contact_team_description') }}</p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('support.contact') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-pink-500 text-white font-semibold rounded-xl hover:bg-pink-600 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                    </svg>
                    {{ __('support.contact_us') }}
                </a>
                <a href="tel:1588-0000"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                    1588-0000
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
