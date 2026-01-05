<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingType;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Region;
use App\Models\Vendor;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function index(Request $request): View
    {
        $query = Product::with(['vendor.user', 'translations', 'images']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('translations', fn ($q) => $q->where('title', 'like', "%{$search}%"));
        }

        $products = $query->latest()->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product): View
    {
        $product->load([
            'vendor.user',
            'translations',
            'prices',
            'images' => fn ($q) => $q->orderBy('sort_order'),
            'schedules' => fn ($q) => $q->orderBy('date'),
            'reviews' => fn ($q) => $q->latest()->limit(5),
        ]);

        return view('admin.products.show', compact('product'));
    }

    public function approve(Product $product): RedirectResponse
    {
        $product->update(['status' => 'active']);

        return redirect()->back()->with('success', '상품이 승인되었습니다.');
    }

    public function reject(Request $request, Product $product): RedirectResponse
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $product->update(['status' => 'draft']);

        return redirect()->back()->with('success', '상품이 반려되었습니다.');
    }

    public function toggle(Product $product): RedirectResponse
    {
        $newStatus = $product->status === ProductStatus::ACTIVE ? ProductStatus::INACTIVE : ProductStatus::ACTIVE;
        $product->update(['status' => $newStatus]);

        $label = $newStatus === ProductStatus::ACTIVE ? '활성화' : '비활성화';

        return redirect()->back()->with('success', "상품이 {$label}되었습니다.");
    }

    public function create(): View
    {
        $locale = app()->getLocale();
        $vendors = Vendor::with('user')->where('status', 'approved')->get();
        $regions = Region::with('translations')
            ->provinces()
            ->active()
            ->ordered()
            ->get()
            ->map(fn ($r) => ['value' => $r->id, 'label' => $r->getShortName($locale)]);
        $types = collect(ProductType::cases())->map(fn ($t) => ['value' => $t->value, 'label' => $t->label()]);
        $bookingTypes = collect(BookingType::cases())->map(fn ($b) => ['value' => $b->value, 'label' => $b->label()]);
        $categories = $this->getHierarchicalCategories($locale);

        return view('admin.products.create', compact('vendors', 'regions', 'types', 'bookingTypes', 'categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $product = $this->productService->create(
            $request->validated(),
            null,
            $request->file('images') ?? []
        );

        return redirect()->route('admin.products.show', $product)
            ->with('success', '상품이 등록되었습니다.');
    }

    public function edit(Product $product): View
    {
        $product->load(['vendor.user', 'translations', 'prices', 'images', 'schedules', 'categories']);

        $locale = app()->getLocale();
        $vendors = Vendor::with('user')->where('status', 'approved')->get();
        $regions = Region::with('translations')
            ->provinces()
            ->active()
            ->ordered()
            ->get()
            ->map(fn ($r) => ['value' => $r->id, 'label' => $r->getShortName($locale)]);
        $types = collect(ProductType::cases())->map(fn ($t) => ['value' => $t->value, 'label' => $t->label()]);
        $bookingTypes = collect(BookingType::cases())->map(fn ($b) => ['value' => $b->value, 'label' => $b->label()]);
        $categories = $this->getHierarchicalCategories($locale);

        return view('admin.products.edit', compact('product', 'vendors', 'regions', 'types', 'bookingTypes', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        $this->productService->update(
            $product,
            $validated,
            $request->file('images') ?? [],
            $validated['delete_images'] ?? []
        );

        return redirect()->route('admin.products.show', $product)
            ->with('success', '상품이 수정되었습니다.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        DB::transaction(function () use ($product) {
            // 예약이 있는지 확인
            if ($product->bookings()->whereNotIn('status', ['cancelled', 'completed', 'no_show'])->count() > 0) {
                throw new \Exception('진행 중인 예약이 있는 상품은 삭제할 수 없습니다.');
            }

            // 이미지 삭제
            foreach ($product->images as $image) {
                if ($image->path) {
                    Storage::disk('public')->delete($image->path);
                }
            }

            // 상품 soft delete
            $product->delete();
        });

        return redirect()->route('admin.products.index')
            ->with('success', '상품이 삭제되었습니다.');
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
