<?php

namespace App\Http\Controllers\Vendor;

use App\Enums\ProductType;
use App\Enums\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreProductRequest;
use App\Http\Requests\Vendor\UpdateProductRequest;
use App\Models\Product;
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
        $regions = collect(Region::cases())->map(fn ($r) => ['value' => $r->value, 'label' => $r->label()]);
        $types = collect(ProductType::cases())->map(fn ($t) => ['value' => $t->value, 'label' => $t->label()]);

        return view('vendor.products.create', compact('regions', 'types'));
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

        $product->load(['translations', 'prices', 'images']);

        $regions = collect(Region::cases())->map(fn ($r) => ['value' => $r->value, 'label' => $r->label()]);
        $types = collect(ProductType::cases())->map(fn ($t) => ['value' => $t->value, 'label' => $t->label()]);

        $productData = [
            'type' => $product->type->value,
            'region' => $product->region->value,
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

        return view('vendor.products.edit', compact('product', 'productData', 'regions', 'types'));
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
}
