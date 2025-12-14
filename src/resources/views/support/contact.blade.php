<x-layouts.app>
    <x-slot:title>{{ __('support.contact_us') }} - My Travel</x-slot:title>

    <!-- Hero Section -->
    <x-support.hero badge="고객지원" :title="__('support.contact_us')" :subtitle="__('support.contact_description')">
        <x-slot:icon>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
            </svg>
        </x-slot:icon>
    </x-support.hero>

    <!-- Contact Content -->
    <section class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Contact Methods - Bento Style -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Phone Card -->
                    <div class="group bg-white rounded-3xl p-6 border border-slate-200 hover:border-pink-200 hover:shadow-xl hover:shadow-pink-500/10 transition-all duration-300 cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 mb-1">{{ __('support.phone') }}</h3>
                                <p class="text-slate-500 text-sm mb-2">{{ __('support.phone_hours') }}</p>
                                <a href="tel:1588-0000" class="text-2xl font-bold text-pink-500 hover:text-pink-600 transition-colors">1588-0000</a>
                            </div>
                        </div>
                    </div>

                    <!-- Email Card -->
                    <div class="group bg-white rounded-3xl p-6 border border-slate-200 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 mb-1">{{ __('support.email') }}</h3>
                                <p class="text-slate-500 text-sm mb-2">{{ __('support.email_response_time') }}</p>
                                <a href="mailto:support@mytravel.com" class="text-blue-500 font-medium hover:text-blue-600 transition-colors">support@mytravel.com</a>
                            </div>
                        </div>
                    </div>

                    <!-- Business Hours Card -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-lg text-slate-900">{{ __('support.business_hours') }}</h3>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-500">{{ __('support.weekdays') }}</span>
                                <span class="font-semibold text-emerald-600">09:00 - 18:00</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-500">{{ __('support.saturday') }}</span>
                                <span class="font-semibold text-amber-600">10:00 - 14:00</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-500">{{ __('support.sunday_holiday') }}</span>
                                <span class="font-semibold text-red-500">{{ __('support.closed') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl shadow-slate-900/5">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">{{ __('support.send_message') }}</h2>
                                <p class="text-sm text-slate-500">빠른 시간 내에 답변 드리겠습니다</p>
                            </div>
                        </div>

                        <form action="#" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">{{ __('support.form_name') }}</label>
                                    <input type="text" id="name" name="name" required
                                           class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all"
                                           placeholder="{{ __('support.form_name_placeholder') }}">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">{{ __('support.form_email') }}</label>
                                    <input type="email" id="email" name="email" required
                                           class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all"
                                           placeholder="{{ __('support.form_email_placeholder') }}">
                                </div>
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="subject" class="block text-sm font-semibold text-slate-700 mb-2">{{ __('support.form_subject') }}</label>
                                <select id="subject" name="subject" required
                                        class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all cursor-pointer">
                                    <option value="">{{ __('support.form_subject_placeholder') }}</option>
                                    <option value="booking">{{ __('support.subject_booking') }}</option>
                                    <option value="payment">{{ __('support.subject_payment') }}</option>
                                    <option value="cancellation">{{ __('support.subject_cancellation') }}</option>
                                    <option value="account">{{ __('support.subject_account') }}</option>
                                    <option value="other">{{ __('support.subject_other') }}</option>
                                </select>
                            </div>

                            <!-- Booking Number -->
                            <div>
                                <label for="booking_number" class="block text-sm font-semibold text-slate-700 mb-2">
                                    {{ __('support.form_booking_number') }}
                                    <span class="font-normal text-slate-400 ml-1">({{ __('support.optional') }})</span>
                                </label>
                                <input type="text" id="booking_number" name="booking_number"
                                       class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all"
                                       placeholder="{{ __('support.form_booking_number_placeholder') }}">
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="message" class="block text-sm font-semibold text-slate-700 mb-2">{{ __('support.form_message') }}</label>
                                <textarea id="message" name="message" rows="5" required
                                          class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all resize-none"
                                          placeholder="{{ __('support.form_message_placeholder') }}"></textarea>
                            </div>

                            <!-- Submit -->
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-4">
                                <p class="text-sm text-slate-500">
                                    {{ __('support.privacy_notice') }}
                                    <a href="{{ route('support.privacy') }}" class="text-pink-500 hover:underline">{{ __('support.privacy_policy') }}</a>
                                </p>
                                <button type="submit"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-pink-500 to-rose-500 text-white font-semibold rounded-xl hover:from-pink-600 hover:to-rose-600 transition-all shadow-lg shadow-pink-500/25 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                    </svg>
                                    {{ __('support.send') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
