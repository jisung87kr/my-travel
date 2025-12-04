<x-layouts.vendor>
    <x-slot name="header">새 상품 등록</x-slot>

    <div class="max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('vendor.products.index') }}" class="text-gray-600 hover:text-gray-900">
                &larr; 상품 목록으로
            </a>
        </div>

        <div id="product-form-container"
             data-action="{{ route('vendor.products.store') }}"
             data-method="POST"
             data-redirect="{{ route('vendor.products.index') }}"
             data-regions="{{ json_encode($regions) }}"
             data-types="{{ json_encode($types) }}">
        </div>

        <!-- Fallback form for non-JS -->
        <noscript>
            <form method="POST" action="{{ route('vendor.products.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Basic Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">기본 정보</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">상품 유형 *</label>
                            <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                @foreach($types as $type)
                                    <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">지역 *</label>
                            <select name="region" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                @foreach($regions as $region)
                                    <option value="{{ $region['value'] }}">{{ $region['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">소요시간 (분)</label>
                            <input type="number" name="duration" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">최대 인원</label>
                            <input type="number" name="max_persons" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">예약 유형 *</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="booking_type" value="instant" checked class="mr-2">
                                    자동 확정 (Instant Booking)
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="booking_type" value="request" class="mr-2">
                                    승인 필요 (Request Booking)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Korean Content -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">상품 정보 (한국어) *</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">상품명 *</label>
                            <input type="text" name="translations[ko][title]" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">간단 설명</label>
                            <input type="text" name="translations[ko][short_description]"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">상세 설명 *</label>
                            <textarea name="translations[ko][description]" rows="5" required
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">포함 사항</label>
                            <textarea name="translations[ko][includes]" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                      placeholder="줄바꿈으로 구분"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">불포함 사항</label>
                            <textarea name="translations[ko][excludes]" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                      placeholder="줄바꿈으로 구분"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Prices -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">가격 설정</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">성인 가격 (원) *</label>
                            <input type="number" name="prices[adult]" min="0" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">아동 가격 (원)</label>
                            <input type="number" name="prices[child]" min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">이미지</h3>

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
                    <button type="submit" name="status" value="draft"
                            class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        초안 저장
                    </button>
                    <button type="submit" name="status" value="pending_review"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        검토 요청
                    </button>
                </div>
            </form>
        </noscript>
    </div>
</x-layouts.vendor>
