<x-layouts.admin>
    <x-slot name="header">예약 상세</x-slot>

    <div class="max-w-6xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('admin.bookings.index') }}" class="text-slate-500 hover:text-blue-600 transition-colors">예약 관리</a>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-slate-900 font-medium">{{ $booking->booking_number ?? '#'.$booking->id }}</span>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Booking Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                    @php
                        $statusValue = $booking->status->value ?? $booking->status;
                    @endphp
                    <div class="p-6 text-center border-b border-slate-100 bg-gradient-to-r {{ $statusValue === 'confirmed' ? 'from-emerald-50 to-teal-50' : ($statusValue === 'pending' ? 'from-amber-50 to-orange-50' : ($statusValue === 'completed' ? 'from-blue-50 to-indigo-50' : ($statusValue === 'cancelled' ? 'from-slate-50 to-slate-100' : 'from-red-50 to-rose-50'))) }}">
                        @if($statusValue === 'confirmed')
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-800 ring-2 ring-inset ring-emerald-600/20">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ $booking->status->label() }}
                            </span>
                        @elseif($statusValue === 'pending')
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-800 ring-2 ring-inset ring-amber-600/20">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                {{ $booking->status->label() }}
                            </span>
                        @elseif($statusValue === 'completed')
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 ring-2 ring-inset ring-blue-600/20">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                {{ $booking->status->label() }}
                            </span>
                        @elseif($statusValue === 'cancelled')
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-slate-100 text-slate-800 ring-2 ring-inset ring-slate-600/20">
                                <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                                {{ $booking->status->label() }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 ring-2 ring-inset ring-red-600/20">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                {{ $booking->status->label() }}
                            </span>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-500">예약번호</span>
                                <span class="font-mono font-medium text-slate-900 bg-slate-100 px-2 py-0.5 rounded">{{ $booking->booking_number ?? '#'.$booking->id }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-500">예약일</span>
                                <span class="font-medium text-slate-900">{{ $booking->booking_date->format('Y-m-d') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-500">인원</span>
                                <span class="font-medium text-slate-900">{{ $booking->quantity }}명</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-500">결제 금액</span>
                                <span class="font-bold text-lg text-blue-600">{{ number_format($booking->total_amount) }}원</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-500">예약 생성일</span>
                                <span class="text-slate-700">{{ $booking->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>

                        @if(!in_array($statusValue, ['cancelled', 'no_show', 'completed']))
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <button type="button" onclick="openCancelModal()"
                                        class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 font-medium shadow-lg shadow-red-500/30 transition-all">
                                    예약 취소
                                </button>
                            </div>
                        @endif

                        @if($booking->cancellation_reason)
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <div class="flex items-center gap-2 text-sm font-medium text-red-600 mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    취소 사유
                                </div>
                                <p class="text-slate-700 bg-red-50 p-3 rounded-xl text-sm">{{ $booking->cancellation_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- User Info -->
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900">예약자 정보</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <span class="text-slate-500 text-sm">이름</span>
                            <p class="font-semibold text-slate-900 mt-0.5">{{ $booking->user->name }}</p>
                        </div>
                        <div>
                            <span class="text-slate-500 text-sm">이메일</span>
                            <p class="text-slate-700 mt-0.5">{{ $booking->user->email }}</p>
                        </div>
                        <div>
                            <span class="text-slate-500 text-sm">노쇼 횟수</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="{{ $booking->user->no_show_count >= 3 ? 'text-red-600 font-bold' : 'text-slate-700' }}">
                                    {{ $booking->user->no_show_count }}회
                                </span>
                                @if($booking->user->is_blocked)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ring-1 ring-inset ring-red-600/20">
                                        <span class="w-1 h-1 rounded-full bg-red-500"></span>
                                        차단됨
                                    </span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.users.show', $booking->user) }}"
                           class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 font-medium">
                            사용자 상세 보기
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column - Product & Payment -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Info -->
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-teal-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900">상품 정보</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex gap-5">
                            @if($booking->product->images->first())
                                <img src="{{ $booking->product->images->first()->url }}" alt=""
                                     class="w-28 h-28 rounded-xl object-cover shadow-md flex-shrink-0">
                            @else
                                <div class="w-28 h-28 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-slate-900">{{ $booking->product->getTranslation('ko')?->name ?? '상품' }}</h4>
                                <p class="text-slate-500 mt-1">{{ $booking->product->vendor->company_name ?? $booking->product->vendor->business_name }}</p>
                                @if($booking->product->region)
                                    <div class="flex items-center gap-1 mt-2 text-sm text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $booking->product->region->label() }}
                                    </div>
                                @endif
                                <a href="{{ route('admin.products.show', $booking->product) }}"
                                   class="inline-flex items-center gap-1 mt-3 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    상품 상세 보기
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                @if($booking->payment)
                    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-orange-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-900">결제 정보</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <span class="text-slate-500 text-sm">결제 수단</span>
                                    <p class="font-medium text-slate-900 mt-0.5">{{ $booking->payment->method ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-slate-500 text-sm">결제 상태</span>
                                    <p class="font-medium text-slate-900 mt-0.5">{{ $booking->payment->status ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-slate-500 text-sm">결제 금액</span>
                                    <p class="font-bold text-lg text-blue-600 mt-0.5">{{ number_format($booking->payment->amount ?? 0) }}원</p>
                                </div>
                                @if($booking->payment->paid_at)
                                    <div>
                                        <span class="text-slate-500 text-sm">결제 일시</span>
                                        <p class="font-medium text-slate-900 mt-0.5">{{ $booking->payment->paid_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Timeline -->
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-violet-50 to-purple-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900">예약 타임라인</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative">
                            <div class="absolute left-[7px] top-3 bottom-3 w-0.5 bg-slate-200"></div>
                            <div class="space-y-6">
                                <div class="flex gap-4 relative">
                                    <div class="w-4 h-4 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 mt-1 shadow-md z-10"></div>
                                    <div>
                                        <p class="font-semibold text-slate-900">예약 생성</p>
                                        <p class="text-sm text-slate-500">{{ $booking->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                                @if($booking->confirmed_at)
                                    <div class="flex gap-4 relative">
                                        <div class="w-4 h-4 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 mt-1 shadow-md z-10"></div>
                                        <div>
                                            <p class="font-semibold text-slate-900">예약 확정</p>
                                            <p class="text-sm text-slate-500">{{ $booking->confirmed_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($booking->completed_at)
                                    <div class="flex gap-4 relative">
                                        <div class="w-4 h-4 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 mt-1 shadow-md z-10"></div>
                                        <div>
                                            <p class="font-semibold text-slate-900">이용 완료</p>
                                            <p class="text-sm text-slate-500">{{ $booking->completed_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($booking->cancelled_at)
                                    <div class="flex gap-4 relative">
                                        <div class="w-4 h-4 rounded-full bg-gradient-to-br from-red-500 to-rose-600 mt-1 shadow-md z-10"></div>
                                        <div>
                                            <p class="font-semibold text-slate-900">예약 취소</p>
                                            <p class="text-sm text-slate-500">{{ $booking->cancelled_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.bookings.index') }}"
               class="inline-flex items-center gap-2 text-slate-600 hover:text-blue-600 font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                목록으로
            </a>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl mx-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">예약 취소</h3>
                    <p class="text-sm text-slate-500">이 예약을 취소하시겠습니까?</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">취소 사유</label>
                    <textarea name="reason" rows="3"
                              placeholder="취소 사유를 입력하세요..."
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCancelModal()"
                            class="px-5 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        닫기
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 font-medium shadow-lg shadow-red-500/30 transition-all">
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

        // Close modal on outside click
        document.getElementById('cancelModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCancelModal();
            }
        });
    </script>
</x-layouts.admin>
