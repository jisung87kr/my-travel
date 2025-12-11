<x-layouts.vendor>
    <x-slot name="header">예약 관리</x-slot>

    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['total'] ?? $bookings->total() }}</p>
                        <p class="text-sm text-slate-500">전체 예약</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['pending'] ?? 0 }}</p>
                        <p class="text-sm text-slate-500">대기중</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['confirmed'] ?? 0 }}</p>
                        <p class="text-sm text-slate-500">확정</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['completed'] ?? 0 }}</p>
                        <p class="text-sm text-slate-500">완료</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ ($stats['cancelled'] ?? 0) + ($stats['no_show'] ?? 0) }}</p>
                        <p class="text-sm text-slate-500">취소/노쇼</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6">
            <form method="GET" action="{{ route('vendor.bookings.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="예약번호 또는 고객명 검색..."
                               class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 focus:bg-white transition-all">
                    </div>
                </div>
                <div>
                    <select name="status" class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 focus:bg-white transition-all min-w-[140px]">
                        <option value="">전체 상태</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>대기중</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>확정</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>진행중</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>완료</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>취소</option>
                        <option value="no_show" {{ request('status') === 'no_show' ? 'selected' : '' }}>노쇼</option>
                    </select>
                </div>
                <div>
                    <select name="product" class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 focus:bg-white transition-all min-w-[180px]">
                        <option value="">전체 상품</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product') == $product->id ? 'selected' : '' }}>
                                {{ $product->getTranslation('ko')?->title ?? '상품' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 focus:bg-white transition-all">
                    <span class="text-slate-400">~</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 focus:bg-white transition-all">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-violet-600 to-violet-700 text-white rounded-xl hover:from-violet-700 hover:to-violet-800 font-medium shadow-lg shadow-violet-500/30 transition-all">
                        검색
                    </button>
                    @if(request()->hasAny(['search', 'status', 'product', 'date_from', 'date_to']))
                        <a href="{{ route('vendor.bookings.index') }}" class="px-4 py-3 text-slate-600 hover:text-slate-900 font-medium transition-colors">
                            초기화
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">예약번호</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">상품</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">고객</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">일정</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">인원</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">금액</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">상태</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">관리</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono font-medium text-slate-900 bg-slate-100 px-2 py-1 rounded-lg">{{ $booking->booking_code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($booking->product->images->first())
                                            <img src="{{ $booking->product->images->first()->url }}" alt=""
                                                 class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-violet-100 to-purple-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-slate-900 truncate max-w-[200px]">
                                                {{ $booking->product->getTranslation('ko')?->title ?? '상품' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-100 to-purple-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-bold text-violet-600">{{ mb_substr($booking->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">{{ $booking->user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $booking->contact_email ?? $booking->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">{{ $booking->schedule?->date?->format('Y-m-d') }}</div>
                                    <div class="text-xs text-slate-500">{{ $booking->schedule?->date?->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">
                                        성인 {{ $booking->adult_count }}
                                        @if($booking->child_count > 0)
                                            <span class="text-slate-400">/</span> 아동 {{ $booking->child_count }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-slate-900">{{ number_format($booking->total_price) }}원</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusValue = $booking->status->value ?? $booking->status;
                                    @endphp
                                    @if($statusValue === 'confirmed')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @elseif($statusValue === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @elseif($statusValue === 'in_progress')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @elseif($statusValue === 'completed')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @elseif($statusValue === 'cancelled')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-50 text-slate-700 ring-1 ring-inset ring-slate-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @elseif($statusValue === 'no_show')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-50 text-slate-700 ring-1 ring-inset ring-slate-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div
                                        data-booking-actions
                                        data-booking-id="{{ $booking->id }}"
                                        data-booking-status="{{ $statusValue }}"
                                    ></div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <p class="text-slate-500 font-medium">예약이 없습니다</p>
                                        <p class="text-sm text-slate-400 mt-1">검색 조건을 변경해보세요</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($bookings->hasPages())
            <div class="flex justify-center">
                {{ $bookings->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-layouts.vendor>
