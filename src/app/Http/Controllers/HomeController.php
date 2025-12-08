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

        // Regions with product counts and images
        $regions = collect([
            Region::SEOUL,
            Region::BUSAN,
            Region::JEJU,
            Region::JEONBUK,
            Region::GYEONGBUK,
            Region::GANGWON,
        ])->map(function ($region) {
            $product = Product::with('images')
                ->active()
                ->where('region', $region)
                ->first();

            $image = $product?->images->first()?->path
                ?? 'https://placehold.co/400x400?text=' . urlencode($region->label());

            return [
                'value' => $region->value,
                'name' => $region->label(),
                'count' => Product::active()->where('region', $region)->count(),
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

        return view('home', compact('recommendedProducts', 'popularProducts', 'regions', 'productTypes'));
    }

    private function formatProduct(Product $product, string $locale): array
    {
        $translation = $product->getTranslation($locale);
        $lowestPrice = $product->prices->where('is_active', true)->min('price');
        $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();

        $isWishlisted = false;
        if (auth()->check()) {
            $isWishlisted = $product->wishlists()
                ->where('user_id', auth()->id())
                ->exists();
        }

        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'title' => $translation?->name ?? $product->getTranslation('ko')?->name ?? '',
            'location' => $product->region->label() . ', 대한민국',
            'image' => $primaryImage?->path ?? 'https://placehold.co/800x600?text=NOIMAGE',
            'region' => $product->region->label(),
            'type' => $product->type->label(),
            'price' => $lowestPrice,
            'formatted_price' => $lowestPrice ? number_format($lowestPrice) : null,
            'rating' => (float) $product->average_rating,
            'review_count' => $product->review_count,
            'reviewCount' => $product->review_count,
            'isWishlisted' => $isWishlisted,
        ];
    }
}
