<x-layouts.admin>
    <x-slot name="header">새 포스트 작성</x-slot>

    <div class="max-w-5xl">
        <form method="POST" action="{{ route('admin.blog.posts.store') }}" enctype="multipart/form-data" id="postForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Title -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">제목 <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-lg"
                               placeholder="포스트 제목을 입력하세요">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content Editor -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">내용 <span class="text-red-500">*</span></label>
                        <div id="editor" class="min-h-[400px] border border-slate-200 overflow-hidden bg-white"></div>
                        <input type="hidden" name="content" id="contentInput">
                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">요약</label>
                        <textarea name="excerpt" rows="3"
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                  placeholder="포스트 요약 (목록에 표시됩니다)">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SEO -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">SEO 설정</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">SEO 제목</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title') }}" maxlength="60"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                       placeholder="검색 결과에 표시될 제목 (60자 이내)">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">SEO 설명</label>
                                <textarea name="meta_description" rows="2" maxlength="160"
                                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                          placeholder="검색 결과에 표시될 설명 (160자 이내)">{{ old('meta_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Publish Settings -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">발행 설정</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">상태</label>
                                <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>임시저장</option>
                                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>즉시 발행</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">URL 슬러그</label>
                                <input type="text" name="slug" value="{{ old('slug') }}"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                       placeholder="자동 생성됩니다">
                            </div>
                        </div>

                        <div class="mt-6 flex gap-2">
                            <button type="submit" class="flex-1 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 font-medium">
                                저장
                            </button>
                            <a href="{{ route('admin.blog.posts.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                                취소
                            </a>
                        </div>
                    </div>

                    <!-- Category & Series -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">분류</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">카테고리</label>
                                <select name="category_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                    <option value="">선택 안함</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">시리즈</label>
                                <select name="series_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                    <option value="">선택 안함</option>
                                    @foreach($series as $s)
                                        <option value="{{ $s->id }}" {{ old('series_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">시리즈 순서</label>
                                <input type="number" name="series_order" value="{{ old('series_order') }}" min="1"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                       placeholder="시리즈 내 순서">
                            </div>
                        </div>
                    </div>

                    <!-- Thumbnail -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">썸네일</h3>
                        <div class="space-y-4">
                            <div id="thumbnailPreview" class="hidden aspect-video rounded-xl bg-slate-100 overflow-hidden">
                                <img id="thumbnailImage" src="" alt="" class="w-full h-full object-cover">
                            </div>
                            <label class="block">
                                <span class="sr-only">썸네일 이미지 선택</span>
                                <input type="file" name="thumbnail" accept="image/*"
                                       class="block w-full text-sm text-slate-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-lg file:border-0
                                              file:text-sm file:font-medium
                                              file:bg-blue-50 file:text-blue-700
                                              hover:file:bg-blue-100"
                                       onchange="previewThumbnail(this)">
                            </label>
                            @error('thumbnail')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">태그</h3>
                        <div id="tagsContainer" class="space-y-2">
                            <div class="flex flex-wrap gap-2 mb-2" id="tagsList"></div>
                            <div class="flex gap-2">
                                <input type="text" id="tagInput"
                                       class="flex-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-sm"
                                       placeholder="태그 입력 후 Enter">
                                <button type="button" onclick="addTag()"
                                        class="px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                    추가
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <style>
        #editor {
            height: 400px;
        }
        #editor .ql-editor {
            min-height: 350px;
            font-size: 1rem;
            line-height: 1.75;
        }
        .ql-toolbar.ql-snow {
            /*border-top-left-radius: 0.75rem;*/
            /*border-top-right-radius: 0.75rem;*/
            border-color: #e2e8f0;
            background: #f8fafc;
        }
        .ql-container.ql-snow {
            /*border-bottom-left-radius: 0.75rem;*/
            /*border-bottom-right-radius: 0.75rem;*/
            border-color: #e2e8f0;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <script>
        // Quill Editor
        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: '내용을 입력하세요...',
            modules: {
                toolbar: {
                    container: [
                        [{'header': [1, 2, 3, false]}],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{'color': []}, {'background': []}],
                        [{'list': 'ordered'}, {'list': 'bullet'}],
                        [{'indent': '-1'}, {'indent': '+1'}],
                        ['blockquote', 'code-block'],
                        ['link', 'image', 'video'],
                        [{'align': []}],
                        ['clean']
                    ],
                    handlers: {
                        image: imageHandler
                    }
                }
            }
        });

        // Custom image upload handler
        function imageHandler() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = async () => {
                const file = input.files[0];
                if (!file) return;

                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('이미지 크기는 5MB를 초과할 수 없습니다.');
                    return;
                }

                const formData = new FormData();
                formData.append('file', file);

                try {
                    const response = await fetch('/api/admin/attachments', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('업로드 실패');
                    }

                    const result = await response.json();
                    const range = quill.getSelection(true);
                    quill.insertEmbed(range.index, 'image', result.url);
                    quill.setSelection(range.index + 1);
                } catch (error) {
                    console.error('Image upload error:', error);
                    alert('이미지 업로드에 실패했습니다.');
                }
            };
        }

        // Form submit - save content to hidden input
        document.getElementById('postForm').addEventListener('submit', function() {
            document.getElementById('contentInput').value = quill.root.innerHTML;
        });

        // Thumbnail preview
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

        // Tags
        let tags = [];

        function renderTags() {
            const container = document.getElementById('tagsList');
            container.innerHTML = tags.map((tag, i) => `
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm">
                    #${tag}
                    <button type="button" onclick="removeTag(${i})" class="hover:text-blue-900">×</button>
                    <input type="hidden" name="tags[]" value="${tag}">
                </span>
            `).join('');
        }

        function addTag() {
            const input = document.getElementById('tagInput');
            const value = input.value.trim();
            if (value && !tags.includes(value)) {
                tags.push(value);
                renderTags();
                input.value = '';
            }
        }

        function removeTag(index) {
            tags.splice(index, 1);
            renderTags();
        }

        document.getElementById('tagInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTag();
            }
        });
    </script>
    @endpush
</x-layouts.admin>
