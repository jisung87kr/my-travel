<x-layouts.admin>
    <x-slot name="header">상품 상세</x-slot>

    <!-- Breadcrumb -->
    <nav class="mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('admin.products.index') }}" class="text-slate-500 hover:text-blue-600 transition-colors">상품 관리</a>
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-800 font-medium">{{ $product->getTranslation('ko')?->title ?? $product->getTranslation('ko')?->name ?? '상품' }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Info Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <!-- Product Image -->
                @if($product->images->first())
                    <img src="{{ $product->images->first()->url }}" alt="" class="w-full h-56 object-cover">
                @else
                    <div class="w-full h-56 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                        <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif

                <!-- Product Details -->
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $statusValue = $product->status->value ?? $product->status;
                        @endphp
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-sm text-slate-500">상태</span>
                            @if($statusValue === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-emerald-100 text-emerald-700 ring-1 ring-inset ring-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    활성
                                </span>
                            @elseif($statusValue === 'pending_review')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-amber-100 text-amber-700 ring-1 ring-inset ring-amber-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    검토중
                                </span>
                            @elseif($statusValue === 'inactive')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    비활성
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                    {{ $statusValue }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-sm text-slate-500">지역</span>
                            <span class="text-sm font-medium text-slate-700">{{ $product->region?->label() ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-sm text-slate-500">제공자</span>
                            <a href="{{ route('admin.vendors.show', $product->vendor) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                {{ $product->vendor->business_name }}
                            </a>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm text-slate-500">등록일</span>
                            <span class="text-sm font-medium text-slate-700">{{ $product->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 pt-6 border-t border-slate-200 space-y-3">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            수정
                        </a>

                        @if($statusValue === 'pending_review' || $statusValue === 'pending')
                            <form method="POST" action="{{ route('admin.products.approve', $product) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all shadow-lg shadow-emerald-500/25 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    승인하기
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.products.reject', $product) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-50 transition-colors font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    반려하기
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.products.toggle', $product) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 {{ $statusValue === 'active' ? 'bg-gradient-to-r from-amber-500 to-amber-600 shadow-amber-500/25' : 'bg-gradient-to-r from-emerald-600 to-emerald-700 shadow-emerald-500/25' }} text-white rounded-xl hover:opacity-90 transition-all shadow-lg font-medium">
                                    @if($statusValue === 'active')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        비활성화
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        활성화
                                    @endif
                                </button>
                            </form>
                        @endif

                        @php
                            $hasActiveBookings = $product->bookings()->whereNotIn('status', ['cancelled', 'completed', 'no_show'])->count() > 0;
                        @endphp
                        @if(!$hasActiveBookings)
                            <button type="button" onclick="openDeleteModal()" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-50 transition-colors font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
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
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200/60 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800">상품 정보</h3>
                </div>
                <div class="p-6">
                    @foreach($product->translations as $translation)
                        <div class="mb-6 last:mb-0">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-500/20">
                                    {{ strtoupper($translation->locale->value ?? $translation->locale) }}
                                </span>
                                <span class="font-semibold text-slate-800">{{ $translation->title ?? $translation->name }}</span>
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed">{{ $translation->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Prices -->
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200/60 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800">가격 정보</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @forelse($product->prices as $price)
                            <div class="flex justify-between items-center py-3 px-4 bg-slate-50 rounded-xl">
                                <span class="text-slate-600 font-medium">{{ $price->label }}</span>
                                <span class="text-lg font-bold text-slate-800">{{ number_format($price->amount) }}원</span>
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-500">
                                가격 정보가 없습니다.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200/60 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-800">최근 리뷰</h3>
                    </div>
                    <span class="text-sm text-slate-500">총 {{ $product->reviews->count() }}건</span>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($product->reviews as $review)
                            <div class="pb-4 border-b border-slate-100 last:border-0 last:pb-0">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <span class="font-medium text-slate-800">{{ $review->user->name }}</span>
                                        <div class="flex items-center gap-0.5 text-amber-400 mt-1">
                                            @for($i = 0; $i < $review->rating; $i++)
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endfor
                                            @for($i = $review->rating; $i < 5; $i++)
                                                <svg class="w-4 h-4 fill-current text-slate-200" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="text-sm text-slate-500">{{ $review->created_at->format('Y-m-d') }}</span>
                                </div>
                                <p class="text-slate-600 text-sm">{{ $review->content }}</p>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                                <p class="text-slate-500 font-medium">리뷰가 없습니다.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Link -->
    <div class="mt-6">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            목록으로 돌아가기
        </a>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">상품 삭제</h3>
                    <p class="text-sm text-slate-500">이 작업은 되돌릴 수 없습니다.</p>
                </div>
            </div>
            <p class="text-slate-600 mb-6">정말 이 상품을 삭제하시겠습니까?</p>
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                        취소
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all shadow-lg shadow-red-500/25 font-medium">
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
