<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreProductRequest;
use App\Http\Requests\Vendor\UpdateProductRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

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
