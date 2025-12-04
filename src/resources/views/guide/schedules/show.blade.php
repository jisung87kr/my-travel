<x-layouts.guide>
    <x-slot name="header">일정 상세</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Booking Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center mb-6">
                    <span class="inline-block px-4 py-2 text-lg rounded-full {{ $booking->status->color() }}">
                        {{ $booking->status->label() }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">예약번호</span>
                        <span class="font-mono">{{ $booking->booking_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">예약일</span>
                        <span>{{ $booking->booking_date->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">인원</span>
                        <span>{{ $booking->quantity }}명</span>
                    </div>
                    @if($booking->adult_count)
                        <div class="flex justify-between">
                            <span class="text-gray-500">성인</span>
                            <span>{{ $booking->adult_count }}명</span>
                        </div>
                    @endif
                    @if($booking->child_count)
                        <div class="flex justify-between">
                            <span class="text-gray-500">아동</span>
                            <span>{{ $booking->child_count }}명</span>
                        </div>
                    @endif
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                    @if($booking->status->value === 'confirmed')
                        <form method="POST" action="{{ route('guide.bookings.start', $booking) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                투어 시작
                            </button>
                        </form>
                    @endif

                    @if($booking->status->value === 'in_progress')
                        <form method="POST" action="{{ route('guide.bookings.complete', $booking) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                투어 완료
                            </button>
                        </form>
                    @endif

                    @if(in_array($booking->status->value, ['confirmed', 'in_progress']))
                        <form method="POST" action="{{ route('guide.bookings.no-show', $booking) }}"
                              onsubmit="return confirm('노쇼로 처리하시겠습니까?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                노쇼 처리
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Customer & Product Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4">고객 정보</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-500 text-sm">이름</span>
                        <p class="font-medium">{{ $booking->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">이메일</span>
                        <p>{{ $booking->user->email }}</p>
                    </div>
                    @if($booking->contact_phone)
                        <div>
                            <span class="text-gray-500 text-sm">연락처</span>
                            <p>{{ $booking->contact_phone }}</p>
                        </div>
                    @endif
                </div>
                @if($booking->special_requests)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <span class="text-gray-500 text-sm">특별 요청사항</span>
                        <p class="mt-1">{{ $booking->special_requests }}</p>
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4">상품 정보</h3>
                <div class="flex gap-4">
                    @if($booking->product->images->first())
                        <img src="{{ $booking->product->images->first()->url }}" alt=""
                             class="w-24 h-24 rounded-lg object-cover">
                    @endif
                    <div class="flex-1">
                        <h4 class="font-medium text-lg">{{ $booking->product->getTranslation('ko')?->title ?? '상품' }}</h4>
                        <p class="text-gray-500">{{ $booking->product->vendor->business_name }}</p>
                        <p class="text-gray-500 text-sm mt-2">
                            {{ $booking->product->region?->label() ?? '' }}
                        </p>
                    </div>
                </div>
                @if($translation = $booking->product->getTranslation('ko'))
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-gray-600 text-sm">{{ Str::limit($translation->description, 200) }}</p>
                    </div>
                @endif
            </div>

            <!-- Vendor Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4">제공자 정보</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-500 text-sm">사업자명</span>
                        <p class="font-medium">{{ $booking->product->vendor->business_name }}</p>
                    </div>
                    @if($booking->product->vendor->phone)
                        <div>
                            <span class="text-gray-500 text-sm">연락처</span>
                            <p>{{ $booking->product->vendor->phone }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('guide.schedules.index') }}" class="text-teal-600 hover:underline">&larr; 일정 목록으로</a>
    </div>
</x-layouts.guide>
