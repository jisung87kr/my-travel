<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingType;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Enums\Region;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\ProductTranslation;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['vendor.user', 'translations', 'images']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('region')) {
            $query->where('region', $request->region);
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
        $product->load(['vendor.user', 'translations', 'prices', 'images', 'reviews' => fn ($q) => $q->latest()->limit(5)]);

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
        $vendors = Vendor::with('user')->where('status', 'approved')->get();
        $regions = collect(Region::cases())->map(fn ($r) => ['value' => $r->value, 'label' => $r->label()]);
        $types = collect(ProductType::cases())->map(fn ($t) => ['value' => $t->value, 'label' => $t->label()]);
        $bookingTypes = collect(BookingType::cases())->map(fn ($b) => ['value' => $b->value, 'label' => $b->label()]);

        return view('admin.products.create', compact('vendors', 'regions', 'types', 'bookingTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'type' => ['required', Rule::in(ProductType::values())],
            'region' => ['required', Rule::in(Region::values())],
            'duration' => ['nullable', 'integer', 'min:0'],
            'min_persons' => ['nullable', 'integer', 'min:1'],
            'max_persons' => ['nullable', 'integer', 'min:1'],
            'booking_type' => ['required', Rule::in(BookingType::values())],
            'meeting_point' => ['nullable', 'string', 'max:255'],
            'meeting_point_detail' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(ProductStatus::values())],
            'translations' => ['required', 'array'],
            'translations.ko.name' => ['required', 'string', 'max:255'],
            'translations.ko.description' => ['required', 'string'],
            'translations.ko.includes' => ['nullable', 'string'],
            'translations.ko.excludes' => ['nullable', 'string'],
            'translations.ko.notes' => ['nullable', 'string'],
            'prices.adult' => ['required', 'integer', 'min:0'],
            'prices.child' => ['nullable', 'integer', 'min:0'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
        ]);

        $product = DB::transaction(function () use ($validated, $request) {
            // 슬러그 생성
            $slug = Str::slug($validated['translations']['ko']['name']);
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            // 상품 생성
            $product = Product::create([
                'vendor_id' => $validated['vendor_id'],
                'slug' => $slug,
                'type' => $validated['type'],
                'region' => $validated['region'],
                'duration' => $validated['duration'] ?? null,
                'min_persons' => $validated['min_persons'] ?? 1,
                'max_persons' => $validated['max_persons'] ?? null,
                'booking_type' => $validated['booking_type'],
                'meeting_point' => $validated['meeting_point'] ?? null,
                'meeting_point_detail' => $validated['meeting_point_detail'] ?? null,
                'status' => $validated['status'],
            ]);

            // 번역 생성
            foreach ($validated['translations'] as $locale => $translation) {
                if (!empty($translation['name'])) {
                    ProductTranslation::create([
                        'product_id' => $product->id,
                        'locale' => $locale,
                        'name' => $translation['name'],
                        'description' => $translation['description'] ?? '',
                        'includes' => $translation['includes'] ?? null,
                        'excludes' => $translation['excludes'] ?? null,
                        'notes' => $translation['notes'] ?? null,
                    ]);
                }
            }

            // 가격 생성
            if (!empty($validated['prices']['adult'])) {
                ProductPrice::create([
                    'product_id' => $product->id,
                    'type' => 'adult',
                    'label' => '성인',
                    'amount' => $validated['prices']['adult'],
                ]);
            }

            if (!empty($validated['prices']['child'])) {
                ProductPrice::create([
                    'product_id' => $product->id,
                    'type' => 'child',
                    'label' => '아동',
                    'amount' => $validated['prices']['child'],
                ]);
            }

            // 이미지 업로드
            if ($request->hasFile('images')) {
                $sortOrder = 0;
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products/' . $product->id, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'url' => Storage::url($path),
                        'path' => $path,
                        'sort_order' => $sortOrder++,
                        'is_primary' => $sortOrder === 1,
                    ]);
                }
            }

            return $product;
        });

        return redirect()->route('admin.products.show', $product)
            ->with('success', '상품이 등록되었습니다.');
    }

    public function edit(Product $product): View
    {
        $product->load(['vendor.user', 'translations', 'prices', 'images']);

        $vendors = Vendor::with('user')->where('status', 'approved')->get();
        $regions = collect(Region::cases())->map(fn ($r) => ['value' => $r->value, 'label' => $r->label()]);
        $types = collect(ProductType::cases())->map(fn ($t) => ['value' => $t->value, 'label' => $t->label()]);
        $bookingTypes = collect(BookingType::cases())->map(fn ($b) => ['value' => $b->value, 'label' => $b->label()]);

        return view('admin.products.edit', compact('product', 'vendors', 'regions', 'types', 'bookingTypes'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'type' => ['required', Rule::in(ProductType::values())],
            'region' => ['required', Rule::in(Region::values())],
            'duration' => ['nullable', 'integer', 'min:0'],
            'min_persons' => ['nullable', 'integer', 'min:1'],
            'max_persons' => ['nullable', 'integer', 'min:1'],
            'booking_type' => ['required', Rule::in(BookingType::values())],
            'meeting_point' => ['nullable', 'string', 'max:255'],
            'meeting_point_detail' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(ProductStatus::values())],
            'translations' => ['required', 'array'],
            'translations.ko.name' => ['required', 'string', 'max:255'],
            'translations.ko.description' => ['required', 'string'],
            'translations.ko.includes' => ['nullable', 'string'],
            'translations.ko.excludes' => ['nullable', 'string'],
            'translations.ko.notes' => ['nullable', 'string'],
            'prices.adult' => ['required', 'integer', 'min:0'],
            'prices.child' => ['nullable', 'integer', 'min:0'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:product_images,id'],
        ]);

        DB::transaction(function () use ($validated, $request, $product) {
            // 상품 업데이트
            $product->update([
                'vendor_id' => $validated['vendor_id'],
                'type' => $validated['type'],
                'region' => $validated['region'],
                'duration' => $validated['duration'] ?? null,
                'min_persons' => $validated['min_persons'] ?? 1,
                'max_persons' => $validated['max_persons'] ?? null,
                'booking_type' => $validated['booking_type'],
                'meeting_point' => $validated['meeting_point'] ?? null,
                'meeting_point_detail' => $validated['meeting_point_detail'] ?? null,
                'status' => $validated['status'],
            ]);

            // 번역 업데이트
            foreach ($validated['translations'] as $locale => $translation) {
                if (!empty($translation['name'])) {
                    ProductTranslation::updateOrCreate(
                        ['product_id' => $product->id, 'locale' => $locale],
                        [
                            'name' => $translation['name'],
                            'description' => $translation['description'] ?? '',
                            'includes' => $translation['includes'] ?? null,
                            'excludes' => $translation['excludes'] ?? null,
                            'notes' => $translation['notes'] ?? null,
                        ]
                    );
                }
            }

            // 가격 업데이트
            ProductPrice::updateOrCreate(
                ['product_id' => $product->id, 'type' => 'adult'],
                ['label' => '성인', 'amount' => $validated['prices']['adult']]
            );

            if (!empty($validated['prices']['child'])) {
                ProductPrice::updateOrCreate(
                    ['product_id' => $product->id, 'type' => 'child'],
                    ['label' => '아동', 'amount' => $validated['prices']['child']]
                );
            } else {
                ProductPrice::where('product_id', $product->id)->where('type', 'child')->delete();
            }

            // 이미지 삭제
            if (!empty($validated['delete_images'])) {
                $imagesToDelete = ProductImage::whereIn('id', $validated['delete_images'])
                    ->where('product_id', $product->id)
                    ->get();

                foreach ($imagesToDelete as $image) {
                    if ($image->path) {
                        Storage::disk('public')->delete($image->path);
                    }
                    $image->delete();
                }
            }

            // 새 이미지 업로드
            if ($request->hasFile('images')) {
                $maxSortOrder = $product->images()->max('sort_order') ?? -1;
                $sortOrder = $maxSortOrder + 1;

                foreach ($request->file('images') as $image) {
                    $path = $image->store('products/' . $product->id, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'url' => Storage::url($path),
                        'path' => $path,
                        'sort_order' => $sortOrder++,
                        'is_primary' => $product->images()->count() === 0,
                    ]);
                }
            }
        });

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
}
