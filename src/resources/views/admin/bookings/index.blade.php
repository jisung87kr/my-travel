<x-layouts.admin>
    <x-slot name="header">예약 관리</x-slot>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="사용자 또는 상품 검색..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">전체 상태</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>대기중</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>확정</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>완료</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>취소</option>
                    <option value="no_show" {{ request('status') === 'no_show' ? 'selected' : '' }}>노쇼</option>
                </select>
            </div>
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    검색
                </button>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">예약번호</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">사용자</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상품</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">예약일</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">금액</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상태</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">관리</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900">{{ $booking->booking_number ?? $booking->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $booking->product->getTranslation('ko')?->title ?? '상품' }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->product->vendor->business_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $booking->booking_date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($booking->total_amount) }}원
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full {{ $booking->status->color() }}">
                                {{ $booking->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                상세
                            </a>
                            @if(!in_array($booking->status->value, ['cancelled', 'no_show', 'completed']))
                                <button type="button" onclick="openCancelModal({{ $booking->id }})"
                                        class="text-red-600 hover:text-red-900">
                                    취소
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            예약이 없습니다.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
        <div class="mt-6">
            {{ $bookings->withQueryString()->links() }}
        </div>
    @endif

    <!-- Cancel Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">예약 취소</h3>
            <form id="cancelForm" method="POST">
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
        function openCancelModal(bookingId) {
            document.getElementById('cancelForm').action = `/admin/bookings/${bookingId}/cancel`;
            document.getElementById('cancelModal').classList.remove('hidden');
            document.getElementById('cancelModal').classList.add('flex');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.getElementById('cancelModal').classList.remove('flex');
        }
    </script>
</x-layouts.admin>
