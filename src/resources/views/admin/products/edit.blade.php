<x-layouts.admin>
    <x-slot name="header">상품 수정 - {{ $product->getTranslation('ko')?->title ?? '상품' }}</x-slot>

    <div class="max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.products.show', $product) }}" class="text-gray-600 hover:text-gray-900">
                &larr; 상품 상세로
            </a>
        </div>

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- 기본 정보 -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">기본 정보</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-1">
                            제공자 <span class="text-red-500">*</span>
                        </label>
                        <select id="vendor_id" name="vendor_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('vendor_id') border-red-500 @enderror">
                            <option value="">제공자 선택</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id', $product->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->company_name }} ({{ $vendor->user->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                            상품 유형 <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('type') border-red-500 @enderror">
                            @foreach($types as $type)
                                <option value="{{ $type['value'] }}" {{ old('type', $product->type->value ?? $product->type) === $type['value'] ? 'selected' : '' }}>
                                    {{ $type['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="region" class="block text-sm font-medium text-gray-700 mb-1">
                            지역 <span class="text-red-500">*</span>
                        </label>
                        <select id="region" name="region" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('region') border-red-500 @enderror">
                            @foreach($regions as $region)
                                <option value="{{ $region['value'] }}" {{ old('region', $product->region->value ?? $product->region) === $region['value'] ? 'selected' : '' }}>
                                    {{ $region['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('region')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">
                            소요시간 (분)
                        </label>
                        <input type="number" id="duration" name="duration" value="{{ old('duration', $product->duration) }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('duration') border-red-500 @enderror">
                        @error('duration')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="booking_type" class="block text-sm font-medium text-gray-700 mb-1">
                            예약 유형 <span class="text-red-500">*</span>
                        </label>
                        <select id="booking_type" name="booking_type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('booking_type') border-red-500 @enderror">
                            @foreach($bookingTypes as $bookingType)
                                <option value="{{ $bookingType['value'] }}" {{ old('booking_type', $product->booking_type->value ?? $product->booking_type) === $bookingType['value'] ? 'selected' : '' }}>
                                    {{ $bookingType['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('booking_type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="min_persons" class="block text-sm font-medium text-gray-700 mb-1">
                            최소 인원
                        </label>
                        <input type="number" id="min_persons" name="min_persons" value="{{ old('min_persons', $product->min_persons ?? 1) }}" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('min_persons') border-red-500 @enderror">
                        @error('min_persons')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_persons" class="block text-sm font-medium text-gray-700 mb-1">
                            최대 인원
                        </label>
                        <input type="number" id="max_persons" name="max_persons" value="{{ old('max_persons', $product->max_persons) }}" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('max_persons') border-red-500 @enderror">
                        @error('max_persons')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meeting_point" class="block text-sm font-medium text-gray-700 mb-1">
                            만남 장소
                        </label>
                        <input type="text" id="meeting_point" name="meeting_point" value="{{ old('meeting_point', $product->meeting_point) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('meeting_point') border-red-500 @enderror">
                        @error('meeting_point')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            상태 <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror">
                            <option value="draft" {{ old('status', $product->status->value ?? $product->status) === 'draft' ? 'selected' : '' }}>임시저장</option>
                            <option value="pending" {{ old('status', $product->status->value ?? $product->status) === 'pending' ? 'selected' : '' }}>승인 대기</option>
                            <option value="active" {{ old('status', $product->status->value ?? $product->status) === 'active' ? 'selected' : '' }}>활성</option>
                            <option value="inactive" {{ old('status', $product->status->value ?? $product->status) === 'inactive' ? 'selected' : '' }}>비활성</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="meeting_point_detail" class="block text-sm font-medium text-gray-700 mb-1">
                            만남 장소 상세
                        </label>
                        <textarea id="meeting_point_detail" name="meeting_point_detail" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('meeting_point_detail') border-red-500 @enderror">{{ old('meeting_point_detail', $product->meeting_point_detail) }}</textarea>
                        @error('meeting_point_detail')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 상품 정보 (한국어) -->
            @php
                $koTranslation = $product->translations->firstWhere('locale', 'ko');
            @endphp
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">상품 정보 (한국어)</h3>

                <div class="space-y-4">
                    <div>
                        <label for="translations_ko_title" class="block text-sm font-medium text-gray-700 mb-1">
                            상품명 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="translations_ko_title" name="translations[ko][title]"
                               value="{{ old('translations.ko.title', $koTranslation?->title) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('translations.ko.title') border-red-500 @enderror"
                               required>
                        @error('translations.ko.title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="translations_ko_short_description" class="block text-sm font-medium text-gray-700 mb-1">
                            간단 설명
                        </label>
                        <input type="text" id="translations_ko_short_description" name="translations[ko][short_description]"
                               value="{{ old('translations.ko.short_description', $koTranslation?->short_description) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('translations.ko.short_description') border-red-500 @enderror">
                        @error('translations.ko.short_description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="translations_ko_description" class="block text-sm font-medium text-gray-700 mb-1">
                            상세 설명 <span class="text-red-500">*</span>
                        </label>
                        <textarea id="translations_ko_description" name="translations[ko][description]" rows="5"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('translations.ko.description') border-red-500 @enderror"
                                  required>{{ old('translations.ko.description', $koTranslation?->description) }}</textarea>
                        @error('translations.ko.description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="translations_ko_includes" class="block text-sm font-medium text-gray-700 mb-1">
                            포함 사항
                        </label>
                        <textarea id="translations_ko_includes" name="translations[ko][includes]" rows="3"
                                  placeholder="줄바꿈으로 구분"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('translations.ko.includes') border-red-500 @enderror">{{ old('translations.ko.includes', $koTranslation?->includes) }}</textarea>
                        @error('translations.ko.includes')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="translations_ko_excludes" class="block text-sm font-medium text-gray-700 mb-1">
                            불포함 사항
                        </label>
                        <textarea id="translations_ko_excludes" name="translations[ko][excludes]" rows="3"
                                  placeholder="줄바꿈으로 구분"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('translations.ko.excludes') border-red-500 @enderror">{{ old('translations.ko.excludes', $koTranslation?->excludes) }}</textarea>
                        @error('translations.ko.excludes')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 가격 설정 -->
            @php
                $adultPrice = $product->prices->firstWhere('type', 'adult');
                $childPrice = $product->prices->firstWhere('type', 'child');
            @endphp
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">가격 설정</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="prices_adult" class="block text-sm font-medium text-gray-700 mb-1">
                            성인 가격 (원) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="prices_adult" name="prices[adult]" value="{{ old('prices.adult', $adultPrice?->amount) }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('prices.adult') border-red-500 @enderror"
                               required>
                        @error('prices.adult')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prices_child" class="block text-sm font-medium text-gray-700 mb-1">
                            아동 가격 (원)
                        </label>
                        <input type="number" id="prices_child" name="prices[child]" value="{{ old('prices.child', $childPrice?->amount) }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('prices.child') border-red-500 @enderror">
                        @error('prices.child')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 기존 이미지 -->
            @if($product->images->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">기존 이미지</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                        <div class="relative group">
                            <img src="{{ $image->url }}" alt="" class="w-full h-32 object-cover rounded-lg">
                            <label class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer rounded-lg">
                                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="sr-only peer">
                                <span class="text-white text-sm peer-checked:text-red-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </span>
                            </label>
                            @if($image->is_primary)
                                <span class="absolute top-2 left-2 px-2 py-1 bg-indigo-600 text-white text-xs rounded">대표</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <p class="mt-2 text-sm text-gray-500">이미지를 클릭하면 삭제 표시됩니다</p>
            </div>
            @endif

            <!-- 새 이미지 -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">새 이미지 추가</h3>

                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-1">
                        상품 이미지
                    </label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('images') border-red-500 @enderror @error('images.*') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">최대 10장, JPG/PNG 형식, 각 파일 5MB 이하</p>
                    @error('images')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 버튼 -->
            <div class="flex items-center justify-between">
                <div>
                    @php
                        $hasActiveBookings = $product->bookings()->whereNotIn('status', ['cancelled', 'completed', 'no_show'])->count() > 0;
                    @endphp
                    @if(!$hasActiveBookings)
                        <button type="button" onclick="confirmDelete()"
                                class="px-4 py-2 text-red-600 hover:text-red-800">
                            상품 삭제
                        </button>
                    @else
                        <span class="text-sm text-gray-500">진행 중인 예약이 있어 삭제할 수 없습니다</span>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.products.show', $product) }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        취소
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        저장
                    </button>
                </div>
            </div>
        </form>
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
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
</x-layouts.admin>
