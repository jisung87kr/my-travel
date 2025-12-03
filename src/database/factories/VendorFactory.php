<?php

namespace Database\Factories;

use App\Enums\VendorStatus;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_name' => fake()->company(),
            'business_number' => fake()->numerify('###-##-#####'),
            'contact_phone' => fake()->phoneNumber(),
            'contact_email' => fake()->companyEmail(),
            'description' => fake()->paragraph(),
            'status' => VendorStatus::PENDING,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VendorStatus::APPROVED,
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VendorStatus::REJECTED,
            'rejection_reason' => fake()->sentence(),
        ]);
    }
}
