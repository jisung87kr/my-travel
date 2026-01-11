<x-traveler.my.layout :title="__('nav.booking_detail')">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span class="text-sm font-medium">{{ __('booking.back_to_list') }}</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Status Card -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('booking.booking_number') }}</p>
                            <p class="text-lg font-bold text-gray-900">{{ $booking->booking_code }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold"
                              style="background-color: {{ $booking->status->color() }}15; color: {{ $booking->status->color() }}">
                            {{ $booking->status->label() }}
                        </span>
                    </div>

                    <!-- Timeline -->
                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-2 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ __('booking.booked_at') }} {{ $booking->created_at->format('Y.m.d H:i') }}</span>
                            </div>
                            @if($booking->confirmed_at)
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ __('booking.confirmed_at') }} {{ $booking->confirmed_at->format('Y.m.d H:i') }}</span>
                                </div>
                            @endif
                            @if($booking->cancelled_at)
                                <div class="flex items-center gap-2 text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ __('booking.cancelled_at') }} {{ $booking->cancelled_at->format('Y.m.d H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="flex flex-col sm:flex-row">
                    <!-- Product Image -->
                    <div class="sm:w-48 h-48 sm:h-auto flex-shrink-0">
                        <img src="{{ $booking->product->images->first()?->url ?? 'https://placehold.co/300x300?text=NO+IMAGE' }}"
                             alt="{{ $translation?->title }}"
                             class="w-full h-full object-cover">
                    </div>
                    <!-- Product Details -->
                    <div class="flex-1 p-6">
                        <div class="flex items-start gap-2 mb-2">
                            @if($booking->product->type)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-pink-50 text-pink-700">
                                {{ $booking->product->type->label() }}
                            </span>
                            @endif
                            @if($booking->product->region)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $booking->product->region->getName(app()->getLocale()) }}
                            </span>
                            @endif
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            {{ $translation?->title ?? $booking->product->getTranslation('ko')?->title }}
                        </h2>
                        <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $booking->product->slug]) }}"
                           class="inline-flex items-center gap-1 text-sm text-pink-600 hover:text-pink-700 font-medium">
                            {{ __('booking.view_product') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Schedule Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('booking.schedule_info') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('booking.schedule_info_desc') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">{{ __('booking.date_label') }}</p>
                            <p class="font-semibold text-gray-900">{{ $booking->schedule->date->translatedFormat('Y. m. d (D)') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">{{ __('booking.time_label') }}</p>
                            <p class="font-semibold text-gray-900">{{ $booking->schedule->start_time?->format('H:i') ?? __('booking.time_tbd') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participant Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('booking.participants') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('booking.total_persons', ['count' => $booking->total_persons]) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-2xl font-bold text-gray-900">{{ $booking->adult_count }}</p>
                        <p class="text-sm text-gray-500">{{ __('booking.adults') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-2xl font-bold text-gray-900">{{ $booking->child_count }}</p>
                        <p class="text-sm text-gray-500">{{ __('booking.children') }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-2xl font-bold text-gray-900">{{ $booking->infant_count }}</p>
                        <p class="text-sm text-gray-500">{{ __('booking.infants') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('booking.booker_info_detail') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('booking.booker_info_detail_desc') }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('booking.name_label') }}</p>
                            <p class="font-medium text-gray-900">{{ $booking->contact_name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('booking.email_label') }}</p>
                            <p class="font-medium text-gray-900">{{ $booking->contact_email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('booking.phone_label') }}</p>
                            <p class="font-medium text-gray-900">{{ $booking->contact_phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Special Request -->
            @if($booking->special_request)
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('booking.special_request_title') }}</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 bg-gray-50 p-4 rounded-xl">{{ $booking->special_request }}</p>
                </div>
            @endif

            <!-- Cancellation Info -->
            @if($booking->isCancelled() && $booking->cancellation_reason)
                <div class="bg-red-50 rounded-2xl p-6 border border-red-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-red-900">{{ __('booking.cancellation_reason') }}</h3>
                        </div>
                    </div>
                    <p class="text-red-700">{{ $booking->cancellation_reason }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-32 space-y-6">
                <!-- Price Summary -->
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('booking.payment_info') }}</h3>

                    <div class="space-y-3 pb-4 border-b border-gray-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">{{ __('booking.adults_with_count', ['count' => $booking->adult_count]) }}</span>
                            <span class="text-gray-900">-</span>
                        </div>
                        @if($booking->child_count > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">{{ __('booking.children_with_count', ['count' => $booking->child_count]) }}</span>
                                <span class="text-gray-900">-</span>
                            </div>
                        @endif
                        @if($booking->infant_count > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">{{ __('booking.infants_with_count', ['count' => $booking->infant_count]) }}</span>
                                <span class="text-gray-900">{{ __('booking.free') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center pt-4">
                        <span class="font-bold text-gray-900">{{ __('booking.total_payment_amount') }}</span>
                        <span class="text-2xl font-bold text-pink-600">{{ number_format($booking->total_price) }}{{ __('booking.currency_suffix') }}</span>
                    </div>
                </div>

                <!-- Vendor Info -->
                @if($booking->product->vendor)
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('booking.vendor_info') }}</h3>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center text-white font-bold">
                                {{ mb_substr($booking->product->vendor->user->name ?? 'V', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $booking->product->vendor->company_name ?? $booking->product->vendor->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->product->vendor->user->email ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-3">
                    @if($booking->canBeCancelled())
                        <button type="button"
                                onclick="openCancelModal()"
                                class="w-full px-4 py-3 bg-white border border-red-200 text-red-600 rounded-xl font-semibold hover:bg-red-50 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            {{ __('booking.cancel_booking') }}
                        </button>
                    @endif

                    @if($booking->isCompleted() && !$booking->review)
                        <button type="button"
                                onclick="openReviewModal()"
                                class="w-full px-4 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-pink-500/25 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                            {{ __('booking.write_review') }}
                        </button>
                    @endif

                    <a href="{{ route('messages.thread', ['locale' => app()->getLocale(), 'booking' => $booking->id]) }}"
                       class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                        </svg>
                        {{ __('booking.contact_vendor') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Cancel Booking Modal --}}
    @if($booking->canBeCancelled())
        <div id="cancel-modal"
             class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4"
             onclick="if(event.target === this) closeCancelModal()">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden" onclick="event.stopPropagation()">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">{{ __('booking.cancel_booking') }}</h3>
                    <button type="button" onclick="closeCancelModal()" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <form action="{{ route('my.booking.cancel', ['locale' => app()->getLocale(), 'booking' => $booking->id]) }}" method="POST">
                    @csrf
                    <div class="p-6">
                        <div class="flex items-center gap-3 p-4 bg-red-50 rounded-xl mb-6">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-red-900">{{ __('booking.cancel_confirm_title') }}</p>
                                <p class="text-sm text-red-700">{{ __('booking.cancel_confirm_desc') }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="cancel-reason" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('booking.cancel_reason_optional') }}
                            </label>
                            <textarea id="cancel-reason"
                                      name="reason"
                                      rows="3"
                                      placeholder="{{ __('booking.cancel_reason_placeholder') }}"
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors resize-none"></textarea>
                        </div>

                        {{-- Booking Summary --}}
                        <div class="bg-gray-50 rounded-xl p-4 text-sm">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-500">{{ __('booking.booking_number') }}</span>
                                <span class="font-medium text-gray-900">{{ $booking->booking_code }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-500">{{ __('booking.date') }}</span>
                                <span class="font-medium text-gray-900">{{ $booking->schedule->date->format('Y.m.d') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('booking.payment_amount') }}</span>
                                <span class="font-medium text-gray-900">{{ number_format($booking->total_price) }}{{ __('booking.currency_suffix') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                        <button type="button"
                                onclick="closeCancelModal()"
                                class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                            {{ __('booking.close') }}
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-colors">
                            {{ __('booking.cancel_booking') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Write Review Modal --}}
    @if($booking->isCompleted() && !$booking->review)
        <div id="review-modal"
             class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4"
             onclick="if(event.target === this) closeReviewModal()">
            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-bold text-gray-900">{{ __('review.write_review') }}</h3>
                    <button type="button" onclick="closeReviewModal()" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <form id="review-form" action="{{ route('reviews.store', ['booking' => $booking->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6 space-y-6">
                        {{-- Product Info --}}
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <img src="{{ $booking->product->images->first()?->url ?? 'https://placehold.co/80x80?text=NO+IMAGE' }}"
                                 alt="{{ $translation?->title }}"
                                 class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $translation?->title ?? $booking->product->getTranslation('ko')?->title }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->schedule->date->format('Y.m.d') }}</p>
                            </div>
                        </div>

                        {{-- Rating --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('review.rating') }}</label>
                            <div class="flex items-center gap-2" id="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                            onclick="setRating({{ $i }})"
                                            class="rating-star w-10 h-10 text-gray-300 hover:text-yellow-400 transition-colors"
                                            data-rating="{{ $i }}">
                                        <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-input" value="5" required>
                        </div>

                        {{-- Content --}}
                        <div>
                            <label for="review-content" class="block text-sm font-medium text-gray-700 mb-2">{{ __('review.content') }}</label>
                            <textarea id="review-content"
                                      name="content"
                                      rows="4"
                                      placeholder="{{ __('review.content_placeholder') }}"
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors resize-none"
                                      required
                                      minlength="10"
                                      maxlength="1000"></textarea>
                            <p class="mt-1 text-xs text-gray-500"><span id="content-count">0</span>/1000</p>
                        </div>

                        {{-- Images --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('review.images') }}
                                <span class="text-gray-400 font-normal">({{ __('review.max_images') }})</span>
                            </label>

                            {{-- Image Preview Area --}}
                            <div id="image-preview" class="grid grid-cols-5 gap-2 mb-3 hidden">
                                {{-- Preview images will be added here --}}
                            </div>

                            {{-- Add Image Button --}}
                            <label id="add-image-btn" class="flex items-center justify-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-pink-300 hover:bg-pink-50/50 transition-colors">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span class="text-sm text-gray-500">{{ __('review.add_images') }}</span>
                                <span class="text-xs text-gray-400">({{ __('review.max_size') }})</span>
                                <input type="file"
                                       name="images[]"
                                       id="image-input"
                                       class="hidden"
                                       accept="image/jpeg,image/png,image/jpg"
                                       multiple>
                            </label>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-gray-100 flex gap-3 sticky bottom-0 bg-white">
                        <button type="button"
                                onclick="closeReviewModal()"
                                class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                            {{ __('review.cancel') }}
                        </button>
                        <button type="submit"
                                id="submit-review-btn"
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-pink-500/25 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ __('review.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        function openCancelModal() {
            const modal = document.getElementById('cancel-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancel-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCancelModal();
                closeReviewModal();
            }
        });

        // Review Modal Functions
        function openReviewModal() {
            const modal = document.getElementById('review-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                // Initialize with 5 stars
                setRating(5);
            }
        }

        function closeReviewModal() {
            const modal = document.getElementById('review-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
                // Reset form
                resetReviewForm();
            }
        }

        function resetReviewForm() {
            const form = document.getElementById('review-form');
            if (form) {
                form.reset();
                setRating(5);
                document.getElementById('content-count').textContent = '0';
                // Clear image previews
                const previewContainer = document.getElementById('image-preview');
                if (previewContainer) {
                    previewContainer.innerHTML = '';
                    previewContainer.classList.add('hidden');
                }
                // Show add button
                const addBtn = document.getElementById('add-image-btn');
                if (addBtn) {
                    addBtn.classList.remove('hidden');
                }
                selectedFiles = [];
            }
        }

        // Rating Stars
        function setRating(rating) {
            document.getElementById('rating-input').value = rating;
            const stars = document.querySelectorAll('.rating-star');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        // Content character count
        const contentTextarea = document.getElementById('review-content');
        if (contentTextarea) {
            contentTextarea.addEventListener('input', function() {
                document.getElementById('content-count').textContent = this.value.length;
            });
        }

        // Image handling
        let selectedFiles = [];
        const maxImages = 5;

        const imageInput = document.getElementById('image-input');
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                const remainingSlots = maxImages - selectedFiles.length;

                if (files.length > remainingSlots) {
                    alert('{{ __("review.max_images") }}');
                    files.splice(remainingSlots);
                }

                files.forEach(file => {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('{{ __("review.max_size") }}');
                        return;
                    }
                    selectedFiles.push(file);
                });

                updateImagePreviews();
                this.value = '';
            });
        }

        function updateImagePreviews() {
            const previewContainer = document.getElementById('image-preview');
            const addBtn = document.getElementById('add-image-btn');

            if (!previewContainer) return;

            previewContainer.innerHTML = '';

            if (selectedFiles.length > 0) {
                previewContainer.classList.remove('hidden');
                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative aspect-square group';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover rounded-lg" alt="Preview">
                            <button type="button"
                                    onclick="removeImage(${index})"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                previewContainer.classList.add('hidden');
            }

            // Hide/show add button based on image count
            if (addBtn) {
                if (selectedFiles.length >= maxImages) {
                    addBtn.classList.add('hidden');
                } else {
                    addBtn.classList.remove('hidden');
                }
            }
        }

        function removeImage(index) {
            selectedFiles.splice(index, 1);
            updateImagePreviews();
        }

        // Form submission
        const reviewForm = document.getElementById('review-form');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                // Remove default file input and add selected files
                formData.delete('images[]');
                selectedFiles.forEach(file => {
                    formData.append('images[]', file);
                });

                const submitBtn = document.getElementById('submit-review-btn');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = '...';

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join('\n');
                        alert(errorMessages);
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    } else {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.location.reload();
                });
            });
        }
    </script>
</x-traveler.my.layout>
