<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPrice>
 */
class ProductPriceFactory extends Factory
{
    protected $model = ProductPrice::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'type' => fake()->randomElement(['adult', 'child', 'infant']),
            'label' => fake()->randomElement(['성인', '아동', '유아']),
            'price' => fake()->numberBetween(10000, 200000),
            'min_age' => null,
            'max_age' => null,
            'is_active' => true,
        ];
    }

    public function adult(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'adult',
            'label' => '성인',
            'min_age' => 13,
            'max_age' => null,
        ]);
    }

    public function child(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'child',
            'label' => '아동',
            'min_age' => 3,
            'max_age' => 12,
        ]);
    }

    public function infant(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'infant',
            'label' => '유아',
            'min_age' => 0,
            'max_age' => 2,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
