import { createApp } from 'vue';
import { createPinia } from 'pinia';
import SearchFilters from '../components/search/SearchFilters.vue';
import ProductGrid from '../components/product/ProductGrid.vue';

// Get initial data from window object (passed from Blade template)
const initialData = window.__PRODUCTS_PAGE_DATA__ || {};

// Create Vue app
const app = createApp({
    components: {
        SearchFilters,
        ProductGrid
    },
    data() {
        return {
            allProducts: initialData.products || [],
            filteredProducts: [],
            displayedProducts: [],
            filters: {
                keyword: initialData.filters?.keyword || '',
                category: initialData.filters?.type || '',
                region: initialData.filters?.region || '',
                date: '',
                minPrice: initialData.filters?.minPrice || null,
                maxPrice: initialData.filters?.maxPrice || null,
                guests: 1,
                sort: initialData.filters?.sort || 'recommended'
            },
            loading: false,
            page: 1,
            perPage: 12,
            hasMore: true,
            categories: initialData.categories || [],
            regions: initialData.regions || [],
            locale: initialData.locale || 'ko'
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

            // Filter by keyword
            if (this.filters.keyword) {
                const keyword = this.filters.keyword.toLowerCase();
                products = products.filter(p =>
                    p.title.toLowerCase().includes(keyword) ||
                    (p.description && p.description.toLowerCase().includes(keyword))
                );
            }

            // Filter by category/type
            if (this.filters.category) {
                products = products.filter(p =>
                    p.type_value && p.type_value.toLowerCase() === this.filters.category.toLowerCase()
                );
            }

            // Filter by region
            if (this.filters.region) {
                products = products.filter(p =>
                    p.region_value && p.region_value.toLowerCase() === this.filters.region.toLowerCase()
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
                default: // recommended - newest
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
            console.log(this.displayedProducts);
        },
        loadMore() {
            if (this.loading || !this.hasMore) return;

            this.loading = true;

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
            }, 300);
        },
        toggleWishlist(productId) {
            const product = this.allProducts.find(p => p.id === productId);
            if (product) {
                product.isWishlisted = !product.isWishlisted;
                // TODO: API call to toggle wishlist
            }
        }
    },
    mounted() {
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
                    :locale="locale"
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
