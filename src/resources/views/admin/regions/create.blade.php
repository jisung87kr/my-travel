<x-layouts.admin>
    <x-slot name="header">새 지역</x-slot>

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('admin.regions.store') }}">
            @csrf

            <div class="bg-white rounded-2xl border border-slate-200/60 p-6 space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">코드 <span class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code') }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                               placeholder="예: seoul, gangnam-gu">
                        @error('code')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">레벨 <span class="text-red-500">*</span></label>
                        <select name="level" required id="level-select"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            <option value="1" {{ old('level') == 1 ? 'selected' : '' }}>국가</option>
                            <option value="2" {{ old('level', 2) == 2 ? 'selected' : '' }}>시/도</option>
                            <option value="3" {{ old('level') == 3 ? 'selected' : '' }}>시/군/구</option>
                        </select>
                    </div>
                </div>

                <div id="parent-field">
                    <label class="block text-sm font-medium text-slate-700 mb-2">상위 지역</label>
                    <select name="parent_id" id="parent-select"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                        <option value="">없음 (최상위)</option>
                        @if(isset($parents[1]))
                            <optgroup label="국가">
                                @foreach($parents[1] as $country)
                                    <option value="{{ $country->id }}" data-level="1" {{ old('parent_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->getName('ko') }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                        @if(isset($parents[2]))
                            <optgroup label="시/도">
                                @foreach($parents[2] as $province)
                                    <option value="{{ $province->id }}" data-level="2" {{ old('parent_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->getName('ko') }}
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
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4">한국어</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">이름 <span class="text-red-500">*</span></label>
                                <input type="text" name="translations[ko][name]" value="{{ old('translations.ko.name') }}" required
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                       placeholder="예: 서울특별시">
                                @error('translations.ko.name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">짧은 이름</label>
                                <input type="text" name="translations[ko][short_name]" value="{{ old('translations.ko.short_name') }}"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                       placeholder="예: 서울">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">설명</label>
                            <textarea name="translations[ko][description]" rows="2"
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                      placeholder="지역 설명 (선택)">{{ old('translations.ko.description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- English Translation -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4">English</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Name</label>
                                <input type="text" name="translations[en][name]" value="{{ old('translations.en.name') }}"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                       placeholder="e.g., Seoul">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Short Name</label>
                                <input type="text" name="translations[en][short_name]" value="{{ old('translations.en.short_name') }}"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                       placeholder="e.g., Seoul">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea name="translations[en][description]" rows="2"
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                      placeholder="Region description (optional)">{{ old('translations.en.description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4">위치 정보</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">위도</label>
                            <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude') }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                   placeholder="37.5665">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">경도</label>
                            <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude') }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                   placeholder="126.9780">
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4">설정</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">정렬 순서</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">타임존</label>
                            <input type="text" name="timezone" value="{{ old('timezone', 'Asia/Seoul') }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                        </div>
                    </div>
                    <div class="flex gap-6 mt-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1"
                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-slate-700">활성화</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1"
                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-slate-700">추천 지역</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-2 pt-4 border-t border-slate-200">
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 font-medium">
                        저장
                    </button>
                    <a href="{{ route('admin.regions.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
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
    </script>
</x-layouts.admin>
