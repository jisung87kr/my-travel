<x-layouts.vendor>
    <x-slot name="header">상품 수정</x-slot>

    <div class="max-w-4xl">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('vendor.products.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                상품 목록으로
            </a>
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
            <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full {{ $statusColors[$product->status] ?? 'bg-slate-100 text-slate-600' }}">
                {{ $statusLabels[$product->status] ?? $product->status }}
            </span>
        </div>

        <div id="product-form-container"
             data-action="{{ route('vendor.products.update', $product) }}"
             data-method="PUT"
             data-redirect="{{ route('vendor.products.index') }}"
             data-regions="{{ json_encode($regions) }}"
             data-types="{{ json_encode($types) }}"
             data-product="{{ json_encode($productData) }}">
        </div>

        <!-- Fallback form for non-JS -->
        <noscript>
            <form method="POST" action="{{ route('vendor.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Info -->
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4">기본 정보</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">상품 유형 *</label>
                            <select name="type" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                                @foreach($types as $type)
                                    <option value="{{ $type['value'] }}" {{ $product->type->value === $type['value'] ? 'selected' : '' }}>
                                        {{ $type['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">지역 *</label>
                            <select name="region" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                                @foreach($regions as $region)
                                    <option value="{{ $region['value'] }}" {{ $product->region->value === $region['value'] ? 'selected' : '' }}>
                                        {{ $region['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">소요시간 (분)</label>
                            <input type="number" name="duration" min="0" value="{{ $product->duration }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">최대 인원</label>
                            <input type="number" name="max_persons" min="1" value="{{ $product->max_persons }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">예약 유형 *</label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="booking_type" value="instant"
                                           {{ $product->booking_type === 'instant' ? 'checked' : '' }} class="w-4 h-4 text-violet-600 border-slate-300 focus:ring-violet-500">
                                    <span class="text-sm text-slate-700">자동 확정 (Instant Booking)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="booking_type" value="request"
                                           {{ $product->booking_type === 'request' ? 'checked' : '' }} class="w-4 h-4 text-violet-600 border-slate-300 focus:ring-violet-500">
                                    <span class="text-sm text-slate-700">승인 필요 (Request Booking)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $koTranslation = $product->getTranslation('ko');
                @endphp

                <!-- Korean Content -->
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4">상품 정보 (한국어) *</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">상품명 *</label>
                            <input type="text" name="translations[ko][title]" required
                                   value="{{ $koTranslation?->title }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">간단 설명</label>
                            <input type="text" name="translations[ko][short_description]"
                                   value="{{ $koTranslation?->short_description }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">상세 설명 *</label>
                            <textarea name="translations[ko][description]" rows="5" required
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors resize-none">{{ $koTranslation?->description }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">포함 사항</label>
                            <textarea name="translations[ko][includes]" rows="3"
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors resize-none"
                                      placeholder="줄바꿈으로 구분">{{ $koTranslation?->includes }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">불포함 사항</label>
                            <textarea name="translations[ko][excludes]" rows="3"
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors resize-none"
                                      placeholder="줄바꿈으로 구분">{{ $koTranslation?->excludes }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Prices -->
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4">가격 설정</h3>

                    @php
                        $adultPrice = $product->prices->where('price_type', 'adult')->first();
                        $childPrice = $product->prices->where('price_type', 'child')->first();
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">성인 가격 (원) *</label>
                            <input type="number" name="prices[adult]" min="0" required
                                   value="{{ $adultPrice?->price }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">아동 가격 (원)</label>
                            <input type="number" name="prices[child]" min="0"
                                   value="{{ $childPrice?->price }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Existing Images -->
                @if($product->images->count() > 0)
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <h3 class="text-sm font-semibold text-slate-900 mb-4">현재 이미지</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($product->images as $image)
                                <div class="relative group">
                                    <img src="{{ $image->url }}" alt="상품 이미지"
                                         class="w-full h-32 object-cover rounded-xl">
                                    <button type="button"
                                            onclick="deleteImage({{ $image->id }})"
                                            class="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- New Images -->
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4">새 이미지 추가</h3>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">상품 이미지</label>
                        <input type="file" name="images[]" multiple accept="image/*"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                        <p class="text-xs text-slate-500 mt-2">최대 10장, JPG/PNG 형식</p>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('vendor.products.index') }}"
                       class="px-6 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                        취소
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-violet-600 to-violet-700 rounded-xl hover:from-violet-700 hover:to-violet-800 shadow-lg shadow-violet-500/25 transition-all">
                        저장
                    </button>
                </div>
            </form>
        </noscript>
    </div>

    @push('scripts')
    <script>
        function deleteImage(imageId) {
            if (confirm('이미지를 삭제하시겠습니까?')) {
                fetch(`{{ route('vendor.products.images.destroy', [$product->id, '']) }}/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        }
    </script>
    @endpush
</x-layouts.vendor>
