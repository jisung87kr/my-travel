<x-layouts.admin>
    <x-slot name="header">카테고리 수정</x-slot>

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('admin.product-categories.update', $productCategory) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-slate-200/60 p-6 space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">슬러그 <span class="text-red-500">*</span></label>
                        <input type="text" name="slug" value="{{ old('slug', $productCategory->slug) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                               placeholder="예: cultural-experience">
                        @error('slug')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">레벨 <span class="text-red-500">*</span></label>
                        <select name="level" required id="level-select"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            <option value="1" {{ old('level', $productCategory->level) == 1 ? 'selected' : '' }}>대분류</option>
                            <option value="2" {{ old('level', $productCategory->level) == 2 ? 'selected' : '' }}>중분류</option>
                            <option value="3" {{ old('level', $productCategory->level) == 3 ? 'selected' : '' }}>소분류</option>
                        </select>
                    </div>
                </div>

                <div id="parent-field">
                    <label class="block text-sm font-medium text-slate-700 mb-2">상위 카테고리</label>
                    <select name="parent_id" id="parent-select"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                        <option value="">없음 (최상위)</option>
                        @if(isset($parents[1]))
                            <optgroup label="대분류">
                                @foreach($parents[1] as $main)
                                    <option value="{{ $main->id }}" data-level="1" {{ old('parent_id', $productCategory->parent_id) == $main->id ? 'selected' : '' }}>
                                        {{ $main->getName('ko') }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                        @if(isset($parents[2]))
                            <optgroup label="중분류">
                                @foreach($parents[2] as $sub)
                                    <option value="{{ $sub->id }}" data-level="2" {{ old('parent_id', $productCategory->parent_id) == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->getName('ko') }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                    @error('parent_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Korean Translation -->
                @php
                    $koTranslation = $productCategory->translations->firstWhere('locale', 'ko');
                    $enTranslation = $productCategory->translations->firstWhere('locale', 'en');
                @endphp
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4">한국어</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">이름 <span class="text-red-500">*</span></label>
                            <input type="text" name="translations[ko][name]" value="{{ old('translations.ko.name', $koTranslation?->name) }}" required
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                   placeholder="예: 문화체험">
                            @error('translations.ko.name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">설명</label>
                            <textarea name="translations[ko][description]" rows="2"
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                      placeholder="카테고리 설명 (선택)">{{ old('translations.ko.description', $koTranslation?->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- English Translation -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4">English</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Name</label>
                            <input type="text" name="translations[en][name]" value="{{ old('translations.en.name', $enTranslation?->name) }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                   placeholder="e.g., Cultural Experience">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea name="translations[en][description]" rows="2"
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                      placeholder="Category description (optional)">{{ old('translations.en.description', $enTranslation?->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Icon -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4">아이콘</h3>
                    <div class="space-y-4">
                        @if($productCategory->hasIcon())
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
                                <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center border border-slate-200 shadow-sm">
                                    {!! $productCategory->getIconHtml('w-8 h-8 text-slate-600') !!}
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-700">현재 아이콘</p>
                                    <p class="text-xs text-slate-500">{{ $productCategory->icon_type === 'svg' ? 'SVG 코드' : '이미지 파일' }}</p>
                                </div>
                                <label class="flex items-center gap-2 text-sm text-red-600 cursor-pointer hover:text-red-700">
                                    <input type="checkbox" name="remove_icon" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                                    삭제
                                </label>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">아이콘 유형</label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="icon_type" value="svg" id="icon-type-svg"
                                           class="text-blue-600 focus:ring-blue-500"
                                           {{ old('icon_type', $productCategory->icon_type ?? 'svg') === 'svg' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-slate-700">SVG 코드</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="icon_type" value="image" id="icon-type-image"
                                           class="text-blue-600 focus:ring-blue-500"
                                           {{ old('icon_type', $productCategory->icon_type) === 'image' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-slate-700">이미지 파일</span>
                                </label>
                            </div>
                        </div>

                        <div id="svg-input-section" class="{{ old('icon_type', $productCategory->icon_type) === 'image' ? 'hidden' : '' }}">
                            <label class="block text-sm font-medium text-slate-700 mb-2">SVG 코드</label>
                            <textarea name="icon_svg" rows="4" id="icon-svg-input"
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors font-mono text-sm"
                                      placeholder='<svg viewBox="0 0 24 24">...</svg>'>{{ old('icon_svg', $productCategory->icon_type === 'svg' ? $productCategory->icon : '') }}</textarea>
                            <p class="mt-1 text-xs text-slate-500">Heroicons, Lucide 등의 SVG 아이콘 코드를 붙여넣으세요</p>
                        </div>

                        <div id="image-input-section" class="{{ old('icon_type', $productCategory->icon_type) !== 'image' ? 'hidden' : '' }}">
                            <label class="block text-sm font-medium text-slate-700 mb-2">이미지 파일</label>
                            <input type="file" name="icon_image" accept="image/*"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-slate-500">PNG, SVG, JPG 파일 (최대 1MB)</p>
                        </div>

                        <div id="icon-preview" class="hidden">
                            <label class="block text-sm font-medium text-slate-700 mb-2">새 아이콘 미리보기</label>
                            <div class="w-16 h-16 rounded-xl bg-slate-100 flex items-center justify-center border border-slate-200">
                                <div id="icon-preview-content" class="w-8 h-8 text-slate-600"></div>
                            </div>
                        </div>

                        @error('icon_svg')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('icon_image')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Settings -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4">설정</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">정렬 순서</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $productCategory->sort_order) }}" min="0"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                        </div>
                    </div>
                    <div class="flex gap-6 mt-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1"
                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                   {{ old('is_active', $productCategory->is_active) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-slate-700">활성화</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1"
                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                   {{ old('is_featured', $productCategory->is_featured) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-slate-700">추천 카테고리</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-2 pt-4 border-t border-slate-200">
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 font-medium">
                        저장
                    </button>
                    <a href="{{ route('admin.product-categories.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                        취소
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('level-select').addEventListener('change', function() {
            const parentField = document.getElementById('parent-field');
            const parentSelect = document.getElementById('parent-select');
            const level = parseInt(this.value);

            if (level === 1) {
                parentField.style.display = 'none';
                parentSelect.value = '';
            } else {
                parentField.style.display = 'block';
                // Filter options based on level
                const options = parentSelect.querySelectorAll('option[data-level]');
                options.forEach(opt => {
                    const optLevel = parseInt(opt.dataset.level);
                    if (level === 2) {
                        opt.style.display = optLevel === 1 ? '' : 'none';
                    } else if (level === 3) {
                        opt.style.display = optLevel === 2 ? '' : 'none';
                    }
                });
            }
        });

        // Trigger on page load
        document.getElementById('level-select').dispatchEvent(new Event('change'));

        // Icon type toggle
        const svgSection = document.getElementById('svg-input-section');
        const imageSection = document.getElementById('image-input-section');
        const iconPreview = document.getElementById('icon-preview');
        const iconPreviewContent = document.getElementById('icon-preview-content');
        const svgInput = document.getElementById('icon-svg-input');

        document.querySelectorAll('input[name="icon_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'svg') {
                    svgSection.classList.remove('hidden');
                    imageSection.classList.add('hidden');
                } else {
                    svgSection.classList.add('hidden');
                    imageSection.classList.remove('hidden');
                }
            });
        });

        // SVG preview
        svgInput.addEventListener('input', function() {
            const svg = this.value.trim();
            if (svg && svg.includes('<svg')) {
                iconPreview.classList.remove('hidden');
                iconPreviewContent.innerHTML = svg;
            } else {
                iconPreview.classList.add('hidden');
            }
        });

        // Image preview
        document.querySelector('input[name="icon_image"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    iconPreview.classList.remove('hidden');
                    iconPreviewContent.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-layouts.admin>
