<x-layouts.app :title="__('product.list_title')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                    <h2 class="font-semibold text-gray-900 mb-4">{{ __('product.filter') }}</h2>

                    <form id="filter-form" method="GET">
                        <!-- Search -->
                        <div class="mb-6">
                            <input type="text"
                                   name="keyword"
                                   value="{{ request('keyword') }}"
                                   placeholder="{{ __('home.search_placeholder') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Region -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('product.region') }}</h3>
                            <div class="space-y-2">
                                @foreach($regions as $region)
                                    <label class="flex items-center">
                                        <input type="radio"
                                               name="region"
                                               value="{{ $region['value'] }}"
                                               {{ request('region') === $region['value'] ? 'checked' : '' }}
                                               class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">{{ $region['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('product.type') }}</h3>
                            <div class="space-y-2">
                                @foreach($types as $type)
                                    <label class="flex items-center">
                                        <input type="radio"
                                               name="type"
                                               value="{{ $type['value'] }}"
                                               {{ request('type') === $type['value'] ? 'checked' : '' }}
                                               class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">{{ $type['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('product.price_range') }}</h3>
                            <div class="flex gap-2">
                                <input type="number"
                                       name="min_price"
                                       value="{{ request('min_price') }}"
                                       placeholder="Min"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <input type="number"
                                       name="max_price"
                                       value="{{ request('max_price') }}"
                                       placeholder="Max"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            {{ __('product.filter') }}
                        </button>

                        @if(request()->hasAny(['keyword', 'region', 'type', 'min_price', 'max_price']))
                            <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                               class="block mt-2 text-center text-sm text-gray-600 hover:text-gray-900">
                                Clear filters
                            </a>
                        @endif
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Sort -->
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600">
                        {{ $products->total() }} {{ __('nav.products') }}
                    </p>
                    <select name="sort"
                            onchange="updateSort(this.value)"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>{{ __('product.sort_newest') }}</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>{{ __('product.sort_popular') }}</option>
                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>{{ __('product.sort_rating') }}</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>{{ __('product.sort_price_low') }}</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>{{ __('product.sort_price_high') }}</option>
                    </select>
                </div>

                @if($products->isEmpty())
                    <div class="text-center py-16">
                        <svg class="mx-auto w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">{{ __('product.no_results') }}</h3>
                        <p class="mt-2 text-gray-600">{{ __('product.try_different') }}</p>
                    </div>
                @else
                    <!-- Product Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateSort(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', value);
            window.location.href = url.toString();
        }
    </script>
    @endpush
</x-layouts.app>
