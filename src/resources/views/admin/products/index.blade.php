<x-layouts.admin>
    <x-slot name="header">상품 관리</x-slot>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="상품명 검색..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">전체 상태</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>활성</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>비활성</option>
                    <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>검토중</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>임시저장</option>
                </select>
            </div>
            <div>
                <select name="region" class="px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">전체 지역</option>
                    @foreach(\App\Enums\Region::cases() as $region)
                        <option value="{{ $region->value }}" {{ request('region') === $region->value ? 'selected' : '' }}>
                            {{ $region->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상품</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">제공자</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">지역</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상태</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">관리</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($product->images->first())
                                    <img src="{{ $product->images->first()->url }}" alt=""
                                         class="w-12 h-12 rounded-lg object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $product->getTranslation('ko')?->title ?? '상품' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $product->created_at->format('Y-m-d') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $product->vendor->business_name }}</div>
                            <div class="text-sm text-gray-500">{{ $product->vendor->user->name }}</div>
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
                            <a href="{{ route('admin.products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                상세
                            </a>
                            @if($product->status === 'pending_review')
                                <form method="POST" action="{{ route('admin.products.approve', $product) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-2">
                                        승인
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.products.reject', $product) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        반려
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.products.toggle', $product) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $product->status === 'active' ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">
                                        {{ $product->status === 'active' ? '비활성화' : '활성화' }}
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            상품이 없습니다.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="mt-6">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</x-layouts.admin>
