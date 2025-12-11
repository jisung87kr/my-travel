<x-layouts.admin>
    <x-slot name="header">상품 등록</x-slot>

    <div class="max-w-5xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('admin.products.index') }}" class="text-slate-500 hover:text-blue-600 transition-colors">상품 관리</a>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-slate-900 font-medium">새 상품 등록</span>
            </nav>
        </div>

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @include('admin.products._form', [
                'vendors' => $vendors,
                'types' => $types,
                'regions' => $regions,
                'bookingTypes' => $bookingTypes,
            ])

            <!-- 버튼 -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.products.index') }}"
                   class="px-6 py-3 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:border-slate-300 font-medium transition-all">
                    취소
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-medium shadow-lg shadow-blue-500/30 transition-all">
                    상품 등록
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>