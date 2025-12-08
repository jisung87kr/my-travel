<x-layouts.admin>
    <x-slot name="header">상품 등록</x-slot>

    <div class="max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900">
                &larr; 상품 목록으로
            </a>
        </div>

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

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
                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
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
                                <option value="{{ $type['value'] }}" {{ old('type') === $type['value'] ? 'selected' : '' }}>
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
                                <option value="{{ $region['value'] }}" {{ old('region') === $region['value'] ? 'selected' : '' }}>
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
                        <input type="number" id="duration" name="duration" value="{{ old('duration') }}" min="0"
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
                                <option value="{{ $bookingType['value'] }}" {{ old('booking_type', 'instant') === $bookingType['value'] ? 'selected' : '' }}>
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
                        <input type="number" id="min_persons" name="min_persons" value="{{ old('min_persons', 1) }}" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('min_persons') border-red-500 @enderror">
                        @error('min_persons')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_persons" class="block text-sm font-medium text-gray-700 mb-1">
                            최대 인원
                        </label>
                        <input type="number" id="max_persons" name="max_persons" value="{{ old('max_persons') }}" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('max_persons') border-red-500 @enderror">
                        @error('max_persons')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meeting_point" class="block text-sm font-medium text-gray-700 mb-1">
                            만남 장소
                        </label>
                        <input type="text" id="meeting_point" name="meeting_point" value="{{ old('meeting_point') }}"
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
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>임시저장</option>
                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>승인 대기</option>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>활성</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>비활성</option>
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
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('meeting_point_detail') border-red-500 @enderror">{{ old('meeting_point_detail') }}</textarea>
                        @error('meeting_point_detail')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 상품 정보 (한국어) -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">상품 정보 (한국어)</h3>

                <div class="space-y-4">
                    <div>
                        <label for="translations_ko_title" class="block text-sm font-medium text-gray-700 mb-1">
                            상품명 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="translations_ko_title" name="translations[ko][title]"
                               value="{{ old('translations.ko.title') }}"
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
                               value="{{ old('translations.ko.short_description') }}"
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
                                  required>{{ old('translations.ko.description') }}</textarea>
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
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('translations.ko.includes') border-red-500 @enderror">{{ old('translations.ko.includes') }}</textarea>
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
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('translations.ko.excludes') border-red-500 @enderror">{{ old('translations.ko.excludes') }}</textarea>
                        @error('translations.ko.excludes')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 가격 설정 -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">가격 설정</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="prices_adult" class="block text-sm font-medium text-gray-700 mb-1">
                            성인 가격 (원) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="prices_adult" name="prices[adult]" value="{{ old('prices.adult') }}" min="0"
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
                        <input type="number" id="prices_child" name="prices[child]" value="{{ old('prices.child') }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('prices.child') border-red-500 @enderror">
                        @error('prices.child')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 이미지 -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">이미지</h3>

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
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    취소
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    등록
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
