<?php

namespace App\Http\Controllers\Traits;

use App\Models\Product;

trait FormatsProducts
{
    protected function formatProduct(Product $product, string $locale): array
    {
        $translation = $product->getTranslation($locale);
        $koTranslation = $product->getTranslation('ko');
        $lowestPrice = $product->prices->where('is_active', true)->min('price');
        $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();

        $isWishlisted = false;
        if (auth()->check()) {
            $isWishlisted = auth()->user()->wishlists()
                ->where('product_id', $product->id)
                ->exists();
        }

        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'title' => $translation?->title ?? $koTranslation?->title ?? '',
            'shortDescription' => $translation?->short_description ?? $koTranslation?->short_description ?? '',
            'description' => $translation?->description ?? '',
            'location' => $product->region->label() . ', 대한민국',
            'image' => $primaryImage?->url ?? $primaryImage?->path ?? 'https://placehold.co/800x600?text=NO+IMAGE',
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
