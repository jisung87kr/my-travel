<x-layouts.vendor>
    <x-slot name="header">예약 관리</x-slot>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('vendor.bookings.index') }}" class="flex flex-wrap gap-4">
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
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
                <select name="product" class="px-4 py-2 border border-gray-300 rounded-lg">
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
                       class="px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       placeholder="종료일"
                       class="px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                    검색
                </button>
                <a href="{{ route('vendor.bookings.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                    초기화
                </a>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-yellow-800">대기중</p>
            <p class="text-2xl font-semibold text-yellow-900">{{ $stats['pending'] ?? 0 }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-800">확정</p>
            <p class="text-2xl font-semibold text-blue-900">{{ $stats['confirmed'] ?? 0 }}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-sm text-green-800">완료</p>
            <p class="text-2xl font-semibold text-green-900">{{ $stats['completed'] ?? 0 }}</p>
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <p class="text-sm text-gray-800">취소/노쇼</p>
            <p class="text-2xl font-semibold text-gray-900">{{ ($stats['cancelled'] ?? 0) + ($stats['no_show'] ?? 0) }}</p>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">예약번호</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상품</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">고객</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">일정</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">인원</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">금액</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상태</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">관리</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono">{{ $booking->booking_code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate">
                                {{ $booking->product->getTranslation('ko')?->title ?? '상품' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $booking->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->contact_email ?? $booking->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm">{{ $booking->schedule?->date?->format('Y-m-d') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm">
                                성인 {{ $booking->adult_count }}
                                @if($booking->child_count > 0)
                                    / 아동 {{ $booking->child_count }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium">{{ number_format($booking->total_price) }}원</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full {{ $booking->status->color() }}">
                                {{ $booking->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('vendor.bookings.show', $booking) }}"
                                   class="text-blue-600 hover:text-blue-900 text-sm">
                                    상세
                                </a>
                                @if($booking->status->value === 'pending')
                                    <form method="POST" action="{{ route('vendor.bookings.approve', $booking) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-900 text-sm">
                                            승인
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('vendor.bookings.reject', $booking) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                            거절
                                        </button>
                                    </form>
                                @elseif($booking->status->value === 'confirmed')
                                    <form method="POST" action="{{ route('vendor.bookings.complete', $booking) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-900 text-sm">
                                            완료
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('vendor.bookings.no-show', $booking) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                            노쇼
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            예약이 없습니다.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="mt-6">
            {{ $bookings->withQueryString()->links() }}
        </div>
    @endif
</x-layouts.vendor>
