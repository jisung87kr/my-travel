<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'booking_id' => Booking::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'content' => fake()->paragraphs(2, true),
            'vendor_reply' => null,
            'replied_at' => null,
            'is_visible' => true,
        ];
    }

    public function withReply(): static
    {
        return $this->state(fn (array $attributes) => [
            'vendor_reply' => fake()->paragraph(),
            'replied_at' => now(),
        ]);
    }

    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_visible' => false,
        ]);
    }

    public function rating(int $rating): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $rating,
        ]);
    }
}
