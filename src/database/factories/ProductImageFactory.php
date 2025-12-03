<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'path' => 'products/' . fake()->randomNumber(5) . '/' . fake()->uuid() . '.jpg',
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }

    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'sort_order' => 1,
        ]);
    }

    public function withOrder(int $order): static
    {
        return $this->state(fn (array $attributes) => [
            'sort_order' => $order,
        ]);
    }
}
