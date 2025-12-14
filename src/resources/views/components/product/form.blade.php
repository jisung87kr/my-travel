@props([
    'action',
    'method' => 'POST',
    'product' => null,
    'vendors' => null,
    'regions',
    'types',
    'bookingTypes' => [
        ['value' => 'instant', 'label' => '자동 확정 (Instant Booking)'],
        ['value' => 'request', 'label' => '승인 필요 (Request Booking)'],
    ],
    'cancelRoute',
    'submitLabel' => '상품 등록',
    'showVendorSelect' => false,
    'colorScheme' => 'violet',
])

@php
    $isEdit = isset($product) && $product?->exists;

    $colors = [
        'violet' => [
            'gradient' => 'from-violet-600 to-violet-700',
            'hover' => 'hover:from-violet-700 hover:to-violet-800',
            'shadow' => 'shadow-violet-500/30',
            'focus' => 'focus:ring-violet-500/20 focus:border-violet-500',
        ],
        'blue' => [
            'gradient' => 'from-blue-600 to-indigo-600',
            'hover' => 'hover:from-blue-700 hover:to-indigo-700',
            'shadow' => 'shadow-blue-500/30',
            'focus' => 'focus:ring-blue-500 focus:border-blue-500',
        ],
    ];
    $scheme = $colors[$colorScheme] ?? $colors['violet'];

    // 번역 데이터
    $koTranslation = $product?->translations?->firstWhere('locale', 'ko')
        ?? $product?->translations?->first(fn($t) => ($t->locale->value ?? $t->locale) === 'ko');
    $enTranslation = $product?->translations?->firstWhere('locale', 'en')
        ?? $product?->translations?->first(fn($t) => ($t->locale->value ?? $t->locale) === 'en');

    // 가격 데이터
    $adultPrice = $product?->prices?->firstWhere('type', 'adult')
        ?? $product?->prices?->first(fn($p) => ($p->type->value ?? $p->type) === 'adult');
    $childPrice = $product?->prices?->firstWhere('type', 'child')
        ?? $product?->prices?->first(fn($p) => ($p->type->value ?? $p->type) === 'child');
@endphp

<script src="https://oapi.map.naver.com/openapi/v3/maps.js?ncpKeyId=az8xhnr3ar&submodules=geocoder"></script>
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

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
                @if($showVendorSelect && $vendors)
                    <div class="md:col-span-2">
                        <label for="vendor_id" class="block text-sm font-medium text-slate-700 mb-2">
                            제공자 <span class="text-red-500">*</span>
                        </label>
                        <select id="vendor_id" name="vendor_id" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error('vendor_id') border-red-500 bg-red-50 @enderror">
                            <option value="">제공자 선택</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id', $product?->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->company_name }} ({{ $vendor->user->name }})
                                </option>
                            @endforeach
                        </select>
                        <x-form-error field="vendor_id" />
                    </div>
                @endif

                <div>
                    <label for="type" class="block text-sm font-medium text-slate-700 mb-2">
                        상품 유형 <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error('type') border-red-500 bg-red-50 @enderror">
                        @foreach($types as $type)
                            <option value="{{ $type['value'] }}" {{ old('type', $product?->type?->value ?? $product?->type) === $type['value'] ? 'selected' : '' }}>
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
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error('region') border-red-500 bg-red-50 @enderror">
                        @foreach($regions as $region)
                            <option value="{{ $region['value'] }}" {{ old('region', $product?->region?->value ?? $product?->region) === $region['value'] ? 'selected' : '' }}>
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
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error('duration') border-red-500 bg-red-50 @enderror">
                    <x-form-error field="duration" />
                </div>

                <div>
                    <label for="booking_type" class="block text-sm font-medium text-slate-700 mb-2">
                        예약 유형 <span class="text-red-500">*</span>
                    </label>
                    <select id="booking_type" name="booking_type" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error('booking_type') border-red-500 bg-red-50 @enderror">
                        @foreach($bookingTypes as $bookingType)
                            <option value="{{ $bookingType['value'] }}" {{ old('booking_type', $product?->booking_type?->value ?? $product?->booking_type ?? 'instant') === $bookingType['value'] ? 'selected' : '' }}>
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
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error('min_persons') border-red-500 bg-red-50 @enderror">
                    <x-form-error field="min_persons" />
                </div>

                <div>
                    <label for="max_persons" class="block text-sm font-medium text-slate-700 mb-2">
                        최대 인원
                    </label>
                    <input type="number" id="max_persons" name="max_persons" value="{{ old('max_persons', $product?->max_persons) }}" min="1"
                           placeholder="제한 없음"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error('max_persons') border-red-500 bg-red-50 @enderror">
                    <x-form-error field="max_persons" />
                </div>

                @if($showVendorSelect)
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-2">
                            상태 <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error('status') border-red-500 bg-red-50 @enderror">
                            <option value="draft" {{ old('status', $product?->status?->value ?? $product?->status) === 'draft' ? 'selected' : '' }}>임시저장</option>
                            <option value="pending" {{ old('status', $product?->status?->value ?? $product?->status) === 'pending' ? 'selected' : '' }}>승인 대기</option>
                            <option value="active" {{ old('status', $product?->status?->value ?? $product?->status ?? 'active') === 'active' ? 'selected' : '' }}>활성</option>
                            <option value="inactive" {{ old('status', $product?->status?->value ?? $product?->status) === 'inactive' ? 'selected' : '' }}>비활성</option>
                        </select>
                        <x-form-error field="status" />
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 상품 정보 (다국어) -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-teal-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">상품 정보</h3>
                    <p class="text-sm text-slate-500">다국어로 상품 정보를 {{ $isEdit ? '수정' : '입력' }}해주세요</p>
                </div>
            </div>
        </div>

        <!-- 언어 탭 -->
        <div class="border-b border-slate-200">
            <nav class="flex -mb-px" aria-label="Tabs">
                <button type="button" data-tab="ko"
                        class="lang-tab active flex-1 py-3 px-4 text-center border-b-2 border-emerald-500 text-emerald-600 font-medium text-sm transition-all">
                    <span class="flex items-center justify-center gap-2">
                        <span class="text-lg">🇰🇷</span>
                        한국어
                        <span class="text-red-500 text-xs">*필수</span>
                    </span>
                </button>
                <button type="button" data-tab="en"
                        class="lang-tab flex-1 py-3 px-4 text-center border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 font-medium text-sm transition-all">
                    <span class="flex items-center justify-center gap-2">
                        <span class="text-lg">🇺🇸</span>
                        English
                        <span class="text-slate-400 text-xs">선택</span>
                    </span>
                </button>
            </nav>
        </div>

        @php
            $translations = [
                'ko' => [
                    'data' => $koTranslation,
                    'required' => true,
                    'hidden' => false,
                    'labels' => [
                        'title' => '상품명',
                        'short_description' => '간단 설명',
                        'description' => '상세 설명',
                        'includes' => '포함 사항',
                        'excludes' => '불포함 사항',
                        'notes' => '추가 안내사항',
                        'meeting_point' => '만남 장소',
                        'meeting_point_detail' => '만남 장소 상세',
                    ],
                    'placeholders' => [
                        'title' => '상품명을 입력해주세요',
                        'short_description' => '한 줄로 상품을 소개해주세요',
                        'description' => '상품에 대한 상세한 설명을 입력해주세요',
                        'includes' => "줄바꿈으로 구분하여 입력해주세요\n예: 호텔 픽업\n점심 식사\n영어 가이드",
                        'excludes' => "줄바꿈으로 구분하여 입력해주세요\n예: 항공권\n여행자 보험\n개인 경비",
                        'notes' => '고객에게 전달할 추가 안내사항을 입력해주세요',
                        'meeting_point' => '예: 서울역 1번 출구',
                        'meeting_point_detail' => '상세한 만남 장소 안내',
                    ],
                ],
                'en' => [
                    'data' => $enTranslation,
                    'required' => false,
                    'hidden' => true,
                    'labels' => [
                        'title' => 'Product Title',
                        'short_description' => 'Short Description',
                        'description' => 'Full Description',
                        'includes' => "What's Included",
                        'excludes' => "What's Not Included",
                        'notes' => 'Additional Notes',
                        'meeting_point' => 'Meeting Point',
                        'meeting_point_detail' => 'Meeting Point Details',
                    ],
                    'placeholders' => [
                        'title' => 'Enter product title',
                        'short_description' => 'Brief introduction of the product',
                        'description' => 'Enter detailed description of the product',
                        'includes' => "Separate items with line breaks\ne.g. Hotel pickup\nLunch\nEnglish guide",
                        'excludes' => "Separate items with line breaks\ne.g. Airfare\nTravel insurance\nPersonal expenses",
                        'notes' => 'Additional information for customers',
                        'meeting_point' => 'e.g. Seoul Station Exit 1',
                        'meeting_point_detail' => 'Detailed meeting point instructions',
                    ],
                ],
            ];
        @endphp

        @foreach($translations as $locale => $config)
            <div id="tab-{{ $locale }}" class="lang-tab-content {{ $config['hidden'] ? 'hidden' : '' }} p-6 space-y-5">
                @if($locale === 'en')
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                        <p class="text-sm text-blue-700 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            영어 번역은 선택사항입니다. 외국인 고객을 위해 입력하시면 좋습니다.
                        </p>
                    </div>
                @endif

                {{-- 상품명/Title --}}
                <div>
                    <label for="translations_{{ $locale }}_title" class="block text-sm font-medium text-slate-700 mb-2">
                        {{ $config['labels']['title'] }} @if($config['required'])<span class="text-red-500">*</span>@endif
                    </label>
                    <input type="text" id="translations_{{ $locale }}_title" name="translations[{{ $locale }}][title]"
                           value="{{ old("translations.{$locale}.title", $config['data']?->title) }}"
                           placeholder="{{ $config['placeholders']['title'] }}"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error("translations.{$locale}.title") border-red-500 bg-red-50 @enderror"
                           {{ $config['required'] ? 'required' : '' }}>
                    <x-form-error field="translations.{{ $locale }}.title" />
                </div>

                {{-- 간단 설명/Short Description --}}
                <div>
                    <label for="translations_{{ $locale }}_short_description" class="block text-sm font-medium text-slate-700 mb-2">
                        {{ $config['labels']['short_description'] }}
                    </label>
                    <input type="text" id="translations_{{ $locale }}_short_description" name="translations[{{ $locale }}][short_description]"
                           value="{{ old("translations.{$locale}.short_description", $config['data']?->short_description) }}"
                           placeholder="{{ $config['placeholders']['short_description'] }}"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error("translations.{$locale}.short_description") border-red-500 bg-red-50 @enderror">
                    <x-form-error field="translations.{{ $locale }}.short_description" />
                </div>

                {{-- 상세 설명/Full Description --}}
                <div>
                    <label for="translations_{{ $locale }}_description" class="block text-sm font-medium text-slate-700 mb-2">
                        {{ $config['labels']['description'] }} @if($config['required'])<span class="text-red-500">*</span>@endif
                    </label>
                    <textarea id="translations_{{ $locale }}_description" name="translations[{{ $locale }}][description]" rows="5"
                              placeholder="{{ $config['placeholders']['description'] }}"
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all resize-none @error("translations.{$locale}.description") border-red-500 bg-red-50 @enderror"
                              {{ $config['required'] ? 'required' : '' }}>{{ old("translations.{$locale}.description", $config['data']?->description) }}</textarea>
                    <x-form-error field="translations.{{ $locale }}.description" />
                </div>

                {{-- 포함/불포함 사항 --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="translations_{{ $locale }}_includes" class="block text-sm font-medium text-slate-700 mb-2">
                            {{ $config['labels']['includes'] }}
                        </label>
                        <textarea id="translations_{{ $locale }}_includes" name="translations[{{ $locale }}][includes]" rows="4"
                                  placeholder="{{ $config['placeholders']['includes'] }}"
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all resize-none @error("translations.{$locale}.includes") border-red-500 bg-red-50 @enderror">{{ old("translations.{$locale}.includes", $config['data']?->includes) }}</textarea>
                        <x-form-error field="translations.{{ $locale }}.includes" />
                    </div>

                    <div>
                        <label for="translations_{{ $locale }}_excludes" class="block text-sm font-medium text-slate-700 mb-2">
                            {{ $config['labels']['excludes'] }}
                        </label>
                        <textarea id="translations_{{ $locale }}_excludes" name="translations[{{ $locale }}][excludes]" rows="4"
                                  placeholder="{{ $config['placeholders']['excludes'] }}"
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all resize-none @error("translations.{$locale}.excludes") border-red-500 bg-red-50 @enderror">{{ old("translations.{$locale}.excludes", $config['data']?->excludes) }}</textarea>
                        <x-form-error field="translations.{{ $locale }}.excludes" />
                    </div>
                </div>

                {{-- 추가 안내사항/Additional Notes --}}
                <div>
                    <label for="translations_{{ $locale }}_notes" class="block text-sm font-medium text-slate-700 mb-2">
                        {{ $config['labels']['notes'] }}
                    </label>
                    <textarea id="translations_{{ $locale }}_notes" name="translations[{{ $locale }}][notes]" rows="3"
                              placeholder="{{ $config['placeholders']['notes'] }}"
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all resize-none @error("translations.{$locale}.notes") border-red-500 bg-red-50 @enderror">{{ old("translations.{$locale}.notes", $config['data']?->notes) }}</textarea>
                    <x-form-error field="translations.{{ $locale }}.notes" />
                </div>

                {{-- 만남 장소/Meeting Point --}}
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="translations_{{ $locale }}_meeting_point" class="block text-sm font-medium text-slate-700 mb-2">
                                {{ $config['labels']['meeting_point'] }}
                            </label>
                            <div class="flex gap-2">
                                <input type="text" id="translations_{{ $locale }}_meeting_point" name="translations[{{ $locale }}][meeting_point]"
                                       value="{{ old("translations.{$locale}.meeting_point", $config['data']?->meeting_point) }}"
                                       placeholder="{{ $config['placeholders']['meeting_point'] }}"
                                       class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error("translations.{$locale}.meeting_point") border-red-500 bg-red-50 @enderror">
                                @if($locale === 'ko')
                                <button type="button" id="search-address-btn"
                                        class="px-4 py-3 bg-gradient-to-r from-cyan-500 to-sky-600 text-white rounded-xl hover:from-cyan-600 hover:to-sky-700 font-medium transition-all flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    검색
                                </button>
                                @endif
                            </div>
                            <x-form-error field="translations.{{ $locale }}.meeting_point" />
                        </div>

                        <div>
                            <label for="translations_{{ $locale }}_meeting_point_detail" class="block text-sm font-medium text-slate-700 mb-2">
                                {{ $config['labels']['meeting_point_detail'] }}
                            </label>
                            <textarea id="translations_{{ $locale }}_meeting_point_detail" name="translations[{{ $locale }}][meeting_point_detail]" rows="1"
                                      placeholder="{{ $config['placeholders']['meeting_point_detail'] }}"
                                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all resize-none @error("translations.{$locale}.meeting_point_detail") border-red-500 bg-red-50 @enderror">{{ old("translations.{$locale}.meeting_point_detail", $config['data']?->meeting_point_detail) }}</textarea>
                            <x-form-error field="translations.{{ $locale }}.meeting_point_detail" />
                        </div>
                    </div>

                    @if($locale === 'ko')
                    {{-- 지도 (한국어 탭에서만 표시) --}}
                    <div class="space-y-3">
                        <!-- 검색 결과 -->
                        <div id="search-results" class="hidden max-h-48 overflow-y-auto border border-slate-200 rounded-xl bg-white divide-y divide-slate-100"></div>

                        <!-- 지도 -->
                        <div id="meeting-point-map" class="w-full h-64 rounded-xl border border-slate-200 overflow-hidden"></div>

                        <!-- 선택된 위치 정보 -->
                        <div id="selected-location-info" class="p-3 bg-slate-50 rounded-xl {{ $product?->latitude ? '' : 'hidden' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-cyan-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p id="selected-address" class="text-sm text-slate-700 truncate">{{ $product?->latitude ? '위치가 저장되어 있습니다' : '' }}</p>
                                    <p id="selected-coords" class="text-xs text-slate-400">
                                        @if($product?->latitude && $product?->longitude)
                                            {{ $product->latitude }}, {{ $product->longitude }}
                                        @endif
                                    </p>
                                </div>
                                <button type="button" id="clear-location" class="text-slate-400 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Hidden inputs -->
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $product?->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $product?->longitude) }}">

                        <p class="text-xs text-slate-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            만남 장소를 입력 후 검색하거나 지도를 클릭하세요
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
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
                        <input type="number" id="prices_adult" name="prices[adult]" value="{{ old('prices.adult', $adultPrice?->price) }}" min="0"
                               placeholder="0"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error('prices.adult') border-red-500 bg-red-50 @enderror"
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
                        <input type="number" id="prices_child" name="prices[child]" value="{{ old('prices.child', $childPrice?->price) }}" min="0"
                               placeholder="0"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl {{ $scheme['focus'] }} focus:bg-white transition-all @error('prices.child') border-red-500 bg-red-50 @enderror">
                    </div>
                    <x-form-error field="prices.child" />
                </div>
            </div>
        </div>
    </div>

    <!-- 스케줄 관리 -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-cyan-50 to-sky-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-sky-600 flex items-center justify-center shadow-lg shadow-cyan-500/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">스케줄 관리</h3>
                    <p class="text-sm text-slate-500">상품의 운영 일정을 {{ $isEdit ? '수정' : '입력' }}해주세요</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- 기존 스케줄 목록 (수정 시) -->
            @if($isEdit && $product->schedules->count() > 0)
            <div class="mb-6">
                <h4 class="text-sm font-medium text-slate-700 mb-3">기존 스케줄</h4>
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-slate-600">날짜</th>
                                <th class="px-4 py-3 text-left font-medium text-slate-600">시작시간</th>
                                <th class="px-4 py-3 text-left font-medium text-slate-600">정원</th>
                                <th class="px-4 py-3 text-left font-medium text-slate-600">잔여</th>
                                <th class="px-4 py-3 text-left font-medium text-slate-600">상태</th>
                                <th class="px-4 py-3 text-center font-medium text-slate-600">삭제</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($product->schedules as $schedule)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3">{{ $schedule->date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $schedule->start_time->format('H:i') }}</td>
                                <td class="px-4 py-3">{{ $schedule->total_count }}명</td>
                                <td class="px-4 py-3">{{ $schedule->available_count }}명</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $schedule->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $schedule->is_active ? '활성' : '비활성' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="checkbox" name="delete_schedules[]" value="{{ $schedule->id }}"
                                               class="w-5 h-5 rounded border-slate-300 text-red-500 focus:ring-red-500 cursor-pointer">
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="mt-2 text-xs text-slate-500 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    삭제할 스케줄을 체크해주세요
                </p>
            </div>
            @endif

            <!-- 새 스케줄 추가 -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-medium text-slate-700">{{ $isEdit ? '새 스케줄 추가' : '스케줄 등록' }}</h4>
                    <button type="button" id="add-schedule-btn"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-cyan-50 hover:bg-cyan-100 text-cyan-700 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        스케줄 추가
                    </button>
                </div>

                <div id="schedules-container" class="space-y-3">
                    <!-- 스케줄 입력 행은 JavaScript로 동적 추가 -->
                </div>

                <div id="no-schedules-message" class="text-center py-8 border-2 border-dashed border-slate-200 rounded-xl text-slate-500">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="font-medium mb-1">등록된 스케줄이 없습니다</p>
                    <p class="text-sm">"스케줄 추가" 버튼을 클릭하여 일정을 등록하세요</p>
                </div>
            </div>

            <div class="mt-4 p-3 bg-slate-50 rounded-xl">
                <p class="text-sm text-slate-600 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    잔여 인원은 저장 시 정원과 동일하게 자동 설정됩니다
                </p>
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
            <div id="image-dropzone" class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-{{ $colorScheme }}-400 hover:bg-{{ $colorScheme }}-50/30 transition-all">
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

            <!-- 이미지 미리보기 -->
            <div id="image-preview-container" class="hidden mt-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-slate-700">
                        선택된 이미지 (<span id="image-count">0</span>장)
                    </p>
                    <button type="button" id="clear-images" class="text-sm text-red-500 hover:text-red-700 transition-colors">
                        전체 삭제
                    </button>
                </div>
                <div id="image-preview" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3"></div>
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

    <!-- 버튼 -->
    @if(isset($buttons))
        {{ $buttons }}
    @else
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ $cancelRoute }}"
               class="px-6 py-3 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:border-slate-300 font-medium transition-all">
                취소
            </a>
            @if(!$showVendorSelect)
                <button type="submit" name="status" value="draft"
                        class="px-6 py-3 bg-slate-600 text-white rounded-xl hover:bg-slate-700 font-medium transition-all">
                    초안 저장
                </button>
                <button type="submit" name="status" value="pending"
                        class="px-8 py-3 bg-gradient-to-r {{ $scheme['gradient'] }} text-white rounded-xl {{ $scheme['hover'] }} font-medium shadow-lg {{ $scheme['shadow'] }} transition-all">
                    검토 요청
                </button>
            @else
                <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r {{ $scheme['gradient'] }} text-white rounded-xl {{ $scheme['hover'] }} font-medium shadow-lg {{ $scheme['shadow'] }} transition-all">
                    {{ $submitLabel }}
                </button>
            @endif
        </div>
    @endif
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 언어 탭 전환
    const langTabs = document.querySelectorAll('.lang-tab');
    const langTabContents = document.querySelectorAll('.lang-tab-content');

    langTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.dataset.tab;

            // 모든 탭 비활성화
            langTabs.forEach(t => {
                t.classList.remove('active', 'border-emerald-500', 'text-emerald-600');
                t.classList.add('border-transparent', 'text-slate-500');
            });

            // 클릭한 탭 활성화
            this.classList.add('active', 'border-emerald-500', 'text-emerald-600');
            this.classList.remove('border-transparent', 'text-slate-500');

            // 모든 콘텐츠 숨기기
            langTabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // 해당 콘텐츠 표시
            document.getElementById('tab-' + targetTab).classList.remove('hidden');
        });
    });

    // 이미지 업로드 미리보기
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview-container');
    const previewGrid = document.getElementById('image-preview');
    const imageCount = document.getElementById('image-count');
    const clearButton = document.getElementById('clear-images');
    const dropzone = document.getElementById('image-dropzone');

    let selectedFiles = new DataTransfer();

    // 파일 선택 시 미리보기
    imageInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    // 드래그 앤 드롭
    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropzone.classList.add('border-violet-400', 'bg-violet-50/30');
    });

    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropzone.classList.remove('border-violet-400', 'bg-violet-50/30');
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropzone.classList.remove('border-violet-400', 'bg-violet-50/30');
        handleFiles(e.dataTransfer.files);
    });

    // 전체 삭제
    clearButton.addEventListener('click', function() {
        selectedFiles = new DataTransfer();
        imageInput.files = selectedFiles.files;
        updatePreview();
    });

    function handleFiles(files) {
        for (let file of files) {
            if (file.type.startsWith('image/') && selectedFiles.files.length < 10) {
                selectedFiles.items.add(file);
            }
        }
        imageInput.files = selectedFiles.files;
        updatePreview();
    }

    function updatePreview() {
        previewGrid.innerHTML = '';

        if (selectedFiles.files.length > 0) {
            previewContainer.classList.remove('hidden');
            imageCount.textContent = selectedFiles.files.length;

            Array.from(selectedFiles.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group aspect-square';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="미리보기" class="w-full h-full object-cover rounded-xl shadow-sm">
                        <button type="button" data-index="${index}"
                                class="remove-image absolute top-2 right-2 w-7 h-7 bg-black/60 hover:bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-2 rounded-b-xl">
                            <p class="text-white text-xs truncate">${file.name}</p>
                        </div>
                        ${index === 0 ? '<span class="absolute top-2 left-2 px-2 py-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-medium rounded-lg shadow-md">대표</span>' : ''}
                    `;
                    previewGrid.appendChild(div);

                    // 개별 삭제 버튼 이벤트
                    div.querySelector('.remove-image').addEventListener('click', function() {
                        removeFile(parseInt(this.dataset.index));
                    });
                };
                reader.readAsDataURL(file);
            });
        } else {
            previewContainer.classList.add('hidden');
        }
    }

    function removeFile(index) {
        const newFiles = new DataTransfer();
        Array.from(selectedFiles.files).forEach((file, i) => {
            if (i !== index) {
                newFiles.items.add(file);
            }
        });
        selectedFiles = newFiles;
        imageInput.files = selectedFiles.files;
        updatePreview();
    }

    // 네이버 지도 초기화
    initNaverMap();

    // 스케줄 관리 초기화
    initScheduleManager();
});

function initNaverMap() {
    const mapContainer = document.getElementById('meeting-point-map');
    if (!mapContainer || typeof naver === 'undefined') return;

    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const locationInfo = document.getElementById('selected-location-info');
    const selectedAddress = document.getElementById('selected-address');
    const selectedCoords = document.getElementById('selected-coords');
    const clearBtn = document.getElementById('clear-location');
    const searchInput = document.getElementById('translations_ko_meeting_point');
    const searchBtn = document.getElementById('search-address-btn');
    const searchResults = document.getElementById('search-results');

    if (!searchInput || !searchBtn) return;

    // 초기 위치 (저장된 좌표 또는 서울 시청)
    const initialLat = parseFloat(latInput.value) || 37.5665;
    const initialLng = parseFloat(lngInput.value) || 126.9780;
    const hasInitialLocation = latInput.value && lngInput.value;

    const map = new naver.maps.Map(mapContainer, {
        center: new naver.maps.LatLng(initialLat, initialLng),
        zoom: hasInitialLocation ? 16 : 12,
        zoomControl: true,
        zoomControlOptions: {
            position: naver.maps.Position.TOP_RIGHT
        }
    });

    let marker = null;

    // 저장된 위치가 있으면 마커 표시
    if (hasInitialLocation) {
        marker = new naver.maps.Marker({
            position: new naver.maps.LatLng(initialLat, initialLng),
            map: map,
            animation: naver.maps.Animation.DROP
        });
    }

    // 지도 클릭 시 마커 설정
    naver.maps.Event.addListener(map, 'click', function(e) {
        setLocation(e.coord.lat(), e.coord.lng());
    });

    // 위치 설정 함수
    function setLocation(lat, lng, address = null) {
        latInput.value = lat;
        lngInput.value = lng;

        if (marker) {
            marker.setPosition(new naver.maps.LatLng(lat, lng));
        } else {
            marker = new naver.maps.Marker({
                position: new naver.maps.LatLng(lat, lng),
                map: map,
                animation: naver.maps.Animation.DROP
            });
        }

        map.setCenter(new naver.maps.LatLng(lat, lng));
        map.setZoom(16);

        locationInfo.classList.remove('hidden');
        selectedCoords.textContent = `위도: ${lat.toFixed(6)}, 경도: ${lng.toFixed(6)}`;

        if (address) {
            selectedAddress.textContent = address;
            fillMeetingPointFields(address);
        } else {
            naver.maps.Service.reverseGeocode({
                coords: new naver.maps.LatLng(lat, lng),
                orders: [
                    naver.maps.Service.OrderType.ADDR,
                    naver.maps.Service.OrderType.ROAD_ADDR
                ].join(',')
            }, function(status, response) {
                if (status === naver.maps.Service.Status.OK) {
                    const result = response.v2.address;
                    const addr = result.roadAddress || result.jibunAddress || '주소를 찾을 수 없습니다';
                    selectedAddress.textContent = addr;
                    fillMeetingPointFields(addr);
                } else {
                    selectedAddress.textContent = '주소를 찾을 수 없습니다';
                }
            });
        }

        searchResults.classList.add('hidden');
    }

    // 만남 장소 필드 자동 채우기
    function fillMeetingPointFields(address) {
        if (searchInput) {
            searchInput.value = address;
        }
    }

    // 위치 초기화
    clearBtn.addEventListener('click', function() {
        latInput.value = '';
        lngInput.value = '';
        if (marker) {
            marker.setMap(null);
            marker = null;
        }
        locationInfo.classList.add('hidden');
        map.setCenter(new naver.maps.LatLng(37.5665, 126.9780));
        map.setZoom(12);
    });

    // 주소 검색
    function searchAddress(query) {
        if (!query.trim()) return;

        naver.maps.Service.geocode({
            query: query
        }, function(status, response) {
            if (status !== naver.maps.Service.Status.OK || response.v2.meta.totalCount === 0) {
                searchResults.innerHTML = '<div class="p-4 text-center text-slate-500">검색 결과가 없습니다</div>';
                searchResults.classList.remove('hidden');
                return;
            }

            const items = response.v2.addresses;
            searchResults.innerHTML = '';

            items.forEach(function(item) {
                const div = document.createElement('div');
                div.className = 'p-3 hover:bg-cyan-50 cursor-pointer transition-colors';
                div.innerHTML = `
                    <p class="font-medium text-slate-800">${item.roadAddress || item.jibunAddress}</p>
                    ${item.roadAddress && item.jibunAddress ? `<p class="text-sm text-slate-500 mt-1">${item.jibunAddress}</p>` : ''}
                `;
                div.addEventListener('click', function() {
                    setLocation(parseFloat(item.y), parseFloat(item.x), item.roadAddress || item.jibunAddress);
                });
                searchResults.appendChild(div);
            });

            searchResults.classList.remove('hidden');
        });
    }

    searchBtn.addEventListener('click', function() {
        searchAddress(searchInput.value);
    });

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchAddress(searchInput.value);
        }
    });

    // 검색창 외부 클릭 시 결과 숨기기
    document.addEventListener('click', function(e) {
        if (!searchResults.contains(e.target) && e.target !== searchInput && e.target !== searchBtn) {
            searchResults.classList.add('hidden');
        }
    });
}

function initScheduleManager() {
    const container = document.getElementById('schedules-container');
    const addBtn = document.getElementById('add-schedule-btn');
    const noSchedulesMessage = document.getElementById('no-schedules-message');

    if (!container || !addBtn) return;

    let scheduleIndex = 0;

    // 스케줄 행 추가
    function addScheduleRow() {
        const row = document.createElement('div');
        row.className = 'schedule-row bg-slate-50 rounded-xl p-4 border border-slate-200';
        row.dataset.index = scheduleIndex;

        // 오늘 날짜를 기본값으로
        const today = new Date().toISOString().split('T')[0];

        row.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">날짜 <span class="text-red-500">*</span></label>
                    <input type="date" name="schedules[${scheduleIndex}][date]" value="${today}" required
                           class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">시작시간 <span class="text-red-500">*</span></label>
                    <input type="time" name="schedules[${scheduleIndex}][start_time]" value="09:00" required
                           class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">정원 (명) <span class="text-red-500">*</span></label>
                    <input type="number" name="schedules[${scheduleIndex}][total_count]" value="10" min="1" required
                           class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                </div>
                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">활성화</label>
                        <label class="flex items-center gap-2 px-3 py-2.5 bg-white border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors">
                            <input type="checkbox" name="schedules[${scheduleIndex}][is_active]" value="1" checked
                                   class="w-4 h-4 text-cyan-600 border-slate-300 rounded focus:ring-cyan-500">
                            <span class="text-sm text-slate-700">활성</span>
                        </label>
                    </div>
                    <button type="button" class="remove-schedule-btn w-10 h-10 flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-500 rounded-lg transition-colors" title="삭제">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        // 삭제 버튼 이벤트
        row.querySelector('.remove-schedule-btn').addEventListener('click', function() {
            row.remove();
            updateNoSchedulesMessage();
        });

        container.appendChild(row);
        scheduleIndex++;
        updateNoSchedulesMessage();
    }

    // "스케줄 없음" 메시지 업데이트
    function updateNoSchedulesMessage() {
        const hasSchedules = container.querySelectorAll('.schedule-row').length > 0;
        if (noSchedulesMessage) {
            noSchedulesMessage.classList.toggle('hidden', hasSchedules);
        }
    }

    // 추가 버튼 클릭
    addBtn.addEventListener('click', addScheduleRow);

    // 초기 상태 업데이트
    updateNoSchedulesMessage();
}
</script>
