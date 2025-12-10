<x-layouts.vendor>
    <x-slot name="header">새 상품 등록</x-slot>

    <div class="max-w-5xl">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('vendor.products.index') }}" class="text-slate-500 hover:text-violet-600 transition-colors">상품 관리</a>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-slate-900 font-medium">새 상품 등록</span>
            </nav>
        </div>

        <x-product.form
            :action="route('vendor.products.store')"
            :regions="$regions"
            :types="$types"
            :cancel-route="route('vendor.products.index')"
            color-scheme="violet"
        />
    </div>
</x-layouts.vendor>
