<?php

namespace App\Http\Controllers\Vendor;

use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreProductRequest;
use App\Http\Requests\Vendor\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Region;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'search', 'per_page']);
        $products = $this->productService->getVendorProducts($request->user(), $filters);

        return view('vendor.products.index', compact('products'));
    }

    public function create(): View
    {
        $locale = app()->getLocale();
        $regions = Region::with('translations')
            ->provinces()
            ->active()
            ->ordered()
            ->get()
            ->map(fn ($r) => ['value' => $r->id, 'label' => $r->getShortName($locale)]);
        $types = collect(ProductType::cases())->map(fn ($t) => ['value' => $t->value, 'label' => $t->label()]);
        $categories = $this->getHierarchicalCategories($locale);

        return view('vendor.products.create', compact('regions', 'types', 'categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->create(
            $request->validated(),
            $request->user()
        );

        return redirect()->route('vendor.products.index')
            ->with('success', '상품이 등록되었습니다.');
    }

    public function edit(Product $product): View
    {
        Gate::authorize('update', $product);

        $product->load(['translations', 'prices', 'images', 'schedules', 'categories']);

        $locale = app()->getLocale();
        $regions = Region::with('translations')
            ->provinces()
            ->active()
            ->ordered()
            ->get()
            ->map(fn ($r) => ['value' => $r->id, 'label' => $r->getShortName($locale)]);
        $types = collect(ProductType::cases())->map(fn ($t) => ['value' => $t->value, 'label' => $t->label()]);
        $categories = $this->getHierarchicalCategories($locale);

        $productData = [
            'type' => $product->type->value,
            'region_id' => $product->region_id,
            'duration' => $product->duration,
            'max_persons' => $product->max_persons,
            'booking_type' => $product->booking_type,
            'translations' => $product->translations->keyBy('locale')->map(fn ($t) => [
                'title' => $t->name,
                'short_description' => $t->short_description,
                'description' => $t->description,
                'includes' => $t->included,
                'excludes' => $t->excluded,
            ])->toArray(),
            'prices' => [
                'adult' => $product->prices->where('type', 'adult')->first()?->price ?? 0,
                'child' => $product->prices->where('type', 'child')->first()?->price ?? 0,
            ],
            'images' => $product->images->map(fn ($i) => ['id' => $i->id, 'url' => $i->url])->toArray(),
        ];

        return view('vendor.products.edit', compact('product', 'productData', 'regions', 'types', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        Gate::authorize('update', $product);

        $this->productService->update($product, $request->validated());

        return redirect()->route('vendor.products.index')
            ->with('success', '상품이 수정되었습니다.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        Gate::authorize('delete', $product);

        $this->productService->delete($product);

        return redirect()->route('vendor.products.index')
            ->with('success', '상품이 삭제되었습니다.');
    }

    public function submit(Product $product): RedirectResponse
    {
        Gate::authorize('update', $product);

        $this->productService->submitForReview($product);

        return redirect()->route('vendor.products.index')
            ->with('success', '검토 요청이 완료되었습니다.');
    }

    public function activate(Product $product): RedirectResponse
    {
        Gate::authorize('update', $product);

        $this->productService->activate($product);

        return redirect()->route('vendor.products.index')
            ->with('success', '상품이 활성화되었습니다.');
    }

    public function deactivate(Product $product): RedirectResponse
    {
        Gate::authorize('update', $product);

        $this->productService->deactivate($product);

        return redirect()->route('vendor.products.index')
            ->with('success', '상품이 비활성화되었습니다.');
    }

    /**
     * Get hierarchical categories array for form display
     */
    private function getHierarchicalCategories(string $locale): array
    {
        $mainCategories = ProductCategory::with(['translations', 'children.translations', 'children.children.translations'])
            ->main()
            ->active()
            ->ordered()
            ->get();

        return $mainCategories->map(function ($main) use ($locale) {
            return [
                'id' => $main->id,
                'name' => $main->getName($locale),
                'children' => $main->children->filter(fn ($c) => $c->is_active)->sortBy('sort_order')->map(function ($sub) use ($locale) {
                    return [
                        'id' => $sub->id,
                        'name' => $sub->getName($locale),
                        'children' => $sub->children->filter(fn ($c) => $c->is_active)->sortBy('sort_order')->map(function ($detail) use ($locale) {
                            return [
                                'id' => $detail->id,
                                'name' => $detail->getName($locale),
                            ];
                        })->values()->all(),
                    ];
                })->values()->all(),
            ];
        })->toArray();
    }
}
