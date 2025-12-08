<?php

namespace App\Http\Controllers\Traveler;

use App\Enums\Region;
use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSchedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $locale = app()->getLocale();

        $query = Product::with(['translations', 'images', 'prices'])
            ->active();

        // Apply filters
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('translations', function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('min_price')) {
            $query->whereHas('prices', function ($q) use ($request) {
                $q->where('is_active', true)->where('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('prices', function ($q) use ($request) {
                $q->where('is_active', true)->where('price', '<=', $request->max_price);
            });
        }

        // Apply sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'popular':
                $query->orderByDesc('booking_count');
                break;
            case 'rating':
                $query->orderByDesc('average_rating');
                break;
            case 'price_low':
                $query->orderByRaw('(SELECT MIN(price) FROM product_prices WHERE product_prices.product_id = products.id AND is_active = 1) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('(SELECT MIN(price) FROM product_prices WHERE product_prices.product_id = products.id AND is_active = 1) DESC');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        // Format products for view
        $products->getCollection()->transform(function ($product) use ($locale) {
            return $this->formatProduct($product, $locale);
        });

        // Get filter options
        $regions = collect(Region::cases())->map(fn ($r) => [
            'value' => $r->value,
            'label' => $r->label(),
        ]);

        $types = collect(ProductType::cases())->map(fn ($t) => [
            'value' => $t->value,
            'label' => $t->label(),
        ]);

        return view('traveler.products.index', compact('products', 'regions', 'types'));
    }

    public function show(string $locale, string $product): View
    {
        // Find product by slug or ID
        $product = Product::where('slug', $product)
            ->orWhere('id', $product)
            ->firstOrFail();

        if (!$product->isActive()) {
            abort(404);
        }

        $product->load([
            'translations',
            'images' => fn ($q) => $q->orderBy('sort_order'),
            'prices' => fn ($q) => $q->where('is_active', true),
            'vendor.user',
            'reviews' => fn ($q) => $q->visible()->with(['user', 'images'])->latest()->take(10),
        ]);

        $translation = $product->getTranslation($locale);

        // Get available schedules for next 30 days
        $schedules = ProductSchedule::where('product_id', $product->id)
            ->where('date', '>=', today())
            ->where('date', '<=', today()->addDays(30))
            ->where('is_active', true)
            ->where('available_count', '>', 0)
            ->orderBy('date')
            ->get();

        // Related products (same type and region)
        $relatedProducts = Product::with(['translations', 'images', 'prices'])
            ->active()
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('type', $product->type)
                    ->orWhere('region', $product->region);
            })
            ->take(4)
            ->get()
            ->map(fn ($p) => $this->formatProduct($p, $locale));

        return view('traveler.products.show', compact('product', 'translation', 'schedules', 'relatedProducts'));
    }

    private function formatProduct(Product $product, string $locale): array
    {
        $translation = $product->getTranslation($locale);
        $lowestPrice = $product->prices->where('is_active', true)->min('price');
        $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();

        // Check if product is wishlisted by current user
        $isWishlisted = false;
        if (auth()->check()) {
            $isWishlisted = auth()->user()->wishlists()
                ->where('product_id', $product->id)
                ->exists();
        }

        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'title' => $translation?->name ?? $product->getTranslation('ko')?->name ?? '',
            'description' => $translation?->description ?? '',
            'image' => $primaryImage?->path ?? 'https://placehold.co/800x600?text=NO+IMAGE',
            'region' => $product->region->label(),
            'region_value' => $product->region->value,
            'type' => $product->type->label(),
            'type_value' => $product->type->value,
            'price' => $lowestPrice,
            'formatted_price' => $lowestPrice ? number_format($lowestPrice) : null,
            'rating' => (float) $product->average_rating,
            'review_count' => $product->review_count,
            'reviewCount' => $product->review_count,
            'url' => route('products.show', ['locale' => $locale, 'product' => $product->slug]),
            'isWishlisted' => $isWishlisted,
        ];
    }
}
