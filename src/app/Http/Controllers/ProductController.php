<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'region',
            'type',
            'date',
            'keyword',
            'min_price',
            'max_price',
            'sort_by',
            'sort_order',
            'per_page',
        ]);

        $products = $this->productService->search($filters);

        return ApiResponse::paginated($products);
    }

    public function show(Product $product): JsonResponse
    {
        if (!$product->isActive()) {
            return ApiResponse::notFound('상품을 찾을 수 없습니다.');
        }

        $product->load([
            'translations',
            'prices' => fn($q) => $q->active(),
            'images',
            'vendor.user',
        ]);

        return ApiResponse::success($product);
    }
}
