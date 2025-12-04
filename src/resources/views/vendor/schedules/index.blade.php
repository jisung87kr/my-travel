<x-layouts.vendor>
    <x-slot name="header">일정 관리</x-slot>

    <div id="schedule-calendar-container"
         data-products="{{ json_encode($products) }}"
         data-csrf-token="{{ csrf_token() }}">
    </div>

    <!-- Fallback for non-JS -->
    <noscript>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">상품 선택</h2>

            <form method="GET" action="{{ route('vendor.schedules.index') }}" class="mb-6">
                <select name="product" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">상품을 선택하세요</option>
                    @foreach($products as $product)
                        <option value="{{ $product['id'] }}" {{ request('product') == $product['id'] ? 'selected' : '' }}>
                            {{ $product['title'] }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if(request('product') && isset($schedules))
                <h3 class="text-md font-semibold mb-4">일정 목록</h3>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">날짜</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">총 재고</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">가용 재고</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">상태</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($schedules as $schedule)
                            <tr>
                                <td class="px-6 py-4">{{ $schedule['date'] }}</td>
                                <td class="px-6 py-4">{{ $schedule['total_count'] }}</td>
                                <td class="px-6 py-4">{{ $schedule['available_count'] }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $schedule['is_active'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $schedule['is_active'] ? '예약가능' : '마감' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    등록된 일정이 없습니다.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </noscript>
</x-layouts.vendor>
