<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSchedule>
 */
class ProductScheduleFactory extends Factory
{
    protected $model = ProductSchedule::class;

    public function definition(): array
    {
        $totalCount = fake()->numberBetween(5, 20);

        return [
            'product_id' => Product::factory(),
            'date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'start_time' => fake()->time('H:i'),
            'total_count' => $totalCount,
            'available_count' => $totalCount,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function soldOut(): static
    {
        return $this->state(fn (array $attributes) => [
            'available_count' => 0,
        ]);
    }

    public function forDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => $date,
        ]);
    }
}
