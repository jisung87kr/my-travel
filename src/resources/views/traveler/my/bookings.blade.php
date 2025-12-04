<x-layouts.app :title="__('nav.my_bookings')">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('nav.my_bookings') }}</h1>

        <!-- Status Filter -->
        <div class="mb-6 flex gap-2 overflow-x-auto pb-2">
            <a href="{{ route('my.bookings', ['locale' => app()->getLocale()]) }}"
               class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All
            </a>
            @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                <a href="{{ route('my.bookings', ['locale' => app()->getLocale(), 'status' => $status]) }}"
                   class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === $status ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ __("booking.status.{$status}") }}
                </a>
            @endforeach
        </div>

        @if($bookings->isEmpty())
            <div class="text-center py-16">
                <svg class="mx-auto w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No bookings yet</h3>
                <p class="mt-2 text-gray-600">Start exploring our products!</p>
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                   class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    {{ __('nav.products') }}
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                        <a href="{{ route('my.booking.detail', ['locale' => app()->getLocale(), 'booking' => $booking['id']]) }}"
                           class="flex flex-col sm:flex-row">
                            <!-- Image -->
                            <div class="sm:w-48 h-32 sm:h-auto flex-shrink-0">
                                <img src="{{ $booking['product_image'] }}"
                                     alt="{{ $booking['product_title'] }}"
                                     class="w-full h-full object-cover">
                            </div>

                            <!-- Content -->
                            <div class="flex-1 p-4 sm:p-6">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                    <div>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full"
                                              style="background-color: {{ $booking['status_color'] }}20; color: {{ $booking['status_color'] }}">
                                            {{ $booking['status_label'] }}
                                        </span>
                                        <h3 class="mt-2 font-semibold text-gray-900">{{ $booking['product_title'] }}</h3>
                                        <p class="text-sm text-gray-500">{{ $booking['booking_code'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">₩{{ $booking['formatted_price'] }}</p>
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $booking['formatted_date'] }}
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        {{ $booking['adult_count'] }} {{ __('booking.adults') }}
                                        @if($booking['child_count'] > 0)
                                            , {{ $booking['child_count'] }} {{ __('booking.children') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
