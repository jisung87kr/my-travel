<x-layouts.vendor>
    <x-slot name="header">예약 관리</x-slot>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-2xl border border-slate-200/60 p-4">
        <form method="GET" action="{{ route('vendor.bookings.index') }}" class="flex flex-wrap gap-4">
            <div>
                <select name="status" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
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
                <select name="product" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                    <option value="">전체 상품</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product') == $product->id ? 'selected' : '' }}>
                            {{ $product->getTranslation('ko')?->title ?? '상품' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       placeholder="시작일"
                       class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
            </div>
            <div>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       placeholder="종료일"
                       class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2.5 bg-slate-900 text-white text-sm font-medium rounded-xl hover:bg-slate-800 transition-colors">
                    검색
                </button>
                @if(request()->hasAny(['status', 'product', 'date_from', 'date_to']))
                    <a href="{{ route('vendor.bookings.index') }}" class="px-4 py-2.5 text-slate-600 text-sm font-medium hover:text-slate-900 transition-colors">
                        초기화
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-amber-50 border border-amber-200/60 rounded-2xl p-4">
            <p class="text-sm font-medium text-amber-700">대기중</p>
            <p class="text-2xl font-bold text-amber-900 mt-1">{{ $stats['pending'] ?? 0 }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-200/60 rounded-2xl p-4">
            <p class="text-sm font-medium text-blue-700">확정</p>
            <p class="text-2xl font-bold text-blue-900 mt-1">{{ $stats['confirmed'] ?? 0 }}</p>
        </div>
        <div class="bg-emerald-50 border border-emerald-200/60 rounded-2xl p-4">
            <p class="text-sm font-medium text-emerald-700">완료</p>
            <p class="text-2xl font-bold text-emerald-900 mt-1">{{ $stats['completed'] ?? 0 }}</p>
        </div>
        <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4">
            <p class="text-sm font-medium text-slate-600">취소/노쇼</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ ($stats['cancelled'] ?? 0) + ($stats['no_show'] ?? 0) }}</p>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100">
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
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono text-slate-900">{{ $booking->booking_code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-900 max-w-xs truncate">
                                    {{ $booking->product->getTranslation('ko')?->title ?? '상품' }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $booking->user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $booking->contact_email ?? $booking->user->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-900">{{ $booking->schedule?->date?->format('Y-m-d') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600">
                                    성인 {{ $booking->adult_count }}
                                    @if($booking->child_count > 0)
                                        / 아동 {{ $booking->child_count }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-slate-900">{{ number_format($booking->total_price) }}원</span>
                            </td>
                            <td class="px-6 py-4">
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
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$statusValue] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $booking->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('vendor.bookings.show', $booking) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-violet-600 hover:text-violet-700 hover:bg-violet-50 rounded-lg transition-colors">
                                        상세
                                    </a>
                                    @if($booking->status->value === 'pending')
                                        <form method="POST" action="{{ route('vendor.bookings.approve', $booking) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">
                                                승인
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('vendor.bookings.reject', $booking) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                                거절
                                            </button>
                                        </form>
                                    @elseif($booking->status->value === 'confirmed')
                                        <form method="POST" action="{{ route('vendor.bookings.complete', $booking) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">
                                                완료
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('vendor.bookings.no-show', $booking) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                                노쇼
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <p class="text-slate-500">예약이 없습니다</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="mt-6">
            {{ $bookings->withQueryString()->links() }}
        </div>
    @endif
</x-layouts.vendor>
