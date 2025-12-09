<x-layouts.guide>
    <x-slot name="header">일정 상세</x-slot>

    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('guide.schedules.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-teal-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            일정 목록으로
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Booking Status Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-900">예약 상태</h2>
                </div>
                <div class="p-6">
                    <!-- Status Badge -->
                    <div class="text-center mb-6">
                        @php
                            $statusColors = [
                                'pending' => 'bg-slate-100 text-slate-700',
                                'confirmed' => 'bg-amber-100 text-amber-700',
                                'in_progress' => 'bg-blue-100 text-blue-700',
                                'completed' => 'bg-emerald-100 text-emerald-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                'no_show' => 'bg-rose-100 text-rose-700',
                            ];
                            $statusValue = $booking->status->value ?? $booking->status;
                        @endphp
                        <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full {{ $statusColors[$statusValue] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ $booking->status->label() }}
                        </span>
                    </div>

                    <!-- Booking Details -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-sm text-slate-500">예약번호</span>
                            <span class="text-sm font-mono font-medium text-slate-900 bg-slate-100 px-2 py-0.5 rounded">{{ $booking->booking_code }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-sm text-slate-500">예약일</span>
                            <span class="text-sm font-medium text-slate-900">{{ $booking->schedule?->date?->format('Y년 m월 d일') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-sm text-slate-500">총 인원</span>
                            <span class="text-sm font-medium text-slate-900">{{ $booking->quantity }}명</span>
                        </div>
                        @if($booking->adult_count)
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-sm text-slate-500">성인</span>
                                <span class="text-sm font-medium text-slate-900">{{ $booking->adult_count }}명</span>
                            </div>
                        @endif
                        @if($booking->child_count)
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-slate-500">아동</span>
                                <span class="text-sm font-medium text-slate-900">{{ $booking->child_count }}명</span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 pt-6 border-t border-slate-100 space-y-3">
                        @if($booking->status->value === 'confirmed')
                            <form method="POST" action="{{ route('guide.bookings.start', $booking) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-500/25 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    투어 시작
                                </button>
                            </form>
                        @endif

                        @if($booking->status->value === 'in_progress')
                            <form method="POST" action="{{ route('guide.bookings.complete', $booking) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-emerald-600 hover:to-emerald-700 shadow-lg shadow-emerald-500/25 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    투어 완료
                                </button>
                            </form>
                        @endif

                        @if(in_array($booking->status->value, ['confirmed', 'in_progress']))
                            <form method="POST" action="{{ route('guide.bookings.no-show', $booking) }}"
                                  onsubmit="return confirm('노쇼로 처리하시겠습니까?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-xl hover:bg-red-100 border border-red-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    노쇼 처리
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Product Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-lg shadow-teal-500/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-900">고객 정보</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">이름</span>
                            <p class="mt-1 text-sm font-medium text-slate-900">{{ $booking->user->name }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">이메일</span>
                            <p class="mt-1 text-sm text-slate-900">{{ $booking->user->email }}</p>
                        </div>
                        @if($booking->contact_phone)
                            <div>
                                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">연락처</span>
                                <p class="mt-1 text-sm text-slate-900">{{ $booking->contact_phone }}</p>
                            </div>
                        @endif
                    </div>
                    @if($booking->special_requests)
                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">특별 요청사항</span>
                            <div class="mt-2 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                <p class="text-sm text-amber-800">{{ $booking->special_requests }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-900">상품 정보</h2>
                </div>
                <div class="p-6">
                    <div class="flex gap-4">
                        @if($booking->product->images->first())
                            <img src="{{ $booking->product->images->first()->url }}" alt=""
                                 class="w-24 h-24 rounded-xl object-cover border border-slate-200">
                        @else
                            <div class="w-24 h-24 rounded-xl bg-slate-100 flex items-center justify-center border border-slate-200">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h4 class="text-base font-semibold text-slate-900 truncate">{{ $booking->product->getTranslation('ko')?->title ?? '상품' }}</h4>
                            <p class="text-sm text-slate-500 mt-1">{{ $booking->product->vendor->business_name }}</p>
                            @if($booking->product->region)
                                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $booking->product->region?->label() ?? '' }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @if($translation = $booking->product->getTranslation('ko'))
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <p class="text-sm text-slate-600 leading-relaxed">{{ Str::limit($translation->description, 200) }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Vendor Info -->
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-900">제공자 정보</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">사업자명</span>
                            <p class="mt-1 text-sm font-medium text-slate-900">{{ $booking->product->vendor->business_name }}</p>
                        </div>
                        @if($booking->product->vendor->phone)
                            <div>
                                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">연락처</span>
                                <p class="mt-1 text-sm text-slate-900">{{ $booking->product->vendor->phone }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guide>
