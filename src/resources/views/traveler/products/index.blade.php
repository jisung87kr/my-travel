<x-layouts.app :title="'Travel Products'">
    <!-- Vue Product Listing Container -->
    <div id="product-listing-app" class="min-h-screen bg-gray-50">
        <!-- Loading placeholder -->
        <div class="animate-pulse p-8">
            <div class="max-w-7xl mx-auto">
                <div class="h-16 bg-gray-200 rounded mb-8"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @for ($i = 0; $i < 8; $i++)
                        <div class="bg-gray-200 rounded-lg h-72"></div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Pass data from Laravel to Vue
        window.__PRODUCTS_PAGE_DATA__ = {
            products: @json($products->items()),
            filters: {
                keyword: @json(request('keyword', '')),
                type: @json(request('type', '')),
                region: @json(request('region', '')),
                minPrice: @json(request('min_price')),
                maxPrice: @json(request('max_price')),
                sort: @json(request('sort', 'newest'))
            },
            categories: @json($types),
            regions: @json($regions),
            locale: @json(app()->getLocale())
        };
    </script>
    @vite('resources/js/pages/products-index.js')
    @endpush
</x-layouts.app>
