<?php

namespace App\Http\Controllers;

use App\Enums\ProductType;
use App\Http\Controllers\Traits\FormatsProducts;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Region;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomeController extends Controller
{
    use FormatsProducts;

    public function index(): View
    {
        $locale = app()->getLocale();

        // Recommended products (8 items for 4-column grid)
        $recommendedProducts = Product::with(['translations', 'images', 'prices'])
            ->active()
            ->orderByDesc('average_rating')
            ->take(8)
            ->get()
            ->map(fn ($product) => $this->formatProduct($product, $locale));

        // Popular products (by booking count, for horizontal scroll)
        $popularProducts = Product::with(['translations', 'images', 'prices'])
            ->active()
            ->orderByDesc('booking_count')
            ->take(5)
            ->get()
            ->map(fn ($product) => $this->formatProduct($product, $locale));

        // Regions with product counts and images (featured provinces)
        $regions = Region::with(['translations', 'images'])
            ->provinces()
            ->featured()
            ->active()
            ->ordered()
            ->withProductCount()
            ->get()
            ->map(function ($region) use ($locale) {
                $product = Product::with('images')
                    ->active()
                    ->where('region_id', $region->id)
                    ->first();

                $imagePath = $region->getPrimaryImage()?->path ?? $product?->images->first()?->path;
                $image = $imagePath
                    ? Storage::disk('public')->url($imagePath)
                    : 'https://placehold.co/400x400?text=' . urlencode($region->getShortName($locale));

                return [
                    'id' => $region->id,
                    'value' => $region->code,
                    'name' => $region->getShortName($locale),
                    'count' => $region->products_count ?? 0,
                    'image' => $image,
                ];
            });

        // Product types with counts
        $productTypes = collect(ProductType::cases())
            ->map(function ($type) {
                return [
                    'value' => $type->value,
                    'label' => $type->label(),
                    'count' => Product::active()->where('type', $type)->count(),
                ];
            });

        // Recent reviews for testimonials
        $reviews = Review::with(['user', 'product.translations'])
            ->visible()
            ->whereHas('product', fn ($q) => $q->active())
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // Top-level product categories for navigation
        $categories = ProductCategory::with('translations')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('home', compact('recommendedProducts', 'popularProducts', 'regions', 'productTypes', 'reviews', 'categories'));
    }
}
