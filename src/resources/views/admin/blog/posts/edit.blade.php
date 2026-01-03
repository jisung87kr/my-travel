<x-layouts.admin>
    <x-slot name="header">포스트 수정</x-slot>

    <div class="max-w-5xl">
        <form method="POST" action="{{ route('admin.blog.posts.update', $post) }}" enctype="multipart/form-data" id="postForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Title -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">제목 <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-lg"
                               placeholder="포스트 제목을 입력하세요">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content Editor -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">내용 <span class="text-red-500">*</span></label>
                        <div id="editor" class="min-h-[400px] border border-slate-200 rounded-xl overflow-hidden"></div>
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
                                  placeholder="포스트 요약 (목록에 표시됩니다)">{{ old('excerpt', $post->excerpt) }}</textarea>
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
                                <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" maxlength="60"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                       placeholder="검색 결과에 표시될 제목 (60자 이내)">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">SEO 설명</label>
                                <textarea name="meta_description" rows="2" maxlength="160"
                                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                          placeholder="검색 결과에 표시될 설명 (160자 이내)">{{ old('meta_description', $post->meta_description) }}</textarea>
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
                                    <option value="draft" {{ old('status', $post->status->value) === 'draft' ? 'selected' : '' }}>임시저장</option>
                                    <option value="published" {{ old('status', $post->status->value) === 'published' ? 'selected' : '' }}>발행됨</option>
                                    <option value="archived" {{ old('status', $post->status->value) === 'archived' ? 'selected' : '' }}>보관됨</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">URL 슬러그</label>
                                <input type="text" name="slug" value="{{ old('slug', $post->slug) }}"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>
                            @if($post->published_at)
                                <div class="text-sm text-slate-500">
                                    발행일: {{ $post->published_at->format('Y-m-d H:i') }}
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex gap-2">
                            <button type="submit" class="flex-1 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 font-medium">
                                저장
                            </button>
                            <a href="{{ route('admin.blog.posts.show', $post) }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
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
                                        <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
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
                                        <option value="{{ $s->id }}" {{ old('series_id', $post->series_id) == $s->id ? 'selected' : '' }}>
                                            {{ $s->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">시리즈 순서</label>
                                <input type="number" name="series_order" value="{{ old('series_order', $post->series_order) }}" min="1"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"
                                       placeholder="시리즈 내 순서">
                            </div>
                        </div>
                    </div>

                    <!-- Thumbnail -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">썸네일</h3>
                        <div class="space-y-4">
                            <div id="thumbnailPreview" class="{{ $post->thumbnail_url ? '' : 'hidden' }} aspect-video rounded-xl bg-slate-100 overflow-hidden relative group">
                                <img id="thumbnailImage" src="{{ $post->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                                @if($post->thumbnail_url)
                                    <label class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center cursor-pointer transition-opacity">
                                        <span class="text-white text-sm font-medium">변경하기</span>
                                        <input type="file" name="thumbnail" accept="image/*" class="hidden" onchange="previewThumbnail(this)">
                                    </label>
                                @endif
                            </div>
                            @if(!$post->thumbnail_url)
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
                            @endif
                            @if($post->thumbnail_url)
                                <label class="flex items-center gap-2 text-sm text-slate-600">
                                    <input type="checkbox" name="remove_thumbnail" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                                    썸네일 삭제
                                </label>
                            @endif
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

                    <!-- Stats -->
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">통계</h3>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-2xl font-bold text-slate-800">{{ number_format($post->view_count) }}</p>
                                <p class="text-sm text-slate-500">조회수</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-pink-600">{{ number_format($post->like_count) }}</p>
                                <p class="text-sm text-slate-500">좋아요</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-800">{{ number_format($post->comment_count) }}</p>
                                <p class="text-sm text-slate-500">댓글</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@tiptap/core@2.1.12/dist/index.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tiptap/starter-kit@2.1.12/dist/index.umd.min.js"></script>
    <script>
        // Tiptap Editor
        const { Editor } = window.tiptapCore;
        const StarterKit = window.tiptapStarterKit.StarterKit;

        const editor = new Editor({
            element: document.querySelector('#editor'),
            extensions: [StarterKit],
            content: {!! json_encode($post->content) !!},
            editorProps: {
                attributes: {
                    class: 'prose prose-slate max-w-none p-4 min-h-[400px] focus:outline-none',
                },
            },
        });

        // Form submit
        document.getElementById('postForm').addEventListener('submit', function() {
            document.getElementById('contentInput').value = editor.getHTML();
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
        let tags = {!! json_encode($post->tags->pluck('name')->toArray()) !!};

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

        // Initialize tags
        renderTags();
    </script>
    @endpush

    @push('styles')
    <style>
        .ProseMirror {
            min-height: 400px;
        }
        .ProseMirror:focus {
            outline: none;
        }
        .ProseMirror p {
            margin: 0.5em 0;
        }
        .ProseMirror h1, .ProseMirror h2, .ProseMirror h3 {
            margin-top: 1em;
            margin-bottom: 0.5em;
        }
    </style>
    @endpush
</x-layouts.admin>
