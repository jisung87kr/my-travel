<?php

namespace App\Http\Controllers;

use App\Enums\Region;
use App\Enums\ProductType;
use App\Http\Controllers\Traits\FormatsProducts;
use App\Models\Product;
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

            $imagePath = $product?->images->first()?->path;
            $image = $imagePath
                ? Storage::disk('public')->url($imagePath)
                : 'https://placehold.co/400x400?text=' . urlencode($region->label());

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

        // Recent reviews for testimonials
        $reviews = Review::with(['user', 'product.translations'])
            ->visible()
            ->whereHas('product', fn ($q) => $q->active())
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('home', compact('recommendedProducts', 'popularProducts', 'regions', 'productTypes', 'reviews'));
    }
}
