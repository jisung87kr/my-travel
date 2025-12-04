<x-layouts.app :title="'Travel Products'">
    <!-- Vue Product Listing Container -->
    <div id="product-listing-app" class="min-h-screen bg-gray-50"></div>

    @push('scripts')
    <script type="module">
        import { createApp } from 'vue';
        import { createPinia } from 'pinia';
        import SearchFilters from '../../js/components/search/SearchFilters.vue';
        import ProductGrid from '../../js/components/product/ProductGrid.vue';

        // Sample product data
        const sampleProducts = [
            {
                id: 1,
                title: 'Seoul City Tour with N Seoul Tower',
                category: 'Tour',
                region: 'Seoul',
                price: 85000,
                rating: 4.8,
                reviewCount: 234,
                duration: '4 hours',
                image: 'https://images.unsplash.com/photo-1537211261171-f2b798896dc8?w=800&h=600&fit=crop',
                url: '#',
                badge: { type: 'hot', text: 'Popular' },
                isWishlisted: false
            },
            {
                id: 2,
                title: 'Jeju Island Full Day Tour',
                category: 'Tour',
                region: 'Jeju',
                price: 120000,
                rating: 4.9,
                reviewCount: 456,
                duration: '8 hours',
                image: 'https://images.unsplash.com/photo-1599873242624-632c8f4f8d9c?w=800&h=600&fit=crop',
                url: '#',
                badge: { type: 'featured', text: 'Featured' },
                isWishlisted: true
            },
            {
                id: 3,
                title: 'Busan Coastal Hiking Experience',
                category: 'Activity',
                region: 'Busan',
                price: 65000,
                rating: 4.6,
                reviewCount: 128,
                duration: '5 hours',
                image: 'https://images.unsplash.com/photo-1604649014502-fb8c53def0b8?w=800&h=600&fit=crop',
                url: '#',
                badge: null,
                isWishlisted: false
            },
            {
                id: 4,
                title: 'Traditional Korean Cooking Class',
                category: 'Activity',
                region: 'Seoul',
                price: 75000,
                rating: 4.7,
                reviewCount: 189,
                duration: '3 hours',
                image: 'https://images.unsplash.com/photo-1582878826629-29b7ad1cdc43?w=800&h=600&fit=crop',
                url: '#',
                badge: { type: 'new', text: 'New' },
                isWishlisted: false
            },
            {
                id: 5,
                title: 'Hanbok Rental and Gyeongbokgung Palace',
                category: 'Activity',
                region: 'Seoul',
                price: 45000,
                rating: 4.9,
                reviewCount: 567,
                duration: '4 hours',
                image: 'https://images.unsplash.com/photo-1548623722-d39b3f22ad8c?w=800&h=600&fit=crop',
                url: '#',
                badge: { type: 'hot', text: 'Bestseller' },
                isWishlisted: false
            },
            {
                id: 6,
                title: 'Seoraksan Mountain Hiking Tour',
                category: 'Tour',
                region: 'Gangwon',
                price: 95000,
                rating: 4.8,
                reviewCount: 312,
                duration: '7 hours',
                image: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                url: '#',
                badge: null,
                isWishlisted: false
            },
            {
                id: 7,
                title: 'DMZ Tour from Seoul',
                category: 'Tour',
                region: 'Gyeonggi',
                price: 110000,
                rating: 4.7,
                reviewCount: 423,
                duration: '6 hours',
                image: 'https://images.unsplash.com/photo-1541728472741-03e45a58cf88?w=800&h=600&fit=crop',
                url: '#',
                badge: null,
                isWishlisted: true
            },
            {
                id: 8,
                title: 'Busan Night View Tour',
                category: 'Tour',
                region: 'Busan',
                price: 55000,
                rating: 4.5,
                reviewCount: 201,
                duration: '3 hours',
                image: 'https://images.unsplash.com/photo-1523568129082-c0032e49e2e0?w=800&h=600&fit=crop',
                url: '#',
                badge: null,
                isWishlisted: false
            },
            {
                id: 9,
                title: 'Korean Temple Stay Experience',
                category: 'Accommodation',
                region: 'Gangwon',
                price: 80000,
                rating: 4.9,
                reviewCount: 145,
                duration: '1 day',
                image: 'https://images.unsplash.com/photo-1545569341-9eb8b30979d9?w=800&h=600&fit=crop',
                url: '#',
                badge: { type: 'featured', text: 'Unique' },
                isWishlisted: false
            },
            {
                id: 10,
                title: 'Nami Island and Petite France Day Trip',
                category: 'Tour',
                region: 'Gyeonggi',
                price: 89000,
                rating: 4.8,
                reviewCount: 678,
                duration: '8 hours',
                image: 'https://images.unsplash.com/photo-1511739001486-6bfe10ce785f?w=800&h=600&fit=crop',
                url: '#',
                badge: { type: 'hot', text: 'Popular' },
                isWishlisted: false
            },
            {
                id: 11,
                title: 'Jeonju Hanok Village Walking Tour',
                category: 'Tour',
                region: 'Jeju',
                price: 50000,
                rating: 4.6,
                reviewCount: 267,
                duration: '4 hours',
                image: 'https://images.unsplash.com/photo-1528127269322-539801943592?w=800&h=600&fit=crop',
                url: '#',
                badge: null,
                isWishlisted: false
            },
            {
                id: 12,
                title: 'Korean BBQ and Street Food Tour',
                category: 'Activity',
                region: 'Seoul',
                price: 70000,
                rating: 4.9,
                reviewCount: 892,
                duration: '3 hours',
                image: 'https://images.unsplash.com/photo-1582878826629-29b7ad1cdc43?w=800&h=600&fit=crop',
                url: '#',
                badge: { type: 'hot', text: 'Bestseller' },
                isWishlisted: true
            },
        ];

        // Sample filter options
        const categories = [
            { value: '', label: 'All Categories' },
            { value: 'tour', label: 'Tours' },
            { value: 'activity', label: 'Activities' },
            { value: 'accommodation', label: 'Accommodation' },
            { value: 'transportation', label: 'Transportation' },
        ];

        const regions = [
            { value: '', label: 'All Regions' },
            { value: 'Seoul', label: 'Seoul' },
            { value: 'Busan', label: 'Busan' },
            { value: 'Jeju', label: 'Jeju' },
            { value: 'Gangwon', label: 'Gangwon' },
            { value: 'Gyeonggi', label: 'Gyeonggi' },
        ];

        // Create Vue app
        const app = createApp({
            components: {
                SearchFilters,
                ProductGrid
            },
            data() {
                return {
                    allProducts: [...sampleProducts],
                    filteredProducts: [],
                    displayedProducts: [],
                    filters: {
                        category: '',
                        region: '',
                        date: '',
                        minPrice: null,
                        maxPrice: null,
                        guests: 1,
                        sort: 'recommended'
                    },
                    loading: false,
                    page: 1,
                    perPage: 8,
                    hasMore: true,
                    categories,
                    regions
                };
            },
            computed: {
                resultsCount() {
                    return this.filteredProducts.length;
                }
            },
            methods: {
                handleFilterChange(newFilters) {
                    this.filters = { ...newFilters };
                    this.applyFilters();
                },
                applyFilters() {
                    let products = [...this.allProducts];

                    // Filter by category
                    if (this.filters.category) {
                        products = products.filter(p =>
                            p.category.toLowerCase() === this.filters.category.toLowerCase()
                        );
                    }

                    // Filter by region
                    if (this.filters.region) {
                        products = products.filter(p =>
                            p.region.toLowerCase() === this.filters.region.toLowerCase()
                        );
                    }

                    // Filter by price range
                    if (this.filters.minPrice !== null && this.filters.minPrice !== '') {
                        products = products.filter(p => p.price >= this.filters.minPrice);
                    }
                    if (this.filters.maxPrice !== null && this.filters.maxPrice !== '') {
                        products = products.filter(p => p.price <= this.filters.maxPrice);
                    }

                    // Sort
                    switch (this.filters.sort) {
                        case 'price_low':
                            products.sort((a, b) => a.price - b.price);
                            break;
                        case 'price_high':
                            products.sort((a, b) => b.price - a.price);
                            break;
                        case 'rating':
                            products.sort((a, b) => b.rating - a.rating);
                            break;
                        case 'popular':
                            products.sort((a, b) => b.reviewCount - a.reviewCount);
                            break;
                        default: // recommended
                            // Keep original order
                            break;
                    }

                    this.filteredProducts = products;
                    this.page = 1;
                    this.loadProducts();
                },
                loadProducts() {
                    const start = 0;
                    const end = this.page * this.perPage;
                    this.displayedProducts = this.filteredProducts.slice(start, end);
                    this.hasMore = end < this.filteredProducts.length;
                },
                loadMore() {
                    if (this.loading || !this.hasMore) return;

                    this.loading = true;

                    // Simulate API delay
                    setTimeout(() => {
                        this.page++;
                        const start = (this.page - 1) * this.perPage;
                        const end = this.page * this.perPage;
                        const newProducts = this.filteredProducts.slice(start, end);

                        this.displayedProducts = [
                            ...this.displayedProducts,
                            ...newProducts
                        ];

                        this.hasMore = end < this.filteredProducts.length;
                        this.loading = false;
                    }, 800);
                },
                toggleWishlist(productId) {
                    const product = this.allProducts.find(p => p.id === productId);
                    if (product) {
                        product.isWishlisted = !product.isWishlisted;
                    }
                }
            },
            mounted() {
                // Initial load
                this.applyFilters();
            },
            template: `
                <div>
                    <SearchFilters
                        :categories="categories"
                        :regions="regions"
                        :initial-filters="filters"
                        :results-count="resultsCount"
                        @filter-change="handleFilterChange"
                    />
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        <ProductGrid
                            :products="displayedProducts"
                            :loading="loading"
                            :has-more="hasMore"
                            @load-more="loadMore"
                            @toggle-wishlist="toggleWishlist"
                        />
                    </div>
                </div>
            `
        });

        const pinia = createPinia();
        app.use(pinia);
        app.mount('#product-listing-app');
    </script>
    @endpush
</x-layouts.app>
