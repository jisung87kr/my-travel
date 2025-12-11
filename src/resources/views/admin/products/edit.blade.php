<x-layouts.admin>
    <x-slot name="header">상품 수정</x-slot>

    <div class="max-w-5xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('admin.products.index') }}" class="text-slate-500 hover:text-blue-600 transition-colors">상품 관리</a>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('admin.products.show', $product) }}" class="text-slate-500 hover:text-blue-600 transition-colors">{{ $product->getTranslation('ko')?->name ?? '상품 상세' }}</a>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-slate-900 font-medium">수정</span>
            </nav>
        </div>

        <!-- Product Header Card -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex items-center gap-4">
                    @if($product->primaryImage)
                        <img src="{{ $product->primaryImage->url }}" alt="" class="w-20 h-20 object-cover rounded-xl shadow-md">
                    @else
                        <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                            <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-slate-900">{{ $product->getTranslation('ko')?->name ?? '상품명 없음' }}</h2>
                        <p class="text-slate-500 text-sm mt-1">{{ $product->vendor->company_name }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            @php
                                $statusValue = $product->status->value ?? $product->status;
                            @endphp
                            @if($statusValue === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    활성
                                </span>
                            @elseif($statusValue === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    승인 대기
                                </span>
                            @elseif($statusValue === 'draft')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-50 text-slate-700 ring-1 ring-inset ring-slate-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                    임시저장
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    비활성
                                </span>
                            @endif
                            <span class="text-xs text-slate-400">ID: {{ $product->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            @include('admin.products._form', [
                'product' => $product,
                'vendors' => $vendors,
                'types' => $types,
                'regions' => $regions,
                'bookingTypes' => $bookingTypes,
            ])

            <!-- 버튼 -->
            <div class="flex items-center justify-between pt-2">
                <div>
                    @php
                        $hasActiveBookings = $product->bookings()->whereNotIn('status', ['cancelled', 'completed', 'no_show'])->count() > 0;
                    @endphp
                    @if(!$hasActiveBookings)
                        <button type="button" onclick="confirmDelete()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-xl transition-all font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            상품 삭제
                        </button>
                    @else
                        <span class="text-sm text-slate-500 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            진행 중인 예약이 있어 삭제할 수 없습니다
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.products.show', $product) }}"
                       class="px-6 py-3 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:border-slate-300 font-medium transition-all">
                        취소
                    </a>
                    <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-medium shadow-lg shadow-blue-500/30 transition-all">
                        저장
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl mx-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">상품 삭제</h3>
                    <p class="text-sm text-slate-500">이 작업은 되돌릴 수 없습니다</p>
                </div>
            </div>
            <p class="text-slate-600 mb-6 pl-16">정말 이 상품을 삭제하시겠습니까? 상품에 연결된 모든 데이터가 함께 삭제됩니다.</p>
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="px-5 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        취소
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 font-medium shadow-lg shadow-red-500/30 transition-all">
                        삭제
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        // Close modal on outside click
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</x-layouts.admin>