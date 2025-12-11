<x-layouts.admin>
    <x-slot name="header">사용자 등록</x-slot>

    <!-- Breadcrumb -->
    <nav class="mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('admin.users.index') }}" class="text-slate-500 hover:text-blue-600 transition-colors">사용자 관리</a>
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-800 font-medium">새 사용자 등록</span>
    </nav>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <!-- Form Header -->
            <div class="px-6 py-5 border-b border-slate-200/60 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">새 사용자 정보</h3>
                        <p class="text-sm text-slate-500">새로운 사용자 계정을 생성합니다.</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
                @csrf

                @include('admin.users._form', ['roles' => $roles])

                <!-- 버튼 -->
                <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}"
                       class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                        취소
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 font-medium">
                        등록하기
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>