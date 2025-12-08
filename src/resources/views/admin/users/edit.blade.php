<x-layouts.admin>
    <x-slot name="header">사용자 수정 - {{ $user->name }}</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- 이름 -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            이름 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
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
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
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
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                               placeholder="010-1234-5678"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 비밀번호 -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            비밀번호
                        </label>
                        <input type="password" id="password" name="password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">변경하려면 새 비밀번호를 입력하세요. 비워두면 기존 비밀번호가 유지됩니다.</p>
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
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
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
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm font-medium text-gray-700">
                            활성 상태
                        </label>
                    </div>

                    <!-- 사용자 정보 -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">사용자 정보</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">가입일:</span>
                                <span class="ml-2">{{ $user->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">노쇼 횟수:</span>
                                <span class="ml-2 {{ $user->no_show_count >= 3 ? 'text-red-600 font-medium' : '' }}">{{ $user->no_show_count }}회</span>
                            </div>
                            <div>
                                <span class="text-gray-500">차단 상태:</span>
                                <span class="ml-2">{{ $user->is_blocked ? '차단됨' : '정상' }}</span>
                            </div>
                            @if($user->provider)
                            <div>
                                <span class="text-gray-500">소셜 로그인:</span>
                                <span class="ml-2">{{ ucfirst($user->provider) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 버튼 -->
                <div class="mt-8 flex items-center justify-between">
                    <div>
                        @if(!$user->hasRole('admin'))
                        <button type="button" onclick="confirmDelete()"
                                class="px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50">
                            탈퇴 처리
                        </button>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.users.show', $user) }}"
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

            @if(!$user->hasRole('admin'))
            <form id="delete-form" method="POST" action="{{ route('admin.users.destroy', $user) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete() {
            if (confirm('정말 이 사용자를 탈퇴 처리하시겠습니까? 이 작업은 되돌릴 수 있습니다.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
    @endpush
</x-layouts.admin>
