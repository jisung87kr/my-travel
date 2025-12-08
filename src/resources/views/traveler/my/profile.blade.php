<x-traveler.my.layout :title="__('nav.profile')">
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-pink-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">기본 정보</h2>
                        <p class="text-sm text-gray-500">개인 정보를 수정할 수 있습니다</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('my.profile.update', ['locale' => app()->getLocale()]) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                            {{ __('booking.contact_name') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors">
                        </div>
                        @error('name')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email (readonly) -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                            {{ __('booking.contact_email') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <input type="email"
                                   id="email"
                                   value="{{ $user->email }}"
                                   readonly
                                   class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-gray-500 bg-gray-50 cursor-not-allowed">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">이메일은 변경할 수 없습니다</p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                            {{ __('booking.contact_phone') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                            </div>
                            <input type="tel"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="010-0000-0000"
                                   class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors">
                        </div>
                        @error('phone')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Preferred Language -->
                    <div>
                        <label for="preferred_language" class="block text-sm font-medium text-gray-700 mb-1.5">
                            선호 언어
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802" />
                                </svg>
                            </div>
                            <select id="preferred_language"
                                    name="preferred_language"
                                    class="block w-full pl-11 pr-10 py-3 border border-gray-200 rounded-xl text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors appearance-none cursor-pointer">
                                <option value="ko" {{ $user->preferred_language->value === 'ko' ? 'selected' : '' }}>한국어</option>
                                <option value="en" {{ $user->preferred_language->value === 'en' ? 'selected' : '' }}>English</option>
                                <option value="zh" {{ $user->preferred_language->value === 'zh' ? 'selected' : '' }}>中文</option>
                                <option value="ja" {{ $user->preferred_language->value === 'ja' ? 'selected' : '' }}>日本語</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                                class="w-full py-3.5 px-4 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-pink-500/50 cursor-pointer">
                            변경사항 저장
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Account Status Card -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900">계정 상태</h3>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">가입일</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('Y년 m월 d일') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-sm text-gray-500">회원 등급</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-pink-500 to-rose-500 text-white">
                            일반 회원
                        </span>
                    </div>
                    @if($user->no_show_count > 0)
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm text-gray-500">노쇼 횟수</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $user->no_show_count >= 2 ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ $user->no_show_count }}/3
                            </span>
                        </div>
                        @if($user->no_show_count >= 2)
                            <div class="p-3 rounded-xl bg-red-50 border border-red-200">
                                <p class="text-xs text-red-700">
                                    <span class="font-medium">주의:</span> 노쇼 3회 누적 시 예약이 제한될 수 있습니다.
                                </p>
                            </div>
                        @endif
                    @else
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm text-gray-500">노쇼 횟수</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                0회
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Security Card -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900">보안</h3>
                </div>

                <a href="{{ route('password.request') }}"
                   class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">비밀번호 변경</span>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-red-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-red-600">위험 영역</h3>
                </div>

                <p class="text-sm text-gray-500 mb-4">
                    계정을 삭제하면 모든 데이터가 영구적으로 삭제되며 복구할 수 없습니다.
                </p>

                <button type="button"
                        onclick="confirmDeleteAccount()"
                        class="w-full py-2.5 px-4 border border-red-200 text-red-600 font-medium rounded-xl hover:bg-red-50 transition-colors cursor-pointer">
                    계정 삭제
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDeleteAccount() {
            if (confirm('정말로 계정을 삭제하시겠습니까?\n이 작업은 취소할 수 없습니다.')) {
                // TODO: Implement account deletion
                alert('계정 삭제 기능은 준비 중입니다.');
            }
        }
    </script>
    @endpush
</x-traveler.my.layout>
