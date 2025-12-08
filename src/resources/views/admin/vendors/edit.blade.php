<x-layouts.admin>
    <x-slot name="header">제공자 수정 - {{ $vendor->company_name }}</x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.vendors.update', $vendor) }}">
                @csrf
                @method('PUT')

                <!-- 담당자 정보 -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">담당자 정보</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                이름 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $vendor->user->name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                이메일 <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $vendor->user->email) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                전화번호
                            </label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $vendor->user->phone) }}"
                                   placeholder="010-1234-5678"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                비밀번호
                            </label>
                            <input type="password" id="password" name="password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror"
                                   placeholder="변경 시에만 입력">
                            <p class="mt-1 text-xs text-gray-500">비워두면 기존 비밀번호가 유지됩니다</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 사업자 정보 -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">사업자 정보</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                                회사명/상호 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $vendor->company_name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('company_name') border-red-500 @enderror"
                                   required>
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="business_number" class="block text-sm font-medium text-gray-700 mb-1">
                                사업자등록번호
                            </label>
                            <input type="text" id="business_number" name="business_number" value="{{ old('business_number', $vendor->business_number) }}"
                                   placeholder="123-45-67890"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('business_number') border-red-500 @enderror">
                            @error('business_number')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                사업장 연락처
                            </label>
                            <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $vendor->contact_phone) }}"
                                   placeholder="02-1234-5678"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('contact_phone') border-red-500 @enderror">
                            @error('contact_phone')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">
                                사업장 이메일
                            </label>
                            <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $vendor->contact_email) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('contact_email') border-red-500 @enderror">
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                상태 <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror"
                                    required>
                                <option value="pending" {{ old('status', $vendor->status->value ?? $vendor->status) === 'pending' ? 'selected' : '' }}>승인 대기</option>
                                <option value="approved" {{ old('status', $vendor->status->value ?? $vendor->status) === 'approved' ? 'selected' : '' }}>승인됨</option>
                                <option value="rejected" {{ old('status', $vendor->status->value ?? $vendor->status) === 'rejected' ? 'selected' : '' }}>거절됨</option>
                                <option value="suspended" {{ old('status', $vendor->status->value ?? $vendor->status) === 'suspended' ? 'selected' : '' }}>정지됨</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                소개/설명
                            </label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror"
                                      placeholder="사업장에 대한 간단한 소개">{{ old('description', $vendor->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 버튼 -->
                <div class="flex items-center justify-between">
                    <div>
                        @if($vendor->products()->count() === 0)
                            <button type="button" onclick="confirmDelete()"
                                    class="px-4 py-2 text-red-600 hover:text-red-800">
                                제공자 삭제
                            </button>
                        @else
                            <span class="text-sm text-gray-500">등록된 상품이 있어 삭제할 수 없습니다</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.vendors.show', $vendor) }}"
                           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            취소
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            저장
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">제공자 삭제</h3>
            <p class="text-gray-600 mb-6">정말 이 제공자를 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.</p>
            <form method="POST" action="{{ route('admin.vendors.destroy', $vendor) }}">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                        취소
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
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
