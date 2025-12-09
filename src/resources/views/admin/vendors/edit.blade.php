<x-layouts.admin>
    <x-slot name="header">제공자 수정</x-slot>

    <!-- Breadcrumb -->
    <nav class="mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('admin.vendors.index') }}" class="text-slate-500 hover:text-blue-600 transition-colors">제공자 관리</a>
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-slate-500 hover:text-blue-600 transition-colors">{{ $vendor->business_name ?? $vendor->company_name }}</a>
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-800 font-medium">수정</span>
    </nav>

    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <form method="POST" action="{{ route('admin.vendors.update', $vendor) }}">
                @csrf
                @method('PUT')

                <!-- Header with Avatar -->
                <div class="px-6 py-5 border-b border-slate-200/60 bg-gradient-to-r from-purple-500/10 to-violet-500/10">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-purple-500 to-violet-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-purple-500/30">
                            {{ mb_substr($vendor->business_name ?? $vendor->company_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800 text-lg">{{ $vendor->business_name ?? $vendor->company_name }}</h3>
                            <p class="text-sm text-slate-500">{{ $vendor->user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- 담당자 정보 -->
                <div class="px-6 py-4 border-b border-slate-200/60 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-slate-700">담당자 정보</h3>
                    </div>
                </div>
                <div class="p-6 border-b border-slate-200/60">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                이름 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $vendor->user->name) }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('name') border-red-500 bg-red-50 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                이메일 <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $vendor->user->email) }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('email') border-red-500 bg-red-50 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                                전화번호
                            </label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $vendor->user->phone) }}"
                                   placeholder="010-1234-5678"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('phone') border-red-500 bg-red-50 @enderror">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                                비밀번호
                            </label>
                            <input type="password" id="password" name="password"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('password') border-red-500 bg-red-50 @enderror"
                                   placeholder="변경 시에만 입력">
                            <p class="mt-2 text-xs text-slate-500">비워두면 기존 비밀번호가 유지됩니다</p>
                            @error('password')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 사업자 정보 -->
                <div class="px-6 py-4 border-b border-slate-200/60 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-slate-700">사업자 정보</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="company_name" class="block text-sm font-medium text-slate-700 mb-2">
                                회사명/상호 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $vendor->business_name ?? $vendor->company_name) }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('company_name') border-red-500 bg-red-50 @enderror"
                                   required>
                            @error('company_name')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="business_number" class="block text-sm font-medium text-slate-700 mb-2">
                                사업자등록번호
                            </label>
                            <input type="text" id="business_number" name="business_number" value="{{ old('business_number', $vendor->business_number) }}"
                                   placeholder="123-45-67890"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('business_number') border-red-500 bg-red-50 @enderror">
                            @error('business_number')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-slate-700 mb-2">
                                사업장 연락처
                            </label>
                            <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $vendor->contact_phone) }}"
                                   placeholder="02-1234-5678"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('contact_phone') border-red-500 bg-red-50 @enderror">
                            @error('contact_phone')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-slate-700 mb-2">
                                사업장 이메일
                            </label>
                            <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $vendor->contact_email) }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('contact_email') border-red-500 bg-red-50 @enderror">
                            @error('contact_email')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 mb-2">
                                상태 <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('status') border-red-500 bg-red-50 @enderror"
                                    required>
                                <option value="pending" {{ old('status', $vendor->status->value ?? $vendor->status) === 'pending' ? 'selected' : '' }}>승인 대기</option>
                                <option value="approved" {{ old('status', $vendor->status->value ?? $vendor->status) === 'approved' ? 'selected' : '' }}>승인됨</option>
                                <option value="rejected" {{ old('status', $vendor->status->value ?? $vendor->status) === 'rejected' ? 'selected' : '' }}>거절됨</option>
                                <option value="suspended" {{ old('status', $vendor->status->value ?? $vendor->status) === 'suspended' ? 'selected' : '' }}>정지됨</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                                소개/설명
                            </label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('description') border-red-500 bg-red-50 @enderror"
                                      placeholder="사업장에 대한 간단한 소개">{{ old('description', $vendor->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 버튼 -->
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-200/60 flex items-center justify-between">
                    <div>
                        @if($vendor->products()->count() === 0)
                            <button type="button" onclick="confirmDelete()"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-50 transition-colors font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                제공자 삭제
                            </button>
                        @else
                            <span class="text-sm text-slate-500">등록된 상품이 있어 삭제할 수 없습니다</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.vendors.show', $vendor) }}"
                           class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                            취소
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 font-medium">
                            저장하기
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">제공자 삭제</h3>
                    <p class="text-sm text-slate-500">이 작업은 되돌릴 수 없습니다.</p>
                </div>
            </div>
            <p class="text-slate-600 mb-6">정말 이 제공자를 삭제하시겠습니까?</p>
            <form method="POST" action="{{ route('admin.vendors.destroy', $vendor) }}">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                        취소
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all shadow-lg shadow-red-500/25 font-medium">
                        삭제
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
</x-layouts.admin>
