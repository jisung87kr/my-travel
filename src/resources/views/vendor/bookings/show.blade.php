<x-layouts.vendor>
    <x-slot name="header">예약 상세</x-slot>

    <div class="mb-6">
        <a href="{{ route('vendor.bookings.index') }}" class="text-gray-600 hover:text-gray-900">
            &larr; 예약 목록으로
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-lg font-semibold">예약 정보</h2>
                        <p class="text-sm text-gray-500 font-mono">{{ $booking->booking_code }}</p>
                    </div>
                    <span class="px-3 py-1 text-sm rounded-full {{ $booking->status->color() }}">
                        {{ $booking->status->label() }}
                    </span>
                </div>

                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">상품</dt>
                        <dd class="text-sm font-medium">{{ $booking->product->getTranslation('ko')?->title ?? '상품' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">예약일</dt>
                        <dd class="text-sm font-medium">{{ $booking->schedule?->date?->format('Y년 m월 d일') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">성인</dt>
                        <dd class="text-sm font-medium">{{ $booking->adult_count }}명</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">아동</dt>
                        <dd class="text-sm font-medium">{{ $booking->child_count }}명</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">예약 생성</dt>
                        <dd class="text-sm font-medium">{{ $booking->created_at->format('Y-m-d H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">예약 유형</dt>
                        <dd class="text-sm font-medium">
                            {{ $booking->product->booking_type === 'instant' ? '자동 확정' : '승인 필요' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">고객 정보</h2>

                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">이름</dt>
                        <dd class="text-sm font-medium">{{ $booking->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">이메일</dt>
                        <dd class="text-sm font-medium">{{ $booking->contact_email ?? $booking->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">연락처</dt>
                        <dd class="text-sm font-medium">{{ $booking->contact_phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">가입일</dt>
                        <dd class="text-sm font-medium">{{ $booking->user->created_at->format('Y-m-d') }}</dd>
                    </div>
                </dl>

                @if($booking->special_requests)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <dt class="text-sm text-gray-500 mb-1">요청사항</dt>
                        <dd class="text-sm bg-gray-50 p-3 rounded-lg">{{ $booking->special_requests }}</dd>
                    </div>
                @endif
            </div>

            <!-- Status History (optional) -->
            @if($booking->statusLogs && $booking->statusLogs->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">상태 이력</h2>

                    <div class="space-y-3">
                        @foreach($booking->statusLogs as $log)
                            <div class="flex items-center text-sm">
                                <span class="w-32 text-gray-500">{{ $log->created_at->format('m/d H:i') }}</span>
                                <span class="px-2 py-1 text-xs rounded {{ $log->status->color() }}">
                                    {{ $log->status->label() }}
                                </span>
                                @if($log->note)
                                    <span class="ml-2 text-gray-600">{{ $log->note }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Price Summary -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">결제 정보</h2>

                <div class="space-y-2">
                    @php
                        $adultPrice = $booking->product->prices->where('price_type', 'adult')->first()?->price ?? 0;
                        $childPrice = $booking->product->prices->where('price_type', 'child')->first()?->price ?? 0;
                    @endphp
                    <div class="flex justify-between text-sm">
                        <span>성인 {{ $booking->adult_count }}명 x {{ number_format($adultPrice) }}원</span>
                        <span>{{ number_format($booking->adult_count * $adultPrice) }}원</span>
                    </div>
                    @if($booking->child_count > 0)
                        <div class="flex justify-between text-sm">
                            <span>아동 {{ $booking->child_count }}명 x {{ number_format($childPrice) }}원</span>
                            <span>{{ number_format($booking->child_count * $childPrice) }}원</span>
                        </div>
                    @endif
                    <div class="pt-2 mt-2 border-t border-gray-200 flex justify-between font-semibold">
                        <span>총 결제 금액</span>
                        <span class="text-blue-600">{{ number_format($booking->total_price) }}원</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">예약 관리</h2>

                <div class="space-y-3">
                    @if($booking->status->value === 'pending')
                        <form method="POST" action="{{ route('vendor.bookings.approve', $booking) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                예약 승인
                            </button>
                        </form>
                        <form method="POST" action="{{ route('vendor.bookings.reject', $booking) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                예약 거절
                            </button>
                        </form>
                    @elseif($booking->status->value === 'confirmed')
                        <form method="POST" action="{{ route('vendor.bookings.complete', $booking) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                이용 완료
                            </button>
                        </form>
                        <form method="POST" action="{{ route('vendor.bookings.no-show', $booking) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700"
                                    onclick="return confirm('노쇼로 처리하시겠습니까? 고객에게 노쇼 기록이 추가됩니다.')">
                                노쇼 처리
                            </button>
                        </form>
                    @elseif(in_array($booking->status->value, ['completed', 'cancelled', 'no_show']))
                        <p class="text-sm text-gray-500 text-center py-4">
                            이 예약은 {{ $booking->status->label() }} 상태입니다.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.vendor>
