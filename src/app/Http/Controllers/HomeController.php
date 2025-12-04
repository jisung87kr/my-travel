<?php

namespace App\Http\Controllers;

use App\Enums\Region;
use App\Enums\ProductType;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        // Popular products (by booking count)
        $popularProducts = Product::with(['translations', 'images', 'prices'])
            ->active()
            ->orderByDesc('booking_count')
            ->take(8)
            ->get()
            ->map(fn ($product) => $this->formatProduct($product, $locale));

        // Products by region
        $productsByRegion = collect(Region::cases())
            ->take(6)
            ->mapWithKeys(function ($region) use ($locale) {
                $products = Product::with(['translations', 'images', 'prices'])
                    ->active()
                    ->where('region', $region)
                    ->take(4)
                    ->get()
                    ->map(fn ($product) => $this->formatProduct($product, $locale));

                return [$region->value => [
                    'name' => $region->label(),
                    'products' => $products,
                    'count' => Product::active()->where('region', $region)->count(),
                ]];
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

        return view('home', compact('popularProducts', 'productsByRegion', 'productTypes'));
    }

    private function formatProduct(Product $product, string $locale): array
    {
        $translation = $product->getTranslation($locale);
        $lowestPrice = $product->prices->where('is_active', true)->min('price');

        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'title' => $translation?->title ?? $product->getTranslation('ko')?->title ?? '',
            'description' => $translation?->short_description ?? '',
            'image' => $product->images->first()?->url ?? '/images/placeholder.jpg',
            'region' => $product->region->label(),
            'type' => $product->type->label(),
            'price' => $lowestPrice,
            'formatted_price' => $lowestPrice ? number_format($lowestPrice) : null,
            'rating' => $product->average_rating,
            'review_count' => $product->review_count,
        ];
    }
}
