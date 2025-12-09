<x-layouts.vendor>
    <x-slot name="header">새 상품 등록</x-slot>

    <div class="max-w-4xl">
        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ route('vendor.products.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                상품 목록으로
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
            <form method="POST" action="{{ route('vendor.products.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Basic Info -->
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4">기본 정보</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">상품 유형 *</label>
                            <select name="type" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                                @foreach($types as $type)
                                    <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">지역 *</label>
                            <select name="region" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                                @foreach($regions as $region)
                                    <option value="{{ $region['value'] }}">{{ $region['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">소요시간 (분)</label>
                            <input type="number" name="duration" min="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">최대 인원</label>
                            <input type="number" name="max_persons" min="1" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">예약 유형 *</label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="booking_type" value="instant" checked class="w-4 h-4 text-violet-600 border-slate-300 focus:ring-violet-500">
                                    <span class="text-sm text-slate-700">자동 확정 (Instant Booking)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="booking_type" value="request" class="w-4 h-4 text-violet-600 border-slate-300 focus:ring-violet-500">
                                    <span class="text-sm text-slate-700">승인 필요 (Request Booking)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Korean Content -->
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4">상품 정보 (한국어) *</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">상품명 *</label>
                            <input type="text" name="translations[ko][title]" required
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">간단 설명</label>
                            <input type="text" name="translations[ko][short_description]"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">상세 설명 *</label>
                            <textarea name="translations[ko][description]" rows="5" required
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">포함 사항</label>
                            <textarea name="translations[ko][includes]" rows="3"
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors resize-none"
                                      placeholder="줄바꿈으로 구분"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">불포함 사항</label>
                            <textarea name="translations[ko][excludes]" rows="3"
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors resize-none"
                                      placeholder="줄바꿈으로 구분"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Prices -->
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4">가격 설정</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">성인 가격 (원) *</label>
                            <input type="number" name="prices[adult]" min="0" required
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">아동 가격 (원)</label>
                            <input type="number" name="prices[child]" min="0"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4">이미지</h3>

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
                    <button type="submit" name="status" value="draft"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-slate-600 rounded-xl hover:bg-slate-700 transition-colors">
                        초안 저장
                    </button>
                    <button type="submit" name="status" value="pending_review"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-violet-600 to-violet-700 rounded-xl hover:from-violet-700 hover:to-violet-800 shadow-lg shadow-violet-500/25 transition-all">
                        검토 요청
                    </button>
                </div>
            </form>
        </noscript>
    </div>
</x-layouts.vendor>
