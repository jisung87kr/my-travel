<?php

namespace App\Http\Controllers\Vendor;

use App\Enums\ProductType;
use App\Enums\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreProductRequest;
use App\Http\Requests\Vendor\UpdateProductRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    // Web View Methods
    public function indexView(Request $request): View
    {
        $filters = $request->only(['status', 'search', 'per_page']);
        $products = $this->productService->getVendorProducts($request->user(), $filters);

        return view('vendor.products.index', compact('products'));
    }

    public function createView(): View
    {
        $regions = collect(Region::cases())->map(fn ($r) => ['value' => $r->value, 'label' => $r->label()]);
        $types = collect(ProductType::cases())->map(fn ($t) => ['value' => $t->value, 'label' => $t->label()]);

        return view('vendor.products.create', compact('regions', 'types'));
    }

    public function editView(Product $product): View
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
                'title' => $t->title,
                'short_description' => $t->short_description,
                'description' => $t->description,
                'includes' => $t->includes,
                'excludes' => $t->excludes,
            ])->toArray(),
            'prices' => [
                'adult' => $product->prices->where('price_type', 'adult')->first()?->price ?? 0,
                'child' => $product->prices->where('price_type', 'child')->first()?->price ?? 0,
            ],
            'images' => $product->images->map(fn ($i) => ['id' => $i->id, 'url' => $i->url])->toArray(),
        ];

        return view('vendor.products.edit', compact('product', 'productData', 'regions', 'types'));
    }

    public function storeWeb(StoreProductRequest $request): JsonResponse|RedirectResponse
    {
        $product = $this->productService->create(
            $request->validated(),
            $request->user()
        );

        if ($request->wantsJson()) {
            return ApiResponse::created($product, '상품이 등록되었습니다.');
        }

        return redirect()->route('vendor.products.index')
            ->with('success', '상품이 등록되었습니다.');
    }

    public function updateWeb(UpdateProductRequest $request, Product $product): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $product);

        $product = $this->productService->update($product, $request->validated());

        if ($request->wantsJson()) {
            return ApiResponse::success($product, '상품이 수정되었습니다.');
        }

        return redirect()->route('vendor.products.index')
            ->with('success', '상품이 수정되었습니다.');
    }

    // API Methods
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'type', 'per_page']);
        $products = $this->productService->getVendorProducts($request->user(), $filters);

        return ApiResponse::paginated($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create(
            $request->validated(),
            $request->user()
        );

        return ApiResponse::created($product, '상품이 등록되었습니다.');
    }

    public function show(Product $product): JsonResponse
    {
        Gate::authorize('view', $product);

        $product->load(['translations', 'prices', 'images', 'vendor']);

        return ApiResponse::success($product);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        Gate::authorize('update', $product);

        $product = $this->productService->update($product, $request->validated());

        return ApiResponse::success($product, '상품이 수정되었습니다.');
    }

    public function destroy(Product $product): JsonResponse
    {
        Gate::authorize('delete', $product);

        $this->productService->delete($product);

        return ApiResponse::deleted('상품이 삭제되었습니다.');
    }

    public function uploadImages(Request $request, Product $product): JsonResponse
    {
        Gate::authorize('update', $product);

        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $images = $this->productService->uploadImages($product, $request->file('images'));

        return ApiResponse::created($images, '이미지가 업로드되었습니다.');
    }

    public function reorderImages(Request $request, Product $product): JsonResponse
    {
        Gate::authorize('update', $product);

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:product_images,id',
        ]);

        $this->productService->reorderImages($product, $request->order);

        return ApiResponse::success(null, '이미지 순서가 변경되었습니다.');
    }

    public function deleteImage(Product $product, ProductImage $image): JsonResponse
    {
        Gate::authorize('update', $product);

        if ($image->product_id !== $product->id) {
            return ApiResponse::notFound('이미지를 찾을 수 없습니다.');
        }

        $this->productService->deleteImage($image);

        return ApiResponse::deleted('이미지가 삭제되었습니다.');
    }

    public function submitForReview(Product $product): JsonResponse
    {
        Gate::authorize('update', $product);

        $product = $this->productService->submitForReview($product);

        return ApiResponse::success($product, '상품이 승인 요청되었습니다.');
    }

    public function activate(Product $product): JsonResponse
    {
        Gate::authorize('update', $product);

        $product = $this->productService->activate($product);

        return ApiResponse::success($product, '상품이 활성화되었습니다.');
    }

    public function deactivate(Product $product): JsonResponse
    {
        Gate::authorize('update', $product);

        $product = $this->productService->deactivate($product);

        return ApiResponse::success($product, '상품이 비활성화되었습니다.');
    }
}
