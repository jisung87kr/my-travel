<?php

namespace Database\Factories;

use App\Enums\BookingType;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Enums\Region;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'vendor_id' => Vendor::factory(),
            'type' => fake()->randomElement(ProductType::cases()),
            'region' => fake()->randomElement(Region::cases()),
            'duration' => fake()->numberBetween(60, 480),
            'booking_type' => fake()->randomElement(BookingType::cases()),
            'status' => ProductStatus::DRAFT,
            'average_rating' => null,
            'review_count' => 0,
            'booking_count' => 0,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProductStatus::ACTIVE,
        ]);
    }

    public function instant(): static
    {
        return $this->state(fn (array $attributes) => [
            'booking_type' => BookingType::INSTANT,
        ]);
    }

    public function request(): static
    {
        return $this->state(fn (array $attributes) => [
            'booking_type' => BookingType::REQUEST,
        ]);
    }

    public function dayTour(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ProductType::DAY_TOUR,
        ]);
    }

    public function withRating(float $rating, int $count = 10): static
    {
        return $this->state(fn (array $attributes) => [
            'average_rating' => $rating,
            'review_count' => $count,
        ]);
    }
}
