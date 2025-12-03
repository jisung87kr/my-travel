<?php

namespace Database\Factories;

use App\Enums\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'phone' => fake()->optional()->phoneNumber(),
            'avatar' => null,
            'preferred_language' => Language::KO,
            'provider' => null,
            'provider_id' => null,
            'no_show_count' => 0,
            'is_blocked' => false,
            'blocked_at' => null,
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function blocked(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_blocked' => true,
            'blocked_at' => now(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
