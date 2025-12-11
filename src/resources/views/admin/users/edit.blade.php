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

                @include('admin.users._form', ['user' => $user, 'roles' => $roles])

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