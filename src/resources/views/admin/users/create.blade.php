<x-layouts.admin>
    <x-slot name="header">사용자 등록</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="space-y-6">
                    <!-- 이름 -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            이름 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 이메일 -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            이메일 <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 전화번호 -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            전화번호
                        </label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                               placeholder="010-1234-5678"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 비밀번호 -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            비밀번호 <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror"
                               required>
                        <p class="mt-1 text-xs text-gray-500">최소 8자 이상</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 역할 -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                            역할 <span class="text-red-500">*</span>
                        </label>
                        <select id="role" name="role"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('role') border-red-500 @enderror"
                                required>
                            <option value="">역할 선택</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                    {{ $role->name === 'admin' ? '관리자' : ($role->name === 'vendor' ? '제공자' : ($role->name === 'guide' ? '가이드' : '여행자')) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 활성 상태 -->
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm font-medium text-gray-700">
                            활성화 상태로 등록
                        </label>
                    </div>
                </div>

                <!-- 버튼 -->
                <div class="mt-8 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        취소
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        등록
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
