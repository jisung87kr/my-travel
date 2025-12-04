<x-layouts.vendor>
    <x-slot name="header">상품 수정</x-slot>

    <div class="max-w-4xl">
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('vendor.products.index') }}" class="text-gray-600 hover:text-gray-900">
                &larr; 상품 목록으로
            </a>
            <div class="flex items-center space-x-2">
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
                <span class="px-3 py-1 text-sm rounded-full {{ $statusColors[$product->status] ?? 'bg-gray-100' }}">
                    {{ $statusLabels[$product->status] ?? $product->status }}
                </span>
            </div>
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
            <form method="POST" action="{{ route('vendor.products.update', $product) }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Basic Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">기본 정보</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">상품 유형 *</label>
                            <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                @foreach($types as $type)
                                    <option value="{{ $type['value'] }}" {{ $product->type->value === $type['value'] ? 'selected' : '' }}>
                                        {{ $type['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">지역 *</label>
                            <select name="region" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                @foreach($regions as $region)
                                    <option value="{{ $region['value'] }}" {{ $product->region->value === $region['value'] ? 'selected' : '' }}>
                                        {{ $region['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">소요시간 (분)</label>
                            <input type="number" name="duration" min="0" value="{{ $product->duration }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">최대 인원</label>
                            <input type="number" name="max_persons" min="1" value="{{ $product->max_persons }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">예약 유형 *</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="booking_type" value="instant"
                                           {{ $product->booking_type === 'instant' ? 'checked' : '' }} class="mr-2">
                                    자동 확정 (Instant Booking)
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="booking_type" value="request"
                                           {{ $product->booking_type === 'request' ? 'checked' : '' }} class="mr-2">
                                    승인 필요 (Request Booking)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $koTranslation = $product->getTranslation('ko');
                @endphp

                <!-- Korean Content -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">상품 정보 (한국어) *</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">상품명 *</label>
                            <input type="text" name="translations[ko][title]" required
                                   value="{{ $koTranslation?->title }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">간단 설명</label>
                            <input type="text" name="translations[ko][short_description]"
                                   value="{{ $koTranslation?->short_description }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">상세 설명 *</label>
                            <textarea name="translations[ko][description]" rows="5" required
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ $koTranslation?->description }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">포함 사항</label>
                            <textarea name="translations[ko][includes]" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                      placeholder="줄바꿈으로 구분">{{ $koTranslation?->includes }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">불포함 사항</label>
                            <textarea name="translations[ko][excludes]" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                      placeholder="줄바꿈으로 구분">{{ $koTranslation?->excludes }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Prices -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">가격 설정</h3>

                    @php
                        $adultPrice = $product->prices->where('price_type', 'adult')->first();
                        $childPrice = $product->prices->where('price_type', 'child')->first();
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">성인 가격 (원) *</label>
                            <input type="number" name="prices[adult]" min="0" required
                                   value="{{ $adultPrice?->price }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">아동 가격 (원)</label>
                            <input type="number" name="prices[child]" min="0"
                                   value="{{ $childPrice?->price }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Existing Images -->
                @if($product->images->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">현재 이미지</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($product->images as $image)
                                <div class="relative group">
                                    <img src="{{ $image->url }}" alt="상품 이미지"
                                         class="w-full h-32 object-cover rounded-lg">
                                    <button type="button"
                                            onclick="deleteImage({{ $image->id }})"
                                            class="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
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
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">새 이미지 추가</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">상품 이미지</label>
                        <input type="file" name="images[]" multiple accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <p class="text-sm text-gray-500 mt-1">최대 10장, JPG/PNG 형식</p>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('vendor.products.index') }}"
                       class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        취소
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
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
