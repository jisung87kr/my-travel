<x-layouts.admin>
    <x-slot name="header">상품 상세 - {{ $product->getTranslation('ko')?->title ?? '상품' }}</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if($product->images->first())
                    <img src="{{ $product->images->first()->url }}" alt="" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif

                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-500">상태</span>
                            <span>
                                @if($product->status === 'active')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">활성</span>
                                @elseif($product->status === 'pending_review')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">검토중</span>
                                @elseif($product->status === 'inactive')
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">비활성</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ $product->status }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">지역</span>
                            <span>{{ $product->region?->label() ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">제공자</span>
                            <a href="{{ route('admin.vendors.show', $product->vendor) }}" class="text-indigo-600 hover:underline">
                                {{ $product->vendor->business_name }}
                            </a>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">등록일</span>
                            <span>{{ $product->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="block w-full px-4 py-2 bg-indigo-600 text-white text-center rounded-lg hover:bg-indigo-700">
                            수정
                        </a>

                        @php
                            $statusValue = $product->status->value ?? $product->status;
                        @endphp
                        @if($statusValue === 'pending_review' || $statusValue === 'pending')
                            <form method="POST" action="{{ route('admin.products.approve', $product) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    승인하기
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.products.reject', $product) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    반려하기
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.products.toggle', $product) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full px-4 py-2 {{ $statusValue === 'active' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg">
                                    {{ $statusValue === 'active' ? '비활성화' : '활성화' }}
                                </button>
                            </form>
                        @endif

                        @php
                            $hasActiveBookings = $product->bookings()->whereNotIn('status', ['cancelled', 'completed', 'no_show'])->count() > 0;
                        @endphp
                        @if(!$hasActiveBookings)
                            <button type="button" onclick="openDeleteModal()" class="w-full px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50">
                                상품 삭제
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Translations -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4">상품 정보</h3>
                @foreach($product->translations as $translation)
                    <div class="mb-6 last:mb-0">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-1 text-xs rounded bg-gray-100">{{ strtoupper($translation->locale->value ?? $translation->locale) }}</span>
                            <span class="font-medium">{{ $translation->title ?? $translation->name }}</span>
                        </div>
                        <p class="text-gray-600 text-sm">{{ $translation->description }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Prices -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4">가격 정보</h3>
                <div class="space-y-2">
                    @forelse($product->prices as $price)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                            <span class="text-gray-600">{{ $price->label }}</span>
                            <span class="font-medium">{{ number_format($price->amount) }}원</span>
                        </div>
                    @empty
                        <p class="text-gray-500">가격 정보가 없습니다.</p>
                    @endforelse
                </div>
            </div>

            <!-- Reviews -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4">최근 리뷰</h3>
                <div class="space-y-4">
                    @forelse($product->reviews as $review)
                        <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="font-medium">{{ $review->user->name }}</span>
                                    <div class="flex items-center text-yellow-400">
                                        @for($i = 0; $i < $review->rating; $i++)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">{{ $review->created_at->format('Y-m-d') }}</span>
                            </div>
                            <p class="text-gray-600 text-sm">{{ $review->content }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">리뷰가 없습니다.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:underline">&larr; 목록으로</a>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">상품 삭제</h3>
            <p class="text-gray-600 mb-6">정말 이 상품을 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.</p>
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
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
