<x-layouts.vendor>
    <x-slot name="header">상품 관리</x-slot>

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500">총 {{ $products->total() }}개의 상품</p>
        </div>
        <a href="{{ route('vendor.products.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-violet-600 to-violet-700 text-white text-sm font-medium rounded-xl hover:from-violet-700 hover:to-violet-800 shadow-lg shadow-violet-500/25 transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            새 상품 등록
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-2xl border border-slate-200/60 p-4">
        <form method="GET" action="{{ route('vendor.products.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="상품명 검색..."
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
            </div>
            <div>
                <select name="status" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                    <option value="">전체 상태</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>초안</option>
                    <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>검토중</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>활성</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>비활성</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2.5 bg-slate-900 text-white text-sm font-medium rounded-xl hover:bg-slate-800 transition-colors">
                    검색
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('vendor.products.index') }}" class="px-4 py-2.5 text-slate-600 text-sm font-medium hover:text-slate-900 transition-colors">
                        초기화
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            상품
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            유형
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            가격
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            상태
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            예약
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            관리
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        @if($product->images->first())
                                            <img src="{{ $product->images->first()->url }}"
                                                 alt="{{ $product->getTranslation('ko')?->title }}"
                                                 class="w-12 h-12 rounded-xl object-cover">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-900 truncate">
                                            {{ $product->getTranslation('ko')?->title ?? '제목 없음' }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            {{ $product->region->label() }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm text-slate-900">{{ $product->type->label() }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        {{ $product->booking_type === 'instant' ? '자동확정' : '승인필요' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $lowestPrice = $product->prices->where('is_active', true)->min('price');
                                @endphp
                                @if($lowestPrice)
                                    <span class="text-sm font-medium text-slate-900">{{ number_format($lowestPrice) }}원~</span>
                                @else
                                    <span class="text-sm text-slate-400">가격 미설정</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-slate-100 text-slate-700',
                                        'pending_review' => 'bg-amber-100 text-amber-700',
                                        'active' => 'bg-emerald-100 text-emerald-700',
                                        'inactive' => 'bg-red-100 text-red-700',
                                    ];
                                    $statusLabels = [
                                        'draft' => '초안',
                                        'pending_review' => '검토중',
                                        'active' => '활성',
                                        'inactive' => '비활성',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$product->status] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $statusLabels[$product->status] ?? $product->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600">{{ $product->booking_count ?? 0 }}건</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('vendor.products.edit', $product) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-violet-600 hover:text-violet-700 hover:bg-violet-50 rounded-lg transition-colors">
                                        수정
                                    </a>
                                    <a href="{{ route('vendor.products.schedules.index', $product) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">
                                        일정
                                    </a>
                                    @if($product->status === 'draft')
                                        <form method="POST" action="{{ route('vendor.products.submit', $product) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-colors">
                                                검토요청
                                            </button>
                                        </form>
                                    @elseif($product->status === 'active')
                                        <form method="POST" action="{{ route('vendor.products.deactivate', $product) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                                비활성화
                                            </button>
                                        </form>
                                    @elseif($product->status === 'inactive')
                                        <form method="POST" action="{{ route('vendor.products.activate', $product) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">
                                                활성화
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                                    </svg>
                                </div>
                                <p class="text-slate-500 mb-2">등록된 상품이 없습니다</p>
                                <a href="{{ route('vendor.products.create') }}" class="text-sm font-medium text-violet-600 hover:text-violet-700 transition-colors">
                                    첫 상품을 등록해보세요
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-6">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</x-layouts.vendor>
