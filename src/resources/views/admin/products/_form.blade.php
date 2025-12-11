@props(['product' => null, 'vendors', 'types', 'regions', 'bookingTypes'])

@php
    $isEdit = isset($product) && $product->exists;

    // 번역 데이터
    $koTranslation = $isEdit
        ? ($product->translations->firstWhere('locale', 'ko') ?? $product->translations->first(fn($t) => ($t->locale->value ?? $t->locale) === 'ko'))
        : null;

    // 가격 데이터
    $adultPrice = $isEdit
        ? ($product->prices->firstWhere('type', 'adult') ?? $product->prices->first(fn($p) => ($p->type->value ?? $p->type) === 'adult'))
        : null;
    $childPrice = $isEdit
        ? ($product->prices->firstWhere('type', 'child') ?? $product->prices->first(fn($p) => ($p->type->value ?? $p->type) === 'child'))
        : null;
@endphp

<!-- 기본 정보 -->
<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-slate-900">기본 정보</h3>
                <p class="text-sm text-slate-500">상품의 기본 정보를 {{ $isEdit ? '수정' : '입력' }}해주세요</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="vendor_id" class="block text-sm font-medium text-slate-700 mb-2">
                    제공자 <span class="text-red-500">*</span>
                </label>
                <select id="vendor_id" name="vendor_id" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('vendor_id') border-red-500 bg-red-50 @enderror">
                    <option value="">제공자 선택</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ old('vendor_id', $product?->vendor_id) == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->company_name }} ({{ $vendor->user->name }})
                        </option>
                    @endforeach
                </select>
                <x-form-error field="vendor_id" />
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-slate-700 mb-2">
                    상품 유형 <span class="text-red-500">*</span>
                </label>
                <select id="type" name="type" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('type') border-red-500 bg-red-50 @enderror">
                    @foreach($types as $type)
                        <option value="{{ $type['value'] }}" {{ old('type', $product?->type->value ?? $product?->type) === $type['value'] ? 'selected' : '' }}>
                            {{ $type['label'] }}
                        </option>
                    @endforeach
                </select>
                <x-form-error field="type" />
            </div>

            <div>
                <label for="region" class="block text-sm font-medium text-slate-700 mb-2">
                    지역 <span class="text-red-500">*</span>
                </label>
                <select id="region" name="region" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('region') border-red-500 bg-red-50 @enderror">
                    @foreach($regions as $region)
                        <option value="{{ $region['value'] }}" {{ old('region', $product?->region->value ?? $product?->region) === $region['value'] ? 'selected' : '' }}>
                            {{ $region['label'] }}
                        </option>
                    @endforeach
                </select>
                <x-form-error field="region" />
            </div>

            <div>
                <label for="duration" class="block text-sm font-medium text-slate-700 mb-2">
                    소요시간 (분)
                </label>
                <input type="number" id="duration" name="duration" value="{{ old('duration', $product?->duration) }}" min="0"
                       placeholder="예: 120"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('duration') border-red-500 bg-red-50 @enderror">
                <x-form-error field="duration" />
            </div>

            <div>
                <label for="booking_type" class="block text-sm font-medium text-slate-700 mb-2">
                    예약 유형 <span class="text-red-500">*</span>
                </label>
                <select id="booking_type" name="booking_type" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('booking_type') border-red-500 bg-red-50 @enderror">
                    @foreach($bookingTypes as $bookingType)
                        <option value="{{ $bookingType['value'] }}" {{ old('booking_type', $product?->booking_type->value ?? $product?->booking_type ?? 'instant') === $bookingType['value'] ? 'selected' : '' }}>
                            {{ $bookingType['label'] }}
                        </option>
                    @endforeach
                </select>
                <x-form-error field="booking_type" />
            </div>

            <div>
                <label for="min_persons" class="block text-sm font-medium text-slate-700 mb-2">
                    최소 인원
                </label>
                <input type="number" id="min_persons" name="min_persons" value="{{ old('min_persons', $product?->min_persons ?? 1) }}" min="1"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('min_persons') border-red-500 bg-red-50 @enderror">
                <x-form-error field="min_persons" />
            </div>

            <div>
                <label for="max_persons" class="block text-sm font-medium text-slate-700 mb-2">
                    최대 인원
                </label>
                <input type="number" id="max_persons" name="max_persons" value="{{ old('max_persons', $product?->max_persons) }}" min="1"
                       placeholder="제한 없음"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('max_persons') border-red-500 bg-red-50 @enderror">
                <x-form-error field="max_persons" />
            </div>

            <div>
                <label for="meeting_point" class="block text-sm font-medium text-slate-700 mb-2">
                    만남 장소
                </label>
                <input type="text" id="meeting_point" name="meeting_point" value="{{ old('meeting_point', $product?->meeting_point) }}"
                       placeholder="예: 서울역 1번 출구"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('meeting_point') border-red-500 bg-red-50 @enderror">
                <x-form-error field="meeting_point" />
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-2">
                    상태 <span class="text-red-500">*</span>
                </label>
                <select id="status" name="status" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('status') border-red-500 bg-red-50 @enderror">
                    <option value="draft" {{ old('status', $product?->status->value ?? $product?->status) === 'draft' ? 'selected' : '' }}>임시저장</option>
                    <option value="pending" {{ old('status', $product?->status->value ?? $product?->status) === 'pending' ? 'selected' : '' }}>승인 대기</option>
                    <option value="active" {{ old('status', $product?->status->value ?? $product?->status ?? 'active') === 'active' ? 'selected' : '' }}>활성</option>
                    <option value="inactive" {{ old('status', $product?->status->value ?? $product?->status) === 'inactive' ? 'selected' : '' }}>비활성</option>
                </select>
                <x-form-error field="status" />
            </div>

            <div class="md:col-span-2">
                <label for="meeting_point_detail" class="block text-sm font-medium text-slate-700 mb-2">
                    만남 장소 상세
                </label>
                <textarea id="meeting_point_detail" name="meeting_point_detail" rows="2"
                          placeholder="상세한 만남 장소 안내를 입력해주세요"
                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all resize-none @error('meeting_point_detail') border-red-500 bg-red-50 @enderror">{{ old('meeting_point_detail', $product?->meeting_point_detail) }}</textarea>
                <x-form-error field="meeting_point_detail" />
            </div>
        </div>
    </div>
</div>

<!-- 상품 정보 (한국어) -->
<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-teal-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-slate-900">상품 정보 (한국어)</h3>
                <p class="text-sm text-slate-500">한국어로 된 상품 정보를 {{ $isEdit ? '수정' : '입력' }}해주세요</p>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-5">
        <div>
            <label for="translations_ko_title" class="block text-sm font-medium text-slate-700 mb-2">
                상품명 <span class="text-red-500">*</span>
            </label>
            <input type="text" id="translations_ko_title" name="translations[ko][title]"
                   value="{{ old('translations.ko.title', $koTranslation?->name ?? $koTranslation?->title) }}"
                   placeholder="상품명을 입력해주세요"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('translations.ko.title') border-red-500 bg-red-50 @enderror"
                   required>
            <x-form-error field="translations.ko.title" />
        </div>

        <div>
            <label for="translations_ko_short_description" class="block text-sm font-medium text-slate-700 mb-2">
                간단 설명
            </label>
            <input type="text" id="translations_ko_short_description" name="translations[ko][short_description]"
                   value="{{ old('translations.ko.short_description', $koTranslation?->short_description) }}"
                   placeholder="한 줄로 상품을 소개해주세요"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('translations.ko.short_description') border-red-500 bg-red-50 @enderror">
            <x-form-error field="translations.ko.short_description" />
        </div>

        <div>
            <label for="translations_ko_description" class="block text-sm font-medium text-slate-700 mb-2">
                상세 설명 <span class="text-red-500">*</span>
            </label>
            <textarea id="translations_ko_description" name="translations[ko][description]" rows="5"
                      placeholder="상품에 대한 상세한 설명을 입력해주세요"
                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all resize-none @error('translations.ko.description') border-red-500 bg-red-50 @enderror"
                      required>{{ old('translations.ko.description', $koTranslation?->description) }}</textarea>
            <x-form-error field="translations.ko.description" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="translations_ko_includes" class="block text-sm font-medium text-slate-700 mb-2">
                    포함 사항
                </label>
                <textarea id="translations_ko_includes" name="translations[ko][includes]" rows="4"
                          placeholder="줄바꿈으로 구분하여 입력해주세요&#10;예: 호텔 픽업&#10;점심 식사&#10;영어 가이드"
                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all resize-none @error('translations.ko.includes') border-red-500 bg-red-50 @enderror">{{ old('translations.ko.includes', $koTranslation?->includes) }}</textarea>
                <x-form-error field="translations.ko.includes" />
            </div>

            <div>
                <label for="translations_ko_excludes" class="block text-sm font-medium text-slate-700 mb-2">
                    불포함 사항
                </label>
                <textarea id="translations_ko_excludes" name="translations[ko][excludes]" rows="4"
                          placeholder="줄바꿈으로 구분하여 입력해주세요&#10;예: 항공권&#10;여행자 보험&#10;개인 경비"
                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all resize-none @error('translations.ko.excludes') border-red-500 bg-red-50 @enderror">{{ old('translations.ko.excludes', $koTranslation?->excludes) }}</textarea>
                <x-form-error field="translations.ko.excludes" />
            </div>
        </div>
    </div>
</div>

<!-- 가격 설정 -->
<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-orange-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-slate-900">가격 설정</h3>
                <p class="text-sm text-slate-500">상품의 가격 정보를 {{ $isEdit ? '수정' : '입력' }}해주세요</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="prices_adult" class="block text-sm font-medium text-slate-700 mb-2">
                    성인 가격 (원) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">₩</span>
                    <input type="number" id="prices_adult" name="prices[adult]" value="{{ old('prices.adult', $adultPrice?->amount) }}" min="0"
                           placeholder="0"
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('prices.adult') border-red-500 bg-red-50 @enderror"
                           required>
                </div>
                <x-form-error field="prices.adult" />
            </div>

            <div>
                <label for="prices_child" class="block text-sm font-medium text-slate-700 mb-2">
                    아동 가격 (원)
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">₩</span>
                    <input type="number" id="prices_child" name="prices[child]" value="{{ old('prices.child', $childPrice?->amount) }}" min="0"
                           placeholder="0"
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all @error('prices.child') border-red-500 bg-red-50 @enderror">
                </div>
                <x-form-error field="prices.child" />
            </div>
        </div>
    </div>
</div>

<!-- 기존 이미지 (수정 시에만 표시) -->
@if($isEdit && $product->images->count() > 0)
<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-rose-50 to-pink-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center shadow-lg shadow-rose-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-slate-900">기존 이미지</h3>
                <p class="text-sm text-slate-500">삭제할 이미지를 선택해주세요</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($product->images as $image)
                <div class="relative group">
                    <img src="{{ $image->url }}" alt="" class="w-full h-32 object-cover rounded-xl shadow-sm">
                    <label class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center cursor-pointer rounded-xl">
                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="sr-only peer">
                        <span class="text-white peer-checked:text-red-400 transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </span>
                    </label>
                    @if($image->is_primary)
                        <span class="absolute top-2 left-2 px-2 py-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-medium rounded-lg shadow-md">대표</span>
                    @endif
                </div>
            @endforeach
        </div>
        <p class="mt-4 text-sm text-slate-500 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            이미지를 클릭하면 삭제 표시됩니다
        </p>
    </div>
</div>
@endif

<!-- 이미지 업로드 -->
<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-violet-50 to-purple-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($isEdit)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    @endif
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-slate-900">{{ $isEdit ? '새 이미지 추가' : '이미지' }}</h3>
                <p class="text-sm text-slate-500">{{ $isEdit ? '추가할 이미지를 업로드해주세요' : '상품 이미지를 업로드해주세요' }}</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-blue-400 hover:bg-blue-50/30 transition-all">
            <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
            <label for="images" class="cursor-pointer">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <p class="text-slate-600 font-medium mb-1">클릭하여 이미지 업로드</p>
                <p class="text-sm text-slate-400">또는 파일을 드래그하세요</p>
            </label>
        </div>
        <div class="mt-3 flex items-center gap-4 text-sm text-slate-500">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                최대 10장
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                JPG/PNG 형식
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                각 파일 5MB 이하
            </span>
        </div>
        <x-form-error field="images" />
        <x-form-error field="images.*" />
    </div>
</div>