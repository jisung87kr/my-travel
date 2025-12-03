<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Product;
use App\Models\ProductSchedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $adultCount = fake()->numberBetween(1, 4);
        $childCount = fake()->numberBetween(0, 2);

        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'schedule_id' => ProductSchedule::factory(),
            'status' => BookingStatus::PENDING,
            'adult_count' => $adultCount,
            'child_count' => $childCount,
            'infant_count' => 0,
            'total_price' => fake()->numberBetween(50000, 500000),
            'special_request' => fake()->optional()->sentence(),
            'contact_name' => fake()->name(),
            'contact_phone' => fake()->phoneNumber(),
            'contact_email' => fake()->email(),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::CONFIRMED,
            'confirmed_at' => now(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::COMPLETED,
            'confirmed_at' => now()->subDays(7),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::CANCELLED,
            'cancelled_at' => now(),
            'cancellation_reason' => fake()->sentence(),
        ]);
    }

    public function noShow(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::NO_SHOW,
        ]);
    }

    public function forProduct(Product $product): static
    {
        return $this->state(fn (array $attributes) => [
            'product_id' => $product->id,
        ]);
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
