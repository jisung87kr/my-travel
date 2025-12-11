<x-layouts.admin>
    <x-slot name="header">제공자 등록</x-slot>

    <!-- Breadcrumb -->
    <nav class="mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('admin.vendors.index') }}" class="text-slate-500 hover:text-blue-600 transition-colors">제공자 관리</a>
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-800 font-medium">새 제공자 등록</span>
    </nav>

    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <form method="POST" action="{{ route('admin.vendors.store') }}">
                @csrf

                @include('admin.vendors._form')

                <!-- 버튼 -->
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-200/60 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.vendors.index') }}"
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
