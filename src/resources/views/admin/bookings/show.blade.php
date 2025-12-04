<x-layouts.admin>
    <x-slot name="header">예약 상세 - {{ $booking->booking_number ?? $booking->id }}</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Booking Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center mb-6">
                    <span class="inline-block px-4 py-2 text-lg rounded-full {{ $booking->status->color() }}">
                        {{ $booking->status->label() }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">예약번호</span>
                        <span class="font-mono">{{ $booking->booking_number ?? $booking->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">예약일</span>
                        <span>{{ $booking->booking_date->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">인원</span>
                        <span>{{ $booking->quantity }}명</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">결제 금액</span>
                        <span class="font-semibold">{{ number_format($booking->total_amount) }}원</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">예약 생성일</span>
                        <span>{{ $booking->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>

                @if(!in_array($booking->status->value, ['cancelled', 'no_show', 'completed']))
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <button type="button" onclick="openCancelModal()"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            예약 취소
                        </button>
                    </div>
                @endif

                @if($booking->cancellation_reason)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-red-500 mb-2">취소 사유</h4>
                        <p class="text-gray-900">{{ $booking->cancellation_reason }}</p>
                    </div>
                @endif
            </div>

            <!-- User Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4">예약자 정보</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-gray-500 text-sm">이름</span>
                        <p class="font-medium">{{ $booking->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">이메일</span>
                        <p>{{ $booking->user->email }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">노쇼 횟수</span>
                        <p class="{{ $booking->user->no_show_count >= 3 ? 'text-red-600 font-medium' : '' }}">
                            {{ $booking->user->no_show_count }}회
                            @if($booking->user->is_blocked)
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 ml-2">차단됨</span>
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('admin.users.show', $booking->user) }}" class="text-indigo-600 hover:underline text-sm">
                        사용자 상세 &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- Product & Payment -->
        <div class="lg:col-span-2 space-y-6">
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
                        <a href="{{ route('admin.products.show', $booking->product) }}" class="text-indigo-600 hover:underline text-sm">
                            상품 상세 &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            @if($booking->payment)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold mb-4">결제 정보</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-500">결제 수단</span>
                            <span>{{ $booking->payment->method ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">결제 상태</span>
                            <span>{{ $booking->payment->status ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">결제 금액</span>
                            <span class="font-semibold">{{ number_format($booking->payment->amount ?? 0) }}원</span>
                        </div>
                        @if($booking->payment->paid_at)
                            <div class="flex justify-between">
                                <span class="text-gray-500">결제 일시</span>
                                <span>{{ $booking->payment->paid_at->format('Y-m-d H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4">예약 타임라인</h3>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="w-3 h-3 rounded-full bg-indigo-600 mt-1.5"></div>
                        <div>
                            <p class="font-medium">예약 생성</p>
                            <p class="text-sm text-gray-500">{{ $booking->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    @if($booking->confirmed_at)
                        <div class="flex gap-4">
                            <div class="w-3 h-3 rounded-full bg-green-600 mt-1.5"></div>
                            <div>
                                <p class="font-medium">예약 확정</p>
                                <p class="text-sm text-gray-500">{{ $booking->confirmed_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($booking->completed_at)
                        <div class="flex gap-4">
                            <div class="w-3 h-3 rounded-full bg-blue-600 mt-1.5"></div>
                            <div>
                                <p class="font-medium">이용 완료</p>
                                <p class="text-sm text-gray-500">{{ $booking->completed_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($booking->cancelled_at)
                        <div class="flex gap-4">
                            <div class="w-3 h-3 rounded-full bg-red-600 mt-1.5"></div>
                            <div>
                                <p class="font-medium">예약 취소</p>
                                <p class="text-sm text-gray-500">{{ $booking->cancelled_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.bookings.index') }}" class="text-indigo-600 hover:underline">&larr; 목록으로</a>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">예약 취소</h3>
            <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">취소 사유</label>
                    <textarea name="reason" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                              placeholder="취소 사유를 입력하세요..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCancelModal()" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                        닫기
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        취소하기
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.getElementById('cancelModal').classList.add('flex');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.getElementById('cancelModal').classList.remove('flex');
        }
    </script>
</x-layouts.admin>
