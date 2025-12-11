@props(['user' => null, 'roles'])

@php
    $isEdit = isset($user) && $user->exists;
    $roleNames = [
        'admin' => '관리자',
        'vendor' => '제공자',
        'guide' => '가이드',
        'traveler' => '여행자',
    ];
@endphp

<div class="space-y-6">
    <!-- 이름 -->
    <div>
        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
            이름 <span class="text-red-500">*</span>
        </label>
        <input type="text" id="name" name="name" value="{{ old('name', $user?->name) }}"
               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('name') border-red-500 bg-red-50 @enderror"
               placeholder="사용자 이름을 입력하세요"
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
        <input type="email" id="email" name="email" value="{{ old('email', $user?->email) }}"
               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('email') border-red-500 bg-red-50 @enderror"
               placeholder="example@email.com"
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
        <input type="text" id="phone" name="phone" value="{{ old('phone', $user?->phone) }}"
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
            비밀번호 @if(!$isEdit)<span class="text-red-500">*</span>@endif
        </label>
        <input type="password" id="password" name="password"
               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors @error('password') border-red-500 bg-red-50 @enderror"
               placeholder="{{ $isEdit ? '새 비밀번호를 입력하세요' : '최소 8자 이상' }}"
               {{ $isEdit ? '' : 'required' }}>
        <p class="mt-2 text-xs text-slate-500">
            @if($isEdit)
                변경하려면 새 비밀번호를 입력하세요. 비워두면 기존 비밀번호가 유지됩니다.
            @else
                최소 8자 이상의 비밀번호를 입력하세요.
            @endif
        </p>
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
            @if(!$isEdit)
                <option value="">역할 선택</option>
            @endif
            @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ old('role', $user?->roles->first()?->name) === $role->name ? 'selected' : '' }}>
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
               {{ old('is_active', $user?->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-sm font-medium text-slate-700">
            {{ $isEdit ? '활성 상태' : '활성화 상태로 등록' }}
        </label>
    </div>

    <!-- 사용자 정보 (수정 시에만 표시) -->
    @if($isEdit)
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
    @endif
</div>