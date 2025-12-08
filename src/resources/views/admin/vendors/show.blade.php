<x-layouts.admin>
    <x-slot name="header">제공자 상세 - {{ $vendor->business_name }}</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Vendor Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-2xl font-bold mx-auto">
                        {{ mb_substr($vendor->business_name, 0, 1) }}
                    </div>
                    <h2 class="mt-4 text-xl font-semibold">{{ $vendor->business_name }}</h2>
                    <p class="text-gray-500">{{ $vendor->user->name }}</p>
                </div>

                <div class="space-y-4">
                    @php
                        $statusValue = $vendor->status->value ?? $vendor->status;
                    @endphp
                    <div class="flex justify-between">
                        <span class="text-gray-500">상태</span>
                        <span>
                            @if($statusValue === 'approved')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">승인됨</span>
                            @elseif($statusValue === 'pending')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">대기중</span>
                            @elseif($statusValue === 'suspended')
                                <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">정지됨</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">거절됨</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">사업자번호</span>
                        <span>{{ $vendor->business_number ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">이메일</span>
                        <span>{{ $vendor->user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">연락처</span>
                        <span>{{ $vendor->phone ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">신청일</span>
                        <span>{{ $vendor->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>

                @if($vendor->description)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">소개</h4>
                        <p class="text-gray-900">{{ $vendor->description }}</p>
                    </div>
                @endif

                @if($vendor->rejection_reason)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-red-500 mb-2">거절 사유</h4>
                        <p class="text-gray-900">{{ $vendor->rejection_reason }}</p>
                    </div>
                @endif

                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                    <a href="{{ route('admin.vendors.edit', $vendor) }}"
                       class="block w-full px-4 py-2 bg-indigo-600 text-white text-center rounded-lg hover:bg-indigo-700">
                        수정
                    </a>

                    @if($statusValue === 'pending')
                        <form method="POST" action="{{ route('admin.vendors.approve', $vendor) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                승인하기
                            </button>
                        </form>
                        <button type="button" onclick="openRejectModal()" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            거절하기
                        </button>
                    @elseif($statusValue === 'rejected')
                        <form method="POST" action="{{ route('admin.vendors.approve', $vendor) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                승인으로 변경
                            </button>
                        </form>
                    @elseif($statusValue === 'approved')
                        <form method="POST" action="{{ route('admin.vendors.suspend', $vendor) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                                정지하기
                            </button>
                        </form>
                    @elseif($statusValue === 'suspended')
                        <form method="POST" action="{{ route('admin.vendors.approve', $vendor) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                정지 해제
                            </button>
                        </form>
                    @endif

                    @if($vendor->products()->count() === 0)
                        <button type="button" onclick="openDeleteModal()" class="w-full px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50">
                            제공자 삭제
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold">등록 상품</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상품명</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">지역</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상태</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">관리</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($vendor->products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $product->getTranslation('ko')?->title ?? '상품' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $product->region?->label() ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($product->status === 'active')
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">활성</span>
                                        @elseif($product->status === 'pending_review')
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">검토중</span>
                                        @elseif($product->status === 'inactive')
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">비활성</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ $product->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('admin.products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900">
                                            상세
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        등록된 상품이 없습니다.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.vendors.index') }}" class="text-indigo-600 hover:underline">&larr; 목록으로</a>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">제공자 거절</h3>
            <form method="POST" action="{{ route('admin.vendors.reject', $vendor) }}">
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">제공자 삭제</h3>
            <p class="text-gray-600 mb-6">정말 이 제공자를 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.</p>
            <form method="POST" action="{{ route('admin.vendors.destroy', $vendor) }}">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                        취소
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        삭제
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }

        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
</x-layouts.admin>
