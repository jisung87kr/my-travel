<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'content' => fake()->paragraph(),
            'read_at' => null,
        ];
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => now(),
        ]);
    }

    public function from(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'sender_id' => $user->id,
        ]);
    }

    public function to(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'receiver_id' => $user->id,
        ]);
    }
}
