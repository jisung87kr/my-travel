<x-layouts.app :title="__('nav.products')">
    <div class="min-h-screen bg-white" x-data="productFilters()">

        <!-- Sticky Header & Search -->
        <div class="sticky top-[-20px] z-40 bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 py-4">

                    <!-- Top Row: Search Bar -->
                    <x-search-widget :regions="$regions" :compact="true" />

                    <!-- Bottom Row: Categories Filter (Horizontal Scroll) -->
                    <div class="w-full flex items-center justify-center gap-4 overflow-x-auto no-scrollbar pb-2 mask-linear-fade relative mt-4">
                        <button @click="filters.category = ''; filters.region = ''; applyFilters()"
                                class="flex flex-col items-center gap-2 min-w-[64px] cursor-pointer group transition-all"
                                :class="(!filters.category && !filters.region) ? 'opacity-100 border-b-2 border-slate-900 pb-2' : 'opacity-60 hover:opacity-100 hover:bg-gray-50 pb-2.5'">
                            <div class="w-6 h-6 text-slate-900">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                            </div>
                            <span class="text-xs font-bold whitespace-nowrap">{{ __('product.all') }}</span>
                        </button>

                        @foreach($categories as $category)
                        <button @click="filters.category = '{{ $category['value'] }}'; applyFilters()"
                                class="flex flex-col items-center gap-2 min-w-[64px] cursor-pointer group transition-all"
                                :class="filters.category === '{{ $category['value'] }}' ? 'opacity-100 border-b-2 border-slate-900 pb-2' : 'opacity-60 hover:opacity-100 hover:bg-gray-50 pb-2.5'">
                            <div class="w-6 h-6 text-slate-900">
                                <!-- Placeholder icons based on generic category logic -->
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" /></svg>
                            </div>
                            <span class="text-xs font-bold whitespace-nowrap">{{ $category['label'] }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Active Filters Summary -->
            <div x-show="activeFilterCount > 0" class="flex flex-wrap items-center gap-2 mb-6" x-transition>
                <template x-if="filters.keyword">
                    <button @click="filters.keyword = ''; applyFilters()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-gray-200 bg-white hover:border-gray-300 text-sm font-semibold transition-colors">
                        <span x-text="filters.keyword"></span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </template>
                <button @click="resetFilters()" class="text-sm font-medium text-slate-500 hover:text-slate-800 hover:underline">
                    {{ __('product.clear_all') }}
                </button>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-6 gap-y-10">
                    @foreach($products as $product)
                        <x-product.card :product="$product" :showWishlist="true" />
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="mt-16 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="py-20 text-center">
                    <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                        <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('product.no_results') }}</h3>
                    <p class="text-slate-500 mb-8 max-w-sm mx-auto">{{ __('product.try_different') }}</p>
                    <button @click="resetFilters()" class="inline-flex items-center px-6 py-3 rounded-lg border border-slate-900 text-slate-900 font-semibold hover:bg-slate-50 transition-colors">
                        {{ __('product.reset_filters') }}
                    </button>
                </div>
            @endif
        </main>

        <!-- Floating Map Toggle (Visual Only for now) -->
{{--        <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-30">--}}
{{--            <button class="bg-slate-900 text-white px-5 py-3 rounded-full shadow-xl hover:scale-105 active:scale-95 transition-transform flex items-center gap-2 font-semibold">--}}
{{--                <span>{{ __('product.show_map') }}</span>--}}
{{--                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.19-.69.19-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.07 3 6.533v12.708c0 .835.88 1.38 1.628 1.006l3.869-1.934c.317-.19.69-.19 1.006 0l4.994 2.495z" /></svg>--}}
{{--            </button>--}}
{{--        </div>--}}

    </div>

    @push('scripts')
    <script>
        function productFilters() {
            return {
                openGuestPicker: false,
                filters: {
                    keyword: @json(request('keyword', '')),
                    date: @json(request('date', '')),
                    adults: parseInt(@json(request('adults', '1'))) || 1,
                    children: parseInt(@json(request('children', '0'))) || 0,
                    region: @json(request('region', '')),
                    category: @json(request('category', '')),
                    sort: @json(request('sort', 'newest'))
                },
                get activeFilterCount() {
                    let count = 0;
                    if (this.filters.keyword) count++;
                    if (this.filters.date) count++;
                    if (this.filters.adults > 1 || this.filters.children > 0) count++;
                    if (this.filters.region) count++;
                    if (this.filters.category) count++;
                    return count;
                },
                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const date = new Date(dateStr);
                    return date.toLocaleDateString('{{ app()->getLocale() }}', { month: 'short', day: 'numeric' });
                },
                applyFilters() {
                    const params = new URLSearchParams();
                    if (this.filters.keyword) params.set('keyword', this.filters.keyword);
                    if (this.filters.date) params.set('date', this.filters.date);
                    if (this.filters.adults > 1) params.set('adults', this.filters.adults);
                    if (this.filters.children > 0) params.set('children', this.filters.children);
                    if (this.filters.region) params.set('region', this.filters.region);
                    if (this.filters.category) params.set('category', this.filters.category);
                    if (this.filters.sort && this.filters.sort !== 'newest') params.set('sort', this.filters.sort);

                    const baseUrl = '{{ route('products.index', ['locale' => app()->getLocale()]) }}';
                    const queryString = params.toString();
                    window.location.href = queryString ? `${baseUrl}?${queryString}` : baseUrl;
                },
                resetFilters() {
                    window.location.href = '{{ route('products.index', ['locale' => app()->getLocale()]) }}';
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .mask-linear-fade { -webkit-mask-image: linear-gradient(to right, black 90%, transparent 100%); mask-image: linear-gradient(to right, black 90%, transparent 100%); }
    </style>
    @endpush
</x-layouts.app>
