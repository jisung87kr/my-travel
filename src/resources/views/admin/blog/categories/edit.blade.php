<x-layouts.admin>
    <x-slot name="header">카테고리 수정</x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.blog.categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-slate-200/60 p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">이름 <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                           placeholder="카테고리 이름">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">슬러그</label>
                    <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                    @error('slug')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">설명</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                              placeholder="카테고리 설명 (선택)">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">정렬 순서</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 text-sm text-slate-700">활성화</label>
                </div>

                <div class="flex gap-2 pt-4 border-t border-slate-200">
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 font-medium">
                        저장
                    </button>
                    <a href="{{ route('admin.blog.categories.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                        취소
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin>
