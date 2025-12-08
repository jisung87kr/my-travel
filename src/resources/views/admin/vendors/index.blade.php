<x-layouts.admin>
    <x-slot name="header">제공자 관리</x-slot>

    <!-- Header Actions -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="text-sm text-gray-500">
            총 {{ $vendors->total() }}개의 제공자
        </div>
        <a href="{{ route('admin.vendors.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            제공자 등록
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-yellow-50 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            <p class="text-sm text-gray-500">승인 대기</p>
        </div>
        <div class="bg-green-50 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
            <p class="text-sm text-gray-500">승인됨</p>
        </div>
        <div class="bg-red-50 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
            <p class="text-sm text-gray-500">거절됨</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.vendors.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="사업자명 또는 사용자 검색..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">전체 상태</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>대기중</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>승인됨</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>거절됨</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    검색
                </button>
            </div>
        </form>
    </div>

    <!-- Vendors Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">사업자</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">담당자</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">신청일</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상태</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">관리</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($vendors as $vendor)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $vendor->business_name }}</div>
                            <div class="text-sm text-gray-500">{{ $vendor->business_number ?? '사업자번호 미등록' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $vendor->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $vendor->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $vendor->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusValue = $vendor->status->value ?? $vendor->status;
                            @endphp
                            @if($statusValue === 'approved')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">승인됨</span>
                            @elseif($statusValue === 'pending')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">대기중</span>
                            @elseif($statusValue === 'suspended')
                                <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">정지됨</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">거절됨</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-indigo-600 hover:text-indigo-900">
                                    상세
                                </a>
                                <a href="{{ route('admin.vendors.edit', $vendor) }}" class="text-gray-600 hover:text-gray-900">
                                    수정
                                </a>
                                @if($vendor->status->value === 'pending' || $vendor->status === 'pending')
                                    <form method="POST" action="{{ route('admin.vendors.approve', $vendor) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                            승인
                                        </button>
                                    </form>
                                    <button type="button" onclick="openRejectModal({{ $vendor->id }})" class="text-red-600 hover:text-red-900">
                                        거절
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            제공자가 없습니다.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($vendors->hasPages())
        <div class="mt-6">
            {{ $vendors->withQueryString()->links() }}
        </div>
    @endif

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">제공자 거절</h3>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">거절 사유</label>
                    <textarea name="reason" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                              placeholder="거절 사유를 입력하세요..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                        취소
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        거절
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(vendorId) {
            document.getElementById('rejectForm').action = `/admin/vendors/${vendorId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }
    </script>
</x-layouts.admin>
