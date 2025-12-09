<x-layouts.vendor>
    <x-slot name="header">예약 상세</x-slot>

    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('vendor.bookings.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            예약 목록으로
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Info -->
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">예약 정보</h2>
                        <p class="text-xs text-slate-500 font-mono mt-1">{{ $booking->booking_code }}</p>
                    </div>
                    @php
                        $statusColors = [
                            'pending' => 'bg-amber-100 text-amber-700',
                            'confirmed' => 'bg-blue-100 text-blue-700',
                            'in_progress' => 'bg-indigo-100 text-indigo-700',
                            'completed' => 'bg-emerald-100 text-emerald-700',
                            'cancelled' => 'bg-slate-100 text-slate-600',
                            'no_show' => 'bg-red-100 text-red-700',
                        ];
                        $statusValue = $booking->status->value ?? $booking->status;
                    @endphp
                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full {{ $statusColors[$statusValue] ?? 'bg-slate-100 text-slate-600' }}">
                        {{ $booking->status->label() }}
                    </span>
                </div>

                <dl class="grid grid-cols-2 gap-6">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 mb-1">상품</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $booking->product->getTranslation('ko')?->title ?? '상품' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 mb-1">예약일</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $booking->schedule?->date?->format('Y년 m월 d일') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 mb-1">성인</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $booking->adult_count }}명</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 mb-1">아동</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $booking->child_count }}명</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 mb-1">예약 생성</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $booking->created_at->format('Y-m-d H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 mb-1">예약 유형</dt>
                        <dd class="text-sm font-medium text-slate-900">
                            {{ $booking->product->booking_type === 'instant' ? '자동 확정' : '승인 필요' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                <h2 class="text-sm font-semibold text-slate-900 mb-4">고객 정보</h2>

                <dl class="grid grid-cols-2 gap-6">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 mb-1">이름</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $booking->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 mb-1">이메일</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $booking->contact_email ?? $booking->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 mb-1">연락처</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $booking->contact_phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 mb-1">가입일</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $booking->user->created_at->format('Y-m-d') }}</dd>
                    </div>
                </dl>

                @if($booking->special_requests)
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <dt class="text-xs font-medium text-slate-500 mb-2">요청사항</dt>
                        <dd class="text-sm text-slate-700 bg-slate-50 p-4 rounded-xl">{{ $booking->special_requests }}</dd>
                    </div>
                @endif
            </div>

            <!-- Status History -->
            @if($booking->statusLogs && $booking->statusLogs->count() > 0)
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h2 class="text-sm font-semibold text-slate-900 mb-4">상태 이력</h2>

                    <div class="space-y-3">
                        @foreach($booking->statusLogs as $log)
                            <div class="flex items-center gap-3 text-sm">
                                <span class="w-28 text-xs text-slate-500">{{ $log->created_at->format('m/d H:i') }}</span>
                                @php
                                    $logStatusValue = $log->status->value ?? $log->status;
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$logStatusValue] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $log->status->label() }}
                                </span>
                                @if($log->note)
                                    <span class="text-slate-600 text-xs">{{ $log->note }}</span>
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
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                <h2 class="text-sm font-semibold text-slate-900 mb-4">결제 정보</h2>

                <div class="space-y-3">
                    @php
                        $adultPrice = $booking->product->prices->where('price_type', 'adult')->first()?->price ?? 0;
                        $childPrice = $booking->product->prices->where('price_type', 'child')->first()?->price ?? 0;
                    @endphp
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600">성인 {{ $booking->adult_count }}명 x {{ number_format($adultPrice) }}원</span>
                        <span class="text-slate-900">{{ number_format($booking->adult_count * $adultPrice) }}원</span>
                    </div>
                    @if($booking->child_count > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">아동 {{ $booking->child_count }}명 x {{ number_format($childPrice) }}원</span>
                            <span class="text-slate-900">{{ number_format($booking->child_count * $childPrice) }}원</span>
                        </div>
                    @endif
                    <div class="pt-3 mt-3 border-t border-slate-100 flex justify-between">
                        <span class="text-sm font-semibold text-slate-900">총 결제 금액</span>
                        <span class="text-sm font-bold text-violet-600">{{ number_format($booking->total_price) }}원</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                <h2 class="text-sm font-semibold text-slate-900 mb-4">예약 관리</h2>

                <div class="space-y-3">
                    @if($booking->status->value === 'pending')
                        <form method="POST" action="{{ route('vendor.bookings.approve', $booking) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl hover:from-emerald-600 hover:to-emerald-700 shadow-lg shadow-emerald-500/25 transition-all">
                                예약 승인
                            </button>
                        </form>
                        <form method="POST" action="{{ route('vendor.bookings.reject', $booking) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 rounded-xl hover:from-red-600 hover:to-red-700 shadow-lg shadow-red-500/25 transition-all">
                                예약 거절
                            </button>
                        </form>
                    @elseif($booking->status->value === 'confirmed')
                        <form method="POST" action="{{ route('vendor.bookings.complete', $booking) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl hover:from-emerald-600 hover:to-emerald-700 shadow-lg shadow-emerald-500/25 transition-all">
                                이용 완료
                            </button>
                        </form>
                        <form method="POST" action="{{ route('vendor.bookings.no-show', $booking) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl hover:from-amber-600 hover:to-orange-600 shadow-lg shadow-amber-500/25 transition-all"
                                    onclick="return confirm('노쇼로 처리하시겠습니까? 고객에게 노쇼 기록이 추가됩니다.')">
                                노쇼 처리
                            </button>
                        </form>
                    @elseif(in_array($booking->status->value, ['completed', 'cancelled', 'no_show']))
                        <div class="text-center py-6">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm text-slate-500">
                                이 예약은 <span class="font-medium">{{ $booking->status->label() }}</span> 상태입니다.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.vendor>
