<x-layouts.vendor>
    <x-slot name="header">상품 관리</x-slot>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-medium">내 상품 목록</h2>
            <p class="text-sm text-gray-500">총 {{ $products->total() }}개의 상품</p>
        </div>
        <a href="{{ route('vendor.products.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            + 새 상품 등록
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('vendor.products.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="상품명 검색..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">전체 상태</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>초안</option>
                    <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>검토중</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>활성</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>비활성</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                    검색
                </button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        상품
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        유형
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        가격
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        상태
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        예약
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        관리
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    @if($product->images->first())
                                        <img src="{{ $product->images->first()->url }}"
                                             alt="{{ $product->getTranslation('ko')?->title }}"
                                             class="h-12 w-12 rounded-lg object-cover">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $product->getTranslation('ko')?->title ?? '제목 없음' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $product->region->label() }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $product->type->label() }}</span>
                            <div class="text-xs text-gray-500">
                                {{ $product->booking_type === 'instant' ? '자동확정' : '승인필요' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $lowestPrice = $product->prices->where('is_active', true)->min('price');
                            @endphp
                            @if($lowestPrice)
                                <span class="text-sm font-medium text-gray-900">{{ number_format($lowestPrice) }}원~</span>
                            @else
                                <span class="text-sm text-gray-400">가격 미설정</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'pending_review' => 'bg-yellow-100 text-yellow-800',
                                    'active' => 'bg-green-100 text-green-800',
                                    'inactive' => 'bg-red-100 text-red-800',
                                ];
                                $statusLabels = [
                                    'draft' => '초안',
                                    'pending_review' => '검토중',
                                    'active' => '활성',
                                    'inactive' => '비활성',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$product->status] ?? 'bg-gray-100' }}">
                                {{ $statusLabels[$product->status] ?? $product->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->booking_count ?? 0 }}건
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('vendor.products.edit', $product) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    수정
                                </a>
                                <a href="{{ route('vendor.products.schedules.index', $product) }}"
                                   class="text-green-600 hover:text-green-900">
                                    일정
                                </a>
                                @if($product->status === 'draft')
                                    <form method="POST" action="{{ route('vendor.products.submit', $product) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                            검토요청
                                        </button>
                                    </form>
                                @elseif($product->status === 'active')
                                    <form method="POST" action="{{ route('vendor.products.deactivate', $product) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            비활성화
                                        </button>
                                    </form>
                                @elseif($product->status === 'inactive')
                                    <form method="POST" action="{{ route('vendor.products.activate', $product) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                            활성화
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            등록된 상품이 없습니다.
                            <a href="{{ route('vendor.products.create') }}" class="text-blue-600 hover:underline ml-1">
                                첫 상품을 등록해보세요
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-6">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</x-layouts.vendor>
