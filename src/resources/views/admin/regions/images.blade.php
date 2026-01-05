<x-layouts.admin>
    <x-slot name="header">지역 이미지 관리</x-slot>

    <div class="max-w-5xl space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.regions.show', $region) }}"
                   class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-semibold text-slate-800">{{ $region->getName('ko') }}</h2>
                    <p class="text-sm text-slate-500">이미지 관리</p>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4">새 이미지 업로드</h3>
            <form method="POST" action="{{ route('admin.regions.images.store', $region) }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">이미지 파일 <span class="text-red-500">*</span></label>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" required
                               class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-slate-500">JPEG, PNG, WebP (최대 5MB)</p>
                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">유형 <span class="text-red-500">*</span></label>
                        <select name="type" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            <option value="gallery">갤러리</option>
                            <option value="hero">히어로</option>
                            <option value="thumbnail">썸네일</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_primary" value="1"
                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-slate-700">대표 이미지</span>
                        </label>
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 font-medium">
                            업로드
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Images Grid -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-800">등록된 이미지 ({{ $region->images->count() }})</h3>
            </div>

            @if($region->images->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="images-grid">
                    @foreach($region->images as $image)
                        <div class="relative group bg-slate-100 rounded-xl overflow-hidden aspect-[4/3]" data-image-id="{{ $image->id }}">
                            <!-- Image -->
                            <img src="{{ $image->url }}" alt="Region image"
                                 class="w-full h-full object-cover">

                            <!-- Badges -->
                            <div class="absolute top-2 left-2 flex flex-wrap gap-1">
                                @if($image->is_primary)
                                    <span class="px-2 py-0.5 text-xs font-medium rounded bg-amber-500 text-white">
                                        대표
                                    </span>
                                @endif
                                <span class="px-2 py-0.5 text-xs font-medium rounded bg-slate-800/70 text-white">
                                    {{ $image->type === 'hero' ? '히어로' : ($image->type === 'thumbnail' ? '썸네일' : '갤러리') }}
                                </span>
                            </div>

                            <!-- Actions Overlay -->
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <!-- Set as Primary -->
                                @if(!$image->is_primary)
                                    <form method="POST" action="{{ route('admin.regions.images.primary', [$region, $image]) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors" title="대표 이미지 설정">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                                <!-- Edit Button -->
                                <button type="button"
                                        onclick="openEditModal({{ $image->id }}, '{{ $image->type }}', {{ $image->is_primary ? 'true' : 'false' }})"
                                        class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors" title="수정">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <form method="POST" action="{{ route('admin.regions.images.destroy', [$region, $image]) }}" class="inline"
                                      onsubmit="return confirm('정말 삭제하시겠습니까?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors" title="삭제">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <!-- Sort Order -->
                            <div class="absolute bottom-2 right-2">
                                <span class="px-2 py-0.5 text-xs font-medium rounded bg-slate-800/70 text-white">
                                    #{{ $image->sort_order }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 font-medium">등록된 이미지가 없습니다.</p>
                    <p class="text-slate-400 text-sm mt-1">위에서 새 이미지를 업로드해주세요.</p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="flex gap-2">
            <a href="{{ route('admin.regions.show', $region) }}"
               class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                돌아가기
            </a>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50" onclick="closeEditModal(event)">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4" onclick="event.stopPropagation()">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">이미지 정보 수정</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">유형</label>
                        <select name="type" id="editType"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            <option value="gallery">갤러리</option>
                            <option value="hero">히어로</option>
                            <option value="thumbnail">썸네일</option>
                        </select>
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_primary" value="1" id="editPrimary"
                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-slate-700">대표 이미지</span>
                        </label>
                    </div>
                </div>
                <div class="flex gap-2 mt-6">
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all font-medium">
                        저장
                    </button>
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                        취소
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const baseUpdateUrl = '{{ url("admin/regions/{$region->id}/images") }}';

        function openEditModal(imageId, type, isPrimary) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            const typeSelect = document.getElementById('editType');
            const primaryCheck = document.getElementById('editPrimary');

            form.action = baseUpdateUrl + '/' + imageId;
            typeSelect.value = type;
            primaryCheck.checked = isPrimary;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal(event) {
            if (event && event.target !== document.getElementById('editModal')) return;
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeEditModal();
        });
    </script>
</x-layouts.admin>
