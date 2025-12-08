<x-layouts.guest title="비밀번호 찾기">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="mx-auto w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-100 to-rose-100 flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">비밀번호를 잊으셨나요?</h1>
        <p class="mt-2 text-sm text-gray-600">이메일 주소를 입력하시면 비밀번호 재설정 링크를 보내드립니다</p>
    </div>

    <!-- Status Message -->
    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-green-800">이메일이 전송되었습니다</p>
                    <p class="text-sm text-green-700">{{ session('status') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="flex items-center gap-2 text-sm text-red-700">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Input -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">이메일</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autocomplete="email"
                       placeholder="your@email.com"
                       class="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors">
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full py-3 px-4 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-pink-500/50">
            재설정 링크 보내기
        </button>
    </form>

    <!-- Back to Login -->
    <div class="mt-8 text-center">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            로그인으로 돌아가기
        </a>
    </div>
</x-layouts.guest>
