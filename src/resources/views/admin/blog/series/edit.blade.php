<x-layouts.admin>
    <x-slot name="header">시리즈 수정</x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.blog.series.update', $series) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-slate-200/60 p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">제목 <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $series->title) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                           placeholder="시리즈 제목">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">슬러그</label>
                    <input type="text" name="slug" value="{{ old('slug', $series->slug) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                    @error('slug')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">설명</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                              placeholder="시리즈 설명">{{ old('description', $series->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">썸네일</label>
                    <div id="thumbnailPreview" class="{{ $series->thumbnail_url ? '' : 'hidden' }} aspect-video rounded-xl bg-slate-100 overflow-hidden mb-2">
                        <img id="thumbnailImage" src="{{ $series->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                    </div>
                    <input type="file" name="thumbnail" accept="image/*"
                           class="block w-full text-sm text-slate-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-medium
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100"
                           onchange="previewThumbnail(this)">
                    @if($series->thumbnail_url)
                        <label class="flex items-center gap-2 mt-2 text-sm text-slate-600">
                            <input type="checkbox" name="remove_thumbnail" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                            썸네일 삭제
                        </label>
                    @endif
                    @error('thumbnail')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                           {{ old('is_active', $series->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 text-sm text-slate-700">활성화</label>
                </div>

                <div class="flex gap-2 pt-4 border-t border-slate-200">
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 font-medium">
                        저장
                    </button>
                    <a href="{{ route('admin.blog.series.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                        취소
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        function previewThumbnail(input) {
            const preview = document.getElementById('thumbnailPreview');
            const image = document.getElementById('thumbnailImage');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    image.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-layouts.admin>
