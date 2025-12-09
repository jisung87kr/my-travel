<x-layouts.admin>
    <x-slot name="header">사용자 수정</x-slot>

    <!-- Breadcrumb -->
    <nav class="mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('admin.users.index') }}" class="text-slate-500 hover:text-blue-600 transition-colors">사용자 관리</a>
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('admin.users.show', $user) }}" class="text-slate-500 hover:text-blue-600 transition-colors">{{ $user->name }}</a>
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-800 font-medium">수정</span>
    </nav>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <!-- Form Header -->
            <div class="px-6 py-5 border-b border-slate-200/60 bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-violet-500 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-blue-500/30">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">{{ $user->name }}</h3>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- 이름 -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                            이름 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('name') border-red-500 bg-red-50 @enderror"
                               required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- 이메일 -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                            이메일 <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('email') border-red-500 bg-red-50 @enderror"
                               required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- 전화번호 -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                            전화번호
                        </label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                               placeholder="010-1234-5678"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('phone') border-red-500 bg-red-50 @enderror">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- 비밀번호 -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                            비밀번호
                        </label>
                        <input type="password" id="password" name="password"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('password') border-red-500 bg-red-50 @enderror"
                               placeholder="새 비밀번호를 입력하세요">
                        <p class="mt-2 text-xs text-slate-500">변경하려면 새 비밀번호를 입력하세요. 비워두면 기존 비밀번호가 유지됩니다.</p>
                        @error('password')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- 역할 -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-700 mb-2">
                            역할 <span class="text-red-500">*</span>
                        </label>
                        <select id="role" name="role"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('role') border-red-500 bg-red-50 @enderror"
                                required>
                            @foreach($roles as $role)
                                @php
                                    $roleNames = [
                                        'admin' => '관리자',
                                        'vendor' => '제공자',
                                        'guide' => '가이드',
                                        'traveler' => '여행자',
                                    ];
                                @endphp
                                <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                    {{ $roleNames[$role->name] ?? $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- 활성 상태 -->
                    <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500 focus:ring-2"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm font-medium text-slate-700">
                            활성 상태
                        </label>
                    </div>

                    <!-- 사용자 정보 -->
                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-5 border border-slate-200">
                        <h4 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            사용자 정보
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-3 border border-slate-200/60">
                                <span class="text-xs text-slate-500 block mb-1">가입일</span>
                                <span class="text-sm font-medium text-slate-700">{{ $user->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-slate-200/60">
                                <span class="text-xs text-slate-500 block mb-1">노쇼 횟수</span>
                                <span class="text-sm font-medium {{ $user->no_show_count >= 3 ? 'text-red-600' : 'text-slate-700' }}">{{ $user->no_show_count }}회</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-slate-200/60">
                                <span class="text-xs text-slate-500 block mb-1">차단 상태</span>
                                <span class="text-sm font-medium {{ $user->is_blocked ? 'text-red-600' : 'text-emerald-600' }}">{{ $user->is_blocked ? '차단됨' : '정상' }}</span>
                            </div>
                            @if($user->provider)
                                <div class="bg-white rounded-lg p-3 border border-slate-200/60">
                                    <span class="text-xs text-slate-500 block mb-1">소셜 로그인</span>
                                    <span class="text-sm font-medium text-slate-700">{{ ucfirst($user->provider) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 버튼 -->
                <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-between">
                    <div>
                        @if(!$user->hasRole('admin'))
                            <button type="button" onclick="confirmDelete()"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-50 transition-colors font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                탈퇴 처리
                            </button>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.users.show', $user) }}"
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
