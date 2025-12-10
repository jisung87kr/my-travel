<x-layouts.vendor>
    <x-slot name="header">상품 수정</x-slot>

    <div class="max-w-5xl">
        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center justify-between">
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('vendor.products.index') }}" class="text-slate-500 hover:text-violet-600 transition-colors">상품 관리</a>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-slate-900 font-medium">상품 수정</span>
            </nav>
            @php
                $colorClasses = [
                    'gray' => 'bg-slate-100 text-slate-700',
                    'yellow' => 'bg-amber-100 text-amber-700',
                    'green' => 'bg-emerald-100 text-emerald-700',
                    'red' => 'bg-red-100 text-red-700',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full {{ $colorClasses[$product->status->color()] ?? 'bg-slate-100 text-slate-600' }}">
                {{ $product->status->label() }}
            </span>
        </div>

        <x-product.form
            :action="route('vendor.products.update', $product)"
            method="PUT"
            :product="$product"
            :regions="$regions"
            :types="$types"
            :cancel-route="route('vendor.products.index')"
            submit-label="저장"
            color-scheme="violet"
        />
    </div>

    @push('scripts')
    <script>
        function deleteImage(imageId) {
            if (confirm('이미지를 삭제하시겠습니까?')) {
                api.vendor.products.deleteImage({{ $product->id }}, imageId)
                    .then(() => location.reload())
                    .catch(error => console.error('Image delete error:', error));
            }
        }
    </script>
    @endpush
</x-layouts.vendor>
