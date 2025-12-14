<x-layouts.app>
    <x-slot:title>{{ __('support.cancellation_policy') }} - My Travel</x-slot:title>

    <!-- Hero Section -->
    <x-support.hero badge="취소/환불 정책" :title="__('support.cancellation_policy')" :subtitle="__('support.cancellation_policy_description')">
        <x-slot:icon>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
        </x-slot:icon>
    </x-support.hero>

    <!-- Policy Content -->
    <section class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Refund Policy Visual Cards -->
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">{{ __('support.refund_policy') }}</h2>
                        <p class="text-sm text-slate-500">취소 시점에 따른 환불 비율</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- 7+ Days -->
                    <div class="group relative bg-white rounded-3xl p-6 border border-slate-200 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300">
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 font-bold text-sm">100%</span>
                        </div>
                        <div class="mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-1">{{ __('support.7_days_before') }}</h3>
                        <p class="text-emerald-600 font-bold text-lg">{{ __('support.full_refund') }}</p>
                    </div>

                    <!-- 3-6 Days -->
                    <div class="group relative bg-white rounded-3xl p-6 border border-slate-200 hover:border-amber-200 hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-300">
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-amber-100 text-amber-600 font-bold text-sm">70%</span>
                        </div>
                        <div class="mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-1">{{ __('support.3_6_days_before') }}</h3>
                        <p class="text-amber-600 font-bold text-lg">{{ __('support.70_refund') }}</p>
                    </div>

                    <!-- 1-2 Days -->
                    <div class="group relative bg-white rounded-3xl p-6 border border-slate-200 hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/10 transition-all duration-300">
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-orange-100 text-orange-600 font-bold text-sm">50%</span>
                        </div>
                        <div class="mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-1">{{ __('support.1_2_days_before') }}</h3>
                        <p class="text-orange-600 font-bold text-lg">{{ __('support.50_refund') }}</p>
                    </div>

                    <!-- Same Day -->
                    <div class="group relative bg-white rounded-3xl p-6 border border-slate-200 hover:border-red-200 hover:shadow-xl hover:shadow-red-500/10 transition-all duration-300">
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-600 font-bold text-sm">0%</span>
                        </div>
                        <div class="mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-1">{{ __('support.same_day') }}</h3>
                        <p class="text-red-600 font-bold text-lg">{{ __('support.no_refund') }}</p>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-3xl p-6 sm:p-8 border border-amber-200/50 mb-12">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-amber-900 text-lg mb-3">{{ __('support.important_notes') }}</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3 text-amber-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-2 flex-shrink-0"></span>
                                <span>{{ __('support.note_1') }}</span>
                            </li>
                            <li class="flex items-start gap-3 text-amber-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-2 flex-shrink-0"></span>
                                <span>{{ __('support.note_2') }}</span>
                            </li>
                            <li class="flex items-start gap-3 text-amber-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-2 flex-shrink-0"></span>
                                <span>{{ __('support.note_3') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- How to Cancel -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl shadow-slate-900/5 mb-12">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ __('support.how_to_cancel') }}</h2>
                        <p class="text-sm text-slate-500">간단한 3단계로 취소가 가능합니다</p>
                    </div>
                </div>

                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-6 top-12 bottom-12 w-0.5 bg-gradient-to-b from-blue-500 via-indigo-500 to-purple-500 hidden sm:block"></div>

                    <div class="space-y-8">
                        <!-- Step 1 -->
                        <div class="relative flex gap-6">
                            <div class="relative z-10 w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg flex-shrink-0 shadow-lg shadow-blue-500/30">
                                1
                            </div>
                            <div class="flex-1 bg-slate-50 rounded-2xl p-5 hover:bg-slate-100 transition-colors">
                                <h4 class="font-bold text-slate-900 mb-2">{{ __('support.cancel_step_1_title') }}</h4>
                                <p class="text-slate-600">{{ __('support.cancel_step_1_desc') }}</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="relative flex gap-6">
                            <div class="relative z-10 w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg flex-shrink-0 shadow-lg shadow-indigo-500/30">
                                2
                            </div>
                            <div class="flex-1 bg-slate-50 rounded-2xl p-5 hover:bg-slate-100 transition-colors">
                                <h4 class="font-bold text-slate-900 mb-2">{{ __('support.cancel_step_2_title') }}</h4>
                                <p class="text-slate-600">{{ __('support.cancel_step_2_desc') }}</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="relative flex gap-6">
                            <div class="relative z-10 w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-lg flex-shrink-0 shadow-lg shadow-purple-500/30">
                                3
                            </div>
                            <div class="flex-1 bg-slate-50 rounded-2xl p-5 hover:bg-slate-100 transition-colors">
                                <h4 class="font-bold text-slate-900 mb-2">{{ __('support.cancel_step_3_title') }}</h4>
                                <p class="text-slate-600">{{ __('support.cancel_step_3_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No-Show Policy -->
            <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-3xl p-6 sm:p-8 border border-red-200/50 mb-12">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-rose-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-900 text-lg mb-2">{{ __('support.no_show_policy') }}</h3>
                        <p class="text-red-800">{{ __('support.no_show_description') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact CTA -->
            <div class="text-center pt-8 border-t border-slate-200">
                <p class="text-slate-500 mb-6 text-lg">{{ __('support.cancellation_questions') }}</p>
                <a href="{{ route('support.contact') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 bg-pink-500 text-white font-semibold rounded-xl hover:bg-pink-600 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                    </svg>
                    {{ __('support.contact_us') }}
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
